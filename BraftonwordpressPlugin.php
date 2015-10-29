<?php
/*
	Plugin Name: Content Importer
	Plugin URI: http://www.brafton.com/support/wordpress
	Description: Wordpress Plugin for Importing marketing content from Brafton, ContentLEAD, and Castleford Media Corp.  Support in line content, dynamic Authors, Updating and Error reporting. video requires php 5.3 or higher.
	Version: 3.3.0
    Requires: 3.5
	Author: Brafton, Inc.
	Author URI: http://brafton.com/support/wordpress
    Text Domain: text domain
    Domain Path: domain path
    //@author: Deryk W. King
    
*/

if( class_exists( 'XMLHandler' ) ){
        echo '<div class="error">
				<p>CONTENT IMPORTER: You may have another Brafton, ContentLEAD, or Castleford Content Importer Installed.  Please Disable your previous version before activating your new one.<br/><blockquote>XMLHandler and XMLException Classes already declared indicating a previous version is installed.</blockquote></p>
				</div>';
    exit();
}

include 'BraftonError.php';
include 'BraftonOptions.php';
include 'BraftonFeedLoader.php';
include 'BraftonArticleLoader.php';
include 'BraftonVideoLoader.php';
include 'BraftonMarpro.php';
include 'BraftonCustomType.php';
include 'admin/BraftonAdminFunctions.php';
include 'BraftonXML.php';
include 'BraftonUpdate.php';

define("BRAFTON_VERSION", '3.3.0');

define("BRAFTON_ROOT", plugin_dir_url(__FILE__));
define("BRAFTON_PLUGIN", dirname(__FILE__).'/BraftonwordpressPlugin.php');
define("BRAFTON_BASENAME", plugin_basename(__FILE__));

class BraftonWordpressPlugin {
    
