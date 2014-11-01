<?php

class Web2MessengerController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->cache(array('index'), array('web2messenger_indexaction'));
    }

    public function indexAction()
    {
        // action body
    }


}

