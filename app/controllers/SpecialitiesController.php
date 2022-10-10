<?php
/**
 * Profile Controller
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
    }

    private function getAll()
    {
        $this->resp->result = 0;
        $data = [];
        
        // if( !$AuthUser )
        // {
        //     $this->resp->msg = "There is no authenticated user !";
        //     $this->jsonecho();
        // }

        $order          = Input::get("order");
        $search         = Input::get("search");
        $length         = Input::get("length") ? (int)Input::get("length") : 10;
        $start          = Input::get("start") ? (int)Input::get("start") : 0;
        // $status         = Input::get("status");

        try
        {
            $query = DB::table(TABLE_PREFIX.TABLE_SPECIALITIES)
                    ->select("*");

            $result = $query->get();
            foreach($result as $element)
            {
                $data[] = array(
                    "id" => (int)$element->id,
                    "name" => $element->name,
                    "description" => $element->description
                );
            }

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