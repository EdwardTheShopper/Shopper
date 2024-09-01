<?php
//include 'functions/payments/payments_methods.php'; // in order to use 'get_vendor_id_from_cart()'

/* get vendor_id from cart and re-write it to the cookie */
add_action('wp_footer', 'redefine_vendor_id');
function redefine_vendor_id() {
    if(is_checkout()) {
        $vendor_id = get_vendor_id_from_cart();
        $cookie_expiry = time() + (365 * 24 * 60 * 60); // 1 year
        set_vendor_cookie($vendor_id, $cookie_expiry);
    }
}

/* send a push notification to this specific vendor, after checkout is complete (including payment if necessary) */
add_action('woocommerce_thankyou', 'send_notification_to_vendor');
function send_notification_to_vendor() {
    $webpushrKey = get_option('webpushrKey');
    $webpushrAuthToken = get_option('webpushrAuthToken');
    $sellerAppUrl = get_option('sellerAppUrl');

    $vendor_id = $_COOKIE['vendorId'];

    $end_point = 'https://api.webpushr.com/v1/notification/send/attribute';
    $http_header = array(
        "Content-Type: Application/Json",
        "webpushrKey: $webpushrKey",
        "webpushrAuthToken: $webpushrAuthToken"
    );
    $req_data = array(
        'title' 	    => "New Order!",
        'message' 		=> "You have received a new order on SellerApp",
        'target_url'	=> $sellerAppUrl . $vendor_id . '/orders',
        'attribute'		=> array('vendorId' => $vendor_id),
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
    curl_setopt($ch, CURLOPT_URL, $end_point);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
}