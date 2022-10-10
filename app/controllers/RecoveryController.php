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
        $AuthUser = $this->getVariable("AuthUser");
        if ($AuthUser) {
            header("Location: ".APPURL."/post");
            exit;
        }

        if (Input::post("action") == "recover") {
            $this->recover();
        }
        $this->view("recovery", "site");
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