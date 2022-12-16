<?php
    /**
     * @author Phong-Kaster
     * @since 26-10-2022
     * $this->view("login") -> app/views/login.php
     * $this->view("login", "site") -> inc/themes/default/views/login.php
     * Login Controller
     */
class LoginController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $AuthUser = $this->getVariable("AuthUser");
        if ($AuthUser) {
            header("Location: ".APPURL);
            exit;
        }

        if (Input::post("action") == "login") {
            $this->login();
        }
        $this->view("login");
    }



    private function login()
    {
        $email = Input::post("email");
        $password = Input::post("password");
        $remember = Input::post("remember");

        if ($email && $password) 
        {
            try {
                $client = new GuzzleHttp\Client();

                $resp = $client->request('POST', API_URL."/login",  [
                    'form_params' => [
                        'email' => $email,
                        'password' => $password
                    ]
                ]);

                $resp = @json_decode($resp->getBody());

                if($resp->result == 1){

                    
                    $accessToken = $resp->accessToken;
                    $exp = $remember ? time()+86400*30 : 0;

                    setcookie("accessToken", $accessToken, $exp, "/");
                    if($remember) {
                        setcookie("mplrmm", "1", $exp, "/");
                    } else {
                        setcookie("mplrmm", "1", time() - 30*86400, "/");
                    }
                    
                    header("Location: ".APPURL."/dashboard");
                    exit;
                }
            } catch (\Exception $e) {
                print_r($e->getMessage());
            }
        }
        return $this;
    }


    /**
     * Login with Facebook
     * @return void
     */
    private function fblogin()
    {
        $this->resp->result = 0;
        $Integrations = $this->getVariable("Integrations");

        $required_fields  = [
            "firstname", "lastname", "email", "token", "userid"
        ];

        
        foreach ($required_fields as $field) {
            if (!Input::post($field)) {
                $this->resp->msg = __("Missing some of required data.");
                $this->jsonecho();
            }
        }

        if (!filter_var(Input::post("email"), FILTER_VALIDATE_EMAIL)) { 
            $this->resp->msg = __("Email is not valid.");
            $this->jsonecho();
        }


        // Validate user token
        $url = "https://graph.facebook.com/v2.8/debug_token?access_token="
             . htmlchars($Integrations->get("data.facebook.app_id")) . "|"
             . htmlchars($Integrations->get("data.facebook.app_secret"))
             . "&input_token=" . Input::post("token");
        $tokenresp = @json_decode(file_get_contents($url));
        
        if (empty($tokenresp->data->user_id) ||
            empty($tokenresp->data->is_valid) ||
            $tokenresp->data->user_id != Input::post("userid")) 
        {
            $this->resp->msg = __("Invalid token");
            $this->jsonecho();
        }
        

        $User = Controller::model("User", Input::post("email"));

        if ($User->isAvailable()) {
            // User exists,
            if ($User->get("is_active") != 1) {
                // User is not active
                $this->resp->msg = __("Account is not active");
                $this->jsonecho();
            }

            setcookie("nplh", $User->get("id").".".md5($User->get("password")), 0, "/");
            setcookie("nplrmm", "1", time() - 30*86400, "/");
        } else {
            // User doesn't exits
            // Register new user

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
                "timezone" => empty($IpInfo->timezone) ? "UTC" : $IpInfo->timezone,
                "dateformat" => "Y-m-d",
                "timeformat" => "24"
            ];

            $data = [
                "fbuserid" => $tokenresp->data->user_id
            ];

            $User->set("email", strtolower(Input::post("email")))
                 ->set("password", 
                       password_hash(readableRandomString(10), PASSWORD_DEFAULT))
                 ->set("firstname", Input::post("firstname"))
                 ->set("lastname", Input::post("lastname"))
                 ->set("settings", json_encode($settings))
                 ->set("preferences", json_encode($preferences))
                 ->set("is_active", 1)
                 ->set("expire_date", $expire_date)
                 ->set("data", json_encode($data))
                 ->save();

            try {
                // Send notification emails to admins
                \Email::sendNotification("new-user", ["user" => $User]);
            } catch (\Exception $e) {
                // Failed to send notification email to admins
                // Do nothing here, it's not critical error
            }

            // Logging in
            setcookie("nplh", $User->get("id").".".md5($User->get("password")), 0, "/");
        }


        $this->resp->result = 1;
        $this->resp->redirect = APPURL."/post";
        $this->jsonecho();
    }
}