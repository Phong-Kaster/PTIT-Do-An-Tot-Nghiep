<?php 
    class DoctorsController extends Controller
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
                $this->getAll();
            }
            else if( $request_method === 'POST')
            {
                $this->save();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 12-10-2022
         * get all doctors
         */
        private function getAll()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data = [];
            

            if( !$AuthUser || $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }


            /**Step 2 - get filters */
            $order          = Input::get("order");
            $search         = Input::get("search");
            $length         = Input::get("length") ? (int)Input::get("length") : 10;
            $start          = Input::get("start") ? (int)Input::get("start") : 0;

            try
            {
                /**Step 3 - query */
                $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)
                        ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES, 
                                    TABLE_PREFIX.TABLE_SPECIALITIES.".id","=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")
                        ->select([
                            TABLE_PREFIX.TABLE_DOCTORS.".*",
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".id as speciality_id"),
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".name as speciality_name"),
                            DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".description as speciality_description"),
                        ]);

                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {
                        $q->where(TABLE_PREFIX.TABLE_DOCTORS.".email", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_DOCTORS.".phone", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_DOCTORS.".name", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_DOCTORS.".description", 'LIKE', $search_query.'%');
                    }); 
                }
                
                /**Step 3.2 - order filter */
                if( $order && isset($order["column"]) && isset($order["dir"]))
                {
                    $type = $order["dir"];
                    $validType = ["asc","desc"];
                    $sort =  in_array($type, $validType) ? $type : "desc";


                    $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
                    $column_name = str_replace(".", "_", $column_name);


                    if(in_array($column_name, ["email", "name", "phone", "speciality_id"])){
                        $query->orderBy(DB::raw($column_name. " * 1"), $sort);
                    }else{
                        $query->orderBy($column_name, $sort);
                    }
                }
                else 
                {
                    $query->orderBy("id", "desc");
                } 

                /**Step 3.3 - length filter * start filter*/
                $query->limit($length ? $length : 10)
                    ->offset($start ? $start : 0);



                /**Step 4 */
                $result = $query->get();
                foreach($result as $element)
                {
                    $data[] = array(
                        "id" => (int)$element->id,
                        "email" => $element->email,
                        "phone" => $element->phone,
                        "name" => $element->name,
                        "description" => $element->description,
                        "price" => (int)$element->price,
                        "role" => $element->role,
                        "avatar" => $element->avatar,
                        "active" => (int)$element->active,
                        "create_at" => $element->create_at,
                        "update_at" => $element->update_at,
                        "speciality" => array(
                            "id" => (int)$element->speciality_id,
                            "name" => $element->speciality_name,
                            "description" => $element->speciality_description
                        )
                    );
                }


                /**Step 5 - return */
                $this->resp->result = 1;
                $this->resp->quantity = count($result);
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
         * @since 12-10-2022
         * create a new doctor
         */
        private function save()
        {
            /**Step 1 - get input data */
            $this->resp->result = 0;

            $required_fields = ["email", "phone", "name", "role"];
            foreach($required_fields as $field)
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            /**Step 2 - declare*/
            $email = Input::post("email");
            $phone = Input::post("phone");

            $password = generateRandomString();
            // $passwordConfirm = Input::post("passwordConfirm");

            $name = Input::post("name");
            $description = Input::post("description") ? Input::post("description") : "Bác sĩ ".$name;

            $price = Input::post("price") ? Input::post("price") : 100000 ;
            $role = Input::post("role") ? Input::post("role") : "member";

            $avatar = "default_avatar.jpg";
            $active = 1;
            
            $create_at = date("Y-m-d H:i:s");
            $update_at = date("Y-m-d H:i:s");

            $speciality_id = Input::post("speciality_id") ? (int)Input::post("speciality_id") : 1;
            // $clinic_id = Input::post("clinic_id") ? (int)Input::post("clinic_id") : 1;


            /**Step 3 - validation */
            /**Step 3.1 - FILTER_VALIDATE_EMAIL */
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->resp->msg = "Email is not correct format. Try again !";
                $this->jsonecho();
            }

            /**Step 3.2 - check duplication */
            $Doctor = Controller::model("Doctor", $email);
            if( $Doctor->isAvailable() )
            {
                $this->resp->msg = "This email is used by someone. Try another !";
                $this->jsonecho();
            }

            /**Step 3.3 - password validation*/
            if (mb_strlen($password) < 6) 
            {
                $this->resp->msg = __("Password must be at least 6 character length !");
                $this->jsonecho();
            } 
            // else if ($password != $passwordConfirm) 
            // {
            //     $this->resp->msg = __("Password confirmation does not equal to password !");
            //     $this->jsonecho();
            // }

            /**Step 3.4 - name  validation*/
            $name_validation = isVietnameseName($name);
            if( $name_validation == 0 ){
                $this->resp->msg = "Vietnamese name only has letters and space";
                $this->jsonecho();
            }

            /**Step 3.5 - phone validation */
            if( strlen($phone) < 10 ){
                $this->resp->msg = "Phone number has at least 10 number !";
                $this->jsonecho();
            }
    
            $phone_number_validation = isNumber($phone);
            if( !$phone_number_validation ){
                $this->resp->msg = "This is not a valid phone number. Please, try again !";
                $this->jsonecho();
            }

            /**Step 3.6 -  price validation */
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

            /**Step 3.7 - role validation */
            $valid_roles = ["admin", "member", "supporter"];
            $role_validation = in_array($role, $valid_roles);
            if( !$role_validation )
            {
                $this->resp->msg = "Role is not valid. There are 2 valid values: admin, member & supporter !";
                $this->jsonecho();
            }

            /**Step 3.8 - speciality validation */
            $Speciality = Controller::model("Speciality", $speciality_id);
            if( !$Speciality->isAvailable() )
            {
                $this->resp->msg = "Speciality is not available.";
                $this->jsonecho();
            }

            /**Step 3.9 - clinic validation */
            // $Clinic = Controller::model("Clinic", $clinic_id);
            // if( !$Clinic->isAvailable() )
            // {
            //     $this->resp->msg = "Clinic is not available.";
            //     $this->jsonecho();
            // }


            /**Step 4 - save*/
            try 
            {
                $Doctor = Controller::model("Doctor");
                $Doctor->set("email", strtolower($email))
                        ->set("phone", $phone)
                        ->set("password", password_hash($password, PASSWORD_DEFAULT))
                        ->set("name", $name)
                        ->set("description", $description)
                        ->set("price", $price)
                        ->set("role", $role)
                        ->set("active", $active)
                        ->set("avatar", $avatar)
                        ->set("create_at", $create_at)
                        ->set("update_at", $update_at)
                        ->set("speciality_id", $speciality_id)
                        ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Doctor account is created successfully !";
                $this->resp->data = array(
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
                    "speciality_id" => $Doctor->get("speciality_id")
                );

                $data = [
                    "email" => strtolower($email),
                    "phone" => $phone,
                    "name" => $name,
                    "password" => $password
                ];

                MyEmail::signup($data);
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>