<?php
    /**
     * @author Phong-Kaster
     * @since 20-10-2022
     * Booking Controller is used to change - update bookings created by patients
     * This controller is used by Doctor plays roles as admin & supporter
     */
    class BookingController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");

            if (!$AuthUser)
            {
                header("Location: ".APPURL."/login");
                exit;
            }
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

            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getById();
            }
            else if( $request_method === 'PUT')
            {
                $this->update();
            }
            else if( $request_method === 'PATCH')
            {
                $this->confirm();
            }
        }



        /**
         * @author Phong-Kaster
         * @since 20-10-2022
         * get booking info by id
         * this function is only used by ADMIN & SUPPORTER
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


            /**Step 3 - get by id */
            try
            {
                $Booking = Controller::model("Booking", $Route->params->id);
                if( !$Booking->isAvailable() )
                {
                    $this->resp->msg = "Booking is not available";
                    $this->jsonecho();
                }

                $query = DB::table(TABLE_PREFIX.TABLE_BOOKINGS)
                        ->where(TABLE_PREFIX.TABLE_BOOKINGS.".id", "=", $Route->params->id)
                        ->leftJoin(TABLE_PREFIX.TABLE_SERVICES, 
                                    TABLE_PREFIX.TABLE_SERVICES.".id","=", TABLE_PREFIX.TABLE_BOOKINGS.".service_id")
                        ->select([
                            TABLE_PREFIX.TABLE_BOOKINGS.".*",
                            DB::raw(TABLE_PREFIX.TABLE_SERVICES.".id as service_id"),
                            DB::raw(TABLE_PREFIX.TABLE_SERVICES.".name as service_name"),
                        ]);

                $result = $query->get();
                if( count($result) > 1 )
                {
                    $this->resp->msg = "Oops, there is an error occurring. Try again !";
                    $this->jsonecho();
                }

                
                $data = array(
                    "id" => (int)$result[0]->id,
                    "patient_id" => (int)$result[0]->patient_id,
                    "booking_name" => $result[0]->booking_name,
                    "booking_phone" => $result[0]->booking_phone,
                    "name" => $result[0]->name,
                    "gender" => (int)$result[0]->gender,
                    "birthday" => $result[0]->birthday,
                    "reason" => $result[0]->reason,
                    "appointment_time" => $result[0]->appointment_time,
                    "appointment_date" => $result[0]->appointment_date,
                    "status" => $result[0]->status,
                    "create_at" => $result[0]->create_at,
                    "update_at" => $result[0]->update_at,
                    "service" => array(
                        "id" => (int)$result[0]->service_id,
                        "name" => $result[0]->service_name
                    )
                );

                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
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
         * update a booking info except STATUS
         * this function is only used by ADMIN & SUPPORTER
         */
        private function update()
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
            $Booking = Controller::model("Booking", $Route->params->id);
            if( !$Booking->isAvailable() )
            {
                $this->resp->msg = "This booking does not exist !";
                $this->jsonecho();
            }
            $valid_roles = ["processing","verified"];
            $role_validation = in_array($Booking->get("status"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You don't have permission to do this action. Only booking's status is "
                .implode(', ', $valid_roles)." can do this action !";
                $this->jsonecho();
            }


            /**Step 2 - get required data */
            $required_fields = ["service_id", "booking_name", "booking_phone",
                                "name", "appointment_time", "appointment_date"];
            foreach($required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }


            /**Step 3 - get data*/
            $service_id = Input::put("service_id");
            $booking_name = Input::put("booking_name");

            $booking_phone = Input::put("booking_phone");
            $name = Input::put("name");

            $gender = Input::put("gender") ? Input::put("gender") : 0;
            $birthday = Input::put("birthday");

            $address = Input::put("address");
            $reason = Input::put("reason");

            $appointment_time = Input::put("appointment_time");
            $appointment_date = Input::put("appointment_date");
            $status = $Booking->get("status");

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $update_at = date("Y-m-d H:i:s");

            /**Step 4 - validation */
            /**Step 4.0 - service validation */
            // $Patient = Controller::model("Patient", $patient_id);
            // if( !$Patient->isAvailable() )
            // {
            //     $this->resp->msg = "Patient is not available";
            //     $this->jsonecho();
            // }

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
            $input = $appointment_date." ".$appointment_time;
            $output = isAppointmentTimeValid($input);
            if( !empty($output) )
            {
                $this->resp->msg = $output;
                $this->jsonecho();
            }



            /**Step 4.10 - Status - booking is only updated if its status is processing or verified*/
            $valid_status = ["processing"];
            $status_validation = in_array($status, $valid_status);
            if( !$status_validation )
            {
                $this->resp->msg = "Booking's status is ".$Booking->get("status")." now. Booking is only updated when its status: "
                                    .implode(', ',$valid_status)." !";
                $this->jsonecho();
            }

            try 
            {
                $Booking->set("service_id", $service_id)
                    ->set("booking_name", $booking_name)
                    ->set("booking_phone", $booking_phone)
                    ->set("name", $name)
                    ->set("gender", $gender)
                    ->set("birthday", $birthday)
                    ->set("address", $address)
                    ->set("reason", $reason)
                    ->set("appointment_time", $appointment_time)
                    ->set("appointment_date", $appointment_date)
                    ->set("status", $status)
                    ->set("update_at", $update_at)
                    ->save();
                
                $this->resp->result = 1;
                $this->resp->msg = "Congratulation, doctor ".$AuthUser->get("name")."! Your booking at "
                                    .$Booking->get("appointment_time")
                                    ." which has been created successfully.";
                $this->resp->data = array(
                    "id" => (int)$Booking->get("id"),
                    "patient_id" => (int)$Booking->get("patient_id"),
                    "booking_name" => $Booking->get("booking_name"),
                    "booking_phone" => $Booking->get("booking_phone"),
                    "name" => $Booking->get("name"),
                    "gender" => (int)$Booking->get("gender"),
                    "birthday" => $Booking->get("birthday"),
                    "address" => $Booking->get("address"),
                    "reason" => $Booking->get("reason"),
                    "appointment_time" => $Booking->get("appointment_time"),
                    "appointment_date" => $Booking->get("appointment_date"),
                    "status" => $Booking->get("status"),
                    "create_at" => $Booking->get("create_at"),
                    "update_at" => $Booking->get("update_at"),
                    "service" => array(
                        "id"=> (int)$Service->get("id"),
                        "name"=>$Service->get("name")
                    )
                );
            } 
            catch (\Exception $ex) {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }


        /**
         * @author Phong-Kaster
         * @since 20-10-2022
         * Case 1 - booking's status is PROCESSING, we confirm VERIFIED
         * => booking's status is VERIFIED & create new appointment with doctor_id
         * 
         * Case 2 - booking's status is PROCESSING, we confirm CANCELLED
         * => booking's status is CANCELLED.
         */
        private function confirm()
        {
            /**Step 1 - declare  */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");
            $AuthUser = $this->getVariable("AuthUser");
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $update_at = date("Y-m-d H:i:s");



            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }
            $Booking = Controller::model("Booking", $Route->params->id);
            if( !$Booking->isAvailable() )
            {
                $this->resp->msg = "This booking does not exist !";
                $this->jsonecho();
            }
            $valid_roles = ["processing"];
            $role_validation = in_array($Booking->get("status"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You don't have permission to do this action. Only booking's status is "
                .implode(', ', $valid_roles)." can do this action !";
                $this->jsonecho();
            }


            /**Step 3 - validation */
            /**Step 3.1 - new Status validation */
            if( !Input::patch("newStatus") )
            {
                $this->resp->msg = "New status is required to continue !";
                $this->jsonecho();
            }
            $new_status = Input::patch("newStatus");
            $valid_status = ["verified", "cancelled"];
            $status_validation = in_array($new_status, $valid_status);
            if( !$status_validation )
            {
                $this->resp->msg = "Booking's status is not valid now. Booking is only updated when its new status: "
                                    .implode(', ',$valid_status)." !";
                $this->jsonecho();
            }

            /**Step 3.2 - current status validation - if status == CANCELLED => stop immediately */
            if( $Booking->get("status") == "cancelled" )
            {
                $this->resp->msg = "This booking's status is ".$Booking->get("status").". No need any action !";
                $this->jsonecho();
            }


            /**Step 3.4 - current status validation - if status == VERIFIED => no need any action*/
            if( $Booking->get("status") == "verified" && $new_status != "cancelled" )
            {
                $this->resp->msg = "This booking's status is ".$Booking->get("status")
                                    ." now & it has been converted an appointment already. No need any action !";
                $this->jsonecho();
            }



            

            /**Step 4 - Case 1 - booking's status is PROCESSING, we confirm VERIFIED
            * => booking's status is VERIFIED & create new appointment with doctor_id */
            if( $new_status == "verified" )
            {
                $msg = "VERIFIED this booking will create a new appointment. Redirect to CREATE - /api/appointment !";

            }


            /**Step 4 - Case 2 - booking's status is PROCESSING, we confirm CANCELLED
            * => booking's status is CANCELLED.*/
            if( $new_status == "cancelled")
            {
                $msg = "Booking has been cancelled successfully !";
            }
            $Booking->set("status", $new_status)
            ->update("update_at", $update_at)
            ->save();

            
            /**Step 5 - save change */            
            $this->resp->result = 1;
            $this->resp->msg = $msg;
            $this->jsonecho();
        }
    }
?>