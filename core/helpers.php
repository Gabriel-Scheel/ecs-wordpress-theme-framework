<?php
/**
 * Misc. helper functions for our Theme.
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Helpers
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/**
 * shorthand for print_r and some wrapper html.
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
 * debug.
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

/* End of file functions.php */
/* Location: ./core/functions.php */