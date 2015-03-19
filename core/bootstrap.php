<?php

define('DS', DIRECTORY_SEPARATOR);

// Define base paths
define('THEME_PATH', get_stylesheet_directory());
define('CORE_PATH', THEME_PATH . DS . 'core');
define('APP_PATH', THEME_PATH . DS . 'app');
define('VENDOR_PATH', THEME_PATH . DS . 'vendor');

// Load core libs
require_once CORE_PATH . DS . 'helpers.php';
require_once CORE_PATH . DS . 'CoreTheme.class.php';
require_once CORE_PATH . DS . 'Registry.class.php';
require_once CORE_PATH . DS . 'Options.class.php';
require_once CORE_PATH . DS . 'PostType.class.php';
require_once CORE_PATH . DS . 'Inflector.class.php';

// Load Theme
require_once APP_PATH . DS . 'Theme.php';

// Load required vendor libs
require_once VENDOR_PATH . DS . 'plugin-activation' . DS . 'class-tgm-plugin-activation.php';