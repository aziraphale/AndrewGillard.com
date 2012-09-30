<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype() {
		$this->bootstrap('view');
	}
    
    protected function _initRouting() {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->addRoute('newsxml', new Zend_Controller_Router_Route_Static('news.xml', array('controller'=>'blog', 'action'=>'rss')));
        $router->addRoute('blogpost', new Zend_Controller_Router_Route('blog/:id/:title', array('controller'=>'blog', 'action'=>'view')));
        $router->addRoute('galleryalbum', new Zend_Controller_Router_Route('gallery/:id/:atitle', array('controller'=>'gallery', 'action'=>'album')));
        $router->addRoute('galleryimage', new Zend_Controller_Router_Route('gallery/:id/:atitle/:image/:ititle', array('controller'=>'gallery', 'action'=>'view')));
        $router->addRoute('dwmap', new Zend_Controller_Router_Route('discworld/maps/:map', array('controller'=>'discworld', 'action'=>'maps')));
    }

    protected function _initNavigation() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
        $config = new Zend_Config_Json(APPLICATION_PATH.'/configs/navigation.json', 'nav');
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);
        
        Zend_Registry::set("extrabreadcrumbs", array());
        
        Zend_Controller_Front::getInstance()->registerPlugin(new AG_Plugin_Navigation());
    }
    
    protected function _initPagination() {
        Zend_Paginator::setDefaultItemCountPerPage(10);
        Zend_Paginator::setDefaultScrollingStyle("Sliding"); // "Sliding" or "Elastic"
        Zend_View_Helper_PaginationControl::setDefaultViewPartial("pagination.phtml");
    }
    
    protected function _initCache() {
        //$pageCacheRegexps = array(
//            '^/discworld/language-graphs' => array( 'cache'=>true,
//                                                    'cache_with_post_variables'=>true,
//                                                    'make_id_with_post_variables'=>true),
//            '^/$' => array('cache'=>true),
//            '^/' => array('cache'=>true), // just cache everything
//            
//        );
//        $pageCache = Zend_Cache::factory(   'Page',
//                                            'Apc', //'File',
//                                            array(
//                                                'lifetime' => 900,
//                                                'debug_header' => false,
//                                                'regexps' => $pageCacheRegexps,
//                                            ),
//                                            array()
//                                        );
//        $pageCache->start();
        
        $cache = Zend_Cache::factory(   'Core',
                                        'Apc',// 'File', 
                                        array(
                                            'lifetime' => 300,
                                            'automatic_serialization' => true
                                        ),
                                        array(
//                                            'cache_dir' => APPLICATION_PATH.'/tmp'
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

