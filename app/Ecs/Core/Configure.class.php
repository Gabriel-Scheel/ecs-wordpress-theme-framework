<?php
namespace Ecs\Core;

/**
 * Configure class for handling theme configurations
 *
 * @todo refactor to handle ALL theme configurations including those
 * in metaboxes, options framework, and general WP config.
 */
final class Configure
{

    /**
     * @param $config array
     */
    protected static $config = array();

    /**
     * Set $config. Used to initialize the Configure object
     *
     * @todo refactor, remove this method and use write()
     */
    public static function set($config = array())
    {
        self::$config = $config;
    }

    /**
     * Write config data
     *
     * @param string|array $key - the config key to write to
     * @param string $val - the config value to write
     * @return void
     * @todo refactor, support passing an array of config data
     */
    public static function write($key, $val = '')
    {
        self::setConfig($key, $val);
    }

    /**
     * Read config data from the array
     *
     * @param string $key - the config value to return
     * @return mixed
     * @todo refactor, enhance to support dot notation (Configure::read('my.config.data'))
     */
    public static function read($key)
    {
        $key = explode('.', $key);
        return self::getConfig($key, self::$config);
    }
    
    /**
     * Handle the dot notation to set a config value to the config array
     *
     * @param type $index
     * @param type $value
     * @return type
     */
    private static function setConfig($index, $val)
    {
        $link =& self::$config;

        if (!empty($index)) {
            $keys = explode('.', $index);

            if (count($keys) == 1) {
                self::$config[$index] = $val;
            } else {
                foreach ($keys as $key) {
                    if (!isset(self::$config[$key])) {
                        $link[$key] = array();
                    }

                    $link =& $link[$key];
                }

                $link = $val;
            }
        }
    }
    
    /**
     * Handle the dot notation to get a config value from the config array
     *
     * @param type $index
     * @param type $value
     * @return type
     */
    private static function getConfig($index, $config)
    {
        if (is_array($index) && count($index)) {
            $current_index = array_shift($index);
        }
        
        if (is_array($index) && count($index) && is_array($config[$current_index]) && count($config[$current_index])) {
            return self::getConfig($index, self::$config[$current_index]);
        } else {
            return (!empty($config[$current_index])) ? $config[$current_index] : false ;
        }
    }
}