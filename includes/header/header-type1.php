<header id="masthead" class="site-header desktop-shadow-disable mobile-shadow-enable mobile-nav-enable" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
    <?php if (get_theme_mod('bacola_top_header', 0) == 1) { ?>
        <div class="header-top header-wrapper hide-mobile">
            <div class="container">
                <div class="column column-left">
                    <nav class="site-menu horizontal">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'top-left-menu',
                            'container' => '',
                            'fallback_cb' => 'show_top_menu',
                            'menu_id' => '',
                            'menu_class' => 'menu',
                            'echo' => true,
                            "walker" => '',
                            'depth' => 0
                        ));
        ?>
                    </nav><!-- site-menu -->
                </div><!-- column-left -->

                <div class="column column-right">
                    <div class="topbar-notice">
                        <i class="klbth-icon-<?php echo esc_attr(get_theme_mod('bacola_top_header_text_icon')); ?>"></i>
                        <span><?php echo bacola_sanitize_data(get_theme_mod('bacola_top_header_text')); ?></span>
                    </div>

                    <div class="text-content">
                        <?php echo bacola_sanitize_data(get_theme_mod('bacola_top_header_content_text')); ?>
                    </div>

                    <div class="header-switchers">
                        <nav class="store-language site-menu horizontal">
                            <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'top-right-menu',
                                    'container' => '',
                                    'fallback_cb' => 'show_top_menu',
                                    'menu_id' => '',
                                    'menu_class' => 'menu',
                                    'echo' => true,
                                    "walker" => '',
                                    'depth' => 0
                                ));
                            ?>
                        </nav><!-- site-menu -->
                    </div><!-- header-switchers -->

                </div><!-- column-right -->
            </div><!-- container -->
        </div><!-- header-top -->
    <?php } ?>
    <div class="header-main header-wrapper">
        <div class="container">
            <div class="column column-left">
                <div class="header-buttons">
                    <div class="header-canvas button-item">
                        <!-- <a href="#" class="filter-toggle"> Sharon code -->
                        <a href="#">
                            <i class="klbth-icon-menu-thin"></i>
                        </a>
                    </div>
                    <div class="button-item">
                        <a href="<?php echo wc_get_page_permalink('myaccount'); ?>">
                            <i class="klbth-icon-user"></i>
                        </a>
                    </div>
                </div><!-- header-buttons -->
                <div class="site-brand">
                    <?php

                        if (is_front_page() || is_cart() || is_checkout() || is_archive()) {
                            $flag1 = 0;
                        } else {
                            $flag1 = 1;
                            $url = get_vendor_slug($vendorId);
                        }

                        if($flag1 && false) {
                            ?>
                            <a href="<?php echo esc_url($url); ?>" title="<?php bloginfo("name"); ?>">
                            <?php
                        }
                    ?>
                        <?php if (get_theme_mod('bacola_logo')) { ?>
                            <img class="desktop-logo hide-mobile" src="<?php echo esc_url(wp_get_attachment_url(get_theme_mod('bacola_logo'))); ?>" alt="<?php bloginfo("name"); ?>">

                        <?php } elseif (get_theme_mod('bacola_logo_text')) { ?>
                            <span class="brand-text hide-mobile"><?php echo esc_html(get_theme_mod('bacola_logo_text')); ?></span>
                        <?php } else { ?>
                            <img class="desktop-logo hide-mobile" src="<?php echo get_template_directory_uri(); ?>/assets/images/bacola-logo.png" width="164" height="44" alt="<?php bloginfo("name"); ?>">
                        <?php } ?>

                        <?php if (get_theme_mod('bacola_mobile_logo')) { ?>
                            <img class="mobile-logo hide-desktop" src="<?php echo esc_url(wp_get_attachment_url(get_theme_mod('bacola_mobile_logo'))); ?>" alt="<?php bloginfo("name"); ?>">
                        <?php } else { ?>
                            <img class="mobile-logo hide-desktop" src="<?php echo get_template_directory_uri(); ?>/assets/images/bacola-logo-mobile.png" alt="<?php bloginfo("name"); ?>">
                        <?php } ?>
                        <?php if (get_theme_mod('bacola_logo_desc')) { ?>
                            <span class="brand-description"><?php echo esc_html(get_theme_mod('bacola_logo_desc')); ?></span>
                        <?php } ?>
                   <?php  if($flag) { ?> </a> <?php } ?>
                </div>
            </div><!-- column -->
            <div class="column column-center">
                <?php if (get_theme_mod('bacola_location_filter', 0) == 1) { ?>
                    <div class="header-location site-location hide-mobile">
                        <a href="#">
                            <span class="location-description"><?php esc_html_e('Your Location', 'bacola'); ?></span>
                            <?php if (bacola_location() == 'all') { ?>
                                <div class="current-location"><?php esc_html_e('Select a Location', 'bacola'); ?></div>
                            <?php } else { ?>
                                <div class="current-location activated"><?php echo esc_html(bacola_location()); ?></div>
                            <?php } ?>
                        </a>
                    </div>
                <?php } ?>
                <!-- All categories filter -->
                <?php //get_template_part('includes/header/models/sidebar-menu');?>
                <?php if (get_theme_mod('bacola_header_search', 0) == 1) { ?>
                    <?php
                    if(!is_front_page()) { // get store logo
                        $vendor_id = get_vendor_id();
                        $vendor = get_user_meta($vendor_id);
                    ?>
                        <div class="mvx-profile-area">
                            <a href="<?php echo get_vendor_slug($vendor_id); ?>">
                                <img src="<?php echo $vendor['_vendor_profile_image'][0] ?? ""; ?>" alt="">
                            </a>
                        </div>                
                    <?php } ?>
                    <div class="header-search">
                        <?php get_template_part('includes/header/models/sidebar-menu'); ?>
                        <?php if (get_theme_mod('bacola_ajax_search_form', 0) == 1 && class_exists('DGWT_WC_Ajax_Search')) { ?>
                            <?php echo do_shortcode('[wcas-search-form]'); ?>
                        <?php } else { ?>
                            <?php echo bacola_header_product_search(); ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="column column-right">
                <div class="header-buttons">
                    <?php $headeraccounticon  = get_theme_mod('bacola_header_account', '0'); ?>
                    <?php if ($headeraccounticon) { ?>
                        <div class="header-login button-item bordered">
                            <a href="<?php echo wc_get_page_permalink('myaccount'); ?>">
                                <div class="button-icon"><i class="klbth-icon-user"></i></div>
                            </a>
                        </div>
                    <?php } ?>

                    <?php $headercart = get_theme_mod('bacola_header_cart', '0'); ?>
                    <?php if ($headercart == '1') { ?>
                        <?php global $woocommerce; ?>
                        <?php $carturl = wc_get_cart_url(); ?>
                        <div class="header-cart button-item bordered">
                            <a href="<?php echo esc_url($carturl); ?>">
                                <div class="cart-price"><?php echo WC()->cart->get_cart_subtotal(); ?></div>
                                <div class="button-icon"><i class="klbth-icon-shopping-bag"></i></div>
                                <span class="cart-count-icon"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'bacola'), $woocommerce->cart->cart_contents_count); ?></span>
                            </a>
                            <div class="cart-dropdown hide">
                                <div class="cart-dropdown-wrapper">
                                    <div class="fl-mini-cart-content">
                                        <?php woocommerce_mini_cart(); ?>
                                    </div>

                                    <?php if (get_theme_mod('bacola_header_mini_cart_notice')) { ?>
                                        <div class="cart-noticy">
                                            <?php echo esc_html(get_theme_mod('bacola_header_mini_cart_notice')); ?>
                                        </div><!-- cart-noticy -->
                                    <?php } ?>
                                </div><!-- cart-dropdown-wrapper -->
                            </div><!-- cart-dropdown -->
                        </div><!-- button-item -->
                    <?php } ?>
                </div><!-- header-buttons -->
            </div><!-- column -->
        </div><!-- container -->
    </div><!-- header-main -->

    <div class="header-nav header-wrapper hide-mobile">
        <div class="container">

            <?php //get_template_part( 'includes/header/models/sidebar-menu' );
            ?>

            <nav class="site-menu primary-menu horizontal">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'main-menu',
                    'container' => '',
                    'fallback_cb' => 'show_top_menu',
                    'menu_id' => '',
                    'menu_class' => 'menu',
                    'echo' => true,
                    "walker" => new bacola_main_walker(),
                    'depth' => 0
                ));
                ?>
            </nav><!-- site-menu -->
        </div><!-- container -->
    </div><!-- header-nav -->

    <?php do_action('bacola_mobile_bottom_menu'); ?>
