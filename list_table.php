<?php

//https://wordpress.stackexchange.com/questions/56805/saving-frontend-form-data-in-wordpress

//https://wpshout.com/wordpress-submit-posts-from-frontend/

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}
/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary. In this tutorial, we are
 * going to use the WP_List_Table class directly from WordPress core.
 *
 * IMPORTANT:
 * Please note that the WP_List_Table class technically isn't an official API,
 * and it could change at some point in the distant future. Should that happen,
 * I will update this plugin with the most current techniques for your reference
 * immediately.
 *
 * If you are really worried about future compatibility, you can make a copy of
 * the WP_List_Table class (file path is shown just below) to use and distribute
 * with your plugins. If you do that, just remember to change the name of the
 * class to avoid conflicts with core.
 *
 * Since I will be keeping this tutorial up-to-date for the foreseeable future,
 * I am going to work with the copy of the class provided in WordPress core.
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}




/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 * 
 * Our theme for this list table is going to be movies.
 */
class TT_Example_List_Table extends WP_List_Table {
   
/**
	 * Const to declare number of posts to show per page in the table.
	 */
	const POSTS_PER_PAGE = 10;

	/**
	 * Property to store post types
	 *
	 * @var  array Array of post types
	 */
	private $allowed_post_types;

	/**
	 * Draft_List_Table constructor.
	 */
	public function __construct() {

		parent::__construct(
			array(
				'singular' => 'Draft',
				'plural'   => 'Drafts',
				'ajax'     => false,
			)
		);

		$this->allowed_post_types = $this->allowed_post_types();

	}

	/**
	 * Retrieve post types to be shown in the table.
	 *
	 * @return array Allowed post types in an array.
	 */
	private function allowed_post_types() {
		$post_types = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );

