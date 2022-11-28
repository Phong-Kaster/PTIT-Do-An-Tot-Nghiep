<?php
    /**
     * @author Phong-Kaster
     * @since 24-10-2022
     * this function is used to get queue to support Appointments's function - Get All.
     * 
     */
    class AppointmentQueueController extends Controller
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
                $request = Input::get("request");
                switch ($request) 
                {
                    case 'all':
                        $this->getAll();
                        break;
                    case 'queue':
                        $this->getQueue();
                    default:
                        $this->getAll();
                        break;
                }
            }
            if($request_method === 'POST')
            {
                $this->arrange();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 24-10-2022
         * this function returns queue of all appointments
         * all users can use this function
         */
        private function getAll()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data = [];
            $msg = "All appointments";


            /**Step 2 - get filters */
            $type           = Input::get("type") ? strtolower(Input::get("type") ): "all"; 
            $order          = Input::get("order");
            $search         = Input::get("search");
            $length         = Input::get("length") ? (int)Input::get("length") : 10;
            $start          = Input::get("start") ? (int)Input::get("start") : 0;
            $doctor_id         = Input::get("doctor_id");// Only ADMIN & SUPPORTER can use this filter.
            $room           = Input::get("room");// Only ADMIN & SUPPORTER can use this filter.
            $date           = Input::get("date");
            $status         = Input::get("status");


            /**Step 3 - declare query */
            $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                        ->select([
                            TABLE_PREFIX.TABLE_APPOINTMENTS.".*"
                        ]);

            // switch($type){
            //     case "all":
            //         $msg = "All appointments";
            //         break;
            //     case "normal":
            //         $msg = "Normal appointments";
            //         $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "=" , "");
            //         break;
            //     case "booking":
            //         $msg = "Booking appointments";
            //         $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "!=", "");
            //         break;
            //     default:
            //         $msg = "All appointments";
            // }

            try
            {
                /**Step 3.0 - 
                 * role as ADMIN or SUPPORTER can pass doctor_id to filter
                 * PATIENT can also pass doctor_id to filter
                 * role - as MEMBER, he/she only see appointments that has been assigned to him/her. */
                $valid_roles = ["admin", "supporter"];
                $role_validation = in_array($AuthUser->get("role"), $valid_roles);
                if( $role_validation || !$AuthUser->get("role"))
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id);
                }
                else
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
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", 'LIKE', $search_query.'%');
                    }); 
                }
                /**Step 3.2 - date filter*/
                if( $date )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date);
                    $msg .= " at ".$date;
                }

                /**Step 3.3 - doctor_id filter - only ADMIN and SUPPORTER can use */
                $valid_roles = ["admin", "supporter"];
                $role_validation = in_array($AuthUser->get("role"), $valid_roles);
                if( $doctor_id && $role_validation )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id);

                    $msg .= " - doctor ID: ".$doctor_id;
                    $Doctor = Controller::model("Doctor", $doctor_id);
                    if( $Doctor->isAvailable() )
                    {
                        $msg .= " - ".$Doctor->get("name");
                    }
                }
    


                /**Step 3.4 - room filter*/
                if( $room )
                {
                    $query->leftJoin(TABLE_PREFIX.TABLE_DOCTORS,
                                        TABLE_PREFIX.TABLE_DOCTORS.".id", "=", TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id")
                        ->where(TABLE_PREFIX.TABLE_DOCTORS.".room_id", "=", $room);
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
    
                /**Step 3.4 - length filter * start filter*/
                $query->limit($length ? $length : 10)
                    ->offset($start ? $start : 0);

    
                /**Step 4 */
                $result = $query->get();
                foreach($result as $element)
                {
                    $data[] = array(
                        "position" => (int)$element->position,
                        "numerical_order" => (int)$element->numerical_order,
                        "id" => (int)$element->id,
                        "patient_id" => (int)$element->patient_id,
                        "patient_name" => $element->patient_name,
                        "doctor_id" => (int)$element->doctor_id,
                        "appointment_time" => $element->appointment_time,
                        "status" => $element->status,
                    );
                }
    
                /**Step 5 - return */
                $this->resp->result = 1;
                $this->resp->msg = $msg;
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
         * @since 24-10-2022
         * this function arrange appointment's POSITION
         * we can only arrange for today appointments.
         * only doctor's role as ADMIN or SUPPORTER can use
         */
        private function oldFlowArrange()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");


            /**Step 2 - check AuthUser role */
            $valid_roles = ["admin", "supporter"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->msg = "Only ".implode(', ', $valid_roles)." can arrange appointments";
                $this->jsonecho();
            }

            /**Step 3 - get required data */
            $required_fields = ["doctor_id", "type", "positions"];
            foreach($required_fields as $field)
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            $doctor_id = Input::post("doctor_id");
            $type = Input::post("type");
            $positions = Input::post("positions");
            $date = Date("d-m-Y");// by default, date is today.

            /**Step 4 - validation */
            /**Step 4.1 - doctor_id validation */
            $Doctor = Controller::model("Doctor", $doctor_id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "Doctor is not available !";
                $this->jsonecho();
            }
            if( $Doctor->get("active") != 1)
            {
                $this->resp->msg = "This doctor account was deactivated. No need this action !";
                $this->jsonecho();
            }

            /**Step 4.2 - type validation */
            $valid_types = ["normal", "booking"];
            $type_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->msg = "Type is not valid. There are ".count($valid_types)." type: ".implode(', ', $valid_roles)." !";
                $this->jsonecho();
            }

            /**Step 4.4 - positions validation*/
            if( is_array($positions) != 1)
            {
                $this->resp->msg = "Positions is not valid.";
                $this->jsonecho();
            }


            $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date);
            switch ($type) {
                case 'normal':
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "=", "");
                    break;
                case 'booking':
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "!=", "");
                    break;
                default:
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "=", "");
                    break;
            }
            $appointments = $query->get();
            $sizePositions = count($positions);
            $sizeAppointments = count($appointments);


            if( $sizePositions < $sizeAppointments )
            {
                $this->resp->msg = "Appointment position does not match with quantity of appointment."
                                ." Positions have ".$sizePositions
                                ." but a mount of appointments is ".$sizeAppointments;
                $this->jsonecho();
            }

            /**Step 5 - save change */
            try 
            {
                $position = 1;
                foreach($positions as $element)
                {
                    $Appointment = Controller::model("Appointment", $element);
                    $Appointment->set("position", $position)
                            ->save();
                    $position++; 
                }

                $this->resp->result = 1;
                $this->resp->msg = "Appointments have been updated their positions";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }

                /**
         * @author Phong-Kaster
         * @since 24-10-2022
         * this function arrange appointment's POSITION
         * we can only arrange for today appointments.
         * only doctor's role as ADMIN or SUPPORTER can use
         */
        private function arrange()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");


            /**Step 2 - check AuthUser role */
            $valid_roles = ["admin", "supporter"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->msg = "Only ".implode(', ', $valid_roles)." can arrange appointments";
                $this->jsonecho();
            }

            /**Step 3 - get required data */
            $required_fields = ["doctor_id", "queue"];
            foreach($required_fields as $field)
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            $doctor_id = Input::post("doctor_id");
            $type = Input::post("type");
            $queue = Input::post("queue");
            $date = Date("d-m-Y");// by default, date is today.

            /**Step 4 - validation */
            /**Step 4.1 - doctor_id validation */
            $Doctor = Controller::model("Doctor", $doctor_id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "Doctor is not available !";
                $this->jsonecho();
            }
            if( $Doctor->get("active") != 1)
            {
                $this->resp->msg = "This doctor account was deactivated. No need this action !";
                $this->jsonecho();
            }

            /**Step 4.2 - type validation */
            // $valid_types = ["normal", "booking"];
            // $type_validation = in_array($AuthUser->get("role"), $valid_roles);
            // if( !$role_validation )
            // {
            //     $this->resp->msg = "Type is not valid. There are ".count($valid_types)." type: ".implode(', ', $valid_roles)." !";
            //     $this->jsonecho();
            // }

            /**Step 4.4 - positions validation*/
            if( is_array($queue) != 1)
            {
                $this->resp->msg = "Queue's format is not valid.";
                $this->jsonecho();
            }


            $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", "=", "processing")
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date);
            $result = $query->get();
            

            /**Step 5 - save change */
            try 
            {
                $position = 1;
                foreach($queue as $element)
                {
                    $Appointment = Controller::model("Appointment", (int)$element);
                    if( $Appointment->get("doctor_id") != $doctor_id )
                    {
                        continue;
                    }
                    $Appointment->set("position", $position)
                            ->save();
                    $position++; 
                }

                $this->resp->result = 1;
                $this->resp->msg = "Appointments have been updated their positions";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }

        /**
         * @author Phong-Kaster
         * @since 24-10-2022
         * get current arrange appointment for both NORMAL and BOOKING.
         */
        private function getQueue()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $date = Date("d-m-Y");


            /**If AuthUser is MEMBER doctor */
            if( $AuthUser->get("role") == "member")
            {
                $doctor_id = $AuthUser->get("id");
            }
            /**If AuthUser is ADMIN, SUPPORTER or PATIENTS */
            else
            {
                $doctor_id = Input::post("doctor_id");
                if( $doctor_id )
                {
                    $this->resp->msg = "Missing doctor ID";
                    $this->jsonecho();
                }
            }

            /**Step 2 get current normal appointment or next appointment */
            $queryNormal = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", "=", "processing")
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "=", "")
                        ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".position", "asc")
                        ->limit(2);
            $resultNormal = $queryNormal->get();

            print_r("current ".$resultNormal[0]->id);
            print_r("\n next ".$resultNormal[1]->id);
        }
    }
?>