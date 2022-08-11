<?php
/**
 * ACF Field Group: Child Theme: Primary Hero Block
 *
 * Description
 *   Creates a Gutenberg Hero block intended for the home page.
 *
 * Use
 *   This Singleton class only needs to be initializeed once by calling:
 *
 *      PrimaryHeroBlock::init();
 *
 *   This call is at the end of this file.
 */

namespace CT;

class PrimaryHeroBlock
{
    /**
     * Block name
     */
    private static $name = 'primary-hero-block';

    /**
     * Unique name we'll use for classnames, ids, etc.. Initialized in __construct().
     */
    private static $unique_name = '';

    /**
     * Url to our block images. Initialized in __construct().
     */
    private static $images_url = '';

    /**
     * Directory path to our block images. Initialized in __construct().
     */
    private static $images_dir = '';

    /**
     * Keep an instance of ourselves here.
     */
    private static $obj = null;

    /**
     * Public interface for initializing our block.
     */
    public static function init()
    {
        // Ensure we only have one instance of this Singleton block.
        if ( ! isset( self::$obj ) ) {
            self::$obj = new self();
        }
    }

    /**
     * Instantiate our block.
     */
    private final function __construct()
    {
        $this->setup();
        $this->register_block();
    }

    /**
     * Perform any setup steps.
     */
    protected function setup()
    {
        // Init ourselves.
        self::$unique_name = 'ct-' . self::$name;

        // Base url to our images folder.
        self::$images_url = Setup::$images_uri . '/blocks/primary-hero';

        // Base directory path to our images folder.
        self::$images_dir = Setup::$images_dir. '/blocks/primary-hero';

        // Create additional image sizes unique to our block for better responsive image support.
        add_image_size(self::$unique_name . '-mobile', 900, 600, true);  // Crop images to be 3:2 ratio.
        add_image_size(self::$unique_name . '-tablet', 1200, 800, true);
        add_image_size(self::$unique_name . '-desktop', 5760, 3840, true);

        /**
         * Dynamically populate the ACF pattern select menu.
         * See https://www.advancedcustomfields.com/resources/dynamically-populate-a-select-fields-choices/
         */
        add_filter( 'acf/load_field/name=primary_hero_pattern_selection', [$this, 'get_pattern_menu'] );
    }

    /**
     * Register our ACF block here.
     * See:
     * o https://www.advancedcustomfields.com/resources/acf_register_block_type/
     * o https://developer.wordpress.org/resource/dashicons/
     */
    protected function register_block()
    {
        add_action('acf/init', function () {
            if ( function_exists( 'acf_register_block_type' )) {
                acf_register_block_type([
                    'name'              => self::$unique_name,
                    'title'             => 'Primary hero',
                    'description'       => 'Hero section suitable for your home page.',
                    'render_callback'   => [$this, 'display_block'],
                    'category'          => 'child-theme',
                    'icon'              => "", // TODO
                    'keywords'          => ['child theme', 'colter', 'hero', 'image'],
                    'post_types'        => ['page'], // Allow only on page types.
                    'mode'              => 'preview',
                    'supports'          => [
                        'align'	          => false, // No need to allow user to define block width.
                        'anchor'          => true,
                        'customClassName' => true,
                        'jsx'             => true, // Required true for use with <InnerBlocks />
                    ],
                ]);
            }
        });
    }

