<?php

add_filter('wt_product_import_allow_same_sku', '__return_true');

add_filter('wc_product_has_unique_sku', 'wt_product_has_unique_sku', 10, 3);

function wt_product_has_unique_sku($sku_found, $product_id, $sku)
{
    return false;
}
