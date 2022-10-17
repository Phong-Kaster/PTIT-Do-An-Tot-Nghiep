<?php 
    class DoctorController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");

            if (!$AuthUser)
            {
                header("Location: ".APPURL."/login");
                exit;
            }
            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
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
         * get doctor by id
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
                $Doctor = Controller::model("Doctor", $Route->params->id);
                if( !$Doctor->isAvailable() )
                {
                    $this->resp->msg = "Doctor is not available";
                    $this->jsonecho();
                }

                $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)
                        ->where(TABLE_PREFIX.TABLE_DOCTORS.".id", "=", $Route->params->id)
                        ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES, 
                                    TABLE_PREFIX.TABLE_SPECIALITIES.".id","=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")
                        ->select([
                            TABLE_PREFIX.TABLE_DOCTORS.".*",
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".id as speciality_id"),
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".name as speciality_name"),
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".description as speciality_description"),
                        ]);

                $result = $query->get();
                if( count($result) > 1 )
                {
                    $this->resp->msg = "Oops, there is an error occurring. Try again !";
                    $this->jsonecho();
                }

                
                $data = array(
                    "id" => (int)$result[0]->id,
                    "email" => $result[0]->email,
                    "phone" => $result[0]->phone,
                    "name" => $result[0]->name,
                    "description" => $result[0]->description,
                    "price" => (int)$result[0]->price,
                    "role" => $result[0]->role,
                    "avatar" => $result[0]->avatar,
                    "active" => (int)$result[0]->active,
                    "create_at" => $result[0]->create_at,
                    "update_at" => $result[0]->update_at,
                    "speciality" => array(
                        "id" => (int)$result[0]->speciality_id,
                        "name" => $result[0]->speciality_name,
                        "description" => $result[0]->speciality_description
                    )
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
         * update a doctor's information except password & email
         */
        private function update()
        {
            /**Step 0 - declare */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");

            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /**Step 1 - does the doctor exist ? */
            $Doctor = Controller::model("Doctor", $Route->params->id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "Doctor is not available. Try again !";
                $this->jsonecho();
            }

            $required_fields = ["phone", "name", "role"];
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
            // $email = Input::put("email");
            $phone = Input::put("phone");

            // $password = generateRandomString();
            // $passwordConfirm = Input::put("passwordConfirm");

            $name = Input::put("name");
            $description = Input::put("description") ? Input::put("description") : "Bác sĩ ".$name;

            $price = Input::put("price") ? Input::put("price") : 100000 ;
            $role = Input::put("role") ? Input::put("role") : "member";

            $avatar = Input::put("avatar")  ? Input::put("avatar") : "default_avatar.jpg";
            $active = Input::put("active");
            
            //$create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            $speciality_id = Input::put("speciality_id") ? (int)Input::put("speciality_id") : 1;
            $clinic_id = Input::put("clinic_id") ? (int)Input::put("clinic_id") : 1;



            /**Step 3 - validation */
            /**Step 3.1 - name  validation*/
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

            /**Step 3.3 -  price validation */
            $price_validation = isNumber($price);
            if( !$price_validation )
            {
                $this->resp->msg = "This is not a valid price. Please, try again !";
                $this->jsonecho();
            }
            if( $price < 100000 )
            {
                $this->resp->msg = "Price must greater than 100.000 !";
                $this->jsonecho();
            }

            /**Step 3.4 - role validation */
            $valid_roles = ["admin", "member"];
            $role_validation = in_array($role, $valid_roles);
            if( !$role_validation )
            {
                $this->resp->msg = "Role is not valid. There are 2 valid values: admin & member !";
                $this->jsonecho();
            }

            /**Step 3.5 - speciality validation */
            $Speciality = Controller::model("Speciality", $speciality_id);
            if( !$Speciality->isAvailable() )
            {
                $this->resp->msg = "Speciality is not available.";
                $this->jsonecho();
            }

            /**Step 3.6 - clinic validation */
            $Clinic = Controller::model("Clinic", $clinic_id);
            if( !$Clinic->isAvailable() )
            {
                $this->resp->msg = "Clinic is not available.";
                $this->jsonecho();
            }


            /**Step 4 - save*/
            try 
            {
                $Doctor->set("phone", $phone)
                        ->set("name", $name)
                        ->set("description", $description)
                        ->set("price", $price)
                        ->set("role", $role)
                        ->set("active", $active)
                        ->set("avatar", $avatar)
                        ->set("update_at", $update_at)
                        ->set("speciality_id", $speciality_id)
                        ->set("clinic_id", $clinic_id)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Doctor account is updated successfully !";
                $this->resp->data = array(
                    "id" => (int)$Doctor->get("id"),
                    "email" => $Doctor->get("email"),
                    "phone" => $Doctor->get("phone"),
                    "name" => $Doctor->get("name"),
                    "description" => $Doctor->get("description"),
                    "price" => $Doctor->get("price"),
                    "role" => $Doctor->get("role"),
                    "avatar" => $Doctor->get("avatar"),
                    "active" => (int)$Doctor->get("active"),
                    "create_at" => $Doctor->get("create_at"),
                    "update_at" => $Doctor->get("update_at"),
                    "speciality_id" => (int)$Doctor->get("speciality_id"),
                    "clinic_id" => (int)$Doctor->get("clinic_id")
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
         * @since 13-10-2022
         * delete a doctor
         * Case 0: can not delete yourself the current account which is logging
         * Condition 2a: can not delete a doctor having booking
         * Condition 2b: can not delete a doctor having appointments.
         * Case 1: if a doctor does not violate the above, he/she will be deleted.
         * Case 2: if a doctor violates one of the above, he/she will be deactivated.
         * 
         * Neu mà hủy kích hoạt bác sĩ đang có bệnh nhân đặt khám hoặc lịch hẹn thì cần phải 
         * chuyển trạng thái các cuộc hẹn trên về dạng HỦY BỎ
         */
        private function delete()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $msg = "Doctor is deactivated successfully";
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }

            /**Step 2 - check required data */
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /**Step 3 (Case 0) - can not delete yourself the current account which is logging */
            if( $AuthUser->get("id") == $Route->params->id )
            {
                $this->resp->msg = "You can not deactivate yourself !";
                $this->jsonecho();
            }


            /**Step 4 - is this doctor active? */
            $id = $Route->params->id;
            $Doctor = Controller::model("Doctor", $id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "Doctor is not available !";
                $this->jsonecho();
            }
            if( $Doctor->get("active") != 1)
            {
                $this->resp->msg = "This doctor account was deactivated. No need this action !";
                $this->jsonecho();
            }

            /**Step 5 ( Case 2a ): can not delete a doctor having booking or appointments */
            // $queryBooking = DB::table(TABLE_PREFIX.TABLE_BOOKINGS)
            //         ->where(TABLE_PREFIX.TABLE_BOOKINGS.".doctor_id", "=", $id)
            //         ->select("*");
            // $quantityBooking = $queryBooking->get();


            /**Step 5 ( Case 2b ):: can not delete a doctor having appointments*/
            $queryAppointment = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $id)
                    ->select("*");
            $quantityAppointment = $queryAppointment->get();


            try 
            {
                /**Step 6 ( Case 1 ): if a doctor does not violate the above, he/she will be deleted*/
                if( count($quantityAppointment) > 0 )
                {
                    /**Cancel all appointments with this doctor */
                    $queryBooking = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $id)
                                    ->update(array(
                                        "status" => "cancelled"
                                    ));
                                

                    $Doctor->set("active", 0)
                        ->save();
                }
                /**Step 6 ( Case 2 ): if a doctor does not violate the above, he/she will be deleted*/
                else 
                {
                    $Doctor->delete();
                    $msg = "Doctor is deleted successfully !";
                }

                $this->resp->result = 1;
                $this->resp->msg = $msg;
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>