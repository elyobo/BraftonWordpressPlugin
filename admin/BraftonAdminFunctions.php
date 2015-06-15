<?php
// test feed brafton high 528a432c-2f60-4dc8-80fe-4cebc1fe25ca
if(isset($_POST['submit'])){
    switch ($_POST['submit']){
        case 'Save Settings':
        $save = BraftonOptions::saveAllOptions();
        break;
        case 'Upload Archive':
        $archive = new BraftonArticleLoader();
        $archive->loadXMLArchive();
        break;
        case 'Save Errors':
        $er = BraftonErrorReport::errorPage();
        break;
        case 'Import Articles':
        $import = new BraftonArticleLoader();
        $import->ImportArticles();
        break;
        case 'Import Videos':
        $import = new BraftonVideoLoader();
        $import->ImportVideos();
        case 'Get Categories';
        $import = new BraftonArticleLoader();
        $import->ImportCategories();
        break;
        }
}

/*
 *********************************************************************************************************************
 *
 * Admin Page Utility Functions
 *
 *********************************************************************************************************************
 */

//function for displaying instructions for each section.  gets passed the information from the add_settings_section() fucntion defined in the set_brafton_settings() function
function getOptions(){
    $option = new BraftonOptions();
    return $option->getAll();
}

//option utility functions
function checkRadioVal($val, $check, $return=NULL){
    if($val == $check){
        if($return == NULL){
            echo 'checked';
        }
        else{
            echo $return;
        }
    }
}

function tooltip($tip){ ?>
    <img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/tt.png" class="brafton_tt" title="<?php echo $tip; ?>">
<?php }

function switchCase($brand){
    switch ($brand){
        case 'api.brafton.com':
        return 'Brafton';
        break;
        case 'api.contentlead.com':
        return 'ContentLEAD';
        break;
        case 'api.castleford.com.au':
        return 'Castleford';
        break;
    }
}
/*
function for displaying errors that relate to the importer only
*/
function braftonWarnings(){
    $options = getOptions();
    //check if importer settings are valid if they are not throw error // this function should be re-written
    if(isset($saved)){
        echo '<div class="updated">
				<p>Options Saved Successfully</p>
				</div>';
    }
	if (!$options['braftonStatus'])
	{
		echo '<div class="error">
				<p>Importer not enabled.</p>
				</div>';
	}
    //check if curl is enabled throw warning if it is not
    if(!function_exists('curl_init')){
        echo '<div class="error">
				<p>Curl not enabled.</p>
				</div>';
    }
    if(!current_theme_supports( 'post-thumbnails' )) {
        echo '<div class="updated">
                <p>Thumbnails not enabled for this Theme.</p>
                </div>';
    }
    //If importer was run manually and had errors throws a warning
    if(isset($_GET['b_error'])){
            echo '<div class="error">
				<p>The Importer Failed to Run</p>
				</div>';
    }
    //get the last importer run time for Articles 
    $status = 'updated';
    $last_run_time = wp_next_scheduled('braftonSetUpCron');
    $last_run_time_video = wp_next_scheduled('braftonSetUpCronVideo');
    
    $last_run = 'N/A';
    $last_run_video = 'N/A';
    if($last_run_time){
        $last_run = date('F d Y h:i:s', $last_run_time);
    }
    if($last_run_time_video){
        $last_run_video = date('F d Y h:i:s', $last_run_time_video);
    }
    $time = time();
    $current_time = date('F d Y h:i:s', $time);
    if(($last_run_time) && $last_run_time < $time){
        echo "<div class='error'>
				<p>The Article Importer Failed to Run at its scheduled time.  Contact tech@brafton.com</p>
				</div>";
        $failed_error = new BraftonErrorReport(BraftonOptions::getSingleOption('braftonApiKey'),BraftonOptions::getSingleOption('braftonApiDomain'), BraftonOptions::getSingleOption('braftonDebugger') );
        trigger_error('Article Importer has failed to run.  The cron was scheduled but did not trigger at the appropriate time');
    }
    if(($last_run_time_video) && $last_run_time_video < $time){
        echo "<div class='error'>
				<p>The Video Importer Failed to Run at its scheduled time.  Contact tech@brafton.com</p>
				</div>";
    }
    echo "<div class='$status'>
                <p>Current Time: $current_time</p>
				<p>Next Article Run: $last_run</p>
                <p>Next Video Run: $last_run_video</p>
				</div>";
}
/*
function for displaying the sections information
*/
function print_section_info($args){
    switch ($args['id']){
        case 'general':
            echo '<p>This section controls the general settings for your importer.  Features for this plugin may depend on your settings in this section.  If you need help with your settings you may contact your CMS or visit <a href="http://www.brafton.com/support" target="_blank">Our Support Page</a> for assistance.</p>';
        break;
        case 'error':
            echo '<p>This section provides a log of any errors that may have occured</p>';
        break;
        case 'article':
            echo '<p>This section is for setting your article specific settings.  All settings on this page are independant of your video settings.';
        break;
        case 'video':
            echo '<p>This section is for setting your video specific settings.  All settings on this page are independant of your article settings.';
        break;
        case 'marpro':
            echo '<p>This section is for settings related to our Pumpkin Product, which handles lead capture and Call To Action features.</p>';
        break;
        case 'archive':
            echo '<p>This is for uploading an archive provided to you by your CMS</p>';
        break;
        case 'control':
            echo '<p>You can manually run the importer at any point by selecting which importer you would like to run.  If you are receiving both Vidoes, and Articles you will have to run the importer for each one seperately.  The importer does run each hour for both automatically provided it is turned on.</p>';
    }
}
/*
 ************************************************************************************************
 *
 * Error Tab Functions Section
 *
 ************************************************************************************************
 */
