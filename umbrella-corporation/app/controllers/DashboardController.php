<?php
    /**
     * @author Phong-Kaster
     * @since 26-10-2022
     * dash board controller
     */
    class DashboardController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");

            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $this->view("dashboard");
        }
    }
?>