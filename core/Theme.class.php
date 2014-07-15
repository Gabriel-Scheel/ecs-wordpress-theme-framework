<?php
/**
 * Wordpress Theme Class
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Theme
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/**
 * Theme Class
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Theme
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class Theme
{

	/**
	 * Array of config options for the theme.
	 *
	 * Config can be multidim, which can be accessed using dotnotation
	 * consider the following config array: array('multi'=>array('dim'=>array('option'=>'val')))
	 * can access option by using:
	 * eg: $this->config('multi.dim.option').
	 * 
	 * @var array
	 */
	private $config = array(
		'name' => 'mytheme',
		'post_types' => array()
	);

	/**
	 * WP Theme Information.
	 * 
	 * @var type 
	 */
	private $_theme_information = FALSE;
	
	/**
	 * Collection of post type objects.
	 * 
	 * @var array $post_types collection of post type objects 
	 */
	public $post_types = array();

	/**
	 * Setup.
	 * 
	 * @param array $config
	 * @todo Develop automatic menu loading and registering of image sizes.
	 */
	public function run($config=array())
	{
		$this->_theme_information = $this->_get_theme_information();
		
		$this->config = array_merge($this->config, $config);
		$this->write_config('version', $this->_theme_information->Version);

		$this->_register_error_handler();

		$this->_check_dependencies();

		$this->_register_post_types();

		$this->_register_assets();
 		$this->_enqueue_assets();
		
		$this->_register_widgets();

		$this->_load_snippets();

		$this->_register_theme_features();

	}

	/**
	 * 
	 * @return type
	 */
	private function _get_theme_information()
	{
		return wp_get_theme();
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	// Misc, Helpers
	//
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * @param string  language string to return
	 */
	public function __($str='')
	{
		return __($str, $this->config('name'));
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	// Theme Config
	//
	////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Get a config value 
	 * 
	 * @param type $index
	 * @return type
	 */
	public function config($index)
	{
		$index = explode('.', $index);
		return $this->_get_config($index, $this->config);
	}

	/**
	 * 
	 */
	public function write_config($index, $val='')
	{
		$this->_set_config($index, $val);
	}
	
	/**
	 * Handle the dot notation to set a config value to the config array
	 * 
	 * @param type $index
	 * @param type $value
	 * @return type
	 */
	private function _set_config($index, $val)
	{
        $link =& $this->config;

        if (!empty($index)) {
            $keys = explode('.', $index);

            if (count($keys) == 1)
            {
            	$this->config[$index] = $val;
            }
            else
            {
	            foreach ($keys as $key) {

	                if (!isset($this->config[$key]))
	                {
	                    $link[$key] = array();
	                }

	                $link =& $link[$key];
	            }

	            $link = $val;
            }
        }
	}
	
	/**
	 * Handle the dot notation to get a config value from the config array
	 * 
	 * @param type $index
	 * @param type $value
	 * @return type
	 */
	private function _get_config($index, $config)
	{
		if (is_array($index) && count($index))
		{
			$current_index = array_shift($index);
		}
		
		if (is_array($index) && count($index) && is_array($config[$current_index]) && count($config[$current_index]))
		{
			return self::_get_config($index, $this->config[$current_index]);
		}
		else
		{
			return (!empty($config[$current_index])) ? $config[$current_index] : FALSE ;
		}
	}

	////////////////////////////////////////////////////////////////////////////////
	// 
	// Enqueue and Load CSS/JS
	// 
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * Set hooks to register assets. 
	 *
	 * Assets have to registered before they are enqueued. 
	 */
	private function _register_assets()
	{
		add_action('wp_enqueue_scripts', array(&$this, 'register_stylesheets'));
		add_action('wp_enqueue_scripts', array(&$this, 'register_scripts'));
	}

	/**
	* Sets hooks to enqueue assets
	*
	* Enqueue registered assets, and handle dependencies. 
	*/
	private function _enqueue_assets()
	{
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_stylesheets'));
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
	}

	/**
	 * Helper method to return stylesheet object from theme config.
	 * 
	 * @return object 
	 */
	private function _get_stylesheets()
	{
		return $this->config('assets.stylesheets');
	}

	/**
	 * Helper method to return scripts object from theme config.
	 * 
	 * @return object
	 */
	private function _get_scripts()
	{               
		return $this->config('assets.scripts');
	}

	/**
	 * Does the actual work of registered stylesheets. Must be called before enqueue.
	 */
	public function register_stylesheets()
	{
		$stylesheets = $this->_get_stylesheets();

		foreach ($stylesheets as $handle => $data)
		{
			$data = $data;
			wp_register_style($handle, $data['source'], $data['dependencies'], $data['version']);
		}
	}

	/**
	 * Does the actual work of registered scripts. Must be called before enqueue.
	 */
	public function register_scripts()
	{
		$scripts = $this->_get_scripts();

		foreach ($scripts as $handle => $data)
		{
			wp_register_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
		}
	}

	/**
	 * Does the actual work of enqueing stylesheets
	 */
	public function enqueue_stylesheets()
	{
		$stylesheets = $this->_get_stylesheets();

		foreach ($stylesheets as $handle => $data)
		{
			wp_enqueue_style($handle, $data['source'], $data['dependencies'], $data['version']);
		}
	}

	/**
	 * Does the actual work of enqueing scripts
	 */
	public function enqueue_scripts()
	{
		$scripts = $this->_get_scripts();

		foreach ($scripts as $handle => $data)
		{
			wp_enqueue_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
		}
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	// Dependency Checks
	//
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * Setup hooks for checking dependencies
	 */
	private function _check_dependencies()
	{
		add_action('admin_notices', array(&$this, 'dependencies'));
	}

	/**
	 *  Check for dependencies
	 */
	public function dependencies()
	{
		// check php
		if ($this->config('dependencies.classes') !== FALSE)
		{
			foreach ($this->config('dependencies.classes') as $name => $class)
			{
				if (!class_exists($class))
				{
					echo '<div class="error"><p>'
					. sprintf($this->__('Please make sure that %s is installed'), $class)
					. '</p></div>';
				}
			}
		}

		// check plugins
		if ($this->config('dependencies.plugins') !== FALSE)
		{
			foreach ($this->config('dependencies.plugins') as $name => $plugin)
			{
				if (!is_plugin_active($plugin))
				{
					echo '<div class="error"><p>'
					. sprintf($this->__('This theme depends on the Plugin %s being installed &amp; activated'), $name)
					. '</p></div>';
				}
			}
		}

		// check vendors, load if found
		if ($this->config('dependencies.vendors') !== FALSE)
		{
			foreach ($this->config('dependencies.vendors') as $name => $vendor)
			{
				if (!file_exists(THEME_PATH . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendor))
				{
					echo '<div class="error"><p>'
					. sprintf($this->__('This theme depends on the Vendor package %s being present'), $name)
					. '</p></div>';
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	// Custom Post Types
	//
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * Set hooks for new post types
	 */
	private function _register_post_types()
	{
		add_action('init', array($this, 'register_post_types'));
	}

	/**
	 * Register custom post types
	 */
	public function register_post_types()
	{
		if ($this->config('post_types') === FALSE)
		{
			return;
		}

		foreach ($this->config('post_types') as $post_type)
		{
			$post_type_path = THEME_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'post_types' . DIRECTORY_SEPARATOR . $post_type . '.PostType.php';

			if (!file_exists($post_type_path))
			{
				$this->add_theme_error(sprintf($this->__('Could not find post type: %s'), $post_type), 'theme', 'theme');
				continue;
			}

			require_once $post_type_path;

			$this->post_types[$post_type] = new $post_type();
			$this->post_types[$post_type]->run();
		}

		$this->write_config('post_types', get_post_types(array('_builtin' => FALSE)));
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	// Widgets
	//
	////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * 
	 */
	private function _register_widgets()
	{
		add_action('widgets_init', array(&$this, 'load_widgets'));
	}
	
	/**
	 * 
	 */
	public function load_widgets()
	{
		if ($this->config('widgets') === FALSE)
		{
			return;
		}

		foreach ($this->config('widgets') as $widget)
		{
			$widget_path = THEME_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . $widget . '.Widget.php';

			if (!file_exists($widget_path))
			{
				$this->add_theme_error(sprintf($this->__('Could not find widget: %s'), $widget), 'theme', 'theme');
				continue;
			}

			include_once $widget_path;
			register_widget($widget);
		}
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	// Load code snippets. 
	// 
	// Code snippets are collections of functions to extend functionality of the theme 
	// without bloating the core libraries. Sometimes you just need to include a
	// st of functions
	//
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * 
	 */
	private function _load_snippets()
	{
		if ($handle = opendir(THEME_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'snippets'))
		{
			while (FALSE !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != ".." && strtolower(substr($file, strpos($file, '.') + 1)) == 'snippet.php')
				{
					include_once THEME_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'snippets' . DIRECTORY_SEPARATOR . $file;
				}
			}
			closedir($handle);
		}
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	// Error Handling
	//
	////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Hook into shutdown to display errors
	 */
	private function _register_error_handler()
	{
		add_action('admin_notices', array(&$this, 'print_theme_errors'), 9999);
	}

	/**
	 * Adds theme specific messages to the global theme WP_Error object.
	 * 
	 * Takes the theme name as $code for the WP_Error object.
	 * Merges old $data and new $data arrays @uses wp_parse_args().
	 * 
	 * @param (string) $message
	 * @param (mixed) $data_key
	 * @param (mixed) $data_value
	 * @return void
	 */
	public function add_theme_error($message, $data_key = '', $data_value = '')
	{
		global $wp_theme_error, $wp_theme_error_code;
	 
		if (!isset( $wp_theme_error_code))
		{
			$theme_data = wp_get_theme();
			$name = str_replace( ' ', '', strtolower($theme_data['Name']));
			$wp_theme_error_code = preg_replace("/[^a-zA-Z0-9\s]/", '', $name);
		}
	 
		if (!is_wp_error( $wp_theme_error ) || !$wp_theme_error)
		{
			$data[$data_key] = $data_value;
			$wp_theme_error = new WP_Error($wp_theme_error_code, $message, $data);
			return $wp_theme_error;
		}
	 
		// merge old and new data
		$old_data = $wp_theme_error->get_error_data($wp_theme_error_code);
		$new_data[$data_key] = $data_value;
		$data = wp_parse_args($new_data, $old_data);
	 
		return $wp_theme_error->add($wp_theme_error_code, $message, $data);
	}
	 
	 
	/**
	 * Prints the error messages added to the global theme specific WP_Error object
	 * 
	 * Only displays for users that have 'manage_options' capability,
	 * needs WP_DEBUG & WP_DEBUG_DISPLAY constants set to TRUE.
	 * Doesn't output anything if there's no error object present.
	 * 
	 * Adds the output to the 'shutdown' hook to render after the theme viewport is output.
	 * 
	 * @return 
	 */
	public function print_theme_errors()
	{
		global $wp_theme_error, $wp_theme_error_code;
	 
		if (!current_user_can('manage_options') || !is_wp_error($wp_theme_error))
		{
			return;
		}

		$output = '';
		foreach ($wp_theme_error->errors[$wp_theme_error_code] as $error)
		{
			$output .= '<li>' . $error . '</li>';
		}

		if ($this->config('debug.enable_debug') === TRUE)
		{
			echo '<div class="__theme_errors"><h4>'.$this->__('Theme Errors & Warnings').'</h4><ul>';
			echo $output;
			echo '</ul></div>';
		}
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	// Theme Support, Theme Features, Theme...
	//
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * Set hook to register theme features.
	 */
	private function _register_theme_features()
	{
		add_action('after_setup_theme', array(&$this, 'register_theme_features'));
	}

	/**
	 * Add theme features per user config.
	 */
	public function register_theme_features()
	{
		$features = $this->config('theme_features');

		if (empty($features))
		{
			return;
		}

		foreach ($features as $k => $v)
		{
			if (is_array($v))
			{
				add_theme_support($k, $v);
			}
			else
			{
				add_theme_support($v);
			}
		}
		
	}

}

/* End of file Theme.php */
/* Location: ./core/Theme.php */