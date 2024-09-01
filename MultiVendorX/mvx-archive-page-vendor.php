<?php

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: mvx_before_main_content.
 *
 */

do_action('mvx_before_main_content');

global $mvx, $wpdb;

?>

<div class="container vendor-store">
	<?php woocommerce_breadcrumb(); ?>	
<?php
/**
* Author: Edward Ziadeh
* All of these variables are used for the schema org
* Getting the data form user meta by calling mvx_find_shop_page_vendor function
* For opeening hour I used wcmp_vendor_fields and convert it from text to array so I can the data by searching for the label
*/
$vendor = (get_user_meta(mvx_find_shop_page_vendor(), '', true)) ?? "";
$vendor_id = mvx_find_shop_page_vendor();
$sql = "
    SELECT nqq_posts.*
    FROM $wpdb->posts
    WHERE $wpdb->posts.post_author = $vendor_id
    AND $wpdb->posts.post_type = 'wpseo_locations'
    AND $wpdb->posts.post_status = 'publish'
";

$results = $wpdb->get_results($sql);
$locationId = $results[0]->ID;
$postmeta = get_post_meta($locationId);
$postmeta_array = array();


foreach ($postmeta as $key => $value) {
    $postmeta_array[$key] = $value;
}

$image = $vendor['_vendor_profile_image'][0] ?? "";
$name = $vendor['_vendor_page_title'][0] ?? "";
$name = str_replace('"', ' ', $name);
$phone = $vendor['_vendor_phone'][0] ?? "";
$post_code = $vendor['_vendor_postcode'][0] ?? "";
/*  echo '<pre>';
print_r($postmeta_array);
echo '</pre>';  */
$description = $vendor['_vendor_description'][0] ? $vendor['_vendor_description'][0] : $vendor['_vendor_message_to_buyers'][0];
$description = str_replace('"', ' ', $description);
$country = $vendor['_vendor_country'][0] ?? "";
$city = $vendor['_vendor_city'][0] ?? "";
$address1 = $vendor['_vendor_address_1'][0] ?? "";
$address2 = $vendor['_vendor_address_2'][0] ?? "";
$fb = $vendor['_vendor_fb_profile'][0] ?? "";
$insta = $vendor['_vendor_instagram'][0] ?? "";
$lat = $postmeta_array['_wpseo_coordinates_lat'][0] ?? "";
$lng = $postmeta_array['_wpseo_coordinates_long'][0] ?? "";

$openning_hours = [
  'Monday' =>     '"Mo ' . $postmeta_array["_wpseo_opening_hours_monday_from"][0] . '-' . $postmeta_array['_wpseo_opening_hours_monday_to'][0] . '", ',
  'Tuesday' =>     '"Tu ' . $postmeta_array["_wpseo_opening_hours_tuesday_from"][0] . '-' . $postmeta_array['_wpseo_opening_hours_tuesday_to'][0] . '", ',
  'Wednesday' =>     '"We ' . $postmeta_array["_wpseo_opening_hours_wednesday_from"][0] . '-' . $postmeta_array['_wpseo_opening_hours_wednesday_to'][0] . '", ',
  'Thursday' =>     '"Th ' . $postmeta_array["_wpseo_opening_hours_thursday_from"][0] . '-' . $postmeta_array['_wpseo_opening_hours_thursday_to'][0] . '", ',
  'Friday' =>     '"Fr ' . $postmeta_array["_wpseo_opening_hours_friday_from"][0] . '-' . $postmeta_array['_wpseo_opening_hours_friday_to'][0] . '", ',
  'Saturday' =>     '"Sa ' . $postmeta_array["_wpseo_opening_hours_saturday_from"][0] . '-' . $postmeta_array['_wpseo_opening_hours_saturday_to'][0] . '", ',
  'Sunday' =>     '"Su ' . $postmeta_array["_wpseo_opening_hours_sunday_from"][0] . '-' . $postmeta_array['_wpseo_opening_hours_sunday_to'][0] . '"'
  ];

?>

	<div class="row content-wrapper sidebar-left">
		<div class="col-12 col-md-12 col-lg-9 content-primary">
			<header class="woocommerce-products-header">
				<?php if (apply_filters('mvx_show_page_title', true)) : ?>
					<div class="woocommerce-products-header__title page-title"><?php is_tax($mvx->taxonomy->taxonomy_name) ? woocommerce_page_title() : print(get_user_meta(mvx_find_shop_page_vendor(), '_vendor_page_title', true)); ?></div>
				<?php endif; ?>
				<?php


                /**
                 * Hook: mvx_archive_description.
                 *
                 */
                 do_action('mvx_archive_description');
        ?>
			</header>

			<?php

            /**
             * Hook: mvx_store_tab_contents.
             *
             * Output mvx store widget
             */

            do_action('mvx_store_tab_widget_contents');

?>
	</div>
		<div id="sidebar" class="col-12 col-md-3 col-lg-3 content-secondary site-sidebar">

			<div class="site-scroll">
				<div class="sidebar-inner">

					<div class="sidebar-mobile-header">
						<!-- <h3 class="entry-title"><?php # esc_html_e('               '/*'Filter Products'*/, 'bacola'); ?></h3> -->
            <!-- <button id="show_products" type="primary">הצג מוצרים</button> -->
            <a href="<?php echo get_vendor_slug($vendor_id); ?>">
              <button id="reset_filters" type="primary">איפוס בחירה</button>
            </a>
						<div class="close-sidebar">
							<i class="klbth-icon-x"></i>
						</div>
					</div>

          <?php if (is_active_sidebar('shop-sidebar')) { ?>
          <?php dynamic_sidebar('shop-sidebar'); ?>
          <!-- custom Hook to get catgories by vendor -->
           
					<?php do_action('display_vendor_categories', get_vendor_id()); ?>
          <div id='insert_canvas_links_here'>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- Schema.org 221122 -->

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "LocalBusiness",
  "name": "<?php  echo $name; ?>",
  "image": "<?php echo $image; ?>",
  "description": "<?php echo strip_tags($description); ?>",
  "telephone": "<?php echo $phone; ?>",
  "url": "<?php echo get_page_link(); ?>",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "<?php echo $address1 . ' ' . $address2; ?>",
    "addressLocality": "<?php echo $city; ?>",
    "addressRegion": "<?php echo $city; ?>",
    "postalCode": "<?php echo $post_code; ?>",
    "addressCountry": "<?php echo $country; ?>"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "<?php echo $lat; ?>",
    "longitude": "<?php echo $lng; ?>"
  },
  "openingHours": [<?php
      foreach($openning_hours as $day => $openning) {
          echo $openning;
      }
?>],
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.5",
    "reviewCount": "100"
  },
  "sameAs": [
    "<?php  echo $insta; ?>",
    "<?php  echo $fb; ?>"
  ]
}

</script>

<?php

    /**
     * Hook: mvx_after_main_content.
     *
     */
    do_action('mvx_after_main_content');

/**
 * Hook: mvx_sidebar.
 *
 */
// deprecated since version 3.0.0 with no alternative available
// do_action( 'mvx_sidebar' );

get_footer('shop');