function ErrorSettingsSetup(){
    //Error Logs Tab
        register_setting(
            'brafton_error_options', // Option group
            'brafton_error' );
        //sets a section name for the options
        add_settings_section(
            'error', // ID
            'Error Log', // Title
            'print_section_info', // Callback
            'brafton_error' // Page
        );  
        //each one of these adds a field with the options
        add_settings_field(
            'braftonDebugger', // ID
            'Debug Mode', // Title 
            'braftonDebugger' , // Callback
            'brafton_error', // Page
            'error' // Section           
        );
        add_settings_field(
            'braftonClearLog', // ID
            'Clear Error Log', // Title 
            'braftonClearLog' , // Callback
            'brafton_error', // Page
            'error' // Section           
        );
        add_settings_field(
            'braftonDisplayLog', // ID
            'Error Log', // Title 
            'braftonDisplayLog' , // Callback
            'brafton_error', // Page
            'error' // Section           
        );
}

//Displays the Option for Turning on the Debugger
function braftonDebugger(){
    $options = getOptions();
    $tip = 'Turns on Debugging Mode.  While enabled all errors are displayed to the user';
    tooltip($tip); ?>
    <input type="radio" name="braftonDebugger" value="1" <?php checkRadioVal($options['braftonDebugger'], 1); ?>> ON
    <input type="radio" name="braftonDebugger" value="0" <?php checkRadioVal($options['braftonDebugger'], 0); ?>> OFF
<?php 
}

//Displays the option for Clearing the error Log from the database
function braftonClearLog(){
    $options = getOptions();
    tooltip('Only set to Clear Log if errors have been corrected.  This log provides a record of errors thrown while the importer is running.');
    ?>
    <input type="radio" name="braftonClearLog" value="1" <?php checkRadioVal($options['braftonClearLog'], 1); ?>> Clear Log
    <input type="radio" name="braftonClearLog" value="0" <?php checkRadioVal($options['braftonClearLog'], 0); ?>> Leave Log Intact
<?php  
}

//Displays any errors currently in the Database from the braftonErrors Option
function braftonDisplayLog(){
    $tip = 'Displays the actual errors for the importer';
    tooltip($tip); ?>
    <div class="b_e_display">
        <pre>
        <?php $errors = get_option('brafton_e_log');
        if(!$errors){ echo 'Everythin is fine. You have no errors'; }
        //convert obj to array
        $errors = $errors;
        if(is_array($errors)){
            $errors = array_reverse($errors);
        }
        for($i=0;$i<count($errors);++$i){
            echo $errors[$i]['client_sys_time'].':<br/>----'.$errors[$i]['error'].'<br>';
        }
        ?>
        </pre>
    </div>
<?php 
}
/********************************************************************************************
 *
 * General Settings Tab Functions Section
 *
 ********************************************************************************************
 */

