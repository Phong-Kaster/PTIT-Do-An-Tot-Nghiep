<?php
/**
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
            $this->resp->result = 1;
            $this->resp->msg = __("You already logged in");
            $this->jsonecho();
        }


        // print_r("hashed password: ".password_hash("123456", PASSWORD_DEFAULT));
        $this->login();
    }


    /**
     * Login
     * @return void
     */
    private function login()
    {
        $this->resp->result = 0;
        $email = Input::post("email");
        $password = Input::post("password");
        // $remember = Input::post("remember");

        if ($email && $password) {
            $Doctor = Controller::model("Doctor", $email);

            if ($Doctor->isAvailable() &&
                $Doctor->get("active") == 1 &&
                password_verify($password, $Doctor->get("password"))) 
            {
                $data = array(
                    "id"    => (int)$Doctor->get("id"),
                    "email" => $Doctor->get("email"),
		    		"phone" => $Doctor->get("phone"),
                    "name" => $Doctor->get("name"),
                    "description" => $Doctor->get("description"),
                    "price" => (int)$Doctor->get("price"),
                    "role" => $Doctor->get("role"),
                    "active" => (int)$Doctor->get("active"),
                    "avatar" => $Doctor->get("avatar"),
                    "create_at" => $Doctor->get("create_at"),
                    "update_at" => $Doctor->get("update_at"),
                    "speciality_id" => (int)$Doctor->get("speciality_id"),
                    "clinic_id" => (int)$Doctor->get("clinic_id")
                );

                $payload = $data;

                $payload["hashPass"] = md5($Doctor->get("password"));
                $payload["iat"] = time();

                $jwt = Firebase\JWT\JWT::encode($payload, EC_SALT, 'HS256');

                $this->resp->result = 1;
                $this->resp->msg = __("Log in successfully");
                $this->resp->accessToken = $jwt;
                $this->resp->data = $data;

                $this->jsonecho();
            }
        }

        $this->resp->msg = __("Email or password is not correct !");
        $this->jsonecho();
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