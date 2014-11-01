<?php

class PortfolioController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->cache(array('index'), array('portfolio_indexaction'));
    }

    public function indexAction()
    {
        // action body
    }


}

