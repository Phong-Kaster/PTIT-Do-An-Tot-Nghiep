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
            $AuthUser = $this->getVariable("AuthUser");

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
                if( $Appointment->get("patient_id") != $AuthUser->get("id") )
                {
                    $this->resp->msg = "This appointment does not belong you";
                    $this->jsonecho();
                }

                
                $Doctor = Controller::model("Doctor", $Appointment->get("doctor_id"));
                $Speciality = Controller::model("Speciality", $Doctor->get("speciality_id"));
                $Room = Controller::model("Room", $Doctor->get("room_id"));


                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = array(
                    "id" => (int)$Appointment->get("id"),
                    "date" => $Appointment->get("date"),
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
                    "update_at" => $Appointment->get("update_at"),
                    "doctor" => array(
                        "id" => (int)$Doctor->get("id"),
                        "email" => $Doctor->get("email"),
                        "phone" => $Doctor->get("phone"),
                        "name" => $Doctor->get("name"),
                        "description" => $Doctor->get("description"),
                        "price" => $Doctor->get("price"),
                        "role" => $Doctor->get("role"),
                        "avatar" => $Doctor->get("avatar"),
                        "active" => (int)$Doctor->get("active"),
                        "create_at" => $Doctor->get("create_at"),
                        "update_at" => $Doctor->get("update_at"),
                    ),
                    "speciality" => array(
                        "id" => (int)$Speciality->get("id"),
                        "name" => $Speciality->get("name"),
                        "description" => $Speciality->get("description")
                    ),
                    "room" => array(
                        "id" => (int) $Room->get("id"),
                        "name" => $Room->get("name"),
                        "location" => $Room->get("location")
                    )
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