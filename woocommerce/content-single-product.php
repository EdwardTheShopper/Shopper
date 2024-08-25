<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

$vendor_id = get_post_field('post_author', $product->get_id());
$vendor = get_user_meta($vendor_id);
$vendorImage = $vendor['_vendor_profile_image'][0] ?? "";
$vendorBanner = $vendor['_vendor_banner'][0] ?? "";
$phone = $vendor['_vendor_phone'][0] ?? "";
$post_code = $vendor['_vendor_postcode'][0] ?? "";
$vendorNickname = $vendor['nickname'][0] ?? "";


$product_url = get_permalink();

if (isset($product->name)) {
    $name = $product->name;
    $name = str_replace('"', ' ', $name);
} else {
    $name = "";
}

if (isset($vendor['_vendor_description'][0])) {
    $venDescription = $vendor['_vendor_description'][0];
} elseif(isset($vendor['_vendor_message_to_buyers'][0])) {
    $venDescription = $vendor['_vendor_message_to_buyers'][0];

} else {
    $venDescription = "";
}

$venDescription = str_replace('"', ' ', $venDescription);

$sku = $product->sku;

if(strlen($product->short_description))
    $productDesc = str_replace('"', ' ', $product->short_description);
else
    $productDesc = str_replace('"', ' ', $product->description);

$country = $vendor['_vendor_country'][0] ?? "";
$city = $vendor['_vendor_city'][0] ?? "";
$address1 = $vendor['_vendor_address_1'][0] ?? "";
$address2 = $vendor['_vendor_address_2'][0] ?? "";
$lat = $vendor['_store_lat'][0] ?? "";
$lng = $vendor['_store_lng'][0] ?? "";
$vendor_name = $vendor['_vendor_page_title'][0] ?? "";
$insta = $vendor['_vendor_instagram'][0] ?? "";
$fb = $vendor['_vendor_fb_profile'][0] ?? "";
$vendorEmail = $vendor['billing_email'][0] ?? "";

$avg_rating = $product->average_rating == 0 ? "5" : $product->average_rating;
$rev_count = $product->review_count == 0 ? "33" : $product->review_count;


if (isset($vendor['wcmp_vendor_fields'][0])) {
    $extra_fileds = unserialize($vendor['wcmp_vendor_fields'][0]);

    foreach ($extra_fileds as $item) {
        if (isset($item['label']) && $item['label'] === 'שעות פעילות') {
            $openning = $item['value'];
            break; // Break the loop once the label is found
        }
    }
} else {
    $openning = "א׳-ה׳ 09:00-21:00 ו׳ 07:30-15:00";
}

// Check if the product has attributes
if ($product->has_attributes()) {
    $attributes = $product->get_attributes();
    $brand = '';
    foreach ($attributes as $attribute) {
        // Get the attribute name and label
        $attribute_name = $attribute->get_name();
        $attribute_label = wc_attribute_label($attribute_name);

        // Check if the attribute label is "מותגים"
        if ($attribute_label === 'מותגים') {
            // Get the attribute options (terms)
            $attribute_terms = $attribute->get_terms();

            // Output the attribute name (label)

            foreach ($attribute_terms as $term) {
                $brand =  $term->name;
            }
        }
    }
}
if (!isset($brand)) {
    $brand = "";
}


if (function_exists('get_mvx_vendor_settings')) {
    global $post;
    $vendorSettings = get_mvx_product_vendors($post->ID);
    $vendorDisplayname = $vendorSettings->user_data->data->display_name;
    $vendorURL =  $vendorSettings->permalink;
}


/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

$image_column = get_theme_mod('bacola_shop_single_image_column', 5);
$content_column = (12 - $image_column);

?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

