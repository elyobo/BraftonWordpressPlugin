<?php
/**
 * @package SamplePHPApi
 */
/**
 * class PhotoInstance models a photo instance object and can parse an 
 * XML instance node into a PhotoInstance object
 * @package SamplePHPApi
 */
class PhotoInstance  {

    /**
     * @var int $width
     */
    private $width; 
    /**
     * @var int $width
     */
    private $height;
    /**
     * @var string $width
     */
    private $url;
    
    private $type;
    /**
     * @return PhotoItem
     */
    function __construct(){
        $this->width = NULL;
        $this->height = NULL;
        $this->url = NULL;
        $this->type = NULL;
    }
    
    /**
     * @param DOMNode $node
     */
    public function parsePhotoInstance($node){
    	$this->width = (string)$node->width;
        $this->height = (string)$node->height;
        $this->url = (string)$node->url;
        $this->type = (string)$node->type;
    } 
    
    public function getWidth(){
    	return $this->width;
    }
    
	public function getHeight(){
    	return $this->height;
    }
    
	public function getUrl(){
    	return $this->url;
    }    
    public function getType(){
        return $this->type;
    }
}
?>