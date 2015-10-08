<?php
/*
 * Class for controling all the BraftonOptions
 *
 * Options are stored in a serialized options BraftonOptions in the wp_options Table.
 * Named BraftonOptions as to not conflict with already installed plugins from the Black Eye Series
 *
 */
class BraftonOptions {

    /*
     * Array of all the options for BraftonOptions
     */
    public $options;
    /*
     * Stores the serialized options from the db
     */
    private $ser_options;

    public function __construct(){
        //registers the hook for when the plugin is activated.  This method can be instantiated on its own again if needed.
        //$this->ini_BraftonOptions();
        //Gets the options stored in the database and stores the associative array in the $this->options
        $this->ser_options = get_option('BraftonOptions');
        $this->options = $this->ser_options;
    }
    //This method checks for the existance of Brafton options.  If there are no Brafton Options it will initialize them all to start. If they already exsist return false. This method is only called when the plugin is activated with the the register_activation_hook().  It is the Second class to be called after BraftonErrors().
    static function ini_BraftonOptions(){
        $default_options = array(
            'braftonDebugger'           => 0,
            'braftonCategories'         => get_option("braftonxml_sched_cats", 'categories'),
            'braftonCustomArticleCategories'    => get_option("braftonxml_sched_cats_input", ''),
            'braftonCustomVideoCategories'    => '',
            'braftonTags'               => get_option("braftonxml_sched_tags", 'none_tags'),
            'braftonCustomTags'         => get_option("braftonxml_sched_tags_input", ''),
            'braftonPublishDate'        => get_option("braftonxml_publishdate", 'published'),
            'braftonPostStatus'         => 'publish',
            'braftonImporterUser'       => '',
            'braftonStatus'             => 0,
            'braftonClearLog'           => 0,
            'braftonApiDomain'          => get_option("braftonxml_domain", 'api.brafton.com'),
            'braftonApiKey'             => get_option("braftonxml_sched_API_KEY", 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'),
            'braftonUpdateContent'      => 0,
            'braftonArticleDynamic'     => get_option("braftonxml_dynamic_author", 'n'),
            'braftonArticleAuthorDefault'   => get_option("braftonxml_default_author", ''),
            'braftonArticleStatus'      => 0,
            'braftonArticlePostType'    => 0,
            'braftonCustomSlug'         => '',
            'braftonArticleExistingPostType'   => 0,
            'braftonArticleExistingCategory'    => '',
            'braftonArticleExistingTag'    => '',
            'braftonArchiveImporterStatus'  => 0,
            'braftonVideoStatus'        => 0,
            'braftonVideoPublicKey'     => get_option("braftonxml_videoPublic", 'XXXXX'),
            'braftonVideoPrivateKey'    => get_option("braftonxml_videoSecret", 'XXXXXXXXXXX'),
            'braftonVideoFeed'          => 0,
            'braftonVideoHeaderScript'  => 'atlantisjs',
            'braftonImportJquery'       => 'off',
            'braftonVideoCSS'           => 'off',
            'braftonVideoCTA'           => array(
                    'pausedText'            => '',
                    'pausedLink'            => '',
                    'pauseAssetGatewayId'   => '',
                    'endingTitle'           => '',
                    'endingSubtitle'        => '',
                    'endingButtonImage'     => '',
                    'endingButtonPositionOne'   => 'top',
                    'endingButtonPositionOneValue'  => 0,
                    'endingButtonPositionTwo'   => 'left',
                    'endingButtonPositionTwoValue'  => 0,
                    'endingButtonText'      => '',
                    'endingButtonLink'      => '',
                    'endingAssetGatewayId'   => '',
                    'endingBackground'      => ''
                    ),
            'braftonMarproStatus'       => 'off',
            'braftonMarproId'           => '',
            'braftonOpenGraphStatus'    => 'off',
            'braftonRestyle'            => 0,
            'braftonArticleLimit'       => 30,
            'braftonVideoLimit'         => 30,
            'braftonPauseColor'         => '',
            'braftonEndBackgroundcolor' => '',
            'braftonEndTitleColor'      => '',
            'braftonEndTitleAlign'      => 'center',
            'braftonEndSubTitleColor'   => '',
            'braftonEndSubTitleBackground' => '',
            'braftonEndSubTitleAlign'   => 'left',
            'braftonEndButtonBackgroundColor'   => '',
            'braftonEndButtonTextColor' => '',
            'braftonEndButtonBackgroundColorHover'  => '',
            'braftonEndButtonTextColorHover'    => '',
            'braftonEndTitleBackground' => '',
            'braftonEnableCustomCSS'    => 0,
            'braftonCustomCSS'  => BraftonOptions::getCSS(),
            'braftonPullQuotes' => 0,
            'braftonPullQuoteWidth' => 25,
            'braftonPullQuoteFloat' => 'left',
            'braftonPullQuoteMargin'    => 5,
            'braftonInlineImages' => 0,
            'braftonInlineImageWidth' => 25,
            'braftonInlineImageFloat' => 'left',
            'braftonInlineImageMargin'    => 5,
            'braftonVideoOutput'        => 0
            );
        //checks for a previous instance of the options array and merges already set values with the default array.  This accounts for new features and new options added to a new version of the importer
        if($old_options = get_option('BraftonOptions')){
            $default_options = wp_parse_args($old_options, $default_options);
            update_option('BraftonOptions', $default_options);
        } else{
            add_option('BraftonOptions', $default_options);
        }

    }

    //Gets all the options for use in an external variable outside the class.  Returns an associative array $options
    public function getAll(){
        return $this->options;
    }
    //gets a fresh instance of the variables from the database for a single use.  Return a fresh associative array for use in the class.
    private function getInstance(){
        $instance = get_option('BraftonOptions');
        $array = $instance;
        return $array;
    }
    //sets one option and saves that option to the database resets the $options array with the new data.  Note this will not affect any external variables currently holding the associative array returned previously.
    public function saveOption($option, $value){
        $this->options[$option] = $value;
        /*
        $this->saveAllOptions();
        $this->ser_options = get_option('BraftonOptions');
        $this->options = $this->ser_options;
        */
        update_option('BraftonOptions', $this->options);
    }
    static function saveAllOptions(){
        $old_options = get_option('BraftonOptions');
        $old_array = $old_options;
        foreach($_POST as $key => $val){
            if(isset($old_options[$key])){
                $old_options[$key] = $val;
            }
        }
        update_option('BraftonOptions', $old_options);
        //checks if the importer is turned on
        if($old_options['braftonStatus']){
            //Checks if the Article loader is on if not it will disable the cron for articles if it has previously been enabled.
            if($old_options['braftonArticleStatus']){
                if(!wp_next_scheduled('braftonSetUpCron')){
                    wp_clear_scheduled_hook('braftonSetUpCron');
                    //importer is set to go off 2 minutes after it is enabled than hourly after that
                    $schedule = wp_schedule_event(time()+120, 'hourly', 'braftonSetUpCron');
                }
            }
            else{ wp_clear_scheduled_hook('braftonSetUpCron'); }
            //checks if the video loader is on if not it will disable to the cron for videos if it has previously been enabled
            if($old_options['braftonVideoStatus']){
                if(!wp_next_scheduled('braftonSetUpCronVideo')){
                    wp_clear_scheduled_hook('braftonSetUpCronVideo');
                    //importer is set to go off 2 minutes after it is enabled than daily after that
                    $schedule = wp_schedule_event(time()+120, 'twicedaily', 'braftonSetUpCronVideo');
                }
            }
            else{ wp_clear_scheduled_hook('braftonSetUpCronVideo'); }
        }
        else{//Importer is turned off clear out both cron jobs
            wp_clear_scheduled_hook('braftonSetUpCron');
            wp_clear_scheduled_hook('braftonSetUpCronVideo');
        }
        $saved = 'saved';
    }
    //Private function to destroy all brafton options
    private function destroyOptions(){
        delete_option('BraftonOptions');
    }
    //Resets all Brafton Options to factory Defaults
    public function resetOptions(){
        $this->destroyOptions();
        $this->ini_BraftonOptions();

    }
    //Gets an instance of a specific variable name ($option, $new=false) $option = Option name, $new = boolean (true returns fresh instance from database, false returns current option value held in the $this->options array
    public function getOptions($option, $new=false){
        if(isset($this->options) && (!$new)){
            return $this->options[$option];
        }
        $sOption = $this->getInstance();
        return $sOption[$option];
    }
    static function getSingleOption($option){
           $instance = get_option('BraftonOptions');
        $array = $instance;
        return $array[$option];
    }
    static function getErrors(){
        $jsonErrors = get_option('brafton_e_log');
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=Brafton_Errors_".date('Y-M-d-(h.m.s)')."-".$_SERVER['HTTP_HOST'].".txt");
        echo '<pre>';
        var_dump($jsonErrors);
        echo '</pre>';
    }
    static function getCSS(){
        $css=<<<EOT
/* Effects the puase cta background color */
span.video-pause-call-to-action, span.ajs-video-annotation{
    background-color:;
}
/* effects the pause cta text color */
span.video-pause-call-to-action a:link, span.video-pause-call-to-action a:visited{
    color:;
    font-weight:900;
}
/* effects the end of video background color *Note: has no effect if a background image is selected */
div.ajs-end-of-video-call-to-action-container{
    background-color:;
}
/* effects the end of video title tag */
div.ajs-end-of-video-call-to-action-container h2{
    background:transparent;
    color:;
    text-align:center;
}
/* effects the end of video subtitle tags */
div.ajs-end-of-video-call-to-action-container p{
    background:transparent;
    color:;
    text-align:left;
}
/* effects the end of video button *Note: has no effect if button image is selected */
a.ajs-call-to-action-button{
     background-color:;
    color:#000;
}
/* effects the end of video button on hover and  *Note: has no effects if button image is selected */
a.ajs-call-to-action-button:hover, a.ajs-call-to-action-button:visited{
    background-color:;
    color:#000;
}
EOT;
        return $css;
    }
}
?>