//Set Up the General Settings Section
function GeneralSettingsSetup(){
//General Settings Tab
        register_setting(
            'brafton_general_options', 
            'brafton_options' );
        add_settings_section(
            'general', // ID
            'General Importer Settings', // Title
            'print_section_info', // Callback
            'brafton_general' // Page
        );  
        //each one of these adds a field with the options
        add_settings_field(
            'braftonImporterOnOff', // ID
            'Master Importer Status', // Title 
            'braftonStatus' , // Callback
            'brafton_general', // Page
            'general' // Section           
        );
        add_settings_field(
            'braftonApiDomain', // ID
            'API Domain', // Title 
            'braftonApiDomain' , // Callback
            'brafton_general', // Page
            'general' // Section           
        );
        add_settings_field(
            'braftonImporterUser', // ID
            'Importer User', // Title 
            'braftonImporterUser' , // Callback
            'brafton_general', // Page
            'general' // Section           
        );
        add_settings_field(
            'braftonImportJquery',
            'Import JQuery Script',
            'braftonImportJquery',
            'brafton_general',
            'general'
        );
        add_settings_field(
            'braftonRestyle',
            'Add Premium Styles',
            'braftonRestyle',
            'brafton_general',
            'general'
        );            
        add_settings_field(
            'braftonDefaultPostStatus',
            'Default Post Status',
            'braftonDefaultPostStatus',
            'brafton_general',
            'general'
        );
        add_settings_field(
            'braftonCategories',
            'Categories',
            'braftonCategories',
            'brafton_general',
            'general'
        );
        add_settings_field(
            'braftonCustomCategories',
            'Custom Categories',
            'braftonCustomCategories',
            'brafton_general',
            'general'
        );
        add_settings_field(
            'braftonTags',
            'Tag Options',
            'braftonTags',
            'brafton_general',
            'general'
        );
        add_settings_field(
            'braftonCustomTags',
            'Custom Tags',
            'braftonCustomTags',
            'brafton_general',
            'general'
        );
        add_settings_field(
            'braftonSetDate',
            'Publish Date',
            'braftonSetDate',
            'brafton_general',
            'general'
        );
        add_settings_field(
            'braftonOpenGraphStatus',
            'Add OG Tags',
            'braftonOpenGraphStatus',
            'brafton_general',
            'general'
        );
        add_settings_field(
            'braftonUpdateContent',
            'Update Existing Content',
            'braftonUpdateContent',
            'brafton_general',
            'general'
        );   
}
//Option enables setting up override styles
//TODO : Will be moving to s seperate section to inself for style
function braftonRestyle(){
    $options = getOptions();
    tooltip('Sometimes with our premium content user stylesheets can cause confilicts with the styles for the content.  Enable this feature to correct for this problem.  NOTE: You must have JQuery on your site for this to work.  If you currently do not have JQuery you can add it with the option above.');
    ?>
    <input type="radio" name="braftonRestyle" value="1" <?php checkRadioVal($options['braftonRestyle'], 1); ?>> Add Style Correction
    <input type="radio" name="braftonRestyle" value="0" <?php checkRadioVal($options['braftonRestyle'], 0); ?>> No Style Correction
<?php  
}

//Importing a copy of JQuery for use if not currently using a copy.  JQuery is required for video playback using AtlantisJS as well as Marpro and syle overrides
function braftonImportJquery(){
    $options = getOptions();
    $tip = 'Some sites already have jquery, set this to off if additional jquery script included with atlantisjs is causing issues.';
    tooltip($tip); ?>
    <input type="radio" name="braftonImportJquery" value="on" <?php	checkRadioval($options['braftonImportJquery'], 'on'); ?> /> On
    <input type="radio" name="braftonImportJquery" value="off" <?php checkRadioval($options['braftonImportJquery'], 'off'); ?>/> Off
<?php 
}

//Displays the option for enabling open graph tags for single article pages
function braftonOpenGraphStatus(){
    $options = getOptions();
    tooltip('Adds og: tags to the single.php pages.  These tags are used for social media sites.  Check if you have another SEO plugin currently generating these tags before turning them on. Support for Twitter Cards and Google+ in addition to Facebook.  Note: Twitter requires approval for sharing twitter cards. ');
    ?>
    <input type="radio" name="braftonOpenGraphStatus" value="1" <?php checkRadioVal($options['braftonOpenGraphStatus'], 1); ?>> Add Tags
    <input type="radio" name="braftonOpenGraphStatus" value="0" <?php checkRadioVal($options['braftonOpenGraphStatus'], 0); ?>> No Tags
<?php  
}

//Display the option for Brafton Categories
function braftonCategories(){
    $options = getOptions();
    $tip = 'This option is for using categories set by the article when importer.  *RECOMENDATION: Set to Brafton Categories';
    tooltip($tip); ?>
<input type="radio" name="braftonCategories" value="categories" <?php checkRadioval($options['braftonCategories'], 'categories'); ?> /> Brafton Categories                
<input type="radio" name="braftonCategories" value="none_cat" <?php checkRadioval($options['braftonCategories'], 'none_cat');
?> /> None
<?php 
}

