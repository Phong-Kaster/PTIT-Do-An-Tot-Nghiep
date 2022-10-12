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
    }
?>