		return $post_types;
	}

	/**
	 * Convert slug string to human readable.
	 *
	 * @param string $title String to transform human readable.
	 *
	 * @return string Human readable of the input string.
	 */
	private function human_readable( $title ) {
		return ucwords( str_replace( '_', ' ', $title ) );
	}

	/**
	 * A map method return all allowed post types to human readable format.
	 *
	 * @return array Array of allowed post types in human readable format.
	 */
	private function allowed_post_types_readable() {
		$formatted = array_map(
			array( $this, 'human_readable' ),
			$this->allowed_post_types
		);

		return $formatted;
	}

	/**
	 * Return instances post object.
	 *
	 * @return WP_Query Custom query object with passed arguments.
	 */
	protected function get_posts_object() {
		$post_types = $this->allowed_post_types;

		$post_args = array(
			'post_type'      => $post_types,
			'post_status'    => array( 'draft' ),
			'posts_per_page' => self::POSTS_PER_PAGE,
		);

		$paged = filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT );

		if ( $paged ) {
			$post_args['paged'] = $paged;
		}

		$post_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );

		if ( $post_type ) {
			$post_args['post_type'] = $post_type;
		}

		$orderby = sanitize_sql_orderby( filter_input( INPUT_GET, 'orderby' ) );
		$order   = esc_sql( filter_input( INPUT_GET, 'order' ) );

		if ( empty( $orderby ) ) {
			$orderby = 'date';
		}

		if ( empty( $order ) ) {
			$order = 'DESC';
		}

		$post_args['orderby'] = $orderby;
		$post_args['order']   = $order;

		$search = esc_sql( filter_input( INPUT_GET, 's' ) );
		if ( ! empty( $search ) ) {
			$post_args['s'] = $search;
		}

		return new \WP_Query( $post_args );
	}

	/**
	 * Display text for when there are no items.
	 */
	public function no_items() {
		esc_html_e( 'No posts found.', 'admin-table-tut' );
	}

	/**
	 * The Default columns
	 *
	 * @param  array  $item        The Item being displayed.
	 * @param  string $column_name The column we're currently in.
	 * @return string              The Content to display
	 */
	public function column_default( $item, $column_name ) {
		$result = '';
		switch ( $column_name ) {
			case 'date':
				$t_time    = get_the_time( 'Y/m/d g:i:s a', $item['id'] );
				$time      = get_post_timestamp( $item['id'] );
				$time_diff = time() - $time;

				if ( $time && $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
					/* translators: %s: Human-readable time difference. */
					$h_time = sprintf( __( '%s ago', 'admin-table-tut' ), human_time_diff( $time ) );
				} else {
					$h_time = get_the_time( 'Y/m/d', $item['id'] );
				}

				$result = '<span title="' . $t_time . '">' . apply_filters( 'post_date_column_time', $h_time, $item['id'], 'date', 'list' ) . '</span>';
				break;

			case 'author':
				$result = $item['author'];
				break;

			case 'type':
				$result = $item['type'];
				break;
		}

		return $result;
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'     => '<input type="checkbox"/>',
			'title'  => __( 'Title', 'admin-table-tut' ),
			'type'   => __( 'Type', 'admin-table-tut' ),
			'author' => __( 'Author', 'admin-table-tut' ),
			'date'   => __( 'Date', 'admin-table-tut' ),
		);
	}

	/**
	 * Return title column.
	 *
	 * @param  array $item Item data.
	 * @return string
	 */
	public function column_title( $item ) {
		$edit_url    = get_edit_post_link( $item['id'] );
		$post_link   = get_permalink( $item['id'] );
		$delete_link = get_delete_post_link( $item['id'] );

		$output = '<strong>';

		/* translators: %s: Post Title */
		$output .= '<a class="row-title" href="' . esc_url( $edit_url ) . '" aria-label="' . sprintf( __( '%s (Edit)', 'admin-table-tut' ), $item['title'] ) . '">' . esc_html( $item['title'] ) . '</a>';
		$output .= _post_states( get_post( $item['id'] ), false );
		$output .= '</strong>';

		// Get actions.
		$actions = array(
			'edit'  => '<a href="' . esc_url( $edit_url ) . '">' . __( 'Edit', 'admin-table-tut' ) . '</a>',
			'trash' => '<a href="' . esc_url( $delete_link ) . '" class="submitdelete">' . __( 'Trash', 'admin-table-tut' ) . '</a>',
			'view'  => '<a href="' . esc_url( $post_link ) . '">' . __( 'View', 'admin-table-tut' ) . '</a>',
		);

		$row_actions = array();

		foreach ( $actions as $action => $link ) {
			$row_actions[] = '<span class="' . esc_attr( $action ) . '">' . $link . '</span>';
		}

		$output .= '<div class="row-actions">' . implode( ' | ', $row_actions ) . '</div>';

		return $output;
	}

	/**
	 * Column cb.
	 *
	 * @param  array $item Item data.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s_id[]" value="%2$s" />',
			esc_attr( $this->_args['singular'] ),
			esc_attr( $item['id'] )
		);
	}

	/**
	 * Prepare the data for the WP List Table
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$sortable              = $this->get_sortable_columns();
		$hidden                = array();
		$primary               = 'title';
		$this->_column_headers = array( $columns, $hidden, $sortable, $primary );
		$data                  = array();

		$this->process_bulk_action();

		$get_posts_obj = $this->get_posts_object();

		if ( $get_posts_obj->have_posts() ) {

			while ( $get_posts_obj->have_posts() ) {

				$get_posts_obj->the_post();

				$data[ get_the_ID() ] = array(
					'id'     => get_the_ID(),
					'title'  => get_the_title(),
					'type'   => ucwords( get_post_type_object( get_post_type() )->labels->singular_name ),
					'date'   => get_post_datetime(),
					'author' => get_the_author(),
				);
			}
			wp_reset_postdata();
		}

		$this->items = $data;
//         echo "<pre>";
// print_r( $data);
		$this->set_pagination_args(
			array(
				'total_items' => $get_posts_obj->found_posts,
				'per_page'    => $get_posts_obj->post_count,
				'total_pages' => $get_posts_obj->max_num_pages,
			)
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
			'trash' => __( 'Move to Trash', 'admin-table-tut' ),
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( 'trash' === $this->current_action() ) {
			$post_ids = filter_input( INPUT_GET, 'draft_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( is_array( $post_ids ) ) {
				$post_ids = array_map( 'intval', $post_ids );

				if ( count( $post_ids ) ) {
					array_map( 'wp_trash_post', $post_ids );
				}
			}
		}
	}

	/**
	 * Generates the table navigation above or below the table
	 *
	 * @param string $which Position of the navigation, either top or bottom.
	 *
	 * @return void
	 */
	protected function display_tablenav( $which ) {
		?>
	<div class="tablenav <?php echo esc_attr( $which ); ?>">

		<?php if ( $this->has_items() ) : ?>
		<div class="alignleft actions bulkactions">
			<?php $this->bulk_actions( $which ); ?>
		</div>
			<?php
		endif;
		$this->extra_tablenav( $which );
		$this->pagination( $which );
		?>

		<br class="clear" />
	</div>
		<?php
	}

	/**
	 * Overriden method to add dropdown filters column type.
	 *
	 * @param string $which Position of the navigation, either top or bottom.
	 *
	 * @return void
	 */
	protected function extra_tablenav( $which ) {

		if ( 'top' === $which ) {
			$drafts_dropdown_arg = array(
				'options'   => array( '' => 'All' ) + $this->allowed_post_types_readable(),
				'container' => array(
					'class' => 'alignleft actions',
				),
				'label'     => array(
					'class'      => 'screen-reader-text',
					'inner_text' => __( 'Filter by Post Type', 'admin-table-tut' ),
				),
				'select'    => array(
					'name'     => 'type',
					'id'       => 'filter-by-type',
					'selected' => filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING ),
				),
			);

			$this->html_dropdown( $drafts_dropdown_arg );

			submit_button( __( 'Filter', 'admin-table-tut' ), 'secondary', 'action', false );
		}
	}

	/**
	 * Navigation dropdown HTML generator
	 *
	 * @param array $args Argument array to generate dropdown.
	 *
	 * @return void
	 */
	private function html_dropdown( $args ) {
		?>

		<div class="<?php echo( esc_attr( $args['container']['class'] ) ); ?>">
			<label
				for="<?php echo( esc_attr( $args['select']['id'] ) ); ?>"
				class="<?php echo( esc_attr( $args['label']['class'] ) ); ?>">
			</label>
			<select
				name="<?php echo( esc_attr( $args['select']['name'] ) ); ?>"
				id="<?php echo( esc_attr( $args['select']['id'] ) ); ?>">
				<?php
				foreach ( $args['options'] as $id => $title ) {
					?>
					<option
					<?php if ( $args['select']['selected'] === $id ) { ?>
						selected="selected"
					<?php } ?>
					value="<?php echo( esc_attr( $id ) ); ?>">
					<?php echo esc_html( \ucwords( $title ) ); ?>
					</option>
					<?php
				}
				?>
			</select>
		</div>

		<?php
	}

	/**
	 * Include the columns which can be sortable.
	 *
	 * @return Array $sortable_columns Return array of sortable columns.
	 */
	public function get_sortable_columns() {

		return array(
			'title'  => array( 'title', false ),
			'type'   => array( 'type', false ),
			'date'   => array( 'date', false ),
			'author' => array( 'author', false ),
		);
	}

}





/** ************************ REGISTER THE TEST PAGE ****************************
 *******************************************************************************
 * Now we just need to define an admin page. For this example, we'll add a top-level
 * menu item to the bottom of the admin menus.
 */






/** *************************** RENDER TEST PAGE ********************************
 *******************************************************************************
 * This function renders the admin page and the example list table. Although it's
 * possible to call prepare_items() and display() from the constructor, there
 * are often times where you may need to include logic here between those steps,
 * so we've instead called those methods explicitly. It keeps things flexible, and
 * it's the way the list tables are used in the WordPress core.
 */
function tt_render_list_page(){
    
    //Create an instance of our package class...
    $testListTable = new TT_Example_List_Table();
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
            <?php $testListTable->display() ?>
        </form>
        
    </div>
    <?php
}



//  include ("olanLlisteleme.php");