//Displays the option for using custom categories
function braftonCustomCategories(){
    $options = getOptions();
    $tip = 'Each category seperated by a comma. I.E. (first,second,third)';
    tooltip($tip); ?>
<input type="text" name="braftonCustomCategories" value="<?php
		echo $options['braftonCustomCategories'];
?>"/>
<?php     
}

//Displays the option for support for Tags
function braftonTags(){
    $options = getOptions();
    $tip = 'Tags are rarely used and hold no true SEO Value, however we provide you with options if you choose to use them. The Option you select must be included in your XML Feed.';
    tooltip($tip); ?>
<input type="radio" name="braftonTags" value="tags" <?php checkRadioval($options['braftonTags'], 'tags'); ?> />Tags as tags<br />              
<input type="radio" name="braftonTags" value="keywords" <?php checkRadioval($options['braftonTags'], 'keywords');?> />Keywords as tags<br />
<input type="radio" name="braftonTags" value="cats" <?php checkRadioval($options['braftonTags'], 'cats');?> />Categories as tags<br />
<input type="radio" name="braftonTags" value="none_tags" <?php checkRadioval($options['braftonTags'], 'none_tags');?> /> None 
<?php 
}

//Displays the option for Custom Tags
function braftonCustomTags(){
    $options = getOptions();
    $tip = 'Each tag seperated by a comma. I.E. (first,second,third)';
    tooltip($tip); ?>
<input type="text" name="braftonCustomTags" value="<?php
		echo $options['braftonCustomTags']; ?>"/>
<?php 
}

//Displays the option for setting the date for an article when imported
function braftonSetDate(){
    $options = getOptions();
    $tip = 'Specify which date from your XML Feed to use as the publish date upon import';
    tooltip($tip); ?>
<input type="radio" name="braftonPublishDate" value="published" <?php checkRadioval($options['braftonPublishDate'], 'published'); ?> /> Published
<input type="radio" name="braftonPublishDate" value="modified" <?php checkRadioval($options['braftonPublishDate'], 'modified'); ?>/> Last Modified
<input type="radio" name="braftonPublishDate" value="created" <?php checkRadioval($options['braftonPublishDate'], 'created'); ?>/> Created
<?php 
}

//Displays the option for Default Post Status upon import
function braftonDefaultPostStatus(){
    $options = getOptions();
    $tip = 'Sets the default Post status for articles and video imported.  Draft affords the ability to approve an article before it is made live on the blog';
    tooltip($tip); ?>
    <input type="radio" name="braftonPostStatus" value="publish" <?php checkRadioval($options['braftonPostStatus'], 'publish'); ?> /> Published
    <input type="radio" name="braftonPostStatus" value="draft" <?php checkRadioval($options['braftonPostStatus'], 'draft'); ?>/> Draft
    <input type="radio" name="braftonPostStatus" value="private" <?php checkRadioval($options['braftonPostStatus'], 'private'); ?>/> Private
<?php     
}

//Displays the option for setting the importer user.  This option used to allow HTMl Tags needed for Premium content including but not limited to script, input, style ect.
function braftonImporterUser(){ 
    $options = getOptions();
    $args = array(
        'role'      => 'administrator'
    );
    $admins = get_users($args);
    $tip = 'Designate a User for the Importer. *NOTE: This is different than the Author';
    tooltip($tip); ?>
    <select name="braftonImporterUser">
        <option value="">Select Importer User</option><?php 
    foreach($admins as $u){ ?>
        <option value="<?php echo $u->user_login; ?>" <?php checkRadioval($options['braftonImporterUser'], $u->user_login, 'selected'); ?>><?php echo $u->user_login; ?></option><?php 
    }
        ?></select>
  
<?php 
}

//Displays the option Turning the Importer itself OFF/ON.  
function braftonStatus(){
    $options = getOptions();
    $tip = 'Turns the Importer ON/OFF while still maintaing your settings.';
    tooltip($tip); ?>
    <input type="radio" name="braftonStatus" value="1" <?php checkRadioval($options['braftonStatus'], 1); ?>> ON
    <input type="radio" name="braftonStatus" value="0" <?php checkRadioval($options['braftonStatus'], 0); ?>> OFF 
<?php 
}

