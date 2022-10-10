<?php
/**
 * Controller
 */
class Controller
{   
    /**
     * Assosiative array
     * Key: will be converted to variable for view
     * Value: value of the variable with name Key
     * @var array
     */
    protected $variables;

    /**
     * JSON output response holder
     * @var \stdClass
     */
    protected $resp;

    /**
     * Initialize variables
     * @param array $variables  [description]
     */
    public function __construct($variables = array())
    {
        $this->variables = array();
        $this->resp = new stdClass;
    }


    /**
     * Get model
     * @param  string|array $name name of model
     * @param  array|string $args Array of arguments for model constructor
     * @return null|mixed       
     */
    public static function model($name, $args=array())
    {
        if (is_array($name)) {
            if (count($name) != 2) {
                throw new Exception('Invalid parameter');
            }

            $file = $name[0];
            $class = $name[1];
        } else {
            $file = APPPATH."/models/".$name."Model.php";
            $class = $name."Model";
        }

        if (file_exists($file)) {
            require_once $file;

            if (!is_array($args)) {
                $args = array($args);
            }

            $reflector = new ReflectionClass($class);
            return $reflector->newInstanceArgs($args);
        }

        return null;
    }

    /**
     * View
     * @param  string $view name of view file
     * @param  string $context 
     * @return void       
     */
    public function view($view, $context = "app")
    {
        foreach ($this->variables as $key => $value) {
            ${$key} = $value;
        }

        switch ($context) {
            case "app":
                $path = APPPATH."/views/".$view.".php";
                break;

            case "site":
                $path = active_theme("path") . "/views/" . $view .".php";
                break;

            default: 
                $path = $view;
        }


        require_once $path;
    }


    /**
     * Set new variable for view.
     * @param string $name  Name of the variable.
     * @param mixed $value 
     */
    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;
        return $this;
    }


    /**
     * Get variable
     * @param  string $name Name of the varaible.
     * @return mixed       
     */
    public function getVariable($name)
    {
        return isset($this->variables[$name]) ? $this->variables[$name] : null;
    }


    /**
     * Print json(or jsonp) string and exit;
     * @return void 
     */
    protected function jsonecho($resp = null)
    {
        if (is_null($resp)) {
            $resp = $this->resp;
        }
        
        echo Input::get("callback") ? 
                Input::get("callback")."(".json_encode($resp).")" : 
                    json_encode($resp);
        exit;
    }
}
