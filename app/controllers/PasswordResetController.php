<?php
/**
 * Password Reset Controller
 */
class PasswordResetController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $request_method = Input::method();
        if( $request_method === 'POST')
        {
            $this->resetPassword();
        }
    }


    /**
     * @author Phong-Kaster
     * @since 11-10-2022
     * reset password with verified recovery token
     */
    private function resetPassword()
    {
        /**Step 1 - declare */
        $this->resp->result = 0;
        $Route = $this->getVariable("Route");


        /**Step 2 - check input data */
        if( !isset($Route->params->id) )
        {
            $this->resp->msg = "ID is required !";
            $this->jsonecho();
        }

        $requiredFields = ["recovery_token", "password", "passwordConfirm"];
        foreach($requiredFields as $field)
        {
            if( !Input::post($field) )
            {
                $this->resp->msg = "Missing field: ".$field;
                $this->jsonecho();
            }
        }


        $recoveryToken = Input::post("recovery_token");
        $password = Input::post("password");
        $passwordConfirm = Input::post("passwordConfirm");
        $id = $Route->params->id;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $update_at = date("Y-m-d H:i:s");

        $Doctor = Controller::model("Doctor", $id );
        if( !$Doctor->isAvailable() )
        {
            $this->resp->msg = "This account is not available ";
            $this->jsonecho();
        }

        /**Step 2 - password filter */
        if( mb_strlen($password) < 6 )
        {
            $this->resp->msg = "Password must have at least 6 characters !";
            $this->jsonecho();
        }
        if( $password != $passwordConfirm )
        {
            $this->resp->msg = "Confirmation password is not equal to password !";
            $this->jsonecho();
        }

        /**Step 3 - recovery token compare*/
        $original_recovery_token = $Doctor->get("recovery_token");
        if( empty( $original_recovery_token) == 1 )
        {
            $this->resp->msg = "Recovery token is not valid. Try again !";
            $this->jsonecho();
        }
        if( $original_recovery_token != $recoveryToken )
        {
            $this->resp->msg = "Recovery token is not correct. Try again !";
            $this->jsonecho();
        }

        /**Step 4 - change password */
        try 
        {
            $Doctor->set("password", password_hash($password, PASSWORD_DEFAULT))
                    ->set("update_at", $update_at)
                    ->save();
            
            $this->resp->result = 1;
            $this->resp->msg = "Password is recovered successfully !";
        } 
        catch (\Exception $ex) 
        {
            $this->resp->msg = $ex->getMessage();
        }
        $this->jsonecho();


    }

    /**
     * Reset
     * @return void
     */
    private function resetpass()
    {
        $User = $this->getVariable("User");
        $password = Input::post("password");
        $password_confirm = Input::post("password-confirm");
        
        if ($password && $password_confirm) {
            if (mb_strlen($password) < 6) {
                $this->setVariable("error", __("Password must be at least 6 character length!"));
            } else if ($password_confirm != $password) {
                $this->setVariable("error", __("Password confirmation didn't match!"));
            } else {
                $data = json_decode($User->get("data"));
                unset($data->recoveryhash);
                $User->set("password", password_hash(Input::post("password"), PASSWORD_DEFAULT))
                     ->set("data", json_encode($data))
                     ->save();
                $this->setVariable("success", true);

                // Since an password reset url is sent to the email address,
                // The email address of the user can be set as verified 
                // after successfully recovering the password
                $User->setEmailAsVerified();
            }
        } else {
            $this->setVariable("error", __("All fields are required!"));
        }

        return $this;
    }
}