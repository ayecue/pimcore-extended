<?php

/**
 *  Blog
 *
 *  Basic:
 *  Use getView and getDocumentId to receive source information.
 *
 *  Rss:
 *  UI should have button to enable rss feed which basicly create a predefined child doc
 *  Helper class should get rss path
 *
 *  UI of interface:
 *  Actuall interface which should help the user to create blog posts
 *  Popup to write new blog entrys. Popup got two modes simple/advanced.
 *  Simple just got simple fields which should be easy enough.
 *  Advanced got full wysiwyg control.
 *  All fields should be i18n.
 *  List to manage all entrys. (Sorting,Deleting)
 *
 *  Important sources:
 *  static/js/pimcore/object/version (/admin/get/object)
 *  static/js/pimcore/object/objects
 *  modules/admin/controller/ObjectController (getAction/saveAction/addAction)
 */

class Document_Tag_Blog extends Document_Tag {

    /**
     * @var array
     */
    public $posts = array();

    /**
     * @var array
     */
    public $postIds = array();

    /**
     * @var array
     */
    public $categories = array();

    /**
     * @var array
     */
    public $categoriesIds = array();

    /**
     * @var boolean
     */
    public $rssActive = FALSE;

    /**
     * @var array
     */
    public $references = array();

    /**
     * @var integer
     */
    public $limit = -1;

    /**
     * @var object
     */
    public $postFolder = NULL;

    /**
     * @var integer
     */
    public $postFolderId = NULL;

    /**
     * @var object
     */
    public $categoryFolder = NULL;

    /**
     * @var integer
     */
    public $categoryFolderId = NULL;

    /**
     * @var string
     */
    public $postModule = "";

    /**
     * @var string
     */
    public $postController = "default";

    /**
     * @var string
     */
    public $postAction = "default";

    /**
     * @var string
     */
    public $postTemplate = "";

    /**
     * @see Document_Tag_Blog::getType
     * @return string
     */
    public function getType() {
        return "blog";
    }

    public function setReferences() {
        if(empty($this->references)) {
            $this->references = Blog_Reference::getList(array(
                "blogDocumentId" => $this->getDocumentId()
            ));
        }
        return $this;
    }

    public function getReferences() {
        $this->setReferences();
        return $this->references;
    }

    /**
     * @return boolean
     */
    public function isReferencesEmpty() {
        $posts = $this->getPosts();

        return !is_array($posts) || count($posts) == 0;
    }

    /**
     * 
     */
    public function setPosts() {
        if(!empty($this->postIds) && empty($this->posts)) {
            $this->posts = array();
            foreach ($this->postIds as $postId) {
                $bp = Object_BlogPost::getById($postId["id"]);
                if($bp instanceof Object_Concrete) {
                    $this->posts[] = $bp;
                }
            }
        }
        return $this;
    }

    /**
     * @return Object_BlogPost
     */
    public function getPosts() {
        $this->setPosts();
        return $this->posts;
    }

    /**
     * @return array
     */
    public function getPostsEditmode() {
        $posts = $this->getPosts();
        $return = array();

        if (!$this->isPostsEmpty()) {
            foreach ($posts as $post) {
                $return[] = array(
                    $post->getId(), 
                    $post->getFullPath(), 
                    $post->getKey()
                );
            }
        }

        return $return;
    }

