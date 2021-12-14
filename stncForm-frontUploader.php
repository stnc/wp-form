<?php // used here only for enabling syntax highlighting. Leave this out if it's already included in your plugin file.

// Fires after WordPress has finished loading, but before any headers are sent.



//--------------
// define the actions for the two hooks created, first for logged in users and the next for logged out users
add_action("wp_ajax_my_user_like", "my_user_like");
add_action("wp_ajax_nopriv_my_user_like", "my_user_like");

// define the function to be fired for logged in users
function my_user_like()
{

  // nonce check for an extra layer of security, the function will exit if it fails
  if (!wp_verify_nonce($_REQUEST['nonce'], "my_user_like_nonce")) {
    exit("Woof Woof Woof");
  }


  // Throws a message if no file is selected
  if (!$_FILES['file']['name']) {
    wp_die(esc_html__('Please choose a file', 'theme-text-domain'));
  }

  $allowed_extensions = array('jpg', 'jpeg', 'png', 'mp4');
  $file_type = wp_check_filetype($_FILES['file']['name']);
  $file_extension = $file_type['ext'];

  // Check for valid file extension
  if (!in_array($file_extension, $allowed_extensions)) {
    wp_die(sprintf(esc_html__('Invalid file extension, only allowed: %s', 'theme-text-domain'), implode(', ', $allowed_extensions)));
  }

  $file_size = $_FILES['file']['size'];
  $allowed_file_size = 512000 * 50; // Here we are setting the file size limit to 500 KB = 500 × 1024

  // Check for file size limit
  if ($file_size >= $allowed_file_size) {
    wp_die(sprintf(esc_html__('File size limit exceeded, file size should be smaller than %d KB', 'theme-text-domain'), $allowed_file_size / 1000));
  }

  // These files need to be included as dependencies when on the front end.
  require_once(ABSPATH . 'wp-admin/includes/image.php');
  require_once(ABSPATH . 'wp-admin/includes/file.php');
  require_once(ABSPATH . 'wp-admin/includes/media.php');

  // Let WordPress handle the upload.
  // Remember, 'wpcfu_file' is the name of our file input in our form above.
  // Here post_id is 0 because we are not going to attach the media to any post.
  $attachment_id = media_handle_upload('file', 0);

  if (is_wp_error($attachment_id)) {
    // There was an error uploading the image.
    wp_die($attachment_id->get_error_message());
  } else {
    echo     $attachment_id;
    // We will redirect the user to the attachment page after uploading the file successfully.
    // wp_redirect( get_the_permalink( $attachment_id ) );
    exit;
  }
}

// define the function to be fired for logged out users
function please_login()
{
  echo "You must log in to like";
  die();
}


// user registration login form
function stncForm_registration_form()
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
			$output = stncForm_registration_form_fields();
		} else {
			$output = __('User registration is not enabled');
		}
*/
  $output = stncForm_registration_form_fields();
  return $output;
}
add_shortcode('StncForm_RegisterForm', 'stncForm_registration_form');
// [StncForm_RegisterForm] --- kullanım örneği


