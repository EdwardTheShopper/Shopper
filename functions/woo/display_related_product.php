<?php

// Display related products tab content
function related_products_tab_content() {
    global $product;
    $vendor = get_mvx_product_vendors(get_the_ID());
    $vendor_id = $vendor->id;
    $vendor_term_id = get_user_meta($vendor_id, '_vendor_term_id', true);
    // Get product category
    $categories = $product->get_category_ids();
    $category = "";
    $countCat = count($categories);
    switch ($countCat) {
        case $countCat > 0:
            $category = $categories[0];
            // no break
        case $countCat > 1:
            $category = $categories[1];
            // no break
        case $countCat > 2:
            $category = $categories[2];
            // no break
        default:
            $category = $categories[0];
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'post__not_in'   => array($product->get_id()),
        'tax_query'      => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category,
            ),
            array(
                'taxonomy' => 'dc_vendor_shop',
                'field'    => 'term_id',
                'terms'    => $vendor_term_id,
            ),
        ),
    );

    // Run the query
    $related_products = new WP_Query($args);

    // Display related products
    if ($related_products->have_posts()) {
        echo '<div class="woocommerce-tabs wc-tabs-wrapper" style="margin-top: 1.25rem; padding: 1.25rem;">
                <span style= "font-family: var(--font-secondary); font-weight: 600;">מוצרים דומים:</span>
                <ul class="products">
            ';
        while ($related_products->have_posts()) {
            $related_products->the_post();
            wc_get_template_part('content', 'product');
        }
        echo '</ul></div>';
        wp_reset_postdata();
    }
}
