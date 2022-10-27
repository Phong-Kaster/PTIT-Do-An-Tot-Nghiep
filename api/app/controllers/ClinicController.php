<?php 
    class ClinicController extends Controller
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
         * @since 12-10-2022
         * get clinic by id
         */
        private function getById()
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



            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }


            /**Step 3 - get by id */
            try
            {
                $Clinic = Controller::model("Clinic", $Route->params->id);
                if( !$Clinic->isAvailable() )
                {
                    $this->resp->msg = "Clinic is not available";
                    $this->jsonecho();
                }



                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = array(
                    "id" => (int)$Clinic->get("id"),
                    "name" => $Clinic->get("name"),
                    "address" => $Clinic->get("address")
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
         * @since 12-10-2022
         * update a clinic
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
            
            $required_fields = ["name", "address"];
            foreach( $required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }


            /**Step 3 - validation */
            $name = Input::put("name");
            $address = Input::put("address");

            $name_validation = isVietnameseHospital($name);
            if( $name_validation == 0 ){
                $this->resp->msg = "Name only has letters and space";
                $this->jsonecho();
            }

            $address_validation = isAddress($address);
            if( $address_validation == 0)
            {
                $this->resp->msg = "Address only accepts letters, numbers, space and comma !";
                $this->jsonecho();
            }


            /**Step 3 - check exist*/
            $Clinic = Controller::model("Clinic", $Route->params->id);
            if( !$Clinic->isAvailable() )
            {
                $this->resp->msg = "Clinic is not available";
                $this->jsonecho();
            }


            /**Step 4 - update */
            try 
            {
                $Clinic->set("name", $name)
                    ->set("address", $address)
                    ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Updated successfully";
                $this->resp->data = array(
                    "id" => (int)$Clinic->get("id"),
                    "name" => $Clinic->get("name"),
                    "address" => $Clinic->get("address")
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
         * @since 10-10-2022
         * delete a clinic
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
                $this->resp->msg = "This is the default Clinic & it can't be deleted !";
                $this->jsonecho();
            }


            /**Step 3 - check exist*/
            $Clinic = Controller::model("Clinic", $Route->params->id);
            if( !$Clinic->isAvailable() )
            {
                $this->resp->msg = "Clinic is not available";
                $this->jsonecho();
            }



            /**Step 4 - how many doctor are there in this Clinic */
            $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)
                    ->where(TABLE_PREFIX.TABLE_DOCTORS.".Clinic_id", "=", $Route->params->id);
            $result = $query->get();

            if( count($result) > 0)
            {
                $this->resp->msg = "This Clinic can't be deleted because there are ".count($result)." doctors working at it";
                $this->jsonecho();
            }

            try 
            {
                $Clinic->delete();
                
                $this->resp->result = 1;
                $this->resp->msg = "Clinic is deleted successfully !";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>