<?php

/**
 * Custom function to initiate a password reset for a user.
 *
 * @param string $user_email The email address of the user.
 * Author: Edward Ziadeh
 */
function register_custom_rest_pass_route()
{
    register_rest_route('custom/v1', '/initiate-password-reset/', array(
        'methods' => 'POST',
        'callback' => 'initiate_password_reset'
    ));
}
add_action('rest_api_init', 'register_custom_rest_pass_route');



// Callback function to handle the API request
function initiate_password_reset()
{
    $user_email = $_POST['email'];


    // Check if the email address exists in the database.
    $user = get_user_by('email', $user_email);

    if ($user) {
        // Generate the password reset key.
        $key = get_password_reset_key($user);

        if (!is_wp_error($key)) {
            // Generate the password reset URL.
            $reset_url = esc_url(add_query_arg(
                array(
                    'action' => 'rp',
                    'key' => $key,
                    'login' => rawurlencode($user->user_login),
                ),
                site_url('wp-login.php')
            ));

            // Compose the email message.
            $message = sprintf(__('To reset your password, click on the following link: %s', 'text-domain'), $reset_url);

            // Send the email.
            $subject = __('Password Reset Request', 'text-domain');
            $headers = 'Content-Type: text/html; charset=UTF-8';
            wp_mail($user_email, $subject, $message, $headers);

            echo 'Password reset email sent successfully.';
        } else {
            echo 'Error: ' . esc_html($key->get_error_message());
        }
    } else {
        return 'Error: User not found.';
    }
}
