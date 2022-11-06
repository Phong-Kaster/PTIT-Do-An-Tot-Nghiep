<?php
    /**
     * @author Phong-Kaster
     * @since 31-10-2022
     * dash board controller
     */
    class AppointmentController extends Controller
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
            else
            {
                $appointmentDate = isset($_GET['appointmentDate']) ? $_GET['appointmentDate'] : "" ;
                $appointmentTime = isset($_GET['appointmentTime']) ? $_GET['appointmentTime'] : "";

                $patientName = isset($_GET['patientName']) ? $_GET['patientName'] : "";
                $patientPhone = isset($_GET['patientPhone']) ? $_GET['patientPhone'] : "";
                $patientReason = isset($_GET['patientReason']) ? $_GET['patientReason'] : "";
                $patientBirthday = isset($_GET['patientBirthday']) ? $_GET['patientBirthday'] : "" ;

                // print_r($appointmentDate);
                // print_r($appointmentTime);
                // print_r($patientName);
                // print_r($patientPhone);
                // print_r($patientReason);
                // print_r($patientBirthday);


                $this->setVariable("appointmentDate", $appointmentDate);
            

                $this->setVariable("appointmentTime", $appointmentTime);
            
        
                $this->setVariable("patientName", $patientName);
            
            
                $this->setVariable("patientPhone", $patientPhone);
            
            
                $this->setVariable("patientReason", $patientReason);
            
            
                $this->setVariable("patientBirthday", $patientBirthday);
            }
            $this->view("appointment");
        }
    }
?>