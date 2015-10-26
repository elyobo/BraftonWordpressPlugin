function settingsValidate(){
    var $ = jQuery;
    var validate = true;
    if($("select[name='braftonImporterUser']").val() == ''){
        validate = false;
        alert('You have not set an Importer User on the General Tab');
    }
    if($("input[name='braftonArticleStatus']:checked").val() == 1 && $('#brafton_api_key').val() == ''){
        validate = false;
        alert('You have turned your Article Importer on but forgot to enter your API Key');
    }
    if($("input[name='braftonVideoStatus']:checked").val() == 1 && ($('#brafton_video_public').val() == '' || $('#brafton_video_secret').val() == '')){
        validate = false;
        alert('You have turned your Video Importer on but forgot to enter your Public or Private Key');
    }
    if($('input[name="braftonArticlePostType"]:checked').val() == 1 && $('input[name="braftonCustomSlug"]').val() == ''){
        validate = false;
        alert('You have choosen to use the importers custom post type of "blog_content" but have not entered a url slug');
    }
    if($('input[name="braftonStatus"]:checked').val() == 1 && $('input[name="braftonRemoteOperation"]:checked').val() == 1){
        validate = false;
        alert('You have turned on Remote Operation however have not turned off Automatic Import.  Please turn Off one of these options before saving');
    }
    if($('#braftonArticleExistingPostType:checked').val()){
           $('input[name="braftonArticleExistingCategory"]').val() = '';
           $('input[name="braftonArticleExistingTag"]').val() = '';
    }
    return validate;
}
jQuery(document).ready(function($){
    
    $('#show_hide').toggle(function(e){
        $(this).html('(Hide Log)');
       $('.b_e_display').show();
    },function(e){
        $(this).html('(Show Log)');
        $('.b_e_display').hide();
    });
    $('#show_hide_cta').toggle(function(e){
       $(this).html('(Hide Settings)');
        $('.b_v_cta').show();
    }, function(e){
        $(this).html('(Show Settings)');
        $('.b_v_cta').hide();
    });
    if($('#brafton-end-button-preview')){
        var count = $('.braftonPositionInput');
        var cor = $('.braftonPositionInput').map(function(){
            return $(this).val();
        }).get();
        $('#brafton-end-button-preview').css(cor[0], cor[1]+'px');
        $('#brafton-end-button-preview').css(cor[2], cor[3]+'px');
    }
    $('input[name="braftonVideoCTA[endingTitle]"]').keyup(function(){
        $('#brafton-end-title-preview').html($(this).val());
    });
    $('input[name="braftonVideoCTA[endingSubtitle]"]').keyup(function(){
        $('#brafton-end-subtitle-preview').html($(this).val());
    });
    $('.braftonPositionInput').change(function(){
        var count = $('.braftonPositionInput');
        var cor = $('.braftonPositionInput').map(function(){
            return $(this).val();
        }).get();
        $('#brafton-end-button-preview').css(cor[0], cor[1]+'px');
        $('#brafton-end-button-preview').css(cor[2], cor[3]+'px');
       //console.log(f_string); 
    });
   $('.archiveStatus').click(function(){
      var stat = true;
       if($(this).attr('value') == 1){ stat = false; }else{stat = true;}
       $('#braftonUpload').prop('disabled', stat);
   });
    $('input[name="braftonArticlePostType"]').change(function(){
        
        if($('input[name="braftonArticlePostType"]:checked').val() == 1){
            $('#braftonArticleExistingPostType').prop('disabled', true );
            $('input[name="braftonCustomSlug"]').prop('disabled', false );
            $('input[name="braftonArticleExistingCategory"]').prop('disabled', true );
            $('input[name="braftonArticleExistingTag"]').prop('disabled', true );
        }else{
            $('#braftonArticleExistingPostType').prop('disabled', false );
            $('input[name="braftonCustomSlug"]').prop('disabled', true );
            $('input[name="braftonArticleExistingCategory"]').prop('disabled', false );
            $('input[name="braftonArticleExistingTag"]').prop('disabled', false );
            
        }
    });
   $('#braftonArticleExistingPostType').change(function(){
       if($('#braftonArticleExistingPostType').val()){
           $('input[name="braftonArticleExistingCategory"]').toggle();
           $('input[name="braftonArticleExistingTag"]').toggle();
           return;
       }
       if($('input[name="braftonArticleExistingCategory"]').css('display') != 'inline-block'){
           $('input[name="braftonArticleExistingCategory"]').toggle();
           $('input[name="braftonArticleExistingTag"]').toggle();
       }
   });
    $('#close-imported').click(function(){
       $('#imported-list').toggle();
    });
});