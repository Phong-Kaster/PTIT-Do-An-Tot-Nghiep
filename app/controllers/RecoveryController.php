<?php
/**
 * Recovery Controller
 */
class RecoveryController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        // $AuthUser = $this->getVariable("AuthUser");
        // if ($AuthUser) {
        //     header("Location: ".APPURL."/post");
        //     exit;
        // }

        // if (Input::post("action") == "recover") {
        //     $this->recover();
        // }
        // $this->view("recovery", "site");

        $request_method = Input::method();
        if( $request_method === 'POST')
        {
            $this->recoveryPassword();
        }
    }

    /**
     * @author Phong-Kaster
     * @since 11-10-2022
     * send email to user with 15-digit number - recovery code which is used to verify users
     */
    private function recoveryPassword()
    {
        $this->resp->result = 0;
        if( !Input::post("email") )
        {
            $this->resp->msg = "Email is required to recovery your password !";
            $this->jsonecho();
        }

        $email = Input::post("email");
        $Doctor = Controller::model("Doctor", $email);
        if( !$Doctor->isAvailable() )
        {
            $this->resp->msg = "There is no account registered with this email !";
            $this->jsonecho();
        }
        if( $Doctor->get("active") != 1)
        {
            $this->resp->msg = "This account is deactivated !";
            $this->jsonecho();
        }

        try 
        {
            $this->resp->result = 1;
            $this->resp->msg = "Email with recovery code is being sent. Let's check your Gmail !";
            $this->resp->id = (int)$Doctor->get("id");

            $data["doctor"] = $Doctor;
            MyEmail::recoveryPassword($data);
        } 
        catch (\Exception $ex) {
            $this->resp->msg = $ex->getMessage();
        }
        $this->jsonecho();

    }

    /**
     * Recover
     * @return void
     */
    private function recover()
    {
        $email = Input::post("email");
        
        if ($email) {
            $User = Controller::model("User", $email);

            if ($User->isAvailable() && $User->get("is_active") == 1) {
                try {
                    // Send instruction to email
                    // Send notification emails to admins
                    if(\Email::sendNotification("password-recovery", ["user" => $User])) {
                        $this->setVariable("success", true);
                    } else {
                        $this->setVariable("error", __("Couldn't send recovery email. Please try again later."));
                    }
                } catch (\Exception $e) {
                    // Failed to send notification email to admins
                    // Do nothing here, it's not critical error
                    $this->setVariable("error", __("Couldn't send recovery email. Please try again later."));
                }
            } else {
                $this->setVariable("error", __("We couldn't find your account"));
            }
        }

        return $this;
    }
}