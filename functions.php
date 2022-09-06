<?php

/**
 * Load the files in the given array. Missing files will produce a fatal error.
 *
 * @param array $files
 */
function ct_load_files( $files ) {
    array_walk( $files, function ( $file ) {
        if ( ! locate_template( $file, true, true ) ) {
            trigger_error(sprintf( 'Error locating %s for inclusion', $file ), E_USER_ERROR );
        }
    });
}

// Kick us off by loading our required theme files, in order.
ct_load_files( [
    'lib/helpers.php',
    'lib/setup.php',
    'lib/customizer/colors/acf.php',
    'lib/customizer/colors/generate-press.php',
    'lib/customizer/colors/neighborhood.php',
    'lib/customizer/colors/wp.php',
    '/lib/custom-post-types/neighborhood/neighborhood.php',
    'acf/setup.php',
] );
