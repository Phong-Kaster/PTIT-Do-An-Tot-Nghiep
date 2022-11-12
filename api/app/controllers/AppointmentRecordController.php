<?php
    /**
     * @author Phong-Kaster
     * @since 23-10-2022
     * this function is used by doctor's role as ADMIN or MEMBER
     */
    class AppointmentRecordController extends Controller
    {
        /**
         * Process
        */
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if (!$AuthUser){
                // Auth
                header("Location: ".APPURL."/login");
                exit;
            }

            
            /**Only ADMIN or MEMBER can use this function */
            $valid_roles = ["admin", "member"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "Only Doctor's role as ".implode(', ', $valid_roles)." who can do this action";
                $this->jsonecho();
            }


            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getById();
            }
            else if( $request_method === 'PUT')
            {
                $this->update();
            }
            else if( $request_method === 'DELETE')
            {
                $this->delete();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 23-10-2022
         * Read an appointment record info by ID
         * Because each appointment has only one record so that this function 
         * supports find appointment record with 2 options
         * 1. Find appointment record with its ID
         * 2. Find appointment record with its appointment_id
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

            /**Step 3 - query*/
            try 
            {
                // $AppointmentRecord = Controller::model("AppointmentRecord", $Route->params->id);
                // if( !$AppointmentRecord->isAvailable() )
                // {
                //     $this->resp->msg = "AppointmentRecord is not available";
                //     $this->jsonecho();
                // }

                


                $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS)
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

                                DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".id as speciality_id"),
                                DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".name as speciality_name"),
                            ]);

                /**Step 4 - query with option */
                /**Case 1 - get with ID */
                $type = Input::get("type") ? Input::get("type") : "id";
                if( $type == "id")
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".id", "=", $Route->params->id);
                }
                /**Case 2 - get with appointment_id */
                if( $type == "appointment_id")
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".appointment_id", "=", $Route->params->id);
                }



                /**Step 5 - return */
                $result = $query->get();
                if( count($result) == 0 )
                {
                    $this->resp->msg = "There is no appointment record found by ".$type." so that we CREATE a new one !";
                    $this->jsonecho();
                }
                $element = $result[0];



                /**Step 6 - if appointment's status != PROCESSING => cancel */
                $Appointment = Controller::model("Appointment", $element->appointment_id);
                if( !$Appointment )
                {
                    $this->resp->msg = "Appointment does not exist !";
                    $this->jsonecho();
                }
                // if( $Appointment->get("status") != "processing" )
                // {
                //     $this->resp->msg = "Appointment's status is ".$Appointment->get("status")." so that you can't do this action";
                //     $this->jsonecho();
                // }


                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = array(
                        "id" => (int)$element->id,
                        "reason" => $element->reason,
                        "description" => $element->description,
                        "status_before" => $element->status_before,
                        "status_after" => $element->status_after,
                        "create_at" => $element->create_at,
                        "update_at" => $element->update_at,
                        "appointment" => array(
                            "id" => (int)$element->appointment_id,
                            "patient_id" => (int)$element->patient_id,
                            "patient_name" => $element->patient_name,
                            "patient_birthday" => $element->patient_birthday,
                            "patient_reason" => $element->patient_reason,
                            "date" => $element->date,
                            "status" => $element->status
                        ),
                        "doctor" => array(
                            "id" => (int)$element->doctor_id,
                            "name" => $element->doctor_name
                        ),
                        "speciality" => array(
                            "id" => (int)$element->speciality_id,
                            "name" => $element->speciality_name
                        )
                );
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }

        /**
         * @author Phong-Kaster
         * @since 23-10-2022
         * update an appointment record info
         */
        private function update()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");
            $AuthUser = $this->getVariable("AuthUser");

            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            $AppointmentRecord = Controller::model("AppointmentRecord", $Route->params->id);
            if( !$AppointmentRecord->isAvailable() )
            {
                $this->resp->msg = "Appointment Record is not available !";
                $this->jsonecho();
            }

            if($AuthUser->get("role") == "member")
            {
                $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS)
                        ->leftJoin(TABLE_PREFIX.TABLE_APPOINTMENTS, 
                                   TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "=", TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".appointment_id")
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".id", "=", $Route->params->id)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $AuthUser->get("id"))
                        ->select("*");
                $result = $query->get();
                if( count($result) == 0 )
                {
                    $this->resp->msg = "This appointment record does not belong to you so that you can update this record";
                    $this->jsonecho();
                }
            }


            /**Step 2 - get required data */
            $required_fields = ["reason", "description"];
            foreach( $required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }


            /**Step 3 - list required data */
            $appointment_id = Input::put("appointment_id");
            $reason = Input::put("reason");
            $description = Input::put("description");
            $status_before = Input::put("status_before");
            $status_after = Input::put("status_after");

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $update_at = date("Y-m-d H:i:s");

            /**Step 4 - validation */
            /**Step 4.1 - is this appointment available? */
            $Appointment = Controller::model("Appointment", $appointment_id);
            if( !$Appointment->isAvailable() )
            {
                $this->resp->msg = "Appointment is not available";
                $this->jsonecho();
            }
            
            /**Step 4.2 - is this appointment's status valid? */
            $valid_status = ["done", "cancelled"];
            $status_validation = in_array($Appointment->get("status"), $valid_status);
            if( $status_validation )
            {
                $this->resp->msg = "The status of appointment is ".$Appointment->get("status")." so that you can't do this action";
                $this->jsonecho();
            }


            /**Step 4.3 - is appointment's date today? 
             * If no, this appointment can not be changed ! */
            $today = Date("d-m-Y");
            $appointment_date = $Appointment->get("date");
            
            $difference = abs(strtotime($today) - strtotime($appointment_date));
            $differenceYear = floor($difference / (365*60*60*24));
            $differenceMonth = floor(($difference - $differenceYear * 365*60*60*24) / (30*60*60*24));
            $differenceDay = floor(($difference - $differenceYear * 365*60*60*24 - $differenceMonth*30*60*60*24)/ (60*60*24));

            if( $differenceDay > 0 )
            {
                $this->resp->msg = "Today is ".$today." but this appointment's is ".$appointment_date
                                    ." so that you can not create new appointment record!";
                $this->jsonecho();
            }


            /**Step 4.4 - name */
            $reason_validation = isAddress($reason);
            if( $reason_validation == 0 ){
                $this->resp->msg = "Reason before only has letters, space, number & dash. Try again !";
                $this->jsonecho();
            }

            /**Step 4.5 - description */


            /**Step 4.6 - Status before */
            if( $status_before ) 
            { 
                $status_before_validation = isAddress($status_before);
                if(!$status_before_validation )
                {
                    $this->resp->msg = "Status before only has letters, space, number & dash. Try again !";
                    $this->jsonecho();
                }
            }


            /**Step 4.7 - Status after */
            if( $status_after )
            {
                $status_after_validation = isAddress($status_after);
                if(!$status_after_validation )
                {
                    $this->resp->msg = "Status after only has letters, space, number & dash. Try again !";
                    $this->jsonecho();
                }
            }
            
            /**Step 5 - save - however, each appointment only has one appointment record*/
            try 
            {
                $AppointmentRecord->set("appointment_id", $appointment_id)
                        ->set("reason", $reason)
                        ->set("description", $description)
                        ->set("status_before", $status_before)
                        ->set("status_after", $status_after)
                        ->set("update_at", $update_at)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Appointment record has been UPDATE successfully";
                $this->resp->data = array(
                    "id" => (int)$AppointmentRecord->get("id"),
                    "appointment_id" => (int)$AppointmentRecord->get("appointment_id"),
                    "reason" => $AppointmentRecord->get("reason"),
                    "description" => $AppointmentRecord->get("description"),
                    "status_before" => $AppointmentRecord->get("status_before"),
                    "status_after" => $AppointmentRecord->get("status_after"),
                    "create_at" => $AppointmentRecord->get("create_at"),
                    "update_at" => $AppointmentRecord->get("update_at")
                );
                $this->jsonecho();
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>