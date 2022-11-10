<?php
    /**
     * @author Phong-Kaster
     * @since 10-11-2022
     * Recovery controller
     */
    class PersonalUpdateController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $this->view("personalUpdate");
        }
    }
?>