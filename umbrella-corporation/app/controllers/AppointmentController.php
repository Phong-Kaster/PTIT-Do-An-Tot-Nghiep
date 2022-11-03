<?php
    /**
     * @author Phong-Kaster
     * @since 31-10-2022
     * dash board controller
     */
    class AppointmentController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $Route = $this->getVariable("Route");
            if( isset($Route->params->id) )
            {
                $this->setVariable("id", $Route->params->id);
                $this->view("appointment");
            }
            else
            {
                header("Location: ".APPURL."/appointment");
            }
            
        }
    }
?>