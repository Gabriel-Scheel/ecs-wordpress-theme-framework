# ECS WordPress Theme Core

A WordPress Theme Development Framework.

## Getting Started

In functions.php create a new Theme object and pass in a basic configuration array with the theme name/id.

The theme name is used as a key for looking up l10n translation strings, etc.

    $theme = new Theme();
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

**Class Name**: Article
**File Name**: Article.PostType.php
**Post Type Identifier**: ecs_article

    class Article extends Ecs\WordPress\PostType{
        
    }

**IMPORTANT** You have to explicitly tell the theme to load the custom post type
    
    'post_types' => array('Article'),

That is all you need to do to create a basic custom post type. 

As per WordPress's recommendations, all custom post types are prefixed with a short namespace to identify our theme. In the above example, our Article post type identifier within WordPress would be referenced as "ecs_article". 

The following attributes are available to customize your post type.

Attribute | Information                      | Usage
--------- | -------------------------------- | -----
**$type** | Set the core post type, uh, type | can be page, post, attachment, revision, nav_menu_item
**$supports** | An array of features the post type should support. | [WP Docs](http://codex.wordpress.org/Function_Reference/post_type_supports)
**$meta_boxes** | An array of metabox fields | Depends on [Rilwis Meta-box Plugin](https://github.com/rilwis/meta-box)
**$taxonomies** | An array of custom taxonomies to attach to post type | [http://codex.wordpress.org/Taxonomies](Taxonomies)

### Overwriting Built In Post Types

Sometimes you will need to modify an existing post type, such as adding custom fields to Pages. To do so you need to override the register method. The steps to modify an existing post type are to first get the post type object, make your changes to the post type, and then save that post object back via register_post_type. If you do not do this you will end up with duplicates of the post type in the admin menu. 

    public function register() {
        $post = get_post_type_object('post');
        // modify $post here...
        register_post_type(strtolower($this->name), $post);
    }

### Using custom post types as models

You can use the custom post type as a data model, or interface, to the post types records, or related records. In fact, that is the idea of encapsulating a custom post type into a class. 

To use your custom post type you first request it from the class registry:

    $registry = \Ecs\WordPress\Registry::getInstance();
    $Article = $registry->get('Article');

Now that you have your Post Type object, you can run whatever methods you have defined in the class. There is a default helper method to retrieve all posts for the current post type. Example:

    $articles = $Article->getAll();

## Theme.php
Located: $theme_dir/core/Theme.php
Use the theme in your templates. 

    $registry = \Ecs\WordPress\Registry::getInstance();
    $Theme = $registry->get('Theme');

Method | Description | Usage
------ | ----------- | -----
config() | Retrieve config value using dot notation. Returns FALSE if key not found. | `$val = $Theme->config('settings.mysetting');`
writeConfig() | Write data to the $config array, using dot notation. | `$Theme->writeConfig('settings.mysetting', 'myvalue');` 
__() | Convienence wrapper for gettext method `__()`. Uses the theme name/id as the domain key for translations. | `echo $Theme->__('my-lang-variable');`

## Debugging

### Available Functions

#### pr($mixed)
A wrapper for print_r, includes pre tags around the output. 

    pr($my_var);

#### debug($mixed)
Same as pr() but includes some additional information, such as file name and line number where debug was called. 

    debug($my_var);

## Directory Structure of core theme files

Path | Purpose
---- | -------
/app | _Custom libraries, to extend core_
/app/partials | _template partials_
/app/post_types | _post type models go here_
/app/shortcodes | _shortcode classes go here_
/app/widgets | _widget classes and assets go here_
/app/snippets | _php includes go here_
/core | _core framework_
/assets | _css, img, js, etc._
/vendor | _3rd party plugins, libraries, etc._
    