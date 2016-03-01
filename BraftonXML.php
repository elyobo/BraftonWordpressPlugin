<?php
class BraftonXMLRPC{
    
    private $options;
    public $message;
    
    public function __construct($args){
        $option_ini = new BraftonOptions();
        $this->options = $option_ini->getAll();
        $msg = '<h1>'.$_SERVER['HTTP_HOST'].' Remote operation for Wordpress Importer Version: '. BRAFTON_VERSION .'</h1>';
        $msg .= '<h2>Running the following Operations:</h2><ul><li>'.implode('</li><li>', $args).'</li></ul>';
        $this->message .= $msg;
        foreach($args as $operation){
            $this->$operation();
        }        
    }
    public function health_check(){
        $msg = 'ok';
        $this->message = $msg;
    }
    public function articles(){
        $import = new BraftonArticleLoader();
        $articles = $import->ImportArticles();  
        $this->message .= $articles;
    }
    public function videos(){
        $import = new BraftonVideoLoader();
        $videos = $import->ImportVideos();
        $this->message .= $videos;
    }
    public function get_options(){
        $msg = '<h2>Options</h2>';
        $msg .= '<ul>';
        foreach($this->options as $option => $value){
            $msg .= "<li>$option : $value </li>";
        }
        $msg .= '</ul>';
        $msg .= $options;
        $this->message .= $msg;
    }
    public function get_errors(){
        $errors = array_reverse(get_option('brafton_e_log'));
        $msg = '<h2>Errors</h2>';
        foreach($errors as $array){
            $msg .= '<ul>';
            foreach($array as $error => $data){
                $msg .= "<li> $error : $data </li>";   
            }
            $msg .= '</ul>';
        }
        $msg .= $errors;
        $this->message .= $msg;
    }
    public function clear_errors(){
        $return = delete_option('brafton_e_log');
        if($return){
            $msg = "<h2>Error Log has been cleared</h2>";
        }else{
            $msg = "<h2>There was an error when attempting to clear the Brafton Error Log. You may need to sign into this accounts dashbaord to correct any problems</h2>";
        }
        $this->message .= $msg;
    }
    public function turn_off(){
        //Turn the importer off. 
        $bOptions = new BraftonOptions();
        $bOptions->saveOption('braftonStatus', 0);
        $bOptions->saveOption('braftonArticleStatus', 0);
        $bOptions->saveOption('braftonVideoStatus', 0);
        $msg = "<h2>This importer has now been turned Off</h2>";
        $this->message .= $msg;
    }
    public function debug_off(){
        $bOptions = new BraftonOptions();
        $bOptions->saveOption('braftonDebugger', 0);
        $msg = "<h2>Debug has now been turned off</h2>";
        $this->message .= $msg;
    }
    static function RemoteOperation($args){
        $xmlrpc = new BraftonXMLRPC($args);
        return $xmlrpc->message;
    }
}
?>