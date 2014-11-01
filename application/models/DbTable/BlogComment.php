<?php

class Model_BlogComment extends Zend_Db_Table_Abstract
{
    protected $_name = 'blogcomments';
    protected $_referenceMap = array(
        'Blog' => array(
            'columns' => 'blogPost',
            'refTableClass' => 'Model_Blog',
            'refColumns' => 'id'
        ),
        'Parent' => array(
            'columns' => 'parentComment',
            'refTableClass' => 'Model_BlogComment',
            'refColumns' => 'id'
        ),
    );

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->setRowClass("BlogComment_Row");
    }

    public function getComment($id) {
        $row = $this->fetchRow($this->select()->where("id=?", (int) $id));
        if (!$row)
            throw new Exception("Invalid comment.");
        return $row;
    }
    
    public function addComment(Model_Blog $blogPost, Model_BlogComment $parentComment=null, $posterName, $title, $comment, $ip, $visible=true, $method, $get, $post, $server) {
        $this->insert(array(
                'blogPost' => $blogPost->id,
                'parentComment' => ($parentComment ? $parentComment->id : null),
                'posterName' => $posterName,
                'date' => Zend_Date::now(),
                'title' => $title,
                'comment' => $comment,
                'ip' => $ip,
                'visible' => (bool) $visible,
                'method' => $method,
                'get' => $get,
                'post' => $post,
                'server' => $server,
        ));
    }
    
    public function updateComment($id, Model_Blog $blogPost, Model_BlogComment $parentComment=null, $posterName, $title, $comment, $ip, $visible=true, $method, $get, $post, $server) {
        $this->update(array(
                'blogPost' => $blogPost->id,
                'parentComment' => ($parentComment ? $parentComment->id : null),
                'posterName' => $posterName,
                'date' => Zend_Date::now(),
                'title' => $title,
                'comment' => $comment,
                'ip' => $ip,
                'visible' => (bool) $visible,
                'method' => $method,
                'get' => $get,
                'post' => $post,
                'server' => $server,
            ),
            'id = ' . (int) $id);
    }
    
    public function deleteComment($id) {
        $this->delete('id = ' . (int) $id);
    }
}

class BlogComment_Row extends Zend_Db_Table_Row_Abstract {
    public function init() {
        $this->date = new Zend_Date($this->date);
    }
    
    public function findChildComments($includeHidden=false) {
        $select = $this->select()->where("parentComment = {$this->id}");
        if (!$includeHidden) {
            $select->where("visible=1");
        }
        return $this->findDependentRowset("Model_BlogComment", null, $select);
    }
}

