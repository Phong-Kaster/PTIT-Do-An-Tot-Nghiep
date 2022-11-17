<?php
/**
 * SpecialitiesController
 */
class SpecialitiesController extends Controller
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
     * @since 10-10-2022
     * get all specialities
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
            $query = DB::table(TABLE_PREFIX.TABLE_SPECIALITIES)
                    ->leftJoin(TABLE_PREFIX.TABLE_DOCTORS, 
                            TABLE_PREFIX.TABLE_DOCTORS.".speciality_id","=", TABLE_PREFIX.TABLE_SPECIALITIES.".id")
                    ->groupBy(TABLE_PREFIX.TABLE_SPECIALITIES.".id")
                    ->select([
                        TABLE_PREFIX.TABLE_SPECIALITIES.".*",
                        DB::raw("COUNT(".TABLE_PREFIX.TABLE_DOCTORS.".id) as doctor_quantity") 
                    ]);

            /**Step 3.1 - search filter*/
            $search_query = trim( (string)$search );
            if($search_query){
                $query->where(function($q) use($search_query)
                {
                    $q->where(TABLE_PREFIX.TABLE_SPECIALITIES.".name", 'LIKE', $search_query.'%')
                    ->orWhere(TABLE_PREFIX.TABLE_SPECIALITIES.".description", 'LIKE', $search_query.'%');
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


                if(in_array($column_name, ["name", "description"])){
                    $query->orderBy(DB::raw(TABLE_PREFIX.TABLE_SPECIALITIES.".".$column_name. " * 1"), $sort);
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
                    "description" => $element->description,
                    "doctor_quantity" => (int)$element->doctor_quantity,
                    "image" => $element->image
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
        $required_fields = ["name", "description"];
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
        $query = DB::table(TABLE_PREFIX.TABLE_SPECIALITIES)
                ->where(TABLE_PREFIX.TABLE_SPECIALITIES.".name", "=", $name);

        $result = $query->get();
        if( count($result) > 0 )
        {
            $this->resp->msg = "This speciality exists ! Try another name";
            $this->jsonecho();
        }


        /**Step 4 - create*/
        $Speciality = Controller::model("Speciality");
        $Speciality->set("name", $name)
                    ->set("description", $description)
                    ->set("image", "default_avatar.jpg")
                    ->save();
        

        /**Step 5 */
        $this->resp->result = 1;
        $this->resp->msg = "Speciality is created successfully !";
        $this->resp->data = array(
            "id" => (int)$Speciality->get("id"),
            "name" => $Speciality->get("name"),
            "description" => $Speciality->get("description"),
            "image" => $Speciality->get("image")
        );
        $this->jsonecho();
    }
}