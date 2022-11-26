<?php
    /**
     * @author Phong-Kaster
     * @since 21-10-2022
     * this function manages appointment. Only DOCTOR can use this function.
     */
    class PatientAppointmentController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if (!$AuthUser){
                // Auth
                header("Location: ".APPURL."/login");
                exit;
            }
            
            if( $AuthUser->get("role") )
            {
                $this->resp->result = 0;
                $this->resp->msg = "This function is used by PATIENT ONLY!";
                $this->jsonecho();
            }

            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getById();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 21-10-2022
         * Read an appointment info by ID
         */
        private function getById()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");


            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /**Step 3 */
            try 
            {
                $Appointment = Controller::model("Appointment", $Route->params->id);
                if( !$Appointment->isAvailable() )
                {
                    $this->resp->msg = "Appointment is not available";
                    $this->jsonecho();
                }


                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = array(
                    "id" => (int)$Appointment->get("id"),
                    "date" => $Appointment->get("date"),
                    "doctor_id" => (int)$Appointment->get("doctor_id"),
                    "numerical_order" => (int)$Appointment->get("numerical_order"),
                    "position" => (int) $Appointment->get("position"),
                    "patient_id" => (int)$Appointment->get("patient_id"),
                    "patient_name" => $Appointment->get("patient_name"),
                    "patient_phone" => $Appointment->get("patient_phone"),
                    "patient_birthday" => $Appointment->get("patient_birthday"),
                    "patient_reason" => $Appointment->get("patient_reason"),
                    "patient_phone" => $Appointment->get("patient_phone"),
                    "appointment_time" => $Appointment->get("appointment_time"),
                    "status" => $Appointment->get("status"),
                    "create_at" => $Appointment->get("create_at"),
                    "update_at" => $Appointment->get("update_at")
                );
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>