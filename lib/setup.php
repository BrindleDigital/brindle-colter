<?php
 /**
 * Bootstrapping setup class.
 *
 * Use
 *   This class only needs to be instantiated once by calling: new Setup()
 *   This call is at the end of this file.
 */

namespace CT; // Child Theme

class Setup {
    // Directory path to theme folder
    public static $theme_dir;

    // URI to theme folder
    public static $theme_uri;

    // URI to scripts folder
    public static $scripts_uri;

    // URI to styles folder
    public static $styles_uri;

    // Directory path to images folder
    public static $images_dir;

    // URI to images folder
    public static $images_uri;

    // Theme version
    public static $theme_version;

    /*
    * Use these values when registering a new ACF block. It's the 'B' from the Brindle logo.
    * The logo will appear in the Gutenberg editor block panel where all blocks are listed.
    *   acf_register_block_type([
    *       'category' => Setup::$block_category['slug'],
    *       'icon'     => Setup::$block_category['icon'],
    *       ...
    *   ]);
    */
    public static $block_category = [
        'slug'  => 'brindle',
        'title' => 'Brindle',
        'icon'  =>
            '<svg id="Layer_2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34.28 31"><rect x="5.15" y=".82" width="22.67" height="23.04"/><path d="M20.92,11.8c.58-.41,.87-1.06,.87-1.96s-.26-1.59-.79-1.98c-.52-.39-1.34-.59-2.46-.59h-2.83v5.15h2.65c1.12,0,1.98-.21,2.56-.62Z" style="fill:#fff;"/><path d="M21.56,15.37c-.3-.23-.64-.4-1.01-.5-.49-.12-.99-.18-1.49-.17h-3.35v5.22h3.36c.52,.01,1.04-.06,1.54-.22,.37-.12,.71-.33,1-.59,.25-.24,.43-.54,.54-.86,.11-.34,.17-.69,.17-1.04s-.06-.71-.18-1.04c-.12-.31-.32-.58-.58-.79h0Z" style="fill:#fff;"/><path d="M31.74,.49H2.91c-.29,0-.58,.06-.85,.17-.27,.11-.51,.27-.72,.48-.21,.21-.37,.45-.48,.72-.11,.27-.17,.56-.17,.85V23.67c0,.43,.14,.83,.44,.98l15.14,5.55c1,.38,1.18,.4,2.12,0l15.12-5.54c.31-.07,.45-.5,.44-.96,0-.05,0-.1,.02-.15V2.71c0-.29-.06-.58-.17-.85-.11-.27-.28-.51-.48-.72-.21-.21-.45-.37-.72-.48s-.56-.17-.85-.17h0Zm-6.58,18.88c-.29,.64-.71,1.21-1.25,1.66-.6,.49-1.29,.86-2.03,1.08-.89,.28-1.82,.41-2.76,.39H10.54v-1.45c-.02-.18,.04-.37,.15-.51,.12-.14,.28-.24,.47-.26l.15-.03,.37-.06c.18-.04,.42-.08,.73-.13V7.15l-.73-.13-.37-.07-.15-.03c-.18-.02-.35-.12-.47-.26s-.17-.33-.15-.51v-1.45h8.01c1-.02,2,.09,2.98,.33,.73,.18,1.42,.5,2.03,.95,.51,.39,.91,.9,1.16,1.49,.25,.62,.38,1.29,.37,1.97,0,.41-.06,.81-.18,1.2-.13,.39-.32,.75-.58,1.07-.28,.35-.61,.66-.98,.9-.44,.29-.92,.52-1.42,.69,2.44,.55,3.66,1.87,3.66,3.96,0,.72-.14,1.43-.43,2.09v.02Z" style="fill:#fff;"/><path d="M22.8,12.94c-.28,.15-.58,.27-.88,.37,.14,.03,.27,.08,.4,.12,.17-.15,.33-.32,.47-.49h.01Z" style="fill:#eaeaea;"/><path d="M20.92,11.8c.58-.41,.87-1.06,.87-1.96s-.26-1.59-.79-1.98c-.52-.39-1.34-.59-2.46-.59h-2.83v1.17h1.35c1.12,0,1.93,.2,2.46,.59,.53,.39,.79,1.05,.79,1.98,0,.4-.07,.8-.22,1.17,.29-.09,.57-.22,.83-.39h0Z" style="fill:#eaeaea;"/><path d="M20.52,19.72s.06,0,.09-.02c.37-.12,.71-.33,1-.59,.25-.24,.43-.54,.54-.86,.11-.34,.17-.69,.17-1.04s-.06-.71-.18-1.04c-.12-.31-.32-.58-.58-.79-.3-.23-.64-.4-1.01-.5-.49-.12-.99-.18-1.49-.17h-3.35v1.16h1.86c.5,0,1,.05,1.49,.17,.37,.09,.71,.26,1.01,.5,.26,.21,.46,.48,.58,.79,.13,.33,.19,.68,.18,1.04,0,.35-.05,.71-.16,1.04-.04,.11-.09,.21-.15,.31Z" style="fill:#eaeaea;"/><path d="M21.87,22.11c-.89,.28-1.82,.41-2.76,.39H10.54v-1.45c-.02-.18,.04-.37,.15-.51,.12-.14,.28-.24,.47-.26l.15-.03,.37-.07c.18-.04,.42-.08,.73-.13V7.15l-.73-.13-.37-.07-.15-.03c-.18-.02-.35-.12-.47-.26s-.17-.33-.15-.51v-1.45l-1.48,1.17v1.45c-.02,.18,.04,.37,.15,.51,.12,.14,.28,.24,.46,.26l.15,.03,.37,.07,.73,.13v12.05c-1.15,.35-1.6,1-1.76,1.44-.05,.1-.08,.21-.09,.33v1.54h8.58c.93,.01,1.86-.12,2.76-.4,.74-.23,1.43-.59,2.03-1.08,.18-.16,.34-.33,.49-.51-.34,.17-.69,.32-1.05,.43h-.01Z" style="fill:#eaeaea;"/><path d="M31.55,.5H2.72c-.29,0-.58,.06-.85,.17-.27,.11-.51,.27-.72,.48-.21,.21-.37,.45-.48,.72s-.17,.56-.17,.85V23.68c0,.43,.14,.83,.44,.98l15.14,5.55c1,.38,1.18,.4,2.12,0l15.12-5.54c.31-.07,.45-.5,.44-.96,0-.05,0-.1,.02-.15V2.72c0-.29-.06-.58-.17-.85-.11-.27-.28-.51-.48-.72-.21-.21-.45-.37-.72-.48s-.56-.17-.85-.17h-.01Z" style="fill:none; stroke:#000; stroke-miterlimit:10;"/></svg>',
    ];

