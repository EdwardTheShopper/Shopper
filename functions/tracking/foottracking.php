<?php

class Foottracking_Table_List extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'foottracking_item',
            'plural' => 'foottracking_items',
            'ajax' => false,
        ));
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, array(), $sortable);

        global $wpdb;
        $table_name = $wpdb->prefix . 'foottracking'; // Replace with your table name

        $per_page = 20;
        $current_page = $this->get_pagenum();



        $orderby = !empty($_GET['orderby']) ? esc_sql($_GET['orderby']) : 'id';
        $order = !empty($_GET['order']) ? esc_sql($_GET['order']) : 'ASC';


        // Retrieve data from your custom table
        $data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY $orderby $order");

        $total_items = count($data);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
        ));

        // Handle bulk actions
        $this->process_bulk_action();

        $this->items = array_slice($data, (($current_page - 1) * $per_page), $per_page);
    }

    public function get_columns()
    {
        return array(
            'id' => 'ID',
            'visitor_id' => 'Visitor ID',
            'email' => 'Email',
            'rawdata' => 'Raw Data',
            'pagestamp' => 'Page Stamp',
            'location' => 'Location',
            'dateTime' => 'Date and Time',
            'LdateTime' => 'Last Date and Time',
        );
    }

    public function get_sortable_columns()
    {
        return array(
            'id' => array('id', false),
            'visitor_id' => array('visitor_id', false),
            'dateTime' => array('dateTime', false),
        );
    }

    public function column_default($item, $column_name)
    {
        return $item->{$column_name};
    }

}

/**
 * This function add a menu in admin area with the name of Foottracking Table
*/
function add_foottracking_table_menu()
{
    add_menu_page('Foottracking Table', 'Foottracking Table', 'manage_options', 'foottracking-table-page', 'render_foottracking_table_page');
}
add_action('admin_menu', 'add_foottracking_table_menu');
/**
 *
 * render_foottracking_table_page
 * Init the class and prepare the items then display data
 *
*/
function render_foottracking_table_page()
{
    $foottracking_table = new Foottracking_Table_List();
    $foottracking_table->prepare_items();
    $foottracking_table->display();
}

/**
 * Export button for all of the data in the table
 * Need to be added to the class
 * not a big issue just for better codeing
*/


function add_export_all_button()
{
    // Check the current page to make sure the button is displayed on the "Foottracking" page
    $current_screen = get_current_screen();
    if ($current_screen->id === 'toplevel_page_foottracking-table-page') { // Replace with the actual page ID or slug
        echo '<a class="button" href="' . admin_url('admin.php?action=export_all_csv') . '">Export All to CSV</a>';
    }
}

add_action('admin_notices', 'add_export_all_button');

/**
 * export all data from the table
*/
add_action('admin_action_export_all_csv', 'export_all_to_csv');

function export_all_to_csv()
{
    // Query all data from your custom table
    global $wpdb;
    $table_name = $wpdb->prefix . 'foottracking'; // Replace with your table name
    $data = $wpdb->get_results("SELECT * FROM $table_name");

    // Generate the CSV file
    $csv_file = fopen('php://temp', 'w');
    $columns = array('ID', 'Visitor ID', 'Email', 'Raw Data', 'Page Stamp', 'Location', 'Date and Time', 'Last Date and Time');
    fputcsv($csv_file, $columns);

    foreach ($data as $row) {
        $csv_data = array(
            $row->id,
            $row->visitor_id,
            $row->email,
            $row->rawdata,
            $row->pagestamp,
            $row->location,
            $row->dateTime,
            $row->LdateTime,
        );
        fputcsv($csv_file, $csv_data);
    }

    rewind($csv_file);
    $csv_content = stream_get_contents($csv_file);
    fclose($csv_file);

    // Set headers for download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exported_foot_tracking_data.csv"');

    echo $csv_content;
    exit;
}
