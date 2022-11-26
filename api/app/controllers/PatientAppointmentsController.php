<?php
    /**
     * @author Phong-Kaster
     * @since 25-11-2022
     * this Controller handles appointments only by PATIENT
     */
    class PatientAppointmentsController extends Controller
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
                $this->getAll();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 20-10-2022
         * get all appointments
         * No matter what role doctors have, all doctors can use this action.
         * Case 1 - However, ADMIN & SUPPORTER can see all appointments
         * Case 2 - MEMBER just see all appointments that doctor_id = $AuthUser->get("id")
         * 
         * Only ADMIN & SUPPORTER can filter with doctor_id
         * The rest of conditions can be used by all roles.
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
            $length         = Input::get("length") ? (int)Input::get("length") : 5;
            $start          = Input::get("start") ? (int)Input::get("start") : 0;
            $doctor_id         = (int)Input::get("doctor_id");// Only ADMIN & SUPPORTER can use this filter.
            $room_id           = (int)Input::get("room_id");// Only ADMIN & SUPPORTER can use this filter.
            $date           = Input::get("date");
            $status         = Input::get("status");
            $speciality_id     = (int)Input::get("speciality_id");
            $start          = Input::get("start");
            try
            {
                /**Step 3 - query */
                $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_id", "=", $AuthUser->get("id"))
                        ->leftJoin(TABLE_PREFIX.TABLE_DOCTORS, 
                                    TABLE_PREFIX.TABLE_DOCTORS.".id", "=", TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id")
                        ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES,
                                    TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")
                        ->leftJoin(TABLE_PREFIX.TABLE_ROOMS,
                                    TABLE_PREFIX.TABLE_ROOMS.".id", "=", TABLE_PREFIX.TABLE_DOCTORS.".room_id")
                        ->select([
                            TABLE_PREFIX.TABLE_APPOINTMENTS.".*",

                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".id as doctor_id"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".email as doctor_email"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".phone as doctor_phone"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".name as doctor_name"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".description as doctor_description"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".price as doctor_price"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".role as doctor_role"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".active as doctor_active"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".avatar as doctor_avatar"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".create_at as doctor_create_at"),
                            DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".update_at as doctor_update_at"),

                            DB::raw(TABLE_PREFIX.TABLE_ROOMS.".id as room_id"),
                            DB::raw(TABLE_PREFIX.TABLE_ROOMS.".name as room_name"),
                            DB::raw(TABLE_PREFIX.TABLE_ROOMS.".location as room_location"),

                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".id as speciality_id"),
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".name as speciality_name"),
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".description as speciality_description"),
                            
                        ]);
    
                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {
                        $q->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_name", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_phone", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_reason", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", 'LIKE', $search_query.'%');
                    }); 
                }

    
                /**Step 3.3 - date filter*/
                if( $date )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date);
                }

                /**Step 3.4 - room filter*/
                if( $room_id )
                {
                    $query->where(TABLE_PREFIX.TABLE_DOCTORS.".room_id", "=", $room_id);
                }

                if( $speciality_id )
                {
                    $query->where(TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", $speciality_id);
                }
                
                /**Step 3.5 - date filter*/
                $valid_status = ["processing", "done", "cancelled"];
                $status_validation = in_array($status, $valid_status);
                if( $status && $status_validation )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", "=", $status);
                }


                /**Step 3.7 - order filter */
                if( $order && isset($order["column"]) && isset($order["dir"]))
                {
                    $type = $order["dir"];
                    $validType = ["asc","desc"];
                    $sort =  in_array($type, $validType) ? $type : "desc";
    
    
                    $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
                    $column_name = str_replace(".", "_", $column_name);
    
    
                    if(in_array($column_name, ["patient_name", "patient_reason", "appointment_time", "status", "create_at", "update_at"])){
                        $query->orderBy(DB::raw($column_name. " * 1"), $sort);
                    }else{
                        $query->orderBy($column_name, $sort);
                    }
                }
                else 
                {
                    $query->orderBy("id", "desc");
                } 
    
                $res = $query->get();
                $quantity = count($res);

                /**Step 3.4 - length filter * start filter - pagination 1*/
                $query->limit($length ? $length : 5)
                    ->offset($start ? $start : 0);

                /**Step 3.4 - length filter * start filter - pagination 2 - no limit*/
                
                
                /**Step 4 */
                $result = $query->get();
                foreach($result as $element)
                {
                    $data[] = array(
                        "id" => (int)$element->id,
                        "date" => $element->date,
                        "numerical_order" => (int)$element->numerical_order,
                        "position" => (int)$element->position,
                        "patient_id" => (int)$element->patient_id,
                        "patient_name" => $element->patient_name,
                        "patient_phone" => $element->patient_phone,
                        "patient_birthday" => $element->patient_birthday,
                        "patient_reason" => $element->patient_reason,
                        "patient_phone" => $element->patient_phone,
                        "appointment_time" => $element->appointment_time,
                        "status" => $element->status,
                        "create_at" => $element->create_at,
                        "update_at" => $element->update_at,
                        "doctor" => array(
                            "id" => (int)$element->doctor_id,
                            "email" => $element->doctor_email,
                            "phone" => $element->doctor_phone,
                            "name" => $element->doctor_name,
                            "description" => $element->doctor_description,
                            "price" => $element->doctor_price,
                            "role" => $element->doctor_role,
                            "avatar" => $element->doctor_avatar,
                            "active" => (int)$element->doctor_active,
                            "create_at" => $element->doctor_create_at,
                            "update_at" => $element->doctor_update_at,
                        ),
                        "speciality" => array(
                            "id" => (int)$element->speciality_id,
                            "name" => $element->speciality_name,
                            "description" => $element->speciality_description
                        ),
                        "room" => array(
                            "id" => (int)$element->room_id,
                            "name" => $element->room_name,
                            "location" => $element->room_location
                        )
                    );
                }
    
    
                /**Step 5 - return */
                $this->resp->result = 1;
                $this->resp->quantity = $quantity;
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