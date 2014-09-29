<?php
/**
 * Wordpess Theme "functions"
 *
 * PHP version 5
 *
 * LICENSE: 
 * 
 * @category   ECS_WP_ThemeCore
 * @package    ECS_WP_ThemeCore
 * @subpackage Core
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

define('DS', DIRECTORY_SEPARATOR);

// Define base paths
define('THEME_PATH', dirname(realpath(__FILE__)));
define('CORE_PATH', THEME_PATH . DS . 'core');
define('APP_PATH', THEME_PATH . DS . 'app');
define('VENDOR_PATH', THEME_PATH . DS . 'vendor');

// Load required libs
require_once CORE_PATH . DS . 'helpers.php';
require_once CORE_PATH . DS . 'Theme.class.php';
require_once CORE_PATH . DS . 'Options.class.php';
require_once CORE_PATH . DS . 'PostType.class.php';
require_once CORE_PATH . DS . 'Inflector.class.php';
require_once APP_PATH . DS . 'CustomTheme.php';

// Load required vendors
require_once VENDOR_PATH . DS . 'plugin-activation' . DS . 'class-tgm-plugin-activation.php';

$theme = new Ecs\WordPress\CustomTheme();

$theme->run(array(
	// Set some defaults
	'name' => 'my-theme-name',

	// Load post types
	'post_types' => array('Page', 'Cover'),
	
	// Load widgets
	'widgets' => array(),
	
	// Configure debugging
	'debug' => array(
		'enable_debug' => TRUE
	),

	// Define theme features
	// http://codex.wordpress.org/Function_Reference/add_theme_support
	'theme_features' => array(
		'post-thumbnails',
		'post-formats' => array(
			'aside', 
			'gallery'
		) 
	),
	
	// Define theme dependencies
	// Require WP Plugins - http://tgmpluginactivation.com/
	// Require Core PHP Classes / Libraries
	'dependencies' => array(
		'plugins' => array(
			// MetaBox is amazing, and we use it in the PostType model
			array(
				'name'      => 'Meta Box',
				'slug'      => 'meta-box',
				'required'  => true,
			),
			// Options Framework is also amazing
			array(
				'name'      => 'Options Framework',
				'slug'      => 'options-framework',
				'required'  => false,
			),
		),
		'classes' => array(
			'Imagick',
		),
	),
	
	// Define stylesheets and scripts
	'assets' => array(
		'stylesheets' => array(
			'style' => array(
				'source' => get_stylesheet_directory_uri() . '/assets/css/app.css',
				'dependencies' => FALSE,
				'version' => 'v1'
			),
		),
		'scripts' => array(
			'main' => array(
				'source' => get_stylesheet_directory_uri() . '/assets/js/main.js',
				'dependencies' => FALSE,
				'version' => 'v1',
				'in_footer' => TRUE
			),
		)
	)
));

/* End of file functions.php */
/* Location: ./functions.php */