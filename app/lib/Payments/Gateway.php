<?php 
namespace Payments;


/**
 * Gateway
 *
 * This class has only one method
 * and it's being used to choose proper payment gateway
 */
class Gateway
{
    /**
     * Gateway
     * @param [type] $gw [description]
     */
    public static function choose($gw = null)
    {
        $gateways = np_get_payment_gateways();

        if (!isset($gateways[$gw])) {
            return false;
        }

        if (is_array($gateways[$gw])) {
            require_once $gateways[$gw][0];
            $instance = $gateways[$gw][1];
        } else {
            $instance = $gateways[$gw];
        }

        return new $instance;
    }
}
