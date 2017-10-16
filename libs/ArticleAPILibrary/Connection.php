<?php
//Used to determine type of connection to use to retrieve the XML from the api
class Connection{
    
    public $url;
    
    public $connection_type = null;
    
    public $doc;
    
    public $response;
    
    public $isfile = false;
    
    public function __construct($url){
        $this->url = $this->protocol($url);

        $this->get_connection_type();
        
    }
    //Determine the type of connection to use.  if a connection type was passed to the ApiHandler it will be used instead.
    private function get_connection_type(){
        //Check if Force Connection was setup by passing a connection string to ApiHandler
        if(defined('BRAFTON_FORCE_CONNECTION')){
            $this->connection_type = BRAFTON_FORCE_CONNECTION;
            return;
        }
        //Look for fopen and allow_url_fopen settings to allow a simple connection
        if(function_exists('fopen') && ini_get('allow_url_fopen')){
            $this->connection_type = 'fopen';
        }
        //Check if curl is available if the above option was not available
        else if(function_exists('curl_init')){
            $this->connection_type = 'curl';
        }
    }
    //Determine if the url passed is indeed a valid url with http or https else assume this is an archive.
    private function protocol($url){
        $parse = parse_url($url);
        if( !($parse['scheme'] == 'http') && !($parse['scheme'] == 'https') ){
            $this->isfile = true;
            return 'file://'.$url;   
        }
        $scheme = $parse['scheme']."://";
        
        unset($parse['scheme']);
        return $scheme.join("", $parse);
    }
    //Retrieve the xml as a string 
    public function getFeed(){
        if($this->connection_type != null){
            $connect = $this->connection_type.'_connection';
            if(method_exists($this, $connect)){
                return $this->$connect();
            }
        }
        return false;
    }
    //Retrieve the XML using curl
    private function curl_connection(){
        $url = $this->url;
        if(!isset($ch)){
          $ch = curl_init();
        }        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Forwarded-Proto"));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $feed_string = curl_exec($ch); 
        $hinfo = curl_getinfo($ch);
        $this->parse_headers_curl($hinfo);
        return $feed_string;
    }
    //Retrieve the XML using fopen
    private function fopen_connection(){
        //@Issue 68 fopen SSL Bug
        $ssl = array(
            "ssl"   => array(
                "verify_peer"   => false,
                "verify_peer_name"  => false
            )
        );
        $options = stream_context_create($ssl);
        $con = file_get_contents($this->url, false, $options);
        if($this->isfile){
            $this->setFileHeaders();
            
        }
        else{
            $this->parse_headers_fopen($http_response_header);
        }
        return $con;
    }
        private function setFileHeaders(){
            $head = array(
                "Content_type" => array('application/xml')
                );
            $this->response = $head;
        }
    //Parse the headers if using fopen
    private function parse_headers_fopen( $headers ){
        $head = array();
        foreach( $headers as $k=>$v )
        {
            $t = explode( ':', $v, 2 );
            if( isset( $t[1] ) )
                $head[ trim($t[0]) ] = trim( $t[1] );
            else
            {
                $head[] = $v;
                if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) )
                    $head['http_code'] = intval($out[1]);
            }
        }
        $head['Content_type'] = explode(';',$head['Content-Type']);

        $this->response = $head;
    }
    //parse the headers if using curl
    private function parse_headers_curl($headers){
        $headers['Content_type'] = explode(';', $headers['content_type']);

        $this->response = $headers;
    }
}