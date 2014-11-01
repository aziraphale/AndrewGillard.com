<?php

class Model_GalleryImage extends Zend_Db_Table_Abstract
{
    protected $_name = 'galleryimages';
    protected $_referenceMap = array(
        'Album' => array(
            'columns' => 'album',
            'refTableClass' => 'Model_GalleryAlbum',
            'refColumns' => 'id'
        ),
    );

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->setRowClass("GalleryImage_Row");
    }

    public function getImage($id) {
        $row = $this->fetchRow($this->select()->where("id=?", (int) $id));
        if (!$row)
            throw new Exception("Invalid image.");
        return $row;
    }
    
    public function addImage(Model_GalleryAlbum $album, $filename, $caption, $height, $width, $thumbHeight, $thumbWidth, $make, $model, $dateTime, $exposureTime, $fNumber, $isoSpeed) {
        $this->insert(array(
                'album' => $album->id,
                'filename' => $filename,
                'caption' => $caption,
                'height' => $height,
                'width' => $width,
                'tHeight' => $thumbHeight,
                'tWidth' => $thumbWidth,
                'make' => $make,
                'model' => $model,
                'dateTime' => $dateTime,
                'exposureTime' => $exposureTime,
                'fNumber' => $fNumber,
                'isoSpeed' => $isoSpeed,
        ));
    }
    
    public function updateImage($id, Model_GalleryAlbum $album, $filename, $caption, $height, $width, $thumbHeight, $thumbWidth, $make, $model, $dateTime, $exposureTime, $fNumber, $isoSpeed) {
        $this->update(array(
                'album' => $album->id,
                'filename' => $filename,
                'caption' => $caption,
                'height' => $height,
                'width' => $width,
                'tHeight' => $thumbHeight,
                'tWidth' => $thumbWidth,
                'make' => $make,
                'model' => $model,
                'dateTime' => $dateTime,
                'exposureTime' => $exposureTime,
                'fNumber' => $fNumber,
                'isoSpeed' => $isoSpeed,
            ),
            'id = ' . (int) $id);
    }
    
    public function incrementViews($id) {
        $this->update(array(
                'views' => new Zend_Db_Expr('`views` + 1'),
            ),
            'id = ' . (int) $id);
    }
    
    public function deleteImage($id) {
        $this->delete('id = ' . (int) $id);
    }
}

class GalleryImage_Row extends Zend_Db_Table_Row_Abstract {
    public function findAlbum() {
        return $this->findParentModel_GalleryAlbum();
    }
    
    public function findFirstImage() {
        $imgs = $this->findImages();
        if ($imgs) {
            return $imgs[0];
        }
        return null;
    }
}

