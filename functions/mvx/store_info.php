<?php

// Define the shortcode function
function store_info_shortcode_function($atts)
{
    $vendor_id = $atts['id'];
    $vendor_url = get_vendor_slug($vendor_id);
    if(!$vendor_id) {
        return;
    }
    $vendor = get_user_meta($vendor_id);
    $vendorImage = $vendor['_vendor_profile_image'][0] ?? "";
    $phone = $vendor['_vendor_phone'][0] ?? "";
    $post_code = $vendor['_vendor_postcode'][0] ?? "";
    $vendorNickname = $vendor['wpseo_title'][0] ?? "";
    $vendoreName = $vendor['first_name'][0] . " " . $vendor['last_name'][0];

    if (isset($vendor['_vendor_description'][0])) {
        $venDescription = $vendor['_vendor_description'][0];
    } elseif(isset($vendor['_vendor_message_to_buyers'][0])) {
        $venDescription = $vendor['_vendor_message_to_buyers'][0];

    } else {
        $venDescription = "";
    }

    $venDescription = str_replace('"', ' ', $venDescription);

    $country = $vendor['_vendor_country'][0] ?? "";
    $city = $vendor['_vendor_city'][0] ?? "";
    $address1 = $vendor['_vendor_address_1'][0] ?? "";
    $address2 = $vendor['_vendor_address_2'][0] ?? "";
    $vendorEmail = $vendor['billing_email'][0] ?? "";
    ?>
    <header class="shopper-store-header">
        <div class="mvx_bannersec_start mvx-theme01">
            <div class="mvx-banner-wrap">
                <div class="mvx-banner-below">
                    <div class="mvx-banner-middle">
                        <div class="mvx-profile-area">
                            <a href="<?php echo $vendor_url; ?>"><img src="<?php echo $vendorImage; ?>" class="mvx-profile-imgcls" alt=""></a>
                        </div>
                        <div class="mvx-heading"><?php echo bacola_vendor_name() ?? $vendorNickname; ?></div>
                    </div>
                </div>
            </div>
            <?php
                $location = $address1 . ', ' . $address2 . ' ' . $city . ', ' . $country . ', ' . $post_code;
                render_store_info_widget($location, $phone, $vendorEmail, $venDescription);
            ?>
        </div>
    </header>
    <div id="separator-line" style="text-align: center;">
        <hr>
    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery('#toggle-header').click(function() {
                var $storeInfo = jQuery('.store-info-toggle');
                // open / close panel
                jQuery('.arrow-toggle').toggleClass('expanded');
                $storeInfo.animate(
                    { height: $storeInfo.height() === 0  ? $storeInfo[0].scrollHeight : 0 }
                , 300);
                // flip arrow
                jQuery('#toggle-header #arrow').toggleClass('fa-angle-up fa-angle-down');
            });
        });
    </script>
<?php

    return;
}

// Register the shortcode
add_shortcode('store_info_shortcode', 'store_info_shortcode_function');


function render_store_info_widget($location, $mobile, $email, $description) {
    $vendor_id = get_vendor_id();
    $vendor_hide_address = apply_filters('mvx_vendor_store_header_hide_store_address', get_user_meta($vendor_id, '_vendor_hide_address', true), $vendor_id);
    $vendor_hide_phone = apply_filters('mvx_vendor_store_header_hide_store_phone', get_user_meta($vendor_id, '_vendor_hide_phone', true), $vendor_id);
    $vendor_hide_email = apply_filters('mvx_vendor_store_header_hide_store_email', get_user_meta($vendor_id, '_vendor_hide_email', true), $vendor_id);

    echo 
    '<div class="description_data store-info-toggle">' .
        '<div class="mvx-contact-deatil">';
    if(!empty($location) && $vendor_hide_address != "Enable") {
        echo 
        '<p class="mvx-address">' .
            '<span><i class="fas fa-map"></i></span>'
            . esc_html($location) .
        '</p>';
    }
    if(!empty($mobile) && $vendor_hide_phone != "Enable") {
        echo 
        '<p class="mvx-address">' .
            '<span><i class="fas fa-phone"></i></span>'
            . apply_filters("vendor_shop_page_contact", $mobile, $vendor_id) .
        '</p>';
    }
    if(!empty($email) && $vendor_hide_email != "Enable") {
        echo
        '<p class="mvx-address">' .
            '<a href="mailto:' . apply_filters("vendor_shop_page_email", $email, $vendor_id) . '" class="mvx_vendor_detail">' .
                '<i class="fas fa-envelope"></i>'
                . apply_filters("vendor_shop_page_email", $email, $vendor_id) .
            '</a>' .
        '</p>';
    }
    /* Custom code by Edward Ziadeh */ 
    $acf_field = get_user_meta($vendor_id);
    $accessible = unserialize($acf_field["accessible_physically"][0]);
    $accessible = $accessible[0] ?? 0;
    $descriptionAccess = ($acf_field["accessible_description"][0]);
    if($accessible) {
        echo
        '<p class="mvx-address">' .
            '<span><i class="fas fa-wheelchair"></i></span>'
            . $descriptionAccess .
        '</p>';
    }
    if(apply_filters("is_vendor_add_external_url_field", true, $vendor_id)) {
        $external_store_url = get_user_meta($vendor_id, "_vendor_external_store_url", true);
        $external_store_label = get_user_meta($vendor_id, "_vendor_external_store_label", true);
        if(empty($external_store_label))
            $external_store_label = __("External Store URL", "multivendorx");
        if(isset($external_store_url) && !empty($external_store_url)) {
            echo
            '<p class="external_store_url">' .
                '<label>' .
                    '<a target="_blank" href="' . apply_filters("vendor_shop_page_external_store", esc_url_raw($external_store_url), $vendor_id) . '">'
                        . esc_html($external_store_label) .
                    '</a>' .
                '</label>' .
            '</p>';
        }
    }
    do_action("mvx_after_vendor_information",$vendor_id);
    echo 
    '</div>' . wp_kses_post(htmlspecialchars_decode( wpautop( $description ), ENT_QUOTES )) . '</div>' .
    '<span id="toggle-header" class="arrow-toggle">' .
        '<i class="fa-solid" id="store-info-icon">i</i>מידע נוסף על החנות<i class="fas fa-angle-down" id="arrow"></i>' .
    '</span>';
}