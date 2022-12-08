<?php
    /**
     * @author Phong-Kaster
     * @since 08-12-2022
     * Booking Photos Controller is used to delete a photo from a specific booking
     */
    class BookingPhotoController extends Controller
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

            if( $AuthUser->get("role") )
            {
                $this->resp->msg = "This function is only used by PATIENT !";
                $this->jsonecho();
            }
            

            $request_method = Input::method();
            if($request_method === 'DELETE')
            {
                $this->delete();
            }
        }

        /**
         * @since 08-12-2022
         */
        private function delete()
        {
            /**Step 0 - declare */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "Photo ID is required !";
                $this->jsonecho();
            }

            /**Step 1 - verify */
            $id = $Route->params->id;
            $BookingPhoto = Controller::model("BookingPhoto", $id);
            if( !$BookingPhoto->isAvailable() )
            {
                $this->resp->msg = "Photo does not exist. Try again!";
                $this->jsonecho();
            }

            try 
            {
                $BookingPhoto->delete();
                $this->resp->result = 1;
                $this->resp->msg = "Photo has been deleted successfully";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>