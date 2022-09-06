<?php
/**
 * Customizer color styles set for Neighborhood.
 *
 * This class will create color settings options in the 'Menu > Appearance > Customize > Color'
 * panel. These colors are specific to the display of the Neighborhood/Map section.
 *
 * We also provide a static method for retrieving a string containing inline CSS with
 * these color [variables], ready to insert into the head of a page. With the inline CSS
 * in place, you can reference these CSS variables when styling the Neighborhood section.
 */

namespace CT\Customizer\Colors;

/*
 * Extend the class we'll use to create headings on the Customize Color panel.
 * We'll use this class to customize the layout of the heading.
 */
include_once ABSPATH . 'wp-includes/class-wp-customize-control.php';

class Customize_Control_Title extends \WP_Customize_Control
{
    public $type = 'customize_control_title';

    public function render_content() {
    ?>
        <?php if ( ! empty( $this->label ) ) : ?>
            <div class="sub-section-heading-control">
                <h4 class="customize-control-title">
                      <?php echo esc_html( $this->label ); ?>
                </h4>
            </div>
        <?php endif; ?>
    <?php
    }
}

class Neighborhood
{
    // GeneratePress's slug for the WPAdmin > Appearance > Customize > Colors
    // panel it creates. We'll be adding colors to this section.
    private static $section = 'generate_colors_section';

    // Here's a complete list of the entries we create/save on the Color section.
    // Default colors were created as a visually appealing start based on
    // site design. All can be changed from the Customize > Colors panel.
    private static $settings = [
        // Neighborhood
        [
            'type'    => 'heading',
            'slug'    => 'neighborhood_heading',
            'label'   => 'Neighborhood Settings',
            'default' => ''
        ], [ // Buttons and panels
            'type'    => 'color',
            'slug'    => 'neighborhood_button_text_color',
            'label'   => 'Button Text',
            'default' => '#5a5a5a'
        ], [
            'type'    => 'color',
            'slug'    => 'neighborhood_button_text_color_active',
            'label'   => 'Button Text (Active)',
            'default' => '#ffffff'
        ], [
            'type'    => 'color',
            'slug'    => 'neighborhood_button_background_color',
            'label'   => 'Background',
            'default' => '#ffffff'
        ], [
            'type'    => 'color',
            'slug'    => 'neighborhood_button_background_color_active',
            'label'   => 'Background (Active)',
            'default' => '#5a5a5a'
        ], [
            'type'    => 'color',
            'slug'    => 'neighborhood_button_separator_color',
            'label'   => 'Button Separator',
            'default' => '#5a5a5a'
        ], [ // Addresses
            'type'    => 'color',
            'slug'    => 'neighborhood_address_header_color',
            'label'   => 'Address Heading',
            'default' => '#ffffff'
        ], [
            'type'    => 'color',
            'slug'    => 'neighborhood_address_text_color',
            'label'   => 'Address Text',
            'default' => '#ffffff'
        ], [
            'type'    => 'color',
            'slug'    => 'neighborhood_address_separator_color',
            'label'   => 'Address Separator',
            'default' => '#ffffff'
        ],
    ];

    function __construct()
    {
        // Add our colors to the Appearance > Customize > Colors Section
        add_action( 'customize_register', [$this, 'render'], 100, 1 ); // 100: After GeneratePress
    }

    public function render( $wp_customize )
    {
        foreach (self::$settings as $setting ) {
            switch ($setting['type']) {
                case 'heading':
                    $this->render_heading( $wp_customize, $setting );
                    break;
                case 'color':
                    $this->render_color( $wp_customize, $setting );
                    break;
            }
        }
    }

    private function render_heading( $wp_customize, $setting )
    {
        $wp_customize->add_setting( $setting['slug'], [] ); // Just a dummy

        $wp_customize->add_control( new Customize_Control_Title(
            $wp_customize,
            $setting['slug'] . '_control',
            [
                'label'    => $setting['label'],
                'section'  => self::$section,
                'settings' => $setting['slug'],
            ]
        ) );
    }

    private function render_color( $wp_customize, $setting )
    {
        $wp_customize->add_setting(
            $setting['slug'],
            [
                'type'              => 'theme_mod',
                'default'           => $setting['default'],
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );

        $wp_customize->add_control( new \WP_Customize_Color_Control(
            $wp_customize,
            $setting['slug'] . '_control',
            [
                'label'    => $setting['label'],
                'section'  => self::$section,
                'settings' => $setting['slug'],
            ]
        ) );
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

        foreach ( self::$settings as $setting ) {
            if ( $setting['type'] !== 'color' ) {
                continue;
            }

            // Get saved color from database.
            $color = get_theme_mod( $setting['slug'], $setting['default'] );

            // In the name field, swap underscores for dashes.
            $name  = str_replace('_', '-', $setting['slug'] );

            // Add out property to our array.
            $css[] = "--ct-{$name}: ${color}";
        }

        return "body {" . join( ';', $css ) . "}";
    }
}

new Neighborhood();
