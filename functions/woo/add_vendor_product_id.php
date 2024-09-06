<?php
/**
 * Add a custom field "Vendor Product ID" to WooCommerce products and include it in the product export.
 *
 * This code adds a custom field to the product data tab in WooCommerce where admins can input a Vendor Product ID.
 * The custom field is then saved as product meta and included in the WooCommerce product export CSV.
 *
 * Author: Edward Ziadeh
 * Date: September 2024
 */


 // Add the Vendor Product ID field in the Product Data tab
add_action( 'woocommerce_product_options_general_product_data', 'add_vendor_product_id_field' );
function add_vendor_product_id_field() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    woocommerce_wp_text_input( array(
        'id' => 'vendor_product_id',
        'label' => 'Vendor Product ID',
        'placeholder' => 'Enter Vendor Product ID',
        'desc_tip' => 'true',
        'description' => 'Vendor Product ID for the product.',
        'type' => 'text',
    ) );
    
    echo '</div>';
}

// Save Vendor Product ID field value
add_action( 'woocommerce_process_product_meta', 'save_vendor_product_id_field' );
function save_vendor_product_id_field( $post_id ) {
    $vendor_product_id = isset( $_POST['vendor_product_id'] ) ? sanitize_text_field( $_POST['vendor_product_id'] ) : '';
    update_post_meta( $post_id, 'vendor_product_id', $vendor_product_id );
}


// Add custom field to product export columns
add_filter( 'woocommerce_product_export_meta_columns', 'add_vendor_product_id_to_export' );
function add_vendor_product_id_to_export( $columns ) {
    $columns['vendor_product_id'] = 'Vendor Product ID';
    return $columns;
}
// Add custom field data to the product export
add_filter( 'woocommerce_product_export_row_data', 'add_vendor_product_id_export_data', 10, 3 );
function add_vendor_product_id_export_data( $data, $product, $row ) {
    $data['vendor_product_id'] = get_post_meta( $product->get_id(), 'vendor_product_id', true );
    return $data;
}
