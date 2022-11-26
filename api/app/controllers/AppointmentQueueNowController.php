<?php 
    class AppointmentQueueNowController extends Controller
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
                $this->getQueue();
            }
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
                $doctor_id = Input::get("doctor_id");
                if( !$doctor_id )
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
                        ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".position", "asc")
                        ->limit(3);
            $resultNormal = $queryNormal->get();
        }
    }
?>