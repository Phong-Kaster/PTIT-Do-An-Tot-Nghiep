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
            $valid_roles = ["admin", "supporter"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->msg = "You are  not admin or supporter & you can't do this action !";
                $this->jsonecho();
            }



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
                    "name" => $Service->get("name")
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
            
            $required_fields = ["name"];
            foreach( $required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            $name = Input::put("name");


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
                    ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Service has been updated successfully";
                $this->resp->data = array(
                    "id" => (int)$Service->get("id"),
                    "name" => $Service->get("name")
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
            $queryBooking = DB::table(TABLE_PREFIX.TABLE_BOOKING)
                    ->where(TABLE_PREFIX.TABLE_BOOKING.".service_id", "=", $Route->params->id);
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
    }
?>