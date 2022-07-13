<?php
 /**
 * Bootstrapping setup class.
 *
 * Use
 *   This class only needs to be instantiated once by calling: new Setup()
 *   This call is at the end of this file.
 */

namespace BCT; // Brindle Colter Theme

class Setup {
    // Path to theme folder
    public static $theme_dir;

    // URI to theme folder
    public static $theme_uri;

    // URI to scripts folder
    public static $scripts_uri;

    // URI to styles folder
    public static $styles_uri;

    // Theme version
    public static $theme_version;

    // In case we want this later.
    function __construct()
    {
        $this->setup_global_constants();
        $this->enqueue_assets();
    }

    /**
     * Setup plugin constants.
     */
    private function setup_global_constants()
    {
        self::$theme_dir = get_stylesheet_directory();
        self::$theme_uri = get_stylesheet_directory_uri();
        self::$scripts_uri = self::$theme_uri . '/assets/js';
        self::$styles_uri = self::$theme_uri . '/assets/css';

        // Child theme version (dynamically pulled from our theme header)
        $theme_data = get_file_data( self::$theme_dir . '/style.css', ['Version' => 'Version'], false );
        self::$theme_version = $theme_data['Version'] ?: '';
    }

    /**
     * Enqueue scripts and stylesheets
     */
    private function enqueue_assets()
    {
        // Enqueue styles and scripts for the frontend.
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_wp_styles'] );
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_wp_scripts'] );

        // Enqueue styles and scripts for the backend.
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_wp_styles'] );
        // add_action( 'admin_enqueue_scripts', [$this, 'enqueue_wp_scripts'] );
    }

    /**
     * Enqueue WordPress styles.
     */
    public function enqueue_wp_styles()
    {
        wp_enqueue_style(
            'bct-main-css',  // Preface always with 'bct-' to avoid name collisions.
            self::$styles_uri . '/main.min.css',
            [],
            self::$theme_version
        );
    }

    /**
     * Enqueue WordPress scripts
     */
    public function enqueue_wp_scripts()
    {
        wp_enqueue_script(
            'bct-main-js',
            self::$scripts_uri . '/main.min.js',
            ['jquery'],
            self::$theme_version,
            true  // Include in footer.
        );
    }
}

new Setup();
