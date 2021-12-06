<?php
if (!defined('ABSPATH')) {
    exit;
}


/**************************************************************************************************
 ****  single ve all post içindeki dataya bağlı olduğu quiz verisini ekler -start (REST API) ***
 **************************************************************************************************/


//https://upnrunn.com/blog/2018/02/extend-wp-rest-api-custom-plugin-part-2/

/**
 * Registers REST API endpoints -- for post list in response quizID
 *
 * @since 1.0.0
 */
add_action('rest_api_init', 'stnc_register_quizID');
function stnc_register_quizID()
{
    register_rest_field(
        'post',
        'quiz_id',
        array(
            'get_callback' => 'stnc_get_quizID',
            'update_callback' => 'stnc_update_quizID',
            'schema' => null,
        ));
}


/**
 * Handler for updating custom field data.
 */
function stnc_update_quizID($value, $object, $field_name)
{
    if (!$value || !is_string($value)) {
        return;
    }
    // return update_post_meta($object->ID, $field_name, strip_tags($value));
    return update_post_meta($object->ID, $field_name, int($value));
}

/**
 * Handler for getting custom field data.
 */
function stnc_get_quizID($object, $field_name, $request)
{
    return get_post_meta($object['id'], $field_name);
}

/**************************************************************************************************
 ****  single ve all post içindeki dataya bağlı olduğu youtube verisini ekler -start (REST API) ***
 **************************************************************************************************/

add_action('rest_api_init', 'stnc_register_youtube');
function stnc_register_youtube()
{
    register_rest_field(
        'post',
        'youtubeLink',
        array(
            'get_callback' => 'stnc_get_youtube',
            'schema' => null));
}



/**
 * Handler for getting custom field data.
 */
function stnc_get_youtube($object, $field_name, $request)
{
    return get_post_meta($object['id'], $field_name);
}


/********************************************************************************************************************************
 ************  admin panelden gelen post daki verinin eklenme düzenlem işleri -start (kullanılmıyor neden ekledim bilmiyorum) ***
 ********************************************************************************************************************************/

add_action('wp_insert_post', 'stnc_youtube_fields');

function stnc_youtube_fields($post_id)
{
    add_post_meta($post_id, 'youtubeLink',null, true);
    return true;
}

add_action('wp_update_post', 'stnc_youtube_fields1');

function stnc_youtube_fields1($post_id)
{
    if (!$value || !is_string($value)) {
        return;
    }
   
    return update_post_meta($post_id, 'youtubeLink', int($value));
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



