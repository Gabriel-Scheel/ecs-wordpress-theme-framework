<?php
/**
 * Misc. helper functions for our Theme.
 *
 * @category   ECS_WordPress
 * @package    Core
 * @subpackage Helpers
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */
if ( !function_exists( 'of_get_option' ) ) {
function of_get_option($name, $default = false) {
    
    $optionsframework_settings = get_option('optionsframework');
    
    // Gets the unique option id
    $option_name = $optionsframework_settings['id'];
    
    if ( get_option($option_name) ) {
        $options = get_option($option_name);
    }
        
    if ( isset($options[$name]) ) {
        return $options[$name];
    } else {
        return $default;
    }
}
}

/**
 * Retrieve object from class registry
 * 
 * @param mixed $object
 */
function ecs_get_instance($name)
{
    $registry = Ecs\Core\Registry::getInstance();
    return $registry->get($name);
}

/**
 * Wrapper function for registering an object to the class registry
 */
function ecs_register_object($name, $object)
{
    $registry = Ecs\Core\Registry::getInstance();
    $registry->set($name, $object);
}

/**
 * shorthand pretty version of print_r
 * 
 * @param mixed $var
 */
if (!function_exists('pr'))
{
    function pr($var=NULL)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

/**
 * debug
 * 
 * @param mixed $var
 */
if (!function_exists('debug'))
{
    function debug($var=NULL)
    {
        $backtrace = debug_backtrace();
        $file = '.'.str_replace($_SERVER['DOCUMENT_ROOT'], '', $backtrace[0]['file']);
        echo '<div class="__theme_debug">';
        echo '<p>DEBUG: file: '.$file.' - line: '.$backtrace[0]['line'].'</p>';
        pr($var);
        echo '</div>';
    }
}

/**
 * Return a url to a page (or custom post type) by title.
 *
 * @param string $page_title
 * @return string
 */
function ecs_page_url($page_title = '')
{
    return get_permalink(get_page_by_title($page_title));
}

/**
 * Return a url to a page (or custom post type) by slug.
 *
 * @param string $page_slug
 * @param string $type
 * @return string
 */
function ecs_page_url_by_slug($page_slug = '', $type = 'page')
{
    global $wpdb;

    $page = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name = '{$page_slug}' AND post_type = '{$type}'");
    return get_permalink($page);
}

/**
 * Render a date "properly"
 */
function ecs_date($format = 'Y-m-d H:i:s', $timestamp = false)
{
    // Set correct publish date
    $dt = new \DateTime('now', new \DateTimeZone(ECS_TIMEZONE));
    if ($timestamp) {
        $dt->setTimestamp($timestamp);
    }
    return $dt->format($format);
}

/**
 *
 */
function ecs_lang($str = '')
{
    $Theme = ecs_get_instance('Theme');
    return $Theme->__($str);
}

/**
 *
 */
function ecs_render_partial($partial, $data = array())
{
    global $wp_query;
    $file = 'app/partials' . DS . $partial . '.php';
    $wp_query->query_vars['partialData'] = $data;
    locate_template($file, true, false);
}

/**
 *
 */
function ecs_json_response($content = '')
{
    if (!empty($content)) {
        header('Content-type: application/json');
        die(json_encode($content));
    }
}

/* End of file helpers.php */
/* Location: ./app/Ecs/helpers.php */
