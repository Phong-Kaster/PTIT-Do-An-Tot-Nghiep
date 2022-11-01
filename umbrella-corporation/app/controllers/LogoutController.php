<?php
/**
 * Logout Controller
 */
class LogoutController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $this->logout();
    }


    /**
     * Logout
     * @return void 
     */
    private function logout()
    {
        $AuthUser = $this->getVariable("AuthUser");

        setcookie("accessToken",null,time()-86400*365, "/");
        setcookie("nplh", null, time()-86400*365, "/");
        setcookie("nplrmm", null, time()-86400*365, "/");

        // Fire user.signout event
        Event::trigger("user.signout", $AuthUser);

        header("Location: ".APPURL);
        exit;
    }
}