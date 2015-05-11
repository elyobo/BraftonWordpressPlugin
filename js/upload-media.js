jQuery(document).ready(function($) {
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

        tb_show('', 'media-upload.php?type=image&TB_iframe=true');

        return false;
    });
});