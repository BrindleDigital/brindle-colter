<?php
 /**
 * This class provides the inline JavaScript for initializing WordPress's [iris] color picker
 * with the color palette values set by GeneratePress in the WPAdmin > Appearance > Customize > Colors
 * panel.
 *
 * Use:
 *   This class only needs to be instantiated once by calling: new WP()
 *   This call is at the end of this file.
 */

namespace CT\Customizer\Colors;

use CT\Helpers;

class WP {

    function __construct()
    {
    }

    /**
     * Returns a string containing inline JavaScript, ready to insert into the page.
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
            jQuery(function($) {
                $('.wp-picker-container').iris({
                    mode: 'hsl',
                    controls: {
                        horiz: 'h', // square horizontal displays hue
                        vert: 's',  // square vertical displays saturdation
                        strip: 'l'  // slider displays lightness
                    },
                    palettes: [$colors]
                });
            });
        EOT;

        return $js;
    }
}

new WP();
