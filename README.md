# ECS WordPress Theme Core

A WordPress Theme Development Framework.

## About
This is an attempt to speed up some of the more tedious aspects of creating an advanced WordPress theme. 

## Features
* Dynamic custom PostType generation w/MetaBox support
* Dependency checking (php libs, WP plugins, vendor libs)
* Snippets, re-usable php functions to extend the core (includes urls.snippet.php as an example)
* Supports dynamic widget loading
* Theme Options Framework

## Structure
/app - _Custom libraries, to extend core_

/app/partials - _template parts, re-usable template blocks_

/app/post_types - _post type models go here_

/app/shortcodes - _shortcode classes go here_

/app/widgets - _widget classes and assets go here_

/app/snippets - _php includes go here_

/core - _core framework_

/assets - _css, img, js, sass, etc._

/vendor - _3rd party plugins, libraries, etc._

## Getting Started

In functions.php create a new Theme object and pass in a basic configuration array with the theme name/id.

The theme name is used as a key for looking up l10n translation strings, etc.

    $theme = new CustomTheme();
    $theme->run(array('name'=>'my-theme-name'));
    

**A more complete example:**

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
        ),
        
        // Define theme dependencies
        'dependencies' => array(
            'plugins' => array(
                // MetaBox is amazing, and we use it in the PostType model
                array(
                    'name'      => 'MetaBox',
                    'slug'      => 'meta-box',
                    'required'  => true,
                )
            ),
            'classes' => array(
                'Imagick',
            )
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
                'source' => get_stylesheet_directory_uri() . '/assets/js/main.js',
                'dependencies' => FALSE,
                'version' => 'v2.1.1',
                'in_footer' => TRUE
            ),
        )
    ));

## Custom Post Types

The Theme uses the following convention for creating and naming post types. If you want to call your custom post type "Articles" for example, you can create a new class called **Article**, and name the file **Article.PostType.php** under $theme_dir/post_types/.

The class name should be singular. Our example Articles class would be called Article. The class file name should be singular as well. Article.PostType.php instead of Articles.PostType.php

    class Article extends PostType{
        
    }

Now you can load it in your config array in functions.php:
    
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
If the MetaBox Plugin by rilwis is available you can very easily add custom fields to any post type. 

There are no defaults. An example would look like:

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

### Overwriting Built In Post Types

Sometimes you will need to modify an existing post type, such as adding custom fields. To do so you need to make one small change to your post type class. Namely, you need to override the register method. To modify an existing post type you need to get the post type object, make your changes, and then save that post object back. Otherwise you will end up with two of the same post types in the menu and it will be weird. 

    public function register() {
        $post = get_post_type_object('post');
        // modify $post here...
        register_post_type(strtolower($this->name), $post);
    }

## Theme.php
Located: $theme_dir/core/Theme.php

### Methods

#### Theme::config();

Retrieve config value using dot notation. Returns FALSE if key not found. 

    $val = $theme->config('settings.mysetting');
    
#### Theme::writeConfig();

Write data to the $config array, using dot notation.

    $theme->writeConfig('settings.mysetting', 'myvalue');

#### Theme::__();

Convienence wrapper for *__()*. Uses the theme name/id as the domain key for translations. 

    echo $theme->__('my-lang-variable');

## Theme Options

There is a very basic custom theme options framework available. 

In your theme config you can pass in an array to create option sections and fields. 

    // Custom Theme Options
    'theme_options' => array(
        // The option section name. 
        'ecs-theme-options' => array( 
            // Field Definition - Text
            array(
                'label' => 'Text',
                'name' => 'my-option',
                'type' => 'text',
            ),
            // Field Definition - Textarea
            array(
                'label' => 'Textarea',
                'name' => 'my-option-textarea',
                'type' => 'textarea',
            ),
            // Field Definition - Checkbox
            array(
                'label' => 'Checkbox',
                'name' => 'my-option-checkboxes',
                'type' => 'checkbox',
                'value' => 'Yes'
            ),
            // Field Definition - Checkbox
            array(
                'label' => 'Radio',
                'name' => 'my-option-radio',
                'type' => 'radio',
                'value' => array(1, 2, 3)
            ),
            // Field Definition - Checkbox
            array(
                'label' => 'Select',
                'name' => 'my-option-select',
                'type' => 'select',
                'value' => array(1, 2, 3)
            ),
        ),
    ),

The Options class is available in the Theme object. There is a helper method to retreive values:

    $theme->Options->get($section, $option);
    $theme->Options->get('ecs-theme-options', 'my-option-checkboxes')

## Debugging

### Available Functions

#### pr($mixed)
A wrapper for print_r, includes pre tags around the output. 

#### debug($mixed)
Same as pr() but includes some additional information, such as file name and line number where debug was called. 

