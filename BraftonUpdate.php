<?php
/*
 * Brafton Update Class.  This class is purposely seperated out so it can be tacked onto exsisting Importers to allow for autoupdate to a newer version
 *
 */
class Brafton_Update
{
    /**
     * The plugin current version
     * @var string
     */
    public $current_version;
 
    /**
     * The plugin remote update path
     * @var string
     */
    public $update_path;
 
    /**
     * Plugin Slug (plugin_directory/plugin_file.php)
     * @var string
     */
    public $plugin_slug;
 
    /**
     * Plugin name (plugin_file)
     * @var string
     */
    public $slug;
 
    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $current_version
     * @param string $update_path
     * @param string $plugin_slug
     */
    function __construct($current_version, $update_path, $plugin_slug)
    {
        // Set the class public variables
        $this->current_version = $current_version;
        $this->update_path = $update_path;
        $this->plugin_slug = $plugin_slug;
        list ($t1, $t2) = explode('/', $plugin_slug);
        $this->slug = str_replace('.php', '', $t2);
 
        // define the alternative API for updating checking
        add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_update'));
        //add_filter('plugins_api_args', array(&$this, 'add_args'), 10, 2);
        // Define the alternative response for information checking
        add_filter('plugins_api', array($this, 'check_info'), 10, 3);
        //var_dump($suc);
    }
    public function add_args($args, $action){
        $args->fields = array(
            'teltale'   => true
        );
        return $args;
        
    }
    /**
     * Add our self-hosted autoupdate plugin to the filter transient
     *
     * @param $transient
     * @return object $ transient
     */
    public function check_update($transient)
    {
        
        if (empty($transient->checked)) {
            return $transient;
        }
 
        // Get the remote version
        //$remote_version = $this->getRemote_version();
        $remote_version = 158;
        $remote_info = $this->getRemote_information();

        // If a newer version is available, add the update
        if (version_compare($this->current_version, $remote_version, '<')) {
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->new_version = $remote_version;
            $obj->url = $this->update_path;
            //$obj->url = 'http://myupload.com';
            //$obj->package = $remote_info->package;
            $obj->package = 'http://myupload.com/hey.zip';
            $obj->fields = array(
                'description' => 'this is the first desc',
                'sections'  => array(
                    'faq'   => 'faqs'
                ),
            );
            $transient->response[$this->plugin_slug] = $obj;
        }
        //echo '<pre>3';
        //var_dump($transient);
        //echo '</pre>'; 
        return $transient;
    }
 
    /**
     * Add our self-hosted description to the filter
     *
     * @param boolean $false
     * @param array $action
     * @param object $arg
     * @return bool|object
     */
    //registers wheither it is our plugin or not if it is add results filter
    public function check_info($false,$action, $arg)
    {

        /*
        if ($arg->slug === $this->slug) {
            $information = $this->getRemote_information();
            return $information;
        }
        return false;
       */
        //echo "<h1>Brafton Plugin</h1>";
        if($arg->slug === $this->slug){
        $obj = new stdClass();
        $obj->slug = $this->slug;
        $obj->plugin_name = 'plugin.php';
        $obj->name = 'brafton';
        $obj->new_version = '5';
        $obj->requires = '3.0';
        $obj->tested = '4.3';
        $obj->downloaded = 12540;
        $obj->last_updated = '2012-01-12';
        $obj->homepage = 'http://mysite.com';
        $obj->sections = array(
        'description' => 'The new version of the Auto-Update plugin',
        'another_section' => 'This is another section',
        'changelog' => 'Some new features'
      );
        $obj->download_link = 'http://localhost/update.php';
        return add_filter('plugins_api_result', array($this, 'check_inf'), 10, 3);
        }
        return false;
        //echo '<pre>1';
        //var_dump($result);
        

    }
    //results filter to return the obj for veiw version details pop
    public function check_inf($false,$action, $arg)
    {

        /*
        if ($arg->slug === $this->slug) {
            $information = $this->getRemote_information();
            return $information;
        }
        return false;
       */
        //echo "<h1>Brafton Plugin</h1>";
        //These will come from the api loading in only the appropriate variables based on the domain that they have the api from
        if($arg->slug === $this->slug){
            $obj = new stdClass();
            $obj->slug = 'plugin.php';
            $obj->plugin_name = 'plugin.php';
            $obj->name = 'Content Importer';
            $obj->new_version = '4';
            $obj->requires = '3.0';
            $obj->tested = '4.3';
            $obj->downloaded = 9581;
            $obj->banners = array(
                'low'   => 'http://localhost/wordpress_a/wp-content/plugins/newPlugin/admin/img/brafton.png',
                'high'  => 'http://localhost/wordpress_a/wp-content/plugins/newPlugin/admin/img/brafton.png'
            );
            $obj->last_updated = '2012-01-12';
            $obj->homepage = 'http://mysite.com';
            $obj->rating = 85;
            $obj->num_ratings = 6685;
            $obj->sections = array(
                'description' => 'The new version of the Auto-Update plugin',
                'Services' => 'This is another section',
                'changelog' => 'Some new features'
            );
            $obj->download_link = 'http://localhost/update.php';
            $obj->external = true;
            return $obj;
        }
        return false;
        //echo '<pre>2';
        //var_dump($result);
        

    }
 
    //Gets the newest Version from the update API
    public function getRemote_version()
    {
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'version')));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return $request['body'];
        }
        return false;
    }
 
    //Gets the Plugin information from the update API
    public function getRemote_information(){
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'info')));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            //echo '<br> these are the request dumps'; echo '<pre>';var_dump($request);
            return unserialize($request['body']);
        }
        return false;
    }
 
}
add_action('init', 'wptuts_activate_au');
function wptuts_activate_au(){
    //variable is defined in the main plugin file.
    global $brafton_plugin_slug;
    global $BraftonPluginData;
    $brafton_plugin_current_version = $BraftonPluginData['Version'];
    $brafton_plugin_remote_path = 'http://localhost/pluginupdates/update/wordpress/update';
    new Brafton_Update ($brafton_plugin_current_version, $brafton_plugin_remote_path, $brafton_plugin_slug);
}
?>