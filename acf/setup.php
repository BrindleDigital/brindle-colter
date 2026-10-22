<?php
/**
 * Singleton ACF Bootstrapping class.
 */

namespace CT;

class ACFSetup
{
    public function __construct()
    {
        // Load all required ACF block and supporting files.
        $this->include_files();

        // Register with ACF the path to our acf-json files.
        add_filter('acf/settings/load_json', [$this, 'acf_register_json_files'], 10, 1);

        // Add options page at the correct ACF hook timing
        add_action('acf/init', [$this, 'add_acf_options_page']);
    }

    /**
     * Load all required ACF block and supporting files.
     */
    private function include_files()
    {
        // ACF helper methods.
        require_once Setup::$theme_dir . '/acf/helpers.php';

        // All ACF block files (auto-load).
        $block_files = Helpers::recursive_file_search(
            Setup::$theme_dir . '/acf/blocks',
            '/.+\.php$/i'
        );

        foreach ($block_files as $file) {
            require_once $file;
        }
    }

    /**
     * Register with ACF the path to our acf-json files.
     *
     * @param array $paths
     * @return array
     */
    public function acf_register_json_files($paths)
    {
        $paths[] = Setup::$theme_dir . '/acf-json';
        return $paths;
    }

    /**
     * Add ACF options page.
     */
    public function add_acf_options_page()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page();
        }
    }
}

/**
 * Initialize ACFSetup at the correct time
 */
add_action('after_setup_theme', function () {
    new \CT\ACFSetup();
});