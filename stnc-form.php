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
    add_menu_page('Erciyes Teknopark Başvuruları','Teknopark Form', 'manage_options', 'stncTekForm', 'tt_render_list_page','dashicons-networking'); ////burası main menuyu ekler yani üst ksıım 
    add_submenu_page( 'stncTekForm', 'Ayarlar', 'Ayarlar', 'manage_options', 'stGnlolOptions', 'stncForm_adminMenu_About_contents' ); ////burası alt kısım onun altında olacak olan bolum için 

}

function adjust_the_wp_menu() {
  $page = remove_submenu_page( 'index.php', 'my-sites.php' );
}
add_action( 'admin_menu', 'adjust_the_wp_menu', 999 );


// astra nın starter arayuzu,wpform lite ve polylang wp mail eklentisinde var 
//http://teknoyeni.test/wp-admin/admin.php?page=wp-mail-smtp-setup-wizard#/
//collabse yapsın 

// jQuery(document).ready(function() {
//   if ( !jQuery(document.body).hasClass('folded') ) {
//       jQuery("#collapse-button").trigger('click');
//   }
// });

// notice ıcın 
// https://www.satollo.net/how-to-remove-wordpress-admin-notices

//https://www.google.com/search?q=worpress+my+plugin++notice+disable&rlz=1C1FKPE_trTR967TR967&sxsrf=AOaemvJSN7ezCb-aSg5E1Kki6IUdCrrphg%3A1640262577500&ei=sWvEYfjfHYeW9u8PrKOwgAw&ved=0ahUKEwi4_JOi9vn0AhUHi_0HHawRDMAQ4dUDCA4&uact=5&oq=worpress+my+plugin++notice+disable&gs_lcp=Cgdnd3Mtd2l6EAM6BwgAEEcQsAM6BwgjELACECc6CAgAEAgQDRAeSgQIQRgASgQIRhgAUMUFWMNVYMJYaAJwAXgAgAGnAYgBvwySAQQwLjEymAEAoAEByAEIwAEB&sclient=gws-wiz 
//https://forum.tutorials7.com/2551/disable-plugin-update-notifications-function-in-wordpress

require_once("functions/stncForm-registers.php");



require_once("stncForm-frontUploader.php");
require_once("stncForm-frontDropzone.php");

//diğer ornekelr
// require_once("dropzonejs-wp-rest-api.php");
// require_once("sadeUpload.php");



/*
diğerlerini short codelar 

  upload için Add the shortcode '[wpcfu_form]' --sade
// [StncForm_videoYukle] --- kullanım örneği  --- benım 
[dropzonerest] api ile calısanın 
*/





/* Remove the "Dashboard" from the admin menu for non-admin users **********************************
** http://wordpress.stackexchange.com/questions/52752/hide-dashboard-from-non-admin-users ******* */	
/* !관리자 아닌 회원 알림판 제거 & 리다이렉트 *********************************************************** */
function custom_remove_dashboard () {
  global $current_user, $menu, $submenu;
  wp_get_current_user();

  if( ! in_array( 'administrator', $current_user->roles ) ) {
      reset( $menu );
      $page = key( $menu );
      while( ( __( 'Dashboard' ) != $menu[$page][0] ) && next( $menu ) ) {
          $page = key( $menu );
      }
      if( __( 'Dashboard' ) == $menu[$page][0] ) {
          unset( $menu[$page] );
      }
      reset($menu);
      $page = key($menu);
      while ( ! $current_user->has_cap( $menu[$page][1] ) && next( $menu ) ) {
          $page = key( $menu );
      }
      if ( preg_match( '#wp-admin/?(index.php)?$#', $_SERVER['REQUEST_URI'] ) &&
          ( 'index.php' != $menu[$page][2] ) ) {
            if (!current_user_can('subscriber')) {
              wp_redirect( get_option( 'siteurl' ) . '/wp-admin/edit.php');
            } else {
              wp_redirect( get_option( 'siteurl' ) . '/wp-admin/profile.php');
            }
      }
  }
}
add_action('admin_menu', 'custom_remove_dashboard');


