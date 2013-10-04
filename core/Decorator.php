<?php
/**
 * Theme Decorator
 *
 * PHP version 5
 *
 * LICENSE: 
 * 
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Decorator
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/**
 * Decorator Class
 *
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage Decorator
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class Decorator
{

	/**
	 *
	 */
	public function __construct()
	{
		
	}

	/**
	 * Setup class
	 */
	public function run()
	{
		
	}

	/**
	 * Return only the end portion of a full mime type.
	 * 
	 * eg: 'application/pdf' would return 'pdf'
	 * 
	 * @param string $mime
	 */
	public function nice_mime($mime = '')
	{
		$mime = explode('/', $mime);
		$mime = end($mime);

		return $mime;
	}

	/**
	 * Convert bytes to humanized size format. 
	 * 
	 * @param int $bytes
	 * @return string
	 */
	public function nice_filesize($bytes = 0)
	{
		if ($bytes > 0)
		{
			$unit = intval(log($bytes, 1024));
			$units = array('B', 'KB', 'MB', 'GB');

			if (array_key_exists($unit, $units))
			{
				return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
			}
		}

		return $bytes;
	}
}

/* End of file Decorator.php */
/* Location: ./lib/Decorator.php */