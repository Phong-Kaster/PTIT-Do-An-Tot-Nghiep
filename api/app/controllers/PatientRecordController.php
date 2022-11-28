<?php
    /**
     * @author Phong-Kaster
     * @since 28-11-2022
     * Patient Record Controller
     */
    class PatientRecordController extends Controller
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
                 $this->resp->msg = "This function is only for PATIENT !";
                 $this->jsonecho();
            }

            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getById();
            }
        }

        /**
         * @since
         */
        private function getById()
        {
             /**Step 1 */
             $this->resp->result = 0;
             $AuthUser = $this->getVariable("AuthUser");
             $Route = $this->getVariable("Route");
             $data = [];
             
 
             /**Step 2 */
             if( !isset($Route->params->id) )
             {
                $this->resp->msg = "APPOINTMENT ID is required !";
                $this->jsonecho();
             }
 
             $appointmentId = $Route->params->id;
 
             $Appointment = Controller::model("Appointment", $appointmentId);
             if( !$Appointment->isAvailable() )
             {
                 $this->resp->msg = "Appointment is not available so that record does not exist !";
                 $this->jsonecho();
             }
             if( $Appointment->get("patient_id") != $AuthUser->get("id") )
             {
                 $this->resp->msg = "Appointment is not for you. Try again !";
                 $this->jsonecho();
             }

             $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS)
                            ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "=", $appointmentId)
                            ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_id", "=", $AuthUser->get("id"))
                            ->leftJoin(TABLE_PREFIX.TABLE_APPOINTMENTS, 
                                    TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "=", TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".appointment_id")
                            ->leftJoin(TABLE_PREFIX.TABLE_DOCTORS,
                                     TABLE_PREFIX.TABLE_DOCTORS.".id", "=", TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id")
                            ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES,
                                     TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")
                            ->select([
                                DB::raw(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".*"),

                                DB::raw(TABLE_PREFIX.TABLE_APPOINTMENTS.".id as appointment_id"),
                                DB::raw(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_id as patient_id"),
                                DB::raw(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_name as patient_name"),
                                DB::raw(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_birthday as patient_birthday"),
                                DB::raw(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_reason as patient_reason"),
                                DB::raw(TABLE_PREFIX.TABLE_APPOINTMENTS.".date as date"),
                                DB::raw(TABLE_PREFIX.TABLE_APPOINTMENTS.".status as status"),

                                DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".id as doctor_id"),
                                DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".name as doctor_name"),
                                DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".avatar as doctor_avatar"),

                                DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".id as speciality_id"),
                                DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".name as speciality_name"),
                            ]);

            $result = $query->get();

            foreach($result as $element)
            {
                $data = array(
                    "id" => (int) $element->id,
                    "reason" => $element->reason,
                    "description" => $element->description,
                    "status_before" =>$element->status_before,
                    "status_after" =>$element->status_after,
                    "create_at" =>$element->create_at,
                    "update_at" =>$element->update_at,
                    "appointment" => array(
                        "id" => (int) $element->appointment_id,
                        "patient_id" => (int) $element->patient_id,
                        "patient_name" =>  $element->patient_name,
                        "patient_birthday" =>  $element->patient_birthday,
                        "patient_reason" =>  $element->patient_reason,
                        "date" =>  $element->date,
                        "status" =>  $element->status,
                    ),
                    "doctor" => array(
                        "id" => (int)$element->doctor_id,
                        "name" => $element->doctor_name,
                        "avatar" => $element->doctor_avatar
                    ),
                    "speciality" => array(
                        "id" => (int) $element->speciality_id,
                        "name" => $element->speciality_name
                    )
                );
            }

            
            /**Step 5 - return */
            $this->resp->result = 1;
            $this->resp->msg = "Congratulation, action successfully";
            $this->resp->data = $data;
            $this->jsonecho();
        }
    }
?>