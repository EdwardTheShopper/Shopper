<?php

add_filter('wc_order_statuses', 'ab_rename_order_status_msg', 20, 1);
function ab_rename_order_status_msg($order_statuses)
{
    $order_statuses['wc-processing'] = _x('pending', 'woocommerce');
    return $order_statuses;
}


function register_shipment_arrival_order_status()
{
    register_post_status('wc-ready-pickup', array(
        'label'                     => 'Ready to pickup',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop('Ready to pickup <span class="count">(%s)</span>', 'Ready to pickup <span class="count">(%s)</span>')
    ));
}
add_action('init', 'register_shipment_arrival_order_status');



function add_ready_to_pickup_to_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[ $key ] = $status;
        if ('wc-processing' === $key) {
            $new_order_statuses['wc-ready-pickup'] = 'Ready to pickup';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_ready_to_pickup_to_order_statuses');
