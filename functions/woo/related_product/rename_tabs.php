<?php

// Rename product data tabs
add_filter('woocommerce_product_tabs', 'woo_rename_tabs', 98);
function woo_rename_tabs($tabs)
{
    $tabs['reviews']['title'] = __('ביקורות');
    return $tabs;
}
