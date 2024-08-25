
<div class="wrap">
    <h1>CORS Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('cors_options_group');
        do_settings_sections('cors-settings');
        submit_button();
        ?>
    </form>
</div>

<?php

function cors_options_section_callback() {
    echo 'Enter your CORS settings below:';
}

function domains_callback() {
    $domains = get_option('domains');
    echo '<textarea name="domains" style="resize: both; width: 60%; height: 150px">' . esc_textarea($domains) . '</textarea>';
}
?>
