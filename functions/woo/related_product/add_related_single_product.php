<?php

// Related Product Issue | added on 17-April-2023
// Add related products tab after single product
add_filter('woocommerce_product_tabs', 'add_related_products_tab');
function add_related_products_tab($tabs)
{
    global $product;
    // Get product category
    $categories = $product->get_category_ids();

    // Check if product has category
    if (!empty($categories)) {
        $tabs['related_products'] = array(
            'title'    => __('מוצרים דומים', 'woocommerce'),
            'priority' => 50,
            'callback' => 'related_products_tab_content'
        );
    }
    return $tabs;
}


