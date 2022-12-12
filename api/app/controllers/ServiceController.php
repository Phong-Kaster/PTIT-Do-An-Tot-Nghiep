<?php 
    /**
     * @author Phong-Kaster
     * @since 17-10-2022
     * Supporter can use getByID()
     */
    class ServiceController extends Controller
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
                $this->getById();
            }
            else if( $request_method === 'PUT')
            {
                $this->update();
            }
            else if( $request_method === 'DELETE')
            {
                $this->delete();
            }
            else if( $request_method === 'POST')
            {
                if( Input::post("action") == 'avatar' )
                {
                    $this->updateAvatar();
                }
                else
                {
                    $this->resp->result = 0;
                    $this->resp->msg = "This request is not valid";
                    $this->jsonecho();
                }
            }
        }

        /**
         * @author Phong-Kaster
         * @since 17-10-2022
         * get service by id
         */
        private function getById()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");


            /** is the role have permission ? */
            // $valid_roles = ["admin", "supporter"];
            // $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            // if( !$role_validation )
            // {
            //     $this->resp->msg = "You are  not admin or supporter & you can't do this action !";
            //     $this->jsonecho();
            // }



            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }


            /**Step 3 - get by id */
            try
            {
                $Service = Controller::model("Service", $Route->params->id);
                if( !$Service->isAvailable() )
                {
                    $this->resp->msg = "Service is not available";
                    $this->jsonecho();
                }



                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = array(
                    "id" => (int)$Service->get("id"),
                    "name" => $Service->get("name"),
                    "image" => $Service->get("image"),
                    "description"=> $Service->get("description")
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
         * @since 10-10-2022
         * update a Service
         */
        private function update()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }



            /**Step 2 - required fields*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }
            
            $required_fields = ["name", "description"];
            foreach( $required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            $name = Input::put("name");
            $description = Input::put("description");


            /**Step 3 - check exist*/
            $Service = Controller::model("Service", $Route->params->id);
            if( !$Service->isAvailable() )
            {
                $this->resp->msg = "Service is not available";
                $this->jsonecho();
            }


            /**Step 4 - update */
            try 
            {
                $Service->set("name", $name)
                    ->set("description", $description)
                    ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Service has been updated successfully";
                $this->resp->data = array(
                    "id" => (int)$Service->get("id"),
                    "name" => $Service->get("name"),
                    "image" => $Service->get("image"),
                    "description"=> $Service->get("description")
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
         * @since 17-10-2022
         * delete a service
         */
        private function delete()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }


            /**Step 2 - required fields*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            if( $Route->params->id == 1 )
            {
                $this->resp->msg = "This is the default Service & it can't be deleted !";
                $this->jsonecho();
            }


            /**Step 3 - check exist*/
            $Service = Controller::model("Service", $Route->params->id);
            if( !$Service->isAvailable() )
            {
                $this->resp->msg = "Service is not available";
                $this->jsonecho();
            }



            /**Step 4 - how many booking are there with this Service */
            $queryBooking = DB::table(TABLE_PREFIX.TABLE_BOOKINGS)
                    ->where(TABLE_PREFIX.TABLE_BOOKINGS.".service_id", "=", $Route->params->id);
            $result = $queryBooking->get();
            if( count($result) > 0)
            {
                $this->resp->msg = "This Service can't be deleted because there are ".count($result)." booking have been existed !";
                $this->jsonecho();
            }


            /**Step 5 - how many service are there were assigned with doctors ? */
            $queryDoctorAndService = DB::table(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE)
                    ->where(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.".service_id", "=", $Route->params->id);
            $result = $queryDoctorAndService->get();
            if( count($result) > 0)
            {
                $this->resp->msg = "This Service can't be deleted because there are ".count($result)." records assigned with doctors !";
                $this->jsonecho();
            }

            try 
            {
                $Service->delete();
                
                $this->resp->result = 1;
                $this->resp->msg = "Service is deleted successfully !";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }


        /**
         * @author Phong-Kaster
         * @since 17-11-2022
         * update image for service
         */
        private function updateAvatar()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            /**Step 2 - only ADMIN can do this action */
            if( $AuthUser->get("role") != 'admin' )
            {
                $this->resp->msg = "Only admin can do this action";
                $this->jsonecho();
            }

            /**Step 3 - check speciality exist ? */
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }
            $Service = Controller::model("Service", $Route->params->id);
            if( !$Service->isAvailable() )
            {
                $this->resp->msg = "Service is not available";
                $this->jsonecho();
            }



            /**Step 4 - check if file is received or not */
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
            $name = "service_".$Service->get("id")."_".$timestamp;
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
                $Service->set("image", $name . "." .$ext)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = __("Image has been updated successfully !");
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