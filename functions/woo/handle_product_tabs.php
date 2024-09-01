<?php

add_filter('woocommerce_product_tabs', 'handle_product_tabs', 20);
function handle_product_tabs($tabs) {

    global $product;


    /* THIS CODE IS RESPONSIBLE FOR CUSTOMIZING THE "RELATED_PRODUCTS" TAB (currently unused) */
    /*
    // check if the product has a category
    if (!empty($product->get_category_ids())) {
        // add related products tab
        $tabs['related_products'] = array(
            'title'    => __('מוצרים דומים', 'woocommerce'),
            'callback' => 'related_products_tab_content'
        );
    }
    /* END OF CUSTOMIZATION */

    // rename / translate all tabs:
    $tabs['description']['title']            = __('תיאור');
    $tabs['additional_information']['title'] = __('מידע נוסף');
    $tabs['reviews']['title']                = __('ביקורות');
    $tabs['related_products']['title']       = __('מוצרים דומים');
    $tabs['vendor']['title']                 = __('חנות');
    $tabs['policies']['title']               = __('מדיניות משלוחים והחזרות');
    $tabs['mvx_customer_qna']['title']       = __('שאלות ותשובות');


    // reorder:
    $tabs['description']['priority']            = 10;
    $tabs['additional_information']['priority'] = 20;
    $tabs['reviews']['priority']                = 30;
    $tabs['related_products']['priority']       = 40;
    $tabs['vendor']['priority']                 = 50;
    $tabs['policies']['priority']               = 60;
    $tabs['mvx_customer_qna']['priority']       = 70;

    // remove unnecessary tabs:
    unset($tabs['vendor']);
    unset($tabs['related_products']);
    unset($tabs['mvx_customer_qna']);

    // conditionally remove description tab 
    if(empty($product->get_short_description()) // being replaced with a custom collapsible description
    || $product->get_short_description() === $product->get_description() // identical
    || empty($product->get_description()) // nothing to show
    )
        unset($tabs['description']); 

    // conditionally remove additional_information tab 
    // NOTE: using "get_attributes" instead of "has_attributes" in order to check for custom attributes as well
    if( empty($product->get_attributes()) && !$product->has_dimensions() && !$product->has_weight())
        unset($tabs['additional_information']);

    return $tabs;
}