<?php // used here only for enabling syntax highlighting. Leave this out if it's already included in your plugin file.

// Fires after WordPress has finished loading, but before any headers are sent.

//form ıcın css yukleme yeri 
//https://www.sanwebe.com/2014/08/css-html-forms-designs
///https://gist.github.com/aziz-blr/fdef65aed6d60729b47f1f52bd6bd0af
//https://www.jqueryscript.net/other/Drag-Drop-File-Uploader-Plugin-dropzone.html
//--------------



// registration form fields
function stncForm_VideUploadForm_fields()
{
  ob_start();


  $nameLastname        = isset($_POST["nameLastname"]) ? sanitize_text_field($_POST["nameLastname"]) : "";
  $companyName        = isset($_POST["companyName"]) ? sanitize_text_field($_POST["companyName"]) : "";
  $phone        = isset($_POST["phone"]) ? sanitize_text_field($_POST["phone"]) : "";
  $mailAdress        = isset($_POST["mailAdress"]) ? sanitize_text_field($_POST["mailAdress"]) : "";
  $webSite        = isset($_POST["webSite"]) ? sanitize_text_field($_POST["webSite"]) : "";






  $add_date   = current_time('mysql', 1);

  if (isset($_GET['successMsg'])) {
?>

    <h3 class="stncForm_header"><?php _e('Teşekkürler'); ?></h3>

  <?php  } else {
  ?>



    <div class="container">
      <div class="row">
        <div class="col-md-12 stncVolunteer">
          <div id="templateBody" class="bodyContent">
            <div class="box box-primary">
              <!-- /.box-header -->
              <!-- form start -->

              <!-- <form role="form" method="POST" enctype="multipart/form-data" action="<?php the_permalink(); ?>" id="stncForm_VideUploadForm"> -->
              <div class="box-body" id="stncForm_VideUploadForm">
                <div class="form-style-2-heading">Erciyes Teknopark Kayıt Formu</div>

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
                    <label>Web Sitesi </label>
                    <input type="text" name="webSite" id="webSite" value="<?php echo $webSite ?>" class="field-long" />
                  </li>

                  <li>
                    <label>Kayseri'ye seyahat etmenize neden olacak herhangi bir engel var mıdır?</label>
                    <select name="travel_ban" id="travel_ban" class="field-select">
                      <option value="evet">evet</option>
                      <option value="hayir">hayır</option>

                    </select>
                  </li>



                  <br>

                  <!-- <li>
                      <input type="submit" value="Submit" />
                    </li> -->
                </ul>

                <div id="upload-group" class="alert alert-danger" style="display: none;" role="alert"></div>

                <div style="    border: 2px dashed #0087F7;" class="dropzone needsclick dz-clickable">
                  <div class="dz-message needsclick">



                    <div class="alert alert-warning" role="alert"><button type="button" class="dz-button">
                        Dosyaları sürükle bırak ile bu alana atınız veya dosya ekle butonunu kullanınız, <br> maksimum dosya boyutu 100 MB dan büyük olamaz</button></div>
                        PDF,ppt,ppx,mp4,webm dosyaları yüklenebilir.
                    <br>




                    <div id="actions" class="row">

                      <div class="col-lg-7">
                        <span class=" btn btn-success text-white  fileinput-button">

                          <span>Dosya Ekle...</span>
                        </span>

                        <a class="btn btn-primary text-white start" style="display: none!important">
                          &nbsp;Başlat
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






                <div id="stncfooter" class="box-footer">
                  <input type="hidden" name="mediaIsExist" id="mediaIsExist" value="0" />
                  <input type="hidden" name="postId" id="postId" />
                  <input type="hidden" name="stncForm_register_nonce" id="stncForm_register_nonce" value="<?php echo wp_create_nonce('stncForm-register-nonce'); ?>" />
                  <?php  echo wp_nonce_field( 'upload_wpcfu_file', 'wpcfu_nonce', true, false ); 
                  ?>
                  <input type="hidden" name="stncForm_user_login" id="stncForm_user_login" value="<?php echo wp_create_nonce('stncForm-register-nonce'); ?>" />
                  <!-- <button type="submit" class="btn btn-primary"><?php _e('Gönder'); ?></button> -->
                  <a id="ders" class="btn btn-primary text-white stnc-start">
                    &nbsp;Gönder
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

      // Get the template HTML and remove it from the doument
      var previewNode = document.querySelector("#template");
      previewNode.id = "";
      var previewTemplate = previewNode.parentNode.innerHTML;
      previewNode.parentNode.removeChild(previewNode);

      var myDropzone = new Dropzone("#stncForm_VideUploadForm", { // Make the whole body a dropzone
        url: myAjax.ajaxurl,

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
        addRemoveLinks: true,
        maxFiles: 1, //https://www.infinetsoft.com/Post/How-to-set-limits-for-file-upload-in-dropzone-js/2534#.YIvbS2YzbJ9
        maxFilesize: 100, //max file size in MB,
        acceptedFiles: "video/mp4,video/webm,	application/pdf,	application/vnd.ms-powerpoint,	application/vnd.openxmlformats-officedocument.presentationml.presentation",
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 1,
        previewTemplate: previewTemplate,
        autoProcessQueue: true,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
      });

      myDropzone.on("addedfile", function(file) {
        // Hookup the start button       
        jQuery("#mediaIsExist").val("1");
        file.previewElement.querySelector(".start").onclick = function() {
          myDropzone.enqueueFile(file);
        };
      });

      myDropzone.on("complete", function(file) {
        jQuery("#stncForm_VideUploadForm").html('<div class="alert alert-success">Teşekkür ederiz, Bilgileriniz Başarı İle Gönderildi</div>');

        myDropzone.removeFile(file);
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
            travel_ban: jQuery("#travel_ban").val(),
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




              } else {
                // myDropzone.processQueue();

                var mediaIsExist = jQuery("#mediaIsExist").val();
                if (mediaIsExist != 0) {
                  myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
                  jQuery("#postId").val(data.id)
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
    $travel_ban        = isset($_POST["travel_ban"]) ? sanitize_text_field($_POST["travel_ban"]) : "";


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

    if (!is_email($mailAdress)) {
      $errors['mailAdress'] = 'Lütfen doğru bir mail adresi giriniz';
    }


    // if (empty($webSite)) {
    //   $errors['webSite'] = 'Lütfen web site adresinizi giriniz';
    // }


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
        'travel_ban' => $travel_ban,
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
