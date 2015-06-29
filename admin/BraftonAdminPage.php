<?php
wp_enqueue_style('admin-css.css', plugin_dir_url( __FILE__ ) .'css/BraftonAdminCSS.css');
$dir = preg_replace('/admin$/', 'BraftonwordpressPlugin.php', dirname(__FILE__));
$plugin_data = get_plugin_data($dir);
global $brand;
$brand = BraftonOptions::getSingleOption('braftonApiDomain');
$brand = switchCase($brand);
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

<script>
tab = <?php if(isset($_GET['tab'])){ echo $_GET['tab'];} else{ echo 0;}?>;
jQuery(function() {
jQuery( "#tab-cont" ).tabs({
  active: tab
});
jQuery( document ).tooltip();
});
</script>
<?php 
    if(isset($_GET['error'])){

    }
?>
<div class="importer_header">
    <!--directory from the api image folder-->
    <img src="<?php echo plugin_dir_url(__FILE__); ?>/img/banner_<?php echo strtolower($brand); ?>.jpg">
</div>

<div id="tab-cont" class="tabs">
    <form method="post" action="" class="braf_options_form" onsubmit="return settingsValidate()">
    <ul>
        <li><a href="#tab-1">General Settings</a></li>
        <li><a href="#tab-2">Articles</a></li>
        <li><a href="#tab-3">Videos</a></li>
        <li><a href="#tab-4">Pumpkin</a></li>
        <li><a href="#tab-5">Archives</a></li>
        <li><a href="#tab-6">Error Logs</a></li>
        <li><a href="#tab-7">Manual Control</a></li>

    </ul>
<?php 
    echo '<div id="tab-1" class="tab-1">';
    settings_fields( 'brafton_general_options' );
    do_settings_sections( 'brafton_general' );
    submit_button('Save Settings');
    echo '</div>';
    echo '<div id="tab-2" class="tab-2">';
    settings_fields( 'brafton_article_options');
    do_settings_sections('brafton_article');
    submit_button('Save Settings');
    echo '</div>';
    echo '<div id="tab-3" class="tab-3">';
    settings_fields('brafton_video_options');
    do_settings_sections('brafton_video');
    submit_button('Save Settings');
    echo '</div>';
    echo '<div id="tab-4" class="tab-4">';
    settings_fields('brafton_marpro_options');
    do_settings_sections('brafton_marpro');
    submit_button('Save Settings');
    echo '</div>';
    echo '</form>';
    echo '<div id="tab-5" class="tab-5">';
    echo '<form method="post" action="'; echo $_SERVER['REQUEST_URI']; echo '" enctype="multipart/form-data">';
    settings_fields('brafton_archive_options');
    do_settings_sections('brafton_archive');
    submit_button('Upload Archive');
    echo '</form>';
    echo '</div>';
    echo '<div id="tab-6" class="tab-6">';
    echo '<form method="post" action="">';
    settings_fields( 'brafton_error_options' );
    do_settings_sections( 'brafton_error' );
    submit_button('Save Errors');
    echo '</form>';
    echo '</div>';
    echo '<div id="tab-7" class="tab-7">';
    echo "<form method='post' action=''>";
    settings_fields('brafton_control_options');
    do_settings_sections('brafton_control');

    echo '</form>';
    echo '</div>';

?>
        
</div>
<div id="imp-details" class="ui-widget ui-widget-content ui-corner-all">
    <h3 class="ui-widget-header"><?php echo $brand; ?>  Importer Details</h3>
    <!--Checks for warnings and errors related to the Brafton Importer only-->
    <?php braftonWarnings();?>
    <table class="form-table side-info">
        <tr>
            <td>Importer Name</td>
            <td><?php echo $plugin_data['Name'];?></td>
        </tr>
        <tr>
            <td>Importer Version</td>
            <td><?php echo $plugin_data['Version']; ?></td>
        </tr>
        <tr>
            <td>Author</td>
            <td><?php echo $plugin_data['AuthorName']; ?></td>
        </tr>
        <tr>
            <td>Support URL</td>
            <td><a href="<?php echo $plugin_data['PluginURI']; ?>">Brafton.com</a></td>
        </tr>
    </table>
    
</div>
<script>
function settingsValidate(){
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
    return validate;
}
$(document).ready(function(){
   $('.archiveStatus').click(function(){
      var stat = true;
       if($(this).attr('value') == 1){ stat = false; }else{stat = true;}
       $('#braftonUpload').prop('disabled', stat);
   });
    $('#close-imported').click(function(){
       $('#imported-list').toggle();
    });
});
</script>


<?php if($_GET['page'] == 'BraftonArticleLoader'){
    add_action('admin_footer_text', 'brafton_custom_footer');
    function brafton_custom_footer(){
        global $brand;
        echo '<div>Thank You for choosing <a href="http://www.'.$brand.'.com" target="_blank">'.$brand.'</a> for your Content Marketing Needs</div>';
    }
}
?>