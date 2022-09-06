<?php
 /**
 * This class provides the inline JavaScript for initializing the ACF color picker
 * with the color values set by GeneratePress in the WPAdmin > Appearance > Customize > Colors
 * panel.
 *
 * Use:
 *   This class only needs to be instantiated once by calling: new ACF()
 *   This call is at the end of this file.
 */

namespace CT\Customizer\Colors;

use CT\Helpers;

class ACF {

    function __construct()
    {
    }

    /**
     * Returns a string containing inline JavaScript, ready to insert into the head
     * of a page.
     *
     * @return string
     */
    public static function get_inline_script()
    {
        // Fetch the style settings from GeneratePress.
        $settings = Helpers::get_generate_settings();

        // Extract just the WPAdmin > Appearance > Customize > Color palette
        $colors = $settings['global_colors'] ?? [];

        if ( empty( $colors ) ) {
            return '';
        }

        // Extract just the hex color values.
        $colors = array_column( $colors, 'color');

        // Wrap each color into a string.
        $colors = array_map(function( $color ) {
            return "'{$color}'";
        }, $colors );

        // Collapse our array into a string.
        $colors = join( ',', $colors );

        $js = <<<EOT
            /**
             * Set the ACF color palette to our theme colors.
             */
            ;jQuery(function ($) {
                'use strict';

                if (typeof acf === 'undefined') {
                    return;
                }

                acf.add_filter('color_picker_args', function( args ){
                    args.palettes = [$colors];
                    return args;
                });
            });
        EOT;

        return $js;
    }
}

new ACF();
