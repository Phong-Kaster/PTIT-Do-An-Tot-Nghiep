<?php
    /**
     * @author Phong-Kaster
     * @since 31-10-2022
     * dash board controller
     */
    class AppointmentsController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $this->view("appointments");
        }
    }
?>