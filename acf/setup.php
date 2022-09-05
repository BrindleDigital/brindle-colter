<?php
/**
 * Singleton ACF Bootstrapping class.
 */

namespace CT;

Class ACFSetup
{
    function __construct()
    {
        // Load all required ACF block and supporting files.
        $this->include_files();

        // Register with ACF the path to our acf-json files.
        add_filter( 'acf/settings/load_json', [$this, 'acf_register_json_files'], 10, 1 );

        $this->add_acf_options_page();
    }

    /**
     * Load all required ACF block and supporting files.
     */
    private function include_files()
    {
        // ACF helper methods.
        require_once Setup::$theme_dir . '/acf/helpers.php';

        // All ACF block files. Load them dynamically so we don't have to manually list them.
        $block_files = Helpers::recursive_file_search( Setup::$theme_dir . '/acf/blocks', '/.+\.php$/i' );

        foreach ( $block_files as $file ) {
            require_once $file;
        }
    }

    /**
     * Register with ACF the path to our acf-json files.
     *
     * @param  array $paths   List of paths ACF uses to load acf-json files.
     * @return array
     */
    public function acf_register_json_files( $paths )
    {
        $paths[] = Setup::$theme_dir . '/acf-json';
        return $paths;
    }

    /**
     * Add our ACF options page. You'll now find a 'Menu > Options' page.
     */
    public function add_acf_options_page() {
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page();
        }
    }
}

new ACFSetup();
