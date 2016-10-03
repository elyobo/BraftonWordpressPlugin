<?php
/**
 * @package SamplePHPApi
 */
/** 
 * class NewsCategory models a category object and has a static method to parse 
 * a set of categories and return them as a collection of category objects
 * @package SamplePHPApi
 */
class NewsCategory {
    
    /**
     * @var int
     */
    private $id;
    /**
     * @var String
     */
    private $name;

    function __construct(){

    }

    /**
     * @param String $url
     * @return array[int]Category
     */
    public static function getCategories($url){
        $xh = new XMLHandler($url);
        //var_dump("XML Handler obj",$xh);
        $nl = $xh->getChildren("category");
        //var_dump("category List object",$nl);
        
        $catList = array();
        
        foreach ($nl as $n) {
            //var_dump("individual Category", $n);exit();
            $child = array();
            $c = new NewsCategory();
            $c->id = (string)$n->id;
            $c->name = (string)$n->name;
            
            $children = $n->categories; 
            //@Issue 71: child categories
            foreach($children->category as $ch){
                $child[] = array(
                    'name'  => (string)$ch->name,
                    'id'    => (string)$ch->id
                    );
            }
            $c->child = $child;
            
            $catList[]=$c;
        }
        
        //var_dump("usabele list",$catList);exit();
        return $catList;
    }
    
    public function getName(){
    	return $this->name;
    }
    
    public function getID(){
    	return $this->id;
    }
}
?>