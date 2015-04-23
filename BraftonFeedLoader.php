<?php
/**
 * Defines vars and functions needed for both video and article classes
 *
 *
 * @version     2.0.1
 *
 */
include_once(ABSPATH . 'wp-includes/pluggable.php');
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
include_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
//require_once(ABSPATH.'wp-includes/rewrite.php');
class BraftonFeedLoader {
    
    public $options;
    public $upload_array;
    public $erros;
    public $ch;
    public $user;
    public $override;
    public $publishDate;
    public $publish_status;
    
    public function __construct(){
        $option_ini = new BraftonOptions();
        $this->options = $option_ini->getAll();
        $this->errors = new BraftonErrorReport();
        $this->upload_array = wp_upload_dir();
        $this->override = $this->options['braftonUpdateContent'];
        $this->publishDate = $this->options['braftonPublishDate'];
        $this->publish_status = $this->options['braftonPostStatus'];
        $this->ch = curl_init();
        $this->user = get_user_by('login', $this->options['braftonImporterUser']);
        wp_set_current_user($this->user->ID);
        
        
    }
    //checks if the article exsists already. Return null if it doesn't return the post id if it does.
    public function  brafton_post_exists($brafton_id){
        global $wpdb;
        //var_dump($args);
        $post_id = null;
        $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_key = 'brafton_id' AND meta_value = $brafton_id", ARRAY_A );
        
        if($results){
            $post_id = $results[0]['post_id'];
        }
        return $post_id;
    }
    
    //dynamic Author Check
    public function checkAuthor($author, $byLine){
        
        if($author == 'n' ){
            $author = $this->options['braftonArticleAuthorDefault'];
        }
        else{
            if(empty($byLine)){$author = $this->options['braftonArticleAuthorDefault'];return $author; }
            if(!(username_exists($byLine))){
                $pass = wp_generate_password(12,false);
                $author = wp_create_user($byLine, $pass, $byLine.rand().'@example.com');
            }
            else{
                $user = get_user_by('login',$byLine);
                $author = $user->ID;
            }
        }
        return $author;
    }
    public function getPostDate($article){
        switch($this->publishDate){
            case 'modified':
            $pdate = $article->getLastModifiedDate();
            break;
            case 'created':
            $pdate = $article->getCreatedDate();
            break;
            default:
            $pdate = $article->getPublishDate();
            break;
        }
        $post_date_gmt = strtotime($pdate);
        $post_date_gmt = gmdate('Y-m-d H:i:s', $post_date_gmt);
        $post_date = get_date_from_gmt($post_date_gmt);
        $date_array = array($post_date_gmt, $post_date);
        return $date_array;
           
    }
    public function image_download($post_image, $post_id, $image_id, $image_alt, $image_caption){
        //Set the section for error reporting
        $this->errors->set_section('image_download');
        //Download the image to the temp folder for preperation as a fake $_FILE
        $temp_file = download_url($post_image);
        $wp_filetype = wp_check_filetype(basename($post_image), NULL);
        //Build fake $_FILE
        $file = array(
            'name'  => basename($post_image),
            'type'  => $wp_filetype['type'],
            'tmp_name'  => $temp_file,
            'error' => 0,
            'size'  => filesize($temp_file)
        );
        //Sets the overrides to allow for using a fake $_FILE
        $overrides = array(
            'test_form' => false,
            'test_size' => true,
            'test_upload'   => true
        );
        //upload the image to the appropriate 
        $up = wp_handle_sideload($file, $overrides);

        $up_url = $up['url'];
        $up_dir = $up['file'];
        $wp_filetype = wp_check_filetype(basename($up_url), NULL);
        $attachment = array(
            'guid'  => $up_url,
            'post_mime_type' => $wp_filetype['type'],
            'post_title'    => $image_caption,
            'post_excerpt'  => $image_caption,
            'post_content'     => $image_caption
        );						
        $attach_id = wp_insert_attachment($attachment, $up_dir, $post_id);
        update_post_meta($attach_id, '_wp_attachment_image_alt', $image_alt);
        $attach_data = wp_generate_attachment_metadata($attach_id, $up_dir);
        wp_update_attachment_metadata($attach_id, $attach_data);
        update_post_meta($post_id, '_thumbnail_id', $attach_id);
                        
    }
    public function add_needed_meta($post_id, $meta_array){
        foreach($meta_array as $field => $value){
            update_post_meta($post_id, $field, $value);   
        }
    }
}
?>