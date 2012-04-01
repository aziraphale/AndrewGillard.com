<?php

class AG_Plugin_Navigation extends Zend_Controller_Plugin_Abstract {
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        $nav = $view->navigation();
        
        switch (strtolower($request->getControllerName())) {
            case 'blog':
                switch (strtolower($request->getActionName())) {
                    case 'view':
                        $nav->findOneBy('controller', 'blog')->active = true;
                        break;
                }
                break;
            case 'gallery':
                switch (strtolower($request->getActionName())) {
                    case 'album':
                        $nav->findOneBy('controller', 'gallery')->active = true;
                        break;
                }
                break;
        }
    }
}

?>
