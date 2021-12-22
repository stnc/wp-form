<?php 
/** ************************ Menu Defined  ****************************
 *******************************************************************************
 * Now we just need to define an admin page. For this example, we'll add a top-level
 * menu item to the bottom of the admin menus.
 */
add_action('admin_menu', 'StncMainMenu');
function StncMainMenu(){
    add_menu_page('Gönüllü Ol Başvuruları','Gönüllüler', 'manage_options', 'stGnlolList', 'tt_render_list_page'); ////burası main menuyu ekler yani üst ksıım 
    add_submenu_page( 'stGnlolList', 'Ayarlar', 'Ayarlar', 'manage_options', 'stGnlolOptions', 'my_admin_page_contents' ); ////burası alt kısım onun altında olacak olan bolum için 
}
