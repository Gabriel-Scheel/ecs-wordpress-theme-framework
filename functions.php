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

require_once 'core/bootstrap.php';

// Setup Theme
$theme = new Ecs\WordPress\Theme();

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

	// Custom Image Sizes
	'image_sizes' => array(
		'cover_large' => array(
			'width' => 1920,
			'height' => 1080,
			'crop' => false
		),
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

$registry = \Ecs\WordPress\Registry::getInstance();
$registry->set('Theme', $theme);

#add_action('wp_footer', function(){ global $theme; pr($theme); });

/* End of file functions.php */
/* Location: ./functions.php */