// registration form fields
function stncForm_registration_form_fields()
{
  ob_start();


  $nameLastname        = isset($_POST["nameLastname"]) ? sanitize_text_field($_POST["nameLastname"]) : "";
  $companyName        = isset($_POST["companyName"]) ? sanitize_text_field($_POST["companyName"]) : "";
  $phone        = isset($_POST["phone"]) ? sanitize_text_field($_POST["phone"]) : "";
  $mailAdress        = isset($_POST["mailAdress"]) ? sanitize_text_field($_POST["mailAdress"]) : "";
  $webSite        = isset($_POST["webSite"]) ? sanitize_text_field($_POST["webSite"]) : "";



  if (!empty($_POST['travelBan'])) {
    foreach ($_POST['travelBan'] as $check) {
      echo $check; //echoes the value set in the HTML form for each checked checkbox.
      //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
      //in your case, it would echo whatever $row['Report ID'] is equivalent to.
    }
  }


  $select_jobs   = 1; //sanitize_text_field($_POST["select_jobs"]);	



  $add_date   = current_time('mysql', 1);

  if (isset($_GET['successMsg'])) {
?>

    <h3 class="stncForm_header"><?php _e('Teşekkürler'); ?></h3>

  <?php  } else {
  ?>
    <?php
    // show any error messages after form submission
    stncForm_show_error_messages(); ?>




    <div class="container">
      <div class="row">
        <div class="col-md-12 stncVolunteer">
          <div id="templateBody" class="bodyContent">
            <div class="box box-primary">
              <!-- /.box-header -->
              <!-- form start -->

              <form role="form" method="POST" enctype="multipart/form-data" action="<?php the_permalink(); ?>" id="stncForm_registration_form" class="stncForm_form">
                <div class="box-body">

                  <div class="form-group">
                    <label for="nameLastname"><?php _e('Adınız'); ?></label>
                    <input class="form-control" id="nameLastname" name="nameLastname" value="<?php echo $nameLastname ?>" placeholder="<?php _e('Name'); ?>">
                  </div>

                  <div class="form-group">
                    <label for="companyName"><?php _e('Şirket Adı'); ?></label>
                    <input class="form-control" id="companyName" name="companyName" value="<?php echo $companyName ?>" placeholder="<?php _e('Şirket Adı'); ?>">
                  </div>

                  <div class="form-group">
                    <label for="phone"><?php _e('Phone Number'); ?></label>
                    <input class="form-control" id="phone" name="phone" value="<?php echo $phone ?>" placeholder="<?php _e('Phone Number:'); ?>">
                  </div>

                  <div class="form-group">
                    <label for="mailAdress"><?php _e('Mail'); ?></label>
                    <input class="form-control" name="mailAdress" id="mailAdress" value="<?php echo $mailAdress ?>" placeholder="<?php _e('Mail Adresiniz: '); ?>">
                  </div>


                  <div class="form-group">
                    <label for="webSite"><?php _e('Web site'); ?></label>
                    <input class="form-control" name="webSite" id="webSite" value="<?php echo $webSite ?>" placeholder="<?php _e('web site'); ?>">
                  </div>


                  <div class="form-group">
                    <label for="note">Kayseri'ye seyahat etmenize neden olacak herhangi bir engel var mıdır?</label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input name="travelBan" value="seyahatEngeliVar" type="radio"> <?php _e('evet var'); ?>
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input name="travelBan" value="seyahatEngeliYok" type="radio"> <?php _e('hayır yok'); ?>
                    </label>
                  </div>





                  <div style="    border: 2px dashed #0087F7;" class="dropzone needsclick dz-clickable">
                    <div class="dz-message needsclick">



                      <div class="alert alert-warning" role="alert"><button type="button" class="dz-button">
                          Resimleri sürükle bırak ile bu alana atınız veya dosya/ resim yükleme butonunu kullanınız</button></div>

                      <br>
            



                      <div id="actions" class="row">

                        <div class="col-lg-7">
                          <span class=" btn btn-success text-white  fileinput-button">
                        
                            <span>Dosya Ekle...</span>
                          </span>


                          <a class="btn btn-primary text-white start">
                          &nbsp;Yüklemeyi başlat
                          </a>
                          <!-- <a class="btn btn-warning   cancel">
                       
                            <span>Yüklemeyi İptal Et</span>
                          </a> -->
                        </div>

                        <div class="col-lg-5">
                          <!-- The global file processing state -->
                          <span class="fileupload-process">
                            <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                              <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                            </div>
                          </span>
                        </div>

                        <div class="table-upload table table-striped files" id="previews">

                          <div id="template" class="file-row">
                            <!-- This is used as the file preview template -->
                            <div>
                              <span class="preview"><img data-dz-thumbnail /></span>
                            </div>
                            <div>
                              <p class="name" data-dz-name></p>
                              <strong class="error text-danger" data-dz-errormessage></strong>
                            </div>
                            <div>
                              <p class="size" data-dz-size></p>
                              <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                              </div>
                            </div>
                            <div>

                             <a style="display: none;" class="btn btn-primary text-white start">
                               Başlat
                              </a>
                              <!--  <a data-dz-remove class="btn btn-danger text-white  cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>İptal</span>
                              </a> -->
                              <!-- <a data-dz-remove class="btn btn-danger delete">
                          <i class="glyphicon glyphicon-trash"></i>
                          <span>Sil</span>
                        </a> -->
                            </div>
                          </div>

                        </div>


                      </div>
                    </div>
                  </div>





                  <script>
                    Dropzone.autoDiscover = false;

                    // Get the template HTML and remove it from the doument
                    var previewNode = document.querySelector("#template");
                    previewNode.id = "";
                    var previewTemplate = previewNode.parentNode.innerHTML;
                    previewNode.parentNode.removeChild(previewNode);

                    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
                      url: myAjax.ajaxurl,

                      init: function() {
                        this.on("sending", function(file, xhr, formData) {
                          formData.append("action", "my_user_like");
                          formData.append("nonce", "<?php echo wp_create_nonce("my_user_like_nonce") ?>");
                          console.log(formData)
                        });

                      },
                      success: function(file, response) {
                        alert (response);
                        setTimeout(function() {
                        }, 2000);
                      },
                      addRemoveLinks: true,
                      maxFiles: 1, //https://www.infinetsoft.com/Post/How-to-set-limits-for-file-upload-in-dropzone-js/2534#.YIvbS2YzbJ9
                      maxFilesize: 10, //max file size in MB,

                      // acceptedFiles:  "{{ fileConfig.fileType }}", 
                      acceptedFiles: "video/mp4,video/webm",
                      thumbnailWidth: 80,
                      thumbnailHeight: 80,
                      parallelUploads: 1,
                      previewTemplate: previewTemplate,
                      autoQueue: false, // Make sure the files aren't queued until manually added
                      previewsContainer: "#previews", // Define the container to display the previews
                      clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
                    });

                    myDropzone.on("addedfile", function(file) {
                      // Hookup the start button

                      file.previewElement.querySelector(".start").onclick = function() {
                        myDropzone.enqueueFile(file);
                      };
                    });


                    // Update the total progress bar
                    myDropzone.on("totaluploadprogress", function(progress) {
                      document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
                    });

                    myDropzone.on("sending", function(file) {
                      // Show the total progress bar when upload starts
                      document.querySelector("#total-progress").style.opacity = "1";
                      // And disable the start button
                      file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
                    });

                    // Hide the total progress bar when nothing's uploading anymore
                    myDropzone.on("queuecomplete", function(progress) {
                      document.querySelector("#total-progress").style.opacity = "0";
                    });

                    // Setup the buttons for all transfers
                    // The "add files" button doesn't need to be setup because the config
                    // `clickable` has already been specified.
                    document.querySelector("#actions .start").onclick = function() {
                    
                      myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
            

                    };


                    // document.querySelector("#actions .cancel").onclick = function() {
                    //   //alert("ipral")
                    //   myDropzone.removeAllFiles(true);
                    // };

                    //TODO: jquery document ile yapılması gerekiyor 
                    //  document.querySelector("#previews .delete").onclick = function () {
                    //      myDropzone.removeAllFiles(true);
                    //  };
                  </script>




                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <input type="hidden" name="stncForm_register_nonce" value="<?php echo wp_create_nonce('stncForm-register-nonce'); ?>" />
                  <?php // wp_nonce_field( 'upload_wpcfu_file', 'wpcfu_nonce', true, false ); 
                  ?>
                  <input type="hidden" name="stncForm_user_login" value="<?php echo wp_create_nonce('stncForm-register-nonce'); ?>" />
                  <button type="submit" class="btn btn-primary"><?php _e('Gönder'); ?></button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>





