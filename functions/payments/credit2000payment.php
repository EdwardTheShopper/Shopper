<?php

add_filter('woocommerce_available_payment_gateways', 'conditionally_hide_payment_gateways', 10, 1);

function conditionally_hide_payment_gateways($available_gateways)
{
    if (function_exists('WC') && WC()->cart) {
        $cartItems = WC()->cart->get_cart();
        $first_cart_item = reset($cartItems);
        if (!empty($cartItems)) {
            // Get the product ID
            $first_product_id = $first_cart_item['product_id'];
            $vendor_id = get_post_field('post_author', $first_product_id);
        }
    }

    // Check if we are on the checkout page

    if (is_checkout() && !is_wc_endpoint_url()) {

        $enable_payment = get_field('acf_enable_payment', 'user_' . $vendor_id);
        $enable_payment = $enable_payment[0] ?? 0;
       //$available_gateways['credit2000']->settings['c2000_CompanyKey'] = $c2000_key;
       //$available_gateways['credit2000']->settings['c2000_VendorName'] = $c2000_name;
        /* echo '<pre>';
        print_r($available_gateways['credit2000']);
        echo '</pre>'; */
        // If the ACF field "acf_enable_payment" is false, unset all payment methods except for a specific condition
        // Check if the specific payment gateway should be hidden
        if (!$enable_payment) {
            // Unset the specific payment gateway if the ACF field is false
            unset($available_gateways['credit2000']);
        }
        if(get_enable_delivery() && /* TODO: shipping method == pickup */false) {
            unset($available_gateways['cod']);
        }
    }
    return $available_gateways;
}