    /**
     * ACF Register Block Type callback function. This will display our block html.
     *
     * @param  array      $block      The block settings and attributes.
     * @param  string     $content    The block inner HTML (empty).
     * @param  bool       $is_preview True during AJAX preview.
     * @param  int|string $post_id    The post ID this block is saved to.
     */
    public function display_block( $block, $content = '', $is_preview = false, $post_id = 0 )
    {
        // Pull our ACF fields.
        $anchor = $block['anchor'] ?? '';
        $id     = $block['id'] ?? '';
        $id     = $anchor ? $anchor : ( $id ? $id : wp_unique_id(self::$unique_name) );
        $class  = $block['className'] ?? '';

        /*
         * Content layout
         */
        $content_bottom_padding_mobile = is_numeric( get_field('content_bottom_padding_mobile' ) )
            ? get_field( 'content_bottom_padding_mobile' ) : 0;

        $content_bottom_padding_tablet = is_numeric( get_field('content_bottom_padding_tablet' ) )
            ? get_field( 'content_bottom_padding_tablet' ) : 0;

        $content_bottom_padding_desktop = is_numeric( get_field('content_bottom_padding_desktop' ) )
            ? get_field( 'content_bottom_padding_desktop' ) : 0;

        /*
         * Image layout
         */
        $image_url = self::$images_url . '/photos/placeholder.jpg'; // Default

        $image_mobile_url = is_numeric( get_field( 'image_id' ) )
            ? wp_get_attachment_image_url( get_field( 'image_id' ), self::$unique_name . '-mobile' )
            : $image_url;

        $image_tablet_url = is_numeric( get_field( 'image_id' ) )
            ? wp_get_attachment_image_url( get_field( 'image_id' ), self::$unique_name . '-tablet' )
            : $image_url;

        $image_desktop_url = is_numeric( get_field( 'image_id' ) )
            ? wp_get_attachment_image_url( get_field( 'image_id' ), self::$unique_name . '-desktop' )
            : $image_url;

        /*
         * Side layout
         */
        $side_text = is_string( get_field( 'side_text' ) ) ? get_field( 'side_text' ) : '';

        $side_color = is_string( get_field( 'side_text_color' ) )
            ? get_field( 'side_text_color' )
            : 'var(--contrast)'; // From GeneratePress color palette.

        $side_background_color = is_string( get_field( 'side_background_color' ) )
            ? get_field( 'side_background_color' )
            : 'var(--contrast-4)';    // From GeneratePress color palette.

        /*
         * Bottom layout
         */
        $bottom_background_color = is_string( get_field( 'bottom_background_color' ) )
            ? get_field( 'bottom_background_color' )
            : 'var(--base)';  // From GeneratePress color palette.

        /*
         * Pattern layout
         */
        $pattern_image_url = is_numeric( get_field( 'pattern_image_image_id' ) )
            ? wp_get_attachment_image_url( get_field( 'pattern_image_image_id' ), 'full' )
            : '';

        if ( empty( $pattern_image_url ) &&
             is_string( get_field( 'pattern_image_primary_hero_pattern_selection' ) ) &&
             get_field( 'pattern_image_primary_hero_pattern_selection' ) !== 'none' ) {

            $pattern_image_url = get_field( 'pattern_image_primary_hero_pattern_selection' );
        }

        $pattern_opacity = is_numeric( get_field( 'pattern_opacity' ) ) ? get_field( 'pattern_opacity' ) : 0.5;
    ?>
        <style>
            /* Styles for our Primary Hero block below */

            #<?php echo $id; ?> .ct-container {
                background-color: <?php echo $bottom_background_color; ?>;
            }