    /**
     * Kick us off
     */
    function __construct()
    {
        $this->setup_global_constants();
        $this->enqueue_assets();
        $this->setup_filters();
    }

    /**
     * Setup plugin constants.
     */
    private function setup_global_constants()
    {
        self::$theme_dir   = get_stylesheet_directory();
        self::$theme_uri   = get_stylesheet_directory_uri();
        self::$scripts_uri = self::$theme_uri . '/assets/js';
        self::$styles_uri  = self::$theme_uri . '/assets/css';
        self::$images_dir  = self::$theme_dir . '/assets/images';
        self::$images_uri  = self::$theme_uri . '/assets/images';

        // Child theme version (dynamically pulled from our theme header)
        $theme_data = get_file_data( self::$theme_dir . '/style.css', ['Version' => 'Version'], false );
        self::$theme_version = $theme_data['Version'] ?: '';
    }

    /**
     * Enqueue scripts and stylesheets.
     */
    private function enqueue_assets()
    {
        // Enqueue styles and scripts for the frontend.
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_wp_styles'], 50, 0 ); // 50: We want to be near last.
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_wp_scripts'], 50, 0 );

        // Enqueue styles and scripts for the backend.
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_admin_wp_styles'], 50, 0 );
        add_action( 'enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets'], 10, 0 );
    }

    /**
     * Enqueue WordPress styles.
     */
    public function enqueue_wp_styles()
    {
        wp_enqueue_style(
            'ct-main',  // Preface always with 'ct-' to avoid name collisions.
            self::$styles_uri . '/main.min.css',
            [],
            self::$theme_version
        );
    }

    /**
     * Enqueue Admin WordPress styles.
     */
    public function enqueue_admin_wp_styles()
    {
        wp_enqueue_style(
            'ct-main',  // Preface always with 'ct-' to avoid name collisions.
            self::$styles_uri . '/main.min.css',
            [],
            self::$theme_version
        );

        // We need these icons for the FloorPlans page (Rent Fetch plugin).
        wp_enqueue_style( 'dashicons' );
    }

    /**
     * Enqueue WordPress scripts.
     */
    public function enqueue_wp_scripts()
    {
        wp_enqueue_script(
            'ct-easy-responsive-tabs',
            self::$scripts_uri . '/vendor/easy-responsive-tabs.js',
            ['jquery'],
            self::$theme_version,
            true  // Include in footer.
        );

        wp_enqueue_script(
            'ct-jquery-scrolltofixed',
            self::$scripts_uri . '/vendor/jquery-scrolltofixed-min.js',
            ['jquery'],
            self::$theme_version,
            true  // Include in footer.
        );

        wp_enqueue_script(
            'ct-jquery-magnific-popup',
            self::$scripts_uri . '/vendor/jquery-magnific-popup.js',
            ['jquery'],
            self::$theme_version,
            true  // Include in footer.
        );

        wp_enqueue_script(
            'ct-expandable-list',
            self::$scripts_uri . '/common/expandable-list.js',
            ['jquery'],
            self::$theme_version,
            true  // Include in footer.
        );

        wp_enqueue_script(
            'ct-neighborhood',
            self::$scripts_uri . '/custom-post-types/neighborhood/neighborhood.js',
            ['jquery'],
            self::$theme_version,
            true  // Include in footer.
        );

        // The below script is loaded when needed by /lib/show-neighborhood_with_map.php.
        // wp_enqueue_script(
        //     'ct-wp-google-map',
        //     self::$scripts_uri . '/custom-post-types/neighborhood/wp-google-map.js',
        //     ['jquery'],
        //     self::$theme_version,
        //     true  // Include in footer.
        // );
    }

    /**
     * Enqueue Block Editor scripts on backend only.
     */
    public function enqueue_block_editor_assets()
    {
        wp_enqueue_script(
            'wpadmin-setup',
            self::$scripts_uri . '/wpadmin/setup.js',
            ['jquery'],
            self::$theme_version,
            true  // Include in footer.
        );
    }

    /**
     * Setup filters.
     */
    public function setup_filters()
    {
        global $wp_version;

        // Add page slug to body class. (i.e. page-mypageslug)
        add_filter( 'body_class', function( $classes ) {
            global $post;

            if ( isset( $post ) ) {
                $classes[] = $post->post_type . '-' . $post->post_name;
            }

            return $classes;
        } );

        // Create our category in the Gutenberg editor menu to store theme blocks.
        // The 'block_categories' filter was deprecated in WP 5.8.
        version_compare( $wp_version, '5.8.0', '>=' )
            ?  add_filter( 'block_categories_all', [$this, 'register_block_categories'], 11, 2 )
            :  add_filter( 'block_categories',     [$this, 'register_block_categories'], 11, 2 );
    }

    /**
     * Create our category in the Gutenberg editor menu to store theme blocks.
     * Move the category to the top of the inserter menu.
     * See: https://developer.wordpress.org/block-editor/developers/filters/block-filters/#managing-block-categories
     */
    public function register_block_categories( $categories, $editor_context_or_post )
    {
        // Only add our category if it's not already included in the $categories array.
        if ( ! in_array( self::$block_category['slug'], array_column( $categories, 'slug' ) ) ) {
            return array_merge([
                ['slug'  => self::$block_category['slug'], 'title' => self::$block_category['title']]
            ], $categories );
        }

        return $categories;
    }
}

new Setup();
