<?php
/**
 * Profile Controller
 */
class ProfileController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $AuthUser = $this->getVariable("AuthUser");
        $Route = $this->getVariable("Route");

        if (!$AuthUser){
            // Auth
            header("Location: ".APPURL."/login");
            exit;
        } else {
            if (\Input::post("action") == "cancel-recurring") {
                // Cancel recurring payment subscption
                $this->cancelRecurringPayments();
            }
        } 

        // Get Package
        $Package = Controller::model("Package", $AuthUser->get("package_id"));
        $EmailSettings = \Controller::model("GeneralData", "email-settings");

        $this->setVariable("TimeZones", getTimezones())
             ->setVariable("Package", $Package)
             ->setVariable("EmailSettings", $EmailSettings);

        if (Input::post("action") == "save") {
            $this->save();
        } else if (Input::post("action") == "resend-email") {
            $this->resendVerificationEmail();
        } else {
            // Check recurring payments
            $this->checkRecurringPayments();
        }
        $this->view("profile");
    }


    /**
     * Check if user is subscribed to recurring payments
     * @return [type] [description]
     */
    private function checkRecurringPayments()
    {
        $AuthUser = $this->getVariable("AuthUser");
        $recurring = false;
        $gateway = $AuthUser->get("data.recurring_payments.gateway");

        $PaymentGateway = \Payments\Gateway::choose($gateway);
        if ($PaymentGateway && method_exists($PaymentGateway, "retrieveSubscription")) {
            try {
                $subscription = $PaymentGateway->retrieveSubscription($AuthUser);
                if ($subscription) {
                    $recurring = true;

                    $this->setVariable("recurring_gateway", $gateway)
                         ->setVariable("recurring_subscription", $subscription);
                }
            } catch (\Exception $e) {
                // Couldn't retrieve the subscription data
                // Might be invalid subscription id
            }
        }

        $this->setVariable("recurring_payments", $recurring);
    }


    /**
     * Save changes
     * @return void 
     */
    private function save()
    {
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");
        $EmailSettings = $this->getVariable("EmailSettings");


        // Check required fields
        $required_fields = ["email", "firstname", "lastname"];

        foreach ($required_fields as $field) {
            if (!Input::post($field)) {
                $this->resp->msg = __("Missing some of required data.");
                $this->jsonecho();
            }
        }

        $email = strtolower(Input::post("email"));

        // Check email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->resp->msg = __("Email is not valid.");
            $this->jsonecho();
        }

        $u = Controller::model("User", $email);
        if ($u->isAvailable() && $u->get("id") != $AuthUser->get("id")) {
            $this->resp->msg = __("Email is not available.");
            $this->jsonecho();
        }

        // Check if email changed
        // Verification email must be send if email changed
        $email_changed = $email == $AuthUser->get("email") ? false : true;

        // Check pass.
        if (mb_strlen(Input::post("password")) > 0) {
            if (mb_strlen(Input::post("password")) < 6) {
                $this->resp->msg = __("Password must be at least 6 character length!");
                $this->jsonecho();
            } 

            if (Input::post("password-confirm") != Input::post("password")) {
                $this->resp->msg = __("Password confirmation didn't match!");
                $this->jsonecho();
            }
        }

        // Start setting data
        $AuthUser->set("firstname", Input::post("firstname"))
                 ->set("lastname", Input::post("lastname"))
                 ->set("email", Input::post("email"));


        // Preferences
        $preferences = [];
        $preferences["timezone"] = Input::post("timezone");
        if (!in_array($preferences["timezone"], DateTimeZone::listIdentifiers())) {
            $preferences["timezone"] = "UTC";
        } 

        $valid_date_formats = [
            "Y-m-d", "d-m-Y", "d/m/Y", "m/d/Y",
            "d F, Y", "F d, Y", "d M, Y", "M d, Y"
        ];
        $preferences["dateformat"] = Input::post("date-format");
        if (!in_array($preferences["dateformat"], $valid_date_formats)) {
            $preferences["dateformat"] = $valid_date_formats[0];
        }

        $preferences["timeformat"] = Input::post("time-format") == "24"
                                ? "24" : "12";

        $language = Config::get("default_applang");
        foreach (Config::get("applangs") as $al) {
            if ($al["code"] == Input::post("language")) {
                $language = Input::post("language");
                break;
            }
        }
        $preferences["language"] = $language;

        $AuthUser->set("preferences", json_encode($preferences));

        if (mb_strlen(Input::post("password")) > 0) {
            $passhash = password_hash(Input::post("password"), PASSWORD_DEFAULT);
            $AuthUser->set("password", $passhash);
        }

        $AuthUser->save();

        // update cookies
        setcookie("nplh", $AuthUser->get("id").".".md5($AuthUser->get("password")), 0, "/");


        // Send verification email if the email changed
        // and email verification setting is ON
        if ($email_changed && $EmailSettings->get("data.email_verification")) {
            $AuthUser->sendVerificationEmail(true);
        }


        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();
    }


    /**
     * Cancel Recurring payments
     */
    private function cancelRecurringPayments()
    {
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");
        $gateway = $AuthUser->get("data.recurring_payments.gateway");

        $PaymentGateway = \Payments\Gateway::choose($gateway);
        if ($PaymentGateway && method_exists($PaymentGateway, "cancelSubscription")) {
            try {
                if ($gateway == "stripe") {
                    $PaymentGateway->cancelSubscription($AuthUser);
                }
                
                $AuthUser->refresh();
            } catch (\Exception $e) {
                $this->resp->msg = $e->getMessage();
                $this->jsonecho();
            }
        }
        
        $this->resp->result = 1;
        $this->jsonecho();
    }


    /**
     * Resend the email to verify the email
     * @return void 
     */
    private function resendVerificationEmail()
    {
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");
        $EmailSettings = $this->getVariable("EmailSettings");

        if ($EmailSettings->get("data.email_verification")) {
            // Don't force to create new hash for the verification
            // Send the link with same hash if the hash is available
            $AuthUser->sendVerificationEmail(false);
        }

        $this->resp->result = 1;
        $this->resp->msg = __("Email sent!");
        $this->jsonecho();
    }
}