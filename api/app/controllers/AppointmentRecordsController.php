<?php
    /**
     * @author Phong-Kaster
     * @since 23-10-2022
     * this function is used by ADMIN or MEMBER
     */
    class AppointmentRecordsController extends Controller
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
                $this->getAll();
            }
            else if( $request_method === 'POST')
            {
                $this->save();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 23-10-2022
         * get all appointment records
         */
        private function getAll()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data = [];
            

            /**Step 2 - get filters */
            $order          = Input::get("order");
            $search         = Input::get("search");
            $length         = Input::get("length") ? (int)Input::get("length") : 10;
            $start          = Input::get("start") ? (int)Input::get("start") : 0;
            $doctor_id      = Input::get("doctor_id");
            $speciality_id  = Input::get("speciality_id");
            $date           = Input::get("date");


            try
            {
                /**Step 3 - query */
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
                
                        
                /**Step 3.0 - if the doctor is MEMBER => he just only see these appointments created by him */
                if($AuthUser->get("role") == "member")
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $AuthUser->get("id"));
                }


                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {
                        $q->where(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".reason", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".description", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".status_before", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".status_after", 'LIKE', $search_query.'%');
                    }); 
                }


                /**Step 3.3 - order filter */
                if( $order && isset($order["column"]) && isset($order["dir"]))
                {
                    $type = $order["dir"];
                    $validType = ["asc","desc"];
                    $sort =  in_array($type, $validType) ? $type : "desc";
    
    
                    $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
                    $column_name = str_replace(".", "_", $column_name);
    
    
                    if(in_array($column_name, ["create_at","update_at", "id"])){
                        $query->orderBy(DB::raw(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".".$column_name. " * 1"), $sort);
                    }
                    else{
                        $query->orderBy($column_name, $sort);
                    }
                }
                else 
                {
                    $query->orderBy("id", "desc");
                } 
    
                /**Step 3.4 - filter */
                if( $doctor_id )
                {
                    $query->where(TABLE_PREFIX.TABLE_DOCTORS.".id", "=", $doctor_id);
                }
                if( $speciality_id )
                {
                    $query->where(TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", $speciality_id);
                }
                if( $date )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date);
                }

                /**Step 3.4 - length filter * start filter*/
                $query->limit($length)
                    ->offset($start);
    
    
    
                /**Step 4 */
                $result = $query->get();
                foreach($result as $element)
                {
                    $data[] = array(
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
    
    
                /**Step 5 - return */
                $this->resp->result = 1;
                $this->resp->quantity = count($result);
                $this->resp->data = $data;
            }
            catch(Exception $ex)
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }


        /**
         * @author Phong-Kaster
         * @since 23-10-2022
         * create new appointment record.
         * 
         * each appointment has only ONE record
         * Case 1: the appointment does not have its record => CREATE
         * Case 2: the appointment has its record => UPDATE
         */
        private function save()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            

            /**Step 2 - get required data */
            $required_fields = ["appointment_id", "reason", "description"];
            foreach( $required_fields as $field)
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }


            /**Step 3 - list required data */
            $appointment_id = Input::post("appointment_id");
            $reason = Input::post("reason");
            $description = Input::post("description");
            $status_before = Input::post("status_before");
            $status_after = Input::post("status_after");

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $create_at = date("Y-m-d H:i:s");
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

                $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENT_RECORDS.".appointment_id", "=", $appointment_id)
                        ->select("*");
                $result = $query->get();


                /**Case 1: the appointment does not have its record => CREATE  */
                if( count($result) == 0 )
                {
                    $AppointmentRecord = Controller::model("AppointmentRecord");
                    $msg = "Appointment record has been CREATE successfully";
                    $AppointmentRecord->set("create_at", $create_at)
                                ->set("update_at", $update_at);
                }
                /**Case 2: the appointment has its record => UPDATE  */
                else
                {
                    $appointment_record_id = $result[0]->id;
                    $AppointmentRecord = Controller::model("AppointmentRecord", $appointment_record_id);
                    $msg = "Appointment record has been UPDATE successfully";
                    $AppointmentRecord->set("update_at", $update_at);
                }
                

                $AppointmentRecord->set("appointment_id", $appointment_id)
                        ->set("reason", $reason)
                        ->set("description", $description)
                        ->set("status_before", $status_before)
                        ->set("status_after", $status_after)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = $msg;
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