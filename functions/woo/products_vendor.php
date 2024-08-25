<?php
/**
 * API Endpoint Description:
 * Method GET
 * GET /wp-json/custom-wc-api/v1/products-by-vendor/123
 *
 * @version 1.0.0
 */


 function custom_wc_api_products_by_vedor_route() {
   
    register_rest_route('custom-wc-api/v1', '/products-by-vendor/(?P<vendor_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_products_by_vendor',
    ));
}

add_action('rest_api_init', 'custom_wc_api_products_by_vedor_route');

// Callback function to handle the API request
function get_products_by_vendor($request) {

    $per_page = $request->get_param('per_page') ?: 10; // Products per page.
    $page = $request->get_param('page') ?: 1; // Current page.
  
    // Calculate the offset.
    $offset = ($page - 1) * $per_page;
    
    $vendor_id = $request->get_param('vendor_id');


    global $wpdb;


    $query = $wpdb->prepare("SELECT 
                    p.ID AS product_id,
                    p.post_title AS product_title,
                    p.post_excerpt AS product_description,
                    p.post_author AS store,
                    s.shop_name AS store_name,
                    i.guid AS img
                    FROM 
                        {$wpdb->posts} AS p
                    JOIN 
                        {$wpdb->posts} AS i
                    ON 
                        p.ID = i.post_parent
                    Left JOIN
                        nqq_dgwt_wcas_ven_index AS s
                    ON
                        p.post_author = s.vendor_id
                    WHERE 
                        p.post_type = 'product'
                    AND
                        p.post_author = $vendor_id
                    GROUP BY
                        p.ID
                    LIMIT %d, %d",  
                    $offset,
                    $per_page)
                    ; 

    $products = $wpdb->get_results($query);

    $product_data = [];
    if ($products) {
        foreach ($products as $product) {
            $product_data[] =[
                'ID' => $product->product_id,
                'Image' => $product->img,
                'Title' => $product->product_title,
                'Content' => $product->product_description,
                'Store' => $product->store,
                'Store Name' => $product->store_name,
            ];
        }
    } 
    return $product_data;
}

