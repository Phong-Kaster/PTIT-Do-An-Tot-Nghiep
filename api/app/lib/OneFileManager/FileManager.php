<?php 
namespace OneFileManager;

use OneFileManager\Common;


/**
 * One FileManager
 *
 * @date 07 June 2017
 * @version 1.0
 * @author Vusal Orujov
 * @copyright Onelab <hello@onelab.co>
 */
class FileManager
{   
    // OneFilemanager/Conntector -  Database connector 
    private $connector;

    // filemanager options
    private $options;

    /**
     * Contructor
     * @param array  $options   Array of options
     * @param OneFileManager/Connector $connector Database Connector object
     */
    public function __construct($options = array(), $connector = null)
    {
        $this->setOptions($options);
        $this->setConnector($connector);
    }


    /**
     * Get options
     * @param  boolean $obj If true return as object else array.
     * @return stdClass|array 
     */
    public function getOptions($obj=true) 
    {
        if ($obj) {
            return json_decode(json_encode($this->options));
        }

        return $this->options;
    }


    /**
     * Set options
     * @param array $options An array of new options
     */
    public function setOptions($options) {
        if (!is_array($this->options)) {
            $this->options = array(
                "path" => "", // Path to root directory [required] 
                "url" => "", // URL of root directory path [required]

                // Denied file extensions. Ex: .jpeg
                // empty array means check allowed extensions
                "deny" => array(), 

                // Allowed file extensions. Ex: .jpeg
                // empty array means allow all
                "allow" => array(), 
                
                // Max. allowed file size in bytes
                // null equals to unlimited
                "max_file_size" => null, 

                // Max. allowed storage size in bytes
                // null equals to unlimited
                "max_storage_size" => null, 

                // Max. allowed files count in upload queue
                // null means unlimited
                "queue_size" => 10
            );
        }

        if (is_array($options) && $options) {
            $this->options = array_replace_recursive(
                array(),
                $this->options,
                $options
            );
        }

        return $this;
    }

    /**
     * Get single option
     * @param  string $key Option name (array key)
     * @return Mixed       Option value or null
     */
    public function getOption($key)
    {
        if (is_string($key) && isset($this->options[$key])) {
            return $this->options[$key];
        }

        return null;
    }


    /**
     * Set sing option
     * @param string $key   Option name
     * @param Mixed $value  Option value
     */
    public function setOption($key, $value)
    {
        if (is_string($key)) {
            return $this->setOptions(array($key => $value));
        }

        return $this;
    }


    /**
     * Get Denied MIME types
     * @return array 
     */
    public function getDeniedMimes()
    {
        $denied_mimes = [];
        foreach ($this->getOption("deny") as $ext) {
            $mime = Common::extToMime($ext);
            if ($mime && !in_array($mime, $denied_mimes)) {
                $denied_mimes[] = $mine;
            }
        }

        return $denied_mimes;
    }


    /**
     * Get Allowed MIME types
     * @return array 
     */
    public function getAllowedMimes()
    {
        $allowed_mimes = [];
        foreach ($this->getOption("allow") as $ext) {
            $mime = Common::extToMime($ext);
            if ($mime && !in_array($mime, $allowed_mimes)) {
                $allowed_mimes[] = $mine;
            }
        }

        return $allowed_mimes;
    }


    /**
     * Get connector object
     * @return OneFileManager/Connector $connector Database Connector object
     */
    public function getConnector()
    {
        return $this->connector;
    }


    /**
     * Set connector object
     * @param OneFileManager/Connector $connector 
     */
    public function setConnector($connector)
    {
        if (is_a($connector, "OneFileManager\Connector")) {
            $this->connector = $connector;
        }

        return $this;
    }


    /**
     * Parse data and run command in console
     * @param  string $data [description]
     * @return [type]       [description]
     */
    public function run($data=null)
    {
        if (is_null($data)) {
            $data = json_decode(json_encode($_REQUEST));
        }


        if (!$this->getOption("path") || !is_string($this->getOption("path"))) {
            return Common::error("Missing/Invalid FileManager option (path).");
        } else if (!is_writable($this->getOption("path"))) {
            return Common::error("Couldn't find readable volume");
        }


        if (!isset($data->cmd)) {
            return Common::error("Command is required");
        }


        $cmd = $data->cmd;
        unset($data->cmd);
        $console = new Console($this);

        if (!method_exists($console, $cmd)) {
            return Common::error("Invalid command");
        } 

        $console->setInputData($data);
        $console->$cmd();
    }
}
