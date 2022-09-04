/**
 * Add any setup functions below that our site needs on the backend by the admin.
 */

/**
 * Define any additional buttons we want to create in Gutenberg.
 * We'll define our own in our /scss/components/button.php file.
 */
 ;jQuery(function($) {
    'use strict';

    if (typeof wp === 'undefined') {
        return;
    }

     if (wp && wp.blocks && wp.blocks.unregisterBlockStyle) {

        /**
         * By registering the below styles, the Gutenberg block editor will display
         * additional style options for buttons.
         */
        wp.blocks.registerBlockStyle('core/button', {
            name: 'btn-arrow',
            label: 'Fill Arrow'
        });
    }
 });
