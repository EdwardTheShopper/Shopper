<?php
/**
 * Modify WooCommerce product image attributes on the fly.
 * 
 * This function dynamically sets the title, alt text, caption, and description
 * for product images when a WooCommerce product page is loaded.
 *
 * Author: Edward Ziadeh
 * Date: August 13, 2024
 *
 * @param array $attr       An array of attributes for the image.
 * @param WP_Post $attachment The attachment post object.
 * @param string|array $size The requested size.
 * @return array Modified array of attributes.
 */

function custom_product_image_attributes( $attr, $attachment, $size ) {
    // Check if the current post is a WooCommerce product
    if ( is_product() ) {
        global $product;
        
        // Get the current product's name
        $product_name = $product->get_name();
        
        // Set dynamic values
        $attr['title'] = $product_name . ' - ' . get_the_title( $attachment->ID );
        $attr['alt'] = $product_name;
        $attr['caption'] = $product_name;
        $attr['description'] = $product_name;
    }
    
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'custom_product_image_attributes', 10, 3 );