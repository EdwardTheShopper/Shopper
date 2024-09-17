<?php
/**
 * By: Edward Ziadeh
 * To add new variable inside yoast
 * Date: Sep 4, 2024
 */
function my_custom_yoast_variable() {
    return 'edward the king';
    // Example: Retrieving a custom field from a post, you can customize this as needed
    $custom_value = get_post_meta(get_the_ID(), 'custom_field_key', true);
    
    // Return the value you want to display in Yoast SEO fields
    return $custom_value ? $custom_value : 'Default Value';
}

// Step 2: Register the custom variable in Yoast SEO
add_action('wpseo_register_extra_replacements', 'my_custom_yoast_variable_replacement');

function my_custom_yoast_variable_replacement() {
    wpseo_register_var_replacement(
        '%%edward%%',  // This is the placeholder that will be used in Yoast settings
        'my_custom_yoast_variable', // Callback function to retrieve the value
        'advanced', // Usage context (use 'advanced' for custom variables)
        'My Custom Variable Description' // Optional description for the variable
    );
}