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

$theme = new Theme();
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

	// Custom Theme Options
	'theme_options' => array(
		// The option section name. 
		'ecs-theme-options' => array( 
			// Field Definition - Text
			array(
				'label' => 'Text',
				'name' => 'my-option',
				'type' => 'text',
			),
			// Field Definition - Textarea
			array(
				'label' => 'Textarea',
				'name' => 'my-option-textarea',
				'type' => 'textarea',
			),
			// Field Definition - Checkbox
			array(
				'label' => 'Checkbox',
				'name' => 'my-option-checkboxes',
				'type' => 'checkbox',
				'value' => 'Yes'
			),
			// Field Definition - Checkbox
			array(
				'label' => 'Radio',
				'name' => 'my-option-radio',
				'type' => 'radio',
				'value' => array(1, 2, 3)
			),
			// Field Definition - Checkbox
			array(
				'label' => 'Select',
				'name' => 'my-option-select',
				'type' => 'select',
				'value' => array('Check it check it check it', 2, 3)
			),
		),
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
	// For plugins, include the path (relative to the plugin directory) to the plugin file
	// Classes are PHP classes your theme depends on
	// Vendors are 3rd party libraries your theme depends on. For example, this framework depends on the MetaBox library
	'dependencies' => array(
		'plugins' => array(
			'Google Analytics' => 'googleanalytics/googleanalytics.php'
		),
		'classes' => array(
			'Imagick',
		),
		'vendors' => array(
			'MetaBox' => 'meta-box/meta-box.php'
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