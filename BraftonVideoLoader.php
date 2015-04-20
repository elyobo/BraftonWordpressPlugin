<?php
require_once 'libs/RCClientLibrary/AdferoArticlesVideoExtensions/AdferoVideoClient.php';
require_once 'libs/RCClientLibrary/AdferoArticles/AdferoClient.php';
require_once 'libs/RCClientLibrary/AdferoPhotos/AdferoPhotoClient.php';

class BraftonVideoLoader extends BraftonFeedLoader {
    
    public $PrivateKey;
    public $PublicKey;
    public $Domain;
    public $VideoURL;
    public $PhotoURL;
    public $VideoClient;
    public $Client;
    public $PhotoClient;
    public $VideoClientOutputs;
    public $FeedNum;
    public $ArticlePhotos;
    public $ArticleList;
    public $ArticleCount;
    public $ClientCategory;
    public $Sitemap;
    
    public function __construct(){
        parent::__construct();
        //set the url and api key for use during the entire run.
        $this->PrivateKey = $this->options['braftonVideoPrivateKey'];
        $this->PublicKey = $this->options['braftonVideoPublicKey'];
        $this->FeedNum = $this->options['braftonVideoFeed'];
        $this->buildVideoURL();
        $this->Sitemap = array();
        
    }
    //Builds the urls needed for video import
    private function buildVideoURL(){
        $this->VideoURL = 'http://livevideo.'.$this->options['braftonApiDomain'].'/v2/';
        $this->PhotoURL = 'http://'.str_replace('api', 'pictures',$this->options['braftonApiDomain']).'/v2/';
        
    }
    public function buildEmbedCode(){
        
    }
    public function generateSourceTag($src, $resolution){
        $tag = ''; 
        $ext = pathinfo($src, PATHINFO_EXTENSION); 

        return sprintf('<source src="%s" type="video/%s" data-resolution="%s" />', $src, $ext, $resolution );
    }
    //Gets a list of categories for this video feed and adds them if they don't already exsist
    public function ImportCategories(){
        $catArray = array();
        $cNum = $this->ClientCategory->ListCategoriesForFeed($this->feedId, 0,100, '','')->totalCount;
        for($i=0;$i<$cNum;$i++){
            $catId = $this->ClientCategory->ListCategoriesForFeed($this->feedId,0,100,'','')->items[$i]->id;
            $catNew = $this->ClientCategory->Get($catId);
            $catArray[] = $catNew->name;
        }
        foreach($catArray as $c){
            wp_create_category($c);
        }
    }
    //Assigns the categories listed for the post to the post including any custom categories.
    private function assignCategories($brafton_id){
        $catArray = array();
        $cNum = $this->ClientCategory->ListForArticle($brafton_id, 0, 100)->totalCount;
        for($i=0;$i<$cNum;$i++){
            $catId = $this->ClientCategory->ListForArticle($brafton_id,0,100)->items[$i]->id;
            $catNew = $this->ClientCategory->Get($catId);
            $catArray[] = $catNew->name;
        }
        return $catArray;
        
    }
    public function getPostDate($pdate){
        
        $post_date_gmt = strtotime($pdate);
        $post_date_gmt = gmdate('Y-m-d H:i:s', $post_date_gmt);
        $post_date = get_date_from_gmt($post_date_gmt);
        $date_array = array($post_date_gmt, $post_date);
        return $date_array;
           
    }
    public function getVideoFeed(){
        $this->VideoClient = new AdferoVideoClient($this->VideoURL, $this->PublicKey, $this->PrivateKey);
        $this->Client = new AdferoClient($this->VideoURL, $this->PublicKey, $this->PrivateKey);
        $this->PhotoClient = new AdferoPhotoClient($this->PhotoURL);
        
        $this->VideoClientOutputs = $this->VideoClient->videoOutputs();
        
        //$photos var from old importer
        $this->ArticlePhotos = $this->Client->ArticlePhotos();
        
        $feeds = $this->Client->Feeds();
        $feedList = $feeds->ListFeeds(0,10);
        $this->feedId = $feedList->items[$this->FeedNum]->id;
        
        $articles = $this->Client->Articles();
        $this->ArticleList = $articles->ListForFeed($feedList->items[$this->FeedNum]->id, 'live', 0, 100);
        $this->ArticleCount = count($this->ArticleList->items);
        
        //$categories var from old importer
        $this->ClientCategory = $this->Client->Categories();
        
    }
    //Actual workhorse of the import video class
    public function ImportVideos(){
        //Gets the Video Feed
        $this->getvideoFeed();
        //Gets the Categories
        $this->ImportCategories();
        //runs the actual loop
        $this->runLoop(); 
    }
    public function runLoop(){
        //Define local vars for the loop
        global $level, $wpdb, $post, $wp_rewrite;
        $scale_axis = 500;
        $scale = 500;
        $counter = 0;
        foreach($this->ArticleList->items as $article){
            $brafton_id = $article->id;
            if( !($post_id = $this->brafton_post_exists($brafton_id)) || $this->override ){//Begin individual video article import
                //Get the current article info in the loop
                $thisArticle = $this->Client->Articles()->Get($brafton_id);
                //Get the splash images for the video embed code
                $preSplash = $thisArticle->fields['preSplash'];
                $postSplash = $thisArticle->fields['postSplash'];
                //Need to find out if the video articles have a byline or not
                //$post_author = $this->checkAuthor($this->options['braftonArticleDynamic'], $thisArticle->fields['byLine']);
                //Get all the article info for inserting or updating the post
                $post_author = $this->options['braftonArticleDynamic'];
                $post_content = $thisArticle->fields['content'];
                $post_title = $thisArticle->fields['title'];
                $post_excerpt = $thisArticle->fields['extract'];
                $post_status = $this->options['braftonPostStatus'];
                
                $post_date_array = $this->getPostDate($thisArticle->fields['date']);
                $post_date = $post_date_array[1];
                $post_date_gmt = $post_date_array[0];
                
                $compacted_article = compact('post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_status', 'post_excerpt');
                $compacted_article['post_category'] = $this->assignCategories($brafton_id);
                echo '<pre>';
                var_dump($compacted_article);
                echo '</pre>';
                exit;
                
                if($post_id){//If the post existed but we are overriding values
                    $compacted_article['ID'] = $post_id;
                    $post_id = wp_update_post($compacted_article);
                }
                else{//if the post doesn't exists we add it to the database
                    $post_id = wp_insert_post($compacted_article);
                }
                if(is_wp_error($post_id)){
                    trigger_error($post_id);
                }
                else{
                    /*
                    $sitemapaddition = array(
                        "url" => get_permalink($post_id),
                        "location" => $mp4,
                        "title" => $post_title,
                        "thumbnail" => $presplash,
                        "description" =>$post_content,
                        "publication" =>$post_date,
                    );
                    $sitemap[]=$sitemapaddition;
                    */
                }
                $meta_array = array(
                    'brafton_id'        => $brafton_id
                );
                $this->add_needed_meta($post_id, $meta_array);

                
                //$videoList = $this->VideoClientOutputs->ListForArticle($brafton_id,0,10);
                //$list = $videoList->items;
                
            }//End the individual video article import
        }
        
    }
}
?>