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
     * ID of this post type. aka the all lowercase no spaces version of $name
     * 
     * @var string $name
     */
    public $id = '';

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
        $this->id   = strtolower($this->name);

        if (in_array($this->id, array('post', 'page', 'attachment', 'revision', 'nav_menu_item'))) {
            wp_die(sprintf('That post type is reserved - %s'), $this->id);
        }

        if (isset($params['supports'])) {
            $this->supports = $params['supports'];
        }

        $this->Inflector = new \Ecs\Core\Inflector();
        
        // Convention over configuration!
        $singular = $this->Inflector->humanize($this->name);
        $plural   = $this->Inflector->humanize($this->Inflector->pluralize($this->name));
        
        $this->labels = array(
            'name' =>                   $plural,
            'singular_name' =>          $singular,
            'add_new' =>                sprintf(__('Add New %s'), $singular),
            'add_new_item' =>           sprintf(__('Add New %s'), $singular),
            'edit_item' =>              sprintf(__('Edit %s'), $singular),
            'new_item' =>               sprintf(__('New %s'), $singular),
            'all_items' =>              sprintf(__('All %s'), $plural),
            'view_item' =>              sprintf(__('View %s'), $singular),
            'search_items' =>           sprintf(__('Search %s'), $plural),
            'not_found' =>              sprintf(__('No %s found'), $plural),
            'not_found_in_trash' =>     sprintf(__('No %s found in trash'), $plural),
            'parent_item_colon' =>      '',
            'menu_name' =>              $plural
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
            'slug' => $singular
        );

        register_post_type($this->id, $this->args);
    }

}

/* End of file PostType.class.php */
/* Location: ./core/PostType.class.php */