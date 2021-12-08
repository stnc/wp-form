<?php
/**v******************************************** */
/**v******************************************** */
/**v******************************************** */
/**v******************************************** */
/**v******************************************** */
//https://gist.github.com/swapnil-webonise/6792192 
include_once 'registeration_form.php';
	
	$mob_table_prefix=$wpdb->prefix;
	define('MOB_TABLE_PREFIX', $mob_table_prefix);
	//****If plugin is active**************************************
	 register_activation_hook(__FILE__,'name_plugin_install');
	 register_deactivation_hook(__FILE__ ,'name_plugin_uninstall');
	 
	//function will call when plugin will active
	function name_plugin_install()
	{
		global $wpdb;
		$table_name = $wpdb->prefix."name_list";
		$structure = "CREATE TABLE $table_name (
		        id INT(9) NOT NULL AUTO_INCREMENT,
		        user_name VARCHAR(20) NOT NULL,
		        PRIMARY KEY id (id)
		    );";
		$wpdb->query($structure);
	}
	//function will call when plugin will deactive
	function name_plugin_uninstall()
	{   
		global $wpdb;
		$table = MOB_TABLE_PREFIX."name_list";
		$structure = "drop table if exists $table";
		$wpdb->query($structure);
	}
	//create menu at admin side
	add_action('admin_menu','name_plugin_admin_menu');
	
	function name_plugin_admin_menu() {
		add_menu_page(
			"Name plugin",
			"Name plugin",
		'administrator',
		__FILE__,
		"name_plugin_setting"
		);
	}