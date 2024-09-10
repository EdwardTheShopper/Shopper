<?php
/**
 * Authour: Edward Ziadeh
 * Created: 3 Mar, 2024
 * This Function only effect when pressing enter or go, search on mobile
 */


function searchfilter($query)
{
    $vendor_id = get_vendor_id();

    if ($query->is_search && !is_admin() && $vendor_id) {
        $query->query_vars['author'] = $vendor_id;
        $query->set('post_type', array('product'));
        return $query;
    }
    return;
}

add_filter('pre_get_posts', 'searchfilter');
