<?php
/**
 *
 * Vendor storeFibesearch override
 * Remove the false from the if statment from menu_cat in line ~64
 * Also remove .before-shop-loop .shop-view-selector {
 *  margin-left: 20px !important;
 * }
 * From  style-rtl.css
 */

add_action('woocommerce_before_shop_loop', function () {
    echo do_shortcode('[fibosearch layout="flex-icon-on-mobile" class="fibosearch-before-shop-loop "]');
    //echo 'わたしはえでわらです';
    ?>
	<script>
		jQuery('.fibosearch-before-shop-loop').insertAfter('.before-shop-loop .mobile-filter');
		var link = jQuery(location).attr('href');
        console.log(link);
		jQuery('.before-shop-loop .dgwt-wcas-search-form').attr('action', link);
		console.log(jQuery('.before-shop-loop .dgwt-wcas-search-form').attr('action'));
	</script>
	<style>
		.fibosearch-before-shop-loop {
			max-width: 400px;
		}

		.search-product-widget {
			display: none !important;
		}
	</style>
<?php
}, 99);
