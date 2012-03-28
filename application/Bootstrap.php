<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype() {
		$this->bootstrap('view');
	}

    protected function _initNavigation() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
//        $config = new Zend_Config_Xml(APPLICATION_PATH.'/configs/navigation.xml', 'nav');
        $config = new Zend_Config_Json(APPLICATION_PATH.'/configs/navigation.json', 'nav');
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);
        
        Zend_Paginator::setDefaultItemCountPerPage(10);
        Zend_Paginator::setDefaultScrollingStyle("Sliding"); // "Sliding" or "Elastic"
        Zend_View_Helper_PaginationControl::setDefaultViewPartial("pagination.phtml");
        
        $cache = Zend_Cache::factory(   'Core',
                                        'File', 
                                        array(
                                            'lifetime' => 300,
                                            'automatic_serialization' => true
                                        ),
                                        array(
                                            'cache_dir' => APPLICATION_PATH.'/tmp'
                                        ));
        Zend_Registry::set('cache', $cache);
    }
}

