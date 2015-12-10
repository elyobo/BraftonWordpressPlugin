<?php
class BraftonXMLRPC{
    
    private $options;
    
    public function __construct($args){
        $option_ini = new BraftonOptions();
        $this->options = $option_ini->getAll();
        $msg = '<h1>'.$_SERVER['HTTP_HOST'].' Remote operation for Wordpress Importer Version: '. BRAFTON_VERSION .'</h1>';
        $msg .= '<h2>Running the following Operations:</h2><ul><li>'.implode('</li><li>', $args).'</li></ul>';
        
        if($this->options['braftonArticleStatus'] && in_array('articles', $args)){
            $import = new BraftonArticleLoader();
            $msg .= $import->ImportArticles();  
        }
        
        if($this->options['braftonVideoStatus'] && in_array('videos', $args)){
            $import = new BraftonVideoLoader();
            $import->ImportVideos();
        }
        if(in_array('get_options', $args)){
            $options = json_encode($this->options);
            $msg .= '<h2>Options</h2>';
            $msg .= $options;
        }
        if(in_array('get_errors', $args)){
            $errors = json_encode(array_reverse(get_option('brafton_e_log')));
            $msg .= '<h2>Errors</h2>';
            $msg .= $errors;
        }
        return $msg;
        
    }
    
    static function RemoteOperation($args){
        new BraftonXMLRPC($args);
    }
}
?>