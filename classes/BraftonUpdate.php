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
 
    public $brand;
    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $current_version
     * @param string $update_path
     * @param string $plugin_slug
     */
    function __construct($current_version, $update_path, $plugin_slug, $brand)
    {
        // Set the class public variables
        $this->current_version = $current_version;
        $this->update_path = $update_path;
        $this->plugin_slug = $plugin_slug;
        list ($t1, $t2) = explode('/', $plugin_slug);
        $this->slug = str_replace('.php', '', $t2);
        $this->brand = $brand;
 
        // define the alternative API for updating checking
        add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_update'));

        // Define the alternative response for information checking
        add_filter('plugins_api', array($this, 'check_info'), 10, 3);
        //var_dump($suc);
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
        $remote_version = $this->getRemote_version();
        
        $remote_info = $this->getRemote_information();

        // If a newer version is available, add the update
        if (version_compare($this->current_version, $remote_version, '<')) {
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->new_version = $remote_version;
            $obj->url = $remote_info->download_link;
            $obj->package = $remote_info->package;
            $obj->fields = array(
                'description' => $remote_info->sections['description'],
                'sections'  => array(
                    'changelog'   => html_entity_decode($remote_info->sections['changelog'])
                ),
            );
            $transient->response[$this->plugin_slug] = $obj;
        }
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

        if( isset($arg->slug) && $arg->slug === $this->slug){
            $remote_info = $this->getRemote_information();
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->plugin_name = $remote_info->plugin_name;
            $obj->name = $remote_info->plugin_name;
            $obj->new_version = $remote_info->new_version;
            $obj->requires = $remote_info->requires;
            $obj->tested = $remote_info->tested;
            $obj->downloaded = $remote_info->downloaded;
            $obj->last_updated = $remote_info->last_updated;
            $obj->homepage = $remote_info->homepage;
            $obj->sections = array(
            'description' => $remote_info->sections['description'],
            'changelog' => html_entity_decode($remote_info->sections['changelog'])
          );
            $obj->download_link = $remote_info->download_link;
            $obj->package = $remote_info->package;
            add_filter('plugins_api_result', array($this, 'check_inf'), 10, 3);
            return $obj;
        }
        return false;

    }
    //results filter to return the obj for veiw version details pop
    public function check_inf($false,$action, $arg)
    {

        //These will come from the api loading in only the appropriate variables based on the domain that they have the api from
        if( isset($arg->slug) && $arg->slug === $this->slug){
            $remote_info = $this->getRemote_information();
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->plugin_name = $remote_info->plugin_name;
            $obj->name = $remote_info->plugin_name;
            $obj->new_version = $remote_info->new_version;
            $obj->requires = $remote_info->requires;
            $obj->tested = $remote_info->tested;
            $obj->downloaded = $remote_info->downloaded;
            $obj->banners = array(
                'low'   => $remote_info->banners['low'],
                'high'  => $remote_info->banners['high']
            );
            $obj->last_updated = $remote_info->last_updated;
            $obj->homepage = $remote_info->homepage;
            $obj->rating = 75;
            $obj->ratings = array(0,0,7,96,875,1008);
            $obj->num_ratings = 1916;
            $obj->sections = array(
                'description' => $remote_info->sections['description'],
                'services'      => $remote_info->sections['services'],
                'changelog' => html_entity_decode($remote_info->sections['changelog'])
            );
            $obj->download_link = $remote_info->download_link;
            $obj->package = $remote_info->package;
            $obj->external = true;
            return $obj;
        }
        return false;        

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
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'info', 'brand' => $this->brand)));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            //echo '<br> these are the request dumps'; echo '<pre>';var_dump($request);
            return unserialize($request['body']);
        }
        return false;
    }
 
}
?>