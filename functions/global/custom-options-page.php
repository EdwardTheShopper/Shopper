
<?php
/**
 * Author: Edward Ziadeh
 * Created: June, 6 2024
 */

/************************
 * Main menu and submenus
 ************************/
add_action('admin_menu', 'custom_options_page_menu');
function custom_options_page_menu() {
    add_menu_page(
        'Shopper Options',             // Page title
        'Shopper Options',             // Menu title
        'manage_options',              // Capability
        'custom-options',              // Menu slug
        'custom_options_page',         // Function to display the page content
        'dashicons-admin-generic',     // Icon URL
        99                             // Position
    );

    add_submenu_page(
        'custom-options',              // Parent slug
        'Webpushr Settings',           // Page title
        'Webpushr Settings',           // Menu title
        'manage_options',              // Capability
        'webpushr-settings',           // Menu slug
        'webpushr_settings_page'       // Function to display the page content
    );

    add_submenu_page(
        'custom-options',              // Parent slug
        'CORS Settings',               // Page title
        'CORS Settings',               // Menu title
        'manage_options',              // Capability
        'cors-settings',               // Menu slug
        'cors_settings_page'           // Function to display the page content
    );

    add_submenu_page(
        'custom-options',              // Parent slug
        'Other Settings',              // Page title
        'Other Settings',              // Menu title
        'manage_options',              // Capability
        'other-settings',              // Menu slug
        'other_settings_page'          // Function to display the page content
    );
}
function custom_options_page() { // display the main options page content
    echo '<div class="wrap"><h1>Main Options Page</h1></div>';
}


/************************
 *      Webpushr
 ************************/
add_action('admin_init', 'webpushr_options_page_settings');
function webpushr_options_page_settings() {
    register_setting('webpushr_options_group', 'webpushrKey');
    register_setting('webpushr_options_group', 'webpushrAuthToken');
    
    add_settings_section(
        'webpushr_options_section',          // ID
        'Webpushr Options Section',          // Title
        'webpushr_options_section_callback', // Callback
        'webpushr-settings'                  // Page
    );
    add_settings_field(
        'webpushrKey',                       // ID
        'Webpushr Key:',                     // Title
        'webpushr_key_callback',             // Callback
        'webpushr-settings',                 // Page
        'webpushr_options_section'           // Section
    );
    add_settings_field(
        'webpushrAuthToken',                 // ID
        'Webpushr Auth Token:',              // Title
        'webpushr_auth_token_callback',      // Callback
        'webpushr-settings',                 // Page
        'webpushr_options_section'           // Section
    );
}
function webpushr_settings_page() { // include the Webpushr settings page
    include(get_template_directory() . '-child/functions/global/options/webpushr-settings.php');
}


/************************
 *        CORS
 ************************/
add_action('admin_init', 'cors_options_page_settings');
function cors_options_page_settings() {
    register_setting('cors_options_group', 'domains');
    
    add_settings_section(
        'cors_options_section',          // ID
        'CORS Options Section',          // Title
        'cors_options_section_callback', // Callback
        'cors-settings'                  // Page
    );    
    add_settings_field(
        'domains',                       // ID
        'Domains:',                      // Title
        'domains_callback',              // Callback
        'cors-settings',                 // Page
        'cors_options_section'           // Section
    );
}
function cors_settings_page() { // include the CORS settings page
    include(get_template_directory() . '-child/functions/global/options/cors-settings.php');
}


/************************
 *        other
 ************************/
add_action('admin_init', 'other_options_page_settings');
function other_options_page_settings() {
    register_setting('other_options_group', 'baseUrl');
    register_setting('other_options_group', 'sellerAppUrl');
    register_setting('other_options_group', 'consumerKey');
    register_setting('other_options_group', 'consumerSecret');
    
    add_settings_section(
        'other_options_section',          // ID
        'Other Options Section',          // Title
        'other_options_section_callback', // Callback
        'other-settings'                  // Page
    );
    add_settings_field(
        'baseUrl',                        // ID
        'Base Url (ends with /):',        // Title
        'base_url_callback',              // Callback
        'other-settings',                 // Page
        'other_options_section'           // Section
    );    
    add_settings_field(
        'sellerAppUrl',                   // ID
        'SellerApp Url (ends with /):',   // Title
        'seller_app_url_callback',        // Callback
        'other-settings',                 // Page
        'other_options_section'           // Section
    );
    add_settings_field(
        'consumerKey',                    // ID
        'Consumer Key:',                  // Title
        'consumer_key_callback',          // Callback
        'other-settings',                 // Page
        'other_options_section'           // Section
    );
    add_settings_field(
        'consumerSecret',                 // ID
        'Consumer Secret:',               // Title
        'consumer_secret_callback',       // Callback
        'other-settings',                 // Page
        'other_options_section'           // Section
    );
}
function other_settings_page() { // include the other settings page
    include(get_template_directory() . '-child/functions/global/options/other-settings.php');
}