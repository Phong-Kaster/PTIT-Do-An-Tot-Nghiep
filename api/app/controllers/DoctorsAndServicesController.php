<?php 
    /**
     * @author Phong-Kaster
     * @since 18-10-2022
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
                $this->update();
            }
        }



        /**
         * @author Phong-Kaster
         * @since 18-10-2022
         * get all services that doctor is ready to work
         */
        private function getAll()
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
        private function update()
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