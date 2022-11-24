<?php 
    /**
     * @author Phong-Kaster
     * @since 24-11-2022
     * Patient Notifications is used to help patients read their notification history
     */
    class PatientNotificationsController extends Controller
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
            if($request_method === 'POST')
            {
                $this->markAllAsRead();
            }
        }

        /**
         * @since 24-11-2022
         * get all notification of a patient
         * return 20 latest notifications
         */
        private function getAll()
        {
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data =[];

            $patient_id = $AuthUser->get("id");

            try 
            {
                $query = DB::table(TABLE_PREFIX.TABLE_NOTIFICATIONS)
                    ->where(TABLE_PREFIX.TABLE_NOTIFICATIONS.".patient_id", "=", $patient_id)
                    ->limit(20)
                    ->orderBy(TABLE_PREFIX.TABLE_NOTIFICATIONS.".create_at", "desc");
                $result = $query->get();

                $quantityUnread = 0;
                foreach($result as $element)
                {
                    if( $element->is_read == 0)
                    {
                        $quantityUnread++;
                    }

                    $data[] = array(
                        "id" => (int)$element->id,
                        "message" => $element->message,
                        "record_id" => (int)$element->record_id,
                        "record_type" => $element->record_type,
                        "is_read" => (int)$element->is_read,
                        "create_at" => $element->create_at,
                        "update_at" => $element->update_at
                    );
                }

                $this->resp->result = 1;
                $this->resp->msg = "Action successfully";
                $this->resp->quantity = count($result);
                $this->resp->quantityUnread = $quantityUnread;
                $this->resp->data = $data;
            } 
            catch (\Exceptio $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }


        
        /**
         * @since 24-11-2022
         * mark all as read
         */
        private function markAllAsRead()
        {
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data =[];

            $patient_id = $AuthUser->get("id");
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $update_at = date("Y-m-d H:i:s");

            try 
            {
                $query = DB::table(TABLE_PREFIX.TABLE_NOTIFICATIONS)
                    ->where(TABLE_PREFIX.TABLE_NOTIFICATIONS.".patient_id", "=", $patient_id)
                    ->where(TABLE_PREFIX.TABLE_NOTIFICATIONS.".is_read", "=", 0)
                    ->update(array(
                        "update_at" => $update_at,
                        "is_read" => 1
                    ));

                $this->resp->result = 1;
                $this->resp->msg = "Congratulations, ".$AuthUser->get("name")."! Mark all notification as read successfully";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>