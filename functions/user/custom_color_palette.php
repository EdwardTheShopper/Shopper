<?php

add_action('show_user_profile', 'extract_colors_from_logo_handler');
add_action('edit_user_profile', 'extract_colors_from_logo_handler');
function extract_colors_from_logo_handler($user) {
    if($user->roles[0] === 'dc_vendor') {
        ?>
            <div id="extracting-colors-container" style="display: flex; align-items: center; width: 830px;">
                <input type="button" class="button button-primary" id="extracting-colors-button" value="Extract colors from my logo">
                <div id="extracted-colors" style="display: none; margin: 5px; padding: 5px; border: 1px solid grey; overflow: scroll;"></div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function($) {

// TODO: improvement- set the button's position during page loading (don't wait for "document.ready")

                    // add the button to the "Theme color palette" section (in the vendor page)
                    $('tr.acf-field-66ad022433d3e').before($('#extracting-colors-container'));
                    $('tr.acf-field-color-picker').css('display', 'flex'); // fix alignment

                    // define onClick listener
                    $('#extracting-colors-button').click(function() {                        
                        $('#extracting-colors-container .spinner').css('visibility', 'visible'); // turn on spinner


                        extract_colors_from_logo().then(extracted_colors => {
                            setTimeout(() => {
                                var htmlContent = '';
                                $.each(extracted_colors, function(index, color) {
                                    htmlContent += `
                                        <div style="display: flex; align-items: center; margin-right: 15px;">
                                            <div class="color-box" style="background-color: ${color.color}; width: 2rem; height: 1.5rem; margin: 5px;"></div>
                                            <span class="color-label">${color.percentage}% ${color.color}</span>
                                        </div>
                                    `;
                                });
                                $('#extracted-colors').html(htmlContent); // set results
                                $('#extracting-colors-container .spinner').css('visibility', 'hidden'); // turn off spinner
                                $('#extracted-colors').css('display', 'flex'); // show results                                
                            }, 1000);
                        }).catch(err => { console.error(err); });
                    });

                    function extract_colors_from_logo() {
                        var logoImg = $('.user-profile-picture .avatar')[0]; // get the existing img element

                        // create a canvas element
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');

                        return new Promise((resolve, reject) => {

                            // ensure the image is loaded
                            if(!logoImg.complete) 
                                logoImg.onload = function() { // wait until the image is fully loaded
                                    extract_colors();
                                };
                            else extract_colors(); // call immediately

                            function extract_colors() {
                                // set canvas dimensions to the size of the image
                                canvas.width = logoImg.width;
                                canvas.height = logoImg.height;

                                // draw the image onto the canvas
                                ctx.drawImage(logoImg, 0, 0, logoImg.width, logoImg.height);

                                // get the image data from the canvas
                                var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                var data = imageData.data;

                                // track color frequencies and total colors counted
                                var colorMap = new Map();
                                let totalColorsCount = 0;
                                const whiteThreshold = 220; // define threshold for ignoring whites

                                for(var i = 0; i < data.length; i += 4) {
                                    var r = data[i];
                                    var g = data[i + 1];
                                    var b = data[i + 2];

                                    // ignore colors considered close to white
                                    if(r > whiteThreshold && g > whiteThreshold && b > whiteThreshold) continue;

                                    // convert RGB to HEX
                                    var hex = `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase()}`;

                                    // count the colors
                                    colorMap.set(hex, (colorMap.get(hex) || 0) + 1);
                                    totalColorsCount++;
                                }

                                // sort colors by frequency (dominance)
                                const sortedColorList = [...colorMap.entries()]
                                    .sort((a, b) => b[1] - a[1])
                                    .map(([color, count]) => {
                                        // calculate percentage of each color
                                        var percentage = ((count / totalColorsCount) * 100).toFixed(2);
                                        return { color: color, percentage: percentage };
                                    });
                                resolve(sortedColorList); 
                            }
                            logoImg.onerror = function() {
                                reject('Error loading image');
                            };
                        });
                    }
                });
            </script>
        <?php
    }
}


add_Action('wp_footer', 'apply_custom_theme_color_palette');
function apply_custom_theme_color_palette() {

    $vendor_id = null;

    if(strpos($_SERVER['REQUEST_URI'], '/store/') !== false) // the current page is associated with a specific store
        $vendor_id = mvx_find_shop_page_vendor();
    else {
        // TODO: are those pages should be custom as well ? (checkout, cart, etc..)
        // TODO: find another way to get the vendor_id
        /* TEMP */ return;
    }

    // get custom colors from ACF
    $primary_color = get_field('primary_color', 'user_' . $vendor_id);
    $secondary_color = get_field('secondary_color', 'user_' . $vendor_id);
    $tertiary_color = get_field('tertiary_color', 'user_' . $vendor_id);
    // TODO: find a use for tertiary_color or remove it entirely (from ACF as well)

    // ensure that colors are set before outputting CSS
    if($primary_color || $secondary_color) {
        // override root variables
        echo '<style type="text/css">';
        echo ':root {';
        if($primary_color) {
            echo '--color-primary: ' . esc_attr($primary_color) . ';';
        }
        if($secondary_color) {
            echo '--color-secondary: ' . esc_attr($secondary_color) . ';';
        }
        echo '}';
        echo '</style>';
    }
}