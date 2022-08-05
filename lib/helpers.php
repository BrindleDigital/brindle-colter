<?php
/**
 * Helper methods and services.
 *
 * Use
 *   This class only needs to be instantiated once by calling: new Helper()
 *   This call is at the end of this file.
 */

namespace CT; // Child Theme

class Helpers {
    /*
     * Media Query Breakpoints
     * These values are used for @media queries so keep them as 'em'.
     * They should match those defined in our /assets/css/sccs/common/_global.scss stylesheet.
     */
    public static $breakpoint = [
        'phone_landscape' => '30em',  // 480px  // 480px @ browser default 16px font-size
        'phablet'         => '36em',  // 576px
        'tablet-small'    => '40em',  // 640px
        'tablet'          => '48em',  // 768px
        'laptop'          => '55em',  // 880px
        'desktop'         => '64em',  // 1024px
        'desktop-large'   => '75em',  // 1200px
        'desktop-xlarge'  => '90em',  // 1440px
    ];

    /**
     * Kick us off
     */
    function __construct()
    {
    }

    /**
     * Internal use: Use for debugging of data structures.
     *
     * @param  object  $obj     PHP data structure
     * @param  bool    $do_die  Optional. Die after dump.
     */
    public static function dump( $obj, $do_die = false ) {
        echo '<pre>'; print_r( $obj ); echo '</pre>';

        if ( $do_die ) {
        die('Dying here from data dump.');
        }
    }

    /**
     * Internal use: Use for debugging of data structures.
     *
     * @param  object  $obj     PHP data structure
     * @param  bool    $do_die  Optional. Die after dump.
     */
    public static function dump_log( $obj, $do_die = false ) {
        error_log( print_r( $obj, true ) );

        if ( $do_die ) {
        die('Dying here from data dump.');
        }
    }

    /**
     * Returns an array of files within the given directory (and its subdirectories)
     * that match the given regex.
     *
     * @param string $folder   Directory path to begin search
     * @param string $pattern  Regex to match files against
     */
    public static function recursive_file_search( $folder, $pattern ) {
        $directory = new \RecursiveDirectoryIterator($folder);
        $iterator  = new \RecursiveIteratorIterator($directory);
        $files     = new \RegexIterator($iterator, $pattern, \RegexIterator::MATCH);
        $paths     = [];

        foreach( $files as $file ) {
            $paths[] = $file->getPathName();
        }

        return $paths;
    }

    /**
     * Returns a string from a filename that is suitable as a label.
     * For example: '/my/path/to/pretty-butterfly.jpg -> "Pretty Butterfly"
     *
     * @param string $filename   '/my/path/to/file.jpg'
     * @return string
     */
    public static function filename_to_label( $filename ) {
        // Start with only the filename.
        $filename = basename( $filename );

        // Strip off any suffix.
        $filename = pathinfo( $filename, PATHINFO_FILENAME );

        // Remove all non-alpha characters.
        $label = preg_replace( '/[^a-z]+/i', ' ', $filename );

        // Capitalize each word.
        $label = ucwords( $label );

        return $label;
    }
}

new Helpers();
