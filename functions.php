<?php

include_once('functions/search/fibo_store_id.php');
include_once('functions/search/search_results.php');
include_once('functions/search/search_by_title.php');

//include_once('functions/search/search-form-before-shop.php'); //Vendor storeFibesearch override

include_once('functions/user/custom_color_palette.php');
include_once('functions/user/user_search.php');
include_once('functions/user/reset_pass.php');

include_once('functions/woo/checkout/address.php');
include_once('functions/woo/checkout/notifications.php');
include_once('functions/woo/checkout/pickup_order.php');
include_once('functions/woo/checkout/policies.php');
include_once('functions/woo/checkout/shipping_options.php');
include_once('functions/woo/checkout/woo_cart_validation.php');
include_once('functions/woo/checkout/woo_order_status.php');


include_once('functions/woo/breadcrumbs_by_vendor.php');
include_once('functions/woo/disable_image_zoom.php');
include_once('functions/woo/display_related_product.php');
include_once('functions/woo/fast_view.php');
include_once('functions/woo/handle_product_tabs.php');
include_once('functions/woo/image_attributes.php');
include_once('functions/woo/import_product.php');
include_once('functions/woo/products_vendor.php');
include_once('functions/woo/add_vendor_product_id.php');
include_once('functions/woo/woo_cat.php');
//include_once('functions/woo/woo_seo.php');
include_once('functions/seo/new_variables.php');
include_once('functions/seo/nonexistent_product.php');


include_once('functions/tracking/visitor_detail.php');
include_once('functions/tracking/mixpanel_head.php'); // only use in production
include_once('functions/tracking/foottracking.php');
include_once('functions/tracking/footer_tracking.php');

include_once('functions/marketing/send_prom_email.php');
include_once('functions/marketing/store_sale_popup.php');

include_once('functions/mvx/store_info.php');
//include_once('functions/mvx/vendor-categories-tree.php');
//include_once('functions/mvx/all-categories-filter-search.php'); Duplicate issue with bacola parent in line 339 in functions

include_once('functions/payments/payments_methods.php'); // replacing credit2000payment.php
include_once('functions/global/custom-options-page.php');
include_once('functions/global/import_product.php');
include_once('functions/global/include_js_files.php');
include_once('functions/global/get_vendor_id.php');
include_once('functions/global/get_vendor_slug.php');
include_once('functions/global/cors.php');
include_once('functions/global/menu_cat.php');
//include_once('functions/global/dynamic_logo.php'); // I did an update in include header it's faster and better that this solution

//echo get_transient('vendor_url');

/**
 * Allow users to register with an existing email address.
 */

function allow_existing_email_registration($errors, $sanitized_user_login, $user_email)
{
    // Check if the user's email address already exists in the database.
    if (email_exists($user_email)) {
        // Remove the email address error.
        unset($errors->errors['user_email']);
    }
    return $errors;
}
add_filter('registration_errors', 'allow_existing_email_registration', 10, 3);

/*  */
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles() {
    $parenthandle = 'parent-style';
    $theme = wp_get_theme();
    wp_enqueue_style($parenthandle, get_template_directory_uri() . '/style.css', array(), $theme->parent()->get('Version'));
    wp_enqueue_style('child-style', get_stylesheet_uri(), array($parenthandle), $theme->get('Version'));
}

add_action('wp_enqueue_scripts', 'enqueue_custom_styles', 30);
function enqueue_custom_styles() {
    wp_enqueue_style('custom-style-global', get_template_directory_uri() . '-child/assets/css/global.css');
    wp_enqueue_style('custom-style-store', get_template_directory_uri() . '-child/assets/css/store.css');
    wp_enqueue_style('custom-style-product', get_template_directory_uri() . '-child/assets/css/product.css');
    wp_enqueue_style('custom-style-cart', get_template_directory_uri() . '-child/assets/css/cart.css');
    wp_enqueue_style('custom-style-checkout', get_template_directory_uri() . '-child/assets/css/checkout.css');
    wp_enqueue_style('custom-style-my-account', get_template_directory_uri() . '-child/assets/css/my-account.css');
    wp_enqueue_style('custom-style-others', get_template_directory_uri() . '-child/assets/css/others.css');
}

