<?php

add_filter('dgwt/wcas/tnt/search_results/ids', 'custom_fibo_filter', 5);
// Search only in current Vendor products
function custom_fibo_filter($ids) {

    $vendorId = $_COOKIE['vendorId'] ?? false;
    // $vendorId = get_vendor_id();

/* TESTING */
// /**/    $vendorId2 = get_vendor_id();
// /**/    setcookie('TEST_DEBUG_FIBO_vendorId_from_cookie', $vendorId, time() + (86400 * 30), "/");
// /**/    setcookie('TEST_DEBUG_FIBO_vendorId_from_function', $vendorId2, time() + (86400 * 30), "/");
/* END OF TESTING */

    if ($vendorId) {
        $products_ids = fibosearch_get_vendor_products($vendorId);
        $ids          = array_intersect($ids, $products_ids);
    }
    return $ids;
}

// Get all products from the given Vendor.
function fibosearch_get_vendor_products($vendorId)
{
    global $wpdb;


    $results = $wpdb->get_col($wpdb->get_results("SELECT DISTINCT p.ID FROM $wpdb->posts as p INNER JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id
    LEFT JOIN $wpdb->term_relationships tr ON (p.ID = tr.object_id) WHERE p.post_author = $vendorId AND p.post_status LIKE 'publish'", $vendorId));
    return $results;
}
