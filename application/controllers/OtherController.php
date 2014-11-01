<?php

class OtherController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->cache(array('index','colour-nick'), array('other_controller'));
    }

    public function indexAction()
    {
        // action body
    }

    public function colourNickAction()
    {
        // action body
    }

}





