<?php
/**
 * Bootstrap the theme
 *
 * @category   ECS_WordPress
 * @package    Core
 * @subpackage Common
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

// Setup defines
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(dirname(realpath(__FILE__))));
define('VENDOR_PATH', APP_PATH . DS . 'vendor');
define('ECS_TIMEZONE', 'America/Los_Angeles');

// Setup Autoloader
spl_autoload_register(function ($className) {
    $namespace = str_replace("\\", "/", __NAMESPACE__);
    $className = str_replace("\\", "/", $className);
    $class = APP_PATH . '/' . (empty($namespace) ? "" : $namespace . "/") . "{$className}.class.php";
    if (file_exists($class)) {
        include_once($class);
    }
});

// Load configs
$curtheme = wp_get_theme();
\Ecs\Core\Configure::write('name', $curtheme->get('Name'));
\Ecs\Core\Configure::write('version', $curtheme->get('Version'));
unset($curtheme);



// Load helper functions
require_once 'helpers.php';


require_once APP_PATH . DS . 'Ecs' . DS . 'Config' . DS . 'core.php';
if (file_exists(APP_PATH . DS . 'Ecs' . DS . 'Config' . DS . 'core-local.php')) {
    require_once APP_PATH . DS . 'Ecs' . DS . 'Config' . DS . 'core-local.php';
}

// Load required vendors
require_once VENDOR_PATH . DS . 'plugin-activation' . DS . 'class-tgm-plugin-activation.php';

// Bootstrap the Theme!
$theme = new \Ecs\Core\Theme();
$theme->run();

/* End of file common.php */
/* Location: ./app/Ecs/common.php */
