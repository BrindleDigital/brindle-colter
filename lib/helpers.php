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

/**
 * Returns a string from a filename that is suitable as a label.
 * For example: '/my/path/to/pretty-butterfly.jpg -> "Pretty Butterfly"
 *
 * @param string $filename   '/my/path/to/file.jpg'
 * @return string
 */
function filename_to_label( $filename ) {
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
