<?php 
	/**
	 * Post Model
	 *
	 * @version 1.0
	 * @author Onelab <hello@onelab.co> 
	 * 
	 */
	
	class PostModel extends DataEntry
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
	    	} else {
	    		$col = null;
	    	}

	    	if ($col) {
		    	$query = DB::table(TABLE_PREFIX.TABLE_POSTS)
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
	    		"status" => "saved",
	    		"user_id" => 0,
	    		"type" => "",
	    		"caption" => "",
	    		"first_comment" => "",
	    		"location" => "",
	    		"media_ids" => "",
	    		"remove_media" => 0,
	    		"account_id" => 0,
	    		"is_scheduled" => 0,
	    		"create_date" => date("Y-m-d H:i:s"),
	    		"schedule_date" => date("Y-m-d H:i:s"),
	    		"publish_date" => date("Y-m-d H:i:s"),
	    		"is_hidden" => 0,
	    		"data" => "{}",
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

	    	$id = DB::table(TABLE_PREFIX.TABLE_POSTS)
		    	->insert(array(
		    		"id" => null,
		    		"status" => $this->get("status"),
		    		"user_id" => $this->get("user_id"),
		    		"type" => $this->get("type"),
		    		"caption" => $this->get("caption"),
		    		"first_comment" => $this->get("first_comment"),
		    		"location" => $this->get("location"),
		    		"media_ids" => $this->get("media_ids"),
		    		"remove_media" => $this->get("remove_media"),
		    		"account_id" => $this->get("account_id"),
		    		"is_scheduled" => $this->get("is_scheduled"),
		    		"create_date" => $this->get("create_date"),
		    		"schedule_date" => $this->get("schedule_date"),
		    		"publish_date" => $this->get("publish_date"),
		    		"is_hidden" => $this->get("is_hidden"),
		    		"data" => $this->get("data")
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

	    	$id = DB::table(TABLE_PREFIX.TABLE_POSTS)
	    		->where("id", "=", $this->get("id"))
		    	->update(array(
		    		"status" => $this->get("status"),
		    		"user_id" => $this->get("user_id"),
		    		"type" => $this->get("type"),
		    		"caption" => $this->get("caption"),
		    		"first_comment" => $this->get("first_comment"),
		    		"location" => $this->get("location"),
		    		"media_ids" => $this->get("media_ids"),
		    		"remove_media" => $this->get("remove_media"),
		    		"account_id" => $this->get("account_id"),
		    		"is_scheduled" => $this->get("is_scheduled"),
		    		"create_date" => $this->get("create_date"),
		    		"schedule_date" => $this->get("schedule_date"),
		    		"publish_date" => $this->get("publish_date"),
		    		"is_hidden" => $this->get("is_hidden"),
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

	    	DB::table(TABLE_PREFIX.TABLE_POSTS)->where("id", "=", $this->get("id"))->delete();
	    	$this->is_available = false;
	    	return true;
	    }


	    /**
	     * Update current id of the post
	     * @param  integer $new_id 
	     * @param  string $idcol  
	     * @return boolean         
	     */
	    public function updateId($new_id, $idcol = "id")
	    {
	    	if (!$this->isAvailable()) {
	    		return false;
	    	}

	    	$current_id = $this->get($idcol);
	    	
	    	DB::table(TABLE_PREFIX.TABLE_POSTS)
	    		->where("id", "=", $this->get("id"))
		    	->update(array(
		    		"id" => $new_id
		    	));

		    $this->set("id", $new_id);
		    return true;
	    }


	    /**
	     * Removes atatched media files to the post
	     * only if the media file is not required for any other unpublished post
	     * @return [type] [description]
	     */
	    public function removeMedia()
	    {
	    	if (!$this->isAvailable()) {
	    		return false;
	    	}

	    	$media_ids = explode(",", $this->get("media_ids"));
	    	$ids = [];
	    	foreach ($media_ids as $id) {
	    		$id = (int)$id;

	    		if ($id < 1) {
	    			continue;
	    		}

	    		// Select all posts which use this media file 
	    		// and not published yet
	    		$posts = \Controller::model("Posts");
	    		$posts->whereIn("status", ["scheduled", "publishing", "failed"])
	    			  ->where("media_ids", "REGEXP", "[[:<:]]".$id."[[:>:]]")
	    			  ->fetchData();

	    		if ($posts->getTotalCount() > 0) {
	    			// Media file is required 
	    			// for some other (unpublished) post(s)
	    			// Don't remove this file
	    			continue;
	    		}

	    		// It's safe to remove the media file
	    		$ids[] = $id;
	    	}

	    	if (count($ids) < 0) {
	    		return false;
	    	}

    		// Get media data before removing it
    		$resp = DB::table(TABLE_PREFIX.TABLE_FILES)
    		       ->select("*")
    		       ->whereIn("id", $ids)
    		       ->get();

    		// Remove media data from the database
    		DB::table(TABLE_PREFIX.TABLE_FILES)->whereIn("id", $ids)
    		                                   ->delete();

			// Remove actual file(s)
    		foreach ($resp as $r) {
    			@unlink (ROOTPATH."/assets/uploads/".$r->user_id."/".$r->filename);
    		}

    		return true;
	    }
	}
	