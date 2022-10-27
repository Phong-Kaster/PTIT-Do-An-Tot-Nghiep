<?php

/**
 * Configuration class
 */
class Config
{
    protected static $settings = array();


    /**
     * Get the value of setting.
     * @param  string $key name of setting.
     * @return mixed       value of setting or null in case of invalid name
     */
    public static function get($key)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }


    /**
     * Set setting
     * @param string $key  name of setting.
     * @param mixed $value value of setting.
     */
    public static function set($key, $value)
    {
        self::$settings[$key] = $value;
    }
}
