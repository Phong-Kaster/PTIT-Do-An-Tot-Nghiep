<?php
    /**
     * @author Phong-Kaster
     * @since 14-10-2022
     * Doctor Profile Controller is used by Doctor to change personal information.
     * 1. phone
     * 2. password
     * 3. name
     * 4. description
     * 5. price
     * 6. avatar
     * 7. special_id
     * 8. clinic_id
     */
    class DoctorProfileController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");

            if (!$AuthUser)
            {
                header("Location: ".APPURL."/login");
                exit;
            }

            
            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getInformation();
            }
            if( $request_method === 'POST')
            {
                $action = Input::post("action");
                switch ($action) {
                    case "personal":
                        echo $action;
                        break;
                    case "password":
                        $this->changePassword();
                        break;
                    case "avatar":
                        $this->changeAvatar();
                        break;
                }
            }
        }

        /**
         * @author Phong-Kaster
         * @since 14-10-2022
         * update personal information
         */
        private function getInformation()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");


            /**Step 2 - is the doctor active ? */
            if( !$AuthUser )
            {
                $this->resp->msg = "You does not log in !";
                $this->jsonecho();
            }

            $id = $AuthUser->get("id");
            $Doctor = Controller::model("Doctor", $id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "This account is not available !";
                $this->jsonecho();
            }


            if( $Doctor->get("active") != 1 )
            {
                $this->resp->msg = "This account is deactivated !";
                $this->jsonecho();
            }



            /**Step 3 - get by id */
            try
            {
                $Doctor = Controller::model("Doctor", $id);
                if( !$Doctor->isAvailable() )
                {
                    $this->resp->msg = "This account is not available";
                    $this->jsonecho();
                }

                $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)
                        ->where(TABLE_PREFIX.TABLE_DOCTORS.".id", "=", $id)
                        ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES, 
                                    TABLE_PREFIX.TABLE_SPECIALITIES.".id","=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")
                        ->leftJoin(TABLE_PREFIX.TABLE_CLINICS, 
                                    TABLE_PREFIX.TABLE_CLINICS.".id","=", TABLE_PREFIX.TABLE_DOCTORS.".clinic_id")
                        ->select([
                            TABLE_PREFIX.TABLE_DOCTORS.".*",
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".id as speciality_id"),
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".name as speciality_name"),
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".description as speciality_description"),
                            DB::raw(TABLE_PREFIX.TABLE_CLINICS.".id as clinic_id"),
                            DB::raw(TABLE_PREFIX.TABLE_CLINICS.".name as clinic_name"),
                            DB::raw(TABLE_PREFIX.TABLE_CLINICS.".address as clinic_address")
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
                    "speciality_name" => $result[0]->speciality_name,
                    "clinic_name" => $result[0]->clinic_name,
                    "create_at" => $result[0]->create_at,
                    "update_at" => $result[0]->update_at,
                    "speciality" => array(
                        "id" => (int)$result[0]->speciality_id,
                        "name" => $result[0]->speciality_name,
                        "description" => $result[0]->speciality_description
                    ),
                    "clinic"=> array(
                        "id" => (int)$result[0]->clinic_id,
                        "name" => $result[0]->clinic_name,
                        "address" => $result[0]->clinic_address
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
         * @since 14-10-2022
         * update password
         */
        private function changePassword()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");

            if( !$AuthUser )
            {
                $this->resp->msg = "You does not log in !";
                $this->jsonecho();
            }



            /**Step 2 - get required field */
            $required_field = ["currentPassword", "newPassword", "confirmPassword"];
            foreach($required_field as $field)
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            $id = $AuthUser->get("id");
            $currentPassword = Input::post("currentPassword");
            $newPassword     = Input::post("newPassword");
            $confirmPassword = Input::post("confirmPassword");

            /**Step 3 - is the doctor active ? */
            $Doctor = Controller::model("Doctor", $id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "This account is not available !";
                $this->jsonecho();
            }

            if( $Doctor->get("active") != 1 )
            {
                $this->resp->msg = "This account is deactivated !";
                $this->jsonecho();
            }


            /**Step 4 - validation */
            $hash = $Doctor->get("password");
            if(  !password_verify( $currentPassword, $hash ) )
            {
                $this->resp->msg = "Your current password is incorrect. Try again !";
                $this->jsonecho();
            }
            if (mb_strlen($newPassword) < 6) 
            {
                $this->resp->msg = __("Password must be at least 6 character length!");
                $this->jsonecho();
            } 
            if ($newPassword != $confirmPassword) 
            {
                $this->resp->msg = __("Password confirmation does not equal to new password !");
                $this->jsonecho();
            }

            /**Step 5 - save */
            try 
            {
                $Doctor->set("password", password_hash($newPassword, PASSWORD_DEFAULT))
                    ->save();

                $this->resp->result = 1;
                $this->resp->msg = "New password has been updated successfully. Don't forget to login again !";
                $this->resp->data = array(
                    "id" => (int)$Doctor->get("id"),
                    "email" => $Doctor->get("email"),
                    "phone" => $Doctor->get("phone"),
                    "name" => $Doctor->get("name"),
                    "description" => $Doctor->get("description"),
                    "price" => $Doctor->get("price"),
                    "role" => $Doctor->get("role"),
                    "active" => (int)$Doctor->get("active"),
                    "avatar" => $Doctor->get("avatar"),
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
         * @since 14-10-2022
         * update password
         */
        private function changeAvatar()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");


            /**Step 2 - is the doctor active ? */
            if( !$AuthUser )
            {
                $this->resp->msg = "You does not log in !";
                $this->jsonecho();
            }
            if( !$AuthUser->isAvailable() )
            {
                $this->resp->msg = "This account is not available !";
                $this->jsonecho();
            }
            if( $AuthUser->get("active") != 1 )
            {
                $this->resp->msg = "This account is deactivated !";
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
            $name = "avatar_".$AuthUser->get("id")."_".$timestamp;
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
                $AuthUser->set("avatar", $name . "." .$ext)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = __("Avatar has been updated successfully !");
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