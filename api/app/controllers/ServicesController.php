<?php
    /**
     * @author Phong-Kaster
     * @since 17-10-2022
     * Supported can use getAll()
     * Admin can use all functions
     */
    class ServicesController extends Controller
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
     * @since 17-10-2022
     * get all services
     */
    private function getAll()
    {
        /**Step 1 */
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");
        $data = [];
        

        // $valid_roles = ["admin", "supporter"];
        // $role_validation = in_array($AuthUser->get("role"), $valid_roles);
        // if( !$role_validation )
        // {
        //     $this->resp->msg = "You are not admin or supporter & you can't do this action !";
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
            $query = DB::table(TABLE_PREFIX.TABLE_SERVICES)
                    ->select("*");

            /**Step 3.1 - search filter*/
            $search_query = trim( (string)$search );
            if($search_query){
                $query->where(function($q) use($search_query)
                {
                    $q->where(TABLE_PREFIX.TABLE_SERVICES.".name", 'LIKE', $search_query.'%');
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


                if(in_array($column_name, ["name"])){
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



            /**Step 4 */
            $result = $query->get();
            foreach($result as $element)
            {
                $data[] = array(
                    "id" => (int)$element->id,
                    "name" => $element->name,
                    "image" => $element->image,
                    "description" => $element->description
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
     * @since 10-10-2022
     * only admin can use
     * create new specialities & there is no duplication with the same name
     */
    private function save()
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


        /**Step 2 - get required */
        $required_fields = ["name"];
        foreach( $required_fields as $field)
        {
            if( !Input::post($field) )
            {
                $this->resp->msg = "Missing field: ".$field;
                $this->jsonecho();
            }
        }
        $name = Input::post("name");
        $description = Input::post("description");


        /**Step 3 - check duplicate */
        $query = DB::table(TABLE_PREFIX.TABLE_SERVICES)
                ->where(TABLE_PREFIX.TABLE_SERVICES.".name", "=", $name);

        $result = $query->get();
        if( count($result) > 0 )
        {
            $this->resp->msg = "This Service exists ! Try another name";
            $this->jsonecho();
        }


        /**Step 4 - create*/
        $Service = Controller::model("Service");
        $Service->set("name", $name)
                ->set("image", 'default_avatar.jpg')
                ->set("description", $description)
                    ->save();
        

        /**Step 5 */
        $this->resp->result = 1;
        $this->resp->msg = "Service is created successfully !";
        $this->resp->data = array(
            "id" => (int)$Service->get("id"),
            "name" => $Service->get("name"),
            "image" => $Service->get("image"),
            "description" => $Service->get("description")
        );
        $this->jsonecho();
    }
}
?>