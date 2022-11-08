<?php
    /**
     * @author Phong-Kaster
     * @since 08-11-2022
     * dash board controller
     */
    class PatientController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $Route = $this->getVariable("Route");
            $this->setVariable("id", 0);
            if( isset($Route->params->id) )
            {
                $this->setVariable("id", $Route->params->id);
                $this->view("patient");
            }
            else
            {
                header("Location: ".APPURL."/patients");
                exit; 
            }
            
        }
    }
?>