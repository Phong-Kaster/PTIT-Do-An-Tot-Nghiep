<?php
    /**
     * @author Phong-Kaster
     * @since 12-11-2022
     * dash board controller
     */
    class AppointmentRecordController extends Controller
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
            $this->setVariable("appointmentId", 0);

            /**UPDATE record */
            if( isset($_GET["id"]) )
            {
                $id = $_GET["id"];
                $this->setVariable("id", $id);
            }
            /**CREATE record */
            if( isset($_GET["appointmentId"]) )
            {
                $appointmentId = $_GET["appointmentId"];
                $this->setVariable("appointmentId", $appointmentId);
                
            }
            $this->view("appointmentRecord");
        }
    }
?>