<?php
/**
 * URL Helpers
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * @category   ECS_WP_ThemeCore
 * @package    Snippets
 * @subpackage Urls
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/**
 * Return a url to a page (or custom post type) by title.
 *
 * @param string $page_title
 * @return string
 */
function ecsPageUrl($page_title='')
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
function ecsPageUrlBySlug($page_slug='', $type='page')
{
	global $wpdb;

	$page = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name = '{$page_slug}' AND post_type = '{$type}'");
	return get_permalink($page);
}

/* End of file urls.snippet.php */
/* Location: ./app/snippets/urls.snippet.php */