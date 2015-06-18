# Configure Theme

Config Files:

* app/Ecs/Config/core.php

# Add custom post types

New post types can be added by passing in a config array to the Theme::run() method.

```
// Define Post Types
$config['post_types] => array(
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
$config['post_types'] => array(
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