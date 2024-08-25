<?php
/**
 * Remove popup image
*/
function remove_image_zoom_support_shopper()
{
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-slider');
}
add_action('wp', 'remove_image_zoom_support_shopper', 100);