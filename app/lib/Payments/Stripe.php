<?php 
namespace Payments;



/**
 * Stripe Payment Gateway
 */
class Stripe extends AbstractGateway
{   

    private $integrations;

    /**
     * Init.
     */
    public function __construct()
    {
        $this->integrations = \Controller::model("GeneralData", "integrations");
        \Stripe\Stripe::setApiKey($this->integrations->get("data.stripe.secret_key"));
        \Stripe\Stripe::setApiVersion("2018-05-21");
    }


    /**
     * Place Order
     *
     * Generate payment page url here and return it
     * @return string URL of the payment page
     */
    public function placeOrder($params = [])
    {
        $Order = $this->getOrder();
        if (!$Order) {
            throw new \Exception('Set order before calling AbstractGateway::placeOrder()');
        }

        if (!in_array($Order->get("status"), ["payment_processing", "subscription_processing"])) {
            throw new \Exception('Invalid order status');
        }

        $User = $this->getUser();
        if (!$User->isAvailable() || !$User->get("is_active")) {
            throw new \Exception('User is not available or active');
        }

        if ($Order->get("data.is_subscription")) {
            // Recurring payments
            return $this->placeRecurringOrder($params);
        } else {
            return $this->placeOnetimeOrder($params);
        }
    }


    /**
     * Handle recurring payments
     * @param  array  $params 
     * @return string URL of the payment page
     */
    private function placeRecurringOrder($params = [])
    {
        $Order = $this->getOrder();
        $User = $this->getUser();

        // Check if the order currency is zero decimal currency
        $iszdc = isZeroDecimalCurrency($Order->get("currency"));

        if (empty($params["token"])) {
            $Order->delete();
            throw new \Exception('Token is required');
        }
        $token = $params["token"];

        if ($User->get("data.recurring_payments.subscribed")) {
            $Order->delete();
            throw new \Exception('User is already subscribed for the recurring payments');
        }


        // Check the subscription just in case
        $subscription_id = $User->get("data.recurring_payments.stripe.subscription_id");
        if ($subscription_id) {
            try {
                $subscription = \Stripe\Subscription::retrieve($subscription_id);
            } catch (\Exception $e) {
                // Not found, expected result
            }

            if (isset($subscription->status) && $subscription->status == "active") {
                // This is unexpected, data modified by external factors
                // Prevent order
                $Order->delete();
                throw new \Exception("User is already subscribed for the recurring payments");
            }

            $subscription_id = false;
        }


        // Get-Create Customer
        $customer_id = $User->get("data.recurring_payments.stripe.customer_id");
        if ($customer_id) {
            // Retrieve customer data
            try {
                $customer = \Stripe\Customer::retrieve($customer_id);
            } catch (\Exception $e) {
                // Couldn't retrieve the customer data
                // Assume that it's new customer
                $customer_id = null;
            }

            if (!empty($customer->id)) {
                $update = false;
                if ($customer->email != $User->get("email")) {
                    // Email has been changed since last subscription
                    // Update the customer
                    $customer->email = $User->get("email");
                    $update = true;
                }

                if (isset($customer->metadata->user_id) && $customer->metadata->user_id != $User->get("id")) {
                    $customer->metadata->user_id = $User->get("id");
                    $update = true;
                }

                if ($update) {
                    $customer->save();
                }
            } 
        }

        if (!$customer_id) {
            // Create new customer
            try {
                $customer = \Stripe\Customer::create([
                    "source" => $token,
                    "email" => $User->get("email"),
                    "metadata" => [
                        "user_id" => $User->get("id")
                    ]
                ]);
            } catch (\Exception $e) {
                // Couldn't create the new customer
                $Order->delete();
                throw new \Exception("Couldn't create the new customer");
            }

            if (empty($customer->id)) {
                $Order->delete();
                throw new \Exception("Couldn't create the new customer");
            } 

            $customer_id = $customer->id;
            $User->set("data.recurring_payments.stripe.customer_id", $customer_id)
                 ->save();
        }


        // Get-Create Subscription Plan
        $plan_id = "plan"
                 . "-" . $Order->get("data.package.id")
                 . "-" . $Order->get("data.plan")
                 . "-" . ($iszdc ? round($Order->get("total")) : $Order->get("total") * 100)
                 . "-" . strtolower($Order->get("currency"));
        try {
            $plan = \Stripe\Plan::retrieve($plan_id);
        } catch (\Exception $e) {
            // Couldnt' retreive the plan,
            // Probably not exists yet
        }

        if (empty($plan)) {
            // Create new plan
            try {
                $plan = \Stripe\Plan::create([
                    "id" => $plan_id,
                    "amount" => $iszdc ? round($Order->get("total")) : $Order->get("total") * 100,
                    "interval" => $Order->get("data.plan") == "annual" ? "year" : "month",
                    "product" => [
                        "name" => $Order->get("data.package.title")
                                . " - " 
                                . ($Order->get("data.plan") == "annual" ? "Annual" : "Monthly")
                    ],
                    "currency" => $Order->get("currency")
                ]);
            } catch (\Exception $e) {
                // Couldn't create a new plan
                // Something is wrong
                $Order->delete();
                throw new \Exception("Couldn't create the new plan");
            }
        }



        // Create subscription
        $trial_end = strtotime($User->get("expire_date"));
        if ($trial_end > time() + 5*365*86400) {
            $Order->delete();
            throw new \Exception(__("This is account will already be active for at least 5 years. <br> It's not possible to create a new payment subscription for this account."));
        }

        $ActivePackage = \Controller::model("Package", $User->get("package_id"));
        if (!$ActivePackage->isAvailable() || $trial_end < time()) {
            // User package is not available,
            // User is either is in trial mode or subscribing to the new package
            // 
            // Or user has subscribed to the package 
            // but not made a payment after the expire date
            $trial_end = "now";
        }

        try {
            $subscription = \Stripe\Subscription::create([
                "customer" => $customer_id,
                "items" => [
                    ["plan" => $plan_id],
                ],
                "trial_end" => $trial_end,
                "metadata" => [
                    "order_id" => $Order->get("id"),
                    "user_id" => $User->get("id")
                ]
            ]);
        } catch (\Exception $e) {
            // Couldn't create the subscription
            $Order->delete();
            throw new \Exception($e->getMessage());
        }

        if (empty($subscription->id)) {
            // Couldn't create the subscription
            $Order->delete();
            throw new \Exception("Couldn't create the new subscription");
        }
        $subscription_id = $subscription->id;

        $Order->set("status","subscribed")
              ->set("payment_id", $subscription->id)
              ->update();

        $User->set("data.recurring_payments.subscribed", true)
             ->set("data.recurring_payments.gateway", "stripe")
             ->set("data.recurring_payments.stripe.subscription_id", $subscription_id)
             ->save();

        $url = APPURL."/checkout/"
             . $Order->get("id") . "." .sha1($Order->get("id").NP_SALT)
             . "?paymentId=" . $subscription_id;

        return $url;
    }


