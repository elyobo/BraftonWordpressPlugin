/*function settingsValidate(){
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
*/
function settingsValidate(){
    var $ = jQuery;
    var validate = true;
    if($("select[name='braftonImporterUser']").val() == ''){
        validate = false;
        alert('You have not set an Importer User on the General Tab');
    }
    if($("input[name='braftonArticleStatus']").val() == 1 && $('#brafton_api_key').val() == ''){
        validate = false;
        alert('You have turned your Article Importer on but forgot to enter your API Key');
    }
    if($("input[name='braftonVideoStatus']").val() == 1 && ($('#brafton_video_public').val() == '' || $('#brafton_video_secret').val() == '')){
        validate = false;
        var itemArray;
        itemArray = {
                PublicKey: $('#brafton_video_public').val(), 
                PrivateKey: $('#brafton_video_secret').val()
        };
        var statement = [];
        for(var item in itemArray){
            if(!itemArray[item]){
                statement.push(item);   
            }
        }
        alert('You have turned your Video Importer on but forgot to enter your '+ (statement.length > 1? statement.join(" and ") : statement[0]) );
    }
    if($('input[name="braftonArticlePostType"]').val() == 1 && $('input[name="braftonCustomSlug"]').val() == ''){
        validate = false;
        alert('You have choosen to use the importers custom post type of "blog_content" but have not entered a Custom Post Type Name');
    }
    if($('input[name="braftonStatus"]').val() == 1 && $('input[name="braftonRemoteOperation"]').val() == 1){
        validate = false;
        alert('You have turned on Remote Operation however have not turned off Automatic Import.  Please turn Off one of these options before saving');
    }
    if($('#braftonArticleExistingPostType:checked').val()){
           $('input[name="braftonArticleExistingCategory"]').val() = '';
           $('input[name="braftonArticleExistingTag"]').val() = '';
    }
    return validate;
}
function getBraftonArticles(page){
    var $ = jQuery;
     var input = $('#braf_id_input');
        var result = $('#b_searchResults');
        var val = input.val();
            var data = {
                action: "getBraftonArticles",
                ids: []
            };
            if(undefined != page){
                data.page = page;   
            }
            val = val.length > 0? val.split(',') : '';
            for(var i = 0; i<val.length;++i){
                data.ids.push(val[i]);   
            }
            $.post(ajaxurl, data, function(response){
                result.html(response);
            });
}
function premium(e){
    var $ = jQuery;
    e = e ? $(e) : $('select[name="braftonEnableCustomCSS"]');
    var value = e.val();
    if(value == 1){
        $('.braftonCustomCSS').css({display: 'block'});
        $('#tab-11 table.form-table tr').each(function(e){
            if(e > 1){
                $(this).css({display: 'none'});
            }
        });
    }else if(value == 2){
        $('.braftonCustomCSS').css({display: 'none'}); 
        $('#tab-11 table.form-table tr').each(function(e){
            if(e > 1){
                $(this).css({display: 'table-row'});
            }
        });
    }else{
        $('#tab-11 table.form-table tr').each(function(e){
            if(e > 1){
                $(this).css({display: 'none'});
            }
        });
        $('.braftonCustomCSS').css({display: 'none'}); 
    }
}
function braftonDialog(element, optionsArray){
    var options = {};
    options[optionsArray.OptionsName] = optionsArray.OptionsFucntion;
}
jQuery(function($){
    getBraftonArticles();
      
    $('#RemoteStatusAutoCheck').change(function(e){
            console.log($(this).is(':checked'));
           if($(this).is(':checked')){
                var $e = $(this);
                //$('#remoteCheck').css({display:'inline-block'});
               $('#checkFlasher').html('System Check');
               $('#checkFlasher').addClass('blinking-text');
               $('#remoteCheck').find('img').attr('src', '../wp-includes/images/wpspin-2x.gif');
               $('#remoteCheck').find('img').css({left: '0px', position: 'relative'});
               var data = {'action': 'health_check'};
               jQuery.post(ajaxurl, data, function(response){
                   
                   if(response == 'ok'){
                       //$('#remoteCheck').css({display:'none'});
                       $('#remoteCheck').find('img').attr('src', '../wp-includes/images/uploader-icons-2x.png');
                       alert('Your system supports Remote Operation.  Save your settings to initialize this option.  The Remote Operation is triggered every 6 hours');
                   }else if(response == 'fail'){
                        alert('Your system does not support the Remote Operation.  Please contact your System Administrator to enable the use of XML-RPC on your server');
                       $e.prop("checked", false);
                       $e.next('input[type="radio"]').prop("checked", true);
                       //$('#remoteCheck').css({display:'none'});
                       $('#remoteCheck').find('img').attr('src', '../wp-includes/images/uploader-icons-2x.png');
                       $('#remoteCheck').find('img').css({position: 'absolute', left: '-188px'});
                   }
                   $('#checkFlasher').html('');
                   $('#checkFlasher').removeClass('blinking-text');
               });
           }else{
                $('#remoteCheck').find('img').attr('src', '');   
           }
        });
        /*
        if($('input[name="braftonEnableCustomCSS"]').length != 0){
            premium(null);
        }
        $('input[name="braftonEnableCustomCSS"]').map(function(e){
            $(this).change(function(e){
                premium(e);
            });
        });
    */
    premium();
    $('select[name="braftonEnableCustomCSS"]').change(function(e){
        premium(this);
    });
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
   
    $('#BraftonArchiveOptionCheck').change(function(){
        var stat = $(this).is(':checked');
        console.log(stat);
        $('#braftonUpload').prop('disabled', !stat);
    });
    
    $('#BraftonPostTypeCheck').change(function(){
        if($(this).is(':checked')){
            console.log('checked');
            $('#braftonArticleExistingPostType').prop('disabled', true );
            $('input[name="braftonCustomSlug"]').prop('disabled', false );
            $('input[name="braftonArticleExistingCategory"]').prop('disabled', true );
            $('input[name="braftonArticleExistingTag"]').prop('disabled', true );
        }
        else{
            console.log('not checked');
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
    $('label.brafton-switch').click(function(e){
               var checkbox = $(this).find("input[type='checkbox']");
               var on = checkbox.attr('data-on');
               var off = checkbox.attr('data-off');
                if(checkbox.is(':checked')){
                       $(this).next("input[type='hidden']").val(on);
                }
               else{
                   $(this).next("input[type='hidden']").val(off);
               }
           });
    $('#braftonMenuNavigation a').click(function(e){
        var _this = $(this);
        var tab = _this.attr('href').substring(1).split('-')[1];
        var formId = $('div #tab-'+tab).find('form').length? $('div #tab-'+tab).find('form') : $('div #tab-'+tab).parent('form');
        var result = [];
        var oldUrl = formId.attr('action').split("&").forEach(function(part){
            var item = part.split("=");
            if(item[0] == 'tab'){ 
                item[0] = '&'+item[0]; 
                item[1] = tab == 1? 0 : tab - 1;
            };
            result.push(item[0]+'='+ decodeURIComponent(item[1]));
        });
        if(!(result.length > 1)){
            result.push("&tab="+(tab-1).toString());
        };
        console.log(result.join(""));
        formId.attr('action', result.join(""));
    });
    $('#findArticleSubmit').click(function(e){
        e.preventDefault();
        getBraftonArticles(jQuery);
    });
});