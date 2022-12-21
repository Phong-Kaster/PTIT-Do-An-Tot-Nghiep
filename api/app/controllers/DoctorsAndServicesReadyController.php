<?php 
    /**
     * @author Phong-Kaster
     * @since 18-10-2022
     * @update 12-12-2022
     * cái này sẽ lấy ra các bác sĩ chưa được thêm vào dịch vụ khám
     * Ví dụ: có tổng cộng 5 bác sĩ thì có 2 bác sĩ đã được thêm vào dịch vụ A
     * Controller này sẽ trả ra tên của 3 bác sĩ còn lại.
     */
    class DoctorsAndServicesReadyController extends Controller 
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
        }/**end PUBLIC FUNCTION PROCESS() */

        /**
         * @since 12-12-2022
         */
        private function getAll()
        {
                /**Step 1 - declare */
                $this->resp->result = 0;
                $Route = $this->getVariable("Route");
                $data = [];


                /**Step 2 - get id */
                if( !isset($Route->params->id) )
                {
                    $this->resp->msg = "Service ID is required !";
                    $this->jsonecho();
                }

                /** Step 3 - check service*/
                $id = $Route->params->id;
                $Service = Controller::model("Service", $id);
                if( !$Service->isAvailable() )
                {
                    $this->resp->msg = "Service is not available !";
                    $this->jsonecho();
                }


                try
                {
                    /** Step 4 - get doctors who are woking with this service */
                    $query = DB::table(TABLE_PREFIX.TABLE_SERVICES)
                    ->where(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.".service_id", "=", $id)

                    ->leftJoin(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE, 
                            TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.".service_id", "=", TABLE_PREFIX.TABLE_SERVICES.".id")

                    ->leftJoin(TABLE_PREFIX.TABLE_DOCTORS,
                            TABLE_PREFIX.TABLE_DOCTORS.".id", "=", TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.".doctor_id")

                    ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES,
                            TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")
                            
                    ->select([
                        DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".id as doctor_id")
                    ]);

                    $doctorNotReady = $query->get();// là mảng chứa ID của các bác sĩ đang làm việc với dịch vụ
                    $IDs= array_map(function($item){
                        return $item->doctor_id;
                        }, $doctorNotReady);

                

                    /**Step 5 - get doctor who are NOT working with this service */
                    $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)

                            ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES,
                                        TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")
                            ->select([
                                TABLE_PREFIX.TABLE_DOCTORS.".*",
                                DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".id as speciality_id"),
                                DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".name as speciality_name"),
                            ]);
                    if( count($IDs) > 0)
                    {
                        $query->whereNotIn(TABLE_PREFIX.TABLE_DOCTORS.".id", $IDs);
                    }
                    
                    $doctorReady = $query->get();
                    $quantity = count($doctorReady);

                    if( $quantity > 0)
                    {
                        foreach($doctorReady as $element)
                        {
                            $data[] = array(
                                "id" => (int)$element->id,
                                "name" => $element->name,
                                "avatar"=> $element->avatar,
                                "phone" => $element->phone,
                                "email" => $element->email,
                                "speciality" => array(
                                    "id" => (int)$element->speciality_id,
                                    "name" => $element->speciality_name
                                )
                            );
                        }
                    }

                    $this->resp->result = 1;
                    $this->resp->msg = "Action successfully";
                    $this->resp->quantity = $quantity;
                    $this->resp->service = array(
                        "id" => (int)$Service->get("id"),
                        "name" => $Service->get("name"),
                        "description" => $Service->get("description")
                    );
                    $this->resp->data = $data;
                } 
                catch (\Exception $ex) 
                {
                    $this->resp->msg = $ex->getMessage();
                }
                $this->jsonecho();
        }
    }
?>