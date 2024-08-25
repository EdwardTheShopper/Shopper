<?php

/**
 * Plugin Name: WooCommerce Product Title Search
 * Description: Extend WooCommerce REST API to search products by title.
 * Version: 1.0
 * Author: EdwarZiadeh
 */

// Hook for adding your custom REST API route.
add_action('rest_api_init', 'register_product_title_search_route');

function register_product_title_search_route()
{
    register_rest_route('wc/v3', '/products/search-by-title/', array(
        'methods' => 'GET',
        'callback' => 'product_title_search',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ));
}

 function product_title_search($request) {
    global $wpdb;

    $params = $request->get_params();
    $title = isset($params['title']) ? $wpdb->esc_like($params['title']) : '';
    $posts_per_page = isset($params['per_page']) ? intval($params['per_page']) : 10;

    // Extend the SQL query to fetch additional data including attributes
    $products_query = "
        SELECT p.ID, p.post_title, p.post_content, p.post_excerpt, 
               max(case when pm.meta_key = '_sku' then pm.meta_value end) as sku,
               max(case when pm.meta_key = '_price' then pm.meta_value end) as regular_price,
               max(case when pm.meta_key = '_sale_price' then pm.meta_value end) as sale_price,
               max(case when pm.meta_key = '_stock' then pm.meta_value end) as stock_quantity,
               max(case when pm.meta_key = '_thumbnail_id' then pm.meta_value end) as thumbnail_id,
               max(case when pm.meta_key = '_product_image_gallery' then pm.meta_value end) as gallery_ids,
               max(case when pm.meta_key = '_yoast_wpseo_title' then pm.meta_value end) as seo_title,
               max(case when pm.meta_key = '_yoast_wpseo_metadesc' then pm.meta_value end) as seo_description,
               (SELECT GROUP_CONCAT(t.name SEPARATOR ', ') FROM {$wpdb->prefix}term_relationships AS tr
                INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                INNER JOIN {$wpdb->prefix}terms AS t ON tt.term_id = t.term_id
                WHERE tr.object_id = p.ID AND tt.taxonomy = 'product_cat') as categories,
               (SELECT GROUP_CONCAT(t.name SEPARATOR ', ') FROM {$wpdb->prefix}term_relationships AS tr
                INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                INNER JOIN {$wpdb->prefix}terms AS t ON tt.term_id = t.term_id
                WHERE tr.object_id = p.ID AND tt.taxonomy = 'product_tag') as tags
        FROM {$wpdb->prefix}posts p
        LEFT JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
        WHERE p.post_type = 'product'
          AND (p.post_status = 'publish' OR p.post_status = 'pending')
          AND p.post_title LIKE %s
        GROUP BY p.ID
        LIMIT %d";

    $prepared_query = $wpdb->prepare($products_query, '%' . $title . '%', $posts_per_page);
    $products = $wpdb->get_results($prepared_query);

    $formatted_products = array_map(function($product) use ($wpdb) {
        // Parsing attribute data from post meta
        $attribute_keys = $wpdb->get_results("
            SELECT meta_key, meta_value
            FROM {$wpdb->prefix}postmeta
            WHERE post_id = $product->ID
            AND meta_key LIKE 'attribute_%'
        ");

        $attributes = [];
        foreach ($attribute_keys as $attribute) {
            $attributes[str_replace('attribute_', '', $attribute->meta_key)] = maybe_unserialize($attribute->meta_value);
        }

        // Get URLs for the featured image and gallery images
        $thumbnail_url = wp_get_attachment_url($product->thumbnail_id);
        $gallery_ids = explode(',', $product->gallery_ids);
        $gallery_urls = array_map('wp_get_attachment_url', $gallery_ids);

        return [
            'ID' => $product->ID,
            'name' => $product->post_title,
            'description' => $product->post_content,
            'short_description' => $product->post_excerpt,
            'sku' => $product->sku,
            'categories' => $product->categories,
            'regular_price' => $product->regular_price,
            'sale_price' => $product->sale_price,
            'stock_quantity' => $product->stock_quantity,
            'tags' => $product->tags,
            'attributes' => $attributes,
            'seo_title' => $product->seo_title,
            'seo_description' => $product->seo_description,
            'thumbnail_url' => $thumbnail_url,
            'gallery_urls' => $gallery_urls
        ];
    }, $products);

    return new WP_REST_Response($formatted_products, 200);
} 

