/*jQuery(document).ready(function($) {
    $(document).on("click", ".upload_image_button", function() {

        jQuery.data(document.body, 'prevElement', $(this).prev());
        jQuery.data(document.body, 'nextElement', $(this).next());
        window.send_to_editor = function(html) {
            var imgurl = jQuery('img',html).attr('src');
            var inputText = jQuery.data(document.body, 'prevElement');
            var showImage = jQuery.data(document.body, 'nextElement');
            
            if(inputText != undefined && inputText != '')
            {
                inputText.val(imgurl);
                showImage.attr('src', imgurl);
            }

            tb_remove();
        };

        tb_show('Insert Image', 'media-upload.php?type=image&TB_iframe=true', false);

        return false;
    });
});
*/
jQuery(document).ready(function($){
    $('.upload_image_button').click(function(e) {
        jQuery.data(document.body, 'prevElement', $(this).prev());
        jQuery.data(document.body, 'nextElement', $(this).next());
        jQuery.data(document.body, 'previewImage', $(this).attr('data-target'));
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            var inputText = jQuery.data(document.body, 'prevElement');
            var showImage = jQuery.data(document.body, 'nextElement');
            var imgPreview = $('#'+jQuery.data(document.body, 'previewImage'));
            //console.log(imgPreview);
            if(inputText != undefined && inputText != '')
            {
                inputText.val(image_url);
                if(showImage.hasClass('pumpkin_widget')){
                    showImage.attr('src', image_url);
                }else{
                    imgPreview.attr('src', image_url);
                }
                
            }
        });
    });
});