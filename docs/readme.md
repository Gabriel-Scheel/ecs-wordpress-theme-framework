# Overview

Do not edit core. Do not edit anything under Ecs/Core. 

All customizations and new functionaly should be under Ecs/Modules.

# Init Theme

Add custom hooks and WordPress behavior to Ecs/Modules/Theme.class.php. 

Ecs/Modules/Theme.class.php extends Ecs/Core/Theme.class.php. 

Any hooks and functions defined in Ecs/Modules/Theme::run() will be executed in theme initialization. 

```
class Theme extends \Ecs\Core\Theme
{
    public function run($config)
    {
        parent::run($config);

        ///// Add Hooks Below /////
        add_action('init', array(&$this, 'myInitFunction'));
        add_action(...);
    }

    public function myInitFunction()
    {
        // do stuff...
    }
}
```

The theme initialization occurs in functions.php. You must pass a theme id to the constructor. The theme id is used for string translations. 

```
$theme = new Ecs\Modules\Theme('my-theme-name');
```

To initialize the theme you must now call the run method

```
$theme->run();
```

Finall add the theme object to the class registry. Objects in the registry can be retreived for use in templates and tin other classes without relying on "global". 

```
\Ecs\Helpers\register_object('Theme', $theme);
```

You can pass in an array of options to run to bootstrap your theme. You can define post types, taxonomies, plugin dependencies, enqueue assets, etc. See the example below. 

```
$theme->run(array(

    // Define Post Types
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

    // Define Taxonomies
    // https://codex.wordpress.org/Function_Reference/register_taxonomy
    'taxonomies' => array(
        'cover_categories' => array(
            'params' => array(
                'post_types'   => array('cover'),
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

    // Load shortcodes
    'shortcodes' => array(
        'Button'
    ),

    // Define theme features
    // http://codex.wordpress.org/Function_Reference/add_theme_support
    'theme_features' => array(
        'automatic-feed-links',
        'post-thumbnails',
        'post-formats' => array(
            'aside',
            'gallery'
        )
    ),

    // Custom Image Sizes
    'image_sizes' => array(
        'cover_large' => array(
            'width' => 1920,
            'height' => 1080,
            'crop' => false
        ),
    ),

    // Define custom nav menus
    // https://codex.wordpress.org/Function_Reference/register_nav_menu
    'menus' => array(
        'main-menu'   => $theme->lang('Main Menu'),
        'sub-menu'    => $theme->lang('Sub Menu'),
        'footer-menu' => $theme->lang('Footer Menu')
    ),

    // Define custom sidebars
    // https://codex.wordpress.org/Function_Reference/register_sidebar
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
    
    // Define theme dependencies
    // Require WP Plugins - http://tgmpluginactivation.com/
    // Require Core PHP Classes / Libraries
    'dependencies' => array(
        'plugins' => array(
            // Rilwils MetaBox is amazing!
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
```

# Add custom post types

New post types can be added by passing in a config array to the Theme::run() method.

```
$theme->run(array(

    ... snip ...

    // Define Post Types
    'post_types' => array(
        'Cover' => array(
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'revisions',
            ),
            'args' => array(
                'description' => $theme->lang('Describe the post type'),
            )
        )
    ),
));
```

The default args below can be overridden in the post type config array you pass to the Theme::run() method. 

```
array(
    'description' => '',
    'public' => true,
    'exclude_from_search ' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'slug' => $singular
)
```

For example:

```
$theme->run(array(

    ... snip ...

    // Define Post Types
    'post_types' => array(
        'Cover' => array(
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'revisions',
            ),
            'args' => array(
                'description' => $theme->lang('Describe the post type'),
                'show_in_menu' => false,
                'exclude_from_search' => true,
            )
        )
    ),
));
```

The default post type support options can also be overridden. 

```
array(
    'title',
    'editor',
    'page-attributes',
    'author',
    'thumbnail',
    'custom-fields',
    'revisions',
    'page-attributes',
    'post-formats',
)
```

# Taxonomies

# Plugin Dependencies

# Class Registry

# Front end assets

# Shortcodes

# Theme Features

# Menus

# Sidebars

# Helpers
* render_partial
* __
* pr
* debug
* get_instance
* register_object
* date
* json_response

