<?php

class Blog_Reference extends Pimcore_Model_Abstract {

    /**
     * @var integer
     */
    public $blogDocumentId;

    /**
     * @var integer
     */
    public $blogComponentName;

    /**
     * @var integer
     */
    public $postObjectId;

    /**
     * @var integer
     */
    public $postDocumentId;

    /**
     * @param integer $id
     * @return Site
     */
    public static function getByPostObjectId($blogDocumentId,$postObjectId) {
        $blogReferences = new self();
        $blogReferences->getResource()->getByPostObjectId($blogDocumentId,$postObjectId);

        return $blogReferences;
    }

    /**
     * @param integer $id
     * @return Site
     */
    public static function getByPostDocumentId($postDocumentId) {
        $blogReferences = new self();
        $blogReferences->getResource()->getByPostDocumentId($postDocumentId);

        return $blogReferences;
    }

    /**
     * @param array $config
     * @return Blog_Reference_List
     */
    public static function getList($config = array()) {
        if (is_array($config)) {
            $list = new Blog_Reference_List();

            $list->setValues($config);
            $list->load();

            return $list;
        }
    }

    /**
     * @param array $config
     * @return array
     */
    public static function getPostObjectsList($config = array()) {
        if (is_array($config)) {
            $list = new Blog_Reference_List();

            $list->setValues($config);

            return $list->loadPostObjectIdList();
        }
    }

    /**
     * @param array $config
     * @return Blog_Reference_List
     */
    public static function getPostDocumentList($config = array()) {
        if (is_array($config)) {
            $list = new Blog_Reference_List();

            $list->setValues($config);

            return $list->loadPostDocumentIdList();
        }
    }

    /**
     * @return mixed
     */
    public function getData() {
        return array(
            'blogDocumentId' => $this->getBlogDocumentId(),
            'blogComponentName' => $this->getBlogComponentName(),
            'postObjectId' => $this->getPostObjectId(),
            'postDocumentId' => $this->getPostDocumentId()
        );
    }

    /**
     * @param array $data
     * @return Site
     */
    public static function create($data) {
        $blogReferences = new self();
        $blogReferences->setValues($data);
        return $blogReferences;
    }

    /**
     * @return Blog_References
     */
    public function setBlogDocumentId($blogDocumentId){
        $this->blogDocumentId = $blogDocumentId;
        return $this;
    }

    /**
     * @return Blog_References
     */
    public function setBlogComponentName($blogComponentName){
        $this->blogComponentName = $blogComponentName;
        return $this;
    }

    /**
     * @return Blog_References
     */
    public function setPostObjectId($postObjectId){
        $this->postObjectId = $postObjectId;
        return $this;
    }

    /**
     * @return Blog_References
     */
    public function setPostDocumentId($postDocumentId){
        $this->postDocumentId = $postDocumentId;
        return $this;
    }

    /**
     * @return integer
     */
    public function getBlogDocumentId(){
        return $this->blogDocumentId;
    }

    /**
     * @return integer
     */
    public function getBlogComponentName(){
        return $this->blogComponentName;
    }

    /**
     * @return integer
     */
    public function getPostObjectId(){
        return $this->postObjectId;
    }

    /**
     * @return integer
     */
    public function getPostDocumentId(){
        return $this->postDocumentId;
    }
}
