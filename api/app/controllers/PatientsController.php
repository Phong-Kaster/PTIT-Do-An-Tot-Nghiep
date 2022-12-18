<?php 
    /**
     * @author Phong-Kaster
     * @since 14-10-2022
     * Patients Controller is used by ADMIN to manage their information
     */
    class PatientsController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");

            if (!$AuthUser)
            {
                header("Location: ".APPURL."/login");
                exit;
            }
            // if( $AuthUser->get("role") != "admin" )
            // {
            //     $this->resp->result = 0;
            //     $this->resp->msg = "You are not admin & you can't do this action !";
            //     $this->jsonecho();
            // }
            
            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->getAll();
            }
            else if( $request_method === 'POST')
            {
                /* 
                * we can't create patient information because they create account 
                * by PHONE NUMBER or GOOGLE.
                * $this->save();
                */
                $this->resp->result = 0;
                $this->resp->msg = "We can't create patient information because they create account by PHONE NUMBER or GOOGLE.";
                $this->jsonecho();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 14-10-2022
         * get all patients' information
         */
        private function getAll()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $data = [];
            

            $valid_roles = ["admin", "supporter"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You don't have permission to do this action. Only "
                .implode(', ', $valid_roles)." can do this action !";
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
                $query = DB::table(TABLE_PREFIX.TABLE_PATIENTS)
                        ->select("*");

                /**Step 3.1 - search filter*/
                $search_query = trim( (string)$search );
                if($search_query){
                    $query->where(function($q) use($search_query)
                    {
                        $q->where(TABLE_PREFIX.TABLE_PATIENTS.".email", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_PATIENTS.".phone", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_PATIENTS.".name", 'LIKE', $search_query.'%')
                        ->orWhere(TABLE_PREFIX.TABLE_PATIENTS.".address", 'LIKE', $search_query.'%');
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


                    if(in_array($column_name, ["email", "name", "phone"])){
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
                        "gender" => (int)$element->gender,
                        "birthday" => $element->birthday,
                        "address" => $element->address,
                        "avatar" => $element->avatar,
                        "create_at" => $element->create_at,
                        "update_at" => $element->update_at
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
    }
?>