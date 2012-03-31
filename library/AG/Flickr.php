<?php

require_once 'Zend/Service/Flickr.php';

/**
 * Another example of Zend Framework simultaneously doing everything and nothing - i.e. it has a
 *  lot of classes covering a wide range of features, but each class seems to lack a lot of
 *  expected functionality...
 */
class AG_Flickr extends Zend_Service_Flickr
{
    /**
     * Finds photo sets by a user's username or email.
     *
     * Additional query options include:
     *
     *  # per_page:        how many results to return per query
     *  # page:            the starting page offset.  first result will be (page - 1) * per_page + 1
     *
     * @param  string $query   username or email
     * @param  array  $options Additional parameters to refine your query.
     * @return Zend_Service_Flickr_ResultSet
     * @throws Zend_Service_Exception
     */
    public function userPhotosets($query, array $options = null)
    {
        static $method = 'flickr.photosets.getList';
        static $defaultOptions = array('per_page' => 500,
                                       'page' => 1,
                                       'extras'   => '');

        // can't access by username, must get ID first
        if (strchr($query, '@')) {
            // optimistically hope this is an email
            $options['user_id'] = $this->getIdByEmail($query);
        } else {
            // we can safely ignore this exception here
            $options['user_id'] = $this->getIdByUsername($query);
        }

        $options = $this->_prepareOptions($method, $options, $defaultOptions);
        $this->_validateUserSearch($options);

        // now search for photos
        $restClient = $this->getRestClient();
        $restClient->getHttpClient()->resetParameters();
        $response = $restClient->restGet('/services/rest/', $options);

        if ($response->isError()) {
            /**
             * @see Zend_Service_Exception
             */
            require_once 'Zend/Service/Exception.php';
            throw new Zend_Service_Exception('An error occurred sending request. Status code: '
                                           . $response->getStatus());
        }

        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());

        self::_checkErrors($dom);

        return new AG_Flickr_PhotosetResultSet($dom, $this);
    }
}

