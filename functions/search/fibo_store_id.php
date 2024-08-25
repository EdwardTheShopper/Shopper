<?php
/**
 * Author: Mohammad Daqa
 * Created: 20/01/2024
 * Fibosearch filter that add request parameters
 * Adding store id by using the user author id to use it in the fibosearch function in the root to pass the vendor id.
 */
add_filter('dgwt/wcas/scripts/custom_params', function ($params) {

    global $post;
    $vendor_id = $_COOKIE['vendorId'] ?? false;

    if($vendor_id) {
        global $set_global_vendor_id;
        $set_global_vendor_id = $vendor_id;
        $params['vendor'] = $vendor_id;
    }
    return $params;
});
