<?php
 /**
 * Customizer color styles set by GeneratePress.
 *
 * We wish to capture styles that are defined in the 'Menu > Appearance > Customize' panel.
 * This is so that we can set the values of some of our own styles based on the colors, etc.
 * that are defined there by the user.
 *
 * To do this, we use a function provided by GeneratePress to get an array containing
 * all of these customized settings. A snippet of that array looks like this:
 *
 *  $settings = [
 *    "h1_color"                     => "var(--contrast)",
 *    "form_button_text_color"       => "var(--base)",
 *    "form_button_background_color" => "var(--accent-2)",
 *    "form_border_color"            => "#cccccc",
 *    ...
 *  ];
 *
 * We loop through this array and for every 'color' setting we find in the array,
 * we translate the name/value pair into a CSS variable under the body{} selector:
 *
 *  body {
 *    --ct-h1-color:                     #000000;
 *    --ct-form-button-text-color:       #000000;
 *    --ct-form-button-background-color: #012334;
 *    --ct-form-border-color:            #cccccc;
 *    ...
 *  }
 *
 * This we save as a string. We then enqueue this string as inline styles, loading
 * it at the top of the frontend AND backend pages
 *
 * In our other SASS files, we are now free to set our CSS values with these
 * CSS variables.
 *
 * Use:
 *   This class only needs to be instantiated once by calling: new Generate_Press()
 *   This call is at the end of this file.
 */

namespace CT\Customizer\Colors;

use CT\Helpers;

class Generate_Press {

    function __construct()
    {
    }

    /**
     * Returns a string containing inline CSS, ready to insert into the head
     * of a page.
     *
     * @return string
     */
    public static function get_inline_css()
    {
        $css = [];

        // Fetch the style settings from GeneratePress.
        $settings = Helpers::get_generate_settings();

        // Create a color lookup table so we can map color slugs to hex values.
        $color_map = Helpers::get_generate_settings_colors( $settings );

        // Generate our CSS.
        foreach ( $settings as $name => $value ) {
            // We're only looking for colors right now.
            if ( ! str_contains( $name, 'color') || is_array( $value ) ) {
                continue;
            }

            // Extract a color slug from the color variable.
            $pattern = "/^var\(--(.*)\)$/i";
            preg_match( $pattern, $value, $matches );
            $color_variable_slug = $matches[1] ?? '';

            // Map variable color to a real hex code color.
            // Default to 'transparent' so we don't leave any color empty in CSS.
            $variable_color = ! empty( $color_variable_slug )
                ? ($color_map[$color_variable_slug]['color'] ?? '')
                : $value;

            $variable_color = $variable_color ?: 'transparent';

            // In the name field, swap underscores for dashes.
            $variable_name  = str_replace('_', '-', $name );

            // Add out property to our array.
            $css[] = "--ct-{$variable_name}: ${variable_color}";
        }

        return "body {" . join( ';', $css ) . "}";
   }
}

new Generate_Press();
