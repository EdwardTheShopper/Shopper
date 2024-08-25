<?php


// For changing Title tag
function override_product_page_title($title)
{

    if (is_singular('product')) {
        global $product;
        $id = get_the_ID();
        $vendor = get_mvx_product_vendors($id);
        $vendor_name = $vendor->user_data->data->display_name;
        $product_name = $product->get_name();
        // Set the new title format
        $new_title = $product_name . ' - ' . $vendor_name;
        // Return the new title
        return $new_title;
    }
    // If not a single product page, return the original title

    return $title;
}
add_filter('pre_get_document_title', 'override_product_page_title', 9999);
