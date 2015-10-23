<?php
class BraftonXMLRPC{
    
    private $options;
    
    public function __construct($args){
        $option_ini = new BraftonOptions();
        $this->options = $option_ini->getAll();
        echo '<h1>'.$_SERVER['HTTP_HOST'].' Remote operation for Wordpress Importer Version: '. BRAFTON_VERSION .'</h1>';
        echo '<h2>Running the following Operations:</h2><ul><li>'.implode('</li><li>', $args).'</li></ul>';
        
        if($this->options['braftonArticleStatus'] && in_array('articles', $args)){
            $import = new BraftonArticleLoader();
            $import->ImportArticles();  
        }
        
        if($this->options['braftonVideoStatus'] && in_array('videos', $args)){
            $import = new BraftonVideoLoader();
            $import->ImportVideos();
        }
        if(in_array('get_options', $args)){
            echo '<pre>';
            var_dump($this->options);
            echo '<pre>';
        }
        if(in_array('get_errors', $args)){
            $errors = array_reverse(get_option('brafton_e_log'));
            echo '<pre>';
            var_dump($errors);
            echo '</pre>';
        }
        
    }
    
    static function RemoteOperation($args){
        new BraftonXMLRPC($args);
    }
}
?>