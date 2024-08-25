<?php

function insert_php_file_at_footer()
{

    ?>

    
    <script>
    jQuery(document).ready(function(){
    var x = document.getElementById("demo");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
    jQuery(document).ajaxStop(function(){
        if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
    });
    });

    function showPosition(position) {
    var x = document.getElementById("location");
    var latitude1 = position.coords.latitude;
    var longitude1 = position.coords.longitude;
    jQuery('#vendors-latlng li').each(function(i, li) {
        console.log('vendors-latlng li');
    var latitude2 = jQuery(this).attr("data-lat");  
    var longitude2 = jQuery(this).attr("data-lng"); 
    var id = jQuery(this).attr("data-id"); 

    if(latitude2 !=='' || longitude2 !==''){
    var start = {lat: parseFloat(latitude1), lng: parseFloat(longitude1)};
    var destination = {lat: parseFloat(latitude2), lng: parseFloat(longitude2)}; 
    let directionsService = new google.maps.DirectionsService();
    let directionsRenderer = new google.maps.DirectionsRenderer();
    //directionsRenderer.setMap(map); // Existing map object displays directions
    // Create route from existing points used for markers
    const route = {
        origin: start,
        destination: destination,
        travelMode: 'DRIVING'
    }

    directionsService.route(route,
        function(response, status) { // anonymous function to capture directions
        //alert(id);
        if (status !== 'OK') {
            //window.alert('Directions request failed due to ' + status);
            return;
        } else {
            directionsRenderer.setDirections(response); // Add route to the map
            var directionsData = response.routes[0].legs[0]; // Get data about the mapped route
            if (!directionsData) {
            window.alert('Directions request failed');
            return;
            }
            else {
            var distance = directionsData.distance.text + ' from you';
            var store_cls = 'dist-'+id;
                jQuery('.'+store_cls).html(distance);
            }
        }
        });   
    }
    });
    }
    jQuery('body').on('click', '.quick-view-button', function() {
    setTimeout(function(){
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    window.scrollTo(0, 0);
    }
    else{
        jQuery('.mfp-wrap').scrollTop(0);
    }
        }, 1000);
    });
    jQuery('body').on('click', '.wcmp-report-abouse-wrapper .close', function() {});
    jQuery('body').on('click', ' #report_abuse', function() {});
    </script>


    <script>
        
        var visitorDetails = JSON.parse(localStorage.getItem('visitorDetails'))

        if (window.location.pathname == "/" ){
        mixpanel.track("Home page viewed", {
            "VisitorId":  visitorDetails.visitorId
        });
    } else if (window.location.pathname.includes("/cart")) {

        mixpanel.track("Cart Page viewed", {
            "VisitorId": visitorDetails.visitorId
        });
    } else if (window.location.pathname.includes("/dashboard")) {
        mixpanel.track("Dashboard Page viewed", {
            "VisitorId": visitorDetails.visitorId
        });
        jQuery(".woocommerce-form-register__submit").click(function () {
            mixpanel.track("Enrollment button clicked", {

                "Email": jQuery("#reg_email").val(),
                "VisitorId": visitorDetails.visitorId,
                "path": "/store-enrollment"
            });
        });
        jQuery(".woocommerce-form-login__submit").click(function () {
            mixpanel.track("Connect Button Clicked", {

                "Email": jQuery("#username").val(),
                "VisitorId": visitorDetails.visitorId,
                "path": "/store-enrollment"
            });
        });

    } else if (window.location.pathname == '/wishlist/') {
        mixpanel.track("Wishlist Page viewed", {
            "VisitorId": visitorDetails.visitorId
        });


    } else if (window.location.pathname.includes("/order-tracking")) {
        mixpanel.track("Order Tracking Page viewed", {
            "VisitorId": visitorDetails.visitorId,
        });
        jQuery(".button").click(function () {
            mixpanel.track("Tracking Button Clicked", {

                "Order id": jQuery("#orderid").val(),
                "Email": jQuery("#order_email").val(),
                "VisitorId": visitorDetails.visitorId
            });
        });


    } else if (window.location.pathname.includes("/about-us")) {
        mixpanel.track("About Us Page viewed", {
            "VisitorId": visitorDetails.visitorId
        });
        // write your traking code fro page 2

    } else if (window.location.pathname.includes("/my-account")) {
        mixpanel.track("My Account Page viewed", {
            "VisitorId": visitorDetails.visitorId
        });
        // write your traking code fro page 2

        jQuery(".woocommerce-form-register__submit").click(function () {
            mixpanel.track("Enrollment button clicked", {

                "Email": jQuery("#reg_email").val(),
                "VisitorId": visitorDetails.visitorId,
                "Path": '/my-account/'
            });
        });

        jQuery(".woocommerce-form-login__submit").click(function () {
            mixpanel.track("Connect Button Clicked", {

                "Email": jQuery("#username").val(),
                "VisitorId": visitorDetails.visitorId,
                "Path": '/my-account/'
            });
        });

    } else if (window.location.pathname == '/my-account/orders/') {
        mixpanel.track("Order Page viewed", {
            "VisitorId": visitorDetails.visitorId
        });
        // write your traking code fro page 2


    } else if (window.location.pathname == '/my-account/edit-account/') {
        mixpanel.track("Edit Account Page viewed", {
            "VisitorId": visitorDetails.visitorId
        });
        // write your traking code fro page 2

    } else if (window.location.pathname.includes("/checkout")) {
        mixpanel.track("checkout Page viewed",{
            "VisitorId": visitorDetails.visitorId
            
        });
        // place order button
        jQuery("#place_order").click(function () {
            mixpanel.track("Place Order Button Clicked", {
                "billing_first_name": jQuery("#billing_first_name").val(),
                "billing_last_name": jQuery("#billing_last_name").val(),
                "billing_company": jQuery("#billing_company").val(),
                "billing_country": jQuery("#billing_country").val(),

                "billing_address_1": jQuery("#billing_address_1").val(),
                "billing_address_2": jQuery("#billing_address_2").val(),
                "billing_city": jQuery("#billing_city").val(),
                "billing_state": jQuery("#billing_state").val(),

                "billing_postcode": jQuery("#billing_postcode").val(),
                "billing_phone": jQuery("#billing_phone").val(),
                "billing_email": jQuery("#billing_email").val(),
                "order_comments": jQuery("#order_comments").val(),
                "billing_email": jQuery("#billing_email").val(),
                "Total Price": jQuery('.woocommerce-Price-amount').text(),
                "VisitorId": visitorDetails.visitorId

            });
        });

    } else if (window.location.pathname.includes("/shop-2")) {
        mixpanel.track("Shop-2 Page viewed", {
            "VisitorId": visitorDetails.visitorId
        });
        // write your traking code fro page 2
    } else {
        console.log("code running")
    }
    // close subscription form
    jQuery('.closeCpnTRC').on('click', function (e) {
        mixpanel.track('close subscribe form', {
            "VisitorId": visitorDetails.visitorId,
        });
    });

    // close wishlist popup
    jQuery('.tinvwl_button_close').on('click', function (e) {
        mixpanel.track('close Wishlist popup', {
            "VisitorId": visitorDetails.visitorId,
        });
    });

    // Subscribe form Submit
    jQuery(".btn_cuponTRC").click(function () {
        if (jQuery("[name='emailCuponTRC']").val() != '') {
            mixpanel.track("Subscribe Form Submit", {
                "Email": jQuery("[name='emailCuponTRC']").val(),
                "VisitorId": visitorDetails.visitorId
            });
        }
    });
            //quick view
            jQuery(".product-buttons .quick-view-button").click(function() {
    var storeName = jQuery(this).parent('.product-buttons').parent('.thumbnail-wrapper').next('.content-wrapper').find('.store-name').children('a').text();
    var pName = jQuery(this).parent('.product-buttons').parent('.thumbnail-wrapper').next('.content-wrapper').find('.product-title').children('a').text();

    mixpanel.track('Open Product Popup',{
    'store Title':pName,
    'store Title':storeName,
        "VisitorId": visitorDetails.visitorId
    });
    });
    // Apply coupan

    jQuery("button[name='apply_coupon']").click(function () {

        mixpanel.track("Apply Promo Button", {
            "Promo Code": jQuery('#coupon_code').val()
        });

    });

    <?php

        if (function_exists('is_product') && is_product()) {
            $id = get_the_ID();
            $vendor = get_mvx_product_vendors($id);
            if (!$vendor) {
                return;
            } else {
                $vendorName = $vendor->user_data->data->display_name;
                $email = $vendor->user_data->data->user_email;
                $address1 = get_user_meta($vendor->user_data->data->ID, '_vendor_address_1', true);
                $address2 = get_user_meta($vendor->user_data->data->ID, '_vendor_address_2', true);
                $city = get_user_meta($vendor->user_data->data->ID, '_vendor_city', true);
                $postCode = get_user_meta($vendor->user_data->data->ID, '_vendor_postcode', true);
                $phone = get_user_meta($vendor->user_data->data->ID, '_vendor_phone', true);
                $country = get_user_meta($vendor->user_data->data->ID, '_vendor_country', true);
                $countryCode = get_user_meta($vendor->user_data->data->ID, '_vendor_country_code', true);
                $pageSlug = get_user_meta($vendor->user_data->data->ID, '_vendor_page_slug', true);
                $pageURL = home_url('/store/' . $pageSlug);
            }
        }
    ?>
    // Store categories
    <?php if(!is_404()) { ?>
    if (window.location.pathname.includes("/store") && window.location.pathname.includes("/product")) {

            mixpanel.track("Product Page view ", {
                    "Product Title": jQuery(".product_title").text(),
                    "SKU": jQuery(".sku").first().text() || "NA",
                    "Brand Name": jQuery(".woocommerce-product-attributes-item--attribute_pa_brands a").text() || "NA",
                    "Shop Name": jQuery(".store-info a").text() || "NA",
                    "Store Main category": jQuery(".woocommerce-breadcrumb").find("li").eq(1).text(),
                    "Store Sub category": jQuery(".woocommerce-breadcrumb").find("li").eq(2).text(),
                    "Store Sub category": jQuery(".woocommerce-breadcrumb").find("li").eq(3).text(),
                    "streetAddress": "<?php echo $address1 . ' ' . $address2; ?>",
                    "AddressLocality": "<?php echo $city; ?>",
                    "AddressRegion": "<?php echo $countryCode; ?>",
                    "PostalCode": "<?php echo $postCode; ?>",
                    "AddressCountry": "<?php echo $country; ?>",
                    "VisitorId": visitorDetails.visitorId
                });

        // Store personal social icon
        jQuery(".wcmp_social_profile").find("a").eq(0).click(function () {
            mixpanel.track("Store facebook icon clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });
        jQuery(".wcmp_social_profile").find("a").eq(1).click(function () {
            mixpanel.track("Store instgram icon clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });
        //Store Filter
        jQuery(".anckrDv").click(function () {
            mixpanel.track("Store Filter clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery(".mobile-filter").click(function () {
            mixpanel.track("Store Filter clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });
        jQuery("#plocationjs").find("a").eq(0).click(function () {
            mixpanel.track("Store address clicked", {
                "Store Address": jQuery("#plocationjs").find("a").eq(0).text(),
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });


        jQuery(".address-row-2").find("a").eq(1).click(function () {
            mixpanel.track("Store Email clicked", {
                "Store Email": jQuery(".address-row-2").find("a").eq(1).text(),
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery(".address-row-2").find("a").eq(0).click(function () {
            mixpanel.track("Store Contact clicked", {
                "Store Contact No": jQuery(".address-row-2").find("a").eq(0).text(),
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });


        jQuery('.facebook').click(function () {
            mixpanel.track("Facebook Icon clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery('.twitter').click(function () {
            mixpanel.track("Twitter Store Icon clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery('.pinterest').click(function () {
            mixpanel.track("Pinterest Store Icon clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery('.linkedin').click(function () {
            mixpanel.track("Linkedin Store Icon clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery('.reddit').click(function () {
            mixpanel.track("Reddit Store Icon clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery('.klbth-icon-menu-thin').click(function () {
            mixpanel.track("Store Categories clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery('.klbth-icon-list-grid').click(function () {
            mixpanel.track("Store List grid clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery('.klbth-icon-2-grid').click(function () {
            mixpanel.track("Store grid clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });
        jQuery('.klbth-icon-3-grid').click(function () {
            mixpanel.track("Store grid clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });
        jQuery('.klbth-icon-4-grid').click(function () {
            mixpanel.track("Store grid clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

        jQuery('.filter-toggle').click(function () {
            mixpanel.track("Store filter clicked", {
                "store Title": jQuery(".wcmp-heading").text(),
                "VisitorId": visitorDetails.visitorId
            })
        });

    }
    <?php  } ?>
    if (window.location.pathname.includes("/product-category")) {
        mixpanel.track("Product category page view ", {
            "Product Main category": jQuery(".woocommerce-breadcrumb").find("li").eq(1).text(),
            "Product Sub category": jQuery(".woocommerce-breadcrumb").find("li").eq(2).text(),
            "Product Sub category": jQuery(".woocommerce-breadcrumb").find("li").eq(3).text(),
            "VisitorId": visitorDetails.visitorId
        });
    }

        //product page view

                var Singleproduct = window.location.pathname.split('/');

                if (Singleproduct[1]== "product") {
            mixpanel.track("product Page view ", {
            "store Title": jQuery(".vendor-info").find("a").eq(1).text(),
            "Product name": jQuery(".product_title").text(),
            "Product Main category": jQuery(".woocommerce-breadcrumb").find("li").eq(1).text(),
            "Product Sub category": jQuery(".woocommerce-breadcrumb").find("li").eq(2).text(),
            "Product Sub category": jQuery(".woocommerce-breadcrumb").find("li > a").eq(3).text(),
            "VisitorId": visitorDetails.visitorId

        });

    }
    <?php if(!is_404()) { ?>
    if (window.location.pathname.includes("/store") && !window.location.pathname.includes("/product")){
        mixpanel.track("store page view",{

            "Shop Name"    :  jQuery(".mvx-heading").text(),
            "Shop Address" :  jQuery(".mvx-contact-deatil").find("p").eq(0).text(),
            "Shop Phone"   :  jQuery(".mvx-contact-deatil").find("p").eq(1).text(),
            "Shop Email"   :  jQuery(".mvx-contact-deatil").find("p").eq(2).text(),
            "VisitorId"    :  visitorDetails.visitorId
        });
        }
        <?php } ?>
        
        
        
        <?php if (@$_GET['s']) { ?>
        mixpanel.track("Store Search Form Submit",
            { "Search query": "<?php echo @$_GET['s']; ?>",
            "VisitorId": visitorDetails.visitorId
            }
        );
        <?php } ?>

    </script>
<?php
}
add_action('wp_footer', 'insert_php_file_at_footer');