<?php 

// register our form css
function stncForm_register_js() {

      // Register the JS file with a unique handle, file location, and an array of dependencies
  wp_register_script("dropzone",  plugins_url('../assets/js/dropzone.min.js',__FILE__) , array('jquery'));
  wp_register_script("dropzone.dict", plugins_url('../assets/js/dropzone.dict-tr.js',__FILE__) . '', array('jquery'));
  wp_register_script("stnc_upload", plugins_url('../assets/js/stnc_upload.js',__FILE__) , array('jquery'));

  // localize the script to your domain name, so that you can reference the url to admin-ajax.php file easily
  wp_localize_script('stnc_upload', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

  // enqueue jQuery library and the script you registered above
  wp_enqueue_script('jquery');
  wp_enqueue_script('dropzone');
  wp_enqueue_script('dropzone.dict');
  wp_enqueue_script('stnc_upload');
}

add_action('init', 'stncForm_register_js');

// load css into the website's front-end
function stncForm_enqueue_style() {
    wp_enqueue_style( 'mytheme-style','https://cdnjs.cloudflare.com/ajax/libs/mini.css/3.0.1/mini-default.min.css')  ; 
}
add_action( 'admin_enqueue_scripts', 'stncForm_enqueue_style' );

/*

// load css into the website's front-end
function mytheme_enqueue_style() {
    wp_enqueue_style( 'mytheme-style', get_stylesheet_uri() ); 
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_style' );
 
// load css into the admin pages
function mytheme_enqueue_options_style() {
    wp_enqueue_style( 'mytheme-options-style', get_template_directory_uri() . '/css/admin.css' ); 
}
add_action( 'admin_enqueue_scripts', 'mytheme_enqueue_options_style' );
 
// load css into the login page
function mytheme_enqueue_login_style() {
    wp_enqueue_style( 'mytheme-options-style', get_template_directory_uri() . '/css/login.css' ); 
}
add_action( 'login_enqueue_scripts', 'mytheme_enqueue_login_style' );

*/

