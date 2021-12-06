<?php
//https://www.inmotionhosting.com/support/edu/wordpress/save-wordpress-plugin-settings-with-form/
/*  ///OLD Sinlge menu 
function my_admin_menu()
{
    add_menu_page(

        __('Sample page', 'my-textdomain'),

        __('Sample menu', 'my-textdomain'),
        'manage_options',
        'sample-page',
        'my_admin_page_contents',
        'dashicons-schedule',
        3
    );
}

add_action('admin_menu', 'my_admin_menu');

*/



function my_admin_page_contents()
{

    ?>

    <h1>

    <?php esc_html_e('Welcome to my custom admin page.', 'my-plugin-textdomain');?>

    </h1>
<p>

kullanmak istediğiniz sayfada  [stncGonulluOl_RegisterForm]
    <br> bu shortcode kullanarak çağrılabilir.</p>
    upload için Add the shortcode '[wpcfu_form]'.
    <?php

}


function register_my_plugin_scripts()
{
    wp_register_style('my-plugin', plugins_url('ddd/css/plugin.css'));
    wp_register_script('my-plugin', plugins_url('ddd/js/plugin.js'));
}


add_action('admin_enqueue_scripts', 'register_my_plugin_scripts');

function load_my_plugin_scripts($hook)
{
    // Load only on ?page=sample-page
    if ($hook != 'toplevel_page_sample-page') {
        return;
    }
    // Load style & scripts.
    wp_enqueue_style('my-plugin');
    wp_enqueue_script('my-plugin');
}

add_action('admin_enqueue_scripts', 'load_my_plugin_scripts');


//https://wordpress.stackexchange.com/questions/342433/error-options-page-not-found-saving-settings-page-with-tabs

