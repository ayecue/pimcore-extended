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
    public $blogPosts = array();

    /**
     * @var array
     */
    public $blogPostIds = array();

    /**
     * @var boolean
     */
    public $rssActive = false;

    /**
     * @see Document_Tag_Blog::getType
     * @return string
     */
    public function getType() {
        return "blog";
    }

    /*
     *
     */
    public function setBlogPosts() {
        if(empty($this->blogPosts)) {
            $this->blogPosts = array();
            foreach ($this->blogPostIds as $blogPostId) {
                $bp = Object_BlogPost::getById($blogPostId["id"]);
                if($bp instanceof Object_Concrete) {
                    $this->blogPosts[] = $bp;
                }
            }
        }
        return $this;
    }

    /**
     * @see Document_Tag_Interface::getData
     * @return mixed
     */
    public function getData() {
        return array(
            'rssActive' => $this->rssActive,
            'blogPosts' => $this->getBlogPosts()
        );
    }

    /**
     * @see Document_Tag_Interface::getDataForResource
     * @return void
     */
    public function getDataForResource() {
        return array(
            'rssActive' => $this->rssActive,
            'blogPostIds' => $this->blogPostIds
        );
    }

    /**
     * Converts the data so it's suitable for the editmode
     * @return mixed
     */
    public function getDataEditmode() {

        $blogPosts = $this->getBlogPosts();
        $posts = array();

        if (!$this->isEmpty()) {
            foreach ($blogPosts as $blogPost) {
                $posts[] = array(
                    $blogPost->getId(), 
                    $blogPost->getFullPath(), 
                    $blogPost->getKey()
                );
            }
        }

        return array(
            'rssActive' => $this->rssActive,
            'blogPosts' => $posts
        );
    }

    /**
     * @see Document_Tag_Interface::frontend
     * @return void
     */
    public function frontend() {

        $blogPosts = $this->getBlogPosts();
        $return = "";

        if (!$this->isEmpty()) {
            foreach ($blogPosts as $blogPost) {
                $return .= Element_Service::getElementType($blogPost) . ": " . $blogPost->getFullPath() . "<br />";
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
            $this->rssActive = $data['rssActive'];
            $this->blogPostIds = $data['blogPostIds'];
        }
        return $this;
    }

    /**
     * @return Element_Interface[]
     */
    public function getBlogPosts() {
        $this->setBlogPosts();
        return $this->blogPosts;
    }

    /**
     * @return boolean
     */
    public function isEmpty() {
        $blogPosts = $this->getBlogPosts();

        return !is_array($blogPosts) || count($blogPosts) == 0;
    }

    /**
     * @return array
     */
    public function resolveDependencies() {

        $blogPosts = $this->getBlogPosts();
        $dependencies = array();

        if (!$this->isEmpty()) {
            foreach ($blogPosts as $blogPost) {
                $type = Element_Service::getElementType($blogPost);
                $key = $type . "_" . $blogPost->getId();

                $dependencies[$key] = array(
                    "id" => $blogPost->getId(),
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
        $blockedVars = array("blogPosts");
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
        $this->setBlogPosts();
    }

    /**
     * Methods for Iterator
     */

    public function rewind() {
        $blogPosts = $this->getBlogPosts();
        reset($blogPosts);
    }

    public function current() {
        $blogPosts = $this->getBlogPosts();
        return current($blogPosts);
    }

    public function key() {
        $blogPosts = $this->getBlogPosts();
        return key($blogPosts);
    }

    public function next() {
        $blogPosts = $this->getBlogPosts();
        return next($blogPosts);
    }

    public function valid() {
        return $this->current() !== false;
    }
}
