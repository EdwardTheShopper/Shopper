<?php

// rename tabs in product-page
add_filter('woocommerce_product_tabs', 'rename_tabs', 98);
function rename_tabs($tabs) {
    $tabs['description']['title']       = __('תיאור');
    $tabs['reviews']['title']           = __('ביקורות');
    $tabs['related_products']['title']  = __('מוצרים דומים');
    $tabs['policies']['title']          = __('מדיניות');
    $tabs['vendor']['title']            = __('חנות');
    $tabs['mvx_customer_qna']['title']  = __('שאלות ותשובות');
    return $tabs;
}