<?php

/**
 * Author: Edward Ziadeh
 * Date: September 17, 2024
 * The following function is designed to handle the redirection of users
 * attempting to access non-existent or unpublished WooCommerce product pages. 
 * If a user tries to visit a product page that no longer exists,
 * this function ensures they are smoothly redirected to the appropriate store section,
 * preventing broken links or dead-end pages.
 */
function custom_redirect_for_nonexistent_products() {
    // Check if we are on a product page
    if ( !is_singular( 'product' ) ) {
        global $post;

        // If the product doesn't exist or is not published
        if ( ! $post || get_post_status( $post->ID ) != 'publish' ) {

            // Debug line to check if the condition is hit
            error_log('Redirecting due to non-existent product');

            // Redirect code
            global $wp;
            $current_url = home_url( $wp->request );
            $parsed_url = wp_parse_url( $current_url );
            $path_parts = explode( '/', $parsed_url['path'] );

            // Check if the URL contains a 'store' path and redirect
            if ( isset( $path_parts[1] ) && $path_parts[1] === 'store' ) {
                wp_redirect( home_url( "/store/" . $path_parts[2] ), 301 );
                exit;
            }
        }
    }
}
add_action( 'template_redirect', 'custom_redirect_for_nonexistent_products', 1 );
