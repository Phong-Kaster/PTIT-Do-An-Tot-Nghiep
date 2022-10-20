<?php
	/**
	 * Get value of input (get, post, request, session, cookie)
	 *
	 * @version 1.0
	 * @author Onelab <hello@onelab.co> 
	 * 
	 */
	class Input
	{

		public function __construct(){
		}

		public static function method(){
			$method = strtoupper($_SERVER['REQUEST_METHOD']);
			$m = "_".$method;
			$input = file_get_contents('php://input');

			if (strlen($input) > 0 && isValidJSON($input)){
				$arr = json_decode($input, true);
			}else{
				parse_str(urldecode($input), $arr);
			}
			foreach($arr as $key => $value){
				$GLOBALS[$m][$key] = $value;
			}
			return $method;
		}

		/**
		 * Session inputs
		 * @param  string  $method 		name of method (get | post | request | session | cookie)
		 * @param  string  $input_name 	name of input
		 * @param  int|bool  $index   	index in input array of treat as $trim
		 * @param  boolean $trim      	trim input value (if it is string) or not
		 * @return mix              
		 */
		public static function getInput($method, $input_name, $index = true, $trim = true)
		{
			if(!in_array($method, array("get", "post", "request", "cookie", "session", "put", "delete", "patch")))
				throw new \Exception('Invalid method!');

			$input = null;

			$method = "_".strtoupper($method);
			if (isset($GLOBALS[$method][$input_name]))
				$input = $GLOBALS[$method][$input_name];

			if (is_array($input) && is_int($index)){
				if ($index >= 0) {
					if (isset($input[$index])) {
						$input = $input[$index];
					} else {
						throw new \Exception('Index is not exists!');
					}
				} else {
					throw new \Exception('Invalid index');
				}
			}


			if (!is_array($input) || !is_int($index)) 
				$trim = (bool)$index;


			if (is_string($input) && $trim) 
				$input =  trim($input);

			return $input;
		}



		public static function __callStatic($name, $arguments) 
		{	
			$name = strtolower($name);

			if($name == "req")
				$name = "request";

	        if (in_array($name, array("get", "post", "request", "cookie", "session", "put", "delete", "patch"))) {
	        	array_unshift($arguments, $name);
	            return call_user_func_array(array('Input', 'getInput'), $arguments);
	        } else {
	        	throw new \Exception('Invalid method');
	        }
	    }


	    public function __call($name, $arguments) 
		{	
			$name = strtolower($name);

			if($name == "req")
				$name = "request";

	        if (in_array($name, array("get", "post", "request", "cookie", "session", "put", "delete", "patch"))) {
	        	array_unshift($arguments, $name);
	            return call_user_func_array(array('Input', 'getInput'), $arguments);
	        } else {
	        	throw new \Exception('Invalid method');
	        }
	    }

	}
?>