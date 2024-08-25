<?php
include 'functions/payments/payments_methods.php'; // in order to use 'get_vendor_id_from_cart()'


/* define a checkbox for the user to accept policies */
add_filter('woocommerce_checkout_fields', 'define_accept_policies_checkbox');
function define_accept_policies_checkbox($fields) {
    if(at_least_one_policy_exists()) {
        $fields['billing']['accept_policies'] = array(
            'type' => 'checkbox',
            'class' => array('form-row-wide', 'accept-policies-checkbox'),
            'label' => __('קראתי ואני מסכימ.ה לתקנוני בית העסק הבאים:', 'woocommerce'),
            'required' => true,
            'label_class' => array('woocommerce-form__label', 'woocommerce-form__label-for-checkbox', 'checkbox')
        );
        $fields['billing']['accept_policies']['priority'] = 12;
    }
    return $fields;
}

/* define store policies */
add_action('woocommerce_before_order_notes', 'define_store_policies');
function define_store_policies() {
    $policies = fetch_store_policies();
    echo '<div class="store-policies" style="display: flex; flex-direction: column; width: 100%; margin-right: 30px;">';
    if($policies[0] != 'No policy found' && $policies[0] != '')
        print_policy(__('מדיניות משלוחים / איסוף'), $policies[0], 0);
    if($policies[1] != 'No policy found' && $policies[1] != '')
        print_policy(__('מדיניות זיכויים'), $policies[1], 1);
    if($policies[2] != 'No policy found' && $policies[2] != '')
        print_policy(__('מדיניות ביטולים / החזרות / החלפות'), $policies[2], 2);
    echo '</div>';
}

/* show error message (after submit) if 'accept_policies_checkbox' is defined and remained unchecked */
add_action('woocommerce_checkout_process', 'validate_accept_policies_checkbox');
function validate_accept_policies_checkbox() {
    if(at_least_one_policy_exists() && !isset($_POST['accept_policies']))
        wc_add_notice(__('כדי להמשיך בהזמנה, יש לקרוא את תקנוני בית העסק ולקבל אותם.', 'woocommerce'), 'error');
}

add_action('woocommerce_checkout_update_order_meta', 'save_accept_policies_checkbox');
function save_accept_policies_checkbox($order_id) {
    if ( isset($_POST['accept_policies']) ) {
        update_post_meta($order_id, 'accept_policies', sanitize_text_field($_POST['accept_policies']));
    }
}

/* help function */
function fetch_store_policies() {
    $baseUrl = get_option('baseUrl');
    $endpoint_url = 'wp-json/mvx_module/v1/list_of_all_tab_based_settings_field';
    $consumer_key =     get_option('consumerKey');
    $consumer_secret =  get_option('consumerSecret');
    $vendor_id = get_vendor_id_from_cart();

    $full_url = $baseUrl . $endpoint_url . '?vendor_id=' . $vendor_id . '&consumer_key=' . $consumer_key . '&consumer_secret=' . $consumer_secret;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $full_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'); // Set user agent
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json'
    ));

    $response = curl_exec($ch);
    if($response === false)
        echo 'Error: ' . curl_error($ch);
    else {
        $data = json_decode($response, true);
        if(isset($data['vendor-policy'])) {
            $shipping_policy =      $data['vendor-policy'][0]["database_value"];
            $refund_policy =        $data['vendor-policy'][1]["database_value"];
            $cancellation_policy =  $data['vendor-policy'][2]["database_value"];
        }
    }
    curl_close($ch);
    return array($shipping_policy, $refund_policy, $cancellation_policy);
}

/* help function */
function at_least_one_policy_exists() {
    $policies = fetch_store_policies();
    return !(($policies[0] === 'No policy found' || $policies[0] === '')
          && ($policies[1] === 'No policy found' || $policies[1] === '')
          && ($policies[2] === 'No policy found' || $policies[2] === '')
    );
}

/* help function */
function print_policy($policy_label, $policy_text, $policy_position) {
    echo '
        <div onclick="togglePolicy(\'' . $policy_position . '\')" style="display: flex; flex-direction: column;
            align-items: flex-start; width: fit-content; cursor: pointer;"
        >
            <span class="policy-arrow-toggle" id="policy_' . $policy_position . '_wrapper">' . $policy_label . '<i class="fas fa-angle-down" id="arrow"></i></span>
            <div id="policy_' . $policy_position . '" style="height: 0; overflow: hidden;">
                <p>' . $policy_text . '</p>
            </div>
        </div>';
}

/* script - toggle store policies text */
add_action('wp_footer', 'toggle_policy_script');
function toggle_policy_script() {
    ?>
    <script>
        function togglePolicy(policy_position) {
            jQuery(document).ready(function() {
                var $policy_wrapper = jQuery(`#policy_${policy_position}_wrapper`);
                var $policy_content = jQuery(`#policy_${policy_position}`);
                var $policy_arrow = jQuery(`#policy_${policy_position}_wrapper #arrow`);

                if($policy_content.height() === 0) {
                    $policy_content.animate({ height: $policy_content[0].scrollHeight }, 300)
                    $policy_wrapper.addClass('expanded');
                    
                    // flip arrow
                    $policy_arrow.removeClass('fa-angle-down');
                    $policy_arrow.addClass('fa-angle-up');
                }
                else {
                    $policy_content.animate({ height: 0 }, 300);
                    $policy_wrapper.removeClass('expanded');
                    
                    // flip arrow back
                    $policy_arrow.removeClass('fa-angle-up');
                    $policy_arrow.addClass('fa-angle-down');
                }
            });
        }
    </script>
    <?php
}