add_action('bacola_single_header_top', 'store_info');
function store_info()
{
    global $product;
    global $post;
    global $woocommerce;
    $id = $product->get_id();
    $output = "";
    $vendor = get_wcmp_product_vendors($id);
    if ($vendor) {
        $vendor_image = $vendor->get_image();
        $output = '<div class="vendor-info">';
        $output .= '<img src="' . $vendor_image . '" class="vendor-logo">';
        $output .= '<span class="store-name">' . bacola_sanitize_data(bacola_vendor_name()) . '<span class="store-dist dist-' . $vendor->id . '"></span></span>';
        $output .= '</div>';
    }
    echo $output;
}

add_action('woocommerce_after_checkout_form', 'ab_disable_shipping_local_pickup');

function ab_disable_shipping_local_pickup($available_gateways)
{

    // Part 1: Hide shipping based on the static choice @ Cart
    // Note: "#customer_details .col-2" strictly depends on your theme

    $chosen_methods = WC()->session->get('chosen_shipping_methods');
    $chosen_shipping = $chosen_methods[0];
    if (0 === strpos($chosen_shipping, 'local_pickup')) {
        ?>
		<script type="text/javascript">
			jQuery('#customer_details .col-2').fadeOut();
		</script>
	<?php
    }

    // Part 2: Hide shipping based on the dynamic choice @ Checkout
    // Note: "#customer_details .col-2" strictly depends on your theme

    ?>
	<script type="text/javascript">
		jQuery('form.checkout').on('change', 'input[name^="shipping_method"]', function() {
			var val = jQuery(this).val();
			if (val.match("^local_pickup")) {
				jQuery('#customer_details .col-2').fadeOut();
			} else {
				jQuery('#customer_details .col-2').fadeIn();
			}
		});
	</script>
<?php

}

function woocommerce_button_proceed_to_checkout()
{

    $new_checkout_url = WC()->cart->get_checkout_url();
    ?>
	<a href="<?php echo $new_checkout_url; ?>" class="checkout-button button alt wc-forward">

		<?php _e('Place Order', 'woocommerce'); ?></a>

<?php
}

function ab_billing_field_strings($translated_text, $text, $domain)
{
    switch ($translated_text) {
        case 'Billing details':
            $translated_text = __('Order Placement Details', 'woocommerce');
            break;
    }
    return $translated_text;
}
add_filter('gettext', 'ab_billing_field_strings', 20, 3);

add_filter('woocommerce_order_button_text', 'ab_custom_button_text');

function ab_custom_button_text($button_text)
{
    return 'אשר את ביצוע ההזמנה'; //'Confirm Placing Order';
}

add_filter('woocommerce_get_order_item_totals', 'remove_payment_method_row_from_emails', 10, 3);
function remove_payment_method_row_from_emails($total_rows, $order, $tax_display)
{
    // On Email notifications only
    if (is_wc_endpoint_url('order-received')) {
        unset($total_rows['payment_method']);
    }
    return $total_rows;
}


add_action('init', 'remove_my_action');
function remove_my_action()
{
    remove_action('woocommerce_before_shop_loop', 'bacola_catalog_ordering_start', 30);
    remove_action('bacola_single_header_top', 'bacola_single_product_header');
    remove_filter('woocommerce_shipping_package_name', 'woocommerce_shipping_package_name', 100, 4);
    remove_action('wp_ajax_nopriv_quick_view', 'bacola_quick_view_callback');
    remove_action('wp_ajax_quick_view', 'bacola_quick_view_callback');
}



add_filter('woocommerce_shipping_package_name', 'woocommerce_shipping_package_name_child', 100, 4);
function woocommerce_shipping_package_name_child($package_name, $vendor_id, $package)
{
    if ($vendor_id && $vendor_id != 0) {
        $vendor = get_wcmp_vendor($vendor_id);

        if ($vendor) {
            return $vendor->page_title . __('', 'dc-woocommerce-multi-vendor');
        }
        return $package_name;
    }
    return $package_name;
}

