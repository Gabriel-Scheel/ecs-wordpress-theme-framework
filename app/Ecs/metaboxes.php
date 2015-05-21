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

if (is_admin() && class_exists('RW_Meta_Box')) {
    $metaboxes = array();

    // Cover Metaboxes
    $metaboxes[] = array(
        'id' => 'mb_cover_fields',
        'title' => 'Cover Custom Fields',
        'pages' => array('cover'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'Cover Image',
                'description' => \Ecs\Helpers\lang('Primary cover image, should be WxH'),
                'id' => 'article_image',
                'type' => 'plupload_image',
                'clone' => false,
                'max_file_uploads' => 1,
            ),
        )
    );

    foreach ($metaboxes as $mb) {
        new \RW_Meta_Box($mb);
    }
}

/* End of file metaboxes.php */
/* Location: ./app/Ecs/metaboxes.php */
