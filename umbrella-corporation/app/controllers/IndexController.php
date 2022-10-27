<?php
/**
 * @author Phong-Kaster
 * @since 26-10-2022
 * Index Controller
 */
class IndexController extends Controller
{
    /**
     * Process
     */
    public function process()
    {   
        $AuthUser = $this->getVariable("AuthUser");
        if (!$AuthUser){
            header("Location: ".APPURL."/login");
            exit;
        }

        $this->view("dashboard");
    }
}