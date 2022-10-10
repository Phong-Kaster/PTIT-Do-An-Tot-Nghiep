<?php
/**
 * User Controller
 */
class UserController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $Route = $this->getVariable("Route");
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


        $User = Controller::model("User");
        if (isset($Route->params->id)) {
            $User->select($Route->params->id);

            if (!$User->isAvailable()) {
                header("Location: ".APPURL."/users");
                exit;
            }
        }

        // Check permissions
        if (!$AuthUser->canEdit($User)) {
            header("Location: ".APPURL."/users");
            exit;
        }

        // Get packages
        $Packages = Controller::model("Packages");
            $Packages->fetchData();


        $this->setVariable("User", $User)
             ->setVariable("TimeZones", getTimezones())
             ->setVariable("Packages", $Packages);

        if (Input::post("action") == "save") {
            $this->save();
        }
        
        $this->view("user");
    }


    /**
     * Save (new|edit) user
     * @return void 
     */
    private function save()
    {
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");
        $User = $this->getVariable("User");

        // Check if this is new or not
        $is_new = !$User->isAvailable();

        // Check required fields
        $required_fields = ["email", "firstname", "lastname"];
        if ($is_new) {
            $required_fields[] = "password";
            $required_fields[] = "password-confirm";
        }

        foreach ($required_fields as $field) {
            if (!Input::post($field)) {
                $this->resp->msg = __("Missing some of required data.");
                $this->jsonecho();
            }
        }

        // Check email
        if (!filter_var(Input::post("email"), FILTER_VALIDATE_EMAIL)) {
            $this->resp->msg = __("Email is not valid.");
            $this->jsonecho();
        }

        $u = Controller::model("User", Input::post("email"));
        if ($u->isAvailable() && $u->get("id") != $User->get("id")) {
            $this->resp->msg = __("Email is not available.");
            $this->jsonecho();
        }
        
        // Check pass.
        if (mb_strlen(Input::post("password")) > 0) {
            if (mb_strlen(Input::post("password")) < 6) {
                $this->resp->msg = __("Password must be at least 6 character length!");
                $this->jsonecho();
            } 

            if (Input::post("password-confirm") != Input::post("password")) {
                $this->resp->msg = __("Password confirmation didn't match!");
                $this->jsonecho();
            }
        }


        // Start setting data
        $User->set("firstname", Input::post("firstname"))
             ->set("lastname", Input::post("lastname"))
             ->set("email", Input::post("email"));


        // Preferences
        $preferences = [];
        $preferences["timezone"] = Input::post("timezone");
        if (!in_array($preferences["timezone"], DateTimeZone::listIdentifiers())) {
            $preferences["timezone"] = "UTC";
        } 

        $valid_date_formats = [
            "Y-m-d", "d-m-Y", "d/m/Y", "m/d/Y",
            "d F, Y", "F d, Y", "d M, Y", "M d, Y"
        ];
        $preferences["dateformat"] = Input::post("date-format");
        if (!in_array($preferences["dateformat"], $valid_date_formats)) {
            $preferences["dateformat"] = $valid_date_formats[0];
        }

        $preferences["timeformat"] = Input::post("time-format") == "24"
                                ? "24" : "12";

        $language = Config::get("default_applang");
        foreach (Config::get("applangs") as $al) {
            if ($al["code"] == Input::post("language")) {
                $language = Input::post("language");
                break;
            }
        }
        $preferences["language"] = $language;

        $User->set("preferences", json_encode($preferences));


        if (mb_strlen(Input::post("password")) > 0) {
            $passhash = password_hash(Input::post("password"), PASSWORD_DEFAULT);
            $User->set("password", $passhash);
        }


        if ($AuthUser->get("id") != $User->get("id")) {
            // Don't allow to change self account type, status or expire date
            // This could cause to lost of access to the app with
            // default (and only) admin account

            // Account type
            $valid_account_types = ["member", "admin", "developer"];
            $account_type = Input::post("account-type");
            if (!in_array($account_type, $valid_account_types)) {
                $account_type = "member"; 
            }


            // Expire date
            $expire_date = new DateTime(Input::post("expire-date"), 
                              new DateTimeZone($AuthUser->get("preferences.timezone")));
            $expire_date->setTimezone(new DateTimeZone(date_default_timezone_get()));

            $User->set("account_type", $account_type)
                 ->set("is_active", Input::post("status") == 1 ? 1 : 0)
                 ->set("expire_date", $expire_date->format("Y-m-d H:i:s"));
        }


        // Package
        $Package = Controller::model("Package", Input::post("package"));
        if ($Package->isAvailable()) {
            $User->set("package_id", $Package->get("id"))
                 ->set("package_subscription", 
                       Input::post("package-subscription") ? 1 : 0);
        } else if (Input::post("package") == 0) {
            $User->set("package_id", "0")
                 ->set("package_subscription", 
                       Input::post("package-subscription") ? 1 : 0);
        } else {
            $User->set("package_id", "-1")
                 ->set("package_subscription", 0);
        }

        // Settings
        if ($User->get("package_subscription")) {
            if ($User->get("package_id") == 0) {
                $TrialPackage = Controller::model("GeneralData", "free-trial");
                $settings = json_decode($TrialPackage->get("data"), true);
                unset($settings["size"]);
                $settings = json_encode($settings);
            } else {
                $settings = $Package->get("settings");
            }
        } else {
            $settings = [];

            // Storage
            $storage_total = (double)Input::post("storage-total");
                if ($storage_total < 0 && $storage_total != "-1") {
                    $storage_total = 0;
                }
                if ($storage_total != "-1") {
                    $storage_total = number_format($storage_total, 2, ".", "");
                }
            $storage_file = (double)Input::post("storage-file");
                if ($storage_file < 0 && $storage_file != "-1") {
                    $storage_file = 0;
                }
                if ($storage_file != "-1") {
                    $storage_file = number_format($storage_file, 2, ".", "");
                }
            $settings["storage"] = [
                "total" => $storage_total,
                "file" => $storage_file
            ];
            
            // Accounts
            $accounts = (int)Input::post("accounts");
            if ($accounts < 0 && $accounts != "-1") {
                $accounts = 0;
            }
            $settings["max_accounts"] = $accounts;

            // File pickers
            $settings["file_pickers"] = [
                "dropbox" => (boolean)Input::post("dropbox"),
                "onedrive" => (boolean)Input::post("onedrive"),
                "google_drive" => (boolean)Input::post("google-drive")
            ];

            // Post Types
            $settings["post_types"] = [
                "timeline_photo" => (boolean)Input::post("timeline-photo"),
                "timeline_video" => (boolean)Input::post("timeline-video"),

                "story_photo" => (boolean)Input::post("story-photo"),
                "story_video" => (boolean)Input::post("story-video"),

                "album_photo" => (boolean)Input::post("album-photo"),
                "album_video" => (boolean)Input::post("album-video"),
            ];

            // Other settings
            $settings["spintax"] = (boolean)Input::post("spintax");
            $settings["modules"] = Input::post("modules");


            $settings = json_encode($settings);
        }

        $User->set("settings", $settings);

        $User->save();

        // update cookies
        if ($User->get("id") == $AuthUser->get("id")) {
            setcookie("nplh", $AuthUser->get("id").".".md5($User->get("password")), 0, "/");
        }


        $this->resp->result = 1;
        if ($is_new) {
            $this->resp->msg = __("User added successfully! Please refresh the page.");
            $this->resp->reset = true;
        } else {
            $this->resp->msg = __("Changes saved!");
        }
        $this->jsonecho();
    }
}