add_action('bacola_single_header_top', 'bacola_single_product_header_child');
function bacola_single_product_header_child()
{

    global $product;
    global $post;
    global $woocommerce;
    $id = $product->get_id();
    $vendor = get_wcmp_product_vendors($id);

    ?>
	<div class="product-header">
		<?php do_action('bacola_single_title'); ?>

		<div class="product-meta top">

			<div class="product-brand">
				<?php wc_display_product_attributes($product); ?>
			</div>

			<?php do_action('bacola_single_rating'); ?>

			<?php if ($product->get_sku()) { ?>
				<div class="sku-wrapper">
					<span><?php esc_html_e('SKU:', 'bacola'); ?></span>
					<span class="sku"><?php echo esc_html($product->get_sku()); ?></span>
				</div>
			<?php } ?>

			<?php if (bacola_vendor_name() && false) { // remove false to return store info in content single page
			    //$vendor_image = $vendor->get_image();
			    ?>
				<div class="store-info">
					<span>
                        <?php esc_html_e('Store:', 'bacola'); ?>
                        <?php echo bacola_vendor_name(); ?>
                        <div class="store-dist dist-<?php echo $vendor->id ?? ''; ?>"></div>
                    </span>
				</div>
			<?php } ?>

		</div><!-- product-meta -->
	</div><!-- product-header -->
<?php
}

add_filter('gettext', 'translate_reply');
add_filter('ngettext', 'translate_reply');

function translate_reply($translated)
{
    $translated = str_ireplace('Vendor', 'Store', $translated);
    return $translated;
}


add_filter('woocommerce_loop_add_to_cart_link', 'replacing_add_to_cart_button', 10, 2);
function replacing_add_to_cart_button($button, $product)
{
    if (!$product->is_type('simple') || $product->get_stock_status() == 'outofstock') {
        $button_text = __("View product", "woocommerce");
        $button = '<a class="button-primary xsmall rounded wide button detail-bnt quick-view-button" href="' . $product->get_id() . '">' . $button_text . '</a>';
    }
    return $button;
}

add_filter('woocommerce_redirect_single_search_result', '__return_false');
function woo_hide_product_categories_widget($list_args)
{

    $list_args['hide_empty'] = 1;

    return $list_args;
}

add_filter('woocommerce_product_categories_widget_args', 'woo_hide_product_categories_widget');


add_filter('woocommerce_valid_order_statuses_for_cancel', 'custom_valid_order_statuses_for_cancel', 10, 2);
function custom_valid_order_statuses_for_cancel($statuses, $order)
{

    // Set HERE the order statuses where you want the cancel button to appear
    $custom_statuses    = array('completed', 'pending', 'processing', 'on-hold', 'failed');

    // Set HERE the delay (in days)
    $duration = 3; // 3 days

    // UPDATE: Get the order ID and the WC_Order object
    if (isset($_GET['order_id'])) {
        $order = wc_get_order(absint($_GET['order_id']));
    }

    $delay = $duration * 24 * 60 * 60; // (duration in seconds)
    $date_created_time  = strtotime($order->get_date_created()); // Creation date time stamp
    $date_modified_time = strtotime($order->get_date_modified()); // Modified date time stamp
    $now = strtotime("now"); // Now  time stamp

    // Using Creation date time stamp
    if (($date_created_time + $delay) >= $now) {
        return $custom_statuses;
    } else {
        return $statuses;
    }
}


remove_action('woocommerce_no_products_found', 'wc_no_products_found');
add_action('woocommerce_no_products_found', 'ab_no_products_found');

function ab_no_products_found()
{
    echo '<p style="margin-bottom: 40px; font-size: 1.5rem;" class="woocommerce-info">לא נמצאו מוצרים התואמים לבחירה שלך.</p>';
    // echo do_shortcode('[elementor-template id="3129"]');
    // echo do_shortcode('[elementor-template id="111111111124516"]');
}

add_filter('comment_post_redirect', 'redirect_after_comment');
function redirect_after_comment($location)
{
    //global $wp;
    //$current_url = home_url( $wp->request );
    return $_SERVER["HTTP_REFERER"];
}

add_filter('wcmp_show_report_abuse_link', '__return_false');



/* checkout fields validation (after submit) */

