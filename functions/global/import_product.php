<?php
/** 
 * This code was provided from webtoofee to fix some issues
*/

add_action('wt_woocommerce_product_import_inserted_product_object', 'wt_woocommerce_product_import_inserted_product_object', 10, 2);

function wt_woocommerce_product_import_inserted_product_object($object, $data)
{
    $product = wc_get_product($object->get_id());

    $product->save();
}