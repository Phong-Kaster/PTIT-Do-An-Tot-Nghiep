<?php
    /**
     * @author Phong-Kaster
     * @since 18-10-2022
     * this functions helps patients make appointments with doctors
     */
    class PatientBookingController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser)
            {
                header("Location: ".APPURL."/login");
                exit;
            }

            /**Only patients can do this action and they does not have "role" field */
            if( $AuthUser->get("role") )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You are not logging with PATIENT account so that you are not allowed do this action !";
                $this->jsonecho();
            }

            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getAll();
            }
            if( $request_method === 'POST')
            {
                //print_r($request_method );
                $this->save();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 18-10-2022
         * get all bookings that the current patient has been making
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

            try
            {
                /**Step 3 - query */
                $query = DB::table(TABLE_PREFIX.TABLE_BOOKINGS)
                        ->leftJoin(TABLE_PREFIX.TABLE_SERVICES, 
                                    TABLE_PREFIX.TABLE_SERVICES.".id","=", TABLE_PREFIX.TABLE_BOOKINGS.".service_id")
                        ->select([
                            TABLE_PREFIX.TABLE_BOOKINGS.".*",
                            DB::raw(TABLE_PREFIX.TABLE_SERVICES.".id as service_id"),
                            DB::raw(TABLE_PREFIX.TABLE_SERVICES.".name as service_name"),
                        ]);

                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {
                        $q->where(TABLE_PREFIX.TABLE_SERVICES.".booking_name", 'LIKE', $search_query.'%')
                            ->orWhere(TABLE_PREFIX.TABLE_SERVICES.".name", 'LIKE', $search_query.'%')
                            ->orWhere(TABLE_PREFIX.TABLE_SERVICES.".appointment_time", 'LIKE', $search_query.'%')
                            ->orWhere(TABLE_PREFIX.TABLE_SERVICES.".status", 'LIKE', $status.'%')
                            ->orWhere(TABLE_PREFIX.TABLE_SERVICES.".reason", 'LIKE', $search_query.'%');
                    }); 
                }
                
                /**Step 3.2 - order filter */
                if( $order && isset($order["column"]) && isset($order["dir"]))
                {
                    $type = $order["dir"];
                    $validType = ["asc","desc"];
                    $sort =  in_array($type, $validType) ? $type : "desc";


                    $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
                    $column_name = str_replace(".", "_", $column_name);


                    if(in_array($column_name, ["booking_name", "name", "appointment_time", "status"])){
                        $query->orderBy(DB::raw($column_name. " * 1"), $sort);
                    }else{
                        $query->orderBy($column_name, $sort);
                    }
                }
                else 
                {
                    $query->orderBy("id", "desc");
                } 

                /**Step 3.3 - length filter * start filter*/
                $query->limit($length ? $length : 10)
                    ->offset($start ? $start : 0);



                /**Step 4 */
                $result = $query->get();
                foreach($result as $element)
                {
                    $data[] = array(
                        "id" => (int)$element->id,
                        "booking_name" => $element->booking_name,
                        "booking_phone" => $element->booking_phone,
                        "name" => $element->name,
                        "gender" => (int)$element->gender,
                        "birthday" => $element->birthday,
                        "address" => $element->address,
                        "reason" => $element->reason,
                        "appointment_time" => $element->appointment_time,
                        "status" => $element->status,
                        "create_at" => $element->create_at,
                        "update_at" => $element->update_at,
                        "service" => array(
                            "id" => (int)$element->service_id,
                            "name" => $element->service_name
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
         * @since 18-10-2022
         * create a new paint
         */
        private function save()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data = [];


            /**Step 2 - get required data */
            $required_fields = ["service_id", "booking_name", "booking_phone",
                                "name", "appointment_time"];
            foreach($required_fields as $field)
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }


            /**Step 3 - get data*/
            $service_id = Input::post("service_id");
            $booking_name = Input::post("booking_name");

            $booking_phone = Input::post("booking_phone");
            $name = Input::post("name");

            $gender = Input::post("gender") ? Input::post("gender") : 0;
            $birthday = Input::post("birthday");

            $address = Input::post("address");
            $reason = Input::post("reason");

            $appointment_time = Input::post("appointment_time");
            $status = "processing";

            $create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            /**Step 4 - validation */
            /**Step 4.1 - service validation */
            $Service = Controller::model("Service", $service_id);
            if( !$Service->isAvailable() )
            {
                $this->resp->msg = "Service is not available";
                $this->jsonecho();
            }

            /**Step 4.2 - Booking Name */
            $booking_name_validation = isVietnameseName($booking_name);
            if( $booking_name_validation == 0 ){
                $this->resp->msg = "( Booking name ) Vietnamese name only has letters and space";
                $this->jsonecho();
            }

            /**Step 4.3 - Booking Phone */
            if( strlen($booking_phone) < 10 ){
                $this->resp->msg = "Booking number has at least 10 number !";
                $this->jsonecho();
            }
    
            $booking_phone_validation = isNumber($booking_phone);
            if( !$booking_phone_validation ){
                $this->resp->msg = "Booking phone is not a valid phone number. Please, try again !";
                $this->jsonecho();
            }

            /**Step 4.4 - Name */
            $name_validation = isVietnameseName($name);
            if( $name_validation == 0 ){
                $this->resp->msg = "( Name ) Vietnamese name only has letters and space";
                $this->jsonecho();
            }

            /**Step 4.5 - Gender */
            $valid_gender = [ 0,1 ];
            $gender_validation = in_array($gender, $valid_gender);
            if( !$gender_validation )
            {
                $this->resp->msg = "Gender is not valid. There are 2 values: 0 is female & 1 is men";
                $this->jsonecho();
            }


            /*Step 4.6 - birthday validation */
            if( $birthday )
            {
                $msg = isBirthdayValid($birthday);
                if( !empty($msg) )
                {
                    $this->resp->msg = $msg;
                    $this->jsonecho();
                }
            }

            /**Step 4.7 - Address */
            $address_validation = isAddress($address);
            if( $address_validation == 0)
            {
                $this->resp->msg = "Address only accepts letters, space & number";
                $this->jsonecho();
            }

            /**Step 4.8 - Reason */

            /**Step 4.9 - appointment */
            $output = isAppointmentTimeValid($appointment_time);
            if( !empty($output) )
            {
                $this->resp->msg = $output;
                $this->jsonecho();
            }



            /**Step 4.10 - Status */
            $valid_status = ["processing", "verified", "cancelled"];
            $status_validation = in_array($status, $valid_status);
            if( !$status_validation )
            {
                $this->resp->msg = "Status value is not valid. There are "
                                    .count($valid_status)
                                    ." values accepted: ".implode(', ',$valid_status);
                $this->jsonecho();
            }

            try 
            {
                $Booking = Controller::model("Booking");
                $Booking->set("service_id", $service_id)
                    ->set("patient_id", $AuthUser->get("id"))
                    ->set("booking_name", $booking_name)
                    ->set("booking_phone", $booking_phone)
                    ->set("name", $name)
                    ->set("gender", $gender)
                    ->set("birthday", $birthday)
                    ->set("address", $address)
                    ->set("reason", $reason)
                    ->set("appointment_time", $appointment_time)
                    ->set("status", $status)
                    ->set("create_at", $create_at)
                    ->set("update_at", $update_at)
                    ->save();
                
                $this->resp->result = 1;
                $this->resp->msg = "Congratulation, ".$AuthUser->get("name")."! Your booking at "
                                    .$Booking->get("appointment_time")
                                    ." which has been created successfully. Keep in touch, our service staff will contact you as soon as possible ";
                $this->resp->data = array(
                    "id" => (int)$Booking->get("id"),
                    "booking_name" => $Booking->get("booking_name"),
                    "booking_phone" => $Booking->get("booking_phone"),
                    "name" => $Booking->get("name"),
                    "gender" => (int)$Booking->get("gender"),
                    "birthday" => $Booking->get("birthday"),
                    "address" => $Booking->get("address"),
                    "reason" => $Booking->get("reason"),
                    "appointment_time" => $Booking->get("appointment_time"),
                    "status" => $Booking->get("status"),
                    "create_at" => $Booking->get("create_at"),
                    "update_at" => $Booking->get("update_at")
                );
            } 
            catch (\Exception $ex) {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>