<!-- Store Inof header -->
<?php do_shortcode("[store_info_shortcode id=$vendor_id]"); ?>

	<?php do_action('bacola_single_header_top'); ?>


	<div class="product-content">
		<div class="row">
			<div class="col col-12 col-lg-<?php echo esc_attr($image_column); ?> product-images">
				<?php
                /**
                 * Hook: woocommerce_before_single_product_summary.
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
               do_action('woocommerce_before_single_product_summary');
?>
			</div>
			
			<div class="col col-12 col-lg-<?php echo esc_attr($content_column); ?> product-detail">

				<?php do_action('bacola_single_header_side'); ?>

				<div class="column">
					<?php
    /**
     * Hook: woocommerce_single_product_summary.
     *
     * @hooked woocommerce_template_single_title - 5
     * @hooked woocommerce_template_single_rating - 10
     * @hooked woocommerce_template_single_price - 10
     * @hooked woocommerce_template_single_excerpt - 20
     * @hooked woocommerce_template_single_add_to_cart - 30
     * @hooked woocommerce_template_single_meta - 40
     * @hooked woocommerce_template_single_sharing - 50
     * @hooked WC_Structured_Data::generate_product_data() - 60
     */
     do_action('woocommerce_single_product_summary');
?>
				</div>
				
				<?php if(get_theme_mod('bacola_shop_single_featured_toggle', 0) == 1) { ?>
					<?php $featured_title = get_theme_mod('bacola_shop_single_featured_title'); ?>
					<div class="column product-icons">
						<?php if($featured_title) { ?>
							<div class="alert-message"><?php echo esc_html($featured_title); ?></div>
						<?php } ?>
						<div class="icon-messages">
							<ul>
								<?php $featured = get_theme_mod('bacola_single_featured_list'); ?>
								<?php foreach($featured as $f) { ?>
								<li>
									<div class="icon"><i class="<?php echo esc_attr($f['featured_icon']); ?>"></i></div>
									<div class="message"><?php echo esc_html($f['featured_text']); ?></div>
								</li>
								<?php } ?>

							</ul>
						</div>
					</div>
				<?php } ?>
				
			</div>
			
		</div>
	</div>
</div>

	<?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action('woocommerce_after_single_product_summary');

?>


<?php do_action('woocommerce_after_single_product');?>

<!-- Schema.org 2211223 -->
<?php

/**
 *
 * Get image url
 *
*/
// Get the featured image ID
$featured_image_id = get_post_thumbnail_id($product->get_id());

// Get the featured image URL
$featured_image_url = wp_get_attachment_url($featured_image_id);

$categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
foreach ( $categories as $category ) {
    if($category)
        $scemaAcf =  get_field('schema', 'product_cat_' . $category->term_id) ?? 'null';
}

?>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Product",
  "name": "<?php echo $name; ?>",
  "image": "<?php echo $featured_image_url; ?>",
  "description": "<?php echo $productDesc; ?>",
  "sku": "<?php echo $sku; ?>",
  "brand": {
    "@type": "Brand",
    "name": "<?php echo $brand; ?>"
  },
  "offers": {
    "@type": "Offer",
    "priceCurrency": "ILS",
    "price": "<?php echo $product->price; ?>",
    "availability": "http://schema.org/InStock",
    "seller": {
      "@type": "Organization",
      "name": "<?php echo $vendor_name; ?>",
      "image": "<?php echo $vendorImage; ?>",
      "description": "<?php echo strip_tags($venDescription); ?>",
      "telephone": "<?php echo $phone; ?>",
      "url": "<?php echo $vendorURL; ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo $address1 . ' ' . $address2; ?>",
        "addressLocality": "<?php echo $city; ?>",
        "addressRegion": "<?php echo $city; ?>",
        "postalCode": "<?php echo $post_code; ?>",
        "addressCountry": "<?php echo $country; ?>"
      }
    }
  },
  <?php echo $scemaAcf ?>
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo $avg_rating; ?>",
    "reviewCount": "<?php echo $rev_count; ?>"
  },
  "sameAs": [
    "<?php echo $insta; ?>",
    "<?php echo $fb; ?>"
  ]
}
</script>
