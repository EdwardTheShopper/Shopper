<?php

function add_popup_script() {
    
    // make sure the current URL is associated with a specific store (store page, product page, search page etc..)
    if(strpos($_SERVER['REQUEST_URI'], '/store/') === false)
        return;

    /* NOTE: the popup shouldn't appear on "general" pages, therefore, do not use the function "get_vendor_id()" */
    $vendor_id = mvx_find_shop_page_vendor();
    if(!$vendor_id) // double check
        return;
    
    set_vendor_cookie($vendor_id); // used by fibosearch

    // check if popup is active (ACF)
    if(!get_field('acf_enable_popup', 'user_' . $vendor_id))
        return;
    
    if(!isset($_COOKIE[$vendor_id . '_popupCountdown']))
        // define a cookie: show popup # times (ACF) // value is always defined and greater than 0 
        setcookie($vendor_id . '_popupCountdown', get_field('acf_show_n_times', 'user_' . $vendor_id), 
            (time() + /* 24h in seconds */ 86400 + /* GMT+3 fix */ 10800), '/');

    ?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(function() {
                    var countdown = getCookie('<?php echo $vendor_id . '_popupCountdown'; ?>');

                    if(countdown > 0) {
                        // generate popup
                        var popupContainer = document.createElement('div');
                        popupContainer.innerHTML = `
                            <div class="overlay" id="overlay"></div>
                            <div id="popup" class="popoup-sale-global">
                                <div class="popup-message"><?php echo get_field('acf_popup_message', 'user_' . $vendor_id); ?></div>
                                <button><a href="<?php echo get_vendor_slug($vendor_id); ?>">לדף החנות</a></button>
                                <span style="position: absolute; top: 10px; right: 10px; cursor: pointer;" onclick="closePopup()">X</span>
                            </div>
                        `;

                        document.body.appendChild(popupContainer); // set popup visible
                        document.getElementById('overlay').style.display = 'block'; // set overlay visible

                        document.body.addEventListener('click', function() {
                            // close popup when clicking outside of it
                            closePopup();
                        });
                        document.getElementById('popup').addEventListener('click', function(event) {
                            // avoid closing the popup when clicking on the popup itself
                            event.stopPropagation();
                        });

                        // decrement countdown
                        setCookie('<?php echo $vendor_id . '_popupCountdown'; ?>', countdown-1, 86400, '/');
                    }
                }, 3000); // 3 seconds delay
            });

            function closePopup() {
                var popup = document.getElementById('popup');
                if (popup) {
                    document.getElementById('overlay').style.display = 'none';
                    popup.parentNode.removeChild(popup);
                }
            }
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                return parts.length === 2 ? parts.pop().split(';').shift() : null;
            }
            function setCookie(name, value) {
                document.cookie = `${name}=${value}; expires=${
                    (()=>{
                        // TODO: get the original 'expires' parameter instead of generating a new one
                        const date = new Date();
                        date.setTime(date.getTime() + ((/* 24h in seconds */ 86400 + /* GMT+3 fix */ 10800)/* convert to milliseconds */ * 1000));
                        return date.toUTCString();
                    })()
                    }; path=/`
            }

        </script>
    <?php
}
add_action('wp_footer', 'add_popup_script');