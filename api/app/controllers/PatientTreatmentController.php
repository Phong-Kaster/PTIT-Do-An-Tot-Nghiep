<?php
    /**
     * @since 30-11-2022
     * Patient Treatment Controller
     */
    class PatientTreatmentController extends Controller
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
                $this->getById();
            }
        }

        /**
         * @since 30-11-2022
         */
        private function getById()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");


            /**Step 2 - check Treatment ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }


            /**Step 3 - get by id */
            try
            {
                $Treatment = Controller::model("Treatment", $Route->params->id);
                if( !$Treatment->isAvailable() )
                {
                    $this->resp->msg = "Treatment is not available";
                    $this->jsonecho();
                }

                $appointmentId = $Treatment->get("appointment_id");
                $Appointment = Controller::model("Appointment", $appointmentId);
                if( $Appointment->get("patient_id") != $AuthUser->get("id") )
                {
                    $this->resp->msg = "This treatment does not belong to you !";
                    $this->jsonecho();
                }


                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = array(
                    "id" => (int)$Treatment->get("id"),
                    "appointment_id" => (int)$Treatment->get("appointment_id"),
                    "name" => $Treatment->get("name"),
                    "type" => $Treatment->get("type"),
                    "times" => (int)$Treatment->get("times"),
                    "purpose" => $Treatment->get("purpose"),
                    "instruction" => $Treatment->get("instruction"),
                    "repeat_time" => $Treatment->get("repeat_time"),
                    "repeat_days" => $Treatment->get("repeat_days")
                );
            }
            catch(Exception $ex)
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>