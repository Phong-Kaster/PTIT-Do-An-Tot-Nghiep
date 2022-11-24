<?php 
    /**
     * @author Phong-Kaster
     * @since 24-11-2022
     * Patient Notification is used to help patients mark one notification as read
     */
    class PatientNotificationController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");
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
            if($request_method === 'POST' && isset($Route->params->id) )
            {
                $this->markAsRead();
            }
            else
            {
                $this->resp->result = 0;
                $this->resp->msg = "Oops! Your request is not correct!";
                $this->jsonecho();
            }
        }

        /**
         * @since 24-11-2022
         * mark a notification as read
         */
        private function markAsRead()
        {
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $update_at = date("Y-m-d H:i:s");

            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "Notification ID is required to do this action";
                $this->jsonecho();
            }

            $id = $Route->params->id;
            $Notification = Controller::model("Notification", $id);
            if( !$Notification->isAvailable() )
            {
                $this->resp->msg = "Notification is not available";
                $this->jsonecho();
            }

            try 
            {
                $Notification->set("is_read", 1)
                        ->set("update_at", $update_at)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Notification have been marked as read successfully";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>