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

        /**
         * @since 08-12-2022
         * this function upload photos to a specific booking
         * ONLY PATIENT can use this function
         */
        private function upload()
        {
            /**Step 0 - declare */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "Booking ID is required !";
                $this->jsonecho();
            }


            /**Step 1 - verify */
            $bookingId = $Route->params->id;
            $Booking = Controller::model("Booking", $bookingId);
            if( !$Booking->isAvailable())
            {
                $this->resp->msg = "This booking does not exist !";
                $this->jsonecho();
            }
            if( $Booking->get("status") != "processing")
            {
                $this->resp->msg = "Right now, the status of booking is ".$Booking->get("status")." so that you can't do this action!";
                $this->jsonecho();
            }
            /**if the patient requests this function, we have to check if the patient is the owner of this booking */
            if( $AuthUser->get("role") == "" &&
                 $Booking->get("patient_id") != $AuthUser->get("id") )
            {
                $this->resp->msg = "This booking does not belong to you !";
                $this->jsonecho();
            }


            /**Step 2 - check if file is received or not */
            if (empty($_FILES["file"]) || $_FILES["file"]["size"] <= 0) 
            {
                $this->resp->msg = "Photo is not received !";
                $this->jsonecho();
            }

            
            /**Step 3 - check filename extension */
            $ext = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
            $allow = ["jpeg", "jpg", "png"];
            if (!in_array($ext, $allow)) 
            {
                $this->resp->msg = __("Only ".join(",", $allow)." files are allowed");
                $this->jsonecho();
            }


            /**Step 4 - upload file */
            $date = new DateTime();
            $timestamp = $date->getTimestamp();
            $name = "booking_".$Booking->get("id")."_".$timestamp;
            $directory = UPLOAD_PATH;


            if (!file_exists($directory)) {
                mkdir($directory);
            }
            
            $filepath = $directory . "/" . $name . "." .$ext;

            if (!move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) 
            {
                $this->resp->msg = __("Oops! An error occured. Please try again later!");
                $this->jsonecho();
            }
            
            /**Step 6 - update photo name for AuthUser */
            try 
            {
                $BookingPhoto = Controller::model("BookingPhoto");
                $BookingPhoto->set("url", $name . "." .$ext)
                        ->set("booking_id", $bookingId)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = __("Photo has been uploaded by you successfully !");
                $this->resp->url = APPURL."/assets/uploads/".$name . "." .$ext;

            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }

            $this->jsonecho();
        }
    }
?>