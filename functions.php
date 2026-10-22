<?php
if ( ! defined( 'CHILD_THEME_VERSION' ) ) {
    define( 'CHILD_THEME_VERSION', '1.0.7' );
}
/*
* Add custom css
*/
add_action("wp_enqueue_scripts", "brindle_enqueue_styles");
function brindle_enqueue_styles()
{
  
 
  wp_enqueue_style("slick-style",   "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css",array(), CHILD_THEME_VERSION);
  wp_enqueue_style("slick-theme-style",   "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css",array(), CHILD_THEME_VERSION);
  wp_enqueue_style("magnific-popup-style",   "https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/magnific-popup.min.css");
}

function brindle_enqueue_scripts()
{
    wp_enqueue_script( 'slick-script', "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js",["jquery"],CHILD_THEME_VERSION,true);  
    wp_enqueue_script( 'magnific-popup-script', "https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/jquery.magnific-popup.min.js",["jquery"],"1.0",true);
 
}
add_action("wp_enqueue_scripts", "brindle_enqueue_scripts");

/**
 * Load the files in the given array. Missing files will produce a fatal error.
 *
 * @param array $files
 */
function ct_load_files( $files ) {
    array_walk( $files, function ( $file ) {
        if ( ! locate_template( $file, true, true ) ) {
            //trigger_error(sprintf( 'Error locating %s for inclusion', $file ), E_USER_ERROR );
            error_log("Missing file: " . $file);
        }
    });
}

// Kick us off by loading our required theme files, in order.'lib/customizer/colors/neighborhood.php', 128 setup.php
ct_load_files( [
    'lib/helpers.php',
    'lib/setup.php',
    'lib/customizer/colors/acf.php',
    'lib/customizer/colors/generate-press.php',    
    'lib/customizer/colors/wp.php',
    'lib/custom-post-types/neighborhood/neighborhood.php',
    'acf/setup.php',
] );



function show_menu_list($attr){
    ob_start();
    $menu = '';
    if(isset($attr['menu'])){
        $menu = $attr['menu']; 
    } 
    if($menu !=''){
        ?>
        <div class="widget widget_nav_menu"><?php echo wp_nav_menu( array( 'menu' => $menu ) );?></div>
        <?php

    }
    $output = ob_get_clean();
    return $output;
}

function register_shortcodes()
{
    add_shortcode("show-menu", "show_menu_list");  
}
add_action("init", "register_shortcodes");