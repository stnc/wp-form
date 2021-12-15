<?php
//FIXME: veri butunlugu ıcın sql_esc_ input filter gib seyler kullanıyorlar onlara bak 
//// '<span title="' . $t_time . '">' . apply_filters('post_date_column_time', $h_time, $item['id'], 'date', 'list') . '</span>'; //TODO: bu fıltre hatırlanabılır 
//https://wordpress.stackexchange.com/questions/56805/saving-frontend-form-data-in-wordpress

//https://wpshout.com/wordpress-submit-posts-from-frontend/



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
if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
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
class TT_Example_List_Table extends WP_List_Table
{

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
	public function __construct()
	{

		parent::__construct(
			array(
				'singular' => __('Customer', 'sp'), //singular name of the listed records
				'plural'   => __('Customers', 'sp'), //plural name of the listed records
				'ajax'     => false //does this table support ajax?
			)
		);

		$this->allowed_post_types = $this->allowed_post_types();
	}

	/**
	 * Retrieve post types to be shown in the table.
	 *
	 * @return array Allowed post types in an array.
	 */
	private function allowed_post_types()
	{
		$post_types = get_post_types(array('public' => true));
		unset($post_types['attachment']);

		return $post_types;
	}

	/**
	 * Convert slug string to human readable.
	 *
	 * @param string $title String to transform human readable.
	 *
	 * @return string Human readable of the input string.
	 */
	private function human_readable($title)
	{
		return ucwords(str_replace('_', ' ', $title));
	}

	/**
	 * A map method return all allowed post types to human readable format.
	 *
	 * @return array Array of allowed post types in human readable format.
	 */
	private function allowed_post_types_readable()
	{
		$formatted = array_map(
			array($this, 'human_readable'),
			$this->allowed_post_types
		);

		return $formatted;
	}

	/**
	 * Display text for when there are no items.
	 */
	public function no_items()
	{
		esc_html_e('No posts found.', 'admin-table-tut');
	}

	/**
	 * The Default columns
	 *
	 * @param  array  $item        The Item being displayed.
	 * @param  string $column_name The column we're currently in.
	 * @return string              The Content to display
	 */
	public function column_default($item, $column_name)
	{
		$result = '';
		switch ($column_name) {
			case 'add_date':
				$phpdate = strtotime($item['add_date']);
				$mysqldate = date('d-m-Y H:i', $phpdate);

				$result = $mysqldate;
				break;

			case 'namelastname':
				$result = $item['namelastname'];
				break;

			case 'company_name':
				$result = $item['company_name'];
				break;

			case 'phone':
				$result = $item['phone'];
				break;
		}

		return $result;
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns()
	{
		return array(
			'cb'     => '<input type="checkbox"/>',
			'namelastname'  => __('Name', 'admin-table-tut'),
			'company_name'   => __('Company', 'admin-table-tut'),
			'phone' => __('Phone', 'admin-table-tut'),
			'add_date'   => __('Date', 'admin-table-tut'),
		);
	}

	/** ************************************************************************
	 * Recommended. This is a custom column method and is responsible for what
	 * is rendered in any column with a name/slug of 'title'. Every time the class
	 * needs to render a column, it first looks for a method named 
	 * column_{$column_title} - if it exists, that method is run. If it doesn't  //burası öenmli funksiyon adı column_{$column_title} boyle olacak 
	 * exist, column_default() is called instead.
	 * 
	 * This example also illustrates how to implement rollover actions. Actions
	 * should be an associative array formatted as 'slug'=>'link html' - and you
	 * will need to generate the URLs yourself. You could even ensure the links
	 * 
	 * 
	 * @see WP_List_Table::::single_row_columns()
	 * @param array $item A singular item (one full row's worth of data)
	 * @return string Text to be placed inside the column <td> (movie title only)
	 **************************************************************************/
	function column_namelastname($item)
	{
		$delete_nonce = wp_create_nonce('sp_delete_customer');

		$title = '<strong>' . $item['namelastname'] . '</strong>';

		$actions = [
			'view' => sprintf('<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">view</a>', esc_attr($_REQUEST['page']), 'view', absint($item['id']), $delete_nonce),
			'delete' => sprintf('<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['id']), $delete_nonce)
		];

		return $title . $this->row_actions($actions);
	}

	//http://summit.test/wp-admin/admin.php?page=wp_list_table_class&action=delete&customer=16&_wpnonce=243e56ab02

	/**
	 * Column cb.
	 *
	 * @param  array $item Item data.
	 * @return string
	 */
	public function column_cb($item)
	{
		return sprintf(
			'<input type="checkbox" name="%1$s_id[]" value="%2$s" />',
			esc_attr($this->_args['singular']),
			esc_attr($item['id'])
		);
	}

	/**
	 * Prepare the data for the WP List Table
	 *
	 * @return void
	 */
	public function prepare_items()
	{
		$columns               = $this->get_columns();
		$sortable              = $this->get_sortable_columns();
		$hidden                = array();
		$primary               = 'title';
		$this->_column_headers = array($columns, $hidden, $sortable, $primary);
		$data                  = array();

		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page('customers_per_page', 15);
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args([
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		]);


		$this->items = self::get_customers($per_page, $current_page);
	}

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_customers($per_page = 5, $page_number = 1)
	{

		global $wpdb;

	
		if (isset($_POST['s'])) {
			$search = $_POST['s'];

			$search = trim($search);

			$sql = "SELECT * FROM {$wpdb->prefix}stnc_teknoparkform WHERE namelastname LIKE '%$search%' ";
			if (!empty($_REQUEST['orderby'])) {
				$sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
				$sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
			}

			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
			$result = $wpdb->get_results($sql, 'ARRAY_A');
		} else {
			$sql = "SELECT * FROM {$wpdb->prefix}stnc_teknoparkform";
			if (!empty($_REQUEST['orderby'])) {
				$sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
				$sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
			}

			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
			$result = $wpdb->get_results($sql, 'ARRAY_A');
		}



		return $result;
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count()
	{
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}stnc_teknoparkform";

		return $wpdb->get_var($sql);
	}


	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions()
	{
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}

	/**
	 * Get bulk actions.
	 *
	 * @return void
	 */
	public function process_bulk_action()
	{
		//Detect when a bulk action is being triggered...
		if ('delete' === $this->current_action()) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr($_REQUEST['_wpnonce']);

			if (!wp_verify_nonce($nonce, 'sp_delete_customer')) {
				die('Go get a life script kiddies');
			} else {
				self::delete_customer(absint($_GET['customer']));

				// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
				// add_query_arg() return the current url
				wp_redirect(esc_url_raw(add_query_arg()));
				exit;
			}
		}


		if ('view' === $this->current_action()) {
			global $wpdb;
			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr($_REQUEST['_wpnonce']);
			 $id = filter_input(INPUT_GET, 'customer', FILTER_DEFAULT);
			$data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}stnc_teknoparkform WHERE id = $id");
			echo $data -> namelastname;

		//https://stackoverflow.com/questions/63659504/how-to-get-attachment-id-of-a-file-uploaded-in-wordpress-post
			   $oynat= wp_get_attachment_url( $data -> media_id );

			echo  do_shortcode('[evp_embed_video url="'.   $oynat.'"  autoplay="true" width="640" template="mediaelement" preload="auto" ]');
			echo "<br>";
			echo "burası veri basacak yer ";
			exit;
		}


		// If the delete bulk action is triggered
		if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
			|| (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
		) {

			$post_ids = filter_input(INPUT_POST, 'customer_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);


			if (is_array($post_ids)) {

				$post_ids = array_map('intval', $post_ids);
				foreach ($post_ids as $id) {
					self::delete_customer($id);
				}
				// if ( count( $post_ids ) ) {
				// 	array_map( 'wp_trash_post', $post_ids );
				// }
			}

			// loop over the array of record IDs and delete them


			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
			// add_query_arg() return the current url
			wp_redirect(esc_url_raw(add_query_arg()));
			exit;
		}
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_customer($id)
	{
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}stnc_teknoparkform",
			['ID' => $id],
			['%d']
		);
	}


