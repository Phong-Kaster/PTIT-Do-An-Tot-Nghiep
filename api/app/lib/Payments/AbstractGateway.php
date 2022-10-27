<?php 
namespace Payments;

/**
 * Abstract Payment Gateway
 * All payment gateways must extend this class
 */
abstract class AbstractGateway 
{
    /**
     * Order
     * @var OrderModel
     */
    private $order;

    /**
     * Order payer user account
     * @var UserModel
     */
    private $user = null;


    /**
     * Set order
     * @param OrderModel $order 
     */
    public function setOrder($order)
    {
        if (!is_a($order, "\OrderModel")) {
            throw new \Exception("Order must be instance of OrderModel");
        }

        if (!$order->isAvailable()) {
            throw new \Exception("Order is not available!");
        }

        $this->order = $order;
        return $this;
    }


    /**
     * Get Order
     * @return OrderModel 
     */
    public function getOrder()
    {
        return $this->order;
    }


    /**
     * Get order payer user account
     * @param  bool $force_new If true, then get new data
     * @return UserModel            
     */
    public function getUser($force_new = false)
    {
        if ($force_new || is_null($this->user)) {
            $Order = $this->getOrder();
            if (!$Order) {
                throw new \Exception('Set order before calling AbstractGateway::getUser()');
            }

            $User = \Controller::model("User", $Order->get("user_id"));
            $this->user = $User;
        }

        return $this->user;
    }


    /**
     * First connection with payment gateway
     *
     * For external payment gateways (ex: paypal)
     * Generate payment page url here and return it
     *
     * For inline payments (ex: credit card)
     * Process payment, comlete order processing, generate the URL for result page and return it
     * 
     * @return string URL of the page
     */
    abstract public function placeOrder($params = []);


    /**
     * Complete the processing of Order
     * Must be called on checkout result page
     *
     * For credit
     * @param  array    $params [description]
     * @return function         [description]
     */
    abstract public function callback($params = []);
}
