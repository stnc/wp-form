<?php
//TODO: bu kısımları eklenti yuknince gelen yukleme modullerı seklınde yapabiliriz 
$stncForm_tableNameMain = 'stnc_teknoparkform';
function stncForm_install()
{
    global $wpdb;
    global $stncForm_tableNameMain;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS  " . $wpdb->prefix . $stncForm_tableNameMain . " (
            id INT NOT NULL AUTO_INCREMENT,
            namelastname varchar(255) DEFAULT NULL,
            company_name varchar(255) DEFAULT NULL,
            phone varchar(255) DEFAULT NULL,
            mail_adress varchar(255) DEFAULT NULL,
            web_site varchar(255) DEFAULT NULL,
            comment TEXT DEFAULT NULL,
            travel_ban varchar(255) DEFAULT NULL,
            user_ip varchar(255) DEFAULT NULL,
            media_id INT DEFAULT NULL,
            add_date DATETIME NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  //  dbDelta($sql);
    // echo $wpdb->last_error;

}

register_activation_hook(__FILE__, 'stncForm_install');

add_action('admin_init', 'stncForm_install');

function stncForm_remove_database()
{

    global $wpdb;
    global $stncForm_tableNameMain;

    $sql = "DROP TABLE IF EXISTS " . $wpdb->prefix . $stncForm_tableNameMain . "";
    $wpdb->query($sql);
    //  delete_option("my_plugin_db_version");
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_uninstall_hook(__FILE__, 'stncForm_remove_database');
register_deactivation_hook(__FILE__, 'stncForm_remove_database');

// function stncForm_load_textdomain()
// {
//     load_plugin_textdomain('stnc', false, dirname(plugin_basename(__FILE__)) . '/i18n/languages/');
// }

// add_action('plugins_loaded', 'stncForm_load_textdomain');

// add_action('admin_init','stncForm_remove_database');