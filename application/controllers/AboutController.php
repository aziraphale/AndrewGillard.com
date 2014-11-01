<?php

class AboutController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->cache(array('index'), array('about_indexaction'));
    }

    public function indexAction()
    {
        // action body
    }


}

