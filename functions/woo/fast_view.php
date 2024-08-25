<?php
/*
*
* on image icon for fast viwe
*
*
*/
add_action('wp_ajax_nopriv_quick_view', 'bacola_quick_view_callback_child');
add_action('wp_ajax_quick_view', 'bacola_quick_view_callback_child');
function bacola_quick_view_callback_child()
{

    global $withcomments;
    $withcomments = true;
    $id = intval($_POST['id']);
    $loop = new WP_Query(
        array(
            'post_type' => 'product',
            'p' => $id,
        )
    );

    while ($loop->have_posts()) : $loop->the_post();
        $product = new WC_Product(get_the_ID());

        $rating = wc_get_rating_html($product->get_average_rating());
        $price = $product->get_price_html();
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();
        $product_image_ids = $product->get_gallery_attachment_ids();

        $output = '';

        $output .= '<div class="quickview-product single-content white-popup xxxxxx">';
        $output .= '<div class="quick-product-wrapper">';
        $output .= '<article class="product">';

        $output .= '<div class="product-header bordered">';
        ob_start();
        woocommerce_template_single_title();
        $output .= ob_get_clean();

        $output .= '<div class="product-meta top">';

        $output .= '<div class="product-brand">';
        ob_start();
        wc_display_product_attributes($product);
        $output .= ob_get_clean();
        $output .= '</div><!-- product-brand -->';

        $output .= '<div class="product-rating">';
        ob_start();
        woocommerce_template_single_rating();
        $output .= ob_get_clean();
        $output .= '</div><!-- product-rating -->';


        if ($product->get_sku()) {
            $output .= '<div class="sku-wrapper">';
            $output .= '<span>' . esc_html__('SKU:', 'bacola') . ' </span>';
            $output .= '<span class="sku">' . esc_html($product->get_sku()) . '</span>';
            $output .= '</div>';
        }
        $id = get_the_ID();
        $vendor = get_wcmp_product_vendors($id);
        if ($vendor) {
            $vendor_image = $vendor->get_image();
            $output .= '<div class="vendor-info">';
            //$output .= '<img src="'.$vendor_image.'" class="vendor-logo">';
            $output .= '<span>Store: </span>';
            $output .= '<span class="store-name-quick">' . bacola_sanitize_data(bacola_vendor_name()) . '<span class="store-dist dist-' . $vendor->id . '"></span></span>';
            $output .= '</div>';
        }
        //echo $output;

        $output .= '</div><!-- product-meta -->';

        $output .= '</div><!-- product-header -->';

        $output .= '<div class="product-wrapper">';
        if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {

            $att = get_post_thumbnail_id();
            $image_src = wp_get_attachment_image_src($att, 'full');
            $image_src = $image_src[0];

            $output .= '<div class="product-thumbnails">';
            $output .= '<div class="woocommerce-product-gallery">';
            $output .= bacola_sale_percentage();

            $output .= '<div class="slider-wrapper">';
            $output .= '<svg class="preloader" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
            $output .= '<figure id="images" class="woocommerce-product-gallery__wrapper site-slider" data-slideshow="1" data-arrows="false" data-asnav="#thumbnails" data-slidespeed="1200">';

            $output .= '<a href="#"><img src="' . esc_url($image_src) . '"></a>';
            foreach ($product_image_ids as $product_image_id) {
                $image_url = wp_get_attachment_url($product_image_id);
                $output .= '<a href="#0"><img src="' . esc_url($image_url) . '" alt="bacola"></a>';
            }

            $output .= '</figure>';
            $output .= '<div id="thumbnails" class="product-thumbnails site-slider" data-slideshow="6" data-focusselect="true" data-asnav="#images" data-slidespeed="1200">';

            if ($product_image_ids) {
                $output .= '<div class="product-thumbnail"><img src="' . esc_url($image_src) . '"></div>';
                foreach ($product_image_ids as $product_image_id) {
                    $image_url = wp_get_attachment_url($product_image_id);
                    $output .= '<div class="product-thumbnail"><img src="' . esc_url($image_url) . '"></div>';
                }
            }

            $output .= '</div><!-- product-thumbnails -->';
            $output .= '</div><!-- slider-wrapper -->';

            $output .= '</div><!-- woocommerce-product-gallery -->';
            $output .= '</div><!-- product-thumbnails -->';
        }
        $output .= '<div class="product-detail sd-product-detail">';
        ob_start();
        woocommerce_template_single_price();
        $output .= ob_get_clean();

        $output .= '<div class="product-short-description">';
        $output .= '<p>' . get_the_excerpt() . '</p>';
        $output .= '</div><!-- product-short-description -->';
        ob_start();
        woocommerce_template_single_add_to_cart();

        $output .= ob_get_clean();

        ob_start();
        do_action('quickview-wishlist-compare');
        $output .= ob_get_clean();

        $type = get_post_meta($id, 'klb_product_type', true);
        $year = get_post_meta($id, 'klb_product_mfg', true);
        $life = get_post_meta($id, 'klb_product_life', true);

        if ($type || $year || $life) {
            $output .= '<div class="product-checklist">';
            $output .= '<ul>';
            if ($type) {
                $output .= '<li>' . esc_html__('Type:', 'bacola') . ' ' . esc_html($type) . '</li>';
            }
            if ($year) {
                $output .= '<li>' . esc_html__('MFG:', 'bacola') . ' ' . esc_html($year) . '</li>';
            }
            if ($life) {
                $output .= '<li>' . esc_html__('LIFE:', 'bacola') . ' ' . esc_html($life) . '</li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
        }

        ob_start();
        woocommerce_template_single_meta();
        $output .= ob_get_clean();
        $output .= '</div><!-- product-details -->';
        $output .= '</div><!-- product-wrapper -->';
        $output .= '<div>';
        ob_start();
        do_action('woocommerce_after_single_product_summary');
        $output .= ob_get_clean();
        $output .= '</div>';
        $output .= '</article><!-- product -->';
        $output .= '</div><!-- quick-product-wrapper -->';
        $output .= '</div>';


    endwhile;
    wp_reset_postdata();

    $output_escaped = $output;
    echo $output_escaped;

    wp_die();
}
