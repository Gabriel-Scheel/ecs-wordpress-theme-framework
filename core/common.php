<?php
/**
 * Includes
 * PHP version 5
 *
 * LICENSE: 
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Common
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

// Define base paths
define('THEME_PATH', dirname(dirname(realpath(__FILE__))));
define('CORE_PATH', THEME_PATH . DIRECTORY_SEPARATOR . 'core');
define('APP_PATH', THEME_PATH . DIRECTORY_SEPARATOR . 'app');
define('VENDOR_PATH', THEME_PATH . DIRECTORY_SEPARATOR . 'vendor');

// Load required libs
require_once CORE_PATH . DIRECTORY_SEPARATOR . 'helpers.php';
require_once CORE_PATH . DIRECTORY_SEPARATOR . 'Theme.class.php';
require_once CORE_PATH . DIRECTORY_SEPARATOR . 'Options.class.php';
require_once CORE_PATH . DIRECTORY_SEPARATOR . 'PostType.class.php';
require_once CORE_PATH . DIRECTORY_SEPARATOR . 'Inflector.class.php';

// Load required vendors
require_once VENDOR_PATH . DIRECTORY_SEPARATOR . 'plugin-activation' . DIRECTORY_SEPARATOR . 'class-tgm-plugin-activation.php';

// Load optional Custom Theme Class
require_once APP_PATH . DIRECTORY_SEPARATOR . 'CustomTheme.php';

/* End of file common.php */
/* Location: ./core/commomn.php */