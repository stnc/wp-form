<?php

function stnc_load_textdomain()
{
    load_plugin_textdomain('stnc', false, dirname(plugin_basename(__FILE__)) . '/i18n/languages/');
}

add_action('plugins_loaded', 'stnc_load_textdomain');

global $wpdb;
$tableNameMain = $wpdb->prefix . 'stnc_teknoparkform';


function stncCatQuiz_install()
{
    global $wpdb;
    global $tableNameMain;



	$charset_collate = $wpdb->get_charset_collate();

      
         $sql = "CREATE TABLE IF NOT EXISTS $tableNameMain (
            id INT NOT NULL AUTO_INCREMENT,
            namelastname varchar(255) DEFAULT NULL,
            company_name varchar(255) DEFAULT NULL,
            phone varchar(255) DEFAULT NULL,
            mail_adress varchar(255) DEFAULT NULL,
            web_site varchar(255) DEFAULT NULL,
            comment TEXT DEFAULT NULL,
            travel_ban varchar(255) DEFAULT NULL,
            user_ip varchar(255) DEFAULT NULL,
            media_id varchar(255) DEFAULT NULL,
            add_date DATETIME NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    



}

register_activation_hook(__FILE__, 'stncCatQuiz_install');

function stncCatQuiz_remove_database()
{

    global $wpdb;
    global $tableNameMain;


    $sql = "DROP TABLE IF EXISTS $tableNameMain";
    $wpdb->query($sql);


    //  delete_option("my_plugin_db_version");
}

register_uninstall_hook(__FILE__, 'stncCatQuiz_remove_database');

// register_deactivation_hook( __FILE__, 'stncCatQuiz_remove_database' );
add_action('admin_init','stncCatQuiz_install');

// add_action('admin_init','stncCatQuiz_remove_database');