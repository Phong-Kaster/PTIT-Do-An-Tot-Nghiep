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
                $this->newFlow();
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
                        ->orWhere(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", 'LIKE', $search_query.'%');
                    }); 
                }


                /**Step 3.2 - doctor_id filter - only ADMIN and SUPPORTER can use */
                $valid_roles = ["admin", "supporter"];
                $role_validation = in_array($AuthUser->get("role"), $valid_roles);
                if( $doctor_id && $role_validation )
                {
                    $query->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id);
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
                        "booking_id" => (int)$element->booking_id,
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


        /**
         * @author Phong-Kaster
         * @since 20-10-2022
         * create a new appointment
         * 
         * doctor's role as ADMIN or SUPPORTER can see all appointments
         * doctor's role as MEMBER only can see appointments which was assigned to them.
         * 
         * Case 1 - if guess with no appointment, default date is today & numerical order is set immediately
         * Case 2 - if guess with appointment, numerical order is not set. Only when they come to hospital, they will have it.
         * 
         * Cụ thể, ta chi bệnh nhân thành 2 loại là NORMAL và BOOKING 
         * 
         * Bệnh nhân NORMAL là khách không đặt lịch hẹn và tới trực tiếp bệnh viện để khám => nhận số thứ tự ngay lập tức 
         * Bệnh nhân BOOKING là khách đặt lịch khám qua điện thoại
         */
        private function oldFlow()
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
            $position = "";
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

            /**Step 5.7 - numerical order - is a ID of patient today - 
             * For example, i go to hospital and i am patient NO.40.
             * Maybe, i am a booking patient or normal patient.
             */
            $date = Date("d-m-Y");
            $queryNumericalOrder = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date)
                    ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "desc")
                    ->select("*");

            $result = $queryNumericalOrder->get();
            $quantity = count($result);
            $numerical_order = $quantity == 0 ? 1 : $quantity + 1;// because first value = 0


            /**Step 5.8 - position */
            /** We separate patients to 2 types:
             * 1. Booking patients who have appointment by Android.
             * 2. Normal patients who go to hospital and wait for queue.
             */
            $date = Date("Y-m-d");// appointment time is today
            if( !empty($appointment_time) )
            {
                $msg = isAppointmentTimeValid($appointment_time);
                if( !empty($msg) )
                {
                    $this->resp->msg = $msg;
                    $this->jsonecho();
                }

                $date = substr($appointment_time, 0,10);// appointment time is set by patient
            }
            $queryPosition = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date)
                    ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "desc")
                    ->select("*");
            
            /* Case 1 - with no appointment, Normal patients also have their own queue and count from 1.*/
            if( empty($appointment_time) )
            {
                //do nothing
                $type = "Normal appointment";
            }
            /**Case 2 - with appointment, Booking patient have their own queue and also count from 1*/
            else
            {
                $type = "Booking appointment";
                $queryPosition->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "=", $date);
            }
            $result = $queryPosition->get();
            $quantity = count($result);
            $position = $quantity == 0 ? 1 : $quantity + 1;// because first value = 0


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
                        ->set("position", $position)
                        ->set("appointment_time", $appointment_time)
                        ->set("date", $date)
                        ->set("status", $status)
                        ->set("create_at", $create_at)
                        ->set("update_at", $update_at)
                        ->save();
                $this->resp->result = 1;
                $this->resp->msg = $type." has been created with patient No.".$numerical_order." with position: ".$position;
                $this->resp->data = array(
                    "id" => (int) $Appointment->get("id"),
                    "date"          => $Appointment->get("date"),
                    "doctor_id" => (int) $Appointment->get("doctor_id"),
                    "numerical_order" =>  (int)$Appointment->get("numerical_order"),
                    "position" => (int) $Appointment->get("position"),
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


        /**
         * @author Phong-Kaster
         * @since 31-10-2022
         * create a new appointment
         * 
         * doctor's role as ADMIN or SUPPORTER can see all appointments
         * doctor's role as MEMBER only can see appointments which was assigned to them.
         * 
         * Case 1 - if guess with no appointment, default date is today & numerical order and position are set up immediately 
         * Case 2 - if guess with appointment, Only when they come to hospital, numerical order is established 
         * and position is also set immediately.
         * 
         * Tức đặt lịch hẹn chỉ là hình thức để bác sĩ biết trước bệnh án và ưu tiên cho những bệnh 
         * nhân bị các bệnh đặc biệt mà không thể đợi lâu. Chúng ta sẽ không phát số thứ tự 
         * cho những bệnh nhân BOOKING.
         *
         * Thay vào đó, họ tới bệnh viện thì mới bắt đầu phát số. Nếu họ bị bệnh đặc biệt, ví dụ: bệnh trĩ.... hoặc người 
         * bệnh đã đặt thời gian vào khám thích hợp thì HỖ TRỢ VIÊN sẽ tiến hành sắp xếp thứ tự khám cho họ
         * 
         * Mỗi bệnh nhân trong một ngày chỉ được phép có duy nhất một lịch khám bệnh. Nếu muốn được khám 
         * tiếp bệnh khác, bệnh nhân cần hoàn thành việc khám bệnh hiện tại thì mới được đăng kí khám sang 
         * các bệnh khác.
         */
        private function oldFlow2()
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
            $doctor_id = (int)Input::post("doctor_id");
            $patient_id = Input::post("patient_id") ? (int)Input::post("patient_id") : 1;

            $patient_name = Input::post("patient_name");
            $patient_birthday = Input::post("patient_birthday");

            $patient_reason = Input::post("patient_reason");
            $patient_phone = Input::post("patient_phone");

            $numerical_order = "";
            $position = "";

            $appointment_time = Input::post("appointment_time") ? Input::post("appointment_time") : "";
            $date = Date("Y-m-d");// is used to calculate position and numerical order
            //$today = Date("Y-m-d");// is used for storing the day when this appointment is created

            $type = $appointment_time ? "BOOKING appointment" : "NORMAL appointment";
            $status = "processing";// default
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            $create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            $booking_id = Input::post("booking_id") ? Input::post("booking_id") : 0;

            /**Step 5 - validation */
            if( $booking_id > 0)
            {
                $Booking = Controller::model("Booking", $booking_id);
                if( !$Booking->isAvailable())
                {
                    $this->resp->msg = "This booking does not exist !";
                    $this->jsonecho();
                }
                if( !$Booking->get("status") == "cancelled")
                {
                    $this->resp->msg = "This booking has been cancelled !";
                    $this->jsonecho();
                }
                if( $Booking->get("patient_id") != $patient_id)
                {
                    $this->resp->msg = "The patient of booking does not match with patient ID";
                    $this->jsonecho();
                }
            }
            

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
            if( $Doctor->get("role") == "supporter")
            {
                $this->resp->msg = "The role of doctor ".$Doctor->get("name")." is ".$Doctor->get("role").". You can't assign appointment to SUPPORTER";
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

            /**Step 5.7 - appointment time*/
            if( !empty($appointment_time) )
            {
                $msg = isAppointmentTimeValid($appointment_time);
                if( !empty($msg) )
                {
                    $this->resp->msg = $msg;
                    $this->jsonecho();
                }
                $date = substr($appointment_time,0, 10);
            }

            /**Step 5.6 - one patient has only one appointment at the time. 
             * If he/she wanna take other exam, he/she must complete the current exam first
             */
            $queryNumberOfAppointment = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_id", "=", $patient_id)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=" , $date)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", "=", "processing");
            
            $resultNumberOfAppointment = $queryNumberOfAppointment->get();
            if( count($resultNumberOfAppointment) > 0)
            {
                $this->resp->msg = "This patient is having a processing appointment ! He(she) must complete this appointment to continue";
                $this->jsonecho();
            }



            /**Step 5.8 - numerical order - is a ID of patient today - 
             * For example, i go to hospital and i am patient NO.40.
             * Maybe, i am a booking patient or normal patient.
             */
            $queryNumericalOrder = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date)
                    ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "desc")
                    ->select("*");

            $result = $queryNumericalOrder->get();
            $quantityNumericalOrder = count($result);
            $numerical_order = $quantityNumericalOrder == 0 ? 1 : $quantityNumericalOrder + 1;// because first value = 0

            /**Step 5.9 - position */
            $queryPosition = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date)
                    ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "desc")
                    ->select("*");
            $result = $queryPosition->get();
            $quantityPosition = count($result);
            $position = $quantityPosition == 0 ? 1 : $quantityPosition + 1;// because first value = 0

            /**Step 5.10 - status */
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
                        ->set("booking_id", $booking_id)
                        ->set("patient_id", $patient_id)
                        ->set("patient_name", $patient_name)
                        ->set("patient_birthday", $patient_birthday)
                        ->set("patient_reason", $patient_reason)
                        ->set("patient_phone", $patient_phone)
                        ->set("numerical_order", $numerical_order)
                        ->set("position", $position)
                        ->set("appointment_time", $appointment_time)
                        ->set("date", $date)
                        ->set("status", $status)
                        ->set("create_at", $create_at)
                        ->set("update_at", $update_at)
                        ->save();
                $this->resp->result = 1;
                $this->resp->msg = $type." has been created with patient No.".$numerical_order." with position: ".$position;
                $this->resp->data = array(
                    "id" => (int) $Appointment->get("id"),
                    "date"          => $Appointment->get("date"),
                    "booking_id"    => (int) $Appointment->get("booking_id"),
                    "doctor_id" => (int) $Appointment->get("doctor_id"),
                    "numerical_order" =>  (int)$Appointment->get("numerical_order"),
                    "position" => (int) $Appointment->get("position"),
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


        /**
         * @author Phong-Kaster
         * @since 18-12-2022
         * Lần này lịch hẹn sẽ có 2 cách để được tạo ra
         * Trường hợp 1: gửi tới 1 ID của service, hệ thống sẽ tìm bác sĩ có số lượng bệnh nhân ít nhất để trả về
         * Trường hợp 2: gửi tới 1 ID của doctor, hệ thống tạo ra bác sĩ và lấy số như oldFlow 2 bên trên
         */
        private function newFlow()
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
            $required_fields = ["patient_name", "patient_birthday",
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
            $service_id = (int)Input::post("service_id");
            $doctor_id = (int)Input::post("doctor_id");
            $patient_id = Input::post("patient_id") ? (int)Input::post("patient_id") : 1;

            $patient_name = Input::post("patient_name");
            $patient_birthday = Input::post("patient_birthday");

            $patient_reason = Input::post("patient_reason");
            $patient_phone = Input::post("patient_phone");

            $numerical_order = "";
            $position = "";

            $appointment_time = Input::post("appointment_time") ? Input::post("appointment_time") : "";
            $date = Date("Y-m-d");// is used to calculate position and numerical order
            //$today = Date("Y-m-d");// is used for storing the day when this appointment is created

            $type = $appointment_time ? "BOOKING appointment" : "NORMAL appointment";
            $status = "processing";// default
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            $create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            $booking_id = Input::post("booking_id") ? Input::post("booking_id") : 0;

            /**Step 5 - validation */
            if( $booking_id > 0)
            {
                $Booking = Controller::model("Booking", $booking_id);
                if( !$Booking->isAvailable())
                {
                    $this->resp->msg = "This booking does not exist !";
                    $this->jsonecho();
                }
                if( !$Booking->get("status") == "cancelled")
                {
                    $this->resp->msg = "This booking has been cancelled !";
                    $this->jsonecho();
                }
                if( $Booking->get("patient_id") != $patient_id)
                {
                    $this->resp->msg = "The patient of booking does not match with patient ID";
                    $this->jsonecho();
                }
            }
            
            /**Step 5.1 - validation service id & doctor id */
            if( $service_id == 0 && $doctor_id == 0 )
            {
                $this->resp->msg = "Để khởi tạo lượt khám, cần cung cấp nhu cầu khám bệnh hoặc tên bác sĩ";
                $this->jsonecho();
            }

            /**Step 5.1.1 - Service validation */
            if( $service_id > 0)
            {
                $Service = Controller::model("Service", $service_id);
                if( !$Service->isAvailable() )
                {
                    $this->resp->msg = "This service does not exist !";
                    $this->jsonecho();
                }
            }

            /**Step 5.1.2 - Case 1 - Trường hợp chỉ định cả service_id lấn doctor_id thì giữ nguyên doctor_id */
            if( $service_id > 0 && $doctor_id > 0)
            {
                # doctor_id is still the same as its origin
            }
            /**Step 5.1.2 - Case 2 - Trường hợp chỉ định service_id nhưng không chỉ định doctor_id thì 
             * sẽ tìm ra bác sĩ có số lượng bệnh nhân ít nhất ở thời điểm hiện tại để lấy ra
             */
            if( $service_id > 0 && $doctor_id == 0)
            {
                $doctor_id = $this->getTheLaziestDoctor($service_id);
            }
            

            /**Step 5.1b - doctor validation */
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
            if( $Doctor->get("role") == "supporter")
            {
                $this->resp->msg = "The role of doctor ".$Doctor->get("name")." is ".$Doctor->get("role").". You can't assign appointment to SUPPORTER";
                $this->jsonecho();
            }

            /**Step 5.3 - patient validation - patient with ID = 1 is default. In case, 
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

            /**Step 5.4 - patient name validation */
            $patient_name_validation = isVietnameseName($patient_name);
            if( $patient_name_validation == 0 ){
                $this->resp->msg = "( Booking name ) Vietnamese name only has letters and space";
                $this->jsonecho();
            }

            /**Step 5.5 - patient birthday validation */
            $msg = isBirthdayValid($patient_birthday);
            if( !empty($msg) )
            {
                $this->resp->msg = $msg;
                $this->jsonecho();
            }
            
            /**Step 5.6 - patient reason */

            /**Step 5.7 - patient phone */
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

            /**Step 5.8 - appointment time*/
            if( !empty($appointment_time) )
            {
                $msg = isAppointmentTimeValid($appointment_time);
                if( !empty($msg) )
                {
                    $this->resp->msg = $msg;
                    $this->jsonecho();
                }
                $date = substr($appointment_time,0, 10);
            }

            /**Step 5.9 - one patient has only one appointment at the time. 
             * If he/she wanna take other exam, he/she must complete the current exam first
             */
            $queryNumberOfAppointment = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".patient_id", "=", $patient_id)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=" , $date)
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", "=", "processing");
            
            $resultNumberOfAppointment = $queryNumberOfAppointment->get();
            if( count($resultNumberOfAppointment) > 0)
            {
                $this->resp->msg = "Bệnh nhân cần hoàn thành lượt khám hiện tại để tiếp tục lượt khám mới !";
                $this->jsonecho();
            }



            /**Step 5.10 - numerical order - is a ID of patient today - 
             * For example, i go to hospital and i am patient NO.40.
             * Maybe, i am a booking patient or normal patient.
             */
            $queryNumericalOrder = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date)
                    ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "desc")
                    ->select("*");

            $result = $queryNumericalOrder->get();
            $quantityNumericalOrder = count($result);
            $numerical_order = $quantityNumericalOrder == 0 ? 1 : $quantityNumericalOrder + 1;// because first value = 0

            /**Step 5.11 - position */
            $queryPosition = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctor_id)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date)
                    ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "desc")
                    ->select("*");
            $result = $queryPosition->get();
            $quantityPosition = count($result);
            $position = $quantityPosition == 0 ? 1 : $quantityPosition + 1;// because first value = 0


            /**Step 5.12 - status */
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
                        ->set("booking_id", $booking_id)
                        ->set("patient_id", $patient_id)
                        ->set("patient_name", $patient_name)
                        ->set("patient_birthday", $patient_birthday)
                        ->set("patient_reason", $patient_reason)
                        ->set("patient_phone", $patient_phone)
                        ->set("numerical_order", $numerical_order)
                        ->set("position", $position)
                        ->set("appointment_time", $appointment_time)
                        ->set("date", $date)
                        ->set("status", $status)
                        ->set("create_at", $create_at)
                        ->set("update_at", $update_at)
                        ->save();
                $this->resp->result = 1;
                $this->resp->msg = $type." has been created with patient No.".$numerical_order." with position: ".$position;
                $this->resp->data = array(
                    "id" => (int) $Appointment->get("id"),
                    "date"          => $Appointment->get("date"),
                    "booking_id"    => (int) $Appointment->get("booking_id"),
                    "doctor_id" => (int) $Appointment->get("doctor_id"),
                    "numerical_order" =>  (int)$Appointment->get("numerical_order"),
                    "position" => (int) $Appointment->get("position"),
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


        /**
         * @since 18-12-2022
         * truyền vào một service id và sẽ tìm ra bác sĩ hiện tại đang có ít bệnh nhân nhất phải
         * khám nhất ở thời điểm.
         * Kết quả trả về là ID của bác sĩ đó
         */
        private function getTheLaziestDoctor($serviceId)
        {
            /**Step 1 - khai báo cú pháp */
            $doctorWithQuantity = [];
            $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)

                    ->leftJoin(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE,
                                TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.".doctor_id", "=", TABLE_PREFIX.TABLE_DOCTORS.".id")

                    ->leftJoin(TABLE_PREFIX.TABLE_SERVICES,
                                TABLE_PREFIX.TABLE_SERVICES.".id", "=", TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.".service_id")
                    ->where(TABLE_PREFIX.TABLE_SERVICES.".id", "=", $serviceId)
                    ->orderBy(TABLE_PREFIX.TABLE_DOCTORS.".id", "asc")
                    ->select(
                        DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".id as doctor_id")
                    );
            
            $result = $query->get();
            
            /**Step 2 - tìm các doctorId với số lượng bệnh nhân hiện khám trong ngày */
            foreach($result as $element)
            {
                $doctorWithQuantity[] = array(
                    "doctorId" => (int)$element->doctor_id,
                    "quantity"=> (int)$this->getCurrentAppointmentQuantityByDoctorId($element->doctor_id)
                );
            }

            /**Step 3 - đẩy các quantity vào các một mảng và trả ra quantitySmallest - tức số lượng bệnh nhân mà 1 bác sĩ 
             * nào đó đang khám là ít nhất
             */
            $quantity = array();
            for($i=0; $i < count($doctorWithQuantity); $i++)
            {
                $element = $doctorWithQuantity[$i];
                $elementQuantity = $element["quantity"];
                array_push($quantity, $elementQuantity);
            }
            $quantitySmallest = min($quantity);

            /**Step 4 - dùng quantity smallest để lấy ra ID của bác sĩ đó
             * Nếu có 2 người trở lên có cùng số bệnh nhân thì lấy ra bác sĩ đầu tiên
             */
            $output = 0;
            for($i=0; $i < count($doctorWithQuantity); $i++)
            {
                $element = $doctorWithQuantity[$i];
                $elementQuantity = $element["quantity"];
                if( $elementQuantity == $quantitySmallest)
                {
                    $output = $element["doctorId"];
                }
                
            }

            $this->resp->doctorWithQuantity = $doctorWithQuantity;
            return $output;
        }

        /**
         * @author Phong-Kaster
         * @since 19-12-2022
         * hàm này sẽ tìm ra số lượng bệnh nhân hiện tại của bác sĩ trong 
         * ngày hôm nay và lượt khám đang là processing
         * trả về số lượng
         */
        private function getCurrentAppointmentQuantityByDoctorId($doctorId)
        {
            $today = Date("Y-m-d");// is used for storing the day when this appointment is created
            $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)

                    ->leftJoin(TABLE_PREFIX.TABLE_APPOINTMENTS,
                                TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", TABLE_PREFIX.TABLE_DOCTORS.".id")

                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctorId)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", "=", "processing")
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $today)
                    ->select(
                        DB::raw("COUNT(".TABLE_PREFIX.TABLE_APPOINTMENTS.".id) as quantity")
                    );
            $result = $query->get();
            $output = $result[0]->quantity;
            return $output;  
        }
    }
?>