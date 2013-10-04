<?php

/**
 * Extends Theme
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

require_once CORE_PATH . '/Theme.php';
require_once APP_PATH . '/CustomDecorator.php';

/**
 * Theme
 * 
 * @category   ECS_WP_ThemeCore
 * @package    App
 * @subpackage Theme
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class CustomTheme extends Theme
{
	/**
	 * 
	 * @param type $config
	 */
	public function run($config = array())
	{
		parent::run($config);
		
		// Load our own decorator
		$this->Decorator = new CustomDecorator();
		$this->Decorator->run();
	}
}

/* End of file CustomTheme.php */
/* Location: ./lib/CustomTheme.php */