<?php
/**
 * Helper functions and services.
 */

namespace CT; // Child Theme

/**
 * Internal use: Use for debugging of data structures.
 *
 * @param  object  $obj     PHP data structure
 * @param  bool    $do_die  Optional. Die after dump.
 */
function dump( $obj, $do_die = false ) {
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
function dump_log( $obj, $do_die = false ) {
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
function recursive_file_search( $folder, $pattern ) {
    $directory = new \RecursiveDirectoryIterator($folder);
    $iterator  = new \RecursiveIteratorIterator($directory);
    $files     = new \RegexIterator($iterator, $pattern, \RegexIterator::MATCH);
    $paths     = [];

    foreach( $files as $file ) {
        $paths[] = $file->getPathName();
    }

    return $paths;
}
