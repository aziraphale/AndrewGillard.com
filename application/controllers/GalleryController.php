<?php

class GalleryController extends Zend_Controller_Action
{

    private $imageModel;
    private $albumModel;
    
    public function init()
    {
        $this->imageModel = new Model_GalleryImage();
        $this->albumModel = new Model_GalleryAlbum();
        
        $this->_helper->cache(array('index','album','view'), array('gallery_controller'));
    }

    public function indexAction()
    {
        $this->view->albums = $this->albumModel->fetchAll();
        
        $flickr = new AG_Flickr($this->getInvokeArg('bootstrap')->getOption('flickrApiKey'));
        $this->view->flickrAlbums = $flickr->userPhotosets($this->getInvokeArg('bootstrap')->getOption('flickrUsername'));
    }

    public function albumAction()
    {
        $this->view->album = $this->albumModel->getAlbum($this->_getParam("id", 0));
        if ($this->view->album) {
            $this->view->images = $this->view->album->findImages();
            
            Zend_Registry::set("extrabreadcrumbs", Zend_Registry::get("extrabreadcrumbs") + array(array($this->view->album->name)));
        }
    }

    private static function simplify(&$n, &$d) {
        if ($n == $d) {
            $n=1;
            $d=1;
        }
        $chng = true;
        while ($chng && ($d / 2) >= 2) {
            for ($i=2; $i<=$d/2; $i++) {
                if ($n % $i == 0 && $d % $i == 0) {
                    $n /= $i;
                    $d /= $i;
                    $chng = true;
                } else $chng = false;
            }
        }
    }
    
    public function viewAction()
    {
        $image = $this->imageModel->getImage($this->_getParam('image', 0));
        if ($image) {
            $this->view->image = $image;
            $this->view->album = $this->view->image->findAlbum();
            
            Zend_Registry::set("extrabreadcrumbs", Zend_Registry::get("extrabreadcrumbs") + array(
                array($this->view->album->name, $this->view->url(array('controller'=>'gallery', 'action'=>'album', 'id'=>$this->view->image->album, 'atitle'=>$this->view->image->findAlbum()->name), 'galleryalbum', true)),
                array($this->view->image->caption),
                ));
            
            $exifData = array();
            $exifData['Description'] = $image->caption;
            $exifData['Number of Views'] = $image->views;
            if (!empty($image->make)) $exifData['Camera Make'] = $image->make;
            if (!empty($image->model)) $exifData['Camera Model'] = $image->model;
            if (!empty($image->dateTime)) {
                $date = new Zend_Date($image->dateTime);
                $exifData['Date'] = $date->toString("EEE, d MMM yyyy, HH:mm:ss");
            }
            if (!empty($image->exposureTime)) {
                $exptime = explode('/', $image->exposureTime);
                self::simplify($exptime[0], $exptime[1]);
                $exifData['Exposure Time'] = $exptime[0] . '/' . $exptime[1] . ' secs';
            }
            if (!empty($image->fNumber)) $exifData['F Number'] = $image->fNumber;
            if (!empty($image->isoSpeed)) $exifData['ISO Speed'] = $image->isoSpeed;
            
            ++$image->views;
            $this->view->exifData = $exifData;
            
            $this->imageModel->incrementViews($this->view->image->id);
        }
    }
}





