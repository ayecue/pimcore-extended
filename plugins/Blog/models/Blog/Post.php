<?php

class Blog_Post extends Pimcore_Model_Abstract {

    public $reference = NULL;
    public $model = NULL;
    public $parent = NULL;
    public $page = NULL;
    public $component = NULL;

    public $blogDocumentId = NULL;
    public $postObjectId = NULL;
    public $postDocumentId = NULL;

    public static function getByPostDocumentId($postDocumentId){
        $post = new self();

        $post->setPostDocumentId($postDocumentId);

        return $post;
    }

    public static function getByPostObjectId($blogDocumentId,$postObjectId){
        $post = new self();

        $post->setBlogDocumentId($blogDocumentId);
        $post->setPostObjectId($postObjectId);

        return $post;
    }

    public function setBlogDocumentId($blogDocumentId){
        $this->blogDocumentId = $blogDocumentId;
        return $this;
    }

    public function getBlogDocumentId(){
        return $this->blogDocumentId;
    }

    public function setPostObjectId($postObjectId){
        $this->postObjectId = $postObjectId;
        return $this;
    }

    public function getPostObjectId(){
        return $this->postObjectId;
    }

    public function setPostDocumentId($postDocumentId){
        $this->postDocumentId = $postDocumentId;
        return $this;
    }

    public function getPostDocumentId(){
        return $this->postDocumentId;
    }

    public function setReference(){
        if (!empty($this->postDocumentId) && empty($this->reference)) {
            $this->reference = Blog_Reference::getByPostDocumentId($this->postDocumentId);
        } elseif (!empty($this->blogDocumentId) && !empty($this->postObjectId) && empty($this->reference)) {
            $this->reference = Blog_Reference::getByPostObjectId($this->blogDocumentId,$this->postObjectId);
        }
        return $this;
    }

    public function getReference(){
        $this->setReference();
        return $this->reference;
    }

    public function setModel(){
        $reference = $this->getReference();
        if (!$this->isEmpty()) {
            $this->model = Object_BlogPost::getById($reference->getPostObjectId());
        }
        return $this;
    }

    public function getModel(){
        $this->setModel();
        return $this->model;
    }

    public function setParent(){
        $reference = $this->getReference();
        if (!$this->isEmpty()) {
            $this->parent = Document_Page::getById($reference->getBlogDocumentId());
        }
        return $this;
    }

    public function getParent(){
        $this->setParent();
        return $this->parent;
    }

    public function setPage(){
        $reference = $this->getReference();
        if (!$this->isEmpty()) {
            $this->page = Document_Page::getById($reference->getPostDocumentId());
        }
        return $this;
    }

    public function getPage(){
        $this->setPage();
        return $this->page;
    }

    public function setComponent(){
        $reference = $this->getReference();
        if (!$this->isEmpty()) {
            $this->component = Document_Tag_Blog::getById($reference->getBlogComponentName());
        }
        return $this;
    }

    public function getComponent(){
        $this->setComponent();
        return $this->component;
    }

    public function isEmpty(){
        return empty($this->reference) || !is_object($this->reference);
    }

    public function getUrlTitle(){
        $model = $this->getModel();
        return $model->getUrlTitle();
    }

    public function getBlogCategories(){
        $model = $this->getModel();
        return $model->getBlogCategories();
    }

    public function getTitle($language = null){
        $model = $this->getModel();
        return $model->getTitle($language);
    }

    public function getSummary($language = null){
        $model = $this->getModel();
        return $model->getSummary($language);
    }

    public function getExtendedPreview($language = null){
        $model = $this->getModel();
        return $model->getExtendedPreview($language);
    }

    public function getText($language = null){
        $model = $this->getModel();
        return $model->getExtendedPreview($language);
    }

    public function getExtendedText($language = null){
        $model = $this->getModel();
        return $model->getExtendedPreview($language);
    }

    public function getIcon(){
        $model = $this->getModel();
        return $model->getIcon();
    }

    public function getSmall(){
        $model = $this->getModel();
        return $model->getIcon();
    }

    public function getBig(){
        $model = $this->getModel();
        return $model->getIcon();
    }

    public function getPosition(){
        $component = $this->getComponent();
        $position = $component->getPositionById($this->model->getId());

        return $position;
    }

    public function getLastPost(){
        $component = $this->getComponent();
        $reference = $this->getReference();
        $objectId = $component->getLastPostById($this->model->getId());

        return self::getByPostObjectId($reference->getBlogDocumentId(),$objectId);
    }

    public function getNextPost(){
        $component = $this->getComponent();
        $reference = $this->getReference();
        $objectId = $component->getNextPostById($this->model->getId());

        return self::getByPostObjectId($reference->getBlogDocumentId(),$objectId);
    }

    public function getLastUrl(){
        $post = $this->getLastPost();
        $page = $post->getPage();

        if ($page) {
            return $page->getFullPath();
        }
    }

    public function getNextUrl(){
        $post = $this->getNextPost();
        $page = $post->getPage();

        if ($page) {
            return $page->getFullPath();
        }
    }

    public function getBackUrl(){
        $parent = $this->getParent();
        return $parent->getFullPath();
    }
}
