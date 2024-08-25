<?php
/**
 * footer.php
 * @package WordPress
 * @subpackage Bacola
 * @since Bacola 1.0
 *
 */
?>
			</div><!-- homepage-content -->
		</div><!-- site-content -->
	</main><!-- site-primary -->

	<?php bacola_do_action('bacola_before_main_footer'); ?>

	<?php if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) { ?>
	
		<?php
       /**
       * Hook: bacola_main_footer
       *
       * @hooked bacola_main_footer_function - 10
       */
       do_action('bacola_main_footer');

	    ?>
		
	<?php } ?>
	
	
	<?php bacola_do_action('bacola_after_main_footer'); ?>
	
	<div class="site-overlay"></div>
    <?php

/** 
*   Need to check why this function exists no data used by it
*   if we need to delete it also check footer_tracking.php line 30.
*/ 

   $vendors = get_mvx_vendors($args = array(), $return = 'id');
echo '<ul id="vendors-latlng">';
foreach ($vendors as $vendor_id) {
    $vendor_meta = get_user_meta($vendor_id);
    $store_lat = $vendor_meta['_store_lat'][0] ?? "";
    $store_lng = $vendor_meta['_store_lng'][0] ?? "";
}
echo '</ul>';

?>
	<?php wp_footer(); ?>

<!-- Google Ads tracking code -->
<script>
  window.addEventListener('load', function() {
    jQuery('.add_to_cart_button,.single_add_to_cart_button,[href*="?add-to-cart="]').click(function() {
      gtag('event', 'conversion', {
        'send_to': 'AW-16497851603/8RVrCMDlybgZENP55Lo9'
      });
    });
    document.addEventListener('click', function(e) {
      if (e.target.closest('#place_order')) {
        gtag('event', 'conversion', {
          'send_to': 'AW-16497851603/IDUoCMPlybgZENP55Lo9'
        });
      }
    });
  })
</script>
	</body>
</html>