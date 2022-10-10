<?php
/**
 * Settings Controller
 */
class SettingsController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $AuthUser = $this->getVariable("AuthUser");
        $Route = $this->getVariable("Route");

        if (!$AuthUser || !$AuthUser->isAdmin()){
            header("Location: ".APPURL."/login");
            exit;
        } else if ($AuthUser->isExpired()) {
            header("Location: ".APPURL."/expired");
            exit;
        }

        require_once(APPPATH.'/inc/currencies.inc.php');
        $page = isset($Route->params->page) ? $Route->params->page : "site";

        $this->setVariable("Settings", Controller::model("GeneralData", "settings"))
             ->setVariable("Integrations", Controller::model("GeneralData", "integrations"))
             ->setVariable("EmailSettings", Controller::model("GeneralData", "email-settings"))
             ->setVariable("page", $page)
             ->setVariable("Currencies", $Currencies);

        if (Input::post("action") == "save") {
            $this->save();
        }
        $this->view("settings");
    }


    /**
     * Save changes
     * @return boolean 
     */
    private function save()
    {
        $page = $this->getVariable("page");

        $method = "save";
        $parts = explode("-", $page);
        foreach ($parts as $p) {
            $method .= ucfirst(strtolower($p));
        }

        return $this->$method();
    }


    /**
     * Save site settings
     * @return boolean 
     */
    private function saveSite()
    {  
        $Settings = $this->getVariable("Settings");
        $do_save= false;
        

        if (!is_null(Input::post("name"))) {
            $Settings->set("data.site_name", Input::post("name"));
            $do_save = true;
        }

        if (!is_null(Input::post("description"))) {
            $Settings->set("data.site_description", Input::post("description"));
            $do_save = true;
        }
        
        if (!is_null(Input::post("keywords"))) {
            $Settings->set("data.site_keywords", Input::post("keywords"));
            $do_save = true;
        }

        if ($do_save) {
            $Settings->save();
        }

        if (!is_null(Input::post("active-theme")) && 
            is_dir(THEMES_PATH . "/" . Input::post("active-theme"))) 
        {
            save_option("np_active_theme_idname", Input::post("active-theme"));
        }

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save logotype
     * @return boolean 
     */
    private function saveLogotype()
    {  
        $Settings = $this->getVariable("Settings");
        $do_save= false;
        
        if (!is_null(Input::post("logotype"))) {
            $Settings->set("data.logotype", Input::post("logotype"));
            $do_save = true;
        }

        if (!is_null(Input::post("logomark"))) {
            $Settings->set("data.logomark", Input::post("logomark"));
            $do_save = true;
        }

        if ($do_save) {
            $Settings->save();
        }

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save other settings
     * @return boolean 
     */
    private function saveOther()
    {  
        $Settings = $this->getVariable("Settings");
        $EmailSettings = $this->getVariable("EmailSettings");
        $do_save= false;
        
        if (!is_null(Input::post("currency"))) {
            $Settings->set("data.currency", Input::post("currency"));
            $do_save = true;
        }

        if (!is_null(Input::post("geonames-username"))) {
            $Settings->set("data.geonamesorg_username", Input::post("geonames-username"));
            $do_save = true;
        }

        if ($do_save) {
            $Settings->save();
        }

        $EmailSettings->set("data.email_verification", (bool)Input::post("email-verification"))
                      ->save();


        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }

    /**
     * Save the settings related to the experimental features
     * @return self 
     */
    private function saveExperimental()
    {   
        // Toggle video processing
        save_option("np_video_processing", (bool)Input::post("video-processing") ? 1 : 0);

        // Toggle search in caption (applies to the first comment input also)
        save_option("np_search_in_caption", (bool)Input::post("search-in-caption") ? 1 : 0);

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save Google Analytics settings
     * @return boolean 
     */
    private function saveGoogleAnalytics()
    {  
        $Integrations = $this->getVariable("Integrations");
        $do_save= false;
        

        if (!is_null(Input::post("property-id"))) {
            $Integrations->set("data.google.analytics.property_id", Input::post("property-id"));
            $do_save = true;
        }


        if ($do_save) {
            $Integrations->save();
        }

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save Google API settings (Drive Picker)
     * @return boolean 
     */
    private function saveGoogleDrive()
    {  
        $Integrations = $this->getVariable("Integrations");
        $do_save= false;
        
        $api_key = Input::post("api-key");
        $client_id = Input::post("client-id");

        if (!is_null($api_key)) {
            $Integrations->set("data.google.api_key", $api_key);
            $do_save = true;
        }

        if (!is_null($client_id)) {
            $Integrations->set("data.google.client_id", $client_id);
            $do_save = true;
        }

        if ($do_save) {
            $Integrations->save();
        }

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save Dropbox settings
     * @return boolean 
     */
    private function saveDropbox()
    {  
        $Integrations = $this->getVariable("Integrations");
        $do_save= false;
        

        if (!is_null(Input::post("api-key"))) {
            $Integrations->set("data.dropbox.api_key", Input::post("api-key"));
            $do_save = true;
        }


        if ($do_save) {
            $Integrations->save();
        }

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save Onedrive settings
     * @return boolean 
     */
    private function saveOnedrive()
    {  
        $Integrations = $this->getVariable("Integrations");
        $do_save= false;
        

        if (!is_null(Input::post("client-id"))) {
            $Integrations->set("data.onedrive.client_id", Input::post("client-id"));
            $do_save = true;
        }


        if ($do_save) {
            $Integrations->save();
        }

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save PayPal settings
     * @return boolean 
     */
    private function savePaypal()
    {  
        $Integrations = $this->getVariable("Integrations");
        
        $client_id = Input::post("client-id");
        $client_secret = Input::post("client-secret");
        $environment = strtolower(Input::post("environment"));
        if ($environment != "live") {
            $environment = "sandbox";
        }

        $Integrations->set("data.paypal.environment", $environment)
                     ->set("data.paypal.client_id", $client_id)
                     ->set("data.paypal.client_secret", $client_secret)
                     ->save();

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }

    /**
     * Save Stripe settings
     * @return boolean 
     */
    private function saveStripe()
    {  
        $Integrations = $this->getVariable("Integrations");
        
        $publishable_key = Input::post("publishable-key");
        $secret_key = Input::post("secret-key");
        $environment = strtolower(Input::post("environment"));
        if ($environment != "live") {
            $environment = "sandbox";
        }
        $recurring = (bool)Input::post("recurring");
        $webhook_key = Input::post("webhook-key");

        $Integrations->set("data.stripe.environment", $environment)
                     ->set("data.stripe.publishable_key", $publishable_key)
                     ->set("data.stripe.secret_key", $secret_key)
                     ->set("data.stripe.recurring", $recurring)
                     ->set("data.stripe.webhook_key", $webhook_key)
                     ->save();

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save Facebook settings
     * @return boolean 
     */
    private function saveFacebook()
    {  
        $Integrations = $this->getVariable("Integrations");
        $do_save= false;
        

        if (!is_null(Input::post("app-id"))) {
            $Integrations->set("data.facebook.app_id", Input::post("app-id"));
            $do_save = true;
        }

        if (!is_null(Input::post("app-secret"))) {
            $Integrations->set("data.facebook.app_secret", Input::post("app-secret"));
            $do_save = true;
        }


        if ($do_save) {
            $Integrations->save();
        }

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save proxy settings
     * @return boolean 
     */
    private function saveProxy()
    {  
        $Settings = $this->getVariable("Settings");
        
        $Settings->set("data.proxy", (bool)Input::post("enable-proxy"));
        $Settings->set("data.user_proxy", (bool)Input::post("enable-user-proxy"));
        $Settings->save();
    
        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save SMTP settings
     * @return boolean 
     */
    private function saveSmtp()
    {
        $EmailSettings = $this->getVariable("EmailSettings");

        if (Input::post("host")) {
            $host = Input::post("host");
            $port = Input::post("port");
            $encryption = strtolower(Input::post("encryption"));
            if (!in_array($encryption, ["ssl", "tls"])) {
                $encryption = "";
            }
            $auth = (bool)Input::post("auth");
            $username = $auth ? Input::post("username") : "";
            $password = $auth ? Input::post("password") : "";
            $from = Input::post("from");

            if (!in_array($port, [25, 465, 587])) {
                $this->resp->msg = __("Invalid port number");
                $this->jsonecho(); 
            }

            if ($from && !filter_var($from, FILTER_VALIDATE_EMAIL)) {
                $this->resp->msg = __("From email is not valid");
                $this->jsonecho();
            }

            // Check SMTP Connection
            $smtp = new SMTP;
            $connected = false;
            // $smtp->do_debug = SMTP::DEBUG_CONNECTION;

            try {
                //Connect to an SMTP server
                $options = [];

                // If your mail server is on GoDaddy
                // Probably you should uncomment following 5 lines
                // 
                // $options["ssl"] = [
                //     'verify_peer' => false,
                //     'verify_peer_name' => false,
                //     'allow_self_signed' => true
                // ];

                if (!$smtp->connect($host, $port, 30, $options)) {
                    $this->resp->msg = __("Connection failed");
                    $this->jsonecho();
                }

                //Say hello
                if (!$smtp->hello(gethostname())) {
                    $this->resp->msg = __("Connection failed");
                    $this->jsonecho();
                }

                //Get the list of ESMTP services the server offers
                $e = $smtp->getServerExtList();
                
                //If server can do TLS encryption, use it
                if (is_array($e) && array_key_exists('STARTTLS', $e)) {
                    $tlsok = $smtp->startTLS();

                    if (!$tlsok) {
                        $this->resp->msg = __("Failed to start encryption");
                        $this->jsonecho();
                    }

                    //Repeat EHLO after STARTTLS
                    if (!$smtp->hello(gethostname())) {
                        $this->resp->msg = __("Encryption failed");
                        $this->jsonecho();
                    }

                    //Get new capabilities list, which will usually now include AUTH if it didn't before
                    $e = $smtp->getServerExtList();
                }

                //If server supports authentication, do it (even if no encryption)
                if ($auth && is_array($e) && array_key_exists('AUTH', $e)) {
                    if ($smtp->authenticate($username, $password)) {
                        $connected = true;
                    } else {
                        $this->resp->msg = __("Authentication failed");
                        $this->jsonecho();
                    }
                }
            } catch (Exception $e) {
                $this->resp->msg = __("Connection failed");
                $this->jsonecho();
            }

            $smtp->quit(true);

            if (!$connected) {
                $this->resp->msg = __("Authentication failed");
                $this->jsonecho();
            }


            // Encrypt the password
            try {
                $passhash = Defuse\Crypto\Crypto::encrypt($password, 
                            Defuse\Crypto\Key::loadFromAsciiSafeString(CRYPTO_KEY));
            } catch (\Exception $e) {
                $this->resp->msg = $e->getMessage();
                $this->jsonecho();
            }


            $settings = [
                "host" => $host,
                "port" => $port,
                "encryption" => $encryption,
                "auth" => $auth,
                "username" => $username,
                "password" => $passhash,
                "from" => $from
            ];
        } else {
            $settings = [
                "host" => "",
                "port" => "",
                "encryption" => "",
                "auth" => false,
                "username" => "",
                "password" => "",
                "from" => ""
            ];
        }

        $data = json_decode($EmailSettings->get("data"), true);
        $data["smtp"] = $settings;
        $EmailSettings->set("data", json_encode($data));
        $EmailSettings->save();
        
        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save email notification settings
     * @return [type] [description]
     */
    private function saveNotifications()
    {
        $EmailSettings = $this->getVariable("EmailSettings");

        $emails = explode(",", Input::post("emails"));
        $valid_emails = [];
        foreach ($emails as $e) {
            $e = trim($e);
            if (filter_var($e, FILTER_VALIDATE_EMAIL)) {
                $valid_emails[] = $e;
            }
        }

        $new_user = (bool)Input::post("new-user");
        $new_payment = (bool)Input::post("new-payment");

        $settings = [
            "emails" => implode(", ", $valid_emails),
            "new_user" => $new_user,
            "new_payment" => $new_payment
        ];


        $data = json_decode($EmailSettings->get("data"), true);
        $data["notifications"] = $settings;
        $EmailSettings->set("data", json_encode($data));
        $EmailSettings->save();
        
        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }


    /**
     * Save recaptcha settings
     * @return [self] 
     */
    private function saveRecaptcha()
    {
        // Save the site key and secret key
        save_option("np_recaptcha_site_key", Input::post("site-key"));
        save_option("np_recaptcha_secret_key", Input::post("secret-key"));

        // Toggle recaptcha verification option in the signup page
        save_option("np_signup_recaptcha_verification", (bool)Input::post("signup-recaptcha-verification") ? 1 : 0);

        $this->resp->result = 1;
        $this->resp->msg = __("Changes saved!");
        $this->jsonecho();

        return $this;
    }
}