<?php

// this file is replacing 'credit2000payment.php file.
// the following code was tested and it works fine!

add_filter('woocommerce_available_payment_gateways', 'conditionally_hide_payment_gateways', 10, 1);
function conditionally_hide_payment_gateways($available_gateways) {
    if(is_checkout() && !is_wc_endpoint_url()) {
        $vendor_id = get_vendor_id_from_cart();
    
        $enable_delivery = get_field('acf_enable_delivery', 'user_' . $vendor_id);
        $enable_delivery = empty($enable_delivery)? 0 : 1;
    
        $enable_payment = get_field('acf_enable_payment', 'user_' . $vendor_id);
        $enable_payment = $enable_payment[0] ?? 0;
    
        if(!$enable_payment)
            unset($available_gateways['credit2000']);
        if($enable_delivery)
            unset($available_gateways['cod']);
    }
    return $available_gateways;
}

function get_vendor_id_from_cart() {
    if(function_exists('WC') && WC()->cart) {
        $cartItems = WC()->cart->get_cart();
        $first_cart_item = reset($cartItems);
        if (!empty($cartItems)) {
            // Get the product ID
            $first_product_id = $first_cart_item['product_id'];
            $vendor_id = get_post_field('post_author', $first_product_id);
        }
    }
    return $vendor_id;
}