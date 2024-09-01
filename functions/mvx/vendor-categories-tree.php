<?php
/**
 * Get all store categroies that have products as a tree view
 * Author: Edward Ziadeh
 * Created: 23 Jul, 2024
*/

function mvx_get_vendor_categories_tree($vendor_id) {

    // Get the vendor object
    $vendor = get_mvx_vendor($vendor_id);

    if (!$vendor) {
        return [];
    }

    // Get the vendor's products
    $products = $vendor->get_products();

    // Initialize an array to store category IDs
    $category_ids = [];

    // Loop through each product to get the categories
    foreach ($products as $product) {
        $product_categories = wp_get_post_terms($product->ID, 'product_cat', ['fields' => 'ids']);
        $category_ids = array_merge($category_ids, $product_categories);
    }

    // Remove duplicate category IDs
    $category_ids = array_unique($category_ids);

    // Get category details
    $categories = [];
    foreach ($category_ids as $category_id) {
        $term = get_term($category_id, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $categories[$term->term_id] = $term;
        }
    }

    return build_vendor_category_tree($categories);
}

function build_vendor_category_tree($categories, $parent_id = 0) {
    $branch = [];
    foreach ($categories as $category) {
        if ($category->parent == $parent_id) {
            $children = build_vendor_category_tree($categories, $category->term_id);
            if ($children) {
                $category->children = $children;
            } else {
                $category->children = [];
            }
            $branch[$category->term_id] = $category;
        }
    }
    return $branch;
}

function display_vendor_categories_tree($vendor_id, $path) {
    $categories_tree = mvx_get_vendor_categories_tree($vendor_id);

    if (empty($categories_tree)) {
        echo '<p>No categories found for this vendor.</p>';
        return;
    }

    display_vendor_category_tree($categories_tree, $path);
}

function display_vendor_category_tree($categories, $path, $sub_menu = false) {
    echo '<ul' . ($sub_menu? ' class="sub-menu" style="display: none"' : '') . '>';
    foreach ($categories as $category) {
        echo '<li class="parent-category"';
        if(empty($category->children)) echo ' class="without-children"';
        echo '>';
        echo "<a href='$path?category=$category->slug'>" . esc_html($category->name) . '</a>';
        if (!empty($category->children)) {
            echo '<i class="fas fa-angle-down toggle"></i>'; // define expand icon
            display_vendor_category_tree($category->children, $path, true); // define sub-menu
        }
        echo "</li>";
    }
    echo '</ul>';
}

// Hook to an action
function vendor_categories_tree_action($vendor_id) {
    /**
     * code path "functions/global/get_vendor_slug.php" by vendor id
     */
    $path = get_vendor_slug($vendor_id);

    display_vendor_categories_tree($vendor_id, $path);
}
add_action('display_vendor_categories', 'vendor_categories_tree_action', 10, 1);


/* enqueue script */
add_action('wp_enqueue_scripts', 'enqueue_custom_categories_menu_script');
function enqueue_custom_categories_menu_script() {
    wp_enqueue_script('custom-categories_menu', get_template_directory_uri() . '-child/assets/js/custom/categories_menu.js', array('jquery'), null, true);
    wp_localize_script('custom-categories_menu', 'custom_categories_params', array('ajax_url' => admin_url('admin-ajax.php')));
}
