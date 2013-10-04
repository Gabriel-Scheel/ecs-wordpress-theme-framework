# ECS WordPress Theme Core

A WordPress Theme Framework.

## About
This is an attempt to speed up some of the more tedious aspects of creating an advanced WordPress theme. 

## Features
* Dynamic custom PostType generation w/MetaBox support 
* Debugging Tools
* Support for weinre for mobile app debugging
* PixelPerfect widget
* Dependency checking (php libs, WP plugins, vendor libs)
* Snippets, re-usable php functions to extend the core (includes urls.snippet.php as an example)
* Supports dynamic widget loading
* Supports dynamic shortcode loading

## Structure
/app - _Custom libraries, to extend core (CustomTheme, CustomDectorator, etc)_

/core - _core theme libraries. Best practice is to not edit these files directly_

/partials - _template parts, re-usable template blocks_

/post_types - _post type models go here_

/shortcodes - _shortcode classes go here_

/widgets - _widget classes and assets go here_

/snippets - _php includes go here_

/assets - _css, img, js, sass, etc._

/vendors - _3rd party plugins, libraries, etc._

## Getting Started

In functions.php create a new Theme object and pass in a basic configuration array with the theme name/id.
The theme name is used as a key for looking up l10n translation strings, etc.

    $theme = new Theme();
    $theme->run(array('name'=>'my-theme-name'));
    
A more advanced setup would look like:

*(CustomTheme extends Theme, and allows us to create new functionality without editing core files)*

**$theme_dir/app/CustomTheme.php**

    class CustomTheme extends Theme
    {
        public function run($config = array())
        {
            parent::run($config);
        }
    }

**$theme_dir/functions.php**

    require_once LIB_PATH . '/CustomTheme.php';
    $theme = new CustomTheme();
    $theme->run(array(

        'name' => 'my-theme-name',
    
        // Load post types
        'post_types' => array(),
        
        // Load widgets
        'widgets' => array(),
        
        // Configure debugging
        'debug' => array(
            'enable_debug' => TRUE,
            'enable_pixel_perfect' => FALSE,
            'enable_wienre' => FALSE,
        ),
        
        // Define theme dependencies
        'dependencies' => array(
            'plugins' => array(
                'Google Analytics' => 'googleanalytics/googleanalytics.php'
            ),
            'classes' => array(
                'Imagick',
            ),
            'vendors' => array(),
        ),
        
        // Define stylesheets and scripts
        'stylesheets' => array(
            'style' => array(
                'source' => get_stylesheet_directory_uri() . '/style.css',
                'dependencies' => FALSE,
                'version' => 'v1'
            ),
        ),
        'scripts' => array(
            'jquery' => array(
                'source' => get_stylesheet_directory_uri() . '/assets/js    /vendor/jquery-1.10.1.min.js',
                'dependencies' => FALSE,
                'version' => 'v2.1.1',
                'in_footer' => TRUE
            ),
            'modernizr' => array(
                'source' => get_stylesheet_directory_uri() . '/assets/js/vendor/modernizr-2.6.2.min.js',
                'dependencies' => FALSE,
                'version' => 'v2.6.2',
                'in_footer' => FALSE
            ),
        )
    ));

## Custom Post Types

The Theme uses the following convention for creating and naming post types. If you want to call your custom post type "Articles" for example, you can create a new class called **Article**, and name the file **Article.PostType.php** under $theme_dir/post_types/.

The class name should be singular. Articles would be Article. The class file name should be singular as well. 

    class Article extends PostType{
        
    }

Now you can specify to load it in your config array in functions.php:
    
    'post_types' => array('Article'),

That is all you need to do to create a basic custom post type. The following attributes are available to customize your post type:

### $type
Can be set to either "page" or "post".
    
    public $type = 'post'; // or page, defaults to post
    
### $supports
An array of features the post type should support. The default list is:

    public $supports = array(
            'title', 
            'editor', 
            'page-attributes',
            'author',
            'thumbnail',
            'custom-fields', 
            'revisions',
            'page-attributes',
            'post-formats',
    );
    
### $meta_boxes
An array of custom meta boxes you wish to include. There are no defaults. An example would look like:

    public $meta_boxes = array(
            array(
                    'id' => 'mb_fields',
                    'title' => 'Article Data',
                    'pages' => array('article'),
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
            ),
    );
    
### $taxonomies
An array of custom taxonomies you wish to create.

    public $taxonomies = array(
            array(
                    'name' => 'article_categories',
                    'post_type' => 'article',
                    'options' => array(
                        'label' => 'Article Categories',
                            'rewrite' => 'article_categories',
                            'hierarchical' => TRUE
                    ),
            ),
    );

## Theme.php
Located: $theme_dir/core/Theme.php

### Methods

#### Theme::config();

Retrieve config value using dot notation. Returns FALSE if key not found. 

    $val = $theme->config('settings.mysetting');
    
#### Theme::write_config();

Write data to the $config array, using dot notation.

    $theme->write_config('settings.mysetting', 'myvalue');

#### Theme::lang();

Convienence wrapper for *__()*. Uses the theme name/id as the domain key for translations. 

    echo $theme->lang('my-lang-variable');

## Debugging

### Available Functions

#### pr($mixed)
A wrapper for print_r, includes pre tags around the output. 

#### debug($mixed)
Same as pr() but includes some additional information, such as file name and line number where debug was called. 

### Debug class

#### weinre
Injects weinre js script into header when enabled. - http://people.apache.org/~pmuellr/weinre/docs/latest/ 

#### pixelperfect
Injects pixelperfect js into header when enabled. 