</header><!-- site-header -->

<script> // show/hide cart dropdown (for mobile view)
    document.querySelector('.header-cart.button-item').addEventListener('click', function() {
        if(window.innerWidth < 768) {
            var siteOverlay = document.querySelector('.site-overlay');
            var cartDropdown = document.querySelector('.cart-dropdown');

            if(cartDropdown.classList.contains('hide')) {
                cartDropdown.classList.remove('hide');
                siteOverlay.classList.add('active');
                siteOverlay.style.opacity = 1;
                siteOverlay.style.visibility = 'visible';
                siteOverlay.style.zIndex = 998;
            }
            else {
                cartDropdown.classList.add('hide');
                siteOverlay.classList.remove('active');
                siteOverlay.style.opacity = 0;
                siteOverlay.style.visibility = 'hidden';
                siteOverlay.style.zIndex = 1000;
            }
        }
        });
    // close the dropdown when clicking outside of it
    document.addEventListener('click', function(e) {
        if(window.innerWidth < 768) {
            var cartDropdown = document.querySelector('.cart-dropdown');
            var siteOverlay = document.querySelector('.site-overlay');
            if(cartDropdown && !cartDropdown.contains(e.target) &&
                e.target !== cartDropdown && e.target !== document.querySelector('.header-cart.button-item')
            ) {
                cartDropdown.classList.add('hide');
                siteOverlay.classList.remove('active');
                siteOverlay.style.opacity = 0;
                siteOverlay.style.visibility = 'hidden';
                siteOverlay.style.zIndex = 1000;
            }
        }
    });
