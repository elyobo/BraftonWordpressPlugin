<?php
class BraftonCustomType {
    
    public $options;
    public $cleanSlug;
    static $urlSlug;
    public function __construct(){
        $op = new BraftonOptions();
        $this->options =  $op->options;
        $this->BraftonPostType();
        $this->rewrite_rules();
        $this->rewrite_post_type_link();
        add_filter('post_type_link', array($this, 'brafton_permalinks'), 10, 3);
        add_filter('pre_get_posts', array($this, 'BraftonIncludeContent'));
        $this->copyTemplates();
        $this->registerTemplates();
        //add_filter('wp_get_nav_menu_items', array($this, 'add_nav'), 10,3);
        add_filter('wp_setup_nav_menu_item', array($this, 'flter_items'), 10, 1);
        flush_rewrite_rules();
    }
    
    static function BraftonInitializeType(){
        $initialize = new BraftonCustomType();
    }
    static function add_nav($items,$menu, $args){
        echo '<pre>';var_dump($items,$menus, $args); echo '</pre>';
        exit();
        
    }
    static function flter_items($menu_item){
        //Get option for what the id of the blog page is when it is added as a page.
        echo '<pre>';var_dump($menu_item); echo '</pre>';
        $customBlogPageId = "1591"; //This must be a string
        if($menu_item->object_id == $customBlogPageId){
            exit();
            $url = site_url().'/slug';
            //$menu_item->url = $url;
        }
        return $menu_item;
    }
    public function copyTemplates(){
        $dir = get_template_directory();
        //copy archive.php file for use with custom post type
        if(file_exists($dir.'/index.php') 
           && !file_exists($dir.'/archive-'.$this->cleanSlug.'.php')){
            if(!copy($dir.'/index.php', 
                     $dir. '/archive-'.$this->cleanSlug.'.php') ){
                //throw error   
            }
        }
        //copy single.php file for use with custom post type
        if(file_exists($dir.'/single.php') 
           && !file_exists($dir.'/single-'.$this->cleanSlug.'.php')){
            if(!copy($dir.'/single.php', 
                     $dir.'/single-'.$this->cleanSlug.'.php')){
                //throw error   
            }
        }
        
    }
    public function registerTemplates(){
        
    }
   public function BraftonPostType(){
       $brand = $this->options['braftonApiDomain'];
       $brand = $this->switchCase($brand);
       //$slug = BraftonOptions::getSingleOption('braftonCustomSlug');
       $slug = $this->options['braftonCustomSlug']? $this->options['braftonCustomSlug']: 'blog';
       $this->cleanSlug = $cleanSlug = strtolower(str_replace(' ', '-', preg_replace("/[^a-z0-9 ]/i", "",$slug) ));
       $this->urlSlug = $cleanSlug;
       $post_args = array(
          'label'   => __($brand.' Content'),
          'labels'  => array(
              'name'    => __($brand.' Content'),
              'singular_name'   => __($brand.' Content'),
              'add_new' => __('Add '. $slug . ' Post'),
              'add_new_item'    => __('Add '.$slug . ' Post'),
              'new_item'    => __('New '.$slug . ' Post'),
              'all_items'   => __('All ' . $slug. ' Posts'),
              'view_item'   => __('View '.$slug .' Post'),
              'edit_item'   => __('edit '.$slug.' Post')
            ),
          'description' => "$brand Content Imported from {$brand}'s XML API Feed.",
          'public'  => true,
          'has_archive' => true,
          'taxonomies'  => array('category', 'post_tag'),
          'rewrite' => array(
              'slug'    => $cleanSlug
              ),
          'supports'    => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
          'query_var'   => true,
           'publicly_queryable' => true
        );
       register_post_type( $cleanSlug, $post_args);
    }
    public function switchCase($brand){
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
    public function rewrite_rules(){
        global $wp_rewrite;
        //Need to determine the taxonomies used and get the rules to apply to custom taxonomy types
        $slug = BraftonOptions::getSingleOption('braftonCustomSlug')? BraftonOptions::getSingleOption('braftonCustomSlug'): 'blog';
        $brafton_type_structure = '/'.$this->cleanSlug.'/%category%/%'.$this->cleanSlug.'%';
        $wp_rewrite->add_rewrite_tag("%".$this->cleanSlug."%", '([^/]+)', $this->cleanSlug."=");
        $wp_rewrite->add_permastruct($this->cleanSlug, $brafton_type_structure, false);
        
        add_rewrite_rule($this->cleanSlug.'/category/([^/]+)', 
                         'index.php?post_type='.$this->cleanSlug.'&category_name=$matches[1]', 'top');
        add_rewrite_rule($this->cleanSlug.'/archive/([^/]+)/([^/]+)', 
                         'index.php?post_type='.$this->cleanSlug.'&year=$matches[1]&monthnum=$matches[2]', 'top');
        

    }
    static function additional_vars($vars){
            $vars[]= 'category';
            return $vars;
    }
    public function rewrite_post_type_link(){
        
    }
    static function brafton_permalinks($permalink, $post_id, $leavename) {
        $op = new BraftonOptions();
        $options =  $op->options;
        //need to reset to get clean slug
         $cleanSlug = strtolower(str_replace(' ', '-', preg_replace("/[^a-z0-9 ]/i", "",$options['braftonCustomSlug']) ));
        $post = get_post($post_id);
        $rewritecode = array(
            '%year%',
            '%monthnum%',
            '%day%',
            '%hour%',
            '%minute%',
            '%second%',
            $leavename? '' : '%postname%',
            '%post_id%',
            '%category%',
            '%author%',
            $leavename? '' : '%pagename%',
        );
        //$slug = $this->options['braftonCustomSlug']? $this->options['braftonCustomSlug']: 'blog';
        if ( '' != $permalink && !in_array($post->post_status, array('draft', 'pending', 'auto-draft')) && $post->post_type == $cleanSlug ) {
            $unixtime = strtotime($post->post_date);

            $category = '';
            if ( strpos($permalink, '%category%') !== false ) {
                //get the custom post taxonomy type
                $tax_type = 'category';

                $customTax = $options['braftonArticleExistingCategory'];
                if($customTax != '' || $customTax != null){
                    $tax_type = $customTax;
                }
                //$cats = get_the_category($post->ID);
                $cats = get_the_terms($post->ID, $tax_type);
                if ( $cats ) {
                    usort($cats, '_usort_terms_by_ID'); // order by ID
                    $category = $cats[0]->slug;
                    if ( $parent = $cats[0]->parent )
                        $category = get_category_parents($parent, false, '/', true) . $category;
                }
                // show default category in permalinks, without
                // having to assign it explicitly
                if ( empty($category) ) {
                    $default_category = get_category( get_option( 'default_category' ) );
                    $category = is_wp_error( $default_category ) ? '' : $default_category->slug;
                }
            }

            $author = '';
            if ( strpos($permalink, '%author%') !== false ) {
                $authordata = get_userdata($post->post_author);
                $author = $authordata->user_nicename;
            }

            $date = explode(" ",date('Y m d H i s', $unixtime));
            $rewritereplace = array(
                $date[0],
                $date[1],
                $date[2],
                $date[3],
                $date[4],
                $date[5],
                $post->post_name,
                $post->ID,
                $category,
                $author,
                $post->post_name,
            );
            $permalink = str_replace($rewritecode, $rewritereplace, $permalink);
        } else { 
        }
        return $permalink;
    }

    static function BraftonIncludeContent( $query ) {
        $slug = BraftonOptions::getSingleOption('braftonCustomSlug')? BraftonOptions::getSingleOption('braftonCustomSlug'): 'blog';
        /*
        echo '<pre>';
        var_dump($query);
        echo '</pre>';
        exit();
        */
        if ( !is_admin() && $query->is_main_query() && ( $query->is_category() || $query->is_date() ) ) {
            //$query->set( 'post_type', array( 'post', $slug ) );
            
            //need to do further work to modify according to what 
        }
    }
}
?>