<?php
/**
 * Author: Edward Ziadeh
 * Created: Apr 2, 2024
 * Override Woocommerce Breadcrumbs
 */
add_filter('woocommerce_get_breadcrumb', 'customize_wc_breadcrumbs', 10, 2);
function customize_wc_breadcrumbs($crumbs, $breadcrumb) {
    $vendor_id = get_vendor_id();
    
    if($vendor_id) {
        foreach($crumbs as $key => $crumb) {
            $category_id = get_term_by('name', $crumb[0], 'product_cat')->term_id;
            $query = $category_id ? '?filter_cat=' . $category_id : "";
            $crumbs[$key][1] = get_site_url() . get_vendor_slug($vendor_id) . $query;
        }
    }
    return $crumbs;
}


/* old function using slug
function customize_wc_breadcrumbs($crumbs, $breadcrumb) {

    $vendor_id = get_vendor_id();
    $vendor_slug = get_vendor_slug($vendor_id);
    
    if($vendor_id) {
        foreach ($crumbs as $key => $crumb) {
            $category_id = get_term_by('name', $crumb[0], 'product_cat')->slug;
            $query = $category_id ? '?category=' . $category_id : "";
            $crumbs[$key][1] = get_site_url() . $vendor_slug . $query;
        }
    }
    return $crumbs;
}
*/
/* unused
function getLastSegmentFromUrl() {
    $url = $_SERVER['REQUEST_URI'];
    $parsedUrl = parse_url($url);

    if(!isset($parsedUrl['path'])) {
        return false;
    }

    // Extract the path component
    $path = $parsedUrl['path'];

    // Split the path into segments
    $pathSegments = explode('/', $path);

    foreach ($pathSegments as $index => $segment) {
        if ($segment === 'store' && isset($pathSegments[$index + 1])) {
            return $pathSegments[$index + 1]; // Return the segment following 'store'
        }
    }

    return false;
}
*/