<?php

// Set theme config
$config = array(
    
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
);

\Ecs\Core\Configure::set($config);

/* End of file core.php */
/* Location: ./app/Ecs/Config/core.php */
