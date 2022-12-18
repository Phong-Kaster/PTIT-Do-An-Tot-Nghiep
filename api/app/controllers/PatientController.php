<?php 
    class PatientController extends Controller
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
         * @since 14-10-2022
         * get patient information by id
         * any one who are logging, can use this API
         */
        private function getById()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");


            if( !$AuthUser )
            {
                $this->resp->msg = "You are not logging !";
                $this->jsonecho();
            }

            $valid_roles = ["admin", "supporter"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You don't have permission to do this action. Only "
                .implode(', ', $valid_roles)." can do this action !";
                $this->jsonecho();
            }




            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }


            /**Step 3 - get by id */
            $Patient = Controller::model("Patient", $Route->params->id);
            if( !$Patient->isAvailable() )
            {
                $this->resp->msg = "Patient is not available";
                $this->jsonecho();
            }

            $query = DB::table(TABLE_PREFIX.TABLE_PATIENTS)
                    ->where(TABLE_PREFIX.TABLE_PATIENTS.".id", "=", $Route->params->id)
                    ->select("*");

            try
            {
                $result = $query->get();
                if( count($result) == 0 )
                {
                    $this->resp->msg = "Oops, there is an error occurring. Try again !";
                    $this->jsonecho();
                }

                
                $data = array(
                    "id" => (int)$result[0]->id,
                    "email" => $result[0]->email,
                    "phone" => $result[0]->phone,
                    "name" => $result[0]->name,
                    "gender" => (int)$result[0]->gender,
                    "birthday" => $result[0]->birthday,
                    "address" => $result[0]->address,
                    "avatar" => $result[0]->avatar,
                    "create_at" => $result[0]->create_at,
                    "update_at" => $result[0]->update_at
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
         * @since 14-10-2022
         * update patient information by id
         * only doctors can use this API
         */
        private function update()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");


            //Only doctors have the field of "role"
            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You does not have permission to use this API !";
                $this->jsonecho();
            }



            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }


            $id = $Route->params->id;
            $Patient = Controller::model("Patient", $id);
            if( !$Patient->isAvailable() )
            {
                $this->resp->msg = "Patient is not available !";
                $this->jsonecho();
            }


            /**Step 3 - required fields */
            $required_fields = ["name", "phone", "birthday"];
            foreach( $required_fields as $field )
            {
                if( !Input::put($field))
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            $email = Input::put("email");
            $phone = Input::put("phone");
            $name = Input::put("name");
            $birthday = Input::put("birthday");
            $address = Input::put("address");
            //$avatar = Input::put("avatar");
            //$create_at = date("Y-m-d H:i:s");
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $update_at = date("Y-m-d H:i:s");
            $gender = Input::put("gender") ? Input::put("gender") : 0;

            /**Step 4 - validation*/
            /**Step 3.1 - name validation */
            $name_validation = isVietnameseName($name);
            if( $name_validation == 0 ){
                $this->resp->msg = "Vietnamese name only has letters and space";
                $this->jsonecho();
            }

            /**Step 3.2 - phone validation */
            if( strlen($phone) < 10 ){
                $this->resp->msg = "Phone number has at least 10 number !";
                $this->jsonecho();
            }
    
            $phone_number_validation = isNumber($phone);
            if( !$phone_number_validation ){
                $this->resp->msg = "This is not a valid phone number. Please, try again !";
                $this->jsonecho();
            }

            /*Step 3.3 - birthday validation */
            // $yearBirthday = (int)substr($birthday, 6);
            // $monthBirthday = (int)substr($birthday,3,5);
            // $dayBirthday = (int)substr($birthday,0,2);

            $yearBirthday = (int)substr($birthday, 0,4);
            $monthBirthday = (int)substr($birthday,5,8);
            $dayBirthday = (int)substr($birthday,8,10);


            $yearToday = (int)date("Y");
            $monthToday = (int)date("m");
            $dayToday = (int)date("d");


            $yearDifference  = $yearToday - $yearBirthday;
            $monthDifference = $monthToday - $monthBirthday;
            $dayDifference   = $dayToday - $dayBirthday;

            $today = date("D, d-m-Y");

            /*Step 3.3 - Case 1 - birthday is not valid*/
            $birthday_validation = checkdate($monthBirthday, $dayBirthday, $yearBirthday);
            if( !$birthday_validation )
            {
                $this->resp->msg = "Your birthday - ".$birthday." - does not exist !";
                $this->jsonecho();
            }
            /*Step 3.3 - Case 2 - yearBirthday(2023) > yearToday(2022)*/
            if( $yearDifference < 0)
            {
                $this->resp->msg = "Today is ".$today." so that birthday is not valid !";
                $this->jsonecho();
            }
            /*Step 3.3 - Case 3 - yearBirthday == yearToday*/
            else if( $yearDifference == 0)
            {
                //Case 3.1. monthBirthday > monthToday
                if( $monthDifference < 0  )
                {
                    $this->resp->msg = "Today is ".$today." so that birthday is not valid !";
                    $this->jsonecho();
                }
                //Case 3.2. monthBirthday == monthToday
                else if( $monthDifference == 0)
                {
                    // dayBirthday = 15 but dayToday = 13
                    if( $dayDifference < 0)
                    {
                        $this->resp->msg = "Today is ".$today." so that birthday is not valid !";
                        $this->jsonecho();
                    }
                }
                //Case 3.3. monthBirthday < monthToday
                else
                {
                    // do thing
                }
            }
            /*Step 3.3 - Case 4 - yearBirthday < yearToday*/
            else
            {
                //always correct
            }

            /**Step 3.4 - address */
            $address_validation = isAddress($address);
            if( $address_validation == 0)
            {
                $this->resp->msg = "Address only accepts letters, space & number";
                $this->jsonecho();
            }

            /**Step 3.5 - gender validation*/
            $valid_gender = [0,1];
            $gender_validation = in_array($gender, $valid_gender);
            if( !$gender_validation )
            {
                $this->resp->msg = "Gender value is not correct. There are 2 values: 0 is female & 1 is man";
                $this->jsonecho();
            }

            /**Step 4 - save */
            try 
            {
                $Patient->set("phone",$phone)
                        ->set("name", $name)
                        ->set("gender", $gender)
                        ->set("birthday", $birthday)
                        ->set("address", $address)
                        ->set("update_at", $update_at)
                        ->save();
                    
                $this->resp->result = 1;
                $this->resp->msg = "Patient personal information has been updated successfully !";
                $this->resp->data = array(
                    "id" => (int)$Patient->get("id"),
                    "email" => $Patient->get("email"),
                    "phone" => $Patient->get("phone"),
                    "name" => $Patient->get("name"),
                    "gender" => (int)$Patient->get("gender"),
                    "birthday" => $Patient->get("birthday"),
                    "address" => $Patient->get("address"),
                    "avatar" => $Patient->get("avatar"),
                    "create_at" => $Patient->get("create_at"),
                    "update_at" => $Patient->get("update_at")
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
         * @since 14-10-2022
         * delete patient information by id
         * only doctors whose role is ADMIN can use this API
         */
        private function delete()
        {
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");

            if( isset($Route->params->id) && $Route->params->id == 1 )
            {
                $this->resp->msg = "This patient is an example & can be deleted !";
                $this->jsonecho();
            }

            // chắc không xóa thông tin bệnh nhân => sau này bán thông tin bệnh nhân kiếm tiền
            $this->resp->msg = "This action is not allowed !";
            $this->jsonecho();
        }
    }
?>