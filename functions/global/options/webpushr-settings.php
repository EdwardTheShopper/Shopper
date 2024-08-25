
<div class="wrap">
    <h1>Webpushr Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('webpushr_options_group');
        do_settings_sections('webpushr-settings');
        submit_button();
        ?>
    </form>
</div>

<?php

function webpushr_options_section_callback() {
    echo 'Enter your Webpushr settings below:';
}

function webpushr_key_callback() {
    $webpushrKey = get_option('webpushrKey');
    echo '<input type="text" style="width: 300px" name="webpushrKey" value="' . esc_attr($webpushrKey) . '" />';
}

function webpushr_auth_token_callback() {
    $webpushrAuthToken = get_option('webpushrAuthToken');
    echo '<input type="text" name="webpushrAuthToken" value="' . esc_attr($webpushrAuthToken) . '" />';
}
?>