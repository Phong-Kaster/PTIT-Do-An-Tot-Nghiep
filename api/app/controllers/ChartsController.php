<?php
    /**
     * @author Phong-Kaster
     * @since 29-10-2022
     * return quantity of appointment monthly
     */
    class ChartsController extends Controller
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
            
            $valid_roles = ["admin", "supporter", "member"];
            $role_validation = in_array($AuthUser->get("role"), $valid_roles);
            if( !$AuthUser->get("role") || !$role_validation )
            {
                $this->resp->result = 0;
                $this->resp->msg = "You don not have permission to do this action !";
                $this->jsonecho();
            }

            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $request = Input::get("request");
                switch ($request) {
                    case "appointmentsinlast7days":
                        $this->appointmentsInLast7Days();
                        break;
                    case "appointmentandbookinginlast7days":
                        $this->appointmentsAndBookingInLast7days();
                        break;
                    default:
                        $this->resp->result = 0;
                        $this->resp->msg = "Your request is invalid!";
                        $this->jsonecho();
                }
            }
            else 
            {
                $this->resp->result = 0;
                $this->resp->msg = "Your request is invalid!";
                $this->jsonecho();
            }
        }

        /**
         * @author Phong-Kaster
         * @since 29-10-2022
         * get quantity of appointment in last 7 days
         */
        private function appointmentsInLast7Days()
        {
            /**Step 1 - declare */
            $this->resp->result = 0;
            date_default_timezone_set('Asia/Ho_Chi_Minh');// set timezone

            $length   = Input::get("length") ? (int)Input::get("length") : 10;
            $start    = Input::get("start") ? (int)Input::get("start") : 0;
            $data = [];

            /**Step 2 - get first day | finish day of a week */
            $date = new \Moment\Moment("now", date_default_timezone_get());
            $from = $date->cloning()->subtractDays(6);
            $to = $date->cloning();


            try 
            {
                /**Step 3 - get quantity of appointment */
                $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                        ->whereBetween(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", 
                                        $from->format("Y-m-d"), $to->format("Y-m-d"))
                        ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "desc")
                        ->groupBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".date")
                        ->select([
                            DB::raw("COUNT(*) as quantity"),
                            TABLE_PREFIX.TABLE_APPOINTMENTS.".date"
                        ]);

                $result = $query->get();

                for($x = 0; $x<7;$x++)
                {
                    $data[] = array(
                        "date" => $from->format("Y-m-d"),
                        "appointment" => 0
                    );
                    $from->addDays(1);
                }

                
                for($x = 0; $x < count($data); $x++)
                {
                    $date = $data[$x]["date"];
                    foreach($result as $r)
                    {
                        if($r->date == $date)
                        {
                            $data[$x]["appointment"] = (int)$r->quantity;
                        }
                    }
                }

                $this->resp->result = 1;
                $this->resp->quantity = count($data);
                $this->resp->data = $data;
            }
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }

        /**
         * @author Phong-Kaster
         * @since 29-10-2022
         * get quantity of appointments and booking in last 7 days
         * Note: booking is a type of appointments
         */
        private function appointmentsAndBookingInLast7days()
        {
             /**Step 1 - declare */
             $this->resp->result = 1;
             date_default_timezone_set('Asia/Ho_Chi_Minh');// set timezone
 
             $length   = Input::get("length") ? (int)Input::get("length") : 10;
             $start    = Input::get("start") ? (int)Input::get("start") : 0;
             $data = [];
 
             /**Step 2 - get first day | finish day of a week */
             $date = new \Moment\Moment("now", date_default_timezone_get());
            $from = $date->cloning()->subtractDays(6);
            $to = $date->cloning();
 
 
             try 
             {
                 /**Step 3 - get quantity of appointment */
                 $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                         ->whereBetween(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", 
                                        $from->format("Y-m-d"), $to->format("Y-m-d"))
                         ->orderBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "desc")
                         ->groupBy(TABLE_PREFIX.TABLE_APPOINTMENTS.".date")
                         ->select([
                             DB::raw("COUNT(*) as quantity"),
                             TABLE_PREFIX.TABLE_APPOINTMENTS.".date"
                         ]);
 
                 $result = $query->get();
                 for($x = 0; $x<7;$x++)
                 {
                    $date = $from->format("Y-m-d");
                     $data[] = array(
                         "date" => $date ,
                         "appointment" => 0,
                         "booking" => 0
                     );
                     $from->addDays(1);
                 }
 
                 
                 for($x = 0; $x < count($data); $x++)
                 {
                     $date = $data[$x]["date"];
                     foreach($result as $r)
                     {
                         if($r->date == $date)
                         {
                             $data[$x]["appointment"] = (int)$r->quantity;
                             $data[$x]["booking"] = $this->quantityBookingInDate($date);
                         }
                     }
                 }
 
                 $this->resp->result = 1;
                 $this->resp->quantity = count($data);
                 $this->resp->data = $data;
            }
            catch(Exception $ex)
            {

            }
            $this->jsonecho();
        }

        private function quantityBookingInDate($date)
        {
            $query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $date )
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".appointment_time", "!=", "" )
                    ->select("*");

            $result = $query->get();
            $quantity = count($result);
            return (int)$quantity;
        }
    }
?>