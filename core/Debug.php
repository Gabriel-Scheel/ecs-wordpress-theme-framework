<?php
/**
 * Debug
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Debug
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/**
 * Debug Class
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Debug
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
final class Debug
{
	
	/**
	 * Debug settings.
	 * 
	 * @var array $settings
	 */
	public $settings = array(
		'enable_debug' => FALSE, // set to true to include debug code
		'enable_wienre' => FALSE, // set to true to include weinre js in theme head
		'enable_pixel_perfect' => FALSE // set to true to include pixel perfect
	);
	
	/**
	 *
	 */
	public function __construct()
	{
		
	}

	/**
	 * 
	 */
	public function run($config = array())
	{
		$this->settings = array_merge($this->settings, $config);
		
		// Add debug code
		if ($this->settings['enable_debug'] === TRUE)
		{
			// weinre mobile debugger
			if ($this->settings['enable_wienre'] === TRUE)
			{
				add_action('wp_head', array(&$this, 'add_debug_js'));
			}
			
			// pixel perfect
			if ($this->settings['enable_pixel_perfect'] === TRUE)
			{
				add_action('wp_enqueue_scripts', array(&$this, 'add_pixel_perfect'));
				add_action('wp_head', array(&$this, 'output_pixel_perfect'));
			}
		}
	}
	
	/**
	 * Add javascript debug code to head
	 */
	public function add_debug_js()
	{
		echo '<!-- Weinre Debug -->';
		echo '<script src="'.home_url().':8080/target/target-script-min.js#'.$this->theme->config('name').'" defer="defer"></script>';
		echo '<!-- /Weinre Debug -->';
	}
	
	/**
	 * Enqueue pixel perfect dependencies
	 */
	public function add_pixel_perfect()
	{
		$scripts = (object) array(
			'pixelperfect-jquery-draggable' => (object) array(
				'source' => '//code.jquery.com/ui/1.10.0/jquery-ui.min.js',
				'dependencies' => array('jquery'),
				'version' => '1.10.0',
				'in_footer' => TRUE
			),
		);
		
		foreach ($scripts as $handle => $data)
		{
			wp_register_script($handle, $data->source, $data->dependencies, $data->version, $data->in_footer);
		}
		
		wp_enqueue_script('pixelperfect-jquery-draggable');
		
		$pp_dir = VENDOR_PATH . '/pixelperfect/';
		
		if (!is_dir($pp_dir . 'assets/img/layouts'))
		{
			if (!mkdir($pp_dir . 'assets/img/layouts', 0777))
			{
				wp_die('Could not create pixel perfect layouts directory, plese create the following directory and set permissions to writable:<br />pixelperfect/assets/img/layouts');
			}
		}
		
		// Make sure uploads directory is writable
		if (!is_writable($pp_dir . 'assets/img/layouts'))
		{
			wp_die('Pixel perfect layouts directory is not writable');
		}
		
		// Make sure json file store is writable
		if (!is_writable($pp_dir . 'assets/json/layouts.json'))
		{
			wp_die('Pixel perfect layouts.json is not writable');
		}
	}
	
	/**
	 * Add pixel perfect init script to head
	 */
	public function output_pixel_perfect()
	{
		$pp_script = '
			<script type="text/javascript" id="pixelperfect-source-code" src="'.get_stylesheet_directory_uri() . '/vendors/pixelperfect/pixelperfect.js"></script>
			<script type="text/javascript" defer="defer"> 
			(function($){
				$(document).ready(function() {
					$("body").pixelPerfect();
				});
			})(jQuery);
			</script> ';
		
		echo $pp_script;
	}
}

/* End of file Debug.php */
/* Location: ./lib/Debug.php */