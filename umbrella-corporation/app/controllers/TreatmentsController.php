<?php
    /**
     * @author Phong-Kaster
     * @since 13-11-2022
     * dash board controller
     */
    class TreatmentsController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }



            $valid_roles = ["admin", "member"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                header("Location: ".APPURL."/dashboard");
                exit;
            }



            $Route = $this->getVariable("Route");
            $this->setVariable("id", 0);
            if( isset($Route->params->id) )
            {
                $this->setVariable("id", $Route->params->id);
                $this->view("treatments");
            }
            else
            {
                header("Location: ".APPURL."/appointments");
                exit;
            }
        }
    }
?>