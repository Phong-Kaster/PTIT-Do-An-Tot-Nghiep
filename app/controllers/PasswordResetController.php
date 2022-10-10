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
        $AuthUser = $this->getVariable("AuthUser");
        $Route = $this->getVariable("Route");

        if ($AuthUser) {
            header("Location: ".APPURL."/post");
            exit;
        }

        $User = Controller::model("User", $Route->params->id);
        if (!$User->isAvailable() ||
            !$User->get("is_active") || 
            $User->get("data.recoveryhash") != $Route->params->hash) 
        {
            header("Location: ".APPURL);
            exit;
        }
        $this->setVariable("User", $User);

        if (Input::post("action") == "reset") {
            $this->resetpass();
        }
        $this->view("password-reset", "site");
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