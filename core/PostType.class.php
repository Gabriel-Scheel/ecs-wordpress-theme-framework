<?php
namespace Ecs\WordPress;
/**
 * Post Type Model
 *
 * PHP version 5
 *
 * LICENSE: 
 * 
 * @category   ECS_WP_ThemeCore
 * @package    Core
 * @subpackage PostType
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

/**
 * Post Type Base Class
 *
 * @category   ECS_WP_ThemeCore
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
     * Meta box definitions.
     * 
     * @var array $meta_boxes
     */
    public $meta_boxes = array();

    /**
     * Taxonomy Definitions.
     * 
     * @var array $taxonomies
     */
    public $taxonomies = array();

    /**
     * 
     */
    public function __construct()
    {
        $this->name = get_class($this);
        
        // Convention over configuration!
        $this->labels = array(
            'name' =>                   Inflector::humanize(Inflector::pluralize($this->name)),
            'singular_name' =>          Inflector::humanize('post'),
            'add_new' =>                sprintf(__('Add New %s'), Inflector::humanize($this->name)),
            'add_new_item' =>           sprintf(__('Add New %s'), Inflector::humanize($this->name)),
            'edit_item' =>              sprintf(__('Edit %s'), Inflector::humanize($this->name)),
            'new_item' =>               sprintf(__('New %s'), Inflector::humanize($this->name)),
            'all_items' =>              sprintf(__('All %s'), Inflector::humanize(Inflector::pluralize($this->name))),
            'view_item' =>              sprintf(__('View %s'), Inflector::humanize($this->name)),
            'search_items' =>           sprintf(__('Search %s'), Inflector::humanize(Inflector::pluralize($this->name))),
            'not_found' =>              sprintf(__('No %s found'), Inflector::humanize(Inflector::pluralize($this->name))),
            'not_found_in_trash' =>     sprintf(__('No %s found in trash'), Inflector::humanize(Inflector::pluralize($this->name))),
            'parent_item_colon' =>      '',
            'menu_name' =>              Inflector::humanize(Inflector::pluralize($this->name))
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
            'supports' => $this->supports
        );
    }
    
    public function run()
    {
        $this->register();
        $this->registerTaxonomies();
        $this->registerMetaBoxes();
    }

    /**
     * 
     */
    public function register()
    {
        register_post_type(strtolower($this->name), $this->getArgs());
    }

    /**
     *
     */
    public function registerTaxonomies()
    {
        foreach ($this->taxonomies as $tax) {
            register_taxonomy($tax['name'], $tax['post_type'], $tax['options']);
            register_taxonomy_for_object_type($tax['name'], $tax['post_type']);
        }
    }

    /**
     * 
     * @return type
     */
    public function registerMetaBoxes()
    {
        if (!class_exists('RW_Meta_Box')) {
            return;
        }

        foreach ($this->meta_boxes as $meta_box) {
            if (is_array($meta_box)) {
                new RW_Meta_Box($meta_box);
            }
        }
    }

    /**
     * 
     * @return string
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * 
     * @return array
     */
    public function getAll()
    {
        $args = array(
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_type' => $this->name
        );

        $post_array = get_posts($args);
        return $post_array;
    }

}

/* End of file PostType.class.php */
/* Location: ./core/PostType.class.php */