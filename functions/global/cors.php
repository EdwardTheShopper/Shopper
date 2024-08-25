<?php
/**
 * Author: Mohammad Daqa
 * Created: 19/10/2023
 * Need to change the domain for each website
 */
add_action('init', 'handle_preflight');
function handle_preflight()
{
    $origin = get_http_origin();
    $domains = [
        'http://localhost:3000/', 'http://localhost:3000',
        'https://localhost:3000/', 'https://localhost:3000',
        'https://seller-app-prod.web.app/', 'https://seller-app-prod.web.app',
        'https://seller-app-admin.web.app/', 'https://seller-app-admin.web.app',
        'https://www.shopper.shop/', 'https://www.shopper.shop'
    ];


    $isValidOrigin = in_array($origin, $domains);

    // echo "CORS-Testing@handle_preflight origin={$origin}, isValidOrigin={$isValidOrigin}";
    if ($isValidOrigin) {
        header("Access-Control-Allow-Origin: {$origin}");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, X-WP-Nonce, Content-Type, Accept, Authorization, App_Name, App-Name');
        if ('OPTIONS' == $_SERVER['REQUEST_METHOD']) {
            status_header(200);
            exit();
        }
    }
}

add_filter('rest_authentication_errors', 'rest_filter_incoming_connections');
function rest_filter_incoming_connections($errors)
{
    $postman = '';
    $origin = get_http_origin();
    $request_server = $_SERVER['REMOTE_ADDR'];

    // echo "CORS-Testing- origin={$origin}, remote_addr={$request_server}, ";

    $domains = [
        'http://localhost:3000/', 'http://localhost:3000',
        'https://localhost:3000/', 'https://localhost:3000',
        'https://seller-app-prod.web.app/', 'https://seller-app-prod.web.app',
        'https://seller-app-admin.web.app/', 'https://seller-app-admin.web.app',
        'https://www.shopper.shop/', 'https://www.shopper.shop',
        $postman
    ];

    $isForbiddenOrigin = !in_array($origin, $domains);

    if ($isForbiddenOrigin) {
        return new WP_Error('forbidden_access', $origin, array(
           'status' => 403
        ));
    }
    return $errors;
}