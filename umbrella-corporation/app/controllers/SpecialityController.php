<?php
    /**
     * @author Phong-Kaster
     * @since 07-11-2022
     * dash board controller
     */
    class SpecialityController extends Controller
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
            $this->view("speciality");
        }
    }
?>