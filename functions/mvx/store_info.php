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
                    <div class="store-info-toggle">
                        <div class="mvx-contact-deatil">
                            <p class="mvx-address"><span><i class="fa-solid fa-map" style="margin-left: 4px"></i></span><?php echo $address1 . ', ' . $address2 . ' ' . $city . ', ' . $country . ', ' . $post_code; ?></p>
                            <p class="mvx-address"><span><i class="fa-solid fa-phone"></i></i></span> <?php echo $phone; ?></p>
                            <p class="mvx-address"><i class="fa-regular fa-envelope" style="margin-left: 4px"></i><a href="mailto:<?php echo $vendorEmail;?>" class="mvx_vendor_detail"><?php echo  $vendorEmail; ?></a></p>                    
                        </div>
                        <div class="description_data">
                            <?php echo $venDescription;?>
                        </div>
                    </div>
                </div>
            </div>
            <span id="toggle-header" class="arrow-toggle">
                <i class="fa-solid" id='store-info-icon'>i</i>מידע נוסף על החנות<i class='fas fa-angle-down' id="arrow"></i>
            </span>
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
