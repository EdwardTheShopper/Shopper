<?php
/** 
 * Validation cart to make sure that only one store can be in cart
*/
add_action('woocommerce_add_to_cart_validation','woocommerce_add_to_cart_validation',10,3);
function woocommerce_add_to_cart_validation($passed, $product_id, $quantity){
    foreach (WC()->cart->get_cart() as $cart_key => $cart_item ){
        $cart_vendor = get_mvx_product_vendors($cart_item['product_id']);
        $product_vendor = get_mvx_product_vendors($product_id);
        if($cart_vendor && $product_vendor){
            if($cart_vendor->id != $product_vendor->id){
                $passed = false;
                wc_add_notice( __( 'Another vendor product is already in your cart.', 'woocommerce' ), 'error' );
                return $passed;
            }
        }
    }
    return $passed;
}