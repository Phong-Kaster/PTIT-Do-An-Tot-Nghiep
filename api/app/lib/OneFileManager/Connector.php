<?php 
namespace OneFileManager;

use OneFileManager\Common;

/**
 * Database Connector class
 */
class Connector
{
    // filemanager options
    private $options;

    // Database connection
    private $connection;

    /**
     * Constructor
     * @param array $options Array of options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
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
                "host" => "localhost",
                "port" => "",
                "database" => "",
                "username" => "",
                "password" => "",
                "charset" => "",
                "options" => array(),
                "table_name" => "files",
                "unix_socket" => "",

                "user_id" => ""
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
     * Get connection
     * @return \PDO 
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set connection
     * @param \PDO $connection 
     */
    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Connect to database
     * @return Connector 
     */
    public function connect()
    {
        if (!$this->getOption("host") || !is_string($this->getOption("host"))) {
            return Common::error("Missing/Invalid Connector option (host).");
        }

        if (!$this->getOption("database") || !is_string($this->getOption("database"))) {
            return Common::error("Missing/Invalid Connector option (database).");
        }

        if (!$this->getOption("username") || !is_string($this->getOption("username"))) {
            return Common::error("Missing/Invalid Connector option (username).");
        }

        if (!is_string($this->getOption("password"))) {
            return Common::error("Missing/Invalid Connector option (password).");
        }



        $dsn = "mysql:host=" . $this->getOption('host');
        
        if ($this->options['database']) {
            $dsn .= ";dbname=" . $this->getOption('database');
        }
        
        if ($this->options['port']) {
            $dsn .= ";port=" . $this->getOption('port');
        }

        if ($this->options['unix_socket']) {
            $dsn .= ";unix_socket=" . $this->getOption('unix_socket');
        }

        if ($this->options['charset']) {
            $dsn .= ";charset=" . $this->getOption('charset');
        }

        try {
            $this->connection = new \PDO($dsn, 
                                         $this->getOption("username"), 
                                         $this->getOption("password"), 
                                         $this->getOption("options"));
        } catch (Exception $e) {
            return Common::error("Couldn't connect to database.");
        }

        return $this;
    }


    /**
     * Check existense file data table,
     * Creates table if it is not exist
     * @return [type] [description]
     */
    public function checkTable()
    {
        if (!$this->getConnection()) {
            return Common::error("Connect to database first!");
        }

        if (!$this->getOption("table_name") || !is_string($this->getOption("table_name"))) {
            return Common::error("Missing/Invalid Connector option (table_name).");
        }

        $stmt = $this->getConnection()->prepare("SHOW TABLES LIKE ?");
        $stmt->execute(array($this->getOption("table_name")));

        if ($stmt->rowCount() < 1) {
            // Table not exits, create
            try {
                $sql = "CREATE TABLE " . $this->getOption("table_name") . " ( 
                    `id` INT NOT NULL AUTO_INCREMENT , 
                    `user_id` INT NOT NULL , 
                    `title` TEXT NOT NULL , 
                    `info` TEXT NOT NULL , 
                    `filename` VARCHAR(200) NOT NULL , 
                    `filesize` VARCHAR(255) NOT NULL,
                    `date` DATETIME NOT NULL , 
                    PRIMARY KEY (`id`)
                ) ENGINE = InnoDB;";
                $stmt = $this->getConnection()->query($sql);
            } catch (\Exception $e) {
                return Common::error("Couldn't find or create files table");
            }
        }
    }


    /**
     * Create a connection, check existance of files table,
     * if table is not exists, then create 
     * @return Connector
     */
    public function init()
    {
        $this->connect();
        $this->checkTable();

        return $this;
    }
}
