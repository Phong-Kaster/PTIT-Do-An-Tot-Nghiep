<?php 
	/**
	 * SpecialityModel
	 *
	 * @version 1.0
	 * @author Onelab <hello@onelab.co> 
	 * 
	 */
	
	class AppointmentsModel extends DataEntry
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
	    	$col = "id";


	    	if ($col) {
		    	$query = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
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
                "patient_id" => "",
                "doctor_id" => "",
				"patient_name" => "",
				"patient_birthday" => "",
				"patient_reason" => "",
				"patient_phone" => "",
				"numerical_order" => "",
				"appointment_time" => "",
				"status" => "",
				"create_at" => "",
				"update_at" => ""
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

	    	$id = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
		    	->insert(array(
		    		"id" => null,
                    "doctor_id" => $this->get("doctor_id"),
		    		"patient_id" => $this->get("patient_id"),
					"patient_name" => $this->get("patient_name"),
					"patient_birthday" => $this->get("patient_birthday"),
					"patient_reason" => $this->get("patient_reason"),
					"patient_phone" => $this->get("patient_phone"),
					"numerical_order" => $this->get("numerical_order"),
                    "appointment_time" => $this->get("appointment_time"),
					"status" => $this->get("status"),
					"create_at" => $this->get("create_at"),
					"update_at" => $this->get("update_at")
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

	    	$id = DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
	    		->where("id", "=", $this->get("id"))
		    	->update(array(
                    "doctor_id" => $this->get("doctor_id"),
		    		"patient_id" => $this->get("patient_id"),
					"patient_name" => $this->get("patient_name"),
					"patient_birthday" => $this->get("patient_birthday"),
					"patient_reason" => $this->get("patient_reason"),
					"patient_phone" => $this->get("patient_phone"),
					"numerical_order" => $this->get("numerical_order"),
                    "appointment_time" => $this->get("appointment_time"),
					"status" => $this->get("status"),
					"create_at" => $this->get("create_at"),
					"update_at" => $this->get("update_at")
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

	    	DB::table(TABLE_PREFIX.TABLE_APPOINTMENTS)
            ->where("id", "=", $this->get("id"))->delete();
	    	$this->is_available = false;
	    	return true;
	    }
	}
?>