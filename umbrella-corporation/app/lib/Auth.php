<?php

/**
 * @author Phong-Kaster
 * @since 27-10-2022
 * Auth class
 */
class Auth
{
  	protected $data;
	protected $is_available;


  
  	public function __construct()
	{
		$this->data = array();
		$this->is_available = false;
	}

    /**
     * Check if entry is available
     * @return boolean 
     */
    public function isAvailable()
    {
        return $this->is_available;
    }


    /**
     * Mark as available
     * Usefull for creating object from raw data
     * @return self
     */
    public function markAsAvailable()
    {
        $this->is_available = true;
        return $this;
    }

    /**
     * Set value for data field
     * @param string $field Name of the data field
     * @param mixed  $value Value of the given data field
     * @param bool   $parse If true then treat $field as json field
     */
    public function set($field, $value, $parse=true)
    {	
        if (is_string($field) && $field) {
            if($parse) {
                $fields = explode(".", $field);
            }

            if (!empty($fields) && count($fields) > 1) {
                $column = $fields[0];
                

                array_shift($fields);
                $total = count($fields);
                
                $newval = $value;
                for ($i = $total-1; $i >= 0 ; $i--) {
                    $newval = array($fields[$i] => $newval);
                }

                $currentval = json_decode($this->get($column), true);
                if (!$currentval) {
                    $currentval = array();
                }

                $this->data[$column] = json_encode(array_replace_recursive($currentval, $newval));
            } else {
                $this->data[$field] = $value;
            }
        }

        return $this;
    }

    /**
     * Get the value of data field
     * @param  string $field Name of the data field
     * @param  bool   $parse If true then treat $field as json field and return subfield in case of found or null
     * @return mixed        Value of the given data field (or null if field is not exists)
     */
    public function get($field, $parse=true)
    {
        if (is_string($field) && $field) {
            if($parse) {
                $fields = explode(".", $field);
            }

            if (!empty($fields) && count($fields) > 1) {
                $column = $fields[0];

                if (isset($this->data[$column]) && is_string($column) && $column) {
                    $parsedjson = @json_decode($this->data[$column]);

                    if ($parsedjson) {
                        array_shift($fields);

                        $val = $parsedjson;
                        foreach ($fields as $name) {
                            if ($name && isset($val->$name)) {
                                $val = $val->$name;
                            } else {
                                $val = null; 
                                break;
                            }
                        }

                        return is_string($val) ? trim($val) : $val;
                    }
                }
            } else {
                if (isset($this->data[$field])) {
                    return is_string($this->data[$field]) ? trim($this->data[$field]) : $this->data[$field];
                }
            }
        }

        return null;
    }

    /**
     * Check if account has administrative privilages
     * @return boolean 
     */
    public function isAdmin()
    {
        if ($this->isAvailable() && in_array($this->get("role"), array("admin"))) {
            return true;
        }

        return false;
    }
}
