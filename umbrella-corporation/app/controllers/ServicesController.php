<?php
    /**
     * @author Phong-Kaster
     * @since 12-12-2022
     * Rooms controller
     */
    class ServicesController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser || $AuthUser->get("role") != "admin")
            {
                header("Location: ".APPURL."/login");
                exit;
            }

            $this->view("services");
        }
    }
?>