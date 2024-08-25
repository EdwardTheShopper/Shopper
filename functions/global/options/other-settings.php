<div class="wrap">
    <h1>Other Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('other_options_group');
        do_settings_sections('other-settings');
        submit_button();
        ?>
    </form>
</div>

<?php

function other_options_section_callback() {
    echo 'Enter your other settings below:';
}

function base_url_callback() {
    $baseUrl = get_option('baseUrl');
    echo '<input type="text" style="width: 250px;" name="baseUrl" value="' . esc_attr($baseUrl) . '" />';
}
function seller_app_url_callback() {
    $sellerAppUrl = get_option('sellerAppUrl');
    echo '<input type="text" style="width: 250px;" name="sellerAppUrl" value="' . esc_attr($sellerAppUrl) . '" />';
}
function consumer_key_callback() {
    $consumerKey = get_option('consumerKey');
    echo '<input type="text" style="width: 340px;" name="consumerKey" value="' . esc_attr($consumerKey) . '" />';
}
function consumer_secret_callback() {
    $consumerSecret = get_option('consumerSecret');
    echo '<input type="text" style="width: 340px;" name="consumerSecret" value="' . esc_attr($consumerSecret) . '" />';
}

?>