            #<?php echo $id; ?> .ct-side-container {
                background-color: <?php echo $side_background_color; ?>;
                color: <?php echo $side_color; ?>;
            }

            #<?php echo $id; ?> .ct-image-container {
                background: url(<?php echo $image_mobile_url; ?>) center/cover;
            }

            @media (min-width: <?php echo Helpers::$breakpoint['tablet']; ?>) {
                #<?php echo $id; ?> .ct-image-container {
                    background: linear-gradient(180deg, rgba(234, 234, 234, 0.6) 17.19%, rgba(255, 255, 255, 0.6) 100%), linear-gradient(180deg, rgba(0, 0, 0, 0.6) 0%, rgba(255, 255, 255, 0.48) 100%), url(<?php echo $image_tablet_url; ?>) center/cover;
                    background-blend-mode: soft-light, overlay, normal;
                }
            }

            @media (min-width: <?php echo Helpers::$breakpoint['desktop']; ?>) {
                #<?php echo $id; ?> .ct-image-container {
                    background: linear-gradient(180deg, rgba(234, 234, 234, 0.6) 17.19%, rgba(255, 255, 255, 0.6) 100%), linear-gradient(180deg, rgba(0, 0, 0, 0.6) 0%, rgba(255, 255, 255, 0.48) 100%), url(<?php echo $image_desktop_url; ?>) center/cover;
                    background-blend-mode: soft-light, overlay, normal;
                }
            }

            <?php if ( ! empty( $pattern_image_url ) ) : ?>
                #<?php echo $id; ?> .ct-pattern-container {
                    background-image: url(<?php echo $pattern_image_url; ?>);
                    opacity: <?php echo $pattern_opacity; ?>;
                }
            <?php endif; ?>

            #<?php echo $id; ?> .ct-heading {
                padding-bottom: <?php echo $content_bottom_padding_mobile; ?>rem;
            }

            @media (min-width: <?php echo Helpers::$breakpoint['tablet']; ?>) {
                #<?php echo $id; ?> .ct-heading {
                    padding-bottom: <?php echo $content_bottom_padding_tablet; ?>rem;
                }
            }

            @media (min-width: <?php echo Helpers::$breakpoint['desktop']; ?>) {
                #<?php echo $id; ?> .ct-heading {
                    padding-bottom: <?php echo $content_bottom_padding_desktop; ?>rem;
                }
            }
        </style>

        <section
            id="<?php echo $id; ?>"
            class="ct-acf-block <?php echo self::$unique_name . ' ' . $class; ?>">

            <div class="ct-container">

                <div class="ct-side-container">
                    <div class="ct-side-text"><?php echo $side_text; ?></div>
                </div>

                <div class="ct-image-container"></div>

                <div class="ct-pattern-container"></div>

                <div class="ct-arrow-container"><img
                    class="ct-arrow"
                    src="<?php echo self::$images_url . '/graphics/arrow.png'; ?>;" /></div>

                <div class="ct-content-container">
                    <div class="ct-content">
                        <div class="ct-heading">
                            <?php
                                /* Notes:
                                *   ACF InnerBlocks are not well documented, so in summary...
                                *   ACF InnerBlocks may contain several attributes. The most used:
                                *     template:      [[ 'core/paragraph' ], ...]
                                *     allowedBlocks: [ 'core/image', 'core/paragraph' ];
                                *     templateLock:  'all', 'insert', or boolean
                                *
                                *   The template will accept configuration parameters that will set the values for the
                                *   inner blocks in the editor sidebar. These parameters are typically the same as those
                                *   accepted for the block in REACT. You'll have to do some digging to find these, but
                                *   you can start with these resources:
                                *
                                *   Examples: https://www.billerickson.net/innerblocks-with-acf-blocks/#template-lock-with-innerblocks
                                *   Definitive: https://github.com/WordPress/gutenberg/tree/master/packages/block-editor/src/components/inner-blocks
                                *   GB Blocks: https://github.com/WordPress/gutenberg (See /gutenberg/packages/block-library/src)
                                */

                                // Our block may only contain a Gutenberg heading block.
                                $allowed_inner_blocks = ['core/heading'];

                                $template = [
                                    [
                                        'core/heading', [
                                            'level' => 1,
                                            'content' => 'Welcome to<br>The Colter',
                                        ]
                                    ]
                                ];
                            ?>

                            <InnerBlocks
                                allowedBlocks="<? echo esc_attr(wp_json_encode($allowed_inner_blocks)) ?>"
                                template="<? echo esc_attr(wp_json_encode($template)) ?>"
                                templateLock="all" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
    }

    /**
     * Dynamically populate our ACF pattern select menu.
     *
     * @param  array  $fields  ACF select menu settings
     * @return array  $fields
     */
    public function get_pattern_menu( $fields )
    {
        // Only get the files from our patterns directory.
        $dir_path = self::$images_dir . '/patterns';

        // The url path that we'll use to access those files.
        $url_path = self::$images_url . '/patterns';

        // Only image files.
        $regex = '/.+\.(jpg|png)$/i';

        // Get the select menu used by ACF in the editor with paths to pattern files.
        $fields = ACFHelpers::get_select_menu_files_from_directory( $fields, $dir_path, $regex, $url_path );

        return $fields;
    }
}

PrimaryHeroBlock::init();