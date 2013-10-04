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

define('THEME_PATH', dirname(realpath(__FILE__)));
define('CORE_PATH', THEME_PATH . DIRECTORY_SEPARATOR . 'core');
define('APP_PATH', THEME_PATH . DIRECTORY_SEPARATOR . 'app');

require_once CORE_PATH . '/helpers.php';
require_once APP_PATH . '/CustomTheme.php';

$theme = new CustomTheme();
$theme->run(array(
	// Set some defaults
	'name' => 'my-theme-name',

	// Load post types
	'post_types' => array(),
	
	// Load widgets
	'widgets' => array(),
	
	// Configure debugging
	'debug' => array(
		'enable_debug' => TRUE,
		'enable_pixel_perfect' => FALSE,
		'enable_wienre' => FALSE,
	),

	// Define theme features
	'theme_features' => array(
		'post-thumbnails',
		'post-formats' => array(
			'aside', 
			'gallery'
		) 
	),
	
	// Define theme dependencies
	'dependencies' => array(
		'plugins' => array(
			'Google Analytics' => 'googleanalytics/googleanalytics.php'
		),
		'classes' => array(
			'Imagick',
		),
		'vendors' => array(),
	),
	
	// Define stylesheets and scripts
	'assets' => array(
		'stylesheets' => array(
			'style' => array(
				'source' => get_stylesheet_directory_uri() . '/assets/css/app.css',
				'dependencies' => FALSE,
				'version' => 'v1'
			),
			'fontawesome' => array(
				'source' => get_stylesheet_directory_uri() . '/assets/vendors/font-awesome/css/font-awesome.min.css',
				'dependencies' => FALSE,
				'version' => 'v1'
			),
		),
		'scripts' => array(
			'myjquery' => array(
				'source' => get_stylesheet_directory_uri() . '/assets/js/vendor/jquery-1.9.1.min.js',
				'dependencies' => FALSE,
				'version' => 'v1.9.1',
				'in_footer' => TRUE
			),
			'modernizr' => array(
				'source' => get_stylesheet_directory_uri() . '/assets/js/vendor/modernizr.min.js',
				'dependencies' => FALSE,
				'version' => 'v2.6.2',
				'in_footer' => FALSE
			),
			'plugins' => array(
				'source' => get_stylesheet_directory_uri() . '/assets/js/plugins.js',
				'dependencies' => array('myjquery'),
				'version' => 'v1',
				'in_footer' => TRUE
			),
			'main' => array(
				'source' => get_stylesheet_directory_uri() . '/assets/js/main.js',
				'dependencies' => array('plugins'),
				'version' => 'v1',
				'in_footer' => TRUE
			),
		)
	)
));

/* End of file functions.php */
/* Location: ./functions.php */