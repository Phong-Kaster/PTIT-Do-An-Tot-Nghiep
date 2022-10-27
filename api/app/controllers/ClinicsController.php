<?php 
    class ClinicsController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");

            if (!$AuthUser || $AuthUser->get("role") != "admin")
            {
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
         * @since 12-10-2022
         * get all clinics where doctors are working
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
                $query = DB::table(TABLE_PREFIX.TABLE_CLINICS)
                        ->select("*");

                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {
                        $q->where(TABLE_PREFIX.TABLE_CLINICS.".name", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_CLINICS.".address", 'LIKE', $search_query.'%');
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


                    if(in_array($column_name, ["name", "address"])){
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
                        "name" => $element->name,
                        "address" => $element->address
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
         * create new clinics & there is no duplication with the same name
         */
        private function save()
        {
            /**Step 1 - declare*/
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data = [];
            

            if( !$AuthUser || $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }


            /**Step 2 - get required */
            $required_fields = ["name", "address"];
            foreach( $required_fields as $field)
            {
                if( !Input::post($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            /**Step 3 - validation */
            $name = Input::post("name");
            $address = Input::post("address");

            $name_validation = isVietnameseHospital($name);
            if( $name_validation == 0 ){
                $this->resp->msg = "Name only has letters and space";
                $this->jsonecho();
            }

            $address_validation = isAddress($address);
            if( $address_validation == 0)
            {
                $this->resp->msg = "Address only accepts letters, numbers, space and comma !";
                $this->jsonecho();
            }

            
            /**Step 4 - check duplicate */
            $query = DB::table(TABLE_PREFIX.TABLE_CLINICS)
                    ->where(TABLE_PREFIX.TABLE_CLINICS.".name", "=", $name)
                    ->orWhere(TABLE_PREFIX.TABLE_CLINICS.".address", "=", $address);

            $result = $query->get();
            if( count($result) > 0 )
            {
                $this->resp->msg = "Name or address exists. Try another name or another address !";
                $this->jsonecho();
            }


            /**Step 5 - create*/
            $Clinic = Controller::model("Clinic");
            $Clinic->set("name", $name)
                        ->set("address", $address)
                        ->save();
            

            /**Step 6 */
            $this->resp->result = 1;
            $this->resp->msg = "Clinic is created successfully !";
            $this->jsonecho();
        }
    }
?>