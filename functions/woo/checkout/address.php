<?php
include 'functions/woo/checkout/pickup_order.php'; // in order to use 'get_enable_delivery()'

/* enqueue CSS styles, JS scripts and initialize Select2 (for searchable select-elements) */
add_action('wp_enqueue_scripts', 'enqueue_select2');
function enqueue_select2() {
    if(is_checkout()) {
        wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
        wp_enqueue_script('select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), null, true);
        wp_enqueue_script('custom-select2-init', get_template_directory_uri() . '-child/assets/js/custom/select2-init.js', array('select2-js'), null, true);
    }
}

/* define 'billing_city' and 'billing_address_1' as select-elements */
add_filter('woocommerce_checkout_fields', 'define_address_fields');
function define_address_fields($fields) {
    // get a list of cities from "gov.il" API and set it as options for 'billing_city' field
    $cities = fetch_list(false);
    $fields['billing']['billing_city'] = array(
        'id' => 'billing_city',
        'type' => 'select',
        'options' => $cities,
        'class' => array('form-row-wide'),
        'label' => __('City', 'woocommerce'),
        'required' => true,
    );
    
    // get a list of streets from "gov.il" API (options will be set dynamically after the user chooses a city)
    $fields['billing']['billing_address_1'] = array(
        'id' => 'billing_address_1',
        'type' => 'select',
        'options' => array('' => 'נא לבחור ישוב'),
        'class' => array('form-row-wide'),
        'label' => __('Address', 'woocommerce'),
        'required' => true,
    );

    return $fields;
}

/* show error message (after submit) if billing_city / billing_address_1 remains empty */
add_action('woocommerce_checkout_process', 'check_select_elements');
function check_select_elements() {
    if((isset($_POST['pickup_option']) && $_POST['pickup_option'] == 1) // checkbox is checked
        || !get_enable_delivery())
        return;

    if(empty($_POST['billing_city']))
        wc_add_notice(__('<strong>ישוב</strong> הוא שדה חובה.'), 'error');
    if(empty($_POST['billing_address_1']) || $_POST['billing_address_1'] == "יש לבחור ישוב")
        wc_add_notice(__('<strong>שם רחוב</strong> הוא שדה חובה.'), 'error');
    if($_POST['billing_postcode'] < '1') // represents street-number
        wc_add_notice(__('<strong>מספר רחוב</strong> אינו תקין.'), 'error');
}

/* help function: using $chosen_city parameter to determine which list should be fetched.
 * the value of $chosen_city can be either "false" - need to fetch all cities
 * or an actual (validated) city name - need to fetch all streets of the chosen city */
function fetch_list($chosen_city) {
    $endpoint_url = 'https://data.gov.il/api/3/action/datastore_search?';

    $full_url = !$chosen_city ?
        $endpoint_url . 'resource_id=d4901968-dad3-4845-a9b0-a57d027f11ab'
    :   $endpoint_url . 'resource_id=a7296d1a-f8c9-4b70-96c2-6ebb4352f8e3&q={"שם_ישוב":"' . $chosen_city . '"}';

    $response = wp_remote_get($full_url);
    if(is_array($response) && !is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if($data && isset($data['result']['records'])) {
            $nested_field_name = !$chosen_city ? "שם_ישוב" : "שם_רחוב";
            $options = array(''); // set an empty placeholder to avoid showing the first value as default
            foreach ($data['result']['records'] as $item)
                $options[$item[$nested_field_name]] = fix_parenthesis($item[$nested_field_name]);
            ksort($options);
            return $options;
        }
    }
    return false;
}
/* replace   )example(   with   (example) */
function fix_parenthesis($item) { return preg_replace('/\)(.*?)\(/', '($1)', $item); }
function reverse_fix_parenthesis($item) { return preg_replace('/\((.*?)\)/', ')$1(', $item); }

/* enqueue script */
add_action('wp_enqueue_scripts', 'enqueue_custom_checkout_address_script');
function enqueue_custom_checkout_address_script() {
    if (is_checkout()) {
        wp_enqueue_script('custom-checkout_address', get_template_directory_uri() . '-child/assets/js/custom/address.js', array('jquery'), null, true);
        wp_localize_script('custom-checkout_address', 'custom_checkout_params', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}

/* triggered by address.js script
   on every city selection - fetching a list of streets according to the chosen city */
add_action('wp_ajax_get_streets', 'get_streets');
add_action('wp_ajax_nopriv_get_streets', 'get_streets');
function get_streets() {
    $chosen_city = sanitize_text_field($_GET['chosen_city']);
    $chosen_city = reverse_fix_parenthesis($chosen_city); // this is the "fixed" value, need to get the original string of chosen city
    $options = fetch_list($chosen_city);

    if(!$options)
        wp_send_json_error('Failed to fetch data from the API.');
    else if(empty($options))
        wp_send_json_error('No records found in API response.');
    else
        wp_send_json_success($options);
}