//Displays the Options for setting the API Domain
function braftonApiDomain(){
    $options = getOptions();
    $tip = 'Set the domain your XML Feed originates from.  This information can be obtained from your CMS.';
    tooltip($tip); ?>
    	<select name='braftonApiDomain'>
            <option value="api.brafton.com" <?php checkRadioval($options['braftonApiDomain'], 'api.brafton.com', 'selected'); ?>>Brafton</option>
            <option value="api.contentlead.com" <?php checkRadioval($options['braftonApiDomain'], 'api.contentlead.com', 'selected'); ?>>ContentLEAD</option>
            <option value="api.castleford.com.au" <?php checkRadioval($options['braftonApiDomain'], 'api.castleford.com.au', 'selected'); ?>>Castleford</option>

        </select>
<?php
}

/********************************************************************************************
 *
 * Article settings Tab functions Section
 *
 ********************************************************************************************
 */

function ArticleSettingsSetup(){
//Articles Tab
        register_setting(
            'brafton_article_options', // Option group
            'brafton_article' );
        //sets a section name for the options
        add_settings_section(
            'article', // ID
            'Article Importer Settings', // Title
            'print_section_info', // Callback
            'brafton_article' // Page
        );
        add_settings_field(
            'braftonArticleStatus',
            'Article Importer Status',
            'braftonArticleStatus',
            'brafton_article',
            'article'
        );
        add_settings_field(
            'braftonApiKey', // ID
            'API Key', // Title 
            'braftonApiKey' , // Callback
            'brafton_article', // Page
            'article' // Section           
        );
        add_settings_field(
            'braftonArticleDynamic',
            'Dynamic Author',
            'braftonArticleDynamic',
            'brafton_article',
            'article'
        );
        add_settings_field(
            'braftonArticleAuthorDefault',
            'Default Author',
            'braftonArticleAuthorDefault',
            'brafton_article',
            'article'
        );
        add_settings_field(
            'braftonArticlePostType',
            'Set Custom Post Type',
            'braftonArticlePostType',
            'brafton_article',
            'article'
        );   
}

//Displays the Option for setting the API Key for use with the Artile Importer
function braftonApiKey(){ 
    $options = getOptions();
    $tip = 'Enter Your API Key for your XML Feed.  This information can be obtained from your CMS. (Example: 2de93ffd-280f-4d4b-9ace-be55db9ad4b7)'; 
    tooltip($tip); ?>
    <input type="text" name="braftonApiKey" id="brafton_api_key" value="<?php echo $options['braftonApiKey']; ?>" />

<?php 
}

//Displays the option for allowing overriding of previously imported articles.
function braftonUpdateContent(){
    $options = getOptions();
    $tip = 'Setting this to ON will override edits made to posts within the last 30 days or using an archive file';
    tooltip($tip); ?>
<input type="radio" name="braftonUpdateContent" value="1" <?php checkRadioval($options['braftonUpdateContent'], 1); ?> /> On
<input type="radio" name="braftonUpdateContent" value="0" <?php checkRadioval($options['braftonUpdateContent'], 0); ?>/> Off
<?php     
}

//Displays the options for Using Dynamic Authors
function braftonArticleDynamic(){
    $options = getOptions();
    $tip = "Sets Author to 'byLine' From the feed. If the Author does not exsist they will be added.
Default auhor is returned if no author is set int he field or if new author cannot be created.";
    tooltip($tip); ?>
<input type="radio" name="braftonArticleDynamic" value="y" <?php checkRadioval($options['braftonArticleDynamic'], 'y'); ?> />Enable              
<input type="radio" name="braftonArticleDynamic" value="n" <?php checkRadioval($options['braftonArticleDynamic'], 'n'); ?> />Disable<br />
<?php 
}

//Displays the options for selecting the default author of imported content from a list of users.
function braftonArticleAuthorDefault(){
    $options = getOptions();
    $tip = 'Set the Default Author for Articles upon Import';
    tooltip($tip); 
    wp_dropdown_users(array(
			'name' => 'braftonArticleAuthorDefault',
			'hide_if_only_one_author' => true,
			'selected' => $options['braftonArticleAuthorDefault']
		));
}

//Displays the Options for turning the Article Importer OFF/ON
function braftonArticleStatus(){
    $options = getOptions();
    $tip = 'Turns the Article Importer ON/OFF.';
    tooltip($tip); ?>
    <input type="radio" name="braftonArticleStatus" value="1" <?php checkRadioval($options['braftonArticleStatus'], 1); ?>> ON
    <input type="radio" name="braftonArticleStatus" value="0" <?php checkRadioval($options['braftonArticleStatus'], 0); ?>> OFF 
<?php    
}

