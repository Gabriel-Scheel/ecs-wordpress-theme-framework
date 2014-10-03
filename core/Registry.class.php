<?php
namespace Ecs\WordPress;

/**
 * Class Registry 
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * @category   ECS_WP_ThemeCore
 * @package    App
 * @subpackage Theme
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/**
 * Registry 
 * 
 * @category   ECS_WP_ThemeCore
 * @package    App
 * @subpackage Theme
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class Registry {

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
	public function set($key, $value) {
		if (isset($this->registry[$key])) {
			throw new \Exception("There is already an entry for key " . $key);
		}

		$this->registry[$key] = $value;
	}
 
	/**
	 *
	 */
	public function get($key) {
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
		if (self::$instance === null)
		{
			self::$instance = new Registry();
		}

		return self::$instance;
	}

	/**
	 *
	 */
	private function __construct(){

	}

	/**
	 *
	 */
	private function __clone(){

	}
}