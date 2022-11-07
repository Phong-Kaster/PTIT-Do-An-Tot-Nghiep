<?php
    /**
     * @author Phong-Kaster
     * @since 06-11-2022
     * dash board controller
     */
    class SpecialitiesController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $this->view("specialities");
        }
    }
?>