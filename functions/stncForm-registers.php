<?php
// register our form css
function stncForm_register_js()
{
    // Register the JS file with a unique handle, file location, and an array of dependencies
    wp_enqueue_script("dropzone",  plugins_url('../assets/js/dropzone.min.js', __FILE__), array('jquery'));

    /*
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
 */

    //  wp_register_script("dropzone.dict", plugins_url('../assets/js/dropzone.dict-tr.js', __FILE__) . '', array('jquery'));
    //   wp_register_script("stnc_upload", plugins_url('../assets/js/stnc_upload.js',__FILE__) , array('jquery'));

    // localize the script to your domain name, so that you can reference the url to admin-ajax.php file easily
    wp_localize_script('dropzone', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

    // enqueue jQuery library and the script you registered above
    wp_enqueue_script('jquery');
    wp_enqueue_script('dropzone');
    //     wp_enqueue_script('dropzone.dict');
    //   wp_enqueue_script('stnc_upload');
}

add_action('wp_enqueue_scripts', 'stncForm_register_js');

// load css into the website's front-end
function stncForm_enqueue_style()
{
    if ((isset($_GET['page'])) && ($_GET['page'] === 'stncTekForm')) {
    wp_enqueue_style('stnc-style', plugins_url('../assets/css/stnc.css', __FILE__));
    }
}
add_action('admin_enqueue_scripts', 'stncForm_enqueue_style');


function dropzone3_enqueue_style()
{
    wp_enqueue_style('dropzone3', "https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.css");
    wp_enqueue_style('stnc-style', plugins_url('../assets/css/stncForm.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'dropzone3_enqueue_style');


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