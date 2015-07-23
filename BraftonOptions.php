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
        $this->ini_BraftonOptions();
        //Gets the options stored in the database and stores the associative array in the $this->options
        $this->ser_options = get_option('BraftonOptions');
        $this->options = $this->ser_options;
    }
    //This method checks for the existance of Brafton options.  If there are no Brafton Options it will initialize them all to start. If they already exsist return false. This method is only called when the plugin is activated with the the register_activation_hook().  It is the Second class to be called after BraftonErrors().
    static function ini_BraftonOptions(){
        $default_options = array(
            'braftonDebugger'           => 0,
            'braftonCategories'         => 'categories',
            'braftonCustomCategories'    => '',
            'braftonTags'               => 'none_tags',
            'braftonCustomTags'         => '',
            'braftonPublishDate'        => 'published',
            'braftonPostStatus'         => 'publish',
            'braftonImporterUser'       => '',
            'braftonStatus'             => 0,
            'braftonClearLog'           => 0,
            'braftonApiDomain'          => 'api.brafton.com',
            'braftonApiKey'             => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'braftonUpdateContent'      => 0,
            'braftonArticleDynamic'     => 'n',
            'braftonArticleAuthorDefault'   => '',
            'braftonArticleStatus'      => 0,
            'braftonArticlePostType'    => 0,
            'braftonCustomSlug'         => '',
            'braftonArticleExistingPostType'   => 0,
            'braftonArticleExistingCategory'    => '',
            'braftonArticleExistingTag'    => '',
            'braftonArchiveImporterStatus'  => 0,
            'braftonVideoStatus'        => 0,
            'braftonVideoPublicKey'     => 'XXXXX',
            'braftonVideoPrivateKey'    => 'XXXXXXXXXXX',
            'braftonVideoFeed'          => 0,
            'braftonVideoHeaderScript'  => 'atlantisjs',
            'braftonImportJquery'       => 'off',
            'braftonVideoCSS'           => 'off',
            'braftonVideoCTA'           => array(
                    'pausedText'            => '',
                    'pausedLink'            => '',
                    'endingTitle'           => '',
                    'endingSubtitle'        => '',
                    'endingButtonText'      => '',
                    'endingButtonLink'      => ''
                    ),
            'braftonMarproStatus'       => 'off',
            'braftonMarproId'           => '',
            'braftonOpenGraphStatus'    => 'off',
            'braftonRestyle'            => 0,
            'braftonArticleLimit'       => 30,
            'braftonVideoLimit'         => 30
        );
        //checks for a previous instance of the options array and merges already set values with the default array.  This accounts for new features and new options added to a new version of the importer
        if($old_options = get_option('BraftonOptions')){
            $default_options = wp_parse_args($old_options, $default_options);
            update_option('BraftonOptions', $default_options);
        }
        //Checks for the previously used type of options from any importer "Black Eye" or older and forms those options into an array to merge with new array.  This maintains many of the new settings from the old importer versions while maintaining integrity of the new versions
        elseif(get_option('braftonxml_domain')){
            $old_options = array(
            'braftonDebugger'           => 0,
            'braftonCategories'         => get_option("braftonxml_sched_cats"),
            'braftonCustomCategories'    => get_option("braftonxml_sched_cats_input"),
            'braftonTags'               => get_option("braftonxml_sched_tags"),
            'braftonCustomTags'         => get_option("braftonxml_sched_tags_input"),
            'braftonPublishDate'        => get_option("braftonxml_publishdate"),
            'braftonImporterUser'       => '',
            'braftonStatus'             => 0,
            'braftonClearLog'           => 0,
            'braftonApiDomain'          => get_option("braftonxml_domain"),
            'braftonApiKey'             => get_option("braftonxml_sched_API_KEY"),
            'braftonUpdateContent'      => 0,
            'braftonArticleDynamic'     => get_option("braftonxml_dynamic_author"),
            'braftonArticleAuthorDefault'   => get_option("braftonxml_default_author"),
            'braftonArticleStatus'      => 0,
            'braftonArticlePostType'    => 0,
            'braftonCustomSlug'         => '',
            'braftonArticleExistingPostType'   => 0,
            'braftonArticleExistingCategory'    => '',
            'braftonArticleExistingTag'    => '',
            'braftonArchiveImporterStatus'  => 0,
            'braftonVideoStatus'        => 0,
            'braftonVideoPublicKey'     => get_option("braftonxml_videoPublic"),
            'braftonVideoPrivateKey'    => get_option("braftonxml_videoSecret"),
            'braftonVideoFeed'          => 0,
            'braftonVideoHeaderScript'  => 'atlantisjs',
            'braftonImportJquery'       => 'off',
            'braftonVideoCSS'           => 'off',
            'braftonVideoCTA'           => array(
                    'pausedText'            => '',
                    'pausedLink'            => '',
                    'endingTitle'           => '',
                    'endingSubtitle'        => '',
                    'endingButtonText'      => '',
                    'endingButtonLink'      => ''
                    ),
            'braftonMarproStatus'       => 'off',
            'braftonMarproId'           => '',
            'braftonOpenGraphStatus'    => 'off',
            'braftonRestyle'            => 0,
            'braftonArticleLimit'       => 30,
            'braftonVideoLimit'         => 30
            );
            $default_options = wp_parse_args($old_options, $default_options);
            add_option('BraftonOptions', $default_options);
        }
        else{
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
        $this->saveAllOptions();
        $this->ser_options = get_option('BraftonOptions');
        $this->options = $this->ser_options;
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
}
?>