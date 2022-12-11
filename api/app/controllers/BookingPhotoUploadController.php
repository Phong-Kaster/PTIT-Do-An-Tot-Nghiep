<?php 
    /**
     * @author Phong-Kaster
     * @since 08-12-2022
     * Booking Photos Controller is used to manage photos which are sent from PATIENT
     */
    class BookingPhotoUploadController extends Controller
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
            if( $request_method === 'POST')
            {
                $this->upload();
            }
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

            if( !Input::post("booking_id") )
            {
                $this->resp->msg = "Booking ID is required !";
                $this->jsonecho();
            }


            /**Step 1 - verify */
            $bookingId = Input::post("booking_id");
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


            /**Step 2 - is booking's date today? 
             * If no, this booking can not be changed ! */
            $today = Date("Y-m-d");
            $appointment_date = $Booking->get("appointment_date");
            //$appointment_date = "2022-12-10";
            
            $difference = abs(strtotime($today) - strtotime($appointment_date));
            $differenceYear = floor($difference / (365*60*60*24));
            $differenceMonth = floor(($difference - $differenceYear * 365*60*60*24) / (30*60*60*24));
            $differenceDay = floor(($difference - $differenceYear * 365*60*60*24 - $differenceMonth*30*60*60*24)/ (60*60*24));

            // print_r("today: ".$today);
            // print_r("\n");
            // print_r("appointment_date: ".$appointment_date);
            // print_r("\n");
            // print_r("difference day: ".$differenceDay);
            // print_r("\n");
            // print_r("difference month: ".$differenceMonth);
            // print_r("\n");
            // print_r("difference year: ".$differenceYear);
            // print_r("\n");

            if( $differenceDay > 0 )
            {
                $this->resp->msg = "Hôm nay là ngày ".$today." nhưng ngày hẹn là ngày ".$appointment_date
                                    ." vì vậy bạn không thể upload thêm hình!";
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