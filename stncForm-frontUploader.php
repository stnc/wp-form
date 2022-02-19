<?php // used here only for enabling syntax highlighting. Leave this out if it's already included in your plugin file.

// Fires after WordPress has finished loading, but before any headers are sent.

//form ıcın css yukleme yeri 
//https://www.sanwebe.com/2014/08/css-html-forms-designs
///https://gist.github.com/aziz-blr/fdef65aed6d60729b47f1f52bd6bd0af
//https://www.jqueryscript.net/other/Drag-Drop-File-Uploader-Plugin-dropzone.html
//--------------

//https://onyxdev.net/snippets-item/dropzonejs-example-with-translations-custom-preview-and-upload-delete-file-with-php/    fğzel ornek 

// registration form fields
function stncForm_VideUploadForm_fields()
{
  ob_start();


  $nameLastname        = isset($_POST["nameLastname"]) ? sanitize_text_field($_POST["nameLastname"]) : "";
  $companyName        = isset($_POST["companyName"]) ? sanitize_text_field($_POST["companyName"]) : "";
  $phone        = isset($_POST["phone"]) ? sanitize_text_field($_POST["phone"]) : "";
  $mailAdress        = isset($_POST["mailAdress"]) ? sanitize_text_field($_POST["mailAdress"]) : "";
  $webSite        = isset($_POST["webSite"]) ? sanitize_text_field($_POST["webSite"]) : "";
  $mediaIsExist        = isset($_POST["mediaIsExist"]) ? sanitize_text_field($_POST["mediaIsExist"]) : "";






  $add_date   = current_time('mysql', 1);

  if (isset($_GET['successMsg'])) {
?>

    <h3 class="stncForm_header"><?php _e('Teşekkürler'); ?></h3>

  <?php  } else {
  ?>



    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="bodyContent">
            <div class="box box-primary">
              <!-- /.box-header -->
              <!-- form start -->

              <!-- <form role="form" method="POST" enctype="multipart/form-data" action="<?php the_permalink(); ?>" id="stncForm_VideUploadForm"> -->
              <div class="box-body" id="stncForm_VideUploadForm">
                <div class="form-style-2-heading">Summit Erciyes Girişimci Başvuru Formu</div>

                <ul class="form-style-1">

                  <li id="nameLastname-group" class="form-group">
                    <label>Ad Soyad <span class="required">*</span></label>
                    <input type="text" name="nameLastname" id="nameLastname" value="<?php echo $nameLastname ?>" class="field-long" />
                  </li>

                  <li id="companyName-group" class="form-group">
                    <label>Şirket <span class="required">*</span></label>
                    <input type="text" name="companyName" id="companyName" value="<?php echo $companyName ?>" class="field-long" />
                  </li>

                  <li id="phone-group" class="form-group">
                    <label>Telefon <span class="required">*</span></label>
                    <input type="text" name="phone" id="phone" value="<?php echo $phone ?>" class="field-long" />
                  </li>

                  <li id="mailAdress-group" class="form-group">
                    <label>Mail <span class="required">*</span></label>
                    <input type="email" name="mailAdress" id="mailAdress" value="<?php echo $mailAdress ?>" class="field-long" />
                  </li>

                  <li id="webSite-group" class="form-group">

                    <label>Web Sitesi <span class="required">*</span></label>
                    <input type="text" name="webSite" id="webSite" value="<?php echo $webSite ?>" class="field-long" />
                  </li>


                  <li id="mediaIsExist-group" class="form-group">


</li>

                  <br>

                  <!-- <li>
                      <input type="submit" value="Submit" />
                    </li> -->
                </ul>




                <strong>Ön değerlendirme işlemi için sunum ya da video yükleyebilirsiniz.</strong>
                <div class="invalid-feedback-stnc" style="display: none;color:red">Lütfen Dosya Yükleyiniz</div>
                <!--begin::Dropzone-->
                <div class="dropzone dropzone-queue mb-2" id="kt_dropzonejs_example_2">
                  <!--begin::Controls-->
                  <div class="dropzone-panel mb-lg-0 mb-2">
                    <a class="dropzone-select btn btn-sm btn-warning me-2">Dosya Ekle</a>
                    <a style="display: none!important;" class="dropzone-upload btn btn-sm btn-light-primary me-2">Upload All</a>
                    <a style="display: none!important;" class="dropzone-remove-all btn btn-sm btn-light-primary">Remove All</a>
                  </div>
                  <!--end::Controls-->
                  <!--begin::Items-->
                  <div class="dropzone-items wm-200px">
                    <div class="dropzone-item" style="display:none">
                      <!--begin::File-->
                      <div class="dropzone-file">
                        <div class="dropzone-filename" title="some_image_file_name.jpg">
                          <span data-dz-name="">some_image_file_name.jpg</span>
                          <strong>(
                            <span data-dz-size="">340kb</span>)</strong>
                        </div>
                        <div class="dropzone-error" data-dz-errormessage=""></div>
                      </div>
                      <!--end::File-->
                      <!--begin::Progress-->
                      <div class="dropzone-progress">
                        <div class="progress">
                          <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress=""></div>
                        </div>
                      </div>
                      <!--end::Progress-->
                      <!--begin::Toolbar-->
                      <div class="dropzone-toolbar">
                        <span class="dropzone-start">
                          <i class="bi bi-play-fill fs-3"></i>
                        </span>
                        <span class="dropzone-cancel" data-dz-remove="" style="display: none;">
                          <i class="bi bi-x fs-3"></i>
                        </span>
                        <span class="dropzone-delete" data-dz-remove="">
                          <i class="bi bi-x fs-1"></i>
                        </span>
                      </div>
                      <!--end::Toolbar-->
                    </div>
                  </div>
                  <!--end::Items-->
                </div>
                <!--end::Dropzone-->

                <!--end::Form-->






                <div id="stncfooter" class="box-footer">
                  <input type="hidden" name="mediaIsExist" id="mediaIsExist" value="0" />
                  <input type="hidden" name="postId" id="postId" />
                  <input type="hidden" name="stncForm_register_nonce" id="stncForm_register_nonce" value="<?php echo wp_create_nonce('stncForm-register-nonce'); ?>" />
                  <?php // wp_nonce_field( 'upload_wpcfu_file', 'wpcfu_nonce', true, false ); 
                  ?>
                  <input type="hidden" name="stncForm_user_login" id="stncForm_user_login" value="<?php echo wp_create_nonce('stncForm-register-nonce'); ?>" />
                  <!-- <button type="submit" class="btn btn-primary"><?php _e('Gönder'); ?></button> -->
                  <a id="ders" class="btn btn-primary text-white stnc-start">
                    &nbsp;Yüklemeyi başlat ve Gönder
                  </a>
                </div>
                <!-- </form> -->

              </div>
              <!-- /.box-body -->


            </div>
          </div>
        </div>

      </div>

    </div>


    <script>
      Dropzone.autoDiscover = false;

      const id = "#kt_dropzonejs_example_2";
      const dropzone = document.querySelector(id);

      // set the preview element template
      var previewNode = dropzone.querySelector(".dropzone-item");
      previewNode.id = "";
      var previewTemplate = previewNode.parentNode.innerHTML;
      previewNode.parentNode.removeChild(previewNode);

      var myDropzone = new Dropzone(id, { // Make the whole body a dropzone
        url: myAjax.ajaxurl,
        addRemoveLinks: true,

        acceptedFiles: "video/mp4,video/webm,	application/pdf,	application/vnd.ms-powerpoint,	application/vnd.openxmlformats-officedocument.presentationml.presentation",
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 1,
        previewTemplate: previewTemplate,
        autoProcessQueue: true,
        //acceptedFiles: "image/*", // all image mime types
        //acceptedFiles: ".mp4", // only .jpg files
        maxFiles: 1,
        uploadMultiple: false,
        maxFilesize: 100, // 5 MB
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: id + " .dropzone-items", // Define the container to display the previews
        clickable: id + " .dropzone-select", // Define the element that should be used as click trigger to select files.
        init: function() {
          this.on("sending", function(file, xhr, formData) {

            formData.append("postID", jQuery("#postId").val());
            formData.append("action", "stncFormSent");
            formData.append("nonce", "<?php echo wp_create_nonce("stncFormSent_nonce") ?>");
            console.log(formData)
          });

        },

        success: function(file, data) {
          console.log(data)
          var res = JSON.parse(data);
          if (!res.success) {
            if (res.errors.hata) {
              jQuery("#upload-group").show();
              jQuery("#upload-group").addClass("is-invalid");
              jQuery("#upload-group").html(
                '<div class="invalid-feedback">' + res.errors.hata + "</div>"
              );
            }
          }
          // var res = JSON.parse(response);
          // if (res.error) {
          //   jQuery(file.previewTemplate).children('.dz-error-mark').css('opacity', '1')
          // }

        },

      });


      myDropzone.on("addedfile", function(file) {
        // Hookup the start button
        file.previewElement.querySelector(id + " .dropzone-start").onclick = function() {
          myDropzone.enqueueFile(file);
        };
        const dropzoneItems = dropzone.querySelectorAll('.dropzone-item');
        dropzoneItems.forEach(dropzoneItem => {
          dropzoneItem.style.display = '';
        });
        jQuery("#mediaIsExist").val("1");
        dropzone.querySelector('.dropzone-upload').style.display = "inline-block";
        dropzone.querySelector('.dropzone-remove-all').style.display = "inline-block";
      });

      myDropzone.on("complete", function(file) {
        jQuery("#stncForm_VideUploadForm").html('<div class="alert alert-success">Teşekkür Ederiz, Bilgileriniz Başarı İle Gönderildi</div>');

        myDropzone.removeFile(file);
      });


      // Update the total progress bar
      myDropzone.on("totaluploadprogress", function(progress) {
        const progressBars = dropzone.querySelectorAll('.progress-bar');
        progressBars.forEach(progressBar => {
          progressBar.style.width = progress + "%";
        });
      });

      myDropzone.on("sending", function(file) {
        // Show the total progress bar when upload starts
        const progressBars = dropzone.querySelectorAll('.progress-bar');
        progressBars.forEach(progressBar => {
          progressBar.style.opacity = "1";
        });
        // And disable the start button
        file.previewElement.querySelector(id + " .dropzone-start").setAttribute("disabled", "disabled");
      });

      // Hide the total progress bar when nothing's uploading anymore
      myDropzone.on("complete", function(progress) {
        const progressBars = dropzone.querySelectorAll('.dz-complete');

        setTimeout(function() {
          progressBars.forEach(progressBar => {
            progressBar.querySelector('.progress-bar').style.opacity = "0";
            progressBar.querySelector('.progress').style.opacity = "0";
            progressBar.querySelector('.dropzone-start').style.opacity = "0";
          });
        }, 300);
      });

      // Setup the buttons for all transfers
      // The "add files" button doesn't need to be setup because the config
      // `clickable` has already been specified.
      // Setup the buttons for all transfers
      dropzone.querySelector(".dropzone-upload").addEventListener('click', function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
      });


      // Setup the button for remove all files
      dropzone.querySelector(".dropzone-remove-all").addEventListener('click', function() {
        dropzone.querySelector('.dropzone-upload').style.display = "none";
        dropzone.querySelector('.dropzone-remove-all').style.display = "none";
        myDropzone.removeAllFiles(true);
      });

      // On all files completed upload
      myDropzone.on("queuecomplete", function(progress) {
        const uploadIcons = dropzone.querySelectorAll('.dropzone-upload');
        uploadIcons.forEach(uploadIcon => {
          uploadIcon.style.display = "none";
        });
      });

      // On all files removed
      myDropzone.on("removedfile", function(file) {
        if (myDropzone.files.length < 1) {
          dropzone.querySelector('.dropzone-upload').style.display = "none";
          dropzone.querySelector('.dropzone-remove-all').style.display = "none";
        }
      });

      jQuery(function($) {
        jQuery('#stncfooter .stnc-start').on('click', function(e) {

          e.preventDefault();
          e.stopPropagation();

          jQuery(".form-group").removeClass("is-invalid");
          jQuery(".invalid-feedback").remove();







          var formData = {
            nameLastname: jQuery("#nameLastname").val(),
            companyName: jQuery("#companyName").val(),
            phone: jQuery("#phone").val(),
            mailAdress: jQuery("#mailAdress").val(),
            webSite: jQuery("#webSite").val(),
            mediaIsExist: jQuery("#mediaIsExist").val(),
            stncForm_register_nonce: jQuery("#stncForm_register_nonce").val(),
            stncForm_user_login: jQuery("#stncForm_user_login").val(),

          };



          jQuery.ajax({
              type: "POST",
              url: "<?php the_permalink() ?>",
              data: formData,
              dataType: "json",
              encode: true,
            }).done(function(data) {
              if (!data.success) {
                if (data.errors.nameLastname) {

                  jQuery("#nameLastname-group").addClass("is-invalid");
                  jQuery("#nameLastname-group").append(
                    '<div class="invalid-feedback">' + data.errors.nameLastname + "</div>"
                  );
                }

                if (data.errors.companyName) {

                  jQuery("#companyName-group").addClass("is-invalid");
                  jQuery("#companyName-group").append(
                    '<div class="invalid-feedback">' + data.errors.companyName + "</div>"
                  );
                }


                if (data.errors.phone) {

                  jQuery("#phone-group").addClass("is-invalid");
                  jQuery("#phone-group").append(
                    '<div class="invalid-feedback">' + data.errors.phone + "</div>"
                  );
                }

                if (data.errors.mailAdress) {

                  jQuery("#mailAdress-group").addClass("is-invalid");
                  jQuery("#mailAdress-group").append(
                    '<div class="invalid-feedback">' + data.errors.mailAdress + "</div>"
                  );
                }

                if (data.errors.webSite) {

                  jQuery("#webSite-group").addClass("is-invalid");
                  jQuery("#webSite-group").append(
                    '<div class="invalid-feedback">' + data.errors.webSite + "</div>"
                  );
                }

               if (data.errors.mediaIsExist) {
                jQuery("#mediaIsExist-group").addClass("is-invalid");
                jQuery("#mediaIsExist-group").append(
                  '<div class="invalid-feedback">' + data.errors.mediaIsExist + "</div>"
                );
                }



              } else {
                // myDropzone.processQueue();

                var mediaIsExist = jQuery("#mediaIsExist").val();
                jQuery("#postId").val(data.id)
                if (mediaIsExist != 0) {
                  myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
                } else {
                  jQuery("#stncForm_VideUploadForm").html('<div class="alert alert-success">' + data.message + "</div>");
                }

              }
            })
            .fail(function(data) {
              jQuery("#stncForm_VideUploadForm").html(
                '<div class="alert alert-danger">Could not reach server, please try again later.</div>'
              );
            });

          // }





        });
      });



      // document.querySelector("#actions .cancel").onclick = function() {
      //   //alert("ipral")
      //   myDropzone.removeAllFiles(true);
      // };

      //TODO: jquery document ile yapılması gerekiyor 
      //  document.querySelector("#previews .delete").onclick = function () {
      //      myDropzone.removeAllFiles(true);
      //  };
    </script>




<?php

    return ob_get_clean();
  }
}



