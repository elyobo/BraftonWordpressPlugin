<?php
wp_enqueue_style('admin-css.css', plugin_dir_url( __FILE__ ) .'css/BraftonAdminCSS.css');
wp_enqueue_script('jquery');
$dir = str_replace('admin', 'BraftonwordpressPlugin.php', dirname(__FILE__));
$plugin_data = get_plugin_data($dir);
global $brand;
$brand = BraftonOptions::getSingleOption('braftonApiDomain');
$brand = switchCase($brand);
?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

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
        <li><a href="#tab-1">Article Style</a></li>
        <li><a href="#tab-2">Atlantis Video Style</a></li>
        
    </ul>
<?php 
    echo '<div id="tab-1" class="tab-1">';
    settings_fields('brafton_article_style_options');
    do_settings_sections('brafton_article_style');
    echo '</div>';
    echo '<div id="tab-2" class="tab-2">';
    settings_fields('brafton_atlantis_style_options');
    do_settings_sections('brafton_atlantis');
    submit_button('Save Settings');
    echo '</div>';
    echo '</form>';
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

}
jQuery(document).ready(function($){
    $('.braftonColorChoose').change(function(){
       $(this).prev().val($(this).val()); 
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