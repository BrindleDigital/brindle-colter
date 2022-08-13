<?php
/**
 * ACF Field Group: Child Theme: Call to Action Block
 *
 * Description
 *   Creates a Gutenberg block intended as a call-to-action section. It defaults to containing
 *   two columns. The left column defaults to containing a heading and paragraph. The right
 *   column defaults to a button.
 *
 * Use
 *   This Singleton class only needs to be initializeed once by calling:
 *
 *      CallToActionBlock::init();
 *
 *   This call is at the end of this file.
 */

namespace CT;

class CallToActionBlock
{
    /**
     * Block name
     */
    private static $name = 'call-to-action-block';

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
        self::$images_url = Setup::$images_uri . '/blocks/call-to-action';

        // Base directory path to our images folder.
        self::$images_dir = Setup::$images_dir. '/blocks/call-to-action';

        /**
         * Dynamically populate the ACF pattern select menu.
         * See https://www.advancedcustomfields.com/resources/dynamically-populate-a-select-fields-choices/
         */
        add_filter( 'acf/load_field/name=middle_pattern_ct_call_to_action', [$this, 'get_pattern_menu'] );
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
                    'title'             => 'Call to Action',
                    'description'       => 'Two-columns with call to action and button.',
                    'render_callback'   => [$this, 'display_block'],
                    'category'          => 'child-theme',
                    'icon'              => "", // TODO
                    'keywords'          => ['child theme', 'colter', 'call', 'action'],
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
         * Top banner layout
         */
        $top_background_color = is_string( get_field( 'top_background_color' ) )
        ? get_field( 'top_background_color' )
        : 'transparent';

        /*
         * Middle banner layout
         */
        $middle_image_url = is_numeric( get_field( 'middle_image_id' ) )
            ? wp_get_attachment_image_url( get_field( 'middle_image_id' ), 'full' )
            : '';

        if ( empty( $middle_image_url ) &&
             is_string( get_field( 'middle_pattern_ct_call_to_action' ) ) &&
             get_field( 'middle_pattern_ct_call_to_action' ) !== 'none' ) {

            $middle_image_url = get_field( 'middle_pattern_ct_call_to_action' );
        }

        $middle_opacity = is_numeric( get_field( 'middle_opacity' ) ) ? get_field( 'middle_opacity' ) : 0.5;

        $middle_background_color = is_string( get_field( 'middle_background_color' ) )
        ? get_field( 'middle_background_color' )
        : 'transparent';

        /*
         * Bottom banner layout
         */
        $bottom_background_color = is_string( get_field( 'bottom_background_color' ) )
        ? get_field( 'bottom_background_color' )
        : 'transparent';
    ?>
        <style>
            #<?php echo $id; ?> .ct-top {
                background-color: <?php echo $top_background_color; ?>;
            }

            <?php if ( ! empty( $middle_background_color ) ) : ?>
                #<?php echo $id; ?> .ct-middle-background {
                    background-color: <?php echo $middle_background_color; ?>;
                }
            <?php endif; ?>

            <?php if ( ! empty( $middle_image_url ) ) : ?>
                #<?php echo $id; ?> .ct-pattern {
                    background-image: url(<?php echo $middle_image_url; ?>);
                    opacity: <?php echo $middle_opacity; ?>;
                }
            <?php endif; ?>

            #<?php echo $id; ?> .ct-bottom {
                background-color: <?php echo $bottom_background_color; ?>;
            }

        </style>

        <section
            id="<?php echo $id; ?>"
            class="ct-acf-block <?php echo self::$unique_name . ' ' . $class; ?>">

            <div class="ct-wrapper">
                <div class="ct-container">
                    <div class="ct-content">
                        <div class="ct-top"></div>
                        <div class="ct-middle">
                            <div class="ct-middle-background">
                                <div class="ct-pattern"></div>
                            </div>
                        </div>
                        <div class="ct-bottom"></div>

                        <div class="ct-cta">
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
                                $template = [
                                    ['generateblocks/container', [],
                                        [
                                            ['generateblocks/container', [],
                                                [
                                                    ['core/columns', [],
                                                        [
                                                            ['core/column', [],
                                                                [
                                                                    ['core/heading', []],
                                                                    ['core/paragraph', []],
                                                                ],
                                                            ],
                                                            ['core/column', [],
                                                                [
                                                                    ['core/buttons', [],
                                                                        [
                                                                            ['core/button', [], []],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ];
                            ?>

                            <InnerBlocks
                                template="<?php echo esc_attr(wp_json_encode($template)) ?>"
                                templateLock="true" />
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
        $regex = '/.+\.(jpg|png|svg)$/i';

        // Get the select menu used by ACF in the editor with paths to pattern files.
        $fields = ACFHelpers::get_select_menu_files_from_directory( $fields, $dir_path, $regex, $url_path );

        return $fields;
    }
}

CallToActionBlock::init();