<?php
/**
 * Plugin Name:     datatable example 
 */

add_action( 'wp_enqueue_scripts', 'my_custom_enqueue_scripts', 10 );
function my_custom_enqueue_scripts() {
	wp_enqueue_style('jquery-datatables-css','//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css');
	wp_enqueue_script('jquery-datatables-js','//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js',array('jquery'));
}


add_action('wp_ajax_datatables_endpoint', 'my_custom_ajax_endpoint'); //logged in
add_action('wp_ajax_no_priv_datatables_endpoint', 'my_custom_ajax_endpoint'); //not logged in
function my_custom_ajax_endpoint(){

	$response = []; 
	
	//Get WordPress posts - you can get your own custom posts types etc here
	$posts = get_posts([
		"post_type" => "post",
		"posts_per_page" => -1,
	]);
	
	//Add two properties to our response data and recorddata 
	$response["data"] = !empty($posts) ? $posts : []; //array of post objects if we have any, otherwise an empty array        
	$response["recordsTotal"] = !empty($posts) ? count($posts) : 0; //total number of posts without any filtering applied
	
	wp_send_json($response); //json_encodes our $response and sends it back with the appropriate headers

}