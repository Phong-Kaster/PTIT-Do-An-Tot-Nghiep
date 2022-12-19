<?php
    /**
     * @author Phong-Kaster
     * @since 18-12-2022
     */
    class DrugController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");

            if (!$AuthUser)
            {
                header("Location: ".APPURL."/login");
                exit;
            }
            // if( $AuthUser->get("role") != "admin" )
            // {
            //     $this->resp->result = 0;
            //     $this->resp->msg = "You are not admin & you can't do this action !";
            //     $this->jsonecho();
            // }

            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getById();
            }
        }
        

        /**
         * @author Phong-Kaster
         * @since 12-10-2022
         * get doctor by id
         */
        private function getById()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");



            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            $id = $Route->params->id;

            /**Step 3 - get by id */
            try
            {
                $Doctor = Controller::model("Doctor", $Route->params->id);
                if( !$Doctor->isAvailable() )
                {
                    $this->resp->msg = "Doctor is not available";
                    $this->jsonecho();
                }

                $query = DB::table(TABLE_PREFIX.TABLE_DRUGS)
                        ->where(TABLE_PREFIX.TABLE_DRUGS.".id", "=", $id)
                        ->select("*");

                $result = $query->get();
                if( count($result) > 1 )
                {
                    $this->resp->msg = "Oops, there is an error occurring. Try again !";
                    $this->jsonecho();
                }

                $data = array(
                    "id" => (int)$result[0]->id,
                    "name" => $result[0]->name
                );

                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = $data;
            }
            catch(Exception $ex)
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }


        /**
         * @author Phong-Kaster
         * @since 13-10-2022
         * update a doctor's information except avatar, password & email
         */
        private function update()
        {
            /**Step 0 - declare */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");
            $AuthUser = $this->getVariable("AuthUser");

            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }

            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /**Step 1 - does the doctor exist ? */
            $Drug = Controller::model("Drug", $Route->params->id);
            if( !$Drug->isAvailable() )
            {
                $this->resp->msg = "Drug is not available. Try again !";
                $this->jsonecho();
            }

            $required_fields = ["name"];
            foreach($required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            /**Step 2 - declare*/
            $id = $Route->params->id;
            $name = Input::put("name");



            /**Step 3 - validation */
            $valid_roles = ["admin"];
            $role_validation = in_array($role, $valid_roles);
            if( !$role_validation )
            {
                $this->resp->msg = "Role is not valid. There are valid values: ".implode(', ',$valid_roles)." !";
                $this->jsonecho();
            }


            /**Step 4 - save*/
            try 
            {
                $Doctor->set("name", $name)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Drug is updated successfully !";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>