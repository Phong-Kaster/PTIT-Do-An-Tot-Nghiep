<?php
    /**
     * @author Phong-Kaster
     * @since 08-12-2022
     * Booking Photos Controller is used to manage photos which are sent from PATIENT
     */
    class BookingPhotosController extends Controller
    {
        /**
         * Process
         */
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
                if( $AuthUser->get("role") )
                {
                    $this->resp->msg = "Uploading photo for booking is the function only for PATIENT !";
                    $this->jsonecho();
                }
                $this->upload();
            }
        }

        /**
         * @since 08-12-2022
         * this function returns all photos sent by PATIENT for a specific booking
         */
        private function getAll()
        {
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");
            $data = [];

            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "Booking ID is required !";
                $this->jsonecho();
            }


        
            $bookingId = $Route->params->id;
            $Booking = Controller::model("Booking", $bookingId);
            if( !$Booking->isAvailable())
            {
                $this->resp->msg = "This booking does not exist !";
                $this->jsonecho();
            }
            /**if the patient requests this function, we have to check if the patient is the owner of this booking */
            if( $AuthUser->get("role") == "" &&
                 $Booking->get("patient_id") != $AuthUser->get("id") )
            {
                $this->resp->msg = "This booking does not belong to you !";
                $this->jsonecho();
            }

            $Service = Controller::model("Service", $Booking->get("service_id"));

            try 
            {
                $query = DB::table(TABLE_PREFIX.TABLE_BOOKING_PHOTOS)
                ->where(TABLE_PREFIX.TABLE_BOOKING_PHOTOS.".booking_id", "=", $bookingId)
                ->orderBy(TABLE_PREFIX.TABLE_BOOKING_PHOTOS.".id", "desc")
                ->select("*");

                $result = $query->get();

                foreach($result as $element)
                {
                    $data[] = array(
                        "id" => (int)$element->id,
                        "booking_id" => (int)$element->booking_id,
                        "url" => $element->url
                    );
                }


                $this->resp->result = 1;
                $this->resp->quantity = count($result);
                $this->resp->booking = array(
                    "id" => (int)$Booking->get("id"),
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
                        "id"=> (int)$Service->get("id"),
                        "name"=>$Service->get("name")
                    )    
                );
                $this->resp->data = $data;
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>