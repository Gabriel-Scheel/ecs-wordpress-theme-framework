<?php
namespace Ecs\WordPress;

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
     * @var WP_Theme 
     */
    private $theme_information;
    
    /**
     * Collection of post type objects.
     * 
     * @var array $post_types collection of post type objects 
     */
    public $post_types = array();

    /**
     *
     */
    public function __construct()
    {
        $this->theme_information = wp_get_theme();
    }

    /**
     * Setup.
     * 
     * @param array $config
     * @todo Develop automatic menu loading and registering of image sizes.
     */
    public function run($config=array())
    {
        
        $this->config = array_merge($this->config, $config);
        $this->writeConfig('version', $this->theme_information->Version);

        // Display admin notices
        add_action('admin_notices', array(&$this, 'printThemeErrors'), 9999);

        // Check for PHP library dependencies
        add_action('admin_notices', array(&$this, 'dependencies'));

        // Require plugins
        add_action('tgmpa_register',array(&$this, 'registerRequiredPlugins'));

        // Load assets
        add_action('wp_enqueue_scripts', array(&$this, 'registerStylesheets'));
        add_action('wp_enqueue_scripts', array(&$this, 'registerScripts'));

        add_action('wp_enqueue_scripts', array(&$this, 'enqueueStylesheets'));
        add_action('wp_enqueue_scripts', array(&$this, 'enqueueScripts'));

        // Setup wp theme features
        add_action('after_setup_theme', array(&$this, 'registerThemeFeatures'));
        
        // Load custom post types, widgets, etc. 
        add_action('init', array($this, 'loadPostTypes'));
        add_action('widgets_init', array(&$this, 'loadWidgets'));

        $this->loadSnippets();

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
        return self::getConfig($index, $this->config);
    }

    /**
     * 
     */
    public function writeConfig($index, $val='')
    {
        self::setConfig($index, $val);
    }
    
    /**
     * Handle the dot notation to set a config value to the config array
     * 
     * @param type $index
     * @param type $value
     * @return type
     */
    private function setConfig($index, $val)
    {
        $link =& $this->config;

        if (!empty($index)) {
            $keys = explode('.', $index);

            if (count($keys) == 1) {
                $this->config[$index] = $val;
            } else {
                foreach ($keys as $key) {

                    if (!isset($this->config[$key])) {
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
    private function getConfig($index, $config)
    {
        if (is_array($index) && count($index)) {
            $current_index = array_shift($index);
        }
        
        if (is_array($index) && count($index) && is_array($config[$current_index]) && count($config[$current_index])) {
            return self::getConfig($index, $this->config[$current_index]);
        } else {
            return (!empty($config[$current_index])) ? $config[$current_index] : false ;
        }
    }

    ////////////////////////////////////////////////////////////////////////////////
    // 
    // Enqueue and Load CSS/JS
    // 
    ////////////////////////////////////////////////////////////////////////////////

    /**
     * Does the actual work of registered stylesheets. Must be called before enqueue.
     */
    public function registerStylesheets()
    {
        $stylesheets = $this->config('assets.stylesheets');

        foreach ($stylesheets as $handle => $data) {
            $data = $data;
            wp_register_style($handle, $data['source'], $data['dependencies'], $data['version']);
        }
    }

    /**
     * Does the actual work of enqueing stylesheets
     */
    public function enqueueStylesheets()
    {
        $stylesheets = $this->config('assets.stylesheets');

        foreach ($stylesheets as $handle => $data) {
            wp_enqueue_style($handle, $data['source'], $data['dependencies'], $data['version']);
        }
    }

    /**
     * Does the actual work of registered scripts. Must be called before enqueue.
     */
    public function registerScripts()
    {
        $scripts = $this->config('assets.scripts');

        foreach ($scripts as $handle => $data) {
            wp_register_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
        }
    }

    /**
     * Does the actual work of enqueing scripts
     */
    public function enqueueScripts()
    {
        $scripts = $this->config('assets.scripts');

        foreach ($scripts as $handle => $data) {
            wp_enqueue_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
        }
    }

    ////////////////////////////////////////////////////////////////////////////////
    //
    // Dependency Checks
    //
    ////////////////////////////////////////////////////////////////////////////////

    /**
     * Register Required Plugins
     */
    public function registerRequiredPlugins()
    {
        if (($plugins = $this->config('dependencies.plugins')) == false) {
            return;
        }

        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */
        $config = array(
            'default_path' => '',                      // Default absolute path to pre-packaged plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
                'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
                'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
                'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
                'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
                'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
                'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
                'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
                'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
                'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
                'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
                'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
                'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
                'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            )
        );

        tgmpa( $plugins, $config );
    }

    /**
     *  Check for dependencies
     */
    public function dependencies()
    {
        // check php
        if ($this->config('dependencies.classes') !== false) {
            foreach ($this->config('dependencies.classes') as $name => $class) {
                if (!class_exists($class)) {
                    echo '<div class="error"><p>'
                    . sprintf($this->__('Please make sure that %s is installed'), $class)
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
     * Load post type classes
     */
    public function loadPostTypes()
    {
        if ($this->config('post_types') === false) {
            return;
        }

        $registry = Registry::getInstance();

        foreach ($this->config('post_types') as $post_type) {
            $post_type_path = THEME_PATH . DS . 'app' . DS . 'post_types' . DS . $post_type . '.PostType.php';

            if (!file_exists($post_type_path)) {
                $this->addThemeError(sprintf($this->__('Could not find post type: %s'), $post_type), 'theme', 'theme');
                continue;
            }

            include_once $post_type_path;

            // Init object and add to registry
            $object = new $post_type;
            $registry->set($post_type, $object);

            // Run setup on new object
            $object = $registry->get($post_type);
            $object->run();
        }

        $this->writeConfig('post_types', get_post_types(array('_builtin' => false)));
    }

    ////////////////////////////////////////////////////////////////////////////////
    //
    // Widgets
    //
    ////////////////////////////////////////////////////////////////////////////////
    
    /**
     * 
     */
    public function loadWidgets()
    {
        if ($this->config('widgets') === false) {
            return;
        }

        foreach ($this->config('widgets') as $widget) {
            $widget_path = THEME_PATH . DS . 'app' . DS . 'widgets' . DS . $widget . '.Widget.php';

            if (!file_exists($widget_path))  {
                $this->addThemeError(sprintf($this->__('Could not find widget: %s'), $widget), 'theme', 'theme');
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
    private function loadSnippets()
    {
        if ($handle = opendir(THEME_PATH . DS . 'app' . DS . 'snippets')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && strtolower(substr($file, strpos($file, '.') + 1)) == 'snippet.php') {
                    include_once THEME_PATH . DS . 'app' . DS . 'snippets' . DS . $file;
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
    public function addThemeError($message, $data_key = '', $data_value = '')
    {
        global $wp_theme_error, $wp_theme_error_code;
     
        if (!isset( $wp_theme_error_code)) {
            $theme_data = wp_get_theme();
            $name = str_replace( ' ', '', strtolower($theme_data['Name']));
            $wp_theme_error_code = preg_replace("/[^a-zA-Z0-9\s]/", '', $name);
        }
     
        if (!is_wp_error( $wp_theme_error ) || !$wp_theme_error) {
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
     * needs WP_DEBUG & WP_DEBUG_DISPLAY constants set to true.
     * Doesn't output anything if there's no error object present.
     * 
     * Adds the output to the 'shutdown' hook to render after the theme viewport is output.
     * 
     * @return 
     */
    public function printThemeErrors()
    {
        global $wp_theme_error, $wp_theme_error_code;
     
        if (!current_user_can('manage_options') || !is_wp_error($wp_theme_error)) {
            return;
        }

        $output = '';
        foreach ($wp_theme_error->errors[$wp_theme_error_code] as $error) {
            $output .= '<li>' . $error . '</li>';
        }

        if ($this->config('debug.enable_debug') === true) {
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
     * Add theme features per user config.
     */
    public function registerThemeFeatures()
    {
        $features = $this->config('theme_features');

        if (empty($features)) {
            return;
        }

        foreach ($features as $k => $v) {
            if (is_array($v)) {
                add_theme_support($k, $v);
            }
            else {
                add_theme_support($v);
            }
        }
        
    }

}

/* End of file Theme.class.php */
/* Location: ./core/Theme.class.php */