<?php
/**
 * Neighborhood Custom Post Type
 */

namespace CT; // Child Theme

class Neighborhood {

    /**
     * Kick us off
     */
    function __construct()
    {
        add_action( 'init', [$this, 'register_cpt'], 10, 0 );
        add_action( 'init', [$this, 'register_shortcodes'], 10, 0 );
    }

    /**
     * Register our Customer Post Type.
     */
    public function register_cpt()
    {
        $labels = [
            'name'               => 'Neighborhoods',
            'singular_name'      => 'Neighborhood',
            'add_new'            => 'Add New Neighborhood',
            'add_new_item'       => 'Add New Neighborhood',
            'edit'               => 'Edit',
            'edit_item'          => 'Edit Neighborhood',
            'new_item'           => 'New Neighborhood',
            'view'               => 'View Neighborhood',
            'view_item'          => 'View Neighborhood',
            'search_items'       => 'Search Neighborhood',
            'not_found'          => 'No Neighborhood found',
            'not_found_in_trash' => 'No Neighborhood found in Trash',
            'parent_item_colon'  => ''
        ];

        register_post_type('neighborhood', [
            'labels'              => $labels,
            'description'         => 'Neighborhood locations',
            'public'              => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'show_ui'             => true,
            'query_var'           => true,
            'capability_type'     => 'post',
            'rewrite'             => true,
            'hierarchical'        => true,
            'menu_position'       => 5,
            'menu_icon'           => Setup::$images_uri . '/custom-post-types/neighborhood/dashicon.png',
            'supports'            => ['title','editor'],
        ] );

        register_taxonomy('neighborhood_cat', ['neighborhood'], [
            'hierarchical'      => true,
            'show_admin_column' => true,
            'label'             => 'Neighborhood Categories',
            'singular_label'    => 'Neighborhood Category',
            'rewrite'           => true
        ] );
    }

    /**
     * Register our shortcodes.
     */
    public function register_shortcodes()
    {
        add_shortcode( 'available-neighborhood', function() {
            ob_start();
            include_once( Setup::$theme_dir . '/lib/custom-post-types/neighborhood/show_neighborhood.php' );
            $content = ob_get_clean();
            return $content;
        } );

        add_shortcode( 'available-neighborhood-with-map', function() {
            ob_start();
            include_once( Setup::$theme_dir . '/lib/custom-post-types/neighborhood/show_neighborhood_with_map.php' );
            $content = ob_get_clean();
            return $content;
        } );

        add_shortcode( 'available-neighborhood-with-out-map', function() {
            ob_start();
            include_once( Setup::$theme_dir . '/lib/custom-post-types/neighborhood/show_neighborhood_without_map.php' );
            $content = ob_get_clean();
            return $content;
        } );
    }
}

new Neighborhood();
