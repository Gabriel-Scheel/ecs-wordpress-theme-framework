<?php
/**
 * Wordpess Theme init
 */

require 'app/Ecs/common.php';

// Init a theme object by passing in a unique name for the theme. This name will be used as the lang key
$theme = new Ecs\Modules\Theme('my-theme-name');

// Pass a config array to Theme::run() to bootstrap the theme.
$theme->run(array(
    
    // Define theme dependencies
    // Require WP Plugins - http://tgmpluginactivation.com/
    // Require Core PHP Classes / Libraries
    'dependencies' => array(
        'plugins' => array(
            // MetaBox is amazing, and we use it in the PostType model
            array(
                'name'      => 'Meta Box',
                'slug'      => 'meta-box',
                'required'  => true,
            ),
            // Options Framework is also amazing
            array(
                'name'      => 'Options Framework',
                'slug'      => 'options-framework',
                'required'  => false,
            ),
            array(
                'name'      => 'Wordpress SEO',
                'slug'      => 'wordpress-seo',
                'required'  => true,
            ),
        ),
        'classes' => array(
            'Imagick',
        ),
    ),
    
    // Define stylesheets and scripts
    'assets' => array(
        'stylesheets' => array(
            'style' => array(
                'source' => get_stylesheet_directory_uri() . '/assets/css/app.css',
                'dependencies' => false,
                'version' => 'v1'
            ),
        ),
        'scripts' => array(
            'main' => array(
                'source' => get_stylesheet_directory_uri() . '/assets/js/main.js',
                'dependencies' => false,
                'version' => 'v1',
                'in_footer' => true
            ),
        )
    )
));

\Ecs\Helpers\register_object('Theme', $theme);

/* End of file functions.php */
/* Location: ./functions.php */