//Displays the Options for turning on custom post types for brafton content_ur
function braftonArticlePostType(){
    $options = getOptions();
    $tip = 'Turn this option on to set custom post type for '.$options['braftonApiDomain'].' Content';
    tooltip($tip); ?>
    <input type="radio" name="braftonArticlePostType" value="1" <?php checkRadioval($options['braftonArticlePostType'], 1); ?>> ON
    <input type="radio" name="braftonArticlePostType" value="0" <?php checkRadioval($options['braftonArticlePostType'], 0); ?>> OFF 
<?php    
}


/*
 *****************************************************************************************************
 *
 * Archive Settings Tab function Section
 *
 *****************************************************************************************************
 */
function ArchiveSettingSetup(){
    //Archives Tab
        register_setting(
            'brafton_archive_options', // Option group
            'brafton_archive' );
        //sets a section name for the options
        add_settings_section(
            'archive', // ID
            'Upload an Article Archive', // Title
            'print_section_info', // Callback
            'brafton_archive' // Page
        );
        add_settings_field(
            'braftonArchiveImporterStatus',
            'Archive Importer Status',
            'braftonArchiveImporterStatus',
            'brafton_archive',
            'archive'
        );
        add_settings_field(
            'braftonArchiveUpload',
            'Upload an XML File',
            'braftonArchiveUpload',
            'brafton_archive',
            'archive'
        );
}
//Displays the options for uploading an archive file in place of retrieving a remote feed url
function braftonArchiveUpload(){
    $tip = 'Select an XML file to upload';
    tooltip($tip); ?>
<input type="file" id="braftonUpload" name="archive" size="40" disabled>
<?php 
}
//Display sthe option for turning on the archive importer.  Must be tuned ON to be able to upload an archive file.
function braftonArchiveImporterStatus(){
    $options = getOptions();
    $tip = 'Turns the ARchive Importer ON/OFF.  If this option is turned OFF selecting a file will result in nothing being imported.  You must turn this option ON AND upload a file.';
    tooltip($tip); ?>
    <input type="radio" class="archiveStatus" name="braftonArchiveImporterStatus" value="1" <?php checkRadioval($options['braftonArchiveImporterStatus'], 1); ?>> ON
    <input type="radio" class="archiveStatus" name="braftonArchiveImporterStatus" value="0" <?php checkRadioval($options['braftonArchiveImporterStatus'], 0); ?>> OFF 
<?php    
}

/*
 **********************************************************************************************
 *
 * Video Settings Tab function Section
 *
 **********************************************************************************************
 */

function VideoSettingsSetup(){
    //Videos Tab
        register_setting(
            'brafton_video_options', // Option group
            'brafton_video' );
        //sets a section name for the options
        add_settings_section(
            'video', // ID
            'Video Importer Settings', // Title
            'print_section_info', // Callback
            'brafton_video' // Page
        );
        add_settings_field(
            'braftonVideoStatus',
            'Video mporter Status',
            'braftonVideoStatus',
            'brafton_video',
            'video'
        );
        add_settings_field(
            'braftonVideoPublicKey',
            'Public Key',
            'braftonVideoPublicKey',
            'brafton_video',
            'video'
        );
        add_settings_field(
            'braftonVideoPrivateKey',
            'Private Key',
            'braftonVideoPrivateKey',
            'brafton_video',
            'video'
        );
        add_settings_field(
            'braftonVideoFeed',
            'Feed Number',
            'braftonVideoFeed',
            'brafton_video',
            'video'
        );
        add_settings_field(
            'braftonVideoHeaderScript',
            'Video Script',
            'braftonVideoHeaderScript',
            'brafton_video',
            'video'
        );
        add_settings_field(
            'braftonVideoCSS',
            'Video CSS Fix',
            'braftonVideoCSS',
            'brafton_video',
            'video'
        );
        add_settings_field(
            'braftonVideoCTAs',
            "AtlantisJS CTA's",
            'braftonVideoCTAs',
            'brafton_video',
            'video'
        );
}

//Displays the options to turn the Video Importer OFF/ON
function braftonVideoStatus(){
    $options = getOptions();
    $tip = 'Turns the Video Importer ON/OFF.';
    tooltip($tip); ?>
    <input type="radio" name="braftonVideoStatus" value="1" <?php checkRadioval($options['braftonVideoStatus'], 1); ?>> ON
    <input type="radio" name="braftonVideoStatus" value="0" <?php checkRadioval($options['braftonVideoStatus'], 0); ?>> OFF 
<?php     
}

