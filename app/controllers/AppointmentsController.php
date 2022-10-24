<?php
    /**
     * @author Phong-Kaster
     * @since 20-10-2022
     * this Controller handles appointments between DOCTORS and PATIENTS
     */
    class AppointmentsController extends Controller
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
            $doctor_id      = Input::get("doctor_id");// Only ADMIN & SUPPORTER can use this filter.
            $length         = Input::get("length") ? (int)Input::get("length") : 10;
            $start          = Input::get("start") ? (int)Input::get("start") : 0;
    
            try
            {
                /**Step 3 - query */
                $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                        ->select([
                            TABLE_PREFIX.TABLE_APPOINTMENTS.".*"
                        ]);

                /**Step 3.0 - role - as MEMBER, he/she only see appointments that has been assigned to him/her. */
                $valid_roles = ["admin", "supporter"];
                $role_validation = in_array($AuthUser->get("role"), $valid_roles);
                if( !$role_validation )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $AuthUser->get("id"));
                }
    
                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {
                        $q->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_name", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_phone", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_reason", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", 'LIKE', $search_query.'%');
                    }); 
                }


                /**Step 3.2 - doctor_id filter - only ADMIN and SUPPORTER can use */
                $valid_roles = ["admin", "supporter"];
                $role_validation = in_array($AuthUser->get("role"), $valid_roles);
                if( $doctor_id && $role_validation )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id);
                }
    
                
                /**Step 3.3 - order filter */
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
    
                /**Step 3.4 - length filter * start filter*/
                $query->limit($length ? $length : 10)
                    ->offset($start ? $start : 0);
    
    
    
                /**Step 4 */
                $result = $query->get();
                foreach($result as $element)
                {
                    $data[] = array(
                        "id" => (int)$element->id,
                        "date" => $element->date,
                        "doctor_id" => (int)$element->doctor_id,
                        "numerical_order" => (int)$element->numerical_order,
                        "patient_id" => (int)$element->patient_id,
                        "patient_name" => $element->patient_name,
                        "patient_phone" => $element->patient_phone,
                        "patient_birthday" => $element->patient_birthday,
                        "patient_reason" => $element->patient_reason,
                        "patient_phone" => $element->patient_phone,
                        "appointment_time" => $element->appointment_time,
                        "status" => $element->status,
                        "create_at" => $element->create_at,
                        "update_at" => $element->update_at
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
         * @since 20-10-2022
         * create a new appointment
         * 
         * doctor's role as ADMIN or SUPPORTER can see all appointments
         * doctor's role as MEMBER only can see appointments which was assigned to them.
         * 
         * if guess with no appointment, default date is today & numerical order is set immediately
         * if guess with appointment, numerical order is not set. Only when they come to hospital, they will have it.
         */
        private function save()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");


            /**Step 2 - verify user's role */
            $valid_roles = ["admin", "supporter"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You don't have permission to do this action. Only "
                .implode(', ', $valid_roles)." can do this action !";
                $this->jsonecho();
            }


            /**Step 3 - get required fields*/
            $required_fields = ["doctor_id", "patient_name", "patient_birthday",
                                "patient_reason"];
            foreach($required_fields as $field)
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }


            /**Step 4 - get data */
            $doctor_id = Input::post("doctor_id");
            $patient_id = Input::post("patient_id") ? Input::post("patient_id") : 1;

            $patient_name = Input::post("patient_name");
            $patient_birthday = Input::post("patient_birthday");

            $patient_reason = Input::post("patient_reason");
            $patient_phone = Input::post("patient_phone");

            $numerical_order = "";
            $appointment_time = Input::post("appointment_time") ? Input::post("appointment_time") : "";

            $status = "processing";// default
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            $create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            /**Step 5 - validation */
            /**Step 5.1 - doctor validation */
            $Doctor = Controller::model("Doctor", $doctor_id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "This doctor does not exist !";
                $this->jsonecho();
            }
            if( $Doctor->get("active") != 1)
            {
                $this->resp->msg = "This doctor was deactivated !";
                $this->jsonecho();
            }

            /**Step 5.2 - patient validation - patient with ID = 1 is default. In case, 
             * patient have not logged in Android application.
             */
            if( $patient_id != 1)
            {
                $Patient = Controller::model("Patient", $patient_id);
                if( !$Patient->isAvailable() )
                {
                    $this->resp->msg = "This patient does not exist !";
                    $this->jsonecho();
                }
            }

            /**Step 5.3 - patient name validation */
            $patient_name_validation = isVietnameseName($patient_name);
            if( $patient_name_validation == 0 ){
                $this->resp->msg = "( Booking name ) Vietnamese name only has letters and space";
                $this->jsonecho();
            }

            /**Step 5.4 - patient birthday validation */
            $msg = isBirthdayValid($patient_birthday);
            if( !empty($msg) )
            {
                $this->resp->msg = $msg;
                $this->jsonecho();
            }
            
            /**Step 5.5 - patient reason */

            /**Step 5.6 - patient phone */
            if( $patient_phone )
            {
                if( strlen($patient_phone) < 10 )
                {
                    $this->resp->msg = "Patient phone number has at least 10 number !";
                    $this->jsonecho();
                }
        
                $patient_phone_validation = isNumber($patient_phone);
                if( !$patient_phone_validation )
                {
                    $this->resp->msg = "Patient phone number is not a valid phone number. Please, try again !";
                    $this->jsonecho();
                }
            }

            /**Step 5.7 - numerical order */
            /**Step 5.7.1 - Case 1 - appointment time is EMPTY so that date is today by default*/
            $date = Date("d-m-Y");


            /**Step 5.7.2 -Case 2 - appointment time is not EMPTY => date equals to date of appointment_time */
            if( !empty($appointment_time) )
            {
                $msg = isAppointmentTimeValid($appointment_time);
                if( !empty($msg) )
                {
                    $this->resp->msg = $msg;
                    $this->jsonecho();
                }

                $date = substr($appointment_time, 0,10);
            }

            /**Step 5.7.3 - if guess with no appointment => get numerical order immediately */
            if( empty($appointment_time) )
            {
                $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id)
                ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date)
                ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "=", "")
                ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "desc")
                ->select("*");

                $result = $query->get();
                $quantity = count($result);
                $numerical_order = $quantity == 0 ? 1 : $quantity+ 1;// because first value = 0
            }
            /**If  guess with appointment => numerical order is empty, only when they go to hospital, they will have numerical order */
            else
            {
                $numerical_order = "";
            }



            /**Step 5.8 - status */
            $valid_status = ["processing", "done", "cancelled"];
            $status_validation = in_array($status, $valid_status);
            if( !$status_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You don't have permission to do this action. Only "
                .implode(', ', $valid_status)." can do this action !";
                $this->jsonecho();
            }

            /**Step 6 - save */
            try 
            {
                $Appointment = Controller::model("Appointment");
                $Appointment->set("doctor_id", $doctor_id)
                        ->set("patient_id", $patient_id)
                        ->set("patient_name", $patient_name)
                        ->set("patient_birthday", $patient_birthday)
                        ->set("patient_reason", $patient_reason)
                        ->set("patient_phone", $patient_phone)
                        ->set("numerical_order", $numerical_order)
                        ->set("appointment_time", $appointment_time)
                        ->set("date", $date)
                        ->set("status", $status)
                        ->set("create_at", $create_at)
                        ->set("update_at", $update_at)
                        ->save();
                $this->resp->result = 1;
                $this->resp->msg = "Appointment has been created successfully !";
                $this->resp->data = array(
                    "id" => (int) $Appointment->get("id"),
                    "numerical_order" =>  (int)$Appointment->get("numerical_order"),
                    "date"          => $Appointment->get("date"),
                    "doctor_id" => (int) $Appointment->get("doctor_id"),
                    "patient_id" => (int) $Appointment->get("patient_id"),
                    "patient_name" => $Appointment->get("patient_name"),
                    "patient_birthday" =>  $Appointment->get("patient_birthday"),
                    "patient_reason" =>  $Appointment->get("patient_reason"),
                    "patient_phone" =>  $Appointment->get("patient_phone"),
                    "appointment_time" => $Appointment->get("appointment_time"),
                    "status" =>  $Appointment->get("status"),
                    "create_at" =>  $Appointment->get("create_at"),
                    "update_at" =>  $Appointment->get("update_at")
                );
            } 
            catch (\Exception $ex) 
            {
                $this->resp->result = $ex->getMessage();
            }
            $this->jsonecho();
        }   
    }
?>