<?php
/**
 * Signup Controller
 */
class SignupController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $AuthUser = $this->getVariable("AuthUser");

        if ($AuthUser) {
            header("Location: ".APPURL."/post");
            exit;
        }

        $recaptcha_enabled = false;
        if (get_option("np_recaptcha_site_key") && 
            get_option("np_recaptcha_secret_key") && 
            get_option("np_signup_recaptcha_verification")
        ) {
            $recaptcha_enabled = true;
        }

        $Integrations = Controller::model("GeneralData", "integrations");
        $Package = Controller::model("Package", Input::get("package"));

        $this->setVariable("TimeZones", getTimezones())
             ->setVariable("Package", $Package)
             ->setVariable("recaptcha_enabled", $recaptcha_enabled)
             ->setVariable("Integrations", $Integrations);

        if (Input::post("action") == "signup") {
            $this->signup();
        }

        $this->view("signup", "site");
    }


    /**
     * Signup
     * @return void
     */
    private function signup()
    {
        $recaptcha_enabled = $this->getVariable("recaptcha_enabled");
        $errors = [];

        $required_fields  = [
            "firstname", "lastname", "email", 
            "password", "password-confirm", "timezone"
        ];

        $required_ok = true;
        foreach ($required_fields as $field) {
            if (!Input::post($field)) {
                $required_ok = false;
            }
        }

        if (!$required_ok) {
            $errors[] = __("All fields are required");
        }


        if (empty($errors)) {
            if (!filter_var(Input::post("email"), FILTER_VALIDATE_EMAIL)) {
                $errors[] = __("Email is not valid!");
            } else {
                $User = Controller::model("User", Input::post("email"));
                if ($User->isAvailable()) {
                    $errors[] = __("Email is not available!");
                }
            }

            if (mb_strlen(Input::post("password")) < 6) {
                $errors[] = __("Password must be at least 6 character length!");
            } else if (Input::post("password-confirm") != Input::post("password")) {
                $errors[] = __("Password confirmation didn't match!");
            }
        }


        // Check recaptcha
        if ($recaptcha_enabled && Input::post("recaptcha")) {
            if (empty($errors)) {
                if (!Input::post("g-recaptcha-response")) {
                    $errors[] = __("Please verify the reCaptcha.");
                } 
            }

            if (empty($errors)) {
                try {
                    $client = new GuzzleHttp\Client();
                    $recaptcha_resp = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                        'form_params' => [
                            'secret' => get_option("np_recaptcha_secret_key"), 
                            'response' => Input::post("g-recaptcha-response")
                        ]
                    ]);
                    $recaptcha_resp = @json_decode($recaptcha_resp->getBody());
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }


                if (empty($recaptcha_resp->success)) {
                    if (isset($recaptcha_resp->{"error-codes"})) {
                        foreach ($recaptcha_resp->{"error-codes"} as $error_code) {
                            switch ($error_code) {
                                case 'missing-input-secret':
                                case 'invalid-input-secret':
                                    $errors[] = __("Missing or invalid secret key for the reCaptcha.");
                                    break;

                                case 'timeout-or-duplicate':
                                    $errors[] = __("Recaptcha is timedout or duplicate.");
                                    break;
                                
                                default:
                                    $errors[] = __("Recaptcha error: ".$error_code);
                                    break;
                            }
                        }
                    } else {
                        $errors[] = __("Couldn't verify the recaptcha");
                    }
                }
            }
        }

        if (empty($errors)) {
            $timezone = Input::post("timezone");
            if (!in_array(Input::post("timezone"), DateTimeZone::listIdentifiers())) {
                $timezone = "UTC";
            }

            $trial = Controller::model("GeneralData", "free-trial");
            $trial_size = (int)$trial->get("data.size");
            if ($trial_size == "-1") {
                $expire_date = "2050-12-12 23:59:59";
            } else if ($trial_size > 0) {
                $expire_date = date("Y-m-d H:i:s", time() + $trial_size * 86400);
            } else {
                $expire_date = date("Y-m-d H:i:s", time());
            }

            $settings = json_decode($trial->get("data"));
            unset($settings->size);

            $preferences = [
                "timezone" => $timezone,
                "dateformat" => "Y-m-d",
                "timeformat" => "24"
            ];

            $User->set("email", strtolower(Input::post("email")))
                 ->set("password", 
                       password_hash(Input::post("password"), PASSWORD_DEFAULT))
                 ->set("firstname", Input::post("firstname"))
                 ->set("lastname", Input::post("lastname"))
                 ->set("settings", json_encode($settings))
                 ->set("preferences", json_encode($preferences))
                 ->set("is_active", 1)
                 ->set("expire_date", $expire_date)
                 ->save();

            // Check is email verification setting is ON
            $EmailSettings = \Controller::model("GeneralData", "email-settings");
            if ($EmailSettings->get("data.email_verification")) {
                // Send verification email to this new user
                $User->sendVerificationEmail();
            }
            
            try {
                // Send notification emails to admins
                \Email::sendNotification("new-user", ["user" => $User]);
            } catch (\Exception $e) {
                // Failed to send notification email to admins
                // Do nothing here, it's not critical error
            }


            // Fire user.signup event
            Event::trigger("user.signup", $User);


            $Package = Controller::model("Package", Input::post("package"));
            if ($Package->isAvailable()) {
                $continue = APPURL . "/renew?package=" . $Package->get("id");
            } else {
                $continue = APPURL . "/post";
            }

            // Logging in
            setcookie("nplh", $User->get("id").".".md5($User->get("password")), 0, "/");


            header("Location: ".$continue);
            exit;
        }

        $this->setVariable("FormErrors", $errors);
        
        return $this;
    }
}