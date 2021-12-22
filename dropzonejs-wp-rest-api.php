
<?php 
define( 'DROPZONEJS_WP_REST_API_PLUGIN_URL',   plugin_dir_url( __FILE__ ) );
define( 'DROPZONEJS_WP_REST_API_PLUGIN_VERSION', '0.0.1' );

add_action( 'plugins_loaded', 'dropzonejs_wp_rest_api_init' );

function dropzonejs_wp_rest_api_init() {
	add_action( 'wp_enqueue_scripts', 'dropzonejs_wp_rest_api_enqueue_scripts' );
	add_shortcode( 'dropzonerest', 'dropzonejs_wp_rest_api_shortcode' );
}

function dropzonejs_wp_rest_api_enqueue_scripts() {

	wp_enqueue_script(
		'dropzonejs',
		'https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js',
		array(),
		DROPZONEJS_WP_REST_API_PLUGIN_VERSION
	);

	// Load custom dropzone javascript
	wp_enqueue_script(
		'dropzone-wp-rest',
		DROPZONEJS_WP_REST_API_PLUGIN_URL . '/js/dropzonejs-wp-rest-api.js',
		array( 'wp-api', 'dropzonejs' ),
		DROPZONEJS_WP_REST_API_PLUGIN_VERSION
	);

	wp_enqueue_style(
		'dropzonecss',
		'https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.css',
		array(),
		DROPZONEJS_WP_REST_API_PLUGIN_VERSION
	);

	// from: http://v2.wp-api.org/guide/authentication/
	wp_enqueue_script( 'wp-api' );
	wp_localize_script(
		'wp-api',
		'WP_API_Settings',
		array(
			'root'        => esc_url_raw( rest_url() ),
			'nonce'       => wp_create_nonce( 'wp_rest' ),
			'title'       => 'Media Title',
			'description' => 'Media Description',
			'alt_text'    => 'Media Alt Text',
			'caption'     => 'Media Caption'
		)
	);
}


// Add Shortcode
function dropzonejs_wp_rest_api_shortcode( $atts ) {

	//user can ?
	if ( ! is_user_logged_in() || !current_user_can( 'upload_files' ) ) {
		return;
	}

	$url   = rest_url() . 'wp/v2/media/'; // dropzonejs will not accept an empty url
	return <<<ENDFORM
<div id="dropzone-wordpress-rest-api"><form action="$url" class="dropzone needsclick dz-clickable" id="dropzone-wordpress-rest-api-form">
	<div class="dz-message needsclick">
		Drop files here or click to upload.<br>
		<span class="note needsclick">(Files are uploaded to uploads/yyyy/mm)</span>
  	</div>
	<input type='hidden' name='action' value='submit_dropzonejs'>
</form></div>
ENDFORM;

}