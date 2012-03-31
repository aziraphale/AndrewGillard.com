<?php

/**
 * @see Zend_Service_Flickr_Result
 */
require_once 'Zend/Service/Flickr/ResultSet.php';

class AG_Flickr_PhotosetResultSet extends Zend_Service_Flickr_ResultSet
{
    /**
     * Reference to Zend_Service_Flickr object with which the request was made
     *
     * @var Zend_Service_Flickr
     */
    private $_flickr;
    
    /**
     * Parse the Flickr Result Set
     *
     * @param  DOMDocument         $dom
     * @param  Zend_Service_Flickr $flickr
     * @return void
     */
    public function __construct(DOMDocument $dom, Zend_Service_Flickr $flickr)
    {
        $this->_flickr = $flickr;
        
        // Now overwrite the values that the parent constructor failed to set
        $xpath = new DOMXPath($dom);

        $photos = $xpath->query('//photosets')->item(0);

        $page    = $photos->getAttribute('page');
        $pages   = $photos->getAttribute('pages');
        $perPage = $photos->getAttribute('perpage');
        $total   = $photos->getAttribute('total');

        $this->totalResultsReturned  = ($page == $pages || $pages == 0) ? ($total - ($page - 1) * $perPage) : (int) $perPage;
        $this->firstResultPosition   = ($page - 1) * $perPage + 1;
        $this->totalResultsAvailable = (int) $total;

        if ($total > 0) {
            $this->_results = $xpath->query('//photoset');
        }
    }

    /**
     * Implements SeekableIterator::current()
     *
     * @return AG_Flickr_PhotosetResult
     */
    public function current()
    {
        return new AG_Flickr_PhotosetResult($this->_results->item($this->key()), $this->_flickr);
    }
}

