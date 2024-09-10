<?php

function recursive_render_categories($instance, $vendor_product_ids, $parent_id) {
    // get all categories that are associated with the vendor's products (only direct children of $parent_id)
    $terms = wp_get_object_terms($vendor_product_ids, 'product_cat', array(
        'hide_empty' => true, // only get categories with products
        'parent'     => $parent_id,
    ));

    // filter
    if($instance['exclude'] != 'all') {
        $arr = explode(',', $instance['exclude']);
        $terms = array_filter($terms, function($term) use ($arr) {
            return !in_array($term->term_id, $arr);
        });
    }
    if($terms) {
        $base_url = get_option('baseUrl');
        $full_url = substr($base_url, 0, strlen($base_url)-1) . get_vendor_slug(get_vendor_id());

        echo '<ul' . ($parent_id? ' class="children" ' : '') . '>';
        foreach($terms as $term) {
            // constract link:
            $category_string = bacola_get_cat_url($term->term_id);
            $position = strpos($category_string, '?');
            $category_string = $position !== false ? // '?' symbol was found ?
                substr($category_string, $position): ''; // adding category : removing category
            //////////////////
            $checkbox = isset($_GET['filter_cat']) && in_array($term->term_id, explode(',', $_GET['filter_cat'])) ? 'checked' : '';

            echo '<li>
                    <a href="' . esc_url($full_url . $category_string) . '" class="product_cat">
                        <input name="product_cat[]" value="' . esc_attr($term->term_id) . '" id="' . esc_attr($term->name) . '" type="checkbox" ' . esc_attr($checkbox) . '>
                        <label ><span></span>' . esc_html($term->name) . '</label>
                    </a>'
            ;

            recursive_render_categories($instance, $vendor_product_ids, $term->term_id);
            echo '</li>';
        }
        echo '</ul>';
    }
}

class Custom_Widget_Product_Categories extends WP_Widget { 

    // Widget Settings
    function __construct() {
        $widget_ops = array('description' => esc_html__('For Main Shop Page.','bacola-core') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'custom_product_categories' );
        parent::__construct( 'custom_product_categories', esc_html__('Custom Product Categories','bacola-core'), $widget_ops, $control_ops );
    }

    // Widget Output
    function widget($args, $instance) {
        extract($args);
        $vendor = get_mvx_vendor(get_vendor_id());
        $vendor_product_ids = wp_list_pluck($vendor->get_products(), 'ID');
    
        echo $before_widget;
        echo $before_title . apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance) . $after_title;
    
        echo '<div class="widget-body site-checkbox-lists ">';
        echo '<div class="site-scroll">';
    
        recursive_render_categories($instance, $vendor_product_ids, 0);
    
        echo '</div></div>';
        echo $after_widget;
    }

    // Update
    function update( $new_instance, $old_instance ) {  
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['exclude'] = strip_tags($new_instance['exclude']);

        return $instance;
    }

    // Backend Form
    function form($instance) {

        $defaults = array('title' => 'Product Categories', 'exclude' => 'All');
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','bacola-core'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php esc_html_e('Exclude id:','bacola-core'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo $instance['exclude']; ?>" />
        </p>

        <?php
    }
}