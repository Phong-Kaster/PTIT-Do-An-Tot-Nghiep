<?php
    /**
     * @author Phong-Kaster
     * @since 18-12-2022
     * Drugs Controller
     */
    class DrugsController extends Controller
    {
        /**
         * Process
         */
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if (!$AuthUser){
                // Auth
                header("Location: ".APPURL."/login");
                exit;
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
         * @since 20-10-2022
         * get all bookings
         * all DOCTOR's role can use.
         */
        private function getAll()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data = [];

            /**Step 2 - verify user's role */
            // $valid_roles = ["admin", "supporter", "member"];
            // $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            // if( !$role_validation )
            // {
            //     $this->resp->result = 0;
            //     $this->resp->msg = "You don't have permission to do this action. Only "
            //     .implode(', ', $valid_roles)." can do this action !";
            //     $this->jsonecho();
            // }
            

            /**Step 2 - get filters */
            $order          = Input::get("order");
            $search         = Input::get("search");
            $length         = Input::get("length") ? (int)Input::get("length") : 10;
            $start          = Input::get("start") ? (int)Input::get("start") : 0;

            try
            {
                /**Step 3 - query */
                $query = DB::table(TABLE_PREFIX.TABLE_DRUGS)
                        ->select("*");
    
                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {       
                        $q->where(TABLE_PREFIX.TABLE_DRUGS.".name", 'LIKE', $search_query.'%');
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
    
    
                    if(in_array($column_name, ["booking_name", "name", "appointment_time", "status"])){
                        $query->orderBy(DB::raw($column_name. " * 1"), $sort);
                    }else{
                        $query->orderBy($column_name, $sort);
                    }
                }
                else 
                {
                    $query->orderBy("id", "desc");
                } 

        
                $res = $query->get();
                $quantity = count($res);

                /**Step 3.3 - length filter * start filter*/
                $query->limit($length)
                    ->offset($start);

                $result = $query->get();


                foreach($result as $element)
                {
                    $data[] = array(
                        "id" => (int)$element->id,
                        "name" => $element->name,
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



        /**
         * @author Phong-Kaster
         * @since 20-10-2022
         * create new booking for patient
         * default status is VERIFIED
         * 
         */
        private function save()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data = [];

            /**Step 2 - verify user's role */
            $valid_roles = ["admin"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You don't have permission to do this action. Only "
                .implode(', ', $valid_roles)." can do this action !";
                $this->jsonecho();
            }

            /**Step 2 - get required data */
            if( !Input::post("name") )
            {
                $this->resp->msg = "Missing field: name";
                $this->jsonecho();
            }
            $name = Input::post("name");

            $query = DB::table(TABLE_PREFIX.TABLE_DRUGS)
                ->where(TABLE_PREFIX.TABLE_DRUGS.".name", "=", $name);
            
            $result = $query->get();

            if( count($result) > 0 )
            {
                $this->resp->msg = "This drug has been existed !";
                $this->jsonecho();
            }

            /**Step 4 - validation */
            try 
            {
                $Drug = Controller::model("Drug");
                $Drug->set("name", $name)
                    ->save();
                
                $this->resp->result = 1;
                $this->resp->msg = "Congratulation, drug has been created successfully !";
                $this->resp->data = array(
                    "id" => (int)$Drug->get("id"),
                    "name" => $Drug->get("name"),
                );
            } 
            catch (\Exception $ex) {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    }
?>