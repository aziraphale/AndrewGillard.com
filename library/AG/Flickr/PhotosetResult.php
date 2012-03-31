<?php

class AG_Flickr_PhotosetResult
{
    /*
    <photoset id="72157629335364882" primary="6882780838" secret="23cc4aa068" server="6053" farm="7" photos="11" videos="0" needs_interstitial="0" visibility_can_see_set="1" count_views="0" count_comments="0" can_comment="0" date_create="1333095075" date_update="1333095161">
        <title>Astronomy/Sky</title>
        <description>Photos of the sky and/or stars/the moon/planets</description>
    </photoset>
    */

    /**
     * The photo's Flickr ID.
     *
     * @var string
     */
    public $id;

    /**
     * The primary photo ID
     *
     * @var string
     */
    public $primary;

    /**
     * A key used in URI construction.
     *
     * @var string
     */
    public $secret;

    /**
     * The servername to use for URI construction.
     *
     * @var string
     */
    public $server;

    /**
     * The photo set's title.
     *
     * @var string
     */
    public $title;

    /**
     * The photo set's description.
     *
     * @var string
     */
    public $description;

    /**
     * The number of photos in this set.
     *
     * @var string
     */
    public $photos;

    /**
     * The number of videos in this set.
     *
     * @var string
     */
    public $videos;

    /**
     * 
     *
     * @var string
     */
    public $needs_interstitial;

    /**
     * Whether the photo set is visible
     *
     * @var string
     */
    public $visibility_can_see_set;

    /**
     * The number of views of this set
     *
     * @var string
     */
    public $count_views;

    /**
     * The number of comments in this set
     *
     * @var string
     */
    public $count_comments;

    /**
     * Whether comments can be made to this set
     *
     * @var string
     */
    public $can_comment;

    /**
     * The date the photoset was created.
     *
     * @var string
     */
    public $date_create;

    /**
     * The date the photoset was updated.
     *
     * @var string
     */
    public $date_update;

    /**
     * A 75x75 pixel square thumbnail of the primary image.
     *
     * @var Zend_Service_Flickr_Image
     */
    protected $Square;

    /**
     * A 100 pixel thumbnail of the primary image.
     *
     * @var Zend_Service_Flickr_Image
     */
    protected $Thumbnail;

    /**
     * A 240 pixel version of the primary image.
     *
     * @var Zend_Service_Flickr_Image
     */
    protected $Small;

    /**
     * A 500 pixel version of the primary image.
     *
     * @var Zend_Service_Flickr_Image
     */
    protected $Medium;

    /**
     * A 640 pixel version of the primary image.
     *
     * @var Zend_Service_Flickr_Image
     */
    protected $Large;

    /**
     * The original primary image.
     *
     * @var Zend_Service_Flickr_Image
     */
    protected $Original;

    /**
     * Original Zend_Service_Flickr object.
     *
     * @var Zend_Service_Flickr
     */
    protected $_flickr;

    /**
     * Parse the Flickr Result
     *
     * @param  DOMElement          $image
     * @param  Zend_Service_Flickr $flickr Original Zend_Service_Flickr object with which the request was made
     * @return void
     */
    public function __construct(DOMElement $image, Zend_Service_Flickr $flickr)
    {
        $xpath = new DOMXPath($image->ownerDocument);

        foreach ($xpath->query('./@*', $image) as $property) {
            $this->{$property->name} = (string) $property->value;
        }
        
        $this->title = $xpath->evaluate("string(./title)", $image);
        $this->description = $xpath->evaluate("string(./description)", $image);

        $this->_flickr = $flickr;
    }
    
    protected function populateImages() {
        if (isset($this->Small))
            return;
        
        foreach ($this->_flickr->getImageDetails($this->primary) as $k => $v) {
            $this->$k = $v;
        }
    }
    
    /**
     * A 75x75 pixel square thumbnail of the primary image.
     *
     * @returns Zend_Service_Flickr_Image
     */
    public function Square() {
        $this->populateImages();
        return $this->Square;
    }

    /**
     * A 100 pixel thumbnail of the primary image.
     *
     * @returns Zend_Service_Flickr_Image
     */
    public function Thumbnail() {
        $this->populateImages();
        return $this->Thumbnail;
    }

    /**
     * A 240 pixel version of the primary image.
     *
     * @returns Zend_Service_Flickr_Image
     */
    public function Small() {
        $this->populateImages();
        return $this->Small;
    }

    /**
     * A 500 pixel version of the primary image.
     *
     * @returns Zend_Service_Flickr_Image
     */
    public function Medium() {
        $this->populateImages();
        return $this->Medium;
    }

    /**
     * A 640 pixel version of the primary image.
     *
     * @returns Zend_Service_Flickr_Image
     */
    public function Large() {
        $this->populateImages();
        return $this->Large;
    }

    /**
     * The original primary image.
     *
     * @returns Zend_Service_Flickr_Image
     */
    public function Original() {
        $this->populateImages();
        return $this->Original;
    }
}
