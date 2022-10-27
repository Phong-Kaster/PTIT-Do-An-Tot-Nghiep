<?php 
/**
 * Register a new directory for autoloading the library files
 * @param  string $dir Path to the directory
 * @return bool      
 */
function np_register_autoload_dir($dir)
{
    if (!$dir) {
        return false;
    }

    // instantiate the loader
    $loader = new Autoloader;

    // register the autoloader
    $loader->register();

    // register the base directory for auto loading
    $loader->addBaseDir($dir);

    return true;
}

/**
 * Registers a new payment gateway to the system
 * @param  array  $pg An assosiative array with lenght 3. 
 *                    Ex:
 *                    ["idname" => "paypal",
 *                      "controller" => "PaymentGateway",
 *                      "controller_path" => "/path/to/PaymentGateway.php"
 *                     ]
 * @return null    
 */
function np_register_payment_gateway($pg = []) 
{
    if (!isset($GLOBALS["PaymentGateways"]) || 
        !is_array($GLOBALS["PaymentGateways"])) 
    {
        $GLOBALS["PaymentGateways"] = [];    
    }

    if (!is_array($pg) || !isset($pg["idname"], $pg["controller"], $pg["controller_path"])) {
        return null;
    }

    $GLOBALS["PaymentGateways"][] = $pg;
}

/**
 * Get the list of all available payment gateways (including built in pgs)
 * @return array An assosiative array of the loaded pgs
 *               Format:
 *               ["pg_idname" => "Controller",
 *                "pg_idname" => ["/path/to/controller/file", "controller"]]
 */
function np_get_payment_gateways()
{
    $gateways = [
        "stripe" => "\Payments\Stripe",
        "paypal" => "\Payments\Paypal"
    ];

    if (is_array($GLOBALS["PaymentGateways"])) {
        foreach ($GLOBALS["PaymentGateways"] as $pg) {
            $gateways[$pg["idname"]] = [
                $pg["controller_path"],
                $pg["controller"],
            ];
        }
    }

    return $gateways;
}
