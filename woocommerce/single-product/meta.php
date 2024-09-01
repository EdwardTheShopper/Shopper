<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
global $product;
?>
<div class="product_meta product-meta bottom">

	<?php do_action('woocommerce_product_meta_start'); ?>

	<?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>

		<span class="sku_wrapper"><?php esc_html_e('SKU:', 'bacola'); ?> <span class="sku"><?php echo (bacola_sanitize_data($sku = $product->get_sku())) ? $sku : esc_html__('N/A', 'bacola'); ?></span></span>

	<?php endif; ?>
	
	<?php
		$url = get_vendor_slug(get_vendor_id()) . "?filter_cat=";
		$product_id = $product->get_id();
		$categories = wp_get_post_terms( $product_id, 'product_cat' );
		
		if(!empty($categories) && !is_wp_error($categories)) {
			echo '<div class="posted_in" style="white-space: nowrap;">קטגוריה: ';
			$links = [];
			foreach($categories as $category)
				$links[] = '<a href="' . esc_url($url . $category->term_id) .'">' . esc_html($category->name) . '</a>';
		
			echo implode(', ', $links);
			echo '</div>';
		}
	?>

	<?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('תגית:', 'תגיות:', count($product->get_tag_ids()), 'bacola') . ' ', '</span>'); ?>

	<?php do_action('woocommerce_product_meta_end'); ?>

</div>

