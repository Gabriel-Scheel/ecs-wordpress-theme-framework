<?php
namespace Ecs\Core;

/**
 * Post Type Base Class
 *
 * @category   ECS_WordPress
 * @package    Core
 * @subpackage PostType
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class PostType
{

    /**
     * Name of this post type.
     * 
     * @var string $name
     */
    public $name = '';
    
    /**
     * The type of post type. Or the post types type...
     * 
     * @var string $post
     */
    public $type = 'post';
    
    /**
     * Post type support features.
     * 
     * @var array $supports
     */
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

    /**
     * Default args.
     * 
     * @var array $args
     */
    public $args = array();
    
    /**
     * Label sets.
     *
     * @var array $labels
     */
    public $labels = array();

    /**
     * 
     */
    public function __construct($name, $params = array())
    {
        $this->name = $name;

        if (isset($params['supports'])) {
            $this->supports = $params['supports'];
        }

        $this->Inflector = new \Ecs\Core\Inflector();
        
        // Convention over configuration!
        $this->labels = array(
            'name' =>                   $this->Inflector->humanize($this->Inflector->pluralize($this->name)),
            'singular_name' =>          $this->Inflector->humanize($this->name),
            'add_new' =>                sprintf(__('Add New %s'), $this->Inflector->humanize($this->name)),
            'add_new_item' =>           sprintf(__('Add New %s'), $this->Inflector->humanize($this->name)),
            'edit_item' =>              sprintf(__('Edit %s'), $this->Inflector->humanize($this->name)),
            'new_item' =>               sprintf(__('New %s'), $this->Inflector->humanize($this->name)),
            'all_items' =>              sprintf(__('All %s'), $this->Inflector->humanize($this->Inflector->pluralize($this->name))),
            'view_item' =>              sprintf(__('View %s'), $this->Inflector->humanize($this->name)),
            'search_items' =>           sprintf(__('Search %s'), $this->Inflector->humanize($this->Inflector->pluralize($this->name))),
            'not_found' =>              sprintf(__('No %s found'), $this->Inflector->humanize($this->Inflector->pluralize($this->name))),
            'not_found_in_trash' =>     sprintf(__('No %s found in trash'), $this->Inflector->humanize($this->Inflector->pluralize($this->name))),
            'parent_item_colon' =>      '',
            'menu_name' =>              $this->Inflector->humanize($this->Inflector->pluralize($this->name))
        );
        
        // Post type defaults
        $this->args = array(
            'labels' => $this->labels,
            'description' => '',
            'public' => true,
            'exclude_from_search ' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => $this->type,
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => $this->supports,
            'slug' => $this->Inflector->humanize($this->name)
        );

        register_post_type(strtolower($this->name), $this->args);
    }

}

/* End of file PostType.class.php */
/* Location: ./core/PostType.class.php */