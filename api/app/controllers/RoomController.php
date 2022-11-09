<?php 
    class RoomController extends Controller
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
         * @since 24-10-2022
         * get room by id
         */
        private function getById()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }



            /**Step 2 - check ID*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }


            /**Step 3 - get by id */
            try
            {
                $Room = Controller::model("Room", $Route->params->id);
                if( !$Room->isAvailable() )
                {
                    $this->resp->msg = "Room is not available";
                    $this->jsonecho();
                }



                $this->resp->result = 1;
                $this->resp->msg = "Action successfully !";
                $this->resp->data = array(
                    "id" => (int)$Room->get("id"),
                    "name" => $Room->get("name"),
                    "location" => $Room->get("location")
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
         * @since 24-10-2022
         * update a Room
         */
        private function update()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }



            /**Step 2 - required fields*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }
            
            $required_fields = ["name", "location"];
            foreach( $required_fields as $field)
            {
                if( !Input::put($field) )
                {
                    $this->resp->msg = "Missing field: ".$field;
                    $this->jsonecho();
                }
            }

            $name = Input::put("name");
            $location = Input::put("location");
            $id = $Route->params->id;

            /**Step 3 - check duplicate */
            $query = DB::table(TABLE_PREFIX.TABLE_ROOMS)
            ->where(TABLE_PREFIX.TABLE_ROOMS.".name", "=", $name)
            ->where(TABLE_PREFIX.TABLE_ROOMS.".location", "=", $location)
            ->where(TABLE_PREFIX.TABLE_ROOMS.".id", "!=" , $id);
            $result = $query->get();
            if( count($result) > 0 )
            {
                $this->resp->msg = "This room ".$name." at ".$location." exists ! Try another name";
                $this->jsonecho();
            }       


            /**Step 4 - check exist*/
            $Room = Controller::model("Room", $Route->params->id);
            if( !$Room->isAvailable() )
            {
                $this->resp->msg = "Room is not available";
                $this->jsonecho();
            }




            /**Step 4 - update */
            try 
            {
                $Room->set("name", $name)
                    ->set("location", $location)
                    ->save();

                $this->resp->result = 1;
                $this->resp->msg = "Updated successfully";
                $this->resp->data = array(
                    "id" => (int)$Room->get("id"),
                    "name" => $Room->get("name"),
                    "location" => $Room->get("location")
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
         * @since 10-10-2022
         * delete a Room
         */
        private function delete()
        {
            /**Step 1 */
            $this->resp->result = 0;
            $AuthUser = $this->getVariable("AuthUser");
            $Route = $this->getVariable("Route");

            if( $AuthUser->get("role") != "admin" )
            {
                $this->resp->msg = "You are not admin & you can't do this action !";
                $this->jsonecho();
            }


            /**Step 2 - required fields*/
            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "ID is required !";
                $this->jsonecho();
            }

            if( $Route->params->id == 1 )
            {
                $this->resp->msg = "This is the default Room & it can't be deleted !";
                $this->jsonecho();
            }


            /**Step 3 - check exist*/
            $Room = Controller::model("Room", $Route->params->id);
            if( !$Room->isAvailable() )
            {
                $this->resp->msg = "Room is not available";
                $this->jsonecho();
            }



            /**Step 4 - how many doctor are there in this Room */
            $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)
                    ->where(TABLE_PREFIX.TABLE_DOCTORS.".room_id", "=", $Route->params->id);
            $result = $query->get();

            if( count($result) > 0)
            {
                $this->resp->msg = "This Room can't be deleted because there are ".count($result)." doctors in it";
                $this->jsonecho();
            }

            try 
            {
                $Room->delete();
                
                $this->resp->result = 1;
                $this->resp->msg = "Room is deleted successfully !";
            } 
            catch (\Exception $ex) 
            {
                $this->resp->msg = $ex->getMessage();
            }
            $this->jsonecho();
        }
    } 
?>