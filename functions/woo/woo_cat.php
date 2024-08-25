<?php

/*
* Plugin Name: Custom WooCommerce API
* Description: Custom API to get all categories and subcategories.
* Method GET
* Path wp-json/custom-wc-api/v1/categories
* Path /wp-json/custom-wc-api/v1/nested-categories
*/

function custom_wc_api_register_routes() {
    register_rest_route('custom-wc-api/v1', '/categories', array(
        'methods' => 'GET',
        'callback' => 'custom_wc_get_categories',
    ));
}

add_action('rest_api_init', 'custom_wc_api_register_routes');


function custom_wc_get_categories($request) {
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ));

    $formatted_categories = array();

    foreach ($categories as $category) {


        /* if ($category->parent === 0) {
            // This is a top-level category
            $formatted_category = array(
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'subcategories' => array(), // Initialize an empty subcategories array
            );

            // Find and add subcategories
            foreach ($categories as $subcategory) {
                if ($subcategory->parent === $category->term_id) {
                    $formatted_category['subcategories'][] = array(
                        'id' => $subcategory->term_id,
                        'name' => $subcategory->name,
                        'slug' => $subcategory->slug,
                    );
                }
            }

            $formatted_categories[] = $formatted_category;
        } */



        $parent_category = null;

        if ($category->parent !== 0) {
            // If the category has a parent, get parent category information
            $parent = get_term($category->parent, 'product_cat');
            $parent_category = array(
                'id' => $parent->term_id,
                'name' => $parent->name,
                'slug' => $parent->slug,
            );
        }

        $formatted_category = array(
            'id' => $category->term_id,
            'name' => $category->name,
            'slug' => $category->slug,
            'parent' => $category->parent,
            'parent_category' => $parent_category,
            'count' => $category->count,
        );

        $formatted_categories[] = $formatted_category;
    }

    return rest_ensure_response($formatted_categories);
}




/* 
With nested
*/


function custom_wc_get_nested_categories() {
    $parent_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => 0,
    ));

    $formatted_categories = array();

    foreach ($parent_categories as $parent_category) {
        $formatted_category = custom_wc_get_category_tree($parent_category);

        $formatted_categories[] = $formatted_category;
    }

    return rest_ensure_response($formatted_categories);
}

function custom_wc_get_category_tree($category) {
    $formatted_category = array(
        'id' => $category->term_id,
        'name' => $category->name,
        'slug' => $category->slug,
        'subcategories' => array(),
    );

    $subcategories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => $category->term_id,
    ));

    foreach ($subcategories as $subcategory) {
        $formatted_subcategory = custom_wc_get_category_tree($subcategory);
        $formatted_category['subcategories'][] = $formatted_subcategory;
    }

    return $formatted_category;
}

add_action('rest_api_init', function () {
    register_rest_route('custom-wc-api/v1', '/nested-categories', array(
        'methods' => 'GET',
        'callback' => 'custom_wc_get_nested_categories',
    ));
});
