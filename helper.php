<?php
if (!defined('ABSPATH')) {
    exit;
}




// used for tracking error messages
function stncGonulluOl_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}


// displays error messages from form submissions
function stncGonulluOl_show_error_messages() {
	if($codes = stncGonulluOl_errors()->get_error_codes()) {
		echo '<div class="stncGonulluOl_errors">';
		    // Loop error codes and display errors
		   foreach($codes as $code){
		        $message = stncGonulluOl_errors()->get_error_message($code);
		        echo '<span class="error"><strong>' . __('Hata') . '</strong>: ' . $message . '</span><br/>';
		    }
		echo '</div>';
		
	}	
}


/**
 * Helper array shuffle
 * @param array $list The request sent from WP REST API.
 * @return array random array list
 */
function shuffle_assoc($list)
{
    if (!is_array($list)) {
        return $list;
    }

    $keys = array_keys($list);
    shuffle($keys);
    $random = array();
    foreach ($keys as $key) {
        $random[$key] = $list[$key];
    }
    return $random;
}

