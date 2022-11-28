<?php
    /**
     * @author Phong-Kaster
     * @since 21-10-2022
     * this function manages appointment. Only DOCTOR can use this function.
     */
    class AppointmentController extends Controller
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
            $valid_roles = ["admin", "supporter", "member"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$AuthUser->get("role") || !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You do not have permission to do this action !";
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
            else if( $request_method === 'DELETE')
            {
                $this->delete();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 21-10-2022
         * Read an appointment info by ID
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

            /**Step 3 */
            try 
            {
                $Appointment = Controller::model("Appointment", $Route->params->id);
                if( !$Appointment->isAvailable() )
                {
                    $this->resp->msg = "Appointment is not available";
                    $this->jsonecho();
                }

                $Doctor = Controller::model("Doctor", $Appointment->get("doctor_id"));
                $Speciality = Controller::model("Speciality", $Doctor->get("speciality_id"));
                $Room = Controller::model("Room", $Doctor->get("room_id"));

                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = array(
                    "id" => (int)$Appointment->get("id"),
                    "date" => $Appointment->get("date"),
                    "numerical_order" => (int)$Appointment->get("numerical_order"),
                    "position" => (int) $Appointment->get("position"),
                    "patient_id" => (int)$Appointment->get("patient_id"),
                    "patient_name" => $Appointment->get("patient_name"),
                    "patient_phone" => $Appointment->get("patient_phone"),
                    "patient_birthday" => $Appointment->get("patient_birthday"),
                    "patient_reason" => $Appointment->get("patient_reason"),
                    "patient_phone" => $Appointment->get("patient_phone"),
                    "appointment_time" => $Appointment->get("appointment_time"),
                    "status" => $Appointment->get("status"),
                    "create_at" => $Appointment->get("create_at"),
                    "update_at" => $Appointment->get("update_at"),
                    "doctor" => array(
                        "id" => (int)$Doctor->get("id"),
                        "name" => $Doctor->get("name"),
                        "avatar" => $Doctor->get("avatar")
                    ),
                    "speciality" => array(
                        "id" => (int)$Speciality->get("id"),
                        "name" => $Speciality->get("name"),
                        "image" => $Speciality->get("image"),
                        "description" => $Speciality->get("description")
                    ),
                    "room" => array(
                        "id" => (int) $Room->get("id"),
                        "name" => $Room->get("name"),
                        "location" => $Room->get("location")
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
         * @since 21-10-2022
         * - only DOCTOR can use this function
         * - change appointment's information
         * - only doctor's role as ADMIN or SUPPORTER can change DOCTOR_ID
         * 
         * 
         * - There fields CAN be updated as following:
         * 1. doctor_id
         * 2. patient_name
         * 3. patient_birthday
         * 4. patient_reason
         * 5. patient_phone
         * 6. appointment_time
         * 
         *- There fields CAN NOT be updated as following:
         * 1. patient_id
         * 2. numerical_order
         * 3. status
         * 4. date
         * 5. id
         * 
         * - Only change appointments has not occured. 
         * Today is 23-10-2022 & appointment_date is 22-10-2022 => this appointment can not changed
         */
        private function update()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");
            $today = (String)Date("Y-m-d");


            /**Step 2 - check the appointment is being changed ! */
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /**Step 2.1 - is appointment available?  */
            $Appointment = Controller::model("Appointment", $Route->params->id);
            if( !$Appointment->isAvailable() )
            {
                $this->resp->msg = "Appointment is not available";
                $this->jsonecho();
            }

            /**Step 2.2 - is appointment's status valid? */
            $invalid_status = ["cancelled", "done"];
            $status_validation = in_array($Appointment->get("status"), $invalid_status);
            if( $status_validation )
            {
                $this->resp->msg = "Appointment's status is ".$Appointment->get("status")." ! You can't do this action !";
                $this->jsonecho();
            }

            /**Step 2.3 - is appointment's date today? 
             * If no, this appointment can not be changed ! 
             * 
             * Today is 23-10-2022 & appointment_date is 22-10-2022 => this appointment can not changed
             * */

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

            /**Step 3 - get required fields */
            $required_fields = ["patient_name", "doctor_id", "patient_id"];
            foreach($required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            /**Step 4 - get data */
            $doctor_id = Input::put("doctor_id");
            $patient_id = Input::put("patient_id");

            $patient_name = Input::put("patient_name");
            $patient_birthday = Input::put("patient_birthday");

            $patient_reason = Input::put("patient_reason");
            $patient_phone = Input::put("patient_phone");

            // $numerical_order = "";
            $appointment_time = Input::put("appointment_time") ? Input::put("appointment_time") : "";

            $status = Input::put("status");// default
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            $create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            /**Step 5 - validation */
            /**Step 5.1 - doctor validation */
            $Doctor = Controller::model("Doctor", $doctor_id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "Doctor is not available";
                $this->jsonecho();
            }

            /**Step 5.2 - patient validation */
            $Patient = Controller::model("Patient", $patient_id);
            if( !$Patient->isAvailable() )
            {
                $this->resp->msg = "Patient is not available";
                $this->jsonecho();
            }

            /**Step 5.3 - patient name validation */
            $patient_name_validation = isVietnameseName($patient_name);
            if( $patient_name_validation == 0 ){
                $this->resp->msg = "( Booking name ) Vietnamese name only has letters and space";
                $this->jsonecho();
            }

            /**Step 5.4 - patient birthday validation */
            if( $patient_birthday )
            {
                $msg = isBirthdayValid($patient_birthday);
                if( !empty($msg) )
                {
                    $this->resp->msg = $msg;
                    $this->jsonecho();
                }
            }

            /**Step 5.5 - patient_reason validation */

            /**Step 5.6 - patient phone validation*/
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

            /**Step 5.7 - appointment time validation */
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

            /**Step 6 - only doctor's role as ADMIN or SUPPORTER who can change doctor_id
             * doctor's role as MEMBER can not change doctor_id
             */
            $valid_status = ["admin", "supporter"];
            $status_validation = in_array($AuthUser->get("role"), $valid_status);
            if( $status_validation )
            {
                $Appointment->set("doctor_id", $doctor_id);
            }

            /**Step 7 - save change */
            try 
            {
                //$Appointment = Controller::model("Appointment");
                $Appointment->set("patient_id", $patient_id)
                        ->set("patient_name", $patient_name)
                        ->set("patient_birthday", $patient_birthday)
                        ->set("patient_reason", $patient_reason)
                        ->set("patient_phone", $patient_phone)
                        ->set("appointment_time", $appointment_time)
                        ->set("update_at", $update_at)
                        ->save();


                $this->resp->result = 1;
                $this->resp->msg = "Appointment has been updated successfully !";
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


        /**
         * @author Phong-Kaster
         * @since 21-10-2022
         * change appointment's status
         * 
         * appointment's status must be PROCESSING => DONE or CANCELLED.
         * 
         * - Doctor's role as ADMIN or SUPPORTER can change any appointment
         * - Doctor's role as MEMBER only change appointment having doctor_id == this doctor.
         */
        private function confirm()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");
            $today = Date("Y-m-d");
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            /**Step 2 - check the appointment is being changed ! */
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /**Step 2.1 - is appointment available?  */
            $Appointment = Controller::model("Appointment", $Route->params->id);
            if( !$Appointment->isAvailable() )
            {
                $this->resp->msg = "Appointment is not available";
                $this->jsonecho();
            }

            /**Step 2.2 - is appointment's status valid? */
            $invalid_status = ["cancelled", "done"];
            $status_validation = in_array($Appointment->get("status"), $invalid_status);
            if( $status_validation )
            {
                $this->resp->msg = "Appointment's status is ".$Appointment->get("status")." ! You can't do this action !";
                $this->jsonecho();
            }

            /**Step 2.3 - is appointment's date today? 
             * If no, this appointment can not be changed ! */

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


            /**Step 3 - check new status */
            if( !Input::patch("status") )
            {
                $this->resp->msg = "Missing new status";
                $this->jsonecho();
            }

            $new_status = Input::patch("status");
            $valid_status = ["cancelled", "done"];
            $status_validation = in_array($new_status, $valid_status);
            if( !$status_validation )
            {
                $this->resp->msg = "The new status of appointment is not valid. These accepted status are: ".implode(', ', $valid_status);
                $this->jsonecho();
            }


            /**Step 4 - if doctor's role as MEMBER, he/she just only update his/her appointment  */
            if( $AuthUser->get("role") == "member" && 
                $Appointment->get("doctor_id") != $AuthUser->get("id") )
            {
                $AnotherDoctor = Controller::model("Doctor", $Appointment->get("doctor_id") );

                $this->resp->msg = "This appointment belongs to doctor ".$AnotherDoctor->get("name")."! Therefore, you can't do this action ";
                $this->jsonecho();
            }

            /**Step 5 - prepare notification for PATIENT */
            $AnotherDoctor = Controller::model("Doctor", $Appointment->get("doctor_id") );
            $AnotherDoctorName = $AnotherDoctor->get("name");
            $message = "";
            if( $new_status == "done")
            {
                
                $message = "Chúc mừng bạn! Lượt khám của bạn với bác sĩ ".$AnotherDoctorName." đã hoàn thành. Bạn có thể xem lại kết luận của bác sĩ trong phần lịch sử khám bệnh";
            }
            else if( $new_status == "cancelled")
            {
                $message = "Lượt khám của bạn đã bị hủy do bạn không có mặt đúng thời gian!";
            }

            /**Step 5 - save */
            try 
            {
                $Appointment->set("status", $new_status)
                            ->set("update_at", $update_at)
                            ->save();

                $Notification = Controller::model("Notification");
                $Notification->set("message", $message)
                            ->set("record_id", $Appointment->get("id") )
                            ->set("record_type", "appointment")
                            ->set("is_read", 0)
                            ->set("patient_id", $Appointment->get("patient_id"))
                            ->set("create_at", $create_at)
                            ->set("update_at", $update_at)
                            ->save();
                

                $this->resp->result = 1;
                $this->resp->msg = "The status of appointment has been updated successfully !";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }


        /**
         * @author Phong-Kaster
         * @since 01-11-2022
         * delete appointment
         * only doctor's role as ADMIN or SUPPORTER can do this function.
         * appointment's status == DONE => can not deleted
         * */
        private function delete()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");


            $valid_status = ["admin", "supporter"];

            $status_validation = in_array($AuthUser->get("role"), $valid_status);
            if( !$status_validation )
            {
                $this->resp->msg = "You are ".$AuthUser->get("role")." or supporter & you can't do this action !";
                $this->jsonecho();
            }


            /**Step 2 - required fields*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }


            /**Step 3 - check exist*/
            $Appointment = Controller::model("Appointment", $Route->params->id);
            if( !$Appointment->isAvailable() )
            {
                $this->resp->msg = "Appointment is not available";
                $this->jsonecho();
            }
            if($Appointment->get("status") == "done")
            {
                $this->resp->msg = "Appointment's status is ".$Appointment->get("status")." now. You can not delete!";
                $this->jsonecho();
            }



            /**Step 4 - how many doctor are there in this Clinic */
            try 
            {
                $Appointment->delete();
                
                $this->resp->result = 1;
                $this->resp->msg = "Appointment is deleted successfully !";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>