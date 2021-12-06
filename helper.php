<?php
if (!defined('ABSPATH')) {
    exit;
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


/**
 * Get User Status (like/dislike)  -- for  
 * @param           int $post_id
 * @since           1.0
 * @return            int
 */
function get_total_like_all($post_id)
{
    global $wpdb;

    $query = " SELECT count(id)	FROM " . esc_sql($wpdb->prefix . 'ulike') . " WHERE `post_id` = '" . esc_sql($post_id) . "' ";

    $result = $wpdb->get_var($query);

    return empty($result) ? 0 : $result;
}


/**
 * Get User Status (like/dislike)  -- for  
 * @param           int $post_id
 * @since           1.0
 * @return            int
 */
function get_total_like_for_like($post_id)
{
    global $wpdb;

    $query = "
					SELECT count(id)
					FROM " . esc_sql($wpdb->prefix . 'ulike') . "
					WHERE `post_id` = '" . esc_sql($post_id) . "' AND `status` ='like'  ";

    $result = $wpdb->get_var($query);

    return empty($result) ? 0 : $result;
}

/**
 * Get User Status (like/dislike)  -- for 
 * @param           int $post_id
 * @since           1.0
 * @return            int
 */
function get_total_like_for_like_userME($post_id,$userId)
{
    global $wpdb;

    $query = "
					SELECT status
					FROM " . esc_sql($wpdb->prefix . 'ulike') . "
					WHERE `post_id` = '" . esc_sql($post_id) . "' AND `status` ='like' AND `user_id` = '" . esc_sql($userId) . "'  ";

    $result = $wpdb->get_var($query);

    return empty($result) ? 'unlike' : $result;
}


/**************************************************************************************************
 ****  admin panelden gelen post daki verinin eklenme düzenlem işleri -end  ***
 **************************************************************************************************/



//---------- ADMİN PANEL -- FEATURED IMAGE

/// https://stnc.com/how-to-show-wordpress-featured-image-in-wordpress-admin-panel/
add_image_size('stnc-admin-post-featured-image', 120, 120, false);
// Add the posts and pages columns filter. They can both use the same function.
add_filter('manage_posts_columns', 'stnc_add_post_admin_thumbnail_column', 2);//posts
add_filter('manage_pages_columns', 'stnc_add_post_admin_thumbnail_column', 2);//pages
// Add the column
function stnc_add_post_admin_thumbnail_column($stnc_columns)
{
    $stnc_columns['stnc_thumb'] = __('Featured Image');
    return $stnc_columns;
}



// Let's manage Post and Page Admin Panel Columns
add_action('manage_posts_custom_column', 'stnc_show_post_thumbnail_column', 5, 2); //posts
add_action('manage_pages_custom_column', 'stnc_show_post_thumbnail_column', 5, 2);//pages 
// Here we are grabbing featured-thumbnail size post thumbnail and displaying it
function stnc_show_post_thumbnail_column($stnc_columns, $stnc_id)
{
    switch ($stnc_columns) {
        case 'stnc_thumb':
            if (function_exists('the_post_thumbnail')) {
                echo the_post_thumbnail('stnc-admin-post-featured-image');
            } else {
                echo 'hmm... your theme doesn\'t support featured image...';
            }

            break;
    }
}
