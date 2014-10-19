<?php

class Document_Tag_Blogpost extends Document_Tag {

    /**
     * @var Blog_Post
     */
    public $post = NULL;

    /**
     * @see Document_Tag_Blog::getType
     * @return string
     */
    public function getType() {
        return "blogpost";
    }

    public function setPost(){
        $view = $this->getView();
        if (!empty($view) && empty($this->post)) {
            $this->post = Blog_Service::getEntry($view);
        }
        return $this;
    }

    public function getPost(){
        $this->setPost();
        return $this->post;
    }

    /**
     * @see Document_Tag_Interface::getData
     * @return mixed
     */
    public function getData() {
        return array(
            'post' => $this->getPost()
        );
    }

    /**
     * @see Document_Tag_Interface::frontend
     * @return void
     */
    public function frontend() {

        $post = $this->getPost();
        $return = "";

        if (!empty($post)) {
            $return .= Element_Service::getElementType($post) . ": " . $post->getFullPath() . "<br />";
        }

        return $return;
    }

    /**
     * @see Document_Tag_Interface::setDataFromResource
     * @param mixed $data
     * @return void
     */
    public function setDataFromResource($data) {
        return $this;
    }

    /**
     * @see Document_Tag_Interface::setDataFromEditmode
     * @param mixed $data
     * @return void
     */
    public function setDataFromEditmode($data) {
        return $this;
    }

    public function __call($method, $arguments) {

        $post = $this->getPost();

        if (method_exists($post,$method)) {
            return call_user_func_array(array($this->post, $method), $arguments);
        } else if (method_exists($this,$method)) {
            return call_user_func_array(array($this, $method), $arguments);
        }

        return parent::__call($method, $arguments);
    }
}
