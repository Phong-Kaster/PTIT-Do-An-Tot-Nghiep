<?php
/**
 * Users Controller
 */
class UsersController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $AuthUser = $this->getVariable("AuthUser");

        // Auth
        if (!$AuthUser){
            header("Location: ".APPURL."/login");
            exit;
        } else if ($AuthUser->isExpired()) {
            header("Location: ".APPURL."/expired");
            exit;
        } else if (!$AuthUser->isAdmin()) {
            header("Location: ".APPURL."/post");
            exit;
        }

        // Get Users
        $Users = Controller::model("Users");
            $Users->search(Input::get("q"))
                  ->setPageSize(20)
                  ->setPage(Input::get("page"))
                  ->orderBy("id","DESC")
                  ->fetchData();

        $this->setVariable("Users", $Users);

        if (Input::post("action") == "remove") {
            $this->remove();
        }
        $this->view("users");
    }


    /**
     * Remove User
     * @return void 
     */
    private function remove()
    {
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");

        if (!Input::post("id")) {
            $this->resp->msg = __("ID is requred!");
            $this->jsonecho();
        }

        $User = Controller::model("User", Input::post("id"));

        if (!$User->isAvailable()) {
            $this->resp->msg = __("User doesn't exist!");
            $this->jsonecho();
        }

        if (!$AuthUser->canEdit($User)) {
            $this->resp->msg = __("You don't have a privilage to modify this user's data!");
            $this->jsonecho();   
        }

        if ($AuthUser->get("id") == $User->get("id")) {
            $this->resp->msg = __("You can not delete your own account!");
            $this->jsonecho();
        }

        $User->delete();

        $this->resp->result = 1;
        $this->jsonecho();
    }
}