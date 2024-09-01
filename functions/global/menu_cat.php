<?php
add_action('woocommerce_before_shop_loop', 'bacola_catalog_ordering_start_child', 30);
function bacola_catalog_ordering_start_child()
{
    ?>
	<!-- Added for SS-41 -->
	<div class="before-shop-loop">
		<div class="shop-view-selector">
			<?php if (get_theme_mod('bacola_grid_list_view', '0') == '1') { ?>

				<?php if (bacola_shop_view() == 'list_view') { ?>
					<a title="<?php esc_html_e('תצוגת רשימה', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg('shop_view', 'list_view')); ?>" class="shop-view active">
						<i class="klbth-icon-list-grid"></i>
					</a>
					<a title="<?php esc_html_e('תצוגת רשת 2 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '2', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
						<i class="klbth-icon-2-grid"></i>
					</a>
					<a title="<?php esc_html_e('תצוגת רשת 3 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
						<i class="klbth-icon-3-grid"></i>
					</a>
					<a title="<?php esc_html_e('תצוגת רשת 4 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
						<i class="klbth-icon-4-grid"></i>
					</a>
				<?php } else { ?>
					<a title="<?php esc_html_e('תצוגת רשימה', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg('shop_view', 'list_view')); ?>" class="shop-view">
						<i class="klbth-icon-list-grid"></i>
					</a>
					<?php if (bacola_get_column_option() == 2) { ?>
						<a title="<?php esc_html_e('תצוגת רשת 2 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '2', 'shop_view' => 'grid_view'))); ?>" class="shop-view active">
							<i class="klbth-icon-2-grid"></i>
						</a>
						<a title="<?php esc_html_e('תצוגת רשת 3 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
							<i class="klbth-icon-3-grid"></i>
						</a>
						<a title="<?php esc_html_e('תצוגת רשת 4 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
							<i class="klbth-icon-4-grid"></i>
						</a>
					<?php } elseif (bacola_get_column_option() == 3) { ?>
						<a title="<?php esc_html_e('תצוגת רשת 2 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '2', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
							<i class="klbth-icon-2-grid"></i>
						</a>
						<a title="<?php esc_html_e('תצוגת רשת 3 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view active">
							<i class="klbth-icon-3-grid"></i>
						</a>
						<a title="<?php esc_html_e('תצוגת רשת 4 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
							<i class="klbth-icon-4-grid"></i>
						</a>
					<?php } else { ?>
						<a title="<?php esc_html_e('תצוגת רשת 2 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '2', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
							<i class="klbth-icon-2-grid"></i>
						</a>
						<a title="<?php esc_html_e('תצוגת רשת 3 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
							<i class="klbth-icon-3-grid"></i>
						</a>
						<a title="<?php esc_html_e('תצוגת רשת 4 עמודות', 'bacola-core'); ?>" href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view active">
							<i class="klbth-icon-4-grid"></i>
						</a>
					<?php } ?>

				<?php } ?>
			<?php } ?>
		</div>
		<?php
        // Remove false to return the search also need to un comment the include_once('functions/global/search-form-before-shop.php'); from the functions.php
            if (is_tax('dc_vendor_shop') && false) {
                ?>
			<div class="search-product-widget">
				<form role="search" method="get" class="wcmp-vproduct-search woocommerce-product-search" action="">
					<label class="screen-reader-text" for="woocommerce-product-search-field-0">Search for:</label>
					<input type="search" id="woocommerce-product-search-field-0" class="search-field" placeholder="Search products…" value="" name="s">
					<button type="submit" value="Search">Search</button>
					<input type="hidden" name="post_type" value="product">
				</form>
			</div>
		<?php
            }
    ?>
			<div class="mobile-filter">
				<a href="#" class="filter-toggle" title="<?php esc_html_e('קטגוריות', 'bacola-core'); ?>">
					<i class="klbth-icon-filter"></i>
					<span><?php esc_html_e('קטגוריות', 'bacola-core'); ?></span>
				</a>
			</div>
			<!-- For get orderby from loop -->
			<?php do_action('klb_catalog_ordering'); ?>


			<!-- For perpage option-->
			<?php if (get_theme_mod('bacola_perpage_view', '0') == '1') { ?>
				<?php $perpage = isset($_GET['perpage']) ? $_GET['perpage'] : ''; ?>
				<?php $defaultperpage = wc_get_default_products_per_row() * wc_get_default_product_rows_per_page(); ?>
				<?php $options = array($defaultperpage, $defaultperpage * 2, $defaultperpage * 3, $defaultperpage * 4); ?>
				<form class="products-per-page product-filter" method="get">
					<span class="perpage-label"><?php esc_html_e('Show', 'bacola-core'); ?></span>
					<?php if (bacola_get_body_class('bacola-ajax-shop-on')) { ?>
						<select name="perpage" class="perpage filterSelect" data-class="select-filter-perpage">
						<?php } else { ?>
							<select name="perpage" class="perpage filterSelect" data-class="select-filter-perpage" onchange="this.form.submit()">
							<?php } ?>
							<?php for ($i = 0; $i < count($options); $i++) { ?>
								<option value="<?php echo esc_attr($options[$i]); ?>" <?php echo esc_attr($perpage == $options[$i] ? 'selected="selected"' : ''); ?>><?php echo esc_html($options[$i]); ?></option>
							<?php } ?>

							</select>
							<?php wc_query_string_form_fields(null, array('perpage', 'submit', 'paged', 'product-page')); ?>
				</form>
			<?php } ?>
	</div>
	<!-- End -->

	<?php
    /**
     * added by saj
     * custom fucntion
     * Retrieves category  based on category ID.
     *
     * @since 0.71
     *
     * @param int $cat_id Category ID.
     * @return string|WP_Error Category name on success, WP_Error on failure.
     */

    function get_the_category_Data_by_ID($cat_id)
    { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
        $cat_id   = (int) $cat_id;
        $category = get_term($cat_id);

        if (is_wp_error($category)) {
            return $category;
        }

        return ($category) ? $category : '';
    }
    ?>

	<!-- category menu starts here -->
	<?php

    $sidebarmenu = get_theme_mod('bacola_header_sidebar', '0'); ?>

	<?php if ($sidebarmenu == '1') { ?>
        
		<style>
			.my-manueliststore {
				position: relative !important;
				width: 32% !important;
			}

			span.text>span {
				text-transform: uppercase;
			}

			.addSidebar {
				-webkit-transition: all .15s linear;
				-moz-transition: all .15s linear;
				-o-transition: all .15s linear;
				transform: translate(0px, 0px);
			}
		</style>

		<div class="all-categoriess locked" style="display: none;">
			<div class="anckrDv" style="background-color: #2bbef9;padding: 15px;border-radius: 5px;margin-bottom:15px">

				<i class="klbth-icon-menu-thin" style="padding:20px;color:white;cursor:pointer"></i>
				<span class="text" style="color:white;font-size:.8125rem;cursor:pointer">&nbsp; <span class=""><?php  _e('Categories', 'bacola');?>
            </span> &nbsp;<i class="fa fa-angle-down" style="color:white"></i></span>

			</div>
			<?php $menu_collapse = is_front_page() && !get_theme_mod('bacola_header_sidebar_collapse') ? 'show' : ''; ?>
			<div class="dropdown-categoriess collapsess <?php echo esc_attr($menu_collapse);?>" id="all-categoriestore" style="width: 100%; position: relative; display: none;"></div>
		</div>
		<script>
			jQuery('.all-categoriess').on('click', function() {
                console.log('menu clicked');
				jQuery('#sidebar').css('transform', 'translate(0px, 0px)');
			})
		</script>
        

	<?php

        // <!-- category menu by Saj Starts here -->

        global $wpdb;
	} ?>

	<?php wp_enqueue_style('klb-remove-filter'); ?>
<?php

}
