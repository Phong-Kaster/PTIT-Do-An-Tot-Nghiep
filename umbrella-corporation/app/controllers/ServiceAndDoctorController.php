<?php
    /**
     * @author Phong-Kaster
     * @since 12-12-2022
     * Service And Doctor Controller
     */
    class ServiceAndDoctorController extends Controller
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
            }
            $this->view("serviceAndDoctor");
        }
    }
?>