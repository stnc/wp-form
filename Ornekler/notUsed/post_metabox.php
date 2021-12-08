<?php
/*******************************************************
 *************  register metabox -start  ***************
  ******************************************************
 --- wp admin panelde bulunan yerde gorunen kısımdır---
 *******************************************************/

function stnc_register_meta_boxes()
{
    add_meta_box('stnc-1', __('STNC Custom Field', 'stnc'), 'stnc_display_callback', 'post');
}
add_action('add_meta_boxes', 'stnc_register_meta_boxes');

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function stnc_display_callback($post)
{
    include plugin_dir_path(__FILE__) . './post_metabox_form.php';
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function stnc_save_meta_box($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
    }
    $fields = [
        'youtubeLink',
    
        'quiz_id',
    ];
    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'stnc_save_meta_box');

/*******************************************************
 *************  register metabox -end  ***************
 *******************************************************/