<?php

class Model_GalleryAlbum extends Zend_Db_Table_Abstract
{
    protected $_name = 'galleryalbums';

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->setRowClass("GalleryAlbum_Row");
    }

    public function getAlbum($id) {
        $row = $this->fetchRow($this->select()->where("id=?", (int) $id));
        if (!$row)
            throw new Exception("Invalid album.");
        return $row;
    }
    
    public function addAlbum($name, $dirName) {
        $this->insert(array(
                'dirName' => $dirName,
                'name' => $name,
        ));
    }
    
    public function updateAlbum($id, $name, $dirName) {
        $this->update(array(
                'dirName' => $dirName,
                'name' => $name,
            ),
            'id = ' . (int) $id);
    }
    
    public function deleteAlbum($id) {
        $this->delete('id = ' . (int) $id);
    }
}

class GalleryAlbum_Row extends Zend_Db_Table_Row_Abstract {
    public function findImages() {
        return $this->findModel_GalleryImage();
    }
    
    public function findFirstImage() {
        $imgs = $this->findImages();
        if ($imgs) {
            return $imgs[0];
        }
        return null;
    }
}
