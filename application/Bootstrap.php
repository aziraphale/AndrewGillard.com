<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype() {
		$this->bootstrap('view');
	}
    
    protected function _initRouting() {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->addRoute('newsxml', new Zend_Controller_Router_Route_Static('news.xml', array('controller'=>'blog', 'action'=>'rss')));
    }

    protected function _initNavigation() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
        $config = new Zend_Config_Json(APPLICATION_PATH.'/configs/navigation.json', 'nav');
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);
    }
    
    protected function _initPagination() {
        Zend_Paginator::setDefaultItemCountPerPage(10);
        Zend_Paginator::setDefaultScrollingStyle("Sliding"); // "Sliding" or "Elastic"
        Zend_View_Helper_PaginationControl::setDefaultViewPartial("pagination.phtml");
    }
    
    protected function _initCache() {
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
    
    protected function _initResourceLoader() {
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH,
        ));
        $resourceLoader->addResourceType('model', 'models/DbTable/', 'Model');
//        $resourceLoader->addResourceType('form', 'forms/', 'Form');
//        $resourceLoader->addResourceType('service', 'services/', 'Service');

        return $resourceLoader;
    }
}

