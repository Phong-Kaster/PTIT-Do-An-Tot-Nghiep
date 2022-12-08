<?php
    /**
     * @author Phong-Kaster
     * @since 08-12-2022
     * Booking Photos Controller shows all photos that patient supplied before appointment is created by SUPPORTER
     */
    class BookingPhotosController extends Controller
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
                $this->view("bookingPhotos");
            }
            else
            {
                header("Location: ".APPURL."/appointments");
                exit; 
            }
            
        }
    }
?>