//Displays the option for entering Public Key for Video Feed
function braftonVideoPublicKey(){
    $options = getOptions();
    $tip = 'Enter your Public Key provided to you from your CMS';
    tooltip($tip); ?>
<input type="text" name="braftonVideoPublicKey" id="brafton_video_public" value="<?php
		echo $options['braftonVideoPublicKey']; ?>" />
<?php     
}

//displays the option for entering Private key for video feed
function braftonVideoPrivateKey(){
    $options = getOptions();
    $tip = 'Enter your Prive Key provided to you from your CMS';
    tooltip($tip); ?>
<input type="text" name="braftonVideoPrivateKey" id="brafton_video_secret" value="<?php
		echo $options['braftonVideoPrivateKey']; ?>" />
<?php     
}

//displays the option for enterign the Feed Number {ID}
function braftonVideoFeed(){
    $options = getOptions();
    $tip = 'Enter your Feed Number. *NOTE: This is usually 0';
    tooltip($tip); ?>
<input type="text" name="braftonVideoFeed" value="<?php
		echo $options['braftonVideoFeed']; ?>" />
<?php 
}

//displays the option for selecting where to get the javascript used for playing videos
function braftonVideoHeaderScript(){
    $options = getOptions();
    $tip = "Selecting 'Neither' will still import videojs embed code, this is just the script imports.  Turn if off for sites that already have video js script in the header.";
    tooltip($tip);
    ?>
    <input type="radio" id="embed_type" name="braftonVideoHeaderScript" value="videojs" <?php checkRadioval($options['braftonVideoHeaderScript'], 'videojs'); ?> /> Video JS
    <input type="radio" id="atlantis" name="braftonVideoHeaderScript" value="atlantisjs" <?php checkRadioval($options['braftonVideoHeaderScript'], 'atlantisjs'); ?>/> Atlantis JS
    <input type="radio" id="neither" name="braftonVideoHeaderScript" value="off" <?php checkRadioval($options['braftonVideoHeaderScript'], 'off'); ?>/> Neither
<?php
}

function braftonVideoCSS(){
    $options = getOptions();
    $tip = 'Extra CSS to fix a common issue where atlantisJS looks wonky.';
    tooltip($tip); ?>
<input type="radio" name="braftonVideoCSS" value="on" <?php checkRadioval($options['braftonVideoCSS'], 'on'); ?> /> On
<input type="radio" name="braftonVideoCSS" value="off" <?php	checkRadioval($options['braftonVideoCSS'], 'off'); ?>/> Off
<?php    
}

function braftonVideoCTAs(){
    $options = getOptions();
    $tip = "If using Atlantis JS for video playback you can set specific CTA's for when the Video is paused and finished";
    tooltip($tip); ?>
    <div class="b_v_cta">
        <label>Paused CTA Text</label><br/><input type="text" name="braftonVideoCTA[pausedText]" value="<?php echo $options['braftonVideoCTA']['pausedText']; ?>"><br>
        <label>Paused CTA Link</label><br/><input type="text" name="braftonVideoCTA[pausedLink]" value="<?php echo $options['braftonVideoCTA']['pausedLink']; ?>"><br>
        <label>Ending CTA Title</label><br/><input type="text" name="braftonVideoCTA[endingTitle]" value="<?php echo $options['braftonVideoCTA']['endingTitle']; ?>"><br>
        <label>Ending CTA Subtitle</label><br/><input type="text" name="braftonVideoCTA[endingSubtitle]" value="<?php echo $options['braftonVideoCTA']['endingSubtitle']; ?>"><br>
        <label>Ending CTA Button Text</label><br/><input type="text" name="braftonVideoCTA[endingButtonText]" value="<?php echo $options['braftonVideoCTA']['endingButtonText']; ?>"><br>
        <label>Ending CTA Button Link</label><br/><input type="text" name="braftonVideoCTA[endingButtonLink]" value="<?php echo $options['braftonVideoCTA']['endingButtonLink']; ?>"><br>
    </div>
<?php 
}

/*
 ************************************************************************************************
 *
 * Marpro Setting Section // Temp renamed to Pumpkin
 *
 ************************************************************************************************
 */
function MarproSettingsSetup(){
    //Marpro Tab
        register_setting(
            'brafton_marpro_options', // Option group
            'brafton_marpro' );
        //sets a section name for the options
        add_settings_section(
            'marpro', // ID
            'Pumpkin Settings', // Title
            'print_section_info', // Callback
            'brafton_marpro' // Page
        );
        add_settings_field(
            'braftonMarproStatus',
            'Pumpkin Status',
            'braftonMarproStatus',
            'brafton_marpro',
            'marpro'
        );
        add_settings_field(
            'braftonMarproId',
            'Pumpkin Id',
            'braftonMarproId',
            'brafton_marpro',
            'marpro'
        );
}

