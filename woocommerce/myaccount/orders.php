<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

    <div class="order-cards-container">

    <?php foreach ( $customer_orders->orders as $customer_order ) {
        $order = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        $item_count = $order->get_item_count() - $order->get_item_count_refunded();
    ?>
        <div class="order-card">
            <div class="order-card-header">
                <div class="order-number">
                    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                        <?php echo esc_html( _x( '#', 'hash before order number', 'bacola' ) . $order->get_order_number() ); ?>
                    </a>
                </div>
                <div class="order-date">
                    <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
                        <?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
                    </time>
                </div>
            </div>
            <div class="order-card-body">
                <div class="order-status">
                    <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                </div>
                <div class="order-total">
                    <?php
                    /* translators: 1: formatted order total 2: total order items */
                    echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'bacola' ), $order->get_formatted_order_total(), $item_count ) );
                    ?>
                </div>
                <div class="order-actions">
                    <?php
                    $actions = wc_get_account_orders_actions( $order );

                    if ( ! empty( $actions ) ) {
                        foreach ( $actions as $key => $action ) {
                            echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                        }
                    }
                    ?>
                </div>
                <div class="order-items">
                    <?php foreach ( $order->get_items() as $item_id => $item ) { ?>
                        <div class="order-item">
                            <div class="product-thumbnail">
                                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $item->get_product_id() ), 'thumbnail'); ?>
                                <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($item->get_name()); ?>">
                            </div>
                            <div class="product-name">
                                <a href="<?php echo get_permalink($item->get_product_id()); ?>"><?php echo esc_html($item->get_name()); ?></a>
                                <strong class="product-quantity">× <?php echo esc_html($item->get_quantity()); ?></strong>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

    </div>

    <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

    <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
        <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
            <?php if ( 1 !== $current_page ) : ?>
                <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'bacola' ); ?></a>
            <?php endif; ?>

            <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
                <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'bacola' ); ?></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else : ?>
    <?php wc_print_notice( esc_html__( 'עדיין לא בוצעו הזמנות.', 'bacola' ) . ' <a class="woocommerce-Button wc-forward button" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">' . esc_html__( 'עיון במוצרים', 'bacola' ) . '</a>', 'notice' ); ?>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>

<style>
    .order-cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
    }

    .order-card {
        background: #fff;
        border: 1px solid #e1e1e1;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        flex: 1 1 calc(33.333% - 20px);
        display: flex;
        flex-direction: column;
    }

    .order-card-header {
        background: #f7f7f7;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-card-body {
        padding: 15px;
    }

    .order-status,
    .order-total,
    .order-actions {
        margin-bottom: 10px;
    }

    .order-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .product-thumbnail {
        flex-shrink: 0;
        margin-right: 10px;
    }

    .product-thumbnail img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 3px;
    }

    .product-name {
        flex-grow: 1;
    }

    .order-actions a {
        margin-right: 5px;
    }

    @media (max-width: 900px) {
        .order-card {
            flex: 1 1 calc(50% - 20px);
        }
    }

    @media (max-width: 600px) {
        .order-card {
            flex: 1 1 100%;
        }
    }
</style>
