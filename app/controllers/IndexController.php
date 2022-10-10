<?php
/**
 * Index Controller
 */
class IndexController extends Controller
{
    /**
     * Process
     */
    public function process()
    {   
        // Get packages
        $Packages = Controller::model("Packages");
        $Packages->where("is_public", "=", 1)
                 ->orderBy("id","ASC")
                 ->fetchData();

        // Get active modules to be displayed in pricing table
        $Plugins = \Controller::model("Plugins");
        $Plugins->where("is_active", 1)
                ->whereIn("idname", [
                    "auto-follow", "auto-unfollow", "auto-like",
                    "auto-comment", "welcomedm", "auto-repost"
                ])->fetchData();

        // Set variables
        $this->setVariable("TrialPackage", Controller::model("GeneralData", "free-trial"))
             ->setVariable("Settings", Controller::model("GeneralData", "settings"))
             ->setVariable("Plugins", $Plugins)
             ->setVariable("Packages", $Packages);

        $this->view("index", "site");
    }
}