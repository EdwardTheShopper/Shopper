<?php
/**
 * Author: Edward Ziadeh
 * Created: Apr 2, 2024
 * Override Woocommerce Breadcrumbs
 */
add_filter('woocommerce_get_breadcrumb', 'customize_wc_breadcrumbs', 10, 2);
function customize_wc_breadcrumbs($crumbs, $breadcrumb)
{
    // Check if a vendor is set
    $vendor_id = current_vendor_id();
    $storeName = getLastSegmentFromUrl();

    if($vendor_id) {
        foreach ($crumbs as $key => $crumb) {
            $category_id = get_term_by('name', $crumb[0], 'product_cat')->slug;
            $query = $category_id ? '?category=' . $category_id : "";
            $crumbs[$key][1] = get_site_url() . '/store/' . $storeName . $query;
        }
    }

    return $crumbs;
}

// Function to get current vendor ID - this is just a placeholder, use your vendor plugin's function
function current_vendor_id()
{
    $vendor_id = mvx_find_shop_page_vendor();
    return $vendor_id; // Replace this with actual logic
}


function getLastSegmentFromUrl()
{
    $url = $_SERVER['REQUEST_URI'];

    $parsedUrl = parse_url($url);

    if (!isset($parsedUrl['path'])) {
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
