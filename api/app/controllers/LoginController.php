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

        $this->login();
    }


    /**
     * @author Phong-Kaster
     * @since 13-10-2022
     * Case 1 : if type equals to "patient"
     * => patient is logging by PHONE OTP and PASSWORD is a unique ID
     * 
     * 
     * Case 2 : if type does not equals to "patient" 
     * => doctor is logging by EMAIL and PASSWORD is a normal password
     * @return void
     */
    private function login()
    {
        $this->resp->result = 0;
        $type = Input::post("type");
        $password = Input::post("password");
        $data = [];
        $payload = [];
        $msg = [];
        $jwt = "";

        if( !$password )
        {
            $this->resp->msg = "Password can not be empty !";
            $this->jsonecho();
        }


        /**Case 1 : if type equals to "patient" => patient is logging */
        if( $type == "patient" )
        {
            $this->loginByPatient();
        }
        /**Case 2 : if type does not equals to "patient" => doctor is logging */
        else
        {
            $this->loginByDoctor();
        }
    }

    /**
     * @author Phong-Kaster
     * @since 13-10-2022
     * this function handles the process of logging executed by a DOCTOR
     * email is individual address. For instance, phongkaster@gmail.
     * password is a secret keyword which is set by ADMIN or this DOCTOR.
     */
    private function loginByDoctor()
    {
        /**Step 1 - declare */
        $this->resp->result = 0;
        $password = Input::post("password");
        $email = Input::post("email");

        /**Step 2 - is email empty ? */
        if( !$email )
        {
            $this->resp->msg = "Email can not be empty !";
            $this->jsonecho();
        }

        /**Step 3 - does the doctor exist? */
        $Doctor = Controller::model("Doctor", $email);
        if( !$Doctor->isAvailable() || 
            $Doctor->get("active") != 1 || 
            !password_verify($password, $Doctor->get("password")) )
        {
            $this->resp->msg = __("The email or password you entered is incorrect !");
            $this->jsonecho();
        }


        $data = array(
            "id"    => (int)$Doctor->get("id"),
            "email" => $Doctor->get("email"),
            "phone" => $Doctor->get("phone"),
            "name" => $Doctor->get("name"),
            "price" => (int)$Doctor->get("price"),
            "role" => $Doctor->get("role"),
            "active" => (int)$Doctor->get("active"),
            "avatar" => $Doctor->get("avatar"),
            "create_at" => $Doctor->get("create_at"),
            "update_at" => $Doctor->get("update_at"),
            "speciality_id" => (int)$Doctor->get("speciality_id"),
            "recovery_token" => $Doctor->get("recovery_token")
        );

        $payload = $data;
        $payload["hashPass"] = md5($Doctor->get("password"));
        $payload["iat"] = time();
        $jwt = Firebase\JWT\JWT::encode($payload, EC_SALT, 'HS256');

        $this->resp->result = 1;
        $this->resp->msg = __("Congratulations, doctor ".$Doctor->get("name")." ! You have been logged in successfully.");
        $this->resp->accessToken = $jwt;
        $this->resp->data = $data;
        $this->jsonecho();
    }


    /**
     * @author Phong-Kaster
     * @since 13-10-2022
     * this function handles the process of logging executed by a PATIENT
     * phone is personal phone number. For instance, 079.410.4124.
     * password is a unique ID which is returned by firebase phone OTP.
     * 
     * Case 1 - if this patient does not exist in the database, we will create a new account for this patient
     * Case 2 - if this patient logins again, we will return JWT token & his/her information except password
     */
    private function loginByPatient()
    {
        /**Step 1 - declare */
        $this->resp->result = 0;
        $password = Input::post("password");
        $phone = Input::post("phone");
        $hashPassword = "";
        $data = [];

        /**Step 2 - is phone number correct format ? */
        if( !$phone )
        {
            $this->resp->msg = "Phone number can not be empty !";
            $this->jsonecho();
        }
        if( strlen($phone) < 10 ){
            $this->resp->msg = "Phone number has at least 10 number !";
            $this->jsonecho();
        }
        $phone_number_validation = isNumber($phone);
        if( !$phone_number_validation ){
            $this->resp->msg = "This is not a valid phone number. Please, try again !";
            $this->jsonecho();
        }

        /**Step 3 - does the patient exist? */
        $query = DB::table(TABLE_PREFIX.TABLE_PATIENTS)
                    ->where(TABLE_PREFIX.TABLE_PATIENTS.".phone" , "=" , $phone);
        $result = $query->get();

        /*Step 3 - Case 1 - if this patient does not exist in the database, we will create a new account for this patient*/
        if( count($result) == 0 )
        {
            $Patient = Controller::model("Patient");
            $Patient->set("email", "")
                ->set("phone", $phone)
                ->set("password", password_hash($password, PASSWORD_DEFAULT) )
                ->set("name", $phone)
                ->set("birthday", "")
                ->set("gender", 0)
                ->set("address", "")
                ->set("avatar", "")
                ->set("create_at", date("Y-m-d H:i:s"))
                ->set("update_at", date("Y-m-d H:i:s"))
                ->save();

            $msg = "Welcome to UMBRELLA CORPORATION, ".$Patient->get("name")." !";
            $data = array(
                "id"    => (int)$Patient->get("id"),
                "email" => $Patient->get("email"),
                "phone" => $Patient->get("phone"),
                "name" => $Patient->get("name"),
                "gender" => (int)$Patient->get("gender"),
                "birthday" => $Patient->get("birthday"),
                "address" => $Patient->get("address"),
                "avatar" => $Patient->get("avatar"),
                "create_at" => $Patient->get("create_at"),
                "update_at" => $Patient->get("update_at")
            );

            $hashPassword = $Patient->get("password");
        }
        /**Step 3 - Case 2 - if this patient logins again, we will return JWT token & his/her information except password */
        else 
        {
            /**is password correct ? */
            $hashPassword = $result[0]->password;
            if( !password_verify($password, $hashPassword ) )
            {
                $this->resp->msg = "Your email or password is incorrect. Try again !";
                $this->jsonecho();
            }


            
            /**yes, WELCOME BACK */
            $msg = "Welcome back to UMBRELLA CORPORATION, ".$result[0]->name." !";
            $data = array(
                "id"    => (int)$result[0]->id,
                "email" => $result[0]->email,
                "phone" => $result[0]->phone,
                "name" => $result[0]->name,
                "gender" => (int)$result[0]->gender,
                "birthday" => $result[0]->birthday,
                "address" => $result[0]->address,
                "avatar" => $result[0]->avatar,
                "create_at" => $result[0]->create_at,
                "update_at" => $result[0]->update_at
            );

            // // need update $password again
            // $password = $result[0]->password;
        }




        $payload = $data;
        $payload["hashPass"] = md5($hashPassword);
        $payload["iat"] = time();
        $jwt = Firebase\JWT\JWT::encode($payload, EC_SALT, 'HS256');

        $this->resp->result = 1;
        $this->resp->msg = $msg;
        $this->resp->accessToken = $jwt;
        $this->resp->data = $data;
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