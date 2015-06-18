<?php

// Set theme config
$config = array(
    // Define Post Types
    // Uncomment below and edit to create custom post types
    /*
    'post_types' => array(
        'Cover' => array(
            // Default supports
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'revisions',
            ),
            // Default args
            'args' => array(
                'description' => $theme->lang('Describe the post type'),
            )
        )
    ),
    */

    // Define Taxonomies
    // https://codex.wordpress.org/Function_Reference/register_taxonomy
    // Uncomment below and edit to create taxonomies
    /*
    'taxonomies' => array(
        'cover_categories' => array(
            'params' => array(
                'post_types'   => array('cover'), // this can include built in post types (page, post, etc)
            ),
            'args' => array(
                'label'        => $theme->lang('Cover Categories'),
                'rewrite'      => 'cover_categories',
                'hierarchical' => true,
            )
        ),
        'cover_tags' => array(
            'params' => array(
                'post_types'   => array('cover'),
            ),
            'args' => array(
                'label'        => $theme->lang('Cover Tags'),
                'rewrite'      => 'cover_tags',
                'hierarchical' => false,
                'show_admin_column' => false
            )
        ),
    ),
    */

    // Load shortcodes
    // Uncomment and add shortcodes you wish to loa
    // The shortcode module should exist under app/Ecs/Shortcodes
    /*
    'shortcodes' => array(
        'Button'
    ),
    */

    // Define theme features
    // http://codex.wordpress.org/Function_Reference/add_theme_support
    /*
    'theme_features' => array(
        'automatic-feed-links',
        'post-thumbnails',
        'post-formats' => array(
            'aside',
            'gallery'
        )
    ),
    */

    // Custom Image Sizes
    /*
    'image_sizes' => array(
        'cover_large' => array(
            'width' => 1920,
            'height' => 1080,
            'crop' => false
        ),
    ),
    */

    // Define custom nav menus
    // https://codex.wordpress.org/Function_Reference/register_nav_menu
    /*
    'menus' => array(
        'main-menu'   => $theme->lang('Main Menu'),
        'sub-menu'    => $theme->lang('Sub Menu'),
        'footer-menu' => $theme->lang('Footer Menu')
    ),
    */

    // Define custom sidebars
    // https://codex.wordpress.org/Function_Reference/register_sidebar
    /*
    'sidebars' => array(
        array(
            'id'            => 'my-custom-sidebar',
            'name'          => $theme->lang('My Custom Sidebar'),
            'description'   => '',
            'class'         => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ),
    ),
    */

    // Define theme dependencies
    // Require WP Plugins - http://tgmpluginactivation.com/
    // Require Core PHP Classes / Libraries
    /*
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
    */
    
    // Define stylesheets and scripts
    /*
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
    */
);

// Register the config array with the Configure class
\Ecs\Core\Configure::set($config);

/* End of file core.php */
/* Location: ./app/Ecs/Config/core.php */
