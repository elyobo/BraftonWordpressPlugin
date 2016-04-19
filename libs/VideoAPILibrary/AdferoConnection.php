<?php
//Used to determine type of connection to use in retrieving the XML from the api
class AdferoConnection{
    
    public static $instance = null;
    public $url;
    
    public $connection_type = null;
    
    public $doc;
    
    public $response;
    
    private static $force_connection = false;
    
    public function __construct($url){
        $this->url = $url;
        $this->get_connection_type();
        
    }
    //Determine the connection type.  If a connection choice was passed to any of the 3 main Clients (Articles, Video, Photo) that connection will be set.
    private function get_connection_type(){
        //Check if the connection has been hard set by passing a connection to 1 of the 3 main clients.  If it has use that connection
        if(self::$force_connection){
            $this->connection_type = self::$force_connection;
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
        $con = file_get_contents($this->url);
        $this->parse_headers_fopen($http_response_header);
        return $con;
    }
    //Parse the headers for fopen
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
    //Parse headers for curl
    private function parse_headers_curl($headers){
        $headers['Content_type'] = explode(';', $headers['content_type']);

        $this->response = $headers;
    }
    //Set the forced connection type 
    public static function force_connection($connection){
        if(self::$force_connection){
            return;   
        }
        self::$force_connection = $connection;
    }
}