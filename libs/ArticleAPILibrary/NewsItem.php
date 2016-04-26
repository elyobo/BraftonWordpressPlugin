<?php
/**
 * @package SamplePHPApi
 */
/**
 * Include Files
 */
include_once 'NewsCategory.php';
include_once 'Photo.php';

/**
 * Constant Definitions for XML elements and attributes
 */
define("NEWS_LIST_ITEM", "newsListItem");
define("NEWS_ITEM", "newsItem");
define("HREF", "href");
define("ID", "id");
define("HEADLINE", "headline");
define("PUBLISH_DATE", "publishDate");
define("ENCODING", "encoding");
define("CREATED_DATE", "createdDate");
define("LAST_MODIFIED_DATE", "lastModifiedDate");
define("EXTRACT", "extract");
define("TEXT", "text");
define("BY_LINE", "byline");
define("TWEET_TEXT", "tweettext");
define("SOURCE", "source");
define("STATE", "state");
define("CLIENT_QUOTE", "clientQuote");
define("HTML_TITLE", "htmlTitle");
define("HTML_META_DESCRIPTION", "htmlMetaDescription");
define("HTML_META_KEYWORDS", "htmlMetaKeywords");
define("HTML_META_LANGUAGE", "htmlMetaLanguage");
define("KEYWORDS", 'keywords');
define("TAGS", "tags");
define("PRIORITY", "priority");
define("FORMAT", "format");
define("PHOTOS", "photos");
define("CATEGORIES", "categories");
define("COMMENTS", "comments");
/**
 * class NewsItem models a news object and has a static method to parse 
 * a set of news items and return them as a collection of NewsItem objects
 * @package SamplePHPApi
 */
class NewsItem	{
	/* @var XMLHandler */
	private $xh;
	/* @var String */
	private $encoding;
	/* @var int */
	private $id;
	/* @var String */
	private $publishDate;
	/* @var String */
	private $createdDate;
	/* @var String */
	private $lastModifiedDate;
	/*  @var String */
	private $headline;
	/* @var String */
	private $extract;
	/* @var String */
	private $text;
	/* @var String */
	private $href;
	/* @var String */
	private $byLine;
	/* @var String */
	private $tweetText;
	/* @var String */
	private $source;
	/* @var String */
	private $state;
	/* @var String */
	private $clientQuote;
	/* @var String */
	private $htmlTitle;
	/* @var String */
	private $htmlMetaDescription;
	/* @var String */
	private $htmlMetaKeywords;
	/* @var String */
	private $htmlMetaLanguage;
	/* @var String */
	private $tags;
	/* @var int */
	private $priority;
	/* @var String */
	private $format;
    /* @var String */
    private $keywords;
	/* @var photos[] */
	private $photos;
	/* @var NewsCategory[] */
	private $categories;
	/* @var NewsComment[] */
	private $comments;

	/** @return NewsItem **/
	function __construct(){
	}

	/** @return XMLHandler **/
	private function getFullNewsXML(){
		if(empty($this->xh)){
			if(strcasecmp($this->getFormat(), "html"))$this->xh = new XMLHandler($this->href);
			else $this->xh = new XMLHandler($this->href . $this->getFormat());
		}
		return $this->xh;
	}

	/**
	 * @param String $url
	 * @return NewsItem[]
	 */
	public static function getNewsList($url, $format) {
		//Exception thrown in XMLHandler constructor if url is incorrect
		$xh = new XMLHandler($url);
        $_newsList = array();
		if(isset($xh)){
            $_news = $xh->getChildren(NEWS_LIST_ITEM);

            foreach($_news as $n){
                
				/* @var $n DomElement */
				$ni = new NewsItem();

                $ni->id = (string)$n->{ID};
                //Check if date is valid if not throw exception
                $ni->publishDate = (string)$n->{PUBLISH_DATE};
                $ni->href = $n->attributes();
                $ni->href = (string)$ni->href[HREF];
                $ni->headline = (string)$n->{HEADLINE};
                $ni->format = $format;
                //Add to newslist array
                $_newsList[] = $ni;
			}
		}
		return $_newsList;
	}
    public static function getNewsItem($url, $id){
        $single = new NewsItem();
        $oneOff = array();
        $single->href = $url.'news/'.$id.'/';
        $single->format = 'html';
        $oneOff[] = $single;
        return $oneOff;
        
    }
	/** @return String **/
	public function getEncoding() {
		if(empty($this->encoding)){
			$xh = $this->getFullNewsXML();
			$this->encoding = $xh->getAttributeValue(NEWS_ITEM, ENCODING);
		}
		return $this->encoding;
	}

	/** @return int **/
	public function getId() {
		if(empty($this->id)){
			$xh = $this->getFullNewsXML();
			$this->id = $xh->getVal(ID);
		}
		return $this->id;
	}

	/** @return String **/
	public function getPublishDate() {
		if(empty($this->publishDate)){
			$xh = $this->getFullNewsXML();
			$this->publishDate = $xh->getVal(PUBLISH_DATE);
		}
		return $this->publishDate;
	}

	/** @return String **/
	public function getHeadline() {
		if(empty($this->headline)){
			$xh = $this->getFullNewsXML();
			$this->headline = $xh->getVal(HEADLINE);
		}
		return $this->headline;
	}