    /**
     * Handle one time payment
     * @param  array  $params 
     * @return string URL of the payment page
     */
    private function placeOnetimeOrder($params = [])
    {
        $Order = $this->getOrder();

        // Check if the order currency is zero decimal currency
        $iszdc = isZeroDecimalCurrency($Order->get("currency"));

        if (empty($params["token"])) {
            throw new \Exception('Token is required');
        }
        $token = $params["token"];

        try {
            $Charge = \Stripe\Charge::create([
                "amount" => $iszdc ? round($Order->get("total")) : $Order->get("total") * 100,
                "currency" => $Order->get("currency"),
                "source" => $token,
                "description" => "Payment for Order #".$Order->get("id"),
                "metadata" => [
                    "order_id" => $Order->get("id")
                ]
            ]);

            $resp = json_decode($Charge->getLastResponse()->body);

            if (isset($resp->id)) {
                // Payment finished,
                // Finish order processing
                $Order->finishProcessing();

                // Updating order...
                $Order->set("status","paid")
                      ->set("payment_id", $resp->id)
                      ->set("paid", $iszdc ? $resp->amount : ($resp->amount / 100))
                      ->update();

                try {
                    // Send notification emails to admins
                    \Email::sendNotification("new-payment", ["order" => $Order]);
                } catch (\Exception $e) {
                    // Failed to send notification email to admins
                    // Do nothing here, it's not critical error
                }

                $url = APPURL."/checkout/"
                     . $Order->get("id") . "." .sha1($Order->get("id").NP_SALT)
                     . "?paymentId=".$Order->get("payment_id");
            } else {
                $Order->delete();
                $url = APPURL."/checkout/error";
            }
        } catch (\Exception $e) {
            $Order->delete();
            $url = APPURL."/checkout/error";
        }

        return $url;
    }




