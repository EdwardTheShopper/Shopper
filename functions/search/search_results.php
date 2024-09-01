<?php
/**
 * Authour: Edward Ziadeh
 * Created: 3 Mar, 2024
 * This Function only effect when pressing enter or go, search on mobile
 */


function searchfilter($query)
{
    $vendor_id = get_vendor_id();

/* TESTING */
/**/    setcookie('TEST_DEBUG_RESULTS_vendorId_from_cookie', $_COOKIE['vendorId'] ?? false, time() + (86400 * 30), "/");
/**/    setcookie('TEST_DEBUG_RESULTS_vendorId_from_function', get_vendor_id(), time() + (86400 * 30), "/");
/* END OF TESTING */

    if ($query->is_search && !is_admin() && $vendor_id) {
        $query->query_vars['author'] = $vendor_id;
        $query->set('post_type', array('product'));
        return $query;
    }
    return;
}

add_filter('pre_get_posts', 'searchfilter');
