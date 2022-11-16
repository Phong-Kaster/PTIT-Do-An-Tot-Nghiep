<?php 
    class LoginWithGoogleController extends Controller
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

            $this->loginWithGoogle();

        }

        /**
         * @author Phong-Kaster
         * @since 16-11-2022
         * login with google info 
         * Case 1 : if the account found by email does not exist => create 
         * Case 2 : if the account exists => return JWT token
         */
        private function loginWithGoogle()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $msg = "";
            $jwt = "";
            $data = [];
            $msg = "Patient has been LOGGED IN successfully !";


            /**Step 2 - get data */
            $required_fields = ["type", "email", "password"];
            foreach($required_fields as $field )
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field ".$field;
                    $this->jsonecho();
                }
            }

            $type = Input::post("type");
            $email = Input::post("email");
            $password = Input::post("password");
            
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            if( $type != "patient")
            {
                $this->resp->msg = "Your request's type is ".$type." & it's not valid !";
                $this->jsonecho(); 
            }



            /**Step 3 - return JWT token */
            /**Step 3 - Case 1 - if the account found by email does not exist => create */
            $Patient = Controller::model("Patient", $email);
            if( !$Patient->isAvailable() )
            {
                $Patient->set("email", $email)
                        ->set("password", password_hash($password, PASSWORD_DEFAULT))
                        ->set("phone", "")
                        ->set("name", $email)
                        ->set("gender", 1)
                        ->set("birthday", "")
                        ->set("address", "")
                        ->set("avatar", "default_avatar.jpg")
                        ->set("create_at", $create_at)
                        ->set("update_at", $update_at)
                        ->save();
                $msg = "Patient account has been CREATE successfully";
            }
            if( !password_verify($password, $Patient->get("password") ) )
            {
                $this->resp->msg = "Your email or password is incorrect. Try again !";
                $this->jsonecho();
            }
            

            /**Step 3 - Case 2 - if the account found by email exists => return JWT */
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
  
            $payload = $data;
  
            $payload["hashPass"] = md5($Patient->get("password"));
            $payload["iat"] = time();
            // $jwt = Firebase\JWT\JWT::encode($payload, EC_SALT);

            $jwt = Firebase\JWT\JWT::encode($payload, EC_SALT, 'HS256');

            $this->resp->result = 1;
            $this->resp->msg = $msg;
            $this->resp->accessToken = $jwt;
            $this->resp->data = $data;
            $this->jsonecho();
        }
    }
?>