<?php 
	/**
	 * User Model
	 *
	 * @version 1.0
	 * @author Onelab <hello@onelab.co> 
	 * 
	 */
	
	class DoctorModel extends DataEntry
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
	    	}
			else 
			{
				$col = "phone";
			}

	    	if ($col) {
		    	$query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)
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
	    		"email" => "",
                "phone" => "",
	    		"password" => "",
	    		"name" => "",
                "description" => "",
                "price" => 0,
				"role" => "admin",
				"active" => "1",
                "avatar" => "",
				"create_at" => date("Y-m-d H:i:s"),
				"update_at" => date("Y-m-d H:i:s"),
                "speciality_id" => "",
				"room_id" => "",
				"recovery_token" => ""
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

	    	$id = DB::table(TABLE_PREFIX.TABLE_DOCTORS)
		    	->insert(array(
		    		"id" => null,
		    		"email" => $this->get("email"),
		    		"phone" => $this->get("phone"),
                    "password" => $this->get("password"),
                    "name" => $this->get("name"),
                    "description" => $this->get("description"),
                    "price" => $this->get("price"),
                    "role" => $this->get("role"),
                    "active" => $this->get("active"),
                    "avatar" => $this->get("avatar"),
                    "create_at" => $this->get("create_at"),
                    "update_at" => $this->get("update_at"),
                    "speciality_id" => $this->get("speciality_id"),
					"room_id" => $this->get("room_id"),
					"recovery_token" => $this->get("recovery_token")
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

	    	$id = DB::table(TABLE_PREFIX.TABLE_DOCTORS)
	    		->where("id", "=", $this->get("id"))
		    	->update(array(
		    		"email" => $this->get("email"),
		    		"phone" => $this->get("phone"),
                    "password" => $this->get("password"),
                    "name" => $this->get("name"),
                    "description" => $this->get("description"),
                    "price" => $this->get("price"),
                    "role" => $this->get("role"),
                    "active" => $this->get("active"),
                    "avatar" => $this->get("avatar"),
                    "create_at" => $this->get("create_at"),
                    "update_at" => $this->get("update_at"),
                    "speciality_id" => $this->get("speciality_id"),
					"room_id" => $this->get("room_id"),
					"recovery_token" => $this->get("recovery_token")
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

	    	DB::table(TABLE_PREFIX.TABLE_DOCTORS)->where("id", "=", $this->get("id"))->delete();
	    	$this->is_available = false;
	    	return true;
	    }


	    /**
	     * Check if account has administrative privilages
	     * @return boolean 
	     */
	    public function isAdmin()
	    {
	    	if ($this->isAvailable() && 
				in_array($this->get("role"), array("developer", "admin"))) {
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
	    		if ($this->get("role") == "developer" || $this->get("id") == $User->get("id")) {
    				return true;
    			}	

    			if (
    				$this->get("role") == "admin" && 
    				(
	    				in_array($User->get("role"), array("member", "admin")) ||
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