    /**
     * @var integer $id
     * @return integer
     */
    public function getPostPositionById($id){
        foreach ($this->postIds as $index => $postId) {
            if ($postId["id"] == $id) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * @var integer $position
     * @return integer
     */
    public function getPostIdByPosition($position){
        if ($position >= 0 && $position < count($this->postIds)) {
            return $this->postIds[$position];
        }
        return -1;
    }

    /**
     * @var integer $id
     * @return integer
     */
    public function getLastPostById($id){
        $position = $this->getPostPositionById($id);

        return $this->getPostIdByPosition($position + 1);
    }

    /**
     * @var integer $id
     * @return integer
     */
    public function getNextPostById($id){
        $position = $this->getPostPositionById($id);

        return $this->getPostIdByPosition($position - 1);
    }

    /**
     * @return boolean
     */
    public function isPostsEmpty() {
        $posts = $this->getPosts();

        return !is_array($posts) || count($posts) == 0;
    }

    /**
     * @var boolean $rssActive
     * @return Document_Tag_Blog
     */
    public function setRssActive($rssActive = false){
        $this->rssActive = $rssActive;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getRssActive(){
        return $this->rssActive;
    }

    /**
     * @var integer $limit
     * @return Document_Tag_Blog
     */
    public function setLimit($limit = -1){
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLimit(){
        return $this->limit;
    }

    /**
     * @return integer
     */
    public function getPostFolderId(){
        return $this->postFolderId;
    }

    /**
     * @return Document_Tag_Blog
     */
    public function setPostFolder(){
        if (!empty($this->postFolderId) && empty($this->postFolder)) {
            $this->postFolder = Document_Folder::getById($this->postFolderId);
        }
        return $this;
    }

    /**
     * @return Document_Folder
     */
    public function getPostFolder(){
        $this->setPostFolder();
        return $this->postFolder;
    }

    /**
     * @return array
     */
    public function getPostFolderEditmode(){
        $postFolder = $this->getPostFolder();

        if (!empty($postFolder)) {
            return array(
                $postFolder->getId(), 
                $postFolder->getFullPath(), 
                $postFolder->getKey()
            );
        }
        return NULL;
    }

    /**
     * @return integer
     */
    public function getCategoryFolderId(){
        return $this->categoryFolderId;
    }

    /**
     * @return Document_Tag_Blog
     */
    public function setCategoryFolder(){
        if (!empty($this->categoryFolderId) && empty($this->categoryFolder)) {
            $this->categoryFolder = Object_Folder::getById($this->categoryFolderId);
        }
        return $this;
    }

    /**
     * @return Object_Folder
     */
    public function getCategoryFolder(){
        $this->setCategoryFolder();
        return $this->categoryFolder;
    }

    /**
     * @return array
     */
    public function getCategoryFolderEditmode(){
        $categoryFolder = $this->getCategoryFolder();

        if (!empty($categoryFolder)) {
            return array(
                $categoryFolder->getId(), 
                $categoryFolder->getFullPath(), 
                $categoryFolder->getKey()
            );
        }
        return NULL;
    }

    /**
     * @var string $blogPostModule
     * @return Document_Tag_Blog
     */
    public function setPostModule($module){
        $this->postModule = $module;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostModule(){
        return $this->postModule;
    }

    /**
     * @var string $blogPostController
     * @return Document_Tag_Blog
     */
    public function setPostController($controller = "default"){
        $this->postController = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostController(){
        return $this->postController;
    }

    /**
     * @var string $blogPostAction
     * @return Document_Tag_Blog
     */
    public function setPostAction($action = "default"){
        $this->postAction = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostAction(){
        return $this->postAction;
    }

    /**
     * @var string $blogPostTemplate
     * @return Document_Tag_Blog
     */
    public function setPostTemplate($template){
        $this->postTemplate = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostTemplate(){
        return $this->postTemplate;
    }

    /**
     * @see Document_Tag_Interface::getData
     * @return mixed
     */
    public function getData() {
        return array(
            'rssActive' => $this->getRssActive(),
            'limit' => $this->getLimit(),
            'postFolder' => $this->getPostFolder(),
            'categoryFolder' => $this->getCategoryFolder(),
            'postModule' => $this->getModule(),
            'postController' => $this->getPostController(),
            'postAction' => $this->getPostAction(),
            'postTemplate' => $this->getPostTemplate(),
            'posts' => $this->getPostPosts()
        );
    }

    /**
     * @see Document_Tag_Interface::getDataForResource
     * @return void
     */
    public function getDataForResource() {
        return array(
            'rssActive' => $this->rssActive,
            'limit' => $this->limit,
            'postFolder' => $this->postFolderId,
            'categoryFolder' => $this->categoryFolderId,
            'postModule' => $this->postModule,
            'postController' => $this->postController,
            'postAction' => $this->postAction,
            'postTemplate' => $this->postTemplate,
            'postIds' => $this->postIds
        );
    }

    /**
     * Converts the data so it's suitable for the editmode
     * @return mixed
     */
    public function getDataEditmode() {
        return array(
            'rssActive' => $this->getRssActive(),
            'limit' => $this->getLimit(),
            'postFolder' => $this->getPostFolderEditmode(),
            'categoryFolder' => $this->getCategoryFolderEditmode(),
            'postModule' => $this->getPostModule(),
            'postController' => $this->getPostController(),
            'postAction' => $this->getPostAction(),
            'postTemplate' => $this->getPostTemplate(),
            'posts' => $this->getPostsEditmode()
        );
    }

    /**
     * @see Document_Tag_Interface::frontend
     * @return void
     */
    public function frontend() {

        $posts = $this->getPosts();
        $return = "";

        if (!$this->isPostsEmpty()) {
            foreach ($posts as $post) {
                $return .= Element_Service::getElementType($post) . ": " . $post->getFullPath() . "<br />";
            }
        }

        return $return;
    }

    /**
     * @see Document_Tag_Interface::setDataFromResource
     * @param mixed $data
     * @return void
     */
    public function setDataFromResource($data) {
        if($data = Pimcore_Tool_Serialize::unserialize($data)) {
            $this->setDataFromEditmode($data);
        }
        return $this;
    }

    /**
     * @see Document_Tag_Interface::setDataFromEditmode
     * @param mixed $data
     * @return void
     */
    public function setDataFromEditmode($data) {
        if(is_array($data)) {
            $this->postIds = $data['postIds'];
            $this->postFolderId = $data['postFolderId'];
            $this->categoryFolderId = $data['categoryFolderId'];
            $this->rssActive = $data['rssActive'];
            $this->limit = $data['limit'];
            $this->postFolder = $data['postFolder'];
            $this->categoryFolder = $data['categoryFolder'];
            $this->postModule = $data['postModule'];
            $this->postController = $data['postController'];
            $this->postAction = $data['postAction'];
            $this->postTemplate = $data['postTemplate'];
        }
        return $this;
    }

    /**
     * @return array
     */
    public function resolveDependencies() {

        $posts = $this->getPosts();
        $dependencies = array();

        if (!$this->isPostsEmpty()) {
            foreach ($posts as $post) {
                $type = Element_Service::getElementType($post);
                $key = $type . "_" . $post->getId();

                $dependencies[$key] = array(
                    "id" => $post->getId(),
                    "type" => $type
                );
            }
        }

        return $dependencies;
    }

    /**
     * @return array
     */
    public function __sleep() {
        $finalVars = array();
        $parentVars = parent::__sleep();
        $blockedVars = array("posts");
        foreach ($parentVars as $key) {
            if (!in_array($key, $blockedVars)) {
                $finalVars[] = $key;
            }
        }

        return $finalVars;
    }

    /**
     *
     */
    public function load () {
        $this->setPosts();
    }

    /**
     * Methods for Iterator
     */

    public function rewind() {
        $posts = $this->getPosts();
        reset($posts);
    }

    public function current() {
        $posts = $this->getPosts();
        return current($posts);
    }

    public function key() {
        $posts = $this->getPosts();
        return key($posts);
    }

    public function next() {
        $posts = $this->getPosts();
        return next($posts);
    }

    public function valid() {
        return $this->current() !== false;
    }
}
