<?php
    /**
     * @author Phong-Kaster
     * @since 28-11-2022
     * patient records controller
     */
    class PatientRecordsController extends Controller
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
                $this->getAll();
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
                            "name" => $element->doctor_name,
                            "avatar" => $element->doctor_avatar
                        ),
                        "speciality" => array(
                            "id" => (int)$element->speciality_id,
                            "name" => $element->speciality_name
                        )
                    );
                }
    
    
                /**Step 5 - return */
                $this->resp->result = 1;
                $this->resp->msg = "Action successfully";
                $this->resp->quantity = count($result);
                $this->resp->data = $data;
            }
            catch(Exception $ex)
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>