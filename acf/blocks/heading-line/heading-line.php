<?php
/**
 * ACF Field Group: Child Theme: Heading with Line Block
 *
 * Description
 *   Creates a Gutenberg heading block with a line extending out from the left.
 *
 * Use
 *   This Singleton class only needs to be initializeed once by calling:
 *
 *      HeadingLineBlock::init();
 *
 *   This call is at the end of this file.
 */

namespace CT;

class HeadingLineBlock
{
    /**
     * Block name
     */
    private static $name = 'heading-line-block';

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
                    'title'             => 'Heading with leading line',
                    'description'       => 'Heading with leading line',
                    'render_callback'   => [$this, 'display_block'],
                    'category'          => 'child-theme',
                    'icon'              => "", // TODO
                    'keywords'          => ['child theme', 'colter', 'line', 'heading'],
                    'post_types'        => ['page'], // Allow only on page types.
                    'mode'              => 'preview',
                    'supports'          => [
                        'align'	          => true,
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
         * Content
         */
        $line_position = is_numeric( get_field( 'line_position' ) ) ? get_field( 'line_position' ) : '0.65';
    ?>
        <style>
        /* Styles for our block below */

        #<?php echo $id; ?> .ct-content h1:before,
        #<?php echo $id; ?> .ct-content h2:before,
        #<?php echo $id; ?> .ct-content h3:before,
        #<?php echo $id; ?> .ct-content h4:before,
        #<?php echo $id; ?> .ct-content h5:before,
        #<?php echo $id; ?> .ct-content h6:before {
            top: <?php echo $line_position; ?>em;
        }

        </style>

        <section
            id="<?php echo $id; ?>"
            class="ct-acf-block <?php echo self::$unique_name . ' ' . $class; ?>">

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
                $template = [ [ 'core/heading', [] ] ];
            ?>

            <InnerBlocks
                allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_inner_blocks)) ?>"
                template="<?php echo esc_attr(wp_json_encode($template)) ?>"
                templateLock="all" />
        </section>
    <?php
    }
}

HeadingLineBlock::init();