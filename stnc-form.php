<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Plugin Name: Erciyes Teknopark Kayıt Formu [BY STNC]
 * Plugin URI: selmantunc.com.tr
 * Description:  Erciyes Teknopark Kayıt Formu
 * Author: stnc
 * Author URI: http://selmantunc.com.tr
 * Version: 1.0.0
 *
 */

$CHfw_meta_key = 'wowPostSetting';

global $stncCatQuiz;
$stncCatQuiz = "2.0.0";

require_once "functions/stncForm-install-database.php";

require_once "helper.php";
require_once "list_table.php";
require_once "stncForm-adminMenu-About.php";

/** ************************ Menu Defined  ****************************
 *******************************************************************************
 * Now we just need to define an admin page. For this example, we'll add a top-level
 * menu item to the bottom of the admin menus.
 */

add_action('admin_menu', 'StncMainMenu');
function StncMainMenu(){
    add_menu_page('Gönüllü Ol Başvuruları','Gönüllüler', 'manage_options', 'stGnlolList', 'tt_render_list_page'); ////burası main menuyu ekler yani üst ksıım 
    add_submenu_page( 'stGnlolList', 'Ayarlar', 'Ayarlar', 'manage_options', 'stGnlolOptions', 'stncForm_adminMenu_About_contents' ); ////burası alt kısım onun altında olacak olan bolum için 
}




/**v******************************************** */
/**v******************************************** */
/**v******************************************** */



require_once("functions/stncForm-registers.php");



require_once("stncForm-frontUploader.php");



