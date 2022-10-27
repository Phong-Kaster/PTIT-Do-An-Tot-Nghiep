<?php
/**
 * PSR-4 Autoloader Class.
 */
class Autoloader
{
    /**
     * An array of base directories to look up for classes
     *
     * @var array
     */
    protected $base_dirs = array();

    /**
     * Register loader with SPL autoloader stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Adds a base directory
     *
     * @param string $base_dir A base directory for namespace.
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     * @return void
     */
    public function addBaseDir($base_dir, $prepend = false)
    {
        // normalize the base directory with a trailing separator
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // initialize the namespace prefix array
        if (!in_array($base_dir, $this->base_dirs)) {
            // retain the base directory for the namespace prefix
            if ($prepend) {
                array_unshift($this->base_dirs, $base_dir);
            } else {
                array_push($this->base_dirs, $base_dir);
            }
        }

    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        $class_name = ltrim($class, '\\');
        $relative_class  = '';
        $namespace = '';

        if ($last_ns_pos = strrpos($class_name, '\\')) {
            $namespace = substr($class_name, 0, $last_ns_pos);
            $class_name = substr($class_name, $last_ns_pos + 1);
            $relative_class  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        //$class_name .= str_replace('_', "DIRECTORY_SEPARATOR", $class_name);
        $relative_class .= $class_name . '.php';


        foreach ($this->base_dirs as $base_dir) {
            $mapped_file = $this->loadMappedFile($base_dir, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }            
        }


        // never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a base directory and relative class.
     *
     * @param string $base_dir Base directory.
     * @param string $relative_class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($base_dir, $relative_class)
    {
        // full path to class file
        $file = $base_dir
              . DIRECTORY_SEPARATOR
              . $relative_class; 

        // if the mapped file exists, require it
        if ($this->requireFile($file)) {
            // yes, we're done
            return $file;
        }

        // never found it
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @param string $file The file to require.
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }
}