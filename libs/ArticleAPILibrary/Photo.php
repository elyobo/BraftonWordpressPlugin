<?php
/**
 * @package SamplePHPApi
 */
/**
 * Include Files
 */
include_once 'PhotoInstance.php';
/**
 * class Photo models a photo object and has a static method to parse 
 * a set of photos and return them as a collection of Photo objects
 * @package SamplePHPApi
 */
class Photo {

	/**
	 * @var $id int
	 */
	private $id;
	/**
	 * @var String
	 */
	private $alt;
	/**
	 * @var PhotoInstance $thumb
	 */
	private $thumb; 
	
	/**
	 * @var PhotoInstance $large
	 */
	private $large;
	
	/**
	 * @var PhotoInstance $hiRes
	 */
	private $hiRes;
	
	/**
	 * @var PhotoInstance $custom
	 */
	private $custom;

	/**
	 * @var String
	 */
	private $orientation;
  
  
  /**
	 * @var String
	 */
	private $caption;

	function __construct(){
        
		$this->thumb = new PhotoInstance();
		$this->large = new PhotoInstance();
		$this->hiRes = new PhotoInstance();
		$this->custom = new PhotoInstance();
	}

	/**
	 * @param String $url
	 * @param int $id
	 * @return Photo[]
	 */
	public static function getPhotos($url){
		$xh = new XMLHandler($url);
		$photoItems = $xh->getChildren("photo");
		$photoList  = array();

		foreach($photoItems as $photoNode){
			$p = new Photo();

			$p->setId((string)$photoNode->id);
			$p->setAlt((string)$photoNode->htmlAlt);
			$p->setOrientation((string)$photoNode->orientation);
            $p->setCaption((string)$photoNode->caption);

			//set thumbnail pic and large pic
			$photoInstancesNode = $photoNode->instances;

			foreach ($photoInstancesNode->instance as $pi){

				$type = $pi->type;
				if( $type == "Thumbnail" || $type == "Small")$p->getThumb()->parsePhotoInstance($pi);
				elseif ($type == "Large" || $type == "Medium")$p->getLarge()->parsePhotoInstance($pi);
				elseif ($type == "HighRes")$p->getHiRes()->parsePhotoInstance($pi);
				elseif ($type == "Custom")$p->getCustom()->parsePhotoInstance($pi);
			}
			$photoList[] = $p;
		}
		return $photoList;
	}

	/**
	 * @return the id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param id the id to set
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the alt
	 */
	public function getAlt() {
		return $this->alt;
	}

	/**
	 * @param alt the alternative text to set
	 */
	public function setAlt($alt) {
		$this->alt = $alt;
	}

	/**
	 * @return the orientation
	 */
	public function getOrientation() {
		return $this->orientation;
	}

	/**
	 * @param orientation the orientation to set
	 */
	public function setOrientation($orientation) {
		$this->orientation = $orientation;
	}
  
  /**
	 * @return the caption
	 */
	public function getCaption() {
		return $this->caption;
	}
  
  /**
	 * @param caption the caption to set
	 */
	public function setCaption($caption) {
    $this->caption = $caption;
  }

	/**
	 * @return PhotoInstance
	 */
	public function getThumb() {
        
		return $this->thumb;
	}

	/**
	 * @param thumb the thumb to set
	 */
	private function setThumb($thumb) {
		$this->thumb = $thumb;
	}

	/**
	 * @return PhotoInstance
	 */
	public function getLarge() {
        
		return $this->large;
	}

	/**
	 * @param large the large PhotoItem to set
	 */
	private function setLarge($large) {
		$this->large = $large;
	}

	/**
	 * @return PhotoInstance
	 */
	public function getHiRes() {
        
		return $this->hiRes;
	}

	/**
	 * @param hiRes the hiRes PhotoItem to set
	 */
	private function setHiRes($hiRes) {
		$this->hiRes = $hiRes;
	}

	/**
	 * @return PhotoInstance
	 */
	public function getCustom() {
		return $this->custom;
	}

	/**
	 * @param custom the custom PhotoItem to set
	 */
	private function setCustom($custom) {
		$this->custom = $custom;
	}
}
?>