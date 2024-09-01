<?php
/**
 * Author: Mohammad Daqa
 * Created: 19/10/2023
 *
 * dynamic domains were added by Sharon Chen on 11/06/2024 (STILL IN PROGRESS)
 */

function get_domains()
{
    $domainsString = get_option('domains');
    $domains = array_map('trim', explode(',', $domainsString)); // convert to an array
    $domains = array_map('trim', $domains); // remove spaces
    return $domains;
}

add_action('init', 'handle_preflight');
function handle_preflight()
{
    $origin = get_http_origin();
    $domains = [
        'http://localhost:3000/', 'http://localhost:3000',
        'https://localhost:3000/', 'https://localhost:3000',
        'https://seller-app-stg.web.app/', 'https://seller-app-stg.web.app',
        'https://shopperstgenv.wpengine.com/', 'https://shopperstgenv.wpengine.com'
    ]; 
    
    /* TODO: try this code instead of lines 21-26

        $domains = get_domains();
    */
 
    $isValidOrigin = in_array($origin, $domains);

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

    $domains = [
        'http://localhost:3000/', 'http://localhost:3000',
        'https://localhost:3000/', 'https://localhost:3000',
        'https://seller-app-stg.web.app/', 'https://seller-app-stg.web.app',
        'https://shopperstgenv.wpengine.com/', 'https://shopperstgenv.wpengine.com',
        $postman
    ];

    /* TODO: try this code instead of lines 53-59
    
        $domains = get_domains();
        $domains[] = $postman; // add postman to the end of the array (is it necessary? postman is empty)
    */
    
    $isForbiddenOrigin = !in_array($origin, $domains);

    if ($isForbiddenOrigin) {
        return new WP_Error('forbidden_access', $origin, array(
           'status' => 403
        ));
    }
    return $errors;
}
