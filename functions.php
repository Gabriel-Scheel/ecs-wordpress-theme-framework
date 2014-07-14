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

require 'core/common.php';

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
		'enable_debug' => TRUE
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