    /*
     *All these variables are only used within this class however this class is instantiated in each method of itself
     *
     */
    //Loads all the options into an array
    public $options;
    //Open Graph Status on/off
    public $ogStatus;
    //Custom CSS Status on/off
    public $cssStatus;
    //Loads needed video javascript files
    public $videoJsStatus;
    //Adds video css fix common to some systems
    public $videoCssStatus;
    //does this site need to add jquery to the site
    public $importJquery;
    //is marpro on
    public $marproStatus;
    //constant plugin version

    
    public function __construct(){
        //fires when the plugin is activated
        register_activation_hook(__FILE__, array($this, 'BraftonActivation'));
        //fires when the plugin is deactivated
        register_deactivation_hook(__FILE__, array($this, 'BraftonDeactivation'));
        if(version_compare(get_option('BraftonVersion', 0), BRAFTON_VERSION, '!=')){
            $option_init = BraftonOptions::ini_BraftonOptions();
        }
        //enable Featured Images if it isn't already
        if(!current_theme_supports('post-thumbnails')){
            add_theme_support('post-thumbnails');
        }
        
        $init_options = new BraftonOptions();
        $this->options = $init_options->getAll();
        
        //Adds our needed hooks
        add_action('wp_head', array($this, 'BraftonOpenGraph'));
        add_action('wp_head', array($this, 'BraftonJQuery'));
        add_action('wp_head', array($this, 'BraftonVideoHead'));
        add_action('wp_footer', array('BraftonMarpro', 'MarproScript'));
        add_action('wp_footer', array($this, 'BraftonRestyle'));
        add_action('admin_menu', array($this, 'BraftonAdminMenu'));
        add_action('braftonSetUpCron', array($this, 'BraftonCronArticle'));
        add_action('braftonSetUpCronVideo', array($this, 'BraftonCronVideo'));
        add_action('init', array('BraftonCustomType', 'BraftonInitializeType'));
        add_action('init', array($this, 'brafton_activate_updater'));
        add_action('wp_dashboard_setup', array($this, 'BraftonDashboardWidget'));
        
        if($this->options['braftonRemoteOperation']){
            add_action('wp_head', array($this, 'RemoteOperation'));
        }
        if(!function_exists('curl_init') || !class_exists('DOMDocument') ){
            add_action('admin_init', array($this, 'BraftonAutoDeactivate'));
        }
        //Adds our needed filters
        add_filter('language_attributes', array($this, 'BraftonOpenGraphNamespace'), 100);
        add_filter('the_content', array($this, 'BraftonContentModifyVideo'));
        add_filter('plugin_row_meta', array($this, 'BraftonPluginMeta'), 10, 2);
        add_filter('plugin_action_links_'.BRAFTON_BASENAME, array($this, 'BraftonPluginLinks'));
        add_filter( 'xmlrpc_methods', array($this, 'BraftonXMLRPC' ));

        $this->ogStatus = $this->options['braftonOpenGraphStatus'];
        if($this->options['braftonMarproStatus'] == 'on'){
            $marpro = new BraftonMarpro();
        }
        if($this->options['braftonArticlePostType']){
            add_action('pre_get_posts', array('BraftonCustomType', 'BraftonIncludeContent'));
        }
    }
    static function BraftonAutoDeactivate(){
        //Deactivate the plugin due to missing dependancies.
        deactivate_plugins(plugin_basename(__FILE__));
        $list[] = function_exists('curl_init')? '' : 'function curl_init()';
        $list[] = function_exists('fopen') && ini_get('allow_url_fopen') ? '' : 'function fopen() with allow_url_fopen set to on or 1 (VIDEO IMPORT ONLY)';
        $list[] = class_exists('DOMDocument')? '' : 'Class DOMDocument';
        
        //strinify list of dependancies missing filtering out any empty values indicating the dependancy does exist.
        $list = array_diff($list, array(''));
        $missing = implode(', ', $list);
        
        echo '<div class="error">
				<p>Your Content Importer for Brafton, ContentLEAD, and Castleford Content has been disabled due to the missing dependancies.Ensure '.$missing.' are enabled on your server.</p>
				</div>';
        
    }
    public function BraftonActivation(){
        $option_init = BraftonOptions::ini_BraftonOptions();
        
        //check for options that are turned on a activate the cron accordingly
        if(BraftonOptions::getSingleOption('braftonArticleStatus')){
            if(!wp_next_scheduled('braftonSetUpCron')){
                wp_clear_scheduled_hook('braftonSetUpCron');
                //importer is set to go off 2 minutes after it is enabled than hourly after that
                $schedule = wp_schedule_event(time()+120, 'hourly', 'braftonSetUpCron');
            }
        }
        if(BraftonOptions::getSingleOption('braftonVideoStatus')){
            if(!wp_next_scheduled('braftonSetUpCronVideo')){
                wp_clear_scheduled_hook('braftonSetUpCronVideo');
                //importer is set to go off 2 minutes after it is enabled than daily after that
                $schedule = wp_schedule_event(time()+120, 'twicedaily', 'braftonSetUpCronVideo');
            }
        }
    }
    
    public function BraftonDeactivation(){
        wp_clear_scheduled_hook('braftonSetUpCron');
        wp_clear_scheduled_hook('braftonSetUpCronVideo');
    }
    static function brafton_activate_updater(){
        $brand = BraftonOptions::getSingleOption('braftonApiDomain');
        $brafton_plugin_remote_path = 'http://updater.brafton.com/u/wordpress/update/';
        new Brafton_Update(BRAFTON_VERSION, $brafton_plugin_remote_path, BRAFTON_BASENAME, $brand);
    }
    static function BraftonPluginMeta($links, $file){
        if($file == plugin_basename(__FILE__)){
            $admin = admin_url('/', 'admin');
            $links[] = '<a href="'.$admin.'admin.php?page=BraftonArticleLoader">Settings</a>';
        }
        return $links;
    }
    static function BraftonPluginLinks($links){
        $links[] = '<a href="'.$admin.'admin.php?page=BraftonArticleLoader">Settings</a>';
        return $links;
    }
    static function BraftonDashboardWidget(){
        $brand = BraftonOptions::getSingleOption('braftonApiDomain');
        $brand = switchCase($brand);
        wp_add_dashboard_widget('BraftonDashAtAGlance', 'Recently Imported by '.$brand, array('BraftonWordpressPlugin','BraftonDisplayDashWidget'));
    }
    static function BraftonDisplayDashWidget(){
        $brand = BraftonOptions::getSingleOption('braftonApiDomain');
        $brand = switchCase($brand);
        $array = array(
            'meta_key'  => 'brafton_id',
            'posts_per_page'    => 5            
        );
        $query = new WP_Query($array);
        ?>
        <img src="<?php echo plugin_dir_url(__FILE__); ?>/admin/img/banner_<?php echo strtolower($brand); ?>.jpg" style="width:100% !important; height:auto !important;">
        <?php
        if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
            echo '<p>';
            echo '<a href="'.get_edit_post_link().'">'.get_the_title(); echo '</a><br/> Imported on: '; the_time('Y-d-m');
            echo '</p>';
        endwhile;endif;
    }
    static function BraftonContentModifyVideo($content){
        
        if(is_single()){
            $ops = new BraftonOptions();
            $static = $ops->getAll();
            if($static['braftonVideoOutput']){
                if($meta=get_post_meta(get_the_ID(), "brafton_video", true)){
                    $content = $static['braftonVideoOutput'] == 'after'? $content . $meta : $meta . $content;
                }
            }
        }
        
        return $content;
    }
    
