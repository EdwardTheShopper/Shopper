<?php

/**
 * Author : Edward Ziadeh
*/
function custom_user_search_endpoint()
{

    register_rest_route('custom/v1', '/user-search', array(
        'methods' => 'GET',
        'callback' => 'custom_user_search_callback',
        'permission_callback' => function () {
            return true;//current_user_can('edit_users'); // Adjust the capability as needed
        },
    ));
}
add_action('rest_api_init', 'custom_user_search_endpoint');

function custom_user_search_callback($request)
{
    $search_term = $request->get_param('search'); // Get the search term from the request

    $args = array(
        'search'         => '*' . esc_attr($search_term) . '*',
        'search_columns' => array('user_login', 'user_email'),
    );

    $users = get_users($args);

    $results = array();
    foreach ($users as $user) {
        $results[] = array(
            'id' => $user->ID,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'display_name' => $user->display_name,
        );
    }

    return $results;
}
