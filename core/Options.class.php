<?php
/**
 * Wordpress Options Class
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Option
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 * @deprecated - Using the WP Options Framrwork now
 */

/**
 * Options Class
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Options
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class Options 
{

	/**
	 * @var array user defined option fields
	 */
	public $option_fields = array();

	/**
	 * @var array option values stored in database
	 */
	public $options = array();

	/**
	 * @var string url slug of the options page
	 */
	public $page_name = 'ecs-theme-options';

	/**
	 * @var string page name of the options page
	 */
	public $options_page_title = 'ECS Theme Options';

	/**
	 * @var object Inflector
	 */
	public $Inflector;

	/**
	 * 
	 */
	public function __construct()
	{

	}

	/**
	 * Set hooks. Loads option values from database. Sets up Options object. 
	 * 
	 * @param array $option_fields user defined option fields
	 */
	public function run($option_fields=array())
	{
		if (empty($option_fields))
		{
			return;
		}

		$this->Inflector = new Inflector();

		foreach ($option_fields as $option_set => $set)
		{
			// Store user defined option fields
			$this->option_fields[$option_set] = $set;

			// Load option values from database
			$this->options[$option_set] = get_option($option_set);
		}

		add_action('admin_init', array(&$this, 'theme_options_init'));
		
		if (is_admin())
		{
			add_action('admin_menu', array(&$this, 'register_admin_menus'));
		}
	}

	/**
	 * Reteive option value from option set
	 *
	 * The option values are retreived from the database on load, and stored in $this->options. 
	 * This helper method will allow you to retreive an option value by key and option name. 
	 *
	 * usage: $value = $options->get('option-set', 'option-value');
	 *
	 * @param string $key set to retrieve option from
	 * @param string $option option value to retrieve
	 * @return string
	 * @throws Exception
	 *
	 */
	public function get($key, $option)
	{
		try
		{
			if (!is_array($this->options))
			{
				throw new Exception('Did not load options from database. Have you run Options::run()?');
			}

			if (!array_key_exists($key, $this->options))
			{
				throw new Exception('Option set (' . $key . ') does not exist in options array');
			}

			if (!array_key_exists($option, $this->options[$key]))
			{
				throw new Exception('Option value (' . $option . ') does not exist in options set');
			}

			return $this->options[$key][$option];
		}
		catch (Exception $e)
		{
			wp_die($e->getMessage());
		}
	}

	/**
	 * Register Admin Menu
	 *
	 * @return void
	 */
	public function register_admin_menus()
	{
		add_options_page(
			$this->options_page_title,  // Page Title for Options Page
			$this->options_page_title,  // Menu Title
			'manage_options',     // Capabilities
			$this->page_name,     // Menu Slug
			array(&$this, 'render_theme_options') // Callback to Render Options Output
		);
	}

	/**
	 * Register users custom defined theme options and sections
	 *
	 * @return void
	 */
	public function theme_options_init()
	{
		foreach ($this->option_fields as $section => $set)
		{

			// Register Setting Group
			// This is the group that all of our options will be saved to
			// It is a serialized array of name value key pairs
			register_setting(
				$section, // Settings Group Name
				$section  // Name of the Setting field group name - ie: <input name="ecs-theme-options[option_name]"
			);	

			add_settings_section(
				$section,                             // ID Attribute
				$this->Inflector->titleize($section),       // Title of the Section
				array(&$this, 'render_section'),      // Callback to render output of section
				$this->page_name                      // Page to display section on
			);

			foreach ($set as $option)
			{
				add_settings_field(
					$option['label'], // ID Attribute for fields
					$option['label'],                  // Title of the field
					array(&$this, 'render_field'),     // Callback to render field
					$this->page_name,                  // Page to display field on
					$section,                          // Section to render field in
					array_merge($option, array(
						'section' => $section
					))                                 // Args that are passed to the callback
				);
			}
		}
	}

	/**
	 * Render Theme Options page
	 *
	 * @return void
	 */
	public function render_theme_options()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die('You do not have sufficient permissions to access this page.');
		}

		global $theme;
		?>
		<div class="wrap">
			<?php screen_icon('themes'); ?> <h2><?php echo $this->options_page_title; ?></h2>

			<form method="post" action="options.php">
				<?php foreach ($this->option_fields as $section => $name): ?>
					<?php settings_fields($section); ?>
					<?php do_settings_sections($section); ?>
				<?php endforeach; ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render theme option section header
	 *
	 * @return void
	 */
	public function render_section()
	{
		echo '<hr />';
	}

	/**
	 * Render field meta function
	 *
	 * @param array $args field data based in via callback
	 * @return void
	 *
	 */
	public function render_field($args)
	{
		if (!empty($this->options[$args['section']][$args['name']]))
		{
			$value = $this->options[$args['section']][$args['name']];
		}
		else
		{
			$value = '';
		}

		$do_render_field = "render_{$args['type']}_field";

		if (!method_exists($this, $do_render_field))
		{
			wp_die('Field type does not exist, cannot render...');
		}

		$this->$do_render_field($args, $value);

	}

	/**
	 * Render text field
	 *
	 * @param array $args field data based in via callback
	 * @param string $value field value
	 * @return void
	 *
	 */
	private function render_text_field($args, $value)
	{
		echo '<input 
				id="' . $args['name'] . '" 
				type="text" 
				name="' . $args['section'] . '[' . $args['name'] . ']" 
				value="' . $value . '" 
				placeholder="">';
	}

	/**
	 * Render textarea field
	 *
	 * @param array $args field data based in via callback
	 * @param string $value field value
	 * @return void
	 *
	 */
	private function render_textarea_field($args, $value)
	{
		echo '<textarea 
				name="' . $args['section'] . '[' . $args['name'] . ']"
				rows="5" 
				cols="50" 
				id="' . $args['name'] . '" >' . $value . '</textarea>';
	}

	/**
	 * Render checkbox field
	 *
	 * @param array $args field data based in via callback
	 * @param string $value field value
	 * @return void
	 *
	 */
	private function render_checkbox_field($args, $value)
	{
		$checked  = ($value != '') ? 'checked="checked"' : false ;

		echo '<input 
				id="' . $args['name'] . '" 
				type="checkbox" 
				name="' . $args['section'] . '[' . $args['name'] . ']" 
				value="' . $args['value'] . '" 
				' . $checked . '
				placeholder="">';
	}

	/**
	 * Render radio fields with labels
	 *
	 * @param array $args field data based in via callback
	 * @param string $value field value
	 * @return void
	 *
	 */
	private function render_radio_field($args, $value)
	{
		if (!is_array($args['value']))
		{
			return;
		}

		foreach ($args['value'] as $v)
		{
			$k = $this->Inflector->underscore($v);
			$selected  = ($k == $value) ? 'checked="checked"' : false ;

			echo '<input 
					id="' . $args['name'] . '" 
					type="radio" 
					name="' . $args['section'] . '[' . $args['name'] . ']" 
					value="' . $k . '"
					' . $selected . '
					placeholder="">';
			echo '&nbsp;<label>' . $v . '</label><br>';
		}
	}

	/**
	 * Render select field with options
	 *
	 * @param array $args field data based in via callback
	 * @param string $value field value
	 * @return void
	 *
	 */
	private function render_select_field($args, $value)
	{
		if (!is_array($args['value']))
		{
			return;
		}

		echo '<select name="' . $args['section'] . '[' . $args['name'] . ']">';
		echo '<option value="">- Select -</option>';
		foreach ($args['value'] as $v)
		{
			$k = $this->Inflector->underscore($v);
			$selected  = ($k == $value) ? 'selected="selected"' : false ;

			echo '<option 
					value="' . $k . '"
					' . $selected . '>
					'. $v . '
					</option>';
		}
		echo '</select>';
	}
}

/* End of file Options.class.php */
/* Location: ./core/Options.class.php */