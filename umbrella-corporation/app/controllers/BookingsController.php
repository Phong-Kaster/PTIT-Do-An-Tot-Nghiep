<?php
    /**
     * @author Phong-Kaster
     * @since 05-11-2022
     * dash board controller
     */
    class BookingsController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser){
                header("Location: ".APPURL."/login");
                exit;
            }

            $this->view("bookings");
        }
    }
?>