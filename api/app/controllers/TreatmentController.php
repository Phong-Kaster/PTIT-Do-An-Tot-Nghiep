<?php 
    class TreatmentController extends Controller
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

            $valid_roles = ["admin", "member"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "Only Doctor's role as ".implode(', ', $valid_roles)." who can do this action";
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
         * @since 22-10-2022
         * get treatment by id
         */
        private function getById()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");


            /**Step 2 - check ID*/
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
                    "repeat_days" => $Treatment->get("repeat_days"),
                    "repeat_time" => $Treatment->get("repeat_time")
                );
            }
            catch(Exception $ex)
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }

        

        /**
         * @author Phong-Kaster
         * @since 22-10-2022
         * update treatment but only appointment's status is PROCESSING
         * 
         * - There fields CAN be updated as following:
         * 1. name
         * 2. type
         * 3. times
         * 4. purpose
         * 5. instruction
         * 
         * - There fields CAN NOT be updated as following:
         * 1. appointment_id
         * 
         * - Only change appointments has not occured. 
         * Today is 23-10-2022 & appointment_date is 22-10-2022 => this appointment can not changed
         */
        private function update()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");
            $AuthUser = $this->getVariable("AuthUser");

            /**Step 2 - required fields*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }
            $Treatment = Controller::model("Treatment", $Route->params->id);
            if( !$Treatment->isAvailable() )
            {
                $this->resp->msg = "Treatment is not available";
                $this->jsonecho();
            }


            $required_fields = ["name", "type", "times", 
                                "purpose", "instruction"];
            foreach( $required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }


            /**Step 3 - list required data */
            $appointment_id = $Treatment->get("appointment_id");
            $name = Input::put("name");
            $type = Input::put("type");
            $times = Input::put("times");
            $purpose = Input::put("purpose");
            $instruction = Input::put("instruction");
            $repeat_days = Input::post("repeat_days") ? Input::post("repeat_days") : "Thực hiện một lần";
            $repeat_time = Input::post("repeat_time") ? Input::post("repeat_time") : "Bác sĩ không chỉ định";


            /**Step 4 - check exist*/
            $Appointment = Controller::model("Appointment", $appointment_id);
            if( !$Appointment->isAvailable() )
            {
                $this->resp->msg = "Appointment is not available";
                $this->jsonecho();
            }

            /**Step 5 - Appointment status must be PROCESSING to continue */
            if( $Appointment->get("status") != "processing")
            {
                $this->resp->msg = "You just can't do this action when appointment's status is ".$Appointment->get("status");
                $this->jsonecho();
            }

            /**Step 6 - if Doctor's role is MEMBER => he just only change appointments belongs to him */
            if( $AuthUser->get("role") == "member" && $Appointment->get("doctor_id") != $AuthUser->get("id") )
            {
                $Doctor = Controller::model("Doctor",  $Appointment->get("doctor_id") );
                $this->resp->msg = "This treatment belongs to doctor ".$Doctor->get("name")." so that you can't do this action";
                $this->jsonecho();
            }


            /**Step 7 - is appointment's date today? 
             * If no, this appointment can not be changed ! 
             * 
             * Today is 23-10-2022 & appointment_date is 22-10-2022 
             * => this appointment can not changed
             * */
            $today = Date("d-m-Y");
            $appointment_date = $Appointment->get("date");
            
            $difference = abs(strtotime($today) - strtotime($appointment_date));
            $differenceYear = floor($difference / (365*60*60*24));
            $differenceMonth = floor(($difference - $differenceYear * 365*60*60*24) / (30*60*60*24));
            $differenceDay = floor(($difference - $differenceYear * 365*60*60*24 - $differenceMonth*30*60*60*24)/ (60*60*24));

            if( $differenceDay > 0 )
            {
                $this->resp->msg = "Today is ".$today." but this appointment's is ".$appointment_date." so that you can not update this treatment";
                $this->jsonecho();
            }



            /**Step 8 - update */
            try 
            {
                $Treatment->set("name", $name)
                    ->set("type", $type)
                    ->set("times", $times)
                    ->set("purpose", $purpose)
                    ->set("instruction", $instruction)
                    ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Treatment has been updated successfully !";
                $this->resp->data = array(
                    "id" => (int)$Treatment->get("id"),
                    "appointment_id" => (int)$Treatment->get("appointment_id"),
                    "name" => $Treatment->get("name"),
                    "type" => $Treatment->get("type"),
                    "times" => (int)$Treatment->get("times"),
                    "purpose" => $Treatment->get("purpose"),
                    "instruction" => $Treatment->get("instruction"),
                    "repeat_days" => $Treatment->get("repeat_days"),
                    "repeat_time" => $Treatment->get("repeat_time")
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
         * @since 22-10-2022
         * delete treatment but only appointment's status is PROCESSING
         */
        private function delete()
        {
           /**Step 1 - declare */
           $this->resp->result = 0;
           $Route = $this->getVariable("Route");
           $AuthUser = $this->getVariable("AuthUser");


           /**Step 2 - required fields*/
           if( !isset($Route->params->id) )
           {
               $this->resp->msg = "ID is required !";
               $this->jsonecho();
           }
           $Treatment = Controller::model("Treatment", $Route->params->id);
           if( !$Treatment->isAvailable() )
           {
               $this->resp->msg = "Treatment is not available";
               $this->jsonecho();
           }

           $appointment_id = $Treatment->get("appointment_id");


           /**Step 3 - check exist*/
           $Appointment = Controller::model("Appointment", $appointment_id);
           if( !$Appointment->isAvailable() )
           {
               $this->resp->msg = "Appointment is not available";
               $this->jsonecho();
           }
           /**Step 4 - Appointment status must be processing to continue */
           if( $Appointment->get("status") != "processing")
           {
               $this->resp->msg = "You just can do this action when appointment's status is PROCESSING ";
               $this->jsonecho();
           }
           /**Step 5 - If doctor's role is MEMBER => he can delete treatments belong to him */
           if( $AuthUser->get("role") == "member" && 
                $Appointment->get("doctor_id") != $AuthUser->get("id") )
           {
               $Doctor = Controller::model("Doctor",  $Appointment->get("doctor_id") );
               $this->resp->msg = "This treatment belongs to doctor ".$Doctor->get("name")." so that you can't do this action";
               $this->jsonecho();
           }

           
            /**Step 6 - is appointment's date today? 
             * If no, this appointment can not be changed ! 
             * 
             * Today is 23-10-2022 & appointment_date is 22-10-2022 
             * => this appointment can not changed
             * */
            $today = Date("d-m-Y");
            $appointment_date = $Appointment->get("date");
            
            $difference = abs(strtotime($today) - strtotime($appointment_date));
            $differenceYear = floor($difference / (365*60*60*24));
            $differenceMonth = floor(($difference - $differenceYear * 365*60*60*24) / (30*60*60*24));
            $differenceDay = floor(($difference - $differenceYear * 365*60*60*24 - $differenceMonth*30*60*60*24)/ (60*60*24));

            if( $differenceDay > 0 )
            {
                $this->resp->msg = "Today is ".$today." but this appointment's is ".$appointment_date." so that you"
                                   ." can not delete this treatment";
                $this->jsonecho();
            }


           /**Step 4 - update */
           try 
           {
               $Treatment->delete();

               $this->resp->result = 1;
               $this->resp->msg = "Treatment has been deleted successfully !";
           } 
           catch (\Exception $ex) 
           {
               $this->resp->msg = $ex->getMessage();
           }
           $this->jsonecho(); 
        }
    }
?>