	/** @return String **/
	public function getCategories() {
		if(empty($this->categories)){
			$xh = $this->getFullNewsXML();
			$this->categories = NewsCategory::getCategories($xh->getAttr(CATEGORIES, HREF));
		}
		return $this->categories;
	}
  
  /** @return String **/
  public function getKeywords() {
    if(empty($this->categories)){
			$xh = $this->getFullNewsXML();
			$this->keywords = $xh->getVal(KEYWORDS);
		}
		return $this->keywords;
  }

	/** @return String **/
	public function getCreatedDate() {
		if(empty($this->createdDate)){
			$xh = $this->getFullNewsXML();
			$this->createdDate = $xh->getVal(CREATED_DATE);
		}
		return $this->createdDate;
	}

	/** @return String **/
	public function getLastModifiedDate() {
		if(empty($this->lastModifiedDate)){
			$xh = $this->getFullNewsXML();
			$this->lastModifiedDate = $xh->getVal(LAST_MODIFIED_DATE);
		}
		return $this->lastModifiedDate;
	}

	/** @return String **/
	public function getPhotos() {
		if(empty($this->photos)){
			$xh = $this->getFullNewsXML();
			$this->photos = Photo::getPhotos($xh->getAttr(PHOTOS, HREF));
		}
		return $this->photos;
	}

	/** @return String **/
	public function getComments() {
		if(empty($this->comments)){
			$xh = $this->getFullNewsXML();
			$this->comments = NewsComment::getComments($xh->getAttr(COMMENTS, HREF));
		}
		return $this->comments;
	}

	/** @return String **/
	public function getExtract() {
		if(empty($this->extract)){
			$xh = $this->getFullNewsXML();
			$this->extract = $xh->getVal(EXTRACT);
		}
		return $this->extract;
	}

	/** @return String **/
	public function getText() {
		if(empty($this->text)){
			$xh = $this->getFullNewsXML();
			$this->text = $xh->getVal(TEXT);
		}
		return $this->text;
	}

	/** @return String **/
	public function getByLine() {
		if(empty($this->byLine)){
			$xh = $this->getFullNewsXML();
			$this->byLine = $xh->getVal(BY_LINE);
		}
		return $this->byLine;
	}

	/** @return String **/
	public function getTweetText() {
		if(empty($this->tweetText)){
			$xh = $this->getFullNewsXML();
			$this->tweetText = $xh->getVal(TWEET_TEXT);
		}
		return $this->tweetText;
	}

	/** @return String **/
	public function getSource() {
		if(empty($this->source)){
			$xh = $this->getFullNewsXML();
			$this->source = $xh->getVal(SOURCE);
		}
		return $this->source;
	}

	/** @return String **/
	public function getState() {
		if(empty($this->state)){
			$xh = $this->getFullNewsXML();
			$this->state = $xh->getVal(STATE);
		}
		return $this->state;
	}

	/** @return String **/
	public function getClientQuote() {
		if(empty($this->clientQuote)){
			$xh = $this->getFullNewsXML();
			$this->clientQuote = $xh->getVal(CLIENT_QUOTE);
		}
		return $this->clientQuote;
	}

	/** @return String **/
	public function getHtmlTitle() {
		if(empty($this->htmlTitle)){
			$xh = $this->getFullNewsXML();
			$this->htmlTitle = $xh->getVal(HTML_TITLE);
		}
		return $this->htmlTitle;
	}

	/** @return String **/
	public function getHtmlMetaDescription() {
		if(empty($this->htmlMetaDescription)){
			$xh = $this->getFullNewsXML();
			$this->htmlMetaDescription = $xh->getVal(HTML_META_DESCRIPTION);
		}
		return $this->htmlMetaDescription;
	}

	/** @return String **/
	public function getHtmlMetaKeywords() {
		if(empty($this->htmlMetaKeywords)){
			$xh = $this->getFullNewsXML();
			$this->htmlMetaKeywords = $xh->getVal(HTML_META_KEYWORDS);
		}
		return $this->htmlMetaKeywords;
	}

	/** @return String **/
	public function getHtmlMetaLanguage() {
		if(empty($this->htmlMetaLanguage)){
			$xh = $this->getFullNewsXML();
			$this->htmlMetaLanguage = $xh->getVal(HTML_META_LANGUAGE);
		}
		return $this->htmlMetaLanguage;
	}

	/** @return String **/
	public function getTags() {
		if(empty($this->tags)){
			$xh = $this->getFullNewsXML();
			$this->tags = $xh->getVal(TAGS);
		}
		return $this->tags;
	}

	/** @return int **/
	public function getPriority() {
		if(empty($this->priority)){
			$xh = $this->getFullNewsXML();
			$this->priority = $xh->getVal(PRIORITY);
		}
		return $this->priority;
	}

	/** @return String **/
	public function getFormat() {
		if(empty($this->format)){
			$xh = $this->getFullNewsXML();
			$this->htmlMetaLanguage = $xh->getAttr(TEXT, FORMAT);
		}
		return $this->format;
	}
}
/**
 * Custom Exception DateParseException to be thrown if a date does not parse correctly
 * @package SamplePHPApi
 */
class DateParseException extends Exception{}
?>