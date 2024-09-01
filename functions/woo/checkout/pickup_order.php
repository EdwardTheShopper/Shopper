<?php
include 'functions/payments/payments_methods.php'; // in order to use 'get_vendor_id_from_cart()'
include 'functions/woo/checkout/shipping_options.php'; // in order to use 'add_shipping_cost_to_cart()'
/*
    (not urgent)
    [TODO] add the missing comments to explain the functions of this file
*/

/* ?? */
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
function enqueue_custom_scripts() {
    if (is_checkout())
        wp_enqueue_script('jquery');
}

/* get ACF custom field for this specific store */
function get_enable_delivery() {
    $vendor_id = get_vendor_id_from_cart();
    $enable_delivery = get_field('acf_enable_delivery', 'user_' . $vendor_id);
    return empty($enable_delivery) ? 0 : 1;
}

/* define a checkbox for "איסוף עצמי" */
add_filter('woocommerce_checkout_fields', 'define_shipping_method_checkbox');
function define_shipping_method_checkbox($fields) {
    $fields['billing']['pickup_option'] = array(
        'type' => 'checkbox',
        'class' => array('pickup-option form-row-wide'),
        'label' => __('איסוף עצמי'),
        'required' => !get_enable_delivery(), // optional only if delivery is available for this store
    );
    return $fields;
}

/* define customer details fields, including their order of appearance and custom labels / placeholders */
add_filter('woocommerce_checkout_fields', 'define_checkout_fields', 20);
function define_checkout_fields($fields) {
    // manually set priorities:
    $fields['billing']['billing_first_name']['priority']= 1;
    $fields['billing']['billing_last_name']['priority'] = 2;
    $fields['billing']['billing_phone']['priority']     = 3;
    $fields['billing']['billing_email']['priority']     = 4;
    $fields['billing']['billing_company']['priority']   = 5;
    $fields['billing']['pickup_option']['priority']     = 6; // shipping method checkbox
    $fields['billing']['billing_city']['priority']      = 7;
    $fields['billing']['billing_address_1']['priority'] = 8;
    $fields['billing']['billing_postcode']['priority']  = 9; // represents street-number
    $fields['billing']['billing_address_2']['priority'] = 10;
    $fields['billing']['delivery_rules']['priority']    = 11;
    // 'accept_policies' priority will be set conditionally from policies.php ( = 12);

    // manually set labels and placeholders:
    $fields['billing']['billing_city']['label'] = 'ישוב';
    $fields['billing']['billing_address_1']['label'] = 'שם רחוב';
    $fields['billing']['billing_postcode']['label'] = 'מספר רחוב'; // represents street-number
    $fields['billing']['billing_address_2']['placeholder'] = "דירה, יחידה, כניסה וכו' (אופציונלי)";
    $fields['order']['order_comments']['placeholder'] = "הערות להזמנה, לדוגמה, הערות מיוחדות למסירה.";

    // using postcode to represent street-number
    $fields['billing']['billing_postcode']['type'] = 'number';

    return $fields;
}

/* permanently disable unused fields */
add_filter('woocommerce_checkout_fields', 'unset_unused_fields', 30);
function unset_unused_fields($fields) {
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_country']);
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_state']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_city']);
    unset($fields['shipping']['shipping_address_1']);
    unset($fields['shipping']['shipping_address_2']);
    unset($fields['shipping']['shipping_postcode']);
    return $fields;
}

/* permanently disable delivery fields for stores that has no delivery available */
add_filter('woocommerce_checkout_fields', 'unset_delivery_fields', 40);
function unset_delivery_fields($fields) {
    if(!get_enable_delivery()) {
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_postcode']); // represents street-number
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['delivery_rules']);
    }
    return $fields;
}

/* disable delivery fields (after submit) if the user chose "איסוף עצמי" */
add_filter('woocommerce_checkout_fields', 'conditionally_unset_delivery_fields', 50);
function conditionally_unset_delivery_fields($fields) {
    if(isset($_POST['pickup_option']) && $_POST['pickup_option'] == 1) {
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_postcode']); // represents street-number
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['delivery_rules']);
    }
    return $fields;
}

/* show / hide delivery fields on every change in "איסוף עצמי" checkbox (relevant only for stores with delivery) */
add_action('wp_footer', 'conditionally_show_delivery_fields');
function conditionally_show_delivery_fields() {
    if(!get_enable_delivery())
        return;
    if(is_checkout()) : ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#pickup_option').change(function() {
                    if ($(this).is(':checked')) {
                        $('#billing_city_field').hide();
                        $('#billing_city_field').prev().remove();
                        $('#billing_address_1_field').hide();
                        $('#billing_postcode_field').hide(); // represents street-number
                        $('#billing_address_2_field').hide();

                        // handle delivery fields
                        $('#delivery_rules_field').hide();
                        $('#delivery_rules_field input[type="radio"]').prop('checked', false); // reset the #delivery_rules_field radio buttons
                        $.ajax({ // remove previous shipping cost from the cart (if exists)
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
                        })
                    } else {
                        $('#billing_city_field').show();
                        $('#billing_city_field').before('<div style="width:100%; padding: 10px"><h3>פרטי משלוח</h3></div>')
                        $('#billing_address_1_field').show();
                        $('#billing_postcode_field').show(); // represents street-number
                        $('#billing_address_2_field').show();
                        $('#delivery_rules_field').show();
                    }
                }).trigger('change');
            });
        </script>
    <?php endif;
}

/* ?? */
add_action('woocommerce_checkout_update_order_meta', 'pickup_field_update_order_meta');
function pickup_field_update_order_meta($order_id) {
    if(isset($_POST['pickup_option']))
        update_post_meta($order_id, 'pickup_option', esc_attr($_POST['pickup_option']));
}

/* ?? */
add_action('woocommerce_admin_order_data_after_billing_address', 'display_pickup_option_in_admin', 10, 1);
function display_pickup_option_in_admin($order) {
    $pickup = get_post_meta($order->get_id(), 'pickup_option', true);
    if($pickup)
        echo '<p><strong>' . __('Pickup Option:') . '</strong> ' . __('Yes') . '</p>';
}

/* enqueue script */
add_action('wp_enqueue_scripts', 'enqueue_custom_checkout_characters_limit_script');
function enqueue_custom_checkout_characters_limit_script() {
    if (is_checkout()) {
        wp_enqueue_script('custom-checkout_characters_limit', get_template_directory_uri() . '-child/assets/js/custom/characters_limit.js', array('jquery'), null, true);
        wp_localize_script('custom-checkout_characters_limit', 'custom_checkout_params', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}
