<?php

/** 
 * 
 */

 function custom_modify_yoast_breadcrumbs($links) {
    // Check if it's a single product page and WooCommerce is active
    if (is_product() && class_exists('WooCommerce')) {
        // 1. Remove the 'מוצרים' breadcrumb
        foreach ($links as $key => $link) {
            if ((isset($link['text']) && $link['text'] === 'מוצרים') || (isset($link['text']) && $link['text'] === 'חנות')) {
                unset($links[$key]);
                break; // Remove only the first occurrence
            }
        }

        // 2. Retrieve the product's categories
        $product_id = get_the_ID();
        $terms = get_the_terms($product_id, 'product_cat');

        if ($terms && !is_wp_error($terms)) {
            // 3. Determine the primary category using Yoast SEO's primary category feature
            $primary_category = '';
            $primary_category_url = '';

            if (function_exists('wpseo_get_primary_term_id')) {
                $primary_term_id = wpseo_get_primary_term_id('product_cat', $product_id);
                if ($primary_term_id) {
                    $primary_term = get_term($primary_term_id, 'product_cat');
                    if ($primary_term && !is_wp_error($primary_term)) {
                        $primary_category = $primary_term->name;
                        $primary_category_url = get_term_link($primary_term);
                    }
                }
            }

            // If no primary category is set, default to the first category
            if (empty($primary_category)) {
                $primary_term = array_shift($terms);
                $primary_category = $primary_term->name;
                $primary_category_url = get_term_link($primary_term);
            }

            // 4. Get the full category hierarchy (parents to child)
            $category_hierarchy = [];

            if ($primary_term) {
                // Function to get all ancestors of a term
                $ancestors = get_ancestors($primary_term->term_id, 'product_cat');

                // Reverse the array to get top-level parents first
                $ancestors = array_reverse($ancestors);

                // Add each ancestor to the hierarchy
                foreach ($ancestors as $ancestor_id) {
                    $ancestor = get_term($ancestor_id, 'product_cat');
                    if ($ancestor && !is_wp_error($ancestor)) {
                        $category_hierarchy[] = [
                            'url' => get_term_link($ancestor),
                            'text' => $ancestor->name,
                            'allow_html' => false,
                        ];
                    }
                }

                // Finally, add the primary category
                $category_hierarchy[] = [
                    'url' => $primary_category_url,
                    'text' => $primary_category,
                    'allow_html' => false,
                ];
            }

            // 5. Insert the category hierarchy before the product name
            // Find the position of the last item (product name)
            $position_before_last = count($links) - 1;

            // Insert the category hierarchy
            array_splice($links, $position_before_last, 0, $category_hierarchy);
        }

        // 6. Ensure the product name is the last item
        foreach ($links as $key => $link) {
            if (empty($link['url'])) { // Typically, the current page has an empty 'url'
                $product_breadcrumb = $link;
                unset($links[$key]);
                $links[] = $product_breadcrumb;
                break;
            }
        }
    }

    return $links;
}
add_filter('wpseo_breadcrumb_links', 'custom_modify_yoast_breadcrumbs');
