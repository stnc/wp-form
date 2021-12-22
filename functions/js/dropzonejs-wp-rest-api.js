/*
Uploading images is a two step process (from https://github.com/WP-API/WP-API/issues/1768#issuecomment-160540932):
	POST the data to /wp/v2/media - this can either be as the request body, or in multipart format. This will upload the file, and give you a 201 Created response with a Location header. This header points to the post object for the attachment that has just been created.
	PUT the post data to the endpoint returned in the Location header (which will look something like /wp/v2/media/{id}).
I do step 2 (PUT), if POST is a success, in myDropzone.on("success", function(file, response){}
*/

// dropzoneWordpressRestApiForm is the configuration for the element that has an id attribute
// with the value dropzone-wordpress-rest-api-form (or dropzoneWordpressRestApiForm)
Dropzone.options.dropzoneWordpressRestApiForm = {
    //acceptedFiles: "image/*", // all image mime types
    acceptedFiles: ".mp4", // only .jpg files
    maxFiles: 1,
    uploadMultiple: false,
    maxFilesize: 100, // 5 MB
    init: function() {
        console.group('dropzonejs-wp-rest-api:');
        var myDropzone = this; // closure
        myDropzone.on("sending", function(file, xhr, data) {
            console.log("file: %O", file);

            //add nonce, from: http://v2.wp-api.org/guide/authentication/
            xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
        });
        // myDropzone.on("processing", function(file) {
        //   this.options.url = WP_API_Settings.root + 'wp/v2/media/';
        // });
        myDropzone.on("error", function(file, error, xhr) {
            console.error("ERROR: %o", error);
            console.groupEnd();
        });
        myDropzone.on("success", function(file, response) {
            console.log("success: %O", response);

            var id = response.id; // media ID

            // from: http://blog.garstasio.com/you-dont-need-jquery/ajax/
            var xhr = new XMLHttpRequest();
            xhr.open('PUT', WP_API_Settings.root + 'wp/v2/media/' + id);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var userInfo = JSON.parse(xhr.responseText);
                    console.log("put: %O", userInfo);
                    console.groupEnd();
                }
            };
            xhr.send(JSON.stringify({
                title: {
                    raw: WP_API_Settings.title,
                    rendered: WP_API_Settings.title
                },
                description: WP_API_Settings.description,
                alt_text: WP_API_Settings.alt_text,
                caption: WP_API_Settings.caption
            }));
        });
    }
};