    public function BraftonAdminMenu(){
        $brand = BraftonOptions::getSingleOption('braftonApiDomain');
        $brand = switchCase($brand);
        //new admin menu
        add_menu_page('Brafton Article Loader', "{$brand} Content Importer", 'activate_plugins','BraftonArticleLoader', 'admin_page','dashicons-download');
        add_submenu_page('BraftonArticleLoader', 'Brafton Article Loader', 'General Options', 'activate_plugins', 'BraftonArticleLoader', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Article Options', 'Article Options', 'activate_plugins', 'BraftonArticleLoader&tab=1', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Video Options', 'Video Options', 'activate_plugins', 'BraftonArticleLoader&tab=2', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Arch Options', 'Arch Options', 'activate_plugins', 'BraftonArticleLoader&tab=3', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Archives', 'Archives', 'activate_plugins', 'BraftonArticleLoader&tab=4', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Error Logs', 'Error Logs', 'activate_plugins', 'BraftonArticleLoader&tab=5', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Run Importers', 'Run Importers', 'activate_plugins', 'BraftonArticleLoader&tab=6', 'admin_page');
        if(BraftonOptions::getSingleOption('braftonRestyle')){
            add_submenu_page('BraftonArticleLoader', 'Premium Styles', 'Premium Styles', 'activate_plugins', 'BraftonPremiumStyles', 'style_page');
        }
    }
    static function BraftonRestyle(){
        $ops = new BraftonOptions();
        $static = $ops->getAll();
        $restyle = $static['braftonRestyle'];
        if($restyle && is_single()){
            $p_width = $static['braftonPullQuoteWidth'];
            $p_float = $static['braftonPullQuoteFloat'];
            $p_margin = $static['braftonPullQuoteMargin'];
            $i_width = $static['braftonInlineImageWidth'];
            $i_float = $static['braftonInlineImageFloat'];
            $i_margin = $static['braftonInlineImageMargin'];
            $pullQuote = '';
            $inlineImage = '';
            $js = '';
            if($static['braftonPullQuotes']){
                $pullQuote = "'width': '{$p_width}%', 'float': '{$p_float}', 'margin': '{$p_margin}px'";
                $js .=<<<EOPQ
	//PULLQUOTE CORRECTION
	jQuery('.pullQuoteWrapper').each(function(){
        jQuery(this).css({{$pullQuote}});
	});
EOPQ;
            }
            if($static['braftonInlineImages']){
                $inlineImage = "'width': '{$i_width}%', 'float': '{$i_float}', 'margin': '{$i_margin}px'";
                $js .=<<<EOIL
	//INLINE IMAGE WRAPPER
	jQuery('.inlineImageWrapper').each(function(){
        jQuery(this).css({{$inlineImage}});
	});
EOIL;
            }
            $restyle =<<<EOC
            <script type="text/javascript">
            (function(d){
                $js
}(document));
        </script>
EOC;
            echo $restyle;
        }
    }
    //Static Article Cron Job **Goes off every hour
    static function BraftonCronArticle(){
        $import = new BraftonArticleLoader();
        $import->ImportArticles();  
    }
    //Static Video Cron job **Goes off every day
    static function BraftonCronVideo(){
        $import = new BraftonVideoLoader();
        $import->ImportVideos();
    }
    //used to clear out brafton cron job add action for init
    static function BraftonXMLRPC($methods){
        $methods[ 'braftonImportRPC' ] = array('BraftonXMLRPC', 'RemoteOperation');
        return $methods;
    }
    //used to get the url for og:url tags
    static function BraftonCurlPageURL(){
    	$pageURL = 'http';

        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER["HTTPS"]) == "on")
            $pageURL .= "s";

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80")
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        else
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

        return $pageURL;
    }
    static function BraftonOpenGraph(){
        $static = BraftonOptions::getSingleOption('braftonOpenGraphStatus');
        if (!is_single() || (!$static))
            return;

        global $post;
        $tags = array(
            'og:type' => 'article',
            'og:site_name' => get_bloginfo('name'),
            'og:url' => BraftonWordpressPlugin::BraftonCurlPageURL(),
            'og:title' => preg_replace('/<.*?>/', '', get_the_title()),
            'og:description' => htmlspecialchars(preg_replace('/<.*?>/', '', get_the_excerpt())),
            'og:image' => wp_get_attachment_url(get_post_thumbnail_id($post->ID)),
            'article:published_time' => date('c', strtotime($post->post_date))
        );
        $twitter = array(
            'twitter:card'  => 'summary_large_image',
            'twitter:title' => preg_replace('/<.*?>/', '', get_the_title()),
            'twitter:description'   => htmlspecialchars(preg_replace('/<.*?>/', '', get_the_excerpt())),
            'twitter:image' =>  wp_get_attachment_url(get_post_thumbnail_id($post->ID))
        );
        $google = array(
            'name'  => preg_replace('/<.*?>/', '', get_the_title()),
            'description'   => htmlspecialchars(preg_replace('/<.*?>/', '', get_the_excerpt())),
            'image' => wp_get_attachment_url(get_post_thumbnail_id($post->ID))
        );
        
        $tagsHtml = '';
        foreach($google as $tag => $content)
            $tagsHtml .= sprintf('<meta itemprop="%s" content="%s" />', $tag, $content) . "\n";
        foreach($twitter as $tag => $content)
            $tagsHtml .= sprintf('<meta name="%s" content="%s" />', $tag, $content) . "\n";

        foreach ($tags as $tag => $content)
            $tagsHtml .= sprintf('<meta property="%s" content="%s" />', $tag, $content) . "\n";

        echo trim($tagsHtml);   
    }
    static function BraftonOpenGraphNamespace($content){
    	$namespaces = array(
		  'xmlns:og="http://ogp.me/ns#"',
		  'xmlns:article="http://ogp.me/ns/article#"'
	   );
	
	   foreach ($namespaces as $ns){
		  if (strpos($content, $ns) === false) // don't add attributes twice
			 $content .= ' ' . $ns;
       }
	   return trim($content);   
    }
    static function BraftonJQuery(){
        $ops = new BraftonOptions();
        $static = $ops->getAll();
        //do we need a jquery script?  Use google CDN
        if($static['braftonImportJquery'] == 'on'){
               echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>';
        }
    }
    static function BraftonVideoHead(){
        $ops = new BraftonOptions();
        $static = $ops->getAll();
        
        $videojs = '<link href="//vjs.zencdn.net/4.3/video-js.css" rel="stylesheet"><script src="//vjs.zencdn.net/4.3/video.js"></script>';
        
        $atlantisjs_src = '//atlantisjs.brafton.com';
    
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER["HTTPS"]) == "on"){
            $atlantisjs_src = 'https://d1z1nkr1lc6xgd.cloudfront.net';
        }
        $atlantisjs = '<link rel="stylesheet" href="'.$atlantisjs_src.'/v1/atlantisjsv1.4.css" type="text/css" /><script src="'.$atlantisjs_src.'/v1/atlantis.min.v1.3.js" type="text/javascript"></script>';
        
        //defines what video javascript option we are using
        $videoOption = $static['braftonVideoHeaderScript'];
        if($videoOption){
            echo $$static['braftonVideoPlayer'];
        }
        $braftonCustomCSS = $static['braftonCustomCSS'];
        //does we need the css fix for the atlantis video player
        if((int)$static['braftonEnableCustomCSS'] == 2 && $static['braftonRestyle']){
            $braftonPauseColor = $static['braftonPauseColor'];
            $braftonEndBackgroundcolor = $static['braftonEndBackgroundcolor'];
            $braftonEndTitleColor = $static['braftonEndTitleColor'];
            $braftonEndTitleAlign = $static['braftonEndTitleAlign'];
            $braftonEndSubTitleColor = $static['braftonEndSubTitleColor'];
            $braftonEndSubTitleBackground = $static['braftonEndSubTitleBackground'];
            $braftonEndSubTitleAlign = $static['braftonEndSubTitleAlign'];
            $braftonEndButtonBackgroundColor = $static['braftonEndButtonBackgroundColor'];
            $braftonEndButtonTextColor = $static['braftonEndButtonTextColor'];
            $braftonEndButtonBackgroundColorHover = $static['braftonEndButtonBackgroundColorHover'];
            $braftonEndButtonTextColorHover = $static['braftonEndButtonTextColorHover'];
            $braftonEndTitleBackground = $static['braftonEndTitleBackground'];
        $css=<<<EOT
		<style type="text/css">
        /* Effects the puase cta background color */
        span.video-pause-call-to-action, span.ajs-video-annotation{
            background-color:;
        }
        /* effects the pause cta text color */
        span.video-pause-call-to-action a:link, span.video-pause-call-to-action a:visited{
            color:$braftonPauseColor;  
        }
        /* effects the end of video background color *Note: has no effect if a background image is selected */
        div.ajs-end-of-video-call-to-action-container{
            background-color:$braftonEndBackgroundcolor;
        }
        /* effects the end of video title tag */
        div.ajs-end-of-video-call-to-action-container h2{
            background:$braftonEndTitleBackground;
            color:$braftonEndTitleColor;
            text-align:$braftonEndTitleAlign;
        }
        /* effects the end of video subtitle tags */
        div.ajs-end-of-video-call-to-action-container p{
            background:$braftonEndSubTitleBackground;
            color:$braftonEndSubTitleColor;
            text-align:$braftonEndSubTitleAlign;
        }
        /* effects the end of video button *Note: has no effect if button image is selected */
        a.ajs-call-to-action-button{
             background-color:$braftonEndButtonBackgroundColor;  
            color:$braftonEndButtonTextColor;
        }
        /* effects the end of video button on hover and  *Note: has no effects if button image is selected */
        a.ajs-call-to-action-button:hover, a.ajs-call-to-action-button:visited{
            background-color:$braftonEndButtonBackgroundColorHover;
            color:$braftonEndButtonTextColorHover;
        }

		</style>
EOT;
        
		echo $css;
        }
        else if((int)$static['braftonEnableCustomCSS'] == 1 && $static['braftonRestyle']){
            echo $braftonCustomCSS;
        }
    }
    
    static function RemoteOperation(){
        $ops = new BraftonOptions();
        $static = $ops->getAll();
        if($static['braftonRemoteTime'] + 21600 > current_time('timestamp')){
            return;
        }
        $remoteUrl = 'http://updater.brafton.com/wp-remote/remote.php?';
        $siteUrl = site_url();
        $functions = $static['braftonArticleStatus'] ? 'articles,' : '';
        $functions .= $static['braftonVideoStatus'] ? 'videos' : '';
        $fullUrl = $remoteUrl . 'clientUrl=' . $siteUrl . '&function=' .$functions;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30);
        curl_exec($ch);
        
        $ops->saveOption('braftonRemoteTime', current_time('timestamp'));
    }
}
$initialize_Brafton = new BraftonWordpressPlugin();


?>