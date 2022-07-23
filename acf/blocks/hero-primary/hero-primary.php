<?php
/**
 * ACF Field Group: CTBlock: Hero Primary
 *
 * Description
 *   Creates a Gutenberg Hero block intended for the home page.
 *
 * Use
 *   This Singleton class only needs to be initializeed once by calling:
 *
 *      HeroPrimaryBlock::init();
 *
 *   This call is at the end of this file.
 */

namespace CT;

class HeroPrimaryBlock
{
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
                    'name'              => 'ct-hero-primary',
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
        dump_log($block);

        // Pull our ACF fields.
        $anchor = $block['anchor'] ?? '';
        $id     = $block['id'] ?? '';
        $id     = $anchor ? $anchor : ( $id ? $id : wp_unique_id('ct-hero-primary-') );
        $class  = $block['className'] ?? '';

        // Get heading.
        $heading       = get_field( 'heading' );
        $heading_text  = $heading['text'] ?: '';
        $heading_color = $heading['color'] ?: '';
    ?>

        <section
            id="<?php echo $id; ?>"
            class="ct-acf-block ct-hero-primary-block <?php echo $class; ?>">

            <div class="ct-container">

                <div class="ct-top-container">
                    <div class="ct-top-text">Apartments in Fort Collins, CO</div>
                </div>

                <div class="ct-image-container">
                    <img src="/wp-content/uploads/welcome-to-the-colter-scaled.jpg">
                </div>

                <div class="ct-pattern-container"
                    style="background-image: url(/wp-content/uploads/pattern-squares-transparent-light-300x210-1.png);"></div>

            </div>
        </section>
    <?php
    }
}

HeroPrimaryBlock::init();