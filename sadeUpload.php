
<?php 
//alınma upload kısmı https://gist.github.com/shamimmoeen/bd577b353446cb8fe519137b164d40e4
//    upload için Add the shortcode '[wpcfu_form]'
if ( ! function_exists( 'wpcfu_output_file_upload_form' ) ) {

	/**
	 * Output the form.
	 *
	 * @param      array  $atts   User defined attributes in shortcode tag
	 */
	function wpcfu_output_file_upload_form( $atts ) {
		$atts = shortcode_atts( array(), $atts );

		$html = '';

		$html .= '<form class="wpcfu-form" method="POST" enctype="multipart/form-data">';

			$html .= '<p class="form-field">';
				$html .= '<input type="file" name="wpcfu_file">';
			$html .= '</p>';

			$html .= '<p class="form-field">';

				// Output the nonce field
				$html .= wp_nonce_field( 'upload_wpcfu_file', 'wpcfu_nonce', true, false );

				$html .= '<input type="submit" name="submit_wpcfu_form" value="' . esc_html__( 'Upload', 'theme-text-domain' ) . '">';
			$html .= '</p>';

		$html .= '</form>';

		echo $html;
	}
}

/**
 * Add the shortcode '[wpcfu_form]'.
 */
add_shortcode( 'wpcfu_form', 'wpcfu_output_file_upload_form' );

if ( ! function_exists( 'wpcfu_handle_file_upload' ) ) {

	/**
	 * Handles the file upload request.
	 */
	function wpcfu_handle_file_upload() {
		// Stop immidiately if form is not submitted
		if ( ! isset( $_POST['submit_wpcfu_form'] ) ) {
			return;
		}

		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['wpcfu_nonce'], 'upload_wpcfu_file' ) ) {
			wp_die( esc_html__( 'Nonce mismatched', 'theme-text-domain' ) );
		}

		// Throws a message if no file is selected
		if ( ! $_FILES['wpcfu_file']['name'] ) {
			wp_die( esc_html__( 'Please choose a file', 'theme-text-domain' ) );
		}

		$allowed_extensions = array( 'jpg', 'jpeg', 'png' , 'mp4');
		$file_type = wp_check_filetype( $_FILES['wpcfu_file']['name'] );
		$file_extension = $file_type['ext'];

		// Check for valid file extension
		if ( ! in_array( $file_extension, $allowed_extensions ) ) {
			wp_die( sprintf(  esc_html__( 'Invalid file extension, only allowed: %s', 'theme-text-domain' ), implode( ', ', $allowed_extensions ) ) );
		}

		$file_size = $_FILES['wpcfu_file']['size'];
		//$allowed_file_size = 512000; // Here we are setting the file size limit to 500 KB = 500 × 1024
  $allowed_file_size = 1048576 * 80; //http://www.learningaboutelectronics.com/Articles/How-to-restrict-the-size-of-a-file-upload-with-PHP.php

		// Check for file size limit
		if ( $file_size >= $allowed_file_size ) {
			wp_die( sprintf( esc_html__( 'File size limit exceeded, file size should be smaller than %d KB', 'theme-text-domain' ), $allowed_file_size / 1000 ) );
		}

		// These files need to be included as dependencies when on the front end.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		// Let WordPress handle the upload.
		// Remember, 'wpcfu_file' is the name of our file input in our form above.
		// Here post_id is 0 because we are not going to attach the media to any post.
		$attachment_id = media_handle_upload( 'wpcfu_file', 0 );

		if ( is_wp_error( $attachment_id ) ) {
			// There was an error uploading the image.
			wp_die( $attachment_id->get_error_message() );
		} else {
			// We will redirect the user to the attachment page after uploading the file successfully.
			wp_redirect( get_the_permalink( $attachment_id ) );
			exit;
		}
	}
}

/**
 * Hook the function that handles the file upload request.
 */
add_action( 'init', 'wpcfu_handle_file_upload' );
