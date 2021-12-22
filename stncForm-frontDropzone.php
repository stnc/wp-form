<?php
// define the actions for the two hooks created, first for logged in users and the next for logged out users
add_action("wp_ajax_stncFormSent", "stncFormSent");
add_action("wp_ajax_nopriv_stncFormSent", "stncFormSent");


// define the function to be fired for logged in users
function stncFormSent()
{

  // nonce check for an extra layer of security, the function will exit if it fails
  if (!wp_verify_nonce($_REQUEST['nonce'], "stncFormSent_nonce")) {
    exit("Woof Woof Woof");
  }

  $errors = [];
  $data = [];


  // Throws a message if no file is selected
  if (!$_FILES['file']['name']) {
    wp_die(esc_html__('Please choose a file', 'theme-text-domain'));
  }

  $allowed_extensions = array('ppt', 'pptx', 'webm','pdf', 'm4v', 'mp4');
  $file_type = wp_check_filetype($_FILES['file']['name']);
  $file_extension = $file_type['ext'];

  // Check for valid file extension
  if (!in_array($file_extension, $allowed_extensions)) {
    // wp_die(sprintf(esc_html__('Invalid file extension, only allowed: %s', 'theme-text-domain'), implode(', ', $allowed_extensions)));
    $errors['hata'] = 'Geçersiz uzantı, yüklenebilir uzantılar mp4,m4k,pdf,ppt,pptx';
  }




  $file_size = $_FILES['file']['size'];

  $allowed_file_size = 1048576 * 80; //http://www.learningaboutelectronics.com/Articles/How-to-restrict-the-size-of-a-file-upload-with-PHP.php


  //   // Check for file size limit
  if ($file_size >= $allowed_file_size) {
    // wp_die(sprintf(esc_html__('File size limit exceeded, file size should be smaller than %d KB', 'theme-text-domain'), $allowed_file_size / 1000));
    $errors['hata'] = 'Dosya boyutunuz ' . $allowed_file_size / 1000 . ' mb dan büyük olamaz';
  }

  // These files need to be included as dependencies when on the front end.
  require_once(ABSPATH . 'wp-admin/includes/image.php');
  require_once(ABSPATH . 'wp-admin/includes/file.php');
  require_once(ABSPATH . 'wp-admin/includes/media.php');

  // Let WordPress handle the upload.
  // Remember, 'wpcfu_file' is the name of our file input in our form above.
  // Here post_id is 0 because we are not going to attach the media to any post.

  if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = $errors;
    echo json_encode($data);
    die;
  }


  $attachment_id = media_handle_upload('file', 0);


  if (is_wp_error($attachment_id)) {
    // There was an error uploading the image.
    //  wp_die($attachment_id->get_error_message());
    $data['errors']  = $attachment_id->get_error_message();
  } else {
    $data['success'] = true;
    $data['message'] = 'Dosya Gönderildi, Teşekkür ederiz';

    global $wpdb;
    $tableNameMain = $wpdb->prefix . 'stnc_teknoparkform';
    $dbData = array();
    $dbData['media_id'] = $attachment_id;

    $wpdb->update($tableNameMain, $dbData, array('id' => sanitize_text_field($_REQUEST['postID'] )));
  }



  echo json_encode($data);
  die;
}

// define the function to be fired for logged out users
function please_login()
{
  echo "You must log in to like";
  die();
}


// user registration login form
function stncForm_VideUploadForm()
{

  // only show the registration form to non-logged-in members


  global $stncForm_load_css;

  // set this to true so the CSS is loaded
  $stncForm_load_css = true;
  /*
		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');
	
		// only show the registration form if allowed
		if($registration_enabled) {
			$output = stncForm_VideUploadForm_fields();
		} else {
			$output = __('User registration is not enabled');
		}
*/
  $output = stncForm_VideUploadForm_fields();
  return $output;


}
add_shortcode('StncForm_videoYukle', 'stncForm_VideUploadForm');
// [StncForm_videoYukle] --- kullanım örneği