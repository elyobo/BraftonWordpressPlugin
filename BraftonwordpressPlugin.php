<?php
/*
	Plugin Name: Content Importer
	Plugin URI: http://www.brafton.com/support/wordpress
	Description: Wordpress Plugin for Importing marketing content from Brafton, ContentLEAD, and Castleford Media Corp.  Support in line content, dynamic Authors, Updating and Error reporting. video requires php 5.3 or higher.
	Version: 3.2.1
    Requires: 3.5
	Author: Brafton, Inc.
	Author URI: http://brafton.com/support/wordpress
    Text Domain: text domain
    Domain Path: domain path
    //@author: Deryk W. King
    
*/
include 'BraftonError.php';
include 'BraftonOptions.php';
include 'BraftonFeedLoader.php';
include 'BraftonArticleLoader.php';
include 'BraftonVideoLoader.php';
include 'BraftonMarpro.php';
include 'BraftonCustomType.php';
include 'admin/BraftonAdminFunctions.php';

define("BRAFTON_VERSION", '3.2.1');
define("BRAFTON_ROOT", plugin_dir_url(__FILE__));
define("BRAFTON_PLUGIN", dirname(__FILE__).'/BraftonwordpressPlugin.php');
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
        
        //enable Featured Images if it isn't already
        if(!current_theme_supports('post-thumbnails')){
            add_theme_support('post-thumbnails');
        }
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
        add_action('pre_get_posts', array('BraftonCustomType', 'BraftonIncludeContent'));
        add_action('wp_dashboard_setup', array($this, 'BraftonDashboardWidget'));
        //Adds our needed filters
        add_filter('language_attributes', array($this, 'BraftonOpenGraphNamespace'), 100);
        add_filter('cron_schedules', array($this, 'BraftonCustomCronTime'),1,1);
        add_filter('the_content', array($this, 'BraftonContentModifyVideo'));
        //XML RPC Support
        //add_filter( 'xmlrpc_methods', array($this, 'BraftonXMLRPC' ));
        $init_options = new BraftonOptions();
        $this->options = $init_options->getAll();
        $this->ogStatus = $this->options['braftonOpenGraphStatus'];
        if($this->options['braftonMarproStatus'] == 'on'){
            $marpro = new BraftonMarpro();
        }
    }
    public function BraftonActivation(){
        $option_init = BraftonOptions::ini_BraftonOptions();
        $staticKey = BraftonOptions::getSingleOption('braftonApiKey');
        $staticBrand =  BraftonOptions::getSingleOption('braftonApiDomain');
        $option = wp_remote_post('http://updater.brafton.com/u/wordpress/update', array('body' => array('action' => 'register', 'version' => BRAFTON_VERSION, 'domain' => $_SERVER['HTTP_HOST'], 'api' => $staticKey, 'brand' => $staticBrand )));
        add_option('BraftonRegister', $option);
        
        //check for options that are turned on a activate the cron accordingly
        if(!BraftonOptions::getSingleOption('braftonStatus')){
            return;
        }
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
    static function BraftonDashboardWidget(){
        $brand = BraftonOptions::getSingleOption('braftonApiDomain');
        $brand = switchCase($brand);
        wp_add_dashboard_widget('BraftonDashAtAGlance', 'Recently Imported by '.$brand, array('BraftonWordpressPlugin','BraftonDisplayDashWidget'));
    }
    static function BraftonDisplayDashWidget(){
        $array = array(
            'meta_key'  => 'brafton_id',
            'posts_per_page'    => 5            
        );
        $query = new WP_Query($array);
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
    public function BraftonCustomCronTime($schedules){
        $schedules['threedaily'] = array(
            'interval'  => 28800,
            'display'   => _('Three Times Daily'),
            );
        return $schedules;
    }
    
    public function BraftonAdminMenu(){
        $style = BraftonOptions::getSingleOption('braftonRestyle');
        $brand = BraftonOptions::getSingleOption('braftonApiDomain');
        $brand = switchCase($brand);
        //new admin menu
        add_menu_page('Brafton Article Loader', "{$brand} Content Importer", 'activate_plugins','BraftonArticleLoader', 'admin_page','dashicons-download');
        add_submenu_page('BraftonArticleLoader', 'Brafton Article Loader', 'General Options', 'activate_plugins', 'BraftonArticleLoader', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Article Options', 'Article Options', 'activate_plugins', 'BraftonArticleLoader&tab=1', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Video Options', 'Video Options', 'activate_plugins', 'BraftonArticleLoader&tab=2', 'admin_page');
        add_submenu_page('BraftonArticleLoader', 'Pumpkin Options', 'Pumpkin Options', 'activate_plugins', 'BraftonArticleLoader&tab=3', 'admin_page');
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
            if($static['braftonPullQuotes']){
                $pullQuote = "'width': '{$p_width}%', 'float': '{$p_float}', 'margin': '{$p_margin}px'";
            }
            if($static['braftonInlineImages']){
                $inlineImage = "'width': '{$i_width}%', 'float': '{$i_float}', 'margin': '{$i_margin}px'";
            }
            $restyle =<<<EOC
            <script type="text/javascript">
            (function(d){
	//SELT NEW STYLE
	//LOOP THROUGH EACH ELEMENT AND ADD THAT STYLE
	jQuery('.pullQuoteWrapper').each(function(){
        jQuery(this).css({{$pullQuote}});
	});
	//INLINE IMAGE WRAPPER
	jQuery('.inlineImageWrapper').each(function(){
        jQuery(this).css({{$inlineImage}});
	});
    //INLINE VIDEO WRAPPER
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
        $methods[ 'braftonImportRPC' ] = 'brafton_remote_import';
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
               echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>';
        }
    }
    static function BraftonVideoHead(){
        $ops = new BraftonOptions();
        $static = $ops->getAll();
        //Define where videoJs comes from
        $videojs = '<link href="//vjs.zencdn.net/4.3/video-js.css" rel="stylesheet"><script src="//vjs.zencdn.net/4.3/video.js"></script>';
        //Define where atlatisJs comes from
        $atlantisjs = '<link rel="stylesheet" href="//atlantisjs.brafton.com/v1/atlantisjsv1.3.css" type="text/css" /><script src="//atlantisjs.brafton.com/v1/atlantis.min.v1.3.js" type="text/javascript"></script>';
        //defines what video javascript option we are using
        $videoOption = $static['braftonVideoHeaderScript'];
        if($videoOption != 'off'){
            echo $$videoOption;
        }
        $braftonCustomCSS = $static['braftonCustomCSS'];
        //does we need the css fix for the atlantis video player
        if(!$static['braftonEnableCustomCSS'] && $static['braftonRestyle']){
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
        else if($static['braftonEnableCustomCSS'] && $static['braftonRestyle']){
            echo $braftonCustomCSS;
        }
    }
}
$initialize_Brafton = new BraftonWordpressPlugin();

$brafton_plugin_slug = plugin_basename(__FILE__);
$BraftonPluginData = get_plugin_data(__FILE__);
include 'BraftonUpdate.php';

?>