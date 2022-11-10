<?php
    /**
     * @author Phong-Kaster
     * @since 07-11-2022
     * dash board controller
     */
    class SecurityController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $this->view("security");
        }
    }
?>