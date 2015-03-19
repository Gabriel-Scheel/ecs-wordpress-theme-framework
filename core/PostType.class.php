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

    public $reserved_posttypes = array(
        'post', 
        'page',
        'attachment',
        'revision',
        'nav_menu_item'
    );

    /**
     * Prefix to namespace post type within WP
     * 
     * @var string $name
     */
    public $prefix = 'ecs';

    /**
     * Name of this post type.
     * 
     * @var string $name
     */
    public $name = '';

    /**
     * Namespace prefix for this post type.
     * 
     * @var string $name
     */
    public $prefixed_name = '';
    
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
        $this->name = strtolower(get_class($this));

        $this->Inflector = new \Inflector();
        
        // Convention over configuration!
        $this->labels = array(
            'name' =>                   $this->Inflector->humanize($this->Inflector->pluralize($this->name)),
            'singular_name' =>          $this->Inflector->humanize($this->Inflector->singularize($this->name)),
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
            'supports' => $this->supports
        );
    }
    
    /**
     * 
     */
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
        if ($this->prefix === 'wp') {
            wp_die('You cannot use wp as the post type namespace identifier');
        }

        // Name space our post types. Except for WP post types, let's not touch those.. 
        if (!in_array($this->name, $this->reserved_posttypes)) {
            $this->prefixed_name = $this->prefix . '_' . $this->name;
        } else {
            $this->prefixed_name = $this->name;
        }

        register_post_type($this->prefixed_name, $this->getArgs());
    }

    /**
     *
     */
    public function registerTaxonomies()
    {
        if (empty($this->taxonomies)) {
            return;
        }

        if (!is_array($this->taxonomies)) {
            return;
        }

        foreach ($this->taxonomies as $name => $args) {
            
            if (!is_array($args)) {
                $args = array();
            }

            $args = array_merge(array(
                'label' => $this->Inflector->humanize($this->Inflector->pluralize($name)),
                'labels' => array(
                    'name'              => $this->Inflector->humanize($this->Inflector->pluralize($name)),
                    'singular_name'     => $this->Inflector->humanize($this->Inflector->singularize($name)),
                    'menu_name'         => $this->Inflector->humanize($this->Inflector->pluralize($name)),
                    'all_items'         => sprintf(__('All %s'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                    'edit_item'         => sprintf(__('Edit %s'), $this->Inflector->humanize($name)),
                    'view_item'         => sprintf(__('View %s'), $this->Inflector->humanize($name)),
                    'update_item'       => sprintf(__('Update %s'), $this->Inflector->humanize($name)),
                    'add_new_item'      => sprintf(__('Add New %s'), $this->Inflector->humanize($name)),
                    'new_item_name'     => sprintf(__('New %s Name'), $this->Inflector->humanize($name)),
                    'parent_item'       => sprintf(__('Parent %s'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                    'parent_item_colon' => sprintf(__('Parent %s:'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                    'search_items'      => sprintf(__('Search %s'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                    'popular_items'     => sprintf(__('Popular %s'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                    'separate_items_with_commas' => sprintf(__('Separate %s with commas'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                    'add_or_remove_items' => sprintf(__('Add and Remove %s'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                    'choose_from_most_used' => sprintf(__('Choose from the most used %s'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                    'not_found' => sprintf(__('No %s found'), $this->Inflector->humanize($this->Inflector->pluralize($name))),
                ),
                'public' => true,
                'show_ui' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'show_admin_column' => false,
                'hierarchical' => false,
                'update_count_callback' => true,
                'meta_box_cb' => true,
                'query_var' => $name,
                'rewrite' => array(
                    'slug' => $name,
                    'with_front' => true,
                    'hierarchical' => false,
                    'ep_mask' => EP_NONE
                ),
            ), $args);

            register_taxonomy($name, $this->prefixed_name, $args);
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

        if (empty($this->meta_boxes)) {
            return;
        }

        foreach ($this->meta_boxes as $meta_box) {
            if (is_array($meta_box)) {
                new \RW_Meta_Box($meta_box);
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
    public function getAll($params = array())
    {
        $defaults = array(
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_type' => $this->name
        );

        $params = array_merge($defaults, $params);

        return get_posts($params);
    }

}

/* End of file PostType.class.php */
/* Location: ./core/PostType.class.php */