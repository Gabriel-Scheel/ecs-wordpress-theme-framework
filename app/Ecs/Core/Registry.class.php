<?php
namespace Ecs\Core;

/**
 * Registry
 *
 * @category   ECS_WordPress
 * @package    Core
 * @subpackage Registry
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class Registry
{

    /**
     *
     */
    private static $instance = null;

    /**
     *
     */
    private $registry = array();

    /**
     *
     */
    public function set($key, $value)
    {
        if (isset($this->registry[$key])) {
            throw new \Exception("There is already an entry for key " . $key);
        }

        $this->registry[$key] = $value;
    }
 
    /**
     *
     */
    public function get($key)
    {
        if (!isset($this->registry[$key])) {
            throw new \Exception("There is no entry for key " . $key);
        }

        return $this->registry[$key];
    }

    /**
     *
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Registry();
        }

        return self::$instance;
    }

    /**
     *
     */
    private function __construct()
    {

    }

    /**
     *
     */
    private function __clone()
    {

    }
}

/* End of file registry.class.php */
/* Location: ./app/Ecs/Core/registry.class.php */
