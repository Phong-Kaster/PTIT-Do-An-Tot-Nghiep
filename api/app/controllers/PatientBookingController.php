<?php 
    /**
     * @author Phong-Kaster
     * @since 20-10-2022
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
            if( $request_method === 'GET')
            {
                $this->getById();
            }
            if( $request_method === 'DELETE')
            {
                $this->delete();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 14-10-2022
         * get patient information by id
         * any one who are logging, can use this API
         */
        private function getById()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }


            /**Step 3 - get by id */
            $Booking = Controller::model("Booking", $Route->params->id);
            if( !$Booking->isAvailable() )
            {
                $this->resp->msg = "Booking does not exist";
                $this->jsonecho();
            }
            if( $Booking->get("patient_id") != $AuthUser->get("id") )
            {
                $this->resp->msg = "This booking is not available";
                $this->jsonecho();
            }


            $Service = Controller::model("Service", $Booking->get("service_id"));
            if( !$Service->isAvailable() )
            {
                $this->resp->msg = "Service does not exist";
                $this->jsonecho();
            }

            /**Step 4 - return */
            try
            {

                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
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
                    "appointment_date" => $Booking->get("appointment_date"),
                    "appointment_time" => $Booking->get("appointment_time"),
                    "status" => $Booking->get("status"),
                    "create_at" => $Booking->get("create_at"),
                    "update_at" => $Booking->get("update_at"),
                    "service" => array(
                        "id" => (int)$Service->get("id"),
                        "name" => $Service->get("name"),
                        "image" => $Service->get("image")
                    )
                );
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
         * cancel a booking only when its status == processing
         */
        private function delete()
        {
            /**Step 1 */
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

            if( $Booking->get("patient_id") != $AuthUser->get("id") )
            {
                $this->resp->msg = "This booking is not available !";
                $this->jsonecho();
            }

            /**Step 3 - if status == cancelled => no need more action*/
            if( $Booking->get("status") == "cancelled" )
            {
                $this->resp->msg = "This booking's status is cancelled. No need any more action !";
                $this->jsonecho();
            }
            
            /**Step 4 - if status == processing or status verified => allow set status to CANCELLED */
            $status = $Booking->get("status");
            $valid_status = ["processing"];
            $status_validation = in_array($status, $valid_status);
            if( !$status_validation )
            {
                $this->resp->msg = "Booking's status is not valid. Booking can be cancelled only when its status: "
                                    .implode(', ',$valid_status)." !";
                $this->jsonecho();
            }
            
            /**Step 5 - save change */
            $Booking->set("status", "cancelled")
                    ->set("update_at", $update_at)
                    ->save();


            $Notification = Controller::model("Notification");
            $Service = Controller::model("Service", $Booking->get("service_id"));
            $serviceName = $Service->get("name");
            $date = $Booking->get("appointment_date");
            $time = $Booking->get("appointment_time");
            
            $notificationMessage = "Lịch hẹn khám ".$serviceName." lúc ".$time." ngày ".$date." đã được hủy bỏ thành công!";
            $Notification->set("message", $notificationMessage)
                    ->set("record_id", $Booking->get("id") )
                    ->set("record_type", "booking")
                    ->set("is_read", 0)
                    ->set("patient_id", $AuthUser->get("id"))
                    ->set("create_at", $update_at)
                    ->set("update_at", $update_at)
                    ->save();
            
            $this->resp->result = 1;
            $this->resp->msg = "Booking has been cancelled successfully !";
            $this->jsonecho();
        }
    }
?>