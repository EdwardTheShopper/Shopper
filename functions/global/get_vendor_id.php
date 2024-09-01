<?php
    function get_vendor_id() {
        if(strpos($_SERVER['REQUEST_URI'], '/store/') !== false)
            // the current page is always associated with a specific store
            $vendor_id = mvx_find_shop_page_vendor();
        else if(is_checkout() || is_cart())
            // the curent page is associated with a specific store IF there are products in the cart
            $vendor_id = get_vendor_id_from_cart();
        else // other pages
            $vendor_id =  $_COOKIE['vendorId'];
            /* NOTE: cannot use the cookie right from the start, because sometimes it might be unset */
        return $vendor_id;    
    }

    function set_vendor_cookie($vendor_id, $expires = 'UNSET') {
        if($expires == 'UNSET')
            $expires = (time() + /* 24h in seconds */ 86400  + /*GMT+3 fix*/ 10800);
        setcookie('vendorId', $vendor_id, $expires, '/');
    }

?>