</script>

<!-- Added for SS-89 -->
<script type="text/javascript">
    var vendorNamee = '<?php
$name = mvx_get_user_meta($vendorId, '_vendor_page_title');
if($name == 'noyatal147') {
    echo mvx_get_user_meta($vendorId, 'nickname');
} else {
    echo $name;
}
?>'
</script>
<!-- End -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.3.0.js"></script>
<script>
    localStorage.removeItem('state-unload');
    localStorage.removeItem('state-load');


    // Function for Saving all data to Database
     function savetoDatabase() {
        visitorDetails = JSON.parse(localStorage.getItem('visitorDetails'));
        url = '<?php echo admin_url('admin-ajax.php'); ?>'
        jQuery.post(url, visitorDetails, function(response, status) {
            if (status != 'success') {
                alert('Failed to save visitor data')
            } else if (status == 'success') {
                console.log(response)
            }
        });
    } 

    if (!localStorage.getItem('visitorDetails')) {
        visitorDetails = {
            'visitorId': '',
            'email': '',
            'feTime': new Date(Date.now()).toString().split('GMT')[0],
            'eTime': '',
            'lTime': '',
            'lat': '',
            'lng': '',
            'pageStamp': [],
            'visitedStore': [],
            'agent': '',

            'action': 'save_visitor_details',
            'inStore': false,
            'storeId': '',
            'firstVisit': true,
            'popupClosed': 0,
        }
        localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
    }

    jQuery(document).ready(function() {

        var vendorStoreLatLng = '';
        var locationFetched;
        var visitorDetails = JSON.parse(localStorage.getItem('visitorDetails'));

        // Generating and Saving Visitor ID
        if (visitorDetails.visitorId == '') {
            function randomFixedInteger(length) {
                return Math.floor(Math.pow(10, length - 1) + Math.random() * (Math.pow(10, length) - Math.pow(10, length - 1) - 1));
            }
            var vid = randomFixedInteger(10);
            visitorDetails.visitorId = vid
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
        }

        // Saving Enter Time
        if (visitorDetails.eTime == '') {
            visitorDetails.eTime = new Date(Date.now()).toString().split('GMT')[0]
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
        }

        // Saving UserAgent
        var userAgent = navigator.userAgent;
        visitorDetails.agent = userAgent;
        localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));

        // Saving Page Stamps
        var pageUrl = jQuery(location).attr('href');
        if (pageUrl.includes('wp-admin') || pageUrl.includes('wp-login')) {

        } else {
            if (!visitorDetails.pageStamp.includes(pageUrl)) {
                visitorDetails.pageStamp.push(pageUrl);
                localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
            }
        }

        // Saving the Store name if visitor visits any Store
        var link = jQuery(location).attr('href');
        if (link.includes('store')) {
            var x = link.split('/');
            var y = x.indexOf('store');
            var storeName = x[y + 1].replace(/-/g, ' ');

            if (!visitorDetails.visitedStore.includes(storeName)) {
                visitorDetails.visitedStore.push(storeName);
                localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
            }
        }

        // Saving visitor Location lat/lng
        const success = function(position) {
            console.log(position)
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            visitorDetails.lat = lat;
            visitorDetails.lng = lng;
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));

            locationFetched = true;
             savetoDatabase();
        }
        const error = function(error) {
            console.log(error);
            locationFetched = false;
        }
        navigator.geolocation.watchPosition(success, error, {
            enableHighAccuracy: true
        });

        // Gettig all Stores lat/lng and do action
        if (visitorDetails.lat != '' && visitorDetails.lng != '') {
            data = {
                action: 'get_vendors_lat_lng',
            }
            url = '<?php echo admin_url('admin-ajax.php'); ?>'
            jQuery.post(url, data, function(response, status) {
                vendorStoreLatLng = JSON.parse(response);
                jQuery.each(vendorStoreLatLng, function(i, val) {
                    storePoint = {
                        lat: val[0],
                        lng: val[1]
                    }

                    visitorPoint = {
                        lat: visitorDetails.lat,
                        lng: visitorDetails.lng
                    }

                    function arePointsNear(checkPoint, centerPoint, km) {
                        var ky = 40000 / 360;
                        var kx = Math.cos(Math.PI * centerPoint.lat / 180.0) * ky;
                        var dx = Math.abs(centerPoint.lng - checkPoint.lng) * kx;
                        var dy = Math.abs(centerPoint.lat - checkPoint.lat) * ky;
                        return Math.sqrt(dx * dx + dy * dy) <= km;
                    }

                    var n = arePointsNear(visitorPoint, storePoint, 0.1);

                    if (n == true) {
                        visitorDetails.inStore = true;
                        visitorDetails.storeId = i;
                        localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
                        console.log('Is in Store');
                    } else {
                        console.log('Not in Store');
                    }
                });
            });
        }

        // Checking if the browseris reloading
       jQuery(window).on('load', function(event) {
            if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
                localStorage.setItem('state-load', 'true');
            }
        });

        // close popup
        jQuery('.closeCpnTRC2').on('click', function(e) {
            e.preventDefault();
            jQuery('.overlayCpnTRC2').hide();
        })

        jQuery('.closeCpnTRC').on('click', function(e) {
            e.preventDefault();
            jQuery('#popupCpnTRC').fadeOut('slow');
            var link = jQuery(location).attr('href');
            if (link.includes('cart')) {
                visitorDetails.popupClosed = 2;
            } else {
                visitorDetails.popupClosed = 1;
            }
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
        })

        // submit popup
        jQuery('.btn_cuponTRC').on('click', function(e) {
            e.preventDefault();
            var email = jQuery('.emailCuponTRC').val();

            visitorDetails.email = email;
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));

            // Saving Email
            if (email != '') {
                data = {
                    action: 'save_email_on_submit',
                    visitorId: visitorDetails.visitorId,
                    email: email,
                    eTime: visitorDetails.eTime,
                    lTime: visitorDetails.lTime,
                }
                url = '<?php echo admin_url('admin-ajax.php'); ?>'
                jQuery.post(url, data, function(response, status) {
                    console.log(response)
                });
            }

            // sending promotional email
            if (email != '') {
                data = {
                    action: 'send_prom_email',
                    email: email
                }
                url = '<?php echo admin_url('admin-ajax.php'); ?>'
                jQuery.post(url, data, function(response, status) {
                    console.log(response)
                });
            }

            jQuery('#popupCpnTRC').fadeOut('slow');
        });


        // Function to check if Location is fully fetched
        function watchVariable(oldvalue) {
            clearcheck = setInterval(repeatcheck, 500, oldvalue);

            function repeatcheck(oldvalue) {
                console.log('Fetching Location...');
                if (locationFetched !== oldvalue) {
                    // do something
                    clearInterval(clearcheck);
                    console.log('Location Fetched.');

                    // saving data to database
                    savetoDatabase();
                    console.log('Data Saved to Database.');
                }
            }
        }
        watchVariable(locationFetched);

    });

    // Leave time Functionality
    jQuery(window).on('unload', function(event) {
        localStorage.setItem('state-unload', 'true');
        if (localStorage.getItem('state-unload') == 'true') {

            visitorDetails = JSON.parse(localStorage.getItem('visitorDetails'));
            visitorDetails.lTime = new Date(Date.now()).toString().split('GMT')[0];
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
            savetoDatabase();

        }
    });


    // sidebar categories appearence change
    jQuery(document).ready(function() {

        jQuery('.mvx_widget_vendor_product_categories ul.children').closest('li').addClass("cat-parent").removeClass('not-main');
        jQuery(".mvx_widget_vendor_product_categories ul > li.cat-parent").append('<span class="sdsubDropdown minus"></span>');
        jQuery('.mvx_widget_vendor_product_categories ul.children input[checked]').closest('li.cat-parent').addClass("current-cat");


        jQuery(".sdsubDropdown")[0] && jQuery(".sdsubDropdown").on("click", function() {
            jQuery(this).toggleClass("plus"), jQuery(this).toggleClass("minus"),
                jQuery(this).prev('ul.children').slideToggle();
        });

        jQuery('.mvx_widget_vendor_product_categories .cat-parent:has(ul.children:empty)').hide();
        jQuery('.mvx_widget_vendor_product_categories .not-main').hide();
        jQuery('.mvx_widget_vendor_product_categories ul > li[data-products-qty=0]').hide();

    })

    // main menu appearence change
    

    // Product categories appearence change
    jQuery(document).ready(function() {

        jQuery('.sign').on('click', function() {
            if (jQuery(this).text() == '+') {
                jQuery(this).text('-');
                jQuery(this).next('.cat-ul-child').css('visibility', 'visible');
            } else {
                jQuery(this).text('+');
                jQuery(this).next('.cat-ul-child').css('visibility', 'hidden');
            }

        })

        jQuery('.store-categories ul.children').closest('li').addClass("cat-parent").removeClass('not-main');
        jQuery('.store-categories .cat-parent:has(ul.children:empty)').hide();
        jQuery('.store-categories .not-main').hide();
        jQuery('.store-categories ul > li[data-products-qty=0]').hide();

    })

    jQuery(document).ready(function() {

        jQuery('.close-sidebar').on('click', function() {
            jQuery('#sidebar').css('transform', 'translate(-2749.6px, 0px)');
        })

    })


    
    jQuery(document).ready(function() {
        // Get current page URL
        var currentUrl = window.location.href;

        // Check if URL includes "store"
        if (currentUrl.indexOf("store") !== -1) {
            // URL contains "store"
        } else {
            // URL does not contain "store"
            var menu = jQuery('.cat-left .menu');
            var storeCategories = jQuery('.store-categories');
            var menuHtml = menu.detach().html();
            storeCategories.after(menuHtml);
        }

    })
</script>
<!-- Popup AND Visitor Functionality => ENDS -->