// register a new user
function stncForm_add_new_member()
{



  if (isset($_POST["stncForm_user_login"]) && wp_verify_nonce($_POST['stncForm_register_nonce'], 'stncForm-register-nonce')) {

    $errors = [];
    $data = [];

    $nameLastname        = isset($_POST["nameLastname"]) ? sanitize_text_field($_POST["nameLastname"]) : "";
    $companyName        = isset($_POST["companyName"]) ? sanitize_text_field($_POST["companyName"]) : "";
    $phone        = isset($_POST["phone"]) ? sanitize_text_field($_POST["phone"]) : "";
    $mailAdress        = isset($_POST["mailAdress"]) ? sanitize_text_field($_POST["mailAdress"]) : "";
    $webSite        = isset($_POST["webSite"]) ? sanitize_text_field($_POST["webSite"]) : "";
    $mediaIsExist        = isset($_POST["mediaIsExist"]) ? sanitize_text_field($_POST["mediaIsExist"]) : "";


    if (empty($nameLastname)) {
      $errors['nameLastname'] = 'Lütfen Ad Soyad alanını doldurunuz.';
    }

    if (empty($companyName)) {
      $errors['companyName'] = 'Lütfen Şirket İsmini giriniz';
    }

    if (empty($phone)) {
      $errors['phone'] = 'Lütfen telefon numaranızı giriniz';
    }
    if (empty($mailAdress)) {
      $errors['mailAdress'] = 'Lütfen mail adresinizi giriniz';
    }

    if (empty($mediaIsExist)) {
      $errors['mediaIsExist'] = 'Lütfen dosya yükleyiniz';
    }

    if (!is_email($mailAdress)) {
      $errors['mailAdress'] = 'Lütfen doğru bir mail adresi giriniz';
    }


    if (empty($webSite)) {
      $errors['webSite'] = 'Lütfen web site adresinizi giriniz';
    }


    if (!empty($errors)) {
      $data['success'] = false;
      $data['errors'] = $errors;
    } else {
      $data['success'] = true;
      $data['message'] = 'Bilgileriniz Gönderildi, Teşekkür ederiz';

      global $wpdb;
      $tableNameMain = $wpdb->prefix . 'stnc_teknoparkform';
      $wpdb->insert($tableNameMain, array(

        'namelastname' => $nameLastname,
        'company_name' => $companyName,
        'phone' => $phone,
        'mail_adress' => $mailAdress,
        'web_site' => $webSite,
        'phone' => $phone,
        'comment' => " ",
        'travel_ban' => "0",
        'media_id' => 0,
        'user_ip' => stnc_GetIP(),
        'add_date' => current_time('mysql', 1),

      ));
      $data['id'] =  $wpdb->insert_id;


      /*
      ajax olmasa kullanırdım 
        $current_slug = add_query_arg(array(), @$wpdb->request);
            if ($insertResult) {
              wp_redirect($current_slug . "?successMsg");
              exit;
            }*/
    }


    echo json_encode($data);
    die;


    // if (empty($nameLastname)) {
    //   stncForm_errors()->add('empty', __('Lütfen adınızı giriniz'));
    // }
    // if (!is_email($mailAdress)) {
    //   stncForm_errors()->add('invalidMail', __('Lütfen doğru bir mail adresi giriniz'));
    // }
    // $errors = stncForm_errors()->get_error_messages();



  }
}
add_action('init', 'stncForm_add_new_member');



function stnc_GetIP()
{
  foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
    if (array_key_exists($key, $_SERVER) === true) {
      foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
          return $ip;
        }
      }
    }
  }
}
