<?php
namespace Ecs\Core;

/**
 *
 * The PostType class is mean to simply define a post type in WordPress.
 *
 */
class Shortcode
{
    /*
     * @param string
     */
    public $shortcode_tag = 'ecs_shortcode_example';

    /**
     * Set hooks for shortcode
     */
    public function __construct()
    {
        add_shortcode($this->shortcode_tag, array($this, 'shortcodeHandler'));

        if (is_admin()) {
            add_action('admin_head', array(&$this, 'adminHead'));
            add_action('admin_enqueue_scripts', array(&$this, 'adminEnqueueScripts'));
        }
    }

    /**
     * admin_head
     * calls your functions into the correct filters
     * @return void
     */
    public function adminHead()
    {
        // check user permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
     
        // check if WYSIWYG is enabled
        if ('true' == get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', array($this ,'mceExternalPlugins'));
            add_filter('mce_buttons', array($this, 'mceButtons'));
        }
    }

    /**
     * getAssetsUrl
     * Return URL for asset $file
     *
     * @param string $file
     */
    private function getAssetsUrl($file)
    {
        // We want the directory of the derived class, not the inherited class
        // Path needs to be relative to theme dir
        $reflector = new \ReflectionClass(get_class($this));
        $path = str_replace(get_stylesheet_directory(), '', dirname($reflector->getFileName()));
        $url = get_stylesheet_directory_uri() . $path . '/' . $file;
        return $url;
    }

    /**
     * mceExternalPlugins
     * Adds our tinymce plugin
     * @param  array $plugin_array
     * @return array
     */
    public function mceExternalPlugins($plugin_array)
    {
        $plugin_array[$this->shortcode_tag] = $this->getAssetsUrl('mce-button.js');
        return $plugin_array;
    }

    /**
     * mce_buttons
     * Adds our tinymce button
     * @param  array $buttons
     * @return array
     */
    public function mceButtons($buttons)
    {
        array_push($buttons, $this->shortcode_tag);
        return $buttons;
    }
 
    /**
     * admin_enqueue_scripts
     * Used to enqueue custom styles
     * @return void
     */
    public function adminEnqueueScripts()
    {
        wp_enqueue_style($this->shortcode_tag . '_button_style', $this->getAssetsUrl('mce-button.css'), __FILE__);
    }
}