add_action('woocommerce_after_checkout_validation', 'validate_fname_lname', 10, 2);
function validate_fname_lname($fields, $errors) { // $fields is actually unused, but has to be mentioned to avoid errors
    if(!preg_match('/^([A-Za-zא-ת\s-]+)$/u', $_POST['billing_first_name']))
        $errors->add('validation', '<strong>שם פרטי</strong> אינו יכול להכיל ספרות או סימנים מיוחדים.');
    if(!preg_match('/^([A-Za-zא-ת\s-]+)$/u', $_POST['billing_last_name']))
        $errors->add('validation', '<strong>שם משפחה</strong> אינו יכול להכיל ספרות או סימנים מיוחדים.');
}

add_action('woocommerce_checkout_process', 'validate_phone');
function validate_phone() {
    global $woocommerce;
    if (!preg_match('/^[0-9]{10}$/D', $_POST['billing_phone']))
        wc_add_notice("הטלפון צריך להכיל 10 ספרות", 'error');
}


// saj added functions starts ------->

function wpb_get_parent_terms($taxonomy = 'category')
{
    $currentPost = get_post();
    $terms       = get_the_terms($currentPost->ID, $taxonomy);

    if (is_wp_error($terms)) {
        /** @var \WP_Error $terms */
        throw new \Exception($terms->get_error_message());
    }

    $map = array_map(
        function ($term) use ($taxonomy) {
            return '<a href="' . esc_url(get_term_link(
                $term->term_id,
                $taxonomy
            )) . '" title="' . esc_attr($term->name) . '">
                ' . $term->name . '
                </a>';
        },
        array_filter($terms, function ($term) {
            return $term->parent == 0;
        })
    );

    return implode(', ', $map);
}

function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val->$key, $key_array)) {
            $key_array[$i] = $val->$key;
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}


add_action('wp_ajax_get_vendors_lat_lng', 'get_vendors_lat_lng');
add_action('wp_ajax_nopriv_get_vendors_lat_lng', 'get_vendors_lat_lng');
function get_vendors_lat_lng()
{
    global $wpdb;
    $vendorsLatLng = array();
    $vendorIDs = (get_mvx_vendors(array(), 'id'));
    foreach ($vendorIDs as $vendor_id) {
        $sql1 = "SELECT * FROM `nqq_postmeta` WHERE `post_id` = $vendor_id AND `meta_key` = 'lat_lngStore';";
        $get_post_meta = $wpdb->get_results($sql1);
        if ($get_post_meta) {
            $metadata = $get_post_meta[0]->meta_value;
            $metaLat = json_decode($metadata)->lat;
            $metaLng = json_decode($metadata)->lng;
            $vendorsLatLng[$vendor_id] = array($metaLat, $metaLng);
        }
    }
    print_r(json_encode($vendorsLatLng));
    wp_die();
}

add_action('wp_ajax_save_email_on_submit', 'save_email_on_submit');
add_action('wp_ajax_nopriv_save_email_on_submit', 'save_email_on_submit');
function save_email_on_submit()
{
    $visitorId = $_POST['visitorId'];
    $email = $_POST['email'];
    $eTime = $_POST['eTime'];
    $lTime = $_POST['lTime'];

    global $wpdb;
    global $table_prefix;
    $table1 = $table_prefix . 'foottracking';

    $ifVisitorDataExists = $wpdb->get_results("SELECT * FROM $table1 WHERE visitor_id = '" . $visitorId . "' AND dateTime = '$eTime' AND LdateTime = '$lTime' ");

    if ($wpdb->num_rows > 0) {
        $result = $wpdb->update($table1, [
            'email' => $email
        ], ['visitor_id' => $visitorId, 'dateTime' => $eTime, 'LdateTime' => $lTime]);
        if ($result == 1) {
            echo 'Email Added Successfuly';
        } else {
            echo 'Error while Adding Email';
        };
        // echo "Store data already available";
    } else {
    }
    wp_die();
}

// Added for SS-86 on 23-May-2023
function custom_rewrite_rule()
{
    add_rewrite_rule(
        '^store/([^/]+)/product/([^/]+)/?$',
        'index.php?store=$matches[1]&product=$matches[2]',
        'top'
    );
}

