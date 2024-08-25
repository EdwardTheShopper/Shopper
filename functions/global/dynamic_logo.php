<?php

function enqueue_dynamic_logo()
{
    $vendor_id = get_transient('vendor_id');
    $flag = false;
    if (is_front_page() || is_cart() || is_checkout() || is_archive() || empty($vendor_id)) {
        $flag = true;
        //return; // Skip enqueuing the scripts on these pages
    }

    $slug = get_vendor_slug($vendor_id);

    wp_enqueue_script('dynamic-logo', get_template_directory_uri() . '-child/assets/js/dynamic-logo.js', array('jquery'), null, true);

    // Define your data
    $data_to_pass = array(
        'slug' => $slug,
        'flag' => $flag
    );

    // Pass the data to the script
    wp_localize_script('dynamic-logo', 'custom_script_vars', $data_to_pass);
}

add_action('wp_enqueue_scripts', 'enqueue_dynamic_logo');
