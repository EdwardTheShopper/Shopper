<?php

class Custom_Widget_Product_Categories extends WP_Widget { 

    // Widget Settings
    function __construct() {
        $widget_ops = array('description' => esc_html__('For Main Shop Page.','bacola-core') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'custom_product_categories' );
        parent::__construct( 'custom_product_categories', esc_html__('Custom Product Categories','bacola-core'), $widget_ops, $control_ops );
    }

    // Widget Output
    function widget($args, $instance) {
        if(is_product_category()){
            $term_children = get_term_children( get_queried_object()->term_id, 'product_cat' );

            if($term_children){
                extract($args);
                $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
                $exclude = $instance['exclude'];

                echo $before_widget;

                if($title) {
                    echo $before_title . $title . $after_title;
                }

                echo '<div class="widget-body site-checkbox-lists ">';
                echo '<div class="site-scroll">';
                echo '<ul>';
                foreach($term_children as $child){
                    $childterm = get_term_by( 'id', $child, 'product_cat' );

                    echo '<li>';
                    echo '<a href="'.esc_url(get_term_link( $childterm->slug, 'product_cat' )).'">';
                    echo '<input name="product_cat[]" value="'.esc_attr($childterm->term_id).'" id="'.esc_attr($childterm->name).'" type="checkbox" >';
                    echo '<label><span></span>'.esc_html($childterm->name).'</label>';
                    echo '</a>';
                    echo '<li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '</div>';

                echo $after_widget;
            }
        }

        if(!is_product_category()){
			
            extract($args);
            $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
            $exclude = $instance['exclude'];
            $vendor_id = get_post_field('post_author');

            $vendor = get_mvx_vendor($vendor_id);

            $vendor_products = $vendor->get_products();

            echo $before_widget;

            if($title) {
                echo $before_title . $title . $after_title;
            }

            $vendor_product_ids = wp_list_pluck($vendor_products, 'ID');

            // Get categories for the vendor's products
            $terms = wp_get_object_terms($vendor_product_ids, 'product_cat', array(
                'hide_empty' => true, // Only get categories with products
                'parent'     => 0,
            ));

            if($exclude != 'all'){
                $str = $exclude;
                $arr = explode(',', $str);
                $terms = array_filter($terms, function($term) use ($arr) {
                    return !in_array($term->term_id, $arr);
                });
            }

            echo '<div class="widget-body site-checkbox-lists ">';
            echo '<div class="site-scroll">';
            echo '<ul>';

            foreach ($terms as $term) {
                $term_children = get_terms(array(
                    'taxonomy'   => 'product_cat',
                    'child_of'   => $term->term_id,
                    'hide_empty' => true, // Only get categories with products
                ));
                $checkbox = '';
                if (isset($_GET['filter_cat'])) {
                    if (in_array($term->term_id, explode(',', $_GET['filter_cat']))) {
                        $checkbox = 'checked';
                    }
                }

                echo '<li>';
                echo '<a href="' . esc_url(bacola_get_cat_url($term->term_id)) . '" class="product_cat">';
                echo '<input name="product_cat[]" value="' . esc_attr($term->term_id) . '" id="' . esc_attr($term->name) . '" type="checkbox" ' . esc_attr($checkbox) . '>';
                echo '<label ><span></span>' . esc_html($term->name) . '</label>';
                echo '</a>';
                if ($term_children) {
                    echo '<ul class="children">';

                    foreach ($term_children as $child) {
                        $childterm = get_term_by('name', $child->name, 'product_cat');
                        $ancestor = get_ancestors($childterm->term_id, 'product_cat');

                        $term_third_children = get_terms(array(
                            'taxonomy'   => 'product_cat',
                            'child_of'   => $childterm->term_id,
                            'hide_empty' => true, // Only get categories with products
                        ));

                        $childcheckbox = '';
                        if (isset($_GET['filter_cat'])) {
                            if (in_array($childterm->term_id, explode(',', $_GET['filter_cat']))) {
                                $childcheckbox .= 'checked';
                            }
                        }

                        if ($childterm->parent && (sizeof($term_third_children) > 0)) {
                            echo '<li>';
                            echo '<a href="' . esc_url(bacola_get_cat_url($childterm->term_id)) . '">';
                            echo '<input name="product_cat[]" value="' . esc_attr($childterm->term_id) . '" id="' . esc_attr($childterm->name) . '" type="checkbox" ' . esc_attr($childcheckbox) . '>';
                            echo '<label><span></span>' . esc_html($childterm->name) . '</label>';
                            echo '</a>';
                            if ($term_third_children) {
                                echo '<ul class="children">';
                                foreach ($term_third_children as $third_child) {
                                    $thirdchildterm = get_term_by('name', $third_child->name, 'product_cat');
                                    $thirdchildthumbnail_id = get_term_meta($thirdchildterm->term_id, 'thumbnail_id', true);
                                    $thirdchildimage = wp_get_attachment_url($thirdchildthumbnail_id);

                                    $thirdchildcheckbox = '';
                                    if (isset($_GET['filter_cat'])) {
                                        if (in_array($thirdchildterm->term_id, explode(',', $_GET['filter_cat']))) {
                                            $thirdchildcheckbox .= 'checked';
                                        }
                                    }

                                    echo '<li>';
                                    echo '<a href="' . esc_url(bacola_get_cat_url($thirdchildterm->term_id)) . '">';
                                    echo '<input name="product_cat[]" value="' . esc_attr($thirdchildterm->term_id) . '" id="' . esc_attr($thirdchildterm->name) . '" type="checkbox" ' . esc_attr($thirdchildcheckbox) . '>';
                                    echo '<label><span></span>' . esc_html($thirdchildterm->name) . '</label>';
                                    echo '</a>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                            }

                            echo '</li>';
                        } elseif (sizeof($ancestor) == 1) {
                            echo '<li>';
                            echo '<a href="' . esc_url(bacola_get_cat_url($childterm->term_id)) . '">';
                            echo '<input name="product_cat[]" value="' . esc_attr($childterm->term_id) . '" id="' . esc_attr($childterm->name) . '" type="checkbox" ' . esc_attr($childcheckbox) . '>';
                            echo '<label><span></span>' . esc_html($childterm->name) . '</label>';
                            echo '</a>';
                            echo '</li>';
                        }
                    }
                    echo '</ul>';
                }
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';

            echo $after_widget;
        }
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

// Add Widget
function custom_widget_product_categories_init() {
    register_widget('Custom_Widget_Product_Categories');
}
add_action('widgets_init', 'custom_widget_product_categories_init');
