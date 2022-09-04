<?php
 /**
 * Bootstrapping styles class.
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
 * it at the top of the frontend AND backend page.
 *
 * In our other .scss files, we are now free to set our CSS values with these
 * CSS variables.
 *
 *
 * Use
 *   This class only needs to be instantiated once by calling: new Styles()
 *   This call is at the end of this file.
 */

namespace CT; // Child Theme

class Styles {
    /**
     * Kick us off
     */
    function __construct()
    {
        $this->enqueue_assets();
    }

    /**
     * Enqueue inline styles for the frontend and backend. Enqueue our styles to load
     * before our child theme CSS file(s).
     */
    private function enqueue_assets()
    {
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_inline_styles'], 0 ); // Zero loads us early!
        add_action( 'wp_enqueue_scripts',    [$this, 'enqueue_inline_styles'], 0 );
    }

    /**
     * Enqueue inline styles.
     */
    public function enqueue_inline_styles()
    {
        wp_register_style( 'ct-variables', false );
        wp_enqueue_style( 'ct-variables' );
        wp_add_inline_style( 'ct-variables', $this->get_css() );
    }

    /**
     * Returns our CSS.
     *
     * @return string
     */
    public function get_css()
    {
        $css = '';

        // Sanity.
        if ( ! function_exists( 'generate_get_defaults' ) ) {
            return $css;
        }

        // Fetch the style settings from GeneratePress.
        $settings  = wp_parse_args(
            get_option( 'generate_settings', [] ),
            generate_get_defaults()
        );

        // Create a color lookup table so we can map color slugs to hex values.
        $global_color_map = $this->get_global_color_map( $settings );

        // Generate our CSS.
        $css = $this->get_css_colors( $settings, $global_color_map );

        return $css;
    }

    /**
     * Returns an array of colors taken from the given $settings array. The array will be
     * used as a lookup table indexed by the color slug to a color hex value.
     *
     * Given:
     *  $settings = [
     *    [global_colors] => [
     *       ["name" => "contrast", "slug" => "contrast", "color" => "#000000"],
     *       ...
     *    ]
     *    ...
     *  ]
     *
     * Returns:
     *  [
     *     "contrast" => ["name" => "contrast", "slug" => "contrast", "color" => "#000000"],
     *     "accent"   => ["name" => "accent",   "slug" => "accent", "color" => "#ffff00"],
     *     ...
     *    ]
     *  ]
     *
     * @param  array  $settings
     * @return array
     */
    private function get_global_color_map( $settings )
    {
        $global_color_map = [];
        $global_colors    = $settings['global_colors'] ?? [];

        foreach ( $global_colors as $global_color ) {
            $slug = $global_color['slug'] ?? null;

            if ( ! empty( $slug ) ) {
                $global_color_map[$slug] = $global_color;
            }
        }

        return $global_color_map;
    }

    /**
     * Returns a string containing all of our CSS wrapped in a body{} selector.
     *
     * @param  array  $settings
     * @param  array  $global_color_map
     * @return string
     */
   private function get_css_colors( $settings, $global_color_map )
   {
        $css = [];

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
                ? ($global_color_map[$color_variable_slug]['color'] ?? '')
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

new Styles();
