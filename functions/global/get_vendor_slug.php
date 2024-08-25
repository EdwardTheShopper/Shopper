<?php
/* 
 Need to work on the logic
*/
function get_vendor_slug($vendor_id) {

    global $MVX;
    $fields = $MVX->user->get_vendor_fields($vendor_id);
    $result = "/store/" . $fields['vendor_page_slug']['value'];

    return $result;
}


