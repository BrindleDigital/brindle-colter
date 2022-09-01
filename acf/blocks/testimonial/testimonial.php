<?php
/**
 * ACF Field Group: Child Theme: Testimonial Block
 *
 * Description
 *   Creates a testimonial Gutenberg block.
 *
 * Use
 *   This Singleton class only needs to be initializeed once by calling:
 *
 *      TestimonialBlock::init();
 *
 *   This call is at the end of this file.
 */

namespace CT;

class TestimonialBlock
{
    /**
     * Block name
     */
    private static $name = 'testimonial-block';

    /**
     * Unique name we'll use for classnames, ids, etc.. Initialized in __construct().
     */
    private static $unique_name = '';

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
        // Init ourselves.
        self::$unique_name = 'ct-' . self::$name;

        $this->register_block();
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
                    'title'             => 'Testimonial',
                    'description'       => 'Single stylized testimonial.',
                    'render_callback'   => [$this, 'display_block'],
                    'category'          => Setup::$block_category['slug'],
                    'icon'              => Setup::$block_category['icon'],
                    'keywords'          => ['child theme', 'colter', 'testimonial'],
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
    ?>
        <section
            id="<?php echo $id; ?>"
            class="ct-acf-block <?php echo self::$unique_name . ' ' . $class; ?>">

            <div class="ct-wrapper">
                <div class="ct-container">
                    <div class="ct-icon">
                        <img src="<?php echo Setup::$images_uri . '/blocks/testimonial/quote.png'; ?>"
                            alt="" role="presentation">
                    </div>

                    <div class="ct-content">
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
                            $allowed_inner_blocks = ['core/quote'];

                            $template = [
                                [
                                    'core/quote'
                                ]
                            ];
                        ?>

                        <InnerBlocks
                            allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_inner_blocks)) ?>"
                            template="<?php echo esc_attr(wp_json_encode($template)) ?>"
                            templateLock="all" />
                    </div>
                </div>
            </div>
        </section>
    <?php
    }
}

TestimonialBlock::init();