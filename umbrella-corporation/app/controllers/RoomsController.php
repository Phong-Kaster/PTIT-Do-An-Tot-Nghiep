<?php
    /**
     * @author Phong-Kaster
     * @since 08-11-2022
     * dash board controller
     */
    class RoomsController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $this->view("rooms");
        }
    }
?>