<?php


/** 
 * Override Bacola Sidebar Menu
 * Author: Edward Ziadeh
 * Created: 10 Mar, 2024
*/
class bacola_sidebar_walker extends Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        // depth dependent classes
        $indent = ($depth > 0 ? str_repeat("\t", $depth) : ''); // code indent
        $display_depth = ($depth + 1); // because it counts the first submenu as 0
        $classes = array(
            '',
            ($display_depth % 21 ? '' : ''),
            ($display_depth >= 2 ? '' : ''),

            );
        $class_names = implode(' ', $classes);

        // build html
        $output .= "\n" . $indent . '<ul class="sub-menu">' . "\n";
    }

    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        $id_field = $this->db_fields['id'];
        if (is_object($args[0])) {
            $args[0]->has_children = ! empty($children_elements[$element->$id_field]);
        }
        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
    public function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0)
    {

        global $wp_query;

        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $class_names = $value = '';

        $classes = empty($object->classes) ? array() : (array) $object->classes;
        $myclasses = empty($object->classes) ? array() : (array) $object->classes;
        $icon_class = $classes[0];
        $classes = array_slice($classes, 1);



        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $object));

        if ($args->has_children) {
            $class_names = 'class="category-parent parent  '. esc_attr($class_names) . '"';
        } elseif(in_array('bottom', $myclasses)) {
            $class_names = 'class="link-parent  '. esc_attr($class_names) . '"';
        } else {
            $class_names = 'class="category-parent  '. esc_attr($class_names) . '"';
        }

        $output .= $indent . '<li ' . $value . $class_names .'>';

        $datahover = str_replace(' ', '', $object->title);


        $attributes = ! empty($object->url) ? ' href="'   . esc_attr($object->url) .'"' : '';


        $object_output = $args->before;

        $object_output .= '<a'. $attributes .'  >';
        if($icon_class) {
            $object_output .= '<i class="'.esc_attr($icon_class).'"></i> ';
        }
        $object_output .= $args->link_before .  apply_filters('the_title', $object->title, $object->ID) . '';
        $object_output .= $args->link_after;
        $object_output .= '</a>';


        $object_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $object_output, $object, $depth, $args);
    }
}
