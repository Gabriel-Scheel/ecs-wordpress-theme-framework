<?php

/**
 *
 */
class Options 
{

	/**
	 *
	 */
	public $option_fields = array();

	/**
	 *
	 */
	public $options = array();

	public $page_name = 'ecs-theme-options';

	/**
	 *
	 */
	public function __construct()
	{

	}

	/**
	 *
	 */
	public function run($params=array())
	{
		if (empty($params))
		{
			return;
		}

		foreach ($params as $option_set => $set)
		{
			$this->option_fields[$option_set] = $set;
		}

		$this->_theme_options_init();
		$this->_register_admin_menus();
	}

	/**
	 *
	 */
	private function _register_admin_menus()
	{
		add_action('admin_menu', array(&$this, 'register_admin_menus'));
	}

	/**
	 *
	 */
	public function register_admin_menus()
	{
		add_options_page(
			'ECS Theme Options',  // Page Title for Options Page
			'ECS Theme Options',  // Menu Title
			'manage_options',     // Capabilities
			$this->page_name,     // Menu Slug
			array(&$this, 'render_theme_options') // Callback to Render Options Output
		);
	}

	/**
	 *
	 */
	private function _theme_options_init()
	{
		add_action('admin_init', array(&$this, 'theme_options_init'));
	}

	/**
	 *
	 */
	public function theme_options_init()
	{
		$Inflector = new Inflector();

		foreach ($this->option_fields as $section => $set)
		{
			// Load option values from database
			$this->options[$section] = get_option($section); //ecs-theme-options

			// Register Setting Group
			// This is the group that all of our options will be saved to
			// It is a serialized array of name value key pairs
			register_setting(
				$section, // Settings Group Name
				$section  // Name of the Setting field group name - ie: <input name="ecs-theme-options[option_name]"
			);	

			add_settings_section(
				$section,                             // ID Attribute
				$Inflector->titleize($section),       // Title of the Section
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
	 * 
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
			<?php screen_icon('themes'); ?> <h2><?php echo $theme->__('ECS Theme Options'); ?></h2>

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
	 * 
	 */
	public function render_section()
	{
		echo '<hr />';
	}

	/**
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
	 * 
	 */
	private function render_radio_field($args, $value)
	{
		if (!is_array($args['value']))
		{
			return;
		}

		foreach ($args['value'] as $k => $v)
		{
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
		foreach ($args['value'] as $k => $v)
		{
			$selected  = ($k != "" && $k == $value) ? 'selected="selected"' : false ;

			echo '<option 
					value="' . $k . '"
					' . $selected . '>
					'. $v . '
					</option>';
		}
		echo '</select>';
	}
}