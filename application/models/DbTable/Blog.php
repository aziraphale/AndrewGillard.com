<?php

class Model_Blog extends Zend_Db_Table_Abstract
{

    protected $_name = 'blog';

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->setRowClass("Blog_Row");
    }

	public function getEntry($id) {
		$row = $this->fetchRow($this->select()->where("id=?", (int) $id));
		if (!$row)
			throw new Exception("Invalid blog entry.");
		return $row;
	}
	
	public function addEntry($title, $body, $isHtml=false) {
		$date = date('Y-m-d H:i:s');
		$this->insert(array(
				'date' => $date,
				'lastEdit' => $date,
				'title' => $title,
				'body' => $body,
                'isHtml' => (bool) $isHtml,
				'visible' => true,
		));
	}
	
	public function updateEntry($id, $title, $body, $isHtml=false, $visible=true) {
		$date = date('Y-m-d H:i:s');
		$this->update(array(
				'lastEdit' => $date,
				'title' => $title,
				'body' => $body,
                'isHtml' => (bool) $isHtml,
				'visible' => (bool) $visible,
			),
			'id = ' . (int) $id);
	}
	
	public function deleteEntry($id) {
		$this->delete('id = ' . (int) $id);
	}
}

class Blog_Row extends Zend_Db_Table_Row_Abstract {
    public function init() {
        $this->date = new Zend_Date($this->date);
    }
    
    public function shortBody() {
        $out = $this->body;
        if (preg_match("#^(.+?\r?\n\r?\n)#us", $out, $matches1)) {
            if (preg_match("#(.+?\r?\n\r?\n)#us", $out, $matches2, PREG_OFFSET_CAPTURE, strlen($matches1[1]))) {
                $matches1[1] .= $matches2[1][0];
            }
            $out = $matches1[1];
        }
        return $out;
    }
    
    public function findAllComments($includeHidden=false) {
        $select = $this->select();
        if (!$includeHidden) {
            $select->where("visible=1");
        }
        return $this->findModel_BlogComment($select);
    }
    
    public function findTopLevelComments($includeHidden=false) {
        $select = $this->select()->where("parentComment IS NULL");
        if (!$includeHidden) {
            $select->where("visible=1");
        }
        return $this->findModel_BlogComment($select);
    }
    
    public function getCommentCount($includeHidden=false) {
        return $this->findAllComments($includeHidden)->count();
    }
}