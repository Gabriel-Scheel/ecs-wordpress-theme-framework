<?php
/**
 * Define and Register custom Metaboxes
 *
 * @category   ECS_WordPress
 * @package    Core
 * @subpackage Metaboxes
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

if (is_admin() && class_exists('RW_Meta_Box'))
{
    $metaboxes = array();

    // Cover Metaboxes
    $metaboxes[] = array(
        'id' => 'mb_fields',
        'title' => 'Article Data',
        'pages' => array('cover'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'Image',
                'id' => 'article_image',
                'type' => 'plupload_image',
                'clone' => FALSE,
                'max_file_uploads' => 1,
            ),
        )
    );

    foreach ($metaboxes as $mb) {
        new \RW_Meta_Box($mb);
    }
}