<?php

    return ob_get_clean();
  }
}



// register a new user
function stncForm_add_new_member()
{

  global $wpdb;

  if (isset($_POST["stncForm_user_login"]) && wp_verify_nonce($_POST['stncForm_register_nonce'], 'stncForm-register-nonce')) {
    $nameLastname        =  sanitize_text_field($_POST["nameLastname"]);
    $companyName    = sanitize_text_field($_POST["companyName"]);
    $phone     = sanitize_text_field($_POST["phone"]);
    $mailAdress   = sanitize_text_field($_POST["mailAdress"]);

    $webSite    = sanitize_text_field($_POST["webSite"]);
    $phone        = sanitize_text_field($_POST["phone"]);
    $travelBan   = 1; //sanitize_text_field($_POST["travelBan"]);	

    $add_date   = current_time('mysql', 1);




    if (empty($nameLastname)) {
      // passwords do not match
      stncForm_errors()->add('empty', __('Lütfen adınızı giriniz'));
    }


    if (!is_email($mailAdress)) {
      stncForm_errors()->add('invalidMail', __('Lütfen doğru bir mail adresi giriniz'));
    }


    $errors = stncForm_errors()->get_error_messages();



    $current_slug = add_query_arg(array(), @$wpdb->request);

    // only create the user in if there are no errors
    if (empty($errors)) {


      $tableNameMain = $wpdb->prefix . 'stnc_teknoparkform';
      $insertResult =  $wpdb->insert($tableNameMain, array(

        'name_lastname' => $nameLastname,
        'company_name' => $companyName,
        'phone' => $phone,
        'mail_adress' => $mailAdress,
        'web_site' => $webSite,
        'phone' => $phone,
        'comment' => " ",
        'travel_ban' => $travelBan,
        'media_id' => 1,
        'user_ip' => get_current_user_id(),
        'add_date' => date("Y-m-d h:i:s"),

      ));

      if ($insertResult) {

        //FIXME: burada permalink mi ne var ona bakıcalak
        /**
 	  $structure = get_option( 'permalink_structure' );
	  print_r($structure);

         */
        wp_redirect($current_slug . "?successMsg");
        exit;
      }
    }
  }
}
add_action('init', 'stncForm_add_new_member');


