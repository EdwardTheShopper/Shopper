<?php
//include 'functions/payments/payments_methods.php'; // in order to use 'get_vendor_id_from_cart()'
//include 'functions/woo/checkout/pickup_order.php'; // in order to use 'get_enable_delivery()'
//include 'functions/woo/checkout/address.php'; // in order to use 'reverse_fix_parenthesis()'

/* define shipping options */
add_filter('woocommerce_checkout_fields', 'define_shipping_options');
function define_shipping_options($fields) {
    $fields['billing']['delivery_rules'] = array(
        'id' => 'delivery_rules',
        'type' => 'radio',
        'options' => array('נא לבחור ישוב'), // options will be set after the user chooses a city
        'class' => array('form-row-wide'),
        'label' =>  __('אפשרויות משלוח'),
        'required' => true,
    );
    return $fields;
}

/* on start, show the default message without the radio button */
add_action('wp_footer', 'clear_radio_button_on_start');
function clear_radio_button_on_start() {
    if (is_checkout()) : ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // clear the radio button itself
                $('#delivery_rules_field .woocommerce-input-wrapper input[name="delivery_rules"]').remove();
            });
        </script>
    <?php endif;
}

/* show general information about shipping options
   triggered on first load (no chosen city) or if there are no available shipping options */
function get_default_message() {
    $message = 'ע"פ מדיניות החנות ניתן לבצע משלוח ע"פ התנאים הבאים:';

    for($i=1; $i<=3; $i++) {
        $delivery_rule = get_delivery_rule($i);
        if($delivery_rule && $delivery_rule['active']) {
            $message .= '<br/><br/><div id="delivery_rules_' . $i . '_wrapper" style="display: flex">'
            . parse_rule($delivery_rule) . '</div>';
        }
        else break; // rest of the rules are always inactive
    }
    return $message;
}

/* get ACF custom field for this specific store */
function get_delivery_rule($number) {
    $vendor_id = get_vendor_id_from_cart();
    $delivery_rule = get_field('delivery_rule_' . $number, 'user_' . $vendor_id);    
    return $delivery_rule['active'] ? $delivery_rule : null;
}

/* parse delivery rule to simple text */
function parse_rule($delivery_rule) {
    if(!$delivery_rule || !$delivery_rule['active'])
        return '';
    return
        'מינימום הזמנה: ' 
        . ($delivery_rule['minimum_order'] ? '&#8362;' . $delivery_rule['minimum_order'] : 'ללא') .
        '. איזור: '
        . (!empty($delivery_rule['cities']) ? print_cities($delivery_rule['cities']) : 'כל הארץ') .
        '. מחיר משלוח: '
        . (!empty($delivery_rule['shipping_cost'] && !$delivery_rule['shipping_cost'] == 0) ?
                '&#8362;' . $delivery_rule['shipping_cost'] : 'חינם') . '. ' . $delivery_rule['additional_text'];
}

/* parse an array of cities to a simple string */
function print_cities($cities) {
    return implode(', ', $cities);
}

/* checks if the order details are matched with a specific delivery rule */
function matched_to_rule($delivery_rule, $chosen_city, $total_order_price) {
    $chosen_city_is_included = empty($delivery_rule['cities']) ? // כל הארץ
        true : in_array($chosen_city, $delivery_rule['cities']);
    
    $total_order_price = str_replace('&#8362;', '', $total_order_price); // remove currency symbol
    $total_order_price = preg_replace('/[^0-9.]/', '', $total_order_price); // remove everything else (HTML tags etc..)
    $minimum_order_is_reached = (empty($delivery_rule['minimum_order']) || $delivery_rule['minimum_order'] == 0) ? // חינם
        true : floatval($total_order_price) >= floatval($delivery_rule['minimum_order']);
    
    return $chosen_city_is_included && $minimum_order_is_reached;
}

/* go over all delivery rules and keep only the available ones (according to chosen city and total order price)
   for each available rule, also adds the shipping cost separately (for an easy access later on) */
