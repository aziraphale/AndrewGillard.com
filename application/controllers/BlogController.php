<?php

class BlogController extends Zend_Controller_Action
{

    private $blogModel = null;

    public function init()
    {
        $this->blogModel = new Model_Blog();
        $this->view->bbcode = Zend_Markup::factory("Bbcode");
    }

    public function indexAction()
    {
        $this->view->paginator = Zend_Paginator::factory(
                $this->blogModel->select()
                            ->where("visible=1")
                            ->order('date DESC')
            )
            ->setItemCountPerPage(5)
            ->setCurrentPageNumber($this->_getParam('page', 1));
    }

    public function viewAction()
    {
    	$this->view->entry = $this->blogModel->getEntry($this->_getParam("id", 0));
        
        Zend_Registry::set("extrabreadcrumbs", Zend_Registry::get("extrabreadcrumbs") + array(array($this->view->entry->title)));
    }

    public function rssAction()
    {
        header("Content-type: text/xml");
        $this->view->paginator = Zend_Paginator::factory(
                $this->blogModel->select()
                            ->where("visible=1")
                            ->order('date DESC')
            )
            ->setItemCountPerPage(5)
            ->setCurrentPageNumber(1);
    }


}



