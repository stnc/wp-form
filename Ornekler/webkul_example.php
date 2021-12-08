<?php
/*
Plugin Name: WordPress - WP_List_Table - Custom
*/
add_action('admin_menu', 'register_my_custom_menu_page');
function register_my_custom_menu_page()
{

    global $new_menu_page;

    // creating admin menu 

    add_menu_page('Career', 'Career', 'edit_posts', 'career', 'custom_list_page', get_template_directory_uri() . '/images/custom.png', 8);

    $hook = add_submenu_page("career", "Custom List", "Custom List", 'edit_posts', "career", "custom_list_page");

    // adding submenu 

    $test_hook =  add_submenu_page("career", "Custom Detail", "Custom Detail", 'edit_posts', "custom_detail", "custom_detail_page");

    // creating options like per page data(pagination)

    add_action("load-" . $hook, 'add_options');
    add_action("load-" . $test_hook, 'add_options');

    // Creating help tab 

    add_action('current_screen', 'my_admin_add_help_tab');
}
function my_admin_add_help_tab()
{

    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page

    $screen->add_help_tab(array(

        'id'    => 'my_help_tab',

        'title' => 'Webkul Help Tab',

        'content'   => '<p>' . __('Help Topics Added Here.') . '</p>',
    ));
}
function add_options()
{

    $option = 'per_page';

    $args = array(

        'label' => 'Results',

        'default' => 10,

        'option' => 'results_per_page'

    );

    add_screen_option($option, $args);
}


    if (!class_exists('Link_List_Table')) {

        require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    }
    class Link_List_Table extends WP_List_Table
    {



        /**
         * Constructor, we override the parent to pass our own arguments
         * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
         */

        function __construct()
        {

            parent::__construct(array(

                'singular'  => 'singular_name',     //singular name of the listed records

                'plural'    => 'plural_name',    //plural name of the listed records

                'ajax'      => false

            ));
        }


        public function column_default($item, $column_name)

        {

            switch ($column_name) {

                case 'first_column_name':

                case 'second_column_name':

                case 'third_column_name':

                case 'fourth_column_name':

                    return "<strong>" . $item[$column_name] . "</strong>";


                default:

                    return print_r($item, true);
            }
        }
        public function get_sortable_columns()
        {

            $sortable_columns = array(

                'first_column_name'     => array('first_column_name', true),

                'second_column_name' => array('second_column_name', true)
            );

            return $sortable_columns;
        }


        public function get_hidden_columns()

        {
            // Setup Hidden columns and return them
            return array();
        }

        function first_column_name($item)
        {
            $actions = array(

                'edit'      => sprintf('<a href="?page=custom_detail_page&user=%s">Edit</a>', $item['id']),

                'trash'    => sprintf('<a href="?page=custom_list_page&action=trash&user=%s">Trash</a>', $item['id']),

            );

            return sprintf('%1$s %2$s', $item['first_column_name'], $this->row_actions($actions));
        }
        function get_bulk_actions()
        {

            $actions = array(

                'trash'    => 'Move To Trash'
            );

            return $actions;
        }
        public function process_bulk_action()

        {
            global $wpdb;
            if ('trash' === $this->current_action()) {

                if (isset($_GET['user'])) {

                    if (is_array($_GET['user'])) {

                        foreach ($_GET['user'] as $id) {

                            if (!empty($id)) {

                                wp_trash_post($id);

                                $table_name = $wpdb->prefix . 'table_name';

                                $wpdb->query("update $table_name set post_status='trash' WHERE id IN($id)");
                            }
                        }
                    } else {

                        if (!empty($_GET['user'])) {

                            $id = $_GET['user'];

                            wp_trash_post($id);

                            $table_name = $wpdb->prefix . 'table_name';

                            $wpdb->query("update $table_name set post_status='trash' WHERE id =$id");
                        }
                    }
                }
            }
        }


        private function table_data()
        {
            global $wpdb;

            $table_name = $wpdb->prefix . 'table_name';

            $data = array();

            if (isset($_GET['s'])) {

                $search = $_GET['s'];

                $search = trim($search);

                $wk_post = $wpdb->get_results("SELECT c_name_one,c_name_two FROM $table_name WHERE c_name_three LIKE '%$search%' and column_name_four='value'");
            } else {
                $wk_post = $wpdb->get_results("SELECT id,c_name_one,c_name_two FROM $table_name ");
            }

            $field_name_one = array();

            $field_name_two = array();

            $i = 0;

            foreach ($wk_post as $wk_posts) {

                $field_name_one[] = $wk_posts->field_name_one;

                $field_name_two[] = $wk_posts->field_name_two;

                $data[] = array(

                    'first_column_name'  => $field_name_one[$i],

                    'second_column_name' =>   $field_name_two[$i]

                );

                $i++;
            }

            return $data;
        }



	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'     => '<input type="checkbox"/>',
			'c_name_one'  => __( 'Name', 'admin-table-tut' ),
			'c_name_two'   => __( 'Company', 'admin-table-tut' ),
			'c_name_three' => __( 'Phone', 'admin-table-tut' ),

		);
	}
        public function prepare_items()
        {

            global $wpdb;

            $columns = $this->get_columns();

            $sortable = $this->get_sortable_columns();

            $hidden = $this->get_hidden_columns();

            $this->process_bulk_action();

            $data = $this->table_data();

            $totalitems = count($data);

            $user = get_current_user_id();

            $screen = get_current_screen();

            $option = $screen->get_option('per_page', 'option');

            $perpage = get_user_meta($user, $option, true);

            $this->_column_headers = array($columns, $hidden, $sortable);

            if (empty($per_page) || $per_page < 1) {

                $per_page = $screen->get_option('per_page', 'default');
            }


            function usort_reorder($a, $b)
            {

                $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'c_name_one'; //If no sort, default to title

                $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc

                $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order

                return ($order === 'asc') ? $result : -$result; //Send final sort direction to usort

            }

            usort($data, 'usort_reorder');

            $totalpages = ceil($totalitems / $perpage);

            $currentPage = $this->get_pagenum();

            $data = array_slice($data, (($currentPage - 1) * $perpage), $perpage);

            $this->set_pagination_args(array(

                "total_items" => $totalitems,

                "total_pages" => $totalpages,

                "per_page" => $perpage,
            ));

            $this->items = $data;
        }
    }



function custom_list_page(){
    
    //Create an instance of our package class...
    $testListTable = new Link_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
    
    ?>
    <div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h2>List Table Test</h2>
        
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <p>This page demonstrates the use of the <tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt> class in plugins.</p> 
            <p>For a detailed explanation of using the <tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt>
            class in your own plugins, you can view this file <a href="<?php echo admin_url( 'plugin-editor.php?plugin='.plugin_basename(__FILE__) ); ?>" style="text-decoration:none;">in the Plugin Editor</a> or simply open <tt style="color:gray;"><?php echo __FILE__ ?></tt> in the PHP editor of your choice.</p>
            <p>Additional class details are available on the <a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WordPress Codex</a>.</p>
        </div>
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php 
            
            $testListTable->search_box( 'search', 'search_id' );
            $testListTable->display() ?>
        </form>
        
    </div>
    <?php
}

function custom_detail_page()
{
    echo "this is custom detail page.";
}
        
        
        
        //source: https://webkul.com/blog/create-admin-tables-using-wp_list_table-class/