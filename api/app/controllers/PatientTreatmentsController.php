<?php
    /**
     * @author Phong-Kaster
     * @since 30-11-2022
     */
    class PatientTreatmentsController extends Controller
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

            if( $AuthUser->get("role") )
            {
                 $this->resp->result = 0;
                 $this->resp->msg = "This function is only for PATIENT !";
                 $this->jsonecho();
            }

            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getAll();
            }
        }

        /**
         * @since 30-11-2022
         */
        private function getAll()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");
            $data = [];
            

            /**Check Appointment's ID */
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "Appointment ID is required !";
                $this->jsonecho();
            }

            /**Step 2 - get filters */
            $appointment_id = $Route->params->id;

            $Appointment = Controller::model("Appointment", $appointment_id);
            if( !$Appointment->isAvailable() )
            {
                $this->resp->msg = "This appointment is not available!";
                $this->jsonecho();
            }
            if( $Appointment->get("patient_id") != $AuthUser->get("id") )
            {
                $this->resp->msg = "This appointment does not belong to you!";
                $this->jsonecho();
            }

            try
            {
                /**Step 3 - query */
                $query = DB::table(TABLE_PREFIX.TABLE_TREATMENTS)
                        ->leftJoin(TABLE_PREFIX.TABLE_APPOINTMENTS,
                                    TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "=", TABLE_PREFIX.TABLE_TREATMENTS.".appointment_id")
                        ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".id", "=", $appointment_id)
                        ->orderBy("id", "asc")
                        ->select([
                            TABLE_PREFIX.TABLE_TREATMENTS.".*"
                        ]);




                $res = $query->get();
                $quantity = count($res);
    
    
    
                /**Step 4 */
                $result = $query->get();
                foreach($result as $element)
                {
                    $data[] = array(
                        "id" => (int)$element->id,
                        "appointment_id" => (int)$element->appointment_id,
                        "name" => $element->name,
                        "type" => $element->type,
                        "times" => (int)$element->times,
                        "purpose" => $element->purpose,
                        "instruction" => $element->instruction,
                        "repeat_days" => $element->repeat_days,
                        "repeat_time" => $element->repeat_time
                    );
                }
    
    
                /**Step 5 - return */
                $this->resp->result = 1;
                $this->resp->quantity = $quantity;
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