	/**
	 * Generates the table navigation above or below the table
	 *
	 * @param string $which Position of the navigation, either top or bottom.
	 *
	 * @return void
	 */
	protected function display_tablenav($which)
	{
?>
		<div class="tablenav <?php echo esc_attr($which); ?>">

			<?php if ($this->has_items()) : ?>
				<div class="alignleft actions bulkactions">
					<?php $this->bulk_actions($which); ?>
				</div>
			<?php
			endif;
			$this->extra_tablenav($which);
			$this->pagination($which);
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
	protected function extra_tablenav($which)
	{

		if ('top' === $which) {
			$drafts_dropdown_arg = array(
				'options'   => array('' => 'All') + $this->allowed_post_types_readable(),
				'container' => array(
					'class' => 'alignleft actions',
				),
				'label'     => array(
					'class'      => 'screen-reader-text',
					'inner_text' => __('Filter by Post Type', 'admin-table-tut'),
				),
				'select'    => array(
					'name'     => 'type',
					'id'       => 'filter-by-type',
					'selected' => filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING),
				),
			);

			$this->html_dropdown($drafts_dropdown_arg);

			submit_button(__('Filter', 'admin-table-tut'), 'secondary', 'action', false);
		}
	}

	/**
	 * Navigation dropdown HTML generator
	 *
	 * @param array $args Argument array to generate dropdown.
	 *
	 * @return void
	 */
	private function html_dropdown($args)
	{
	?>

		<div class="<?php echo (esc_attr($args['container']['class'])); ?>">
			<label for="<?php echo (esc_attr($args['select']['id'])); ?>" class="<?php echo (esc_attr($args['label']['class'])); ?>">
			</label>
			<select name="<?php echo (esc_attr($args['select']['name'])); ?>" id="<?php echo (esc_attr($args['select']['id'])); ?>">
				<?php
				foreach ($args['options'] as $id => $title) {
				?>
					<option <?php if ($args['select']['selected'] === $id) { ?> selected="selected" <?php } ?> value="<?php echo (esc_attr($id)); ?>">
						<?php echo esc_html(\ucwords($title)); ?>
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
	public function get_sortable_columns()
	{

		return array(
			'namelastname'  => array('namelastname', false),
			'company_name'   => array('company_name', false),
			'phone'   => array('phone', false),
			'add_date' => array('add_date', false),
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
function tt_render_list_page()
{

	//Create an instance of our package class...
	$testListTable = new TT_Example_List_Table();
	//Fetch, prepare, sort, and filter our data...
	$testListTable->prepare_items();

	?>
	<div class="wrap">

		<div id="icon-users" class="icon32"><br /></div>
		<h2>Erciyes Teknopark Video Gönderileri</h2>

		<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
			<p>Bu eklenti Erciyes Teknopark İçin Selman TUNÇ tarafından yapılmıştır, <br>
				Eklentiyi bu <a href="https://github.com/stnc/wp-form" target="_blank" style="text-decoration:none;">linkten</a> indirebilirsiniz </p>

		</div>

		<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
		<form id="movies-filter" method="post">
			<!-- For plugins, we also need to ensure that the form posts back to our current page -->
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			<!-- Now we can render the completed list table -->
			<?php

			$testListTable->search_box('search', 'search_id');
			$testListTable->display() ?>
		</form>

	</div>
<?php
}