add_action('init', 'custom_rewrite_rule');

function custom_post_type_permalink($post_link, $post)
{
    if ($post->post_type == 'product') {
        $product_id = $post->ID;
        $vendor = get_mvx_product_vendors($product_id);
        $vendor_link = $vendor->permalink;
        $path = parse_url($vendor_link, PHP_URL_PATH);
        $segments = explode('/', rtrim($path, '/'));
        $lastSegment = end($segments);
        $vendor_slug = $lastSegment;

        $current_url = $_SERVER['REQUEST_URI'];

        if ($vendor_slug) {
            $post_link = str_replace('store/%my_store%', 'store/' . $vendor_slug, $post_link);
        }
    }
    return $post_link;
}
add_filter('post_type_link', 'custom_post_type_permalink', 10, 2);
// End

// Added for SS-92 on 23-May-2023
function custom_query_vars($vars)
{
    $vars[] = 'store';
    $vars[] = 'product';
    return $vars;
}
add_filter('query_vars', 'custom_query_vars');

function custom_template_redirect()
{

    if (is_singular('product')) {
        global $wp_query;
        $id = get_the_ID();
        $vendor = get_mvx_product_vendors($id);
        $vendor_link = $vendor->permalink;
        $path = parse_url($vendor_link, PHP_URL_PATH);
        $segments = explode('/', rtrim($path, '/'));
        $lastSegment = end($segments);
        $vendor_slug = $lastSegment;

        $store_slug = $wp_query->get('store');

        // Check if the store slug is valid
        $valid_store_slugs = array($vendor_slug);
        if (!in_array($store_slug, $valid_store_slugs)) {
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
            include(locate_template('404.php'));
            exit;
        }
    }
}
add_action('template_redirect', 'custom_template_redirect');
// End

add_action('init', 'start_session_wp', 1);
function start_session_wp()
{
    if(!session_id()) {
        session_start();
    }
}

add_action('wp_enqueue_scripts', 'child_theme_enqueue_scripts', 20);
function child_theme_enqueue_scripts() {
    // Deregister the parent theme's script
    wp_deregister_script('parent-script-handle');

    // Register the custom script in the child theme
    wp_register_script(
        'parent-script-handle', // Same handle as the parent theme to override
        get_stylesheet_directory_uri() . '/assets/js/custom/sidebarfilter.js', // Path to your custom script
        array(), // Dependencies, if any
        null, // Version number
        true // Load in footer
    );

    // Enqueue the custom script
    wp_enqueue_script('parent-script-handle');
}

add_action('wp_enqueue_scripts', 'enqueue_fixing_paragraphs_script');
function enqueue_fixing_paragraphs_script() {
    wp_enqueue_script('custom_fixing_paragraphs', get_template_directory_uri() . '-child/assets/js/custom/fixing_paragraphs.js', array(), null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_mobile_search_script');
function enqueue_mobile_search_script() {
    wp_enqueue_script('custom_mobile_search', get_template_directory_uri() . '-child/assets/js/custom/mobile_search.js', array(), null, true);
    wp_enqueue_script('custom_press_enter_search', get_template_directory_uri() . '-child/assets/js/custom/press-enter-search.js', array(), null, true);
}



/* Override sidebar function */
function unregister_klb_product_categories_widget() {
    unregister_widget('klb_widget_klb_product_categories');
   
    wp_enqueue_script('custom_product_categories', get_template_directory_uri() . '-child/woocommerce-filter/widgets/product-categories/js/widget-product-categories.js', array('jquery'), null, true);
    wp_enqueue_style('custom_product_categories', get_template_directory_uri() . '-child/woocommerce-filter/widgets/product-categories/css/widget-product-categories-rtl.css', array(), null, 'all');
    wp_enqueue_style('custom_product_categories', get_template_directory_uri() . '-child/woocommerce-filter/widgets/product-categories/css/widget-product-categories.css', array(), null, 'all');
    include_once ('woocommerce-filter/widgets/product-categories/custom-widget-product-categories.php');
    register_widget('Custom_Widget_Product_Categories');
}
add_action('widgets_init', 'unregister_klb_product_categories_widget', 11);
