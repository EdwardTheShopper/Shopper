<?php
// Remove product data tabs
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);
function woo_remove_product_tabs($tabs)
{
    unset($tabs['wcmp_customer_qna']);
    return $tabs;
}