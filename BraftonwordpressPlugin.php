<?php
/*
	Plugin Name: Content Importer
	Plugin URI: http://www.brafton.com/support/wordpress
	Description: Wordpress Plugin for Importing marketing content from Brafton, ContentLEAD, and Castleford Media Corp.
	Version: 3.0.0
    Requires: 3.4
	Author: Brafton, Inc.
	Author URI: http://brafton.com/support/wordpress
    Text Domain: text domain
    Domain Path: domain path 
    
*/
include 'BraftonError.php';
include 'BraftonOptions.php';
include 'BraftonFeedLoader.php';
include 'BraftonArticleLoader.php';
include 'BraftonVideoLoader.php';
include 'admin/BraftonAdminFunctions.php';

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
    
    public function __construct(){
        //fires when the plugin is activated
        register_activation_hook(__FILE__, array($this, 'BraftonActivation'));
        //fires when the plugin is deactivated
        register_deactivation_hook(__FILE__, array($this, 'BraftonDeactivation'));
        
        //Adds our needed hooks
        add_action('wp_head', array($this, 'BraftonOpenGraph'));
        add_action('wp_head', array($this, 'BraftonVideoHead'));
        add_action('braftonSetUpCron', array($this, 'BraftonCronArticle'));
        add_action('braftonSetUpCronVideo', array($this, 'BraftonCronVideo'));
        
        //Adds our needed filters
        add_filter('language_attributes', array($this, 'BraftonOpenGraphNamespace'), 100);
        $init_options = new BraftonOptions();
        $this->options = $init_options->getAll();
        $this->ogStatus = $this->options['braftonOpenGraphStatus'];
    }
    
    public function BraftonActivation(){
        $option_init = BraftonOptions::ini_BraftonOptions();
    }
    
    public function BraftonDeactivation(){
        wp_clear_scheduled_hook('braftonSetUpCron');
        wp_clear_scheduled_hook('braftonSetUpCronVideo');
    }
    //Static Article Cron Job **Goes off every hour
    static function BraftonCronArticle(){
        $import = new BraftonArticleLoader();
        $import->ImportArticles();  
    }
    //Static Video Cron job **Goes off every hour
    static function BraftonCronVideo(){
        $import = new BraftonvideoLoader();
        $import->ImportArticles();
    }
    //used to clear out brafton cron job add action for init
    static function BraftonXMLRPC(){
        
    }
    //used to get the url for og:url tags
    private function BraftonCurlPageURL(){
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
        $static = new BraftonWordpressPlugin();
        if (!is_single() || (!$static->ogStatus))
            return;

        global $post;
        $tags = array(
            'og:type' => 'article',
            'og:site_name' => get_bloginfo('name'),
            'og:url' => $static->BraftonCurlPageURL(),
            'og:title' => preg_replace('/<.*?>/', '', get_the_title()),
            'og:description' => htmlspecialchars(preg_replace('/<.*?>/', '', get_the_excerpt())),
            'og:image' => wp_get_attachment_url(get_post_thumbnail_id($post->ID)),
            'article:published_time' => date('c', strtotime($post->post_date))
        );

        $tagsHtml = '';
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
    static function BraftonVideoHead(){
        $static = new BraftonWordpressPlugin();
        //do we need a jquery script?  Use google CDN
        if($static->options['braftonImportJquery'] == 'on'){
               echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>';
        }
        //Define where videoJs comes from
        $videojs = '<link href="//vjs.zencdn.net/4.3/video-js.css" rel="stylesheet"><script src="//vjs.zencdn.net/4.3/video.js"></script>';
        //Define where atlatisJs comes from
        $atlantisjs = '<link rel="stylesheet" href="http://p.ninjacdn.co.uk/atlantisjs/v0.11.7/atlantisjs.css" type="text/css" /><script src="http://p.ninjacdn.co.uk/atlantisjs/v0.11.7/atlantis.js" type="text/javascript"></script>';
        //defines what video javascript option we are using
        $videoOption = $static->options['braftonVideoHeaderScript'];
        if($videoOption != 'off'){
            echo $$videoOption;
        }
        //does we need the css fix for the atlantis video player
        if($static->options['braftonVideoCSS'] == 'on'){
        $css=<<<EOT
		<style type="text/css">
		.vjs-menu{
		width:10em!important;
		left:-4em!important;
		}

		.ajs-default-skin div.vjs-big-play-button span{
		top:70%!important;
		}

		.ajs-default-skin{
		-moz-box-shadow: 2px 2px 4px 3px #ccc;
		-webkit-box-shadow: 2px 2px 4px 3px #ccc;
		box-shadow: 2px 2px 4px 3px #ccc;
		}

		.ajs-call-to-action-button{
		width:200px!important;
		color: #58795B!important;
		margin-left:0px!important;
		}

		.ajs-call-to-action-button a{
		color:darkslateblue!important;
		}

		.ajs-call-to-action-button a:visited{
		color:darkslateblue!important;
		}
		</style>
EOT;
        
		echo $css;
        }
    }
}
$initialize_Brafton = new BraftonWordpressPlugin();

$brafton_plugin_slug = plugin_basename(__FILE__);
$BraftonPluginData = get_plugin_data(__FILE__);
include 'BraftonUpdate.php';

?>