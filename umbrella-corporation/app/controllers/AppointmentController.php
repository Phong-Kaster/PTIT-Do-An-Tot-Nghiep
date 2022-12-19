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
            $this->setVariable("bookingId", "");
            $this->setVariable("appointmentDate", "");
            $this->setVariable("appointmentTime", "");
            $this->setVariable("patientId", "");
            $this->setVariable("patientName", "");
            $this->setVariable("patientPhone", "");
            $this->setVariable("patientReason", "");
            $this->setVariable("patientBirthday", "");


            
            if( isset($Route->params->id) )
            {
                $this->setVariable("id", $Route->params->id);
                $this->view("appointment");
            }
            else
            {
                $doctorId = isset($_GET['doctorId']) ? $_GET['doctorId'] : "" ;
                $serviceId = isset($_GET['serviceId']) ? $_GET['serviceId'] : "" ;
                $bookingId = isset($_GET['bookingId']) ? $_GET['bookingId'] : "" ;
                $appointmentDate = isset($_GET['appointmentDate']) ? $_GET['appointmentDate'] : "" ;
                $appointmentTime = isset($_GET['appointmentTime']) ? $_GET['appointmentTime'] : "";

                $patientId = isset($_GET['patientId']) ? $_GET['patientId'] : "";
                $patientName = isset($_GET['patientName']) ? $_GET['patientName'] : "";
                $patientPhone = isset($_GET['patientPhone']) ? $_GET['patientPhone'] : "";
                $patientReason = isset($_GET['patientReason']) ? $_GET['patientReason'] : "";
                $patientBirthday = isset($_GET['patientBirthday']) ? $_GET['patientBirthday'] : "" ;

                // print_r($bookingId);
                // print_r($patientId);
                // print_r($appointmentDate);
                // print_r($appointmentTime);
                // print_r($patientName);
                // print_r($patientPhone);
                // print_r($patientReason);
                // print_r($patientBirthday);


                $this->setVariable("appointmentDate", $appointmentDate);
                $this->setVariable("appointmentTime", $appointmentTime);
                $this->setVariable("patientId", $patientId);
                $this->setVariable("patientName", $patientName);
                $this->setVariable("patientPhone", $patientPhone);
                $this->setVariable("patientReason", $patientReason);
                $this->setVariable("patientBirthday", $patientBirthday);
                $this->setVariable("bookingId", $bookingId);
                $this->setVariable("serviceId", $serviceId);
                $this->setVariable("doctorId", $doctorId);
                $this->view("appointment");
            }
           
        }
    }
?>