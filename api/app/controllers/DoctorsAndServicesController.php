<?php 
    /**
     * @author Phong-Kaster
     * @since 18-10-2022
     * @update 12-12-2022
     * each doctor can serve many services so that we use 2 functions to manage
     * getAll - get all services that doctor is working.
     * update - update services that doctor is ready to work.
     */
    class DoctorsAndServicesController extends Controller 
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
                $this->create();
            }
            else if( $request_method === 'DELETE')
            {
                $this->delete();
            }
        }






        /**
         * @author Phong-Kaster
         * @since 12-12-2022
         * get service info and all doctors are woking with this service
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
                    DB::raw(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.".id as doctor_and_service_id"),
                    DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".avatar as doctor_avatar"),
                    DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".id as doctor_id"),
                    DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".name as doctor_name"),
                    DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".phone as doctor_phone"),
                    DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".email as doctor_email"),
                    DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".id as speciality_id"),
                    DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".name as speciality_name"),
                    

                ]);

                $result = $query->get();
                $quantity = count($result);

                if( $quantity > 0)
                {
                    foreach($result as $element)
                    {
                        $data[] = array(
                            "doctor_and_service_id" => (int)$element->doctor_and_service_id,
                            "id" => (int)$element->doctor_id,
                            "name" => $element->doctor_name,
                            "avatar"=> $element->doctor_avatar,
                            "phone" => $element->doctor_phone,
                            "email" => $element->doctor_email,
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


        /**
         * @author Phong-Kaster
         * @since 12-12-2022
         * add a pair of doctor_id & service_id to TABLE DOCTORS_AND_SERVICES
         */
        private function create()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");
            $data = [];

            /**Step 2 - get id - the id of service*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "Service ID is required !";
                $this->jsonecho();
            }

            /** Step 3 - check service*/
            $service_id = $Route->params->id;
            $Service = Controller::model("Service", $service_id);
            if( !$Service->isAvailable() )
            {
                $this->resp->msg = "Service is not available !";
                $this->jsonecho();
            }

            $doctor_id = Input::post("doctor_id");
            if( !$doctor_id )
            {
                $this->resp->msg = "Doctor ID is required !";
                $this->jsonecho();
            }
            $Doctor = Controller::model("Doctor", $doctor_id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "Doctor is not available";
                $this->jsonecho();
            }
            if( $Doctor->get("active") != 1)
            {
                $this->resp->msg = "Doctor was deactivated !";
                $this->jsonecho(); 
            }



            try 
            {
                $DoctorAndService = Controller::model("DoctorAndService");
                $DoctorAndService->set("service_id", $service_id)
                                ->set("doctor_id", $doctor_id)
                                ->save();
                
                $this->resp->result = 1;
                $this->resp->msg = "Created successfully";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }


        /**
         * @author Phong-Kaster
         * @since 12-12-2022
         * delete a pair of doctor_id and service_id with @id that the
         * ID from table DOCTOR_AND_SERVICE
         */
        private function delete()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");
            $data = [];

            /**Step 2 - @id is one of ID from table DOCTOR AND SERVICE  */
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /** Step 3 - check service*/
            $doctor_and_service_id = $Route->params->id;
            $DoctorAndService = Controller::model("DoctorAndService", $doctor_and_service_id);
            if( !$DoctorAndService->isAvailable() )
            {
                $this->resp->msg = "DoctorAndService is not available !";
                $this->jsonecho();
            }

            try 
            {
                $DoctorAndService->delete();

                $this->resp->result = 1;
                $this->resp->msg = "Deleted successfully";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }

        /**
         * @author Phong-Kaster
         * @since 18-10-2022
         * get all services that doctor is ready to work
         */
        private function oldFlowGetAll()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");
            $data = [];

            /**Step 2 - check required data - this is is doctorId */
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /**Step 3 - does he exist ? */
            $doctorId = $Route->params->id;
            $Doctor = Controller::model("Doctor", $doctorId);
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

            /**Step 4 - get services that he/she is working */
            $query = DB::table(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE)
                ->leftJoin(TABLE_PREFIX.TABLE_SERVICES, 
                    TABLE_PREFIX.TABLE_SERVICES.".id", "=", TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.".service_id")
                ->select([
                    DB::raw(TABLE_PREFIX.TABLE_SERVICES.".id as service_id"),
                    DB::raw(TABLE_PREFIX.TABLE_SERVICES.".name as service_name")
                ]);

            $result = $query->get();
            
            foreach($result as $element)
            {
                $data[] = array(
                    "id" => (int)$element->service_id,
                    "name" => $element->service_name
                );
            }

            /**Step 5 - return */
            $this->resp->result = 1;
            $this->resp->quantity = count($result);
            $this->resp->data = $data;
            $this->jsonecho();
        }

        /**
         * @author Phong-Kaster
         * @since 18-10-2022
         * this function plays a role as CREATE - UPDATE - DELETE
         * update services matching with the doctor
         */
        private function oldFlowUpdate()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");
            $data = [];

            /**Step 2 - check required data - this is is doctorId */
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            /**Step 3 - does he exist ? */
            $doctorId = $Route->params->id;
            $Doctor = Controller::model("Doctor", $doctorId);
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

            /**Step 4 - service validation */
            /**Step 4.1 - do we get Service Array? */
            $services = Input::post("services");
            if( !$services || !is_array($services) )
            {
                $this->resp->msg = "Services is not valid !";
                $this->jsonecho();
            }
            /**Step 4.2 - are they available ? */
            foreach ( $services as $element) 
            {
                print_r($element);
                $Service = Controller::model("Service", (int)$element);
                if( !$Service->isAvailable() )
                {
                    $this->resp->msg = "One of services is not available !";
                    $this->jsonecho();
                }
            }

            /**Step 4.3 - delete previous se */
            DB::table(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE)
            ->where( TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE.'.doctor_id',"=", $doctorId)
            ->delete();

            /**Step 5 - create an array to prepare to insert */
            $data = array();
            foreach($services as $id){
                $data[] = array(
                    'service_id'  => $id,
                    'doctor_id' => $Doctor->get("id")
                );
            }

            if(count($data) > 0)
            {
                DB::table(TABLE_PREFIX.TABLE_DOCTOR_AND_SERVICE)->insert($data);
            }

            $this->resp->result = 1;
            $this->resp->msg = "Updated successfully";
            $this->jsonecho();
        }
    }
?>