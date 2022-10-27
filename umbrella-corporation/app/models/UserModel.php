<?php 
	/**
	 * User Model
	 *
	 * @version 1.0
	 * @author Onelab <hello@onelab.co> 
	 * 
	 */
	
	class UserModel extends DataEntry
	{	
		/**
		 * Extend parents constructor and select entry
		 * @param mixed $uniqid Value of the unique identifier
		 */
	    public function __construct($uniqid=0)
	    {
	        parent::__construct();
	        $this->select($uniqid);
	    }



	    /**
	     * Select entry with uniqid
	     * @param  int|string $uniqid Value of the any unique field
	     * @return self       
	     */
	    public function select($uniqid)
	    {
	    	if (is_int($uniqid) || ctype_digit($uniqid)) {
	    		$col = $uniqid > 0 ? "id" : null;
	    	} else if (filter_var($uniqid, FILTER_VALIDATE_EMAIL)) {
	    		$col = "email";
	    	} else {
	    		$col = "username";
	    	}

	    	if ($col) {
		    	$query = DB::table(TABLE_PREFIX.TABLE_USERS)
			    	      ->where($col, "=", $uniqid)
			    	      ->limit(1)
			    	      ->select("*");
		    	if ($query->count() == 1) {
		    		$resp = $query->get();
		    		$r = $resp[0];

		    		foreach ($r as $field => $value)
		    			$this->set($field, $value);

		    		$this->is_available = true;
		    	} else {
		    		$this->data = array();
		    		$this->is_available = false;
		    	}
	    	}

	    	return $this;
	    }


	    /**
	     * Extend default values
	     * @return self
	     */
	    public function extendDefaults()
	    {
	    	$defaults = array(
	    		"account_type" => "member",
	    		"email" => uniqid()."@thepostcode.co",
	    		"username" => "user_".uniqid(),
	    		"password" => uniqid(),
	    		"firstname" => "",
	    		"lastname" => "",
	    		"package_id" => "0",
	    		"package_subscription" => "0",
	    		"settings" => "{}",
	    		"preferences" => "{}",
	    		"is_active" => "0",
	    		"expire_date" => date("Y-m-d H:i:s"),
	    		"date" => date("Y-m-d H:i:s"),
	    		"data" => '{}',
	    	);


	    	foreach ($defaults as $field => $value) {
	    		if (is_null($this->get($field)))
	    			$this->set($field, $value);
	    	}
	    }


	    /**
	     * Insert Data as new entry
	     */
	    public function insert()
	    {
	    	if ($this->isAvailable())
	    		return false;

	    	$this->extendDefaults();

	    	$id = DB::table(TABLE_PREFIX.TABLE_USERS)
		    	->insert(array(
		    		"id" => null,
		    		"account_type" => $this->get("account_type"),
		    		"email" => $this->get("email"),
		    		"username" => $this->get("username"),
		    		"password" => $this->get("password"),
		    		"firstname" => $this->get("firstname"),
		    		"lastname" => $this->get("lastname"),
		    		"package_id" => $this->get("package_id"),
		    		"package_subscription" => $this->get("package_subscription"),
		    		"settings" => $this->get("settings"),
		    		"preferences" => $this->get("preferences"),
		    		"is_active" => $this->get("is_active"),
		    		"expire_date" => $this->get("expire_date"),
		    		"date" => $this->get("date"),
		    		"data" => $this->get("data"),
		    	));

	    	$this->set("id", $id);
	    	$this->markAsAvailable();
	    	return $this->get("id");
	    }


	    /**
	     * Update selected entry with Data
	     */
	    public function update()
	    {
	    	if (!$this->isAvailable())
	    		return false;

	    	$this->extendDefaults();

	    	$id = DB::table(TABLE_PREFIX.TABLE_USERS)
	    		->where("id", "=", $this->get("id"))
		    	->update(array(
		    		"account_type" => $this->get("account_type"),
		    		"email" => $this->get("email"),
		    		"username" => $this->get("username"),
		    		"password" => $this->get("password"),
		    		"firstname" => $this->get("firstname"),
		    		"lastname" => $this->get("lastname"),
		    		"package_id" => $this->get("package_id"),
		    		"package_subscription" => $this->get("package_subscription"),
		    		"settings" => $this->get("settings"),
		    		"preferences" => $this->get("preferences"),
		    		"is_active" => $this->get("is_active"),
		    		"expire_date" => $this->get("expire_date"),
		    		"date" => $this->get("date"),
		    		"data" => $this->get("data"),
		    	));

	    	return $this;
	    }


	    /**
		 * Remove selected entry from database
		 */
	    public function delete()
	    {
	    	if(!$this->isAvailable())
	    		return false;

	    	DB::table(TABLE_PREFIX.TABLE_USERS)->where("id", "=", $this->get("id"))->delete();
	    	$this->is_available = false;
	    	return true;
	    }


	    /**
	     * Check if account has administrative privilages
	     * @return boolean 
	     */
	    public function isAdmin()
	    {
	    	if ($this->isAvailable() && in_array($this->get("account_type"), array("developer", "admin"))) {
	    		return true;
	    	}

	    	return false;
	    }


	    /**
	     * Checks if this user can edit another user's data
	     * 
	     * @param  UserModel $User Another user
	     * @return boolean          
	     */
	    public function canEdit(UserModel $User)
	    {	
	    	if ($this->isAvailable()) {
	    		if ($this->get("account_type") == "developer" || $this->get("id") == $User->get("id")) {
    				return true;
    			}	

    			if (
    				$this->get("account_type") == "admin" && 
    				(
	    				in_array($User->get("account_type"), array("member", "admin")) ||
	    				!$User->isAvailable() // New User
    				)
    			) {
    				return true;    				
    			}
	    	}

	    	return false;
	    }


	    /**
	     * Check if user is expired
	     * @return boolean true on expired
	     */
	    public function isExpired()
	    {
	    	if ($this->isAvailable()) {
	    		$ed = new DateTime($this->get("expire_date"));
	    		$now = new DateTime();
	    		if ($ed > $now) {
	    			return false;
	    		}
	    	}

	    	return true;
	    }


	    /**
	     * get date-time format preference
	     * @return null|string 
	     */
	    public function getDateTimeFormat()
	    {
	    	if (!$this->isAvailable()) {
	    		return null;
	    	}

	    	$date_format = $this->get("preferences.dateformat");
	    	$time_format = $this->get("preferences.timeformat") == "24"
	    	             ? "H:i" : "h:i A";
	    	return $date_format . " " . $time_format;
	    }


	    /**
	     * Check if user's (primary) email is verified or not
	     * @return boolean 
	     */
	    public function isEmailVerified()
	    {
	    	if (!$this->isAvailable()) {
	    		return false;
	    	}

	    	if ($this->get("data.email_verification_hash")) {
	    		return false;
	    	}

	    	return true;
	    }


	    /**
	     * Send verification email to the user
	     * @param  boolean $force_new Create a new hash if it's true
	     * @return [bool]                  
	     */
	    public function sendVerificationEmail($force_new = false)
	    {
	    	if (!$this->isAvailable()) {
	    		return false;
	    	}

	    	$hash = $this->get("data.email_verification_hash");

	    	if (!$hash || $force_new) {
	    		$hash = sha1(uniqid(readableRandomString(10), true));
	    	}


	    	// Get site settings
	    	$site_settings = \Controller::model("GeneralData", "settings");
	    	

	    	// Send mail
	    	$mail = new \Email;
	    	$mail->addAddress($this->get("email"));
	    	$mail->Subject = __("{site_name} Account Activation", [
	    		"{site_name}" => $site_settings->get("data.site_name")
	    	]);

	    	$body = "<p>" . __("Hi %s", htmlchars($this->get("firstname"))) . ", </p>"
                  . "<p>" . __("Please verify the email address {email} belongs to you. To do so, simply click the button below.", ["{email}" => "<strong>" . $this->get("email") . "</strong>"])
                  . "<div style='margin-top: 30px; font-size: 14px; color: #9b9b9b'>"
                  . "<a style='display: inline-block; background-color: #3b7cff; color: #fff; font-size: 14px; line-height: 24px; text-decoration: none; padding: 6px 12px; border-radius: 4px;' href='".APPURL."/verification/email/".$this->get("id").".".$hash."'>".__("Verify Email")."</a>"
                  . "</div>";
            $mail->sendmail($body);

	    	// Save (new) hash
	    	$this->set("data.email_verification_hash", $hash)
	    	     ->save();

	    	return true;
	    }


	    /**
	     * Set the user's (primary) email address as verified
	     */
	    public function setEmailAsVerified()
	    {
	    	if (!$this->isAvailable()) {
	    		return false;
	    	}

	    	$data = json_decode($this->get("data"));
	    	if (isset($data->email_verification_hash)) {
		    	unset($data->email_verification_hash);
		    	$this->set("data", json_encode($data))
		    	     ->update();
	    	}

	    	return true;
	    }
	}
?>