function filter_shipping_options($chosen_city) {
    $total_order_price = WC()->cart->get_cart_total();
    $available_options = array();

    for($i=1; $i<=3; $i++) {
        $delivery_rule = get_delivery_rule($i);
        if($delivery_rule && $delivery_rule['active']) {
            if(matched_to_rule($delivery_rule, $chosen_city, $total_order_price))
                array_push($available_options, [parse_rule($delivery_rule), $delivery_rule['shipping_cost']]);
        }
        else break; // rest of the rules are always inactive
    }
    return $available_options; // might be empty (handled in 'get_shipping_options')
}

/* enqueue scripts */
add_action('wp_enqueue_scripts', 'enqueue_custom_checkout_shipping_options_script');
function enqueue_custom_checkout_shipping_options_script() {
    if(is_checkout()) {
        wp_enqueue_script('custom-checkout_shipping_options', get_template_directory_uri() . '-child/assets/js/custom/shipping_options.js', array('jquery'), null, true);
        wp_localize_script('custom-checkout_shipping_options', 'custom_checkout_params', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}

/* triggered by shipping_options.js script
   on every city selection - get the available shipping options according to the chosen city */
add_action('wp_ajax_get_shipping_options', 'get_shipping_options');
add_action('wp_ajax_nopriv_get_shipping_options', 'get_shipping_options');
function get_shipping_options() {
    $chosen_city = sanitize_text_field($_GET['chosen_city']);
    $chosen_city = reverse_fix_parenthesis($chosen_city); // this is the "fixed" value, need to get the original string of chosen city
    $available_options = filter_shipping_options($chosen_city);

    if(!$available_options) // no match to delivery rules
        wp_send_json_error('לא נמצא משלוח זמין עבורך. ' . get_default_message());
    else
        wp_send_json_success($available_options);
}

/* triggered by shipping_options.js script
   on every shipping option selection - add the shipping cost to the cart and re-calculate the total amount */
add_action('wp_ajax_add_shipping_cost_to_cart', 'add_shipping_cost_to_cart');
add_action('wp_ajax_nopriv_add_shipping_cost_to_cart', 'add_shipping_cost_to_cart');
function add_shipping_cost_to_cart() {
    if (isset($_POST['shipping_cost'])) {
        $shipping_cost = empty($_POST['shipping_cost']) ? 0 : floatval($_POST['shipping_cost']);
        WC()->session->set('custom_shipping_cost', $shipping_cost);
        WC()->cart->calculate_totals();
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}

// apply the custom shipping cost to the cart totals
add_action('woocommerce_cart_calculate_fees', 'apply_custom_shipping_cost');
function apply_custom_shipping_cost($cart) {
    if(is_admin() && !defined('DOING_AJAX')) return;
    
    if(is_checkout()) { // don't run on other pages
        $shipping_cost = WC()->session->get('custom_shipping_cost');
        if($shipping_cost)
            $cart->add_fee(__('Shipping', 'woocommerce'), $shipping_cost);
    }
}

/* when the user leaves the checkout page without completing the order (or refreshes it),
   any previous shipping-cost will be removed from the cart (if exists) */
add_Action('wp_footer', 'clear_shipping_cost_from_cart');
function clear_shipping_cost_from_cart() {
    ?>
        <script type="text/javascript">
           jQuery(document).ready(function($) {
               var delivery_in_cart = $('tr.fee');
               if(delivery_in_cart.length) {
                   delivery_in_cart.remove(); // do it first for better display
                   $.ajax({
                       type: 'POST',
                       url: wc_checkout_params.ajax_url,
                       data: {
                           action: 'add_shipping_cost_to_cart',
                           shipping_cost: 0
                       },
                       success: function(response) {
                           if(response.success)
                               $(document.body).trigger('update_checkout');
                       }
                   });  
               }
           });
       </script>
    <?php
}