<?php
/**
 * RoomsController
 */
class RoomsController extends Controller
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
     * @since 24-10-2022
     * get all room
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
        $length         = Input::get("length") ? (int)Input::get("length") : 5;
        $start          = Input::get("start") ? (int)Input::get("start") : 0;
        $speciality_id  = Input::get("speciality_id");
        try
        {
            /**Step 3 - query */
            $query = DB::table(TABLE_PREFIX.TABLE_ROOMS)
                    ->leftJoin(TABLE_PREFIX.TABLE_DOCTORS, 
                                TABLE_PREFIX.TABLE_DOCTORS.".room_id","=", TABLE_PREFIX.TABLE_ROOMS.".id")
                    ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES, 
                                TABLE_PREFIX.TABLE_SPECIALITIES.".id","=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")
                    ->groupBy(TABLE_PREFIX.TABLE_ROOMS.".id")
                    ->select([
                        DB::raw(TABLE_PREFIX.TABLE_ROOMS.".*"),
                        DB::raw("COUNT(".TABLE_PREFIX.TABLE_DOCTORS.".id) as doctor_quantity") 
                    ]);

            /**Step 3.1 - search filter*/
            $search_query = trim( (string)$search );
            if($search_query){
                $query->where(function($q) use($search_query)
                {
                    $q->where(TABLE_PREFIX.TABLE_ROOMS.".name", 'LIKE', $search_query.'%')
                    ->orWhere(TABLE_PREFIX.TABLE_ROOMS.".location", 'LIKE', $search_query.'%');
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


                if(in_array($column_name, ["name", "location"])){
                    $query->orderBy(DB::raw(TABLE_PREFIX.TABLE_ROOMS.".".$column_name. " * 1"), $sort);
                }else{
                    $query->orderBy(TABLE_PREFIX.TABLE_ROOMS.".".$column_name, $sort);
                }
            }
            else 
            {
                $query->orderBy("id", "desc");
            } 

            if($speciality_id)
            {
                $query->where(TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", $speciality_id);
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
                    "location" => $element->location,
                    "doctor_quantity" => (int)$element->doctor_quantity
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
     * @since 24-10-2022
     * create new rooms & there is no duplication with the same name & location
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
        $required_fields = ["name", "location"];
        foreach( $required_fields as $field)
        {
            if( !Input::post($field) )
            {
                $this->resp->msg = "Missing field: ".$field;
                $this->jsonecho();
            }
        }
        $name = Input::post("name");
        $location = Input::post("location");


        /**Step 3 - check duplicate */
        $query = DB::table(TABLE_PREFIX.TABLE_ROOMS)
                ->where(TABLE_PREFIX.TABLE_ROOMS.".name", "=", $name)
                ->where(TABLE_PREFIX.TABLE_ROOMS.".location", "=", $location);

        $result = $query->get();
        if( count($result) > 0 )
        {
            $this->resp->msg = "This room ".$name." at ".$location." exists ! Try another name";
            $this->jsonecho();
        }


        /**Step 4 - create*/
        $Room = Controller::model("Room");
        $Room->set("name", $name)
                ->set("location", $location)
                ->save();
        

        /**Step 5 */
        $this->resp->result = 1;
        $this->resp->msg = "Room is created successfully !";
        $this->resp->data = array(
            "id" => (int)$Room->get("id"),
            "name" => $Room->get("name"),
            "location" => $Room->get("location")
        );
        $this->jsonecho();
    }
}