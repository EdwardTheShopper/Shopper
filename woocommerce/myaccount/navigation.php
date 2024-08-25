<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>
<div class="row content-wrapper sidebar-right">
	<div class="col-12 col-md-12 col-lg-12 content-primary">
		<div class="my-account-wrapper"> <!-- my-account-wrapper be closed in myaccount.php -->
			<div class="my-account-navigation"> 
				<div class="account-toggle-menu">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><line x1="2.3" y1="12" x2="21.8" y2="12"></line><line x1="2.3" y1="6" x2="21.8" y2="6"></line><line x1="2.3" y1="18" x2="21.8" y2="18"></line></svg>
					<?php esc_html_e('תפריט','bacola'); ?>
				</div><!-- account-toggle-menu -->

				<nav class="woocommerce-MyAccount-navigation">
					<ul>
                        <?php
                            $menu_items = wc_get_account_menu_items();
                            $items_to_remove = array( // those are the unique classes
                                'woocommerce-MyAccount-navigation-link--dashboard',
                                'woocommerce-MyAccount-navigation-link--edit-address',
                                'woocommerce-MyAccount-navigation-link--wishlist'
                            );

                            foreach($menu_items as $endpoint => $label) {
                                $menu_item_classes = wc_get_account_menu_item_classes($endpoint);
                                $found = false;

                                // convert string of classes into an array for easier comparison
                                $menu_item_classes_array = explode(' ', $menu_item_classes);
                                foreach($menu_item_classes_array as $class)
                                    if(in_array($class, $items_to_remove)) {
                                        $found = true;
                                        break;
                                    }

                                if(!$found) {
                                    ?>
                                        <li class="<?php echo esc_attr($menu_item_classes); ?>">
                                            <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
                                                <?php echo esc_html($label); ?>
                                            </a>
                                        </li>
                                    <?php
                                }
                            }
                        ?>
					</ul>
				</nav>
			</div>
<?php do_action( 'woocommerce_after_account_navigation' ); ?>
