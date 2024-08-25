<?php

function enqueue_blockui()
{
    wp_enqueue_script('blockui', get_template_directory_uri() . '-child/assets/js/jquery.blockUI.js', array('jquery'), '2.7', true);
}
add_action('wp_enqueue_scripts', 'enqueue_blockui');

/**
 * Add a jquery file @translateused becuseI didn't find a way to translate this word
 * Author: Edward Ziadeh
 * Date: 10/11/2023
 */
function enqueue_custom_script()
{
    // Enqueue the script
    wp_enqueue_script('custom-script', get_template_directory_uri() . '-child/assets/js/translate.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');