    /**
     * Payment callback 
     * @return boolean [description]
     */
    public function callback($params = [])
    {
        // Payment processing has already been finished
        // Order processing has already been finished
        // Just check paymentId for URL validation

        if (empty($params["paymentId"])) {
            throw new \Exception(__('System detected logical error').": invalid_payment_id");
        }

        $paymentId = $params["paymentId"];

        $Order = $this->getOrder();
        if (!$Order) {
            throw new \Exception('Set order before calling AbstractGateway::placeOrder()');
        }

        if ($Order->get("payment_id") != $paymentId) {
            throw new \Exception(__("Couldn't get payment information")); 
        }

        if (!in_array($Order->get("status"), ["paid", "subscribed"])) {
            return false;
        }

        return true;
    }



    /**
     * Cancel payment subscription for the $User
     * @param  \UserModel $User 
     * @return bool           
     */
    public function cancelSubscription(\UserModel $User)
    {
        $subscription_id = $User->get("data.recurring_payments.stripe.subscription_id");
        try {
            $sub = \Stripe\Subscription::retrieve($subscription_id);
            $sub->cancel();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if ($User->get("data.recurring_payments.gateway") == "stripe") {
            $User->set("data.recurring_payments.subscribed", false)->update();
        }

        if (isset($sub->metadata->order_id)) {
            $Order = \Controller::model("Order", $sub->metadata->order_id);

            if ($Order->isAvailable() && $Order->get("payment_id") == $subscription_id) {
                $Order->set("status", "unsubscribed")
                      ->save();
            }
        }

        return true;
    }



    /**
     * Retrieve the payment subscription data for the $User
     * @param  \UserModel $User 
     * @return mixed           
     */
    public function retrieveSubscription(\UserModel $User)
    {
        if (!$User->get("data.recurring_payments.subscribed")) {
            return null;
        }

        $subscription_id = $User->get("data.recurring_payments.stripe.subscription_id");
        if (!$subscription_id) {
            return null;
        }

        try {
            $subscription = \Stripe\Subscription::retrieve($subscription_id);

            if (in_array($subscription->status, ["active", "trialing"])) {
                return $subscription;
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return null;
    }


    /**
     * Callback function for the recurring payments
     * @return null 
     */
    public function webhook()
    {
        // Retrieve the request's body and parse it as JSON
        $payload = @file_get_contents("php://input");
        $event_json = json_decode($payload);


        // Get webhook secret key
        $webhook_key = $this->integrations->get("data.stripe.webhook_key");

        if (!isset($_SERVER["HTTP_STRIPE_SIGNATURE"])) {
            // Couldn't get verification signatures
            http_response_code(400);
            echo "No direct access";
            exit;
        }

        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;

        try {
          $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $webhook_key
          );
        } catch(\UnexpectedValueException $e) {
          // Invalid payload
          http_response_code(400);
          exit;
        } catch(\Stripe\Error\SignatureVerification $e) {
          // Invalid signature
          http_response_code(400);
          exit;
        }

        switch ($event->type) {
            case "invoice.payment_succeeded":
                $this->whInvoicePaymentSucceeded($event);
                break;

            case "customer.subscription.deleted":
                $this->whSubscriptionDeleted($event);
                break;
            
            default:
                break;
        }

        http_response_code(200);
    }


    /**
     * WebHook: charge.succeeded
     * @param  \Stripe\Event $event 
     * @return null        
     */
    private function whInvoicePaymentSucceeded($event)
    {
        $eventobj = $event->data->object;

        if (empty($eventobj->charge)) {
            // Invalid charge id
            http_response_code(400);
            exit;
        }

        if (empty($eventobj->subscription)) {
            // Invalid subscription id
            http_response_code(400);
            exit;
        }

        $subscription_id = $eventobj->subscription;
        try {
            $subscription = \Stripe\Subscription::retrieve($subscription_id);
        } catch (\Exception $e) {
            // Couldn't get subscription data
            http_response_code(400);
            exit;
        }

        if (empty($subscription->metadata->order_id)) {
            // Invalid subscription data
            http_response_code(400);
            exit;
        }

        $subscription_order_id = $subscription->metadata->order_id;
        $Order = \Controller::model("Order", $subscription_order_id);
        if (!$Order->isAvailable() || $Order->get("status") != "subscribed") {
            // Invalid order
            http_response_code(400);
            exit;
        }

        // Check if the order currency is zero decimal currency
        $iszdc = isZeroDecimalCurrency($Order->get("currency"));

        $order_data = json_decode($Order->get("data"));
        $order_data->is_subscription = false;
        $order_data->is_subscription_payment = true;


        $NewOrder = \Controller::model("Order", $eventobj->charge);
        if ($NewOrder->isAvailable()) {
            // event already handled
            return;    
        }

        $NewOrder->set("user_id", $Order->get("user_id"))
                 ->set("data", json_encode($order_data))
                 ->set("status", "paid")
                 ->set("payment_gateway", $Order->get("payment_gateway"))
                 ->set("payment_id", $eventobj->charge)
                 ->set("total", $Order->get("total"))
                 ->set("paid", $iszdc ? $eventobj->amount_paid : ($eventobj->amount_paid / 100))
                 ->set("currency", $Order->get("currency"))
                 ->save();

        $NewOrder->finishProcessing();

        try {
            // Send notification emails to admins
            \Email::sendNotification("new-payment", ["order" => $Order]);
        } catch (\Exception $e) {
            // Failed to send notification email to admins
            // Do nothing here, it's not critical error
        }
    }


    /**
     * WebHook: customer.subscription.deleted
     * @param  \Stripe\Event $event 
     * @return null        
     */
    private function whSubscriptionDeleted($event)
    {
        $eventobj = $event->data->object;
        $subscription_id = $eventobj->id;


        try {
            $subscription = \Stripe\Subscription::retrieve($subscription_id);
        } catch (\Exception $e) {
            // Couldn't get subscription data
            http_response_code(400);
            exit;
        }

        if (empty($subscription->metadata->user_id) || 
            empty($subscription->metadata->order_id)) {
            // Invalid data
            http_response_code(400);
            exit;
        }

        $user_id = $subscription->metadata->user_id;
        $User = \Controller::model("User", $user_id);

        if ($User->get("data.recurring_payments.gateway") == "stripe") {
            $User->set("data.recurring_payments.subscribed", false)->update();
        }

        $order_id = $subscription->metadata->order_id;
        $Order = \Controller::model("Order", $order_id);
        
        if ($Order->isAvailable() && $Order->get("payment_id") == $subscription_id) {
            $Order->set("status", "unsubscribed")
                  ->save();
        }
    }
}
