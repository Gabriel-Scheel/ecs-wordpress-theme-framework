<?php
/**
 * Contextual Help
 *
 * PHP version 5
 *
 * LICENSE: 
 * 
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Help
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/**
 * Help Class
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Help
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class Help
{
	/**
	 *
	 */
	public function __construct(Theme &$theme)
	{
		$this->theme = $theme;
	}

	/**
	 * Setup class
	 */
	public function run()
	{
		add_filter('contextual_help', array(&$this, 'setup_help'), 10, 3);
	}

	/**
	 *
	 * @param array   
	 * @param object  
	 * @param string  
	 */
	public function setup_help($contextual_help, $screen_id)
	{
		if (!is_array($this->theme->config('post_types')))
		{
			return;
		}
		
		$screen_id = str_replace('edit-', '', $screen_id);
		
		if (in_array($screen_id, $this->theme->config('post_types')))
		{
			$contextual_help = $this->load($screen_id);
		}

		return $contextual_help;
	}

	/**
	 * Attempt to load help file
	 *
	 * @param string  name of the help file to load, minus the file extension
	 */
	public function load($content)
	{
		if (file_exists(HELP_PATH . DIRECTORY_SEPARATOR . $content . '.php'))
		{
			$content = file_get_contents(HELP_PATH . DIRECTORY_SEPARATOR . $content . '.php');
			return $content;
		}
		else
		{
			$this->theme->add_theme_error(sprintf(__('Could not load help file: %s.php'), $content), 'theme_error', $this->theme->config('name'));
		}
	}
}

/* End of file Help.php */
/* Location: ./lib/Help.php */