//function for the marpro section
function braftonMarproStatus(){
    $options = getOptions();
    $tip = 'Turning on Marpro will add our custom script to the footer allowing for connection to your Asset Gateway for your marketing resources';
    tooltip($tip); ?>
    <input type="radio" name="braftonMarproStatus" value="on" <?php	checkRadioval($options['braftonMarproStatus'], 'on'); ?> /> On
    <input type="radio" name="braftonMarproStatus" value="off" <?php checkRadioval($options['braftonMarproStatus'], 'off'); ?>/> Off
<?php 
}
//function for setting the marpro id
function braftonMarproId(){
    $options = getOptions();
    $tip = 'If using our Marpro Product you will need your Id.  You can obtain this information from your CMS';
    tooltip($tip); ?>
<input type="text" name="braftonMarproId" value="<?php
		echo $options['braftonMarproId']; ?>"/>
<?php 
}

/*
 **************************************************************************************************
 * 
 * Manual Import Section
 *
 **************************************************************************************************
 */
function ManualSettingsSetup(){
    //EManual Control Tab
        register_setting(
            'brafton_control_options', // Option group
            'brafton_control' );
        //sets a section name for the options
        add_settings_section(
            'control', // ID
            'Manual Control', // Title
            'print_section_info', // Callback
            'brafton_control' // Page
        );  
        add_settings_section(
            'braftonManualImport',
            'Select an Import Option',
            'braftonManualImport',
            'brafton_control',
            'control'
        );
}

//Manual Import Settings
function braftonManualImport(){?>
    <div class="manual_buttons"><?php submit_button('Import Articles'); ?></div>
    <div class="manual_buttons"><?php submit_button('Import Videos'); ?></div>
    <div class="manual_buttons"><?php submit_button('Get Categories'); ?></div>
    <?php 
}
function braftonRegisterSettings(){
//Defines each settings section for General, Articles, Videos, marpro, Archives, and Error Logs.  Each section is labeled with the appropriate settings section for finding the appropriate fucntion for displaying that option.

    GeneralSettingsSetup();
    ArticleSettingsSetup();
    VideoSettingsSetup();
    MarproSettingsSetup();
    ArchiveSettingSetup();
    ErrorSettingsSetup();
    ManualSettingsSetup();
}
//add_action('admin_init', 'braftonRegisterSettings');
function admin_page(){
    braftonRegisterSettings();
    include 'BraftonAdminPage.php';
}
//add_action('option.php', 'redirect');
function redirect(){
   // header("LOCATION:my&b_error=vital");
    echo 'right hook';
}
/*
add_action('admin_menu', 'braftonxml_sched_add_admin_pages');
function braftonxml_sched_add_admin_pages(){
    $brand = BraftonOptions::getSingleOption('braftonApiDomain');
    $brand = switchCase($brand);
    //new admin menu
	add_menu_page('Brafton Article Loader', "{$brand} Content Importer", 'update_plugins','BraftonArticleLoader', 'admin_page','dashicons-download', 81);
    add_submenu_page('BraftonArticleLoader', 'Brafton Article Loader', 'General Options', 'update_plugins', 'BraftonArticleLoader', 'admin_page');
    add_submenu_page('BraftonArticleLoader', 'Article Options', 'Article Options', 'update_plugins', 'BraftonArticleLoader&tab=1', 'admin_page');
    add_submenu_page('BraftonArticleLoader', 'Video Options', 'Video Options', 'update_plugins', 'BraftonArticleLoader&tab=2', 'admin_page');
    add_submenu_page('BraftonArticleLoader', 'Marpro Options', 'Marpro Options', 'update_plugins', 'BraftonArticleLoader&tab=3', 'admin_page');
    add_submenu_page('BraftonArticleLoader', 'Archives', 'Archives', 'update_plugins', 'BraftonArticleLoader&tab=4', 'admin_page');
    add_submenu_page('BraftonArticleLoader', 'Error Logs', 'Error Logs', 'update_plugins', 'BraftonArticleLoader&tab=5', 'admin_page');
    add_submenu_page('BraftonArticleLoader', 'Run Importers', 'Run Importers', 'update_plugins', 'BraftonArticleLoader&tab=6', 'admin_page');
}
*/
?>