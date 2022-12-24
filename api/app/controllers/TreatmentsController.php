<?php
    /**
     * @author Phong-Kaster
     * @since 22-10-2022
     * this function is used by ADMIN or MEMBER
     */
    class TreatmentsController extends Controller
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
         * @since 22-10-2022
         * get all treatment
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
            $appointment_id = Input::get("appointment_id");

            try
            {
                /**Step 3 - query */
                $query = DB::table(TABLE_PREFIX.TABLE_TREATMENTS)
                        ->leftJoin(TABLE_PREFIX.TABLE_APPOINTMENTS,
                                    TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "=", TABLE_PREFIX.TABLE_TREATMENTS.".appointment_id")
                        ->select([
                            TABLE_PREFIX.TABLE_TREATMENTS.".*"
                        ]);
                
                if($AuthUser->get("role") == "member")
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $AuthUser->get("id"));
                }
    
                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {
                        $q->where(TABLE_PREFIX.TABLE_TREATMENTS.".name", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_TREATMENTS.".purpose", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_TREATMENTS.".instruction", 'LIKE', $search_query.'%');
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
    
    
                    if(in_array($column_name, ["appointment_id"])){
                        $query->orderBy(DB::raw($column_name. " * 1"), $sort);
                    }else{
                        $query->orderBy($column_name, $sort);
                    }
                }
                else 
                {
                    $query->orderBy("id", "desc");
                } 
                
                if( $appointment_id )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "=", $appointment_id);
                }


                $res = $query->get();
                $quantity = count($res);

                /**Step 3.4 - length filter * start filter*/
                $query->limit($length ? $length : 10)
                    ->offset($start ? $start : 0);
    
    
    
                /**Step 4 */
                $result = $query->get();
                foreach($result as $element)
                {
                    $data[] = array(
                        "id" => (int)$element->id,
                        "appointment_id" => (int)$element->appointment_id,
                        "name" => $element->name,
                        "type" => $element->type,
                        "times" => (int)$element->times,
                        "purpose" => $element->purpose,
                        "instruction" => $element->instruction,
                        "repeat_days" => $element->repeat_days,
                        "repeat_time" => $element->repeat_time,
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


        /**
         * @author Phong-Kaster
         * @since 22-10-2022
         * create new treatments with specific appointment's ID.
         */
        private function save()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            

            /**Step 2 - get required data */
            $required_fields = ["appointment_id", "name", "type", "times", 
                                "purpose", "instruction"];
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
            $name = Input::post("name");
            $type = Input::post("type");
            $times = Input::post("times");
            $purpose = Input::post("purpose");
            $instruction = Input::post("instruction");
            $repeat_days = Input::post("repeat_days") ? Input::post("repeat_days") : "Thực hiện một lần";
            $repeat_time = Input::post("repeat_time") ? Input::post("repeat_time") : "Bác sĩ không chỉ định";


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
                $this->resp->msg = "Today is ".$today." but this appointment's is ".$appointment_date." so that you can not do this action";
                $this->jsonecho();
            }


            /**Step 4.4 - name */
            $name_validation = isAddress($name);
            if( $name_validation == 0 ){
                $this->resp->msg = "Name only has letters and space";
                $this->jsonecho();
            }

            /**Step 4.5 - type */
            $type_validation = isVietnameseName($type);
            if( $type_validation == 0 ){
                $this->resp->msg = "Type only has letters and space";
                $this->jsonecho();
            }

            /**Step 4.6 - times */
            $times_validation = isNumber($times);
            if( !$times_validation ){
                $this->resp->msg = "Treatment's times is not valid. Try again !";
                $this->jsonecho();
            }


            /**Step 4.7 - purpose */
            // $purpose_validation = isVietnameseName($purpose);
            // if( $purpose_validation == 0 ){
            //     $this->resp->msg = "Purpose only has letters and space";
            //     $this->jsonecho();
            // }

            /**Step 4.8 - instruction */
            // $instruction_validation = isAddress($instruction);
            // if( $instruction_validation == 0 ){
            //     $this->resp->msg = "Instruction only has letters and space";
            //     $this->jsonecho();
            // }

            try 
            {
                $Treatment = Controller::model("Treatment");
                $Treatment->set("appointment_id", $appointment_id)
                        ->set("name", $name)
                        ->set("type", $type)
                        ->set("times", $times)
                        ->set("purpose", $purpose)
                        ->set("instruction", $instruction)
                        ->set("repeat_days", $repeat_days)
                        ->set("repeat_time", $repeat_time)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Treatment has been created successfully !";
                $this->resp->data = array(
                    "id" => (int)$Treatment->get("id"),
                    "appointment_id" => (int)$Treatment->get("appointment_id"),
                    "name" => $Treatment->get("name"),
                    "type" => $Treatment->get("type"),
                    "times" => (int)$Treatment->get("times"),
                    "purpose" => $Treatment->get("purpose"),
                    "instruction" => $Treatment->get("instruction"),
                    "repeat_days" => $Treatment->get("repeat_days"),
                    "repeat_time" => $Treatment->get("repeat_time")
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