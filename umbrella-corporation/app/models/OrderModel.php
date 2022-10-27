<?php 
	/**
	 * Order Model
	 *
	 * @version 1.0
	 * @author Onelab <hello@onelab.co> 
	 * 
	 */
	
	class OrderModel extends DataEntry
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
	    		$col = "payment_id";
	    	}

	    	if ($col) {
		    	$query = DB::table(TABLE_PREFIX.TABLE_ORDERS)
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
	    		"user_id" => 0,
	    		"data" => "{}",
	    		"status" => "",
	    		"payment_gateway" => "",
	    		"payment_id" => "",
	    		"total" => 0.00,
	    		"paid" => 0.00,
	    		"currency" => "",
	    		"date" => date("Y-m-d H:i:s")
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

	    	$id = DB::table(TABLE_PREFIX.TABLE_ORDERS)
		    	->insert(array(
		    		"id" => null,
		    		"user_id" => $this->get("user_id"),
		    		"data" => $this->get("data"),
		    		"status" => $this->get("status"),
		    		"payment_gateway" => $this->get("payment_gateway"),
		    		"payment_id" => $this->get("payment_id"),
		    		"total" => $this->get("total"),
		    		"paid" => $this->get("paid"),
		    		"currency" => $this->get("currency"),
		    		"date" => $this->get("date")
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

	    	$id = DB::table(TABLE_PREFIX.TABLE_ORDERS)
	    		->where("id", "=", $this->get("id"))
		    	->update(array(
		    		"user_id" => $this->get("user_id"),
		    		"data" => $this->get("data"),
		    		"status" => $this->get("status"),
		    		"payment_gateway" => $this->get("payment_gateway"),
		    		"payment_id" => $this->get("payment_id"),
		    		"total" => $this->get("total"),
		    		"paid" => $this->get("paid"),
		    		"currency" => $this->get("currency"),
		    		"date" => $this->get("date")
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

	    	DB::table(TABLE_PREFIX.TABLE_ORDERS)->where("id", "=", $this->get("id"))->delete();
	    	$this->is_available = false;
	    	return true;
	    }


	    /**
	     * Finished the order payment processing
	     * Must be called after successfull payment
	     * 
	     * @return OrderModel	self
	     */
	    public function finishProcessing()
	    {
	    	$User = \Controller::model("User", $this->get("user_id"));
	    	$order_data = json_decode($this->get("data"));

	    	$delta = 0;
	    	if ($order_data->plan == "annual") {
    	        $delta = 365 * 24 * 60 *60;
	    	} else {
    	        $delta = 30 * 24 * 60 *60;
	    	}
	    	
	    	$start_time = strtotime($User->get("expire_date"));
	    	if ($start_time < time()) {
	    	    $start_time = time();
	    	}

	    	$new_expire_data = date("Y-m-d H:i:s", $start_time + $delta);

	    	$User->set("expire_date", $new_expire_data)
	    	     ->set("settings", json_encode($order_data->applied_settings))
	    	     ->set("package_id", $order_data->package->id)
	    	     ->save();
	    }
	}
?>