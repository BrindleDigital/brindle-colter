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
