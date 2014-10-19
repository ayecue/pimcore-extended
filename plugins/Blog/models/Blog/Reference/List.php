<?php

class Blog_Reference_List extends Pimcore_Model_List_Abstract implements Zend_Paginator_Adapter_Interface, Zend_Paginator_AdapterAggregate, Iterator {
	/**
     * List of references
     *
     * @var array
     */
    public $references = null;

    /**
     * @var integer
     */
    public $blogDocumentId;

    /**
     * List of valid order keys
     *
     * @var array
     */
    public $validOrderKeys = array(
        "blogDocumentId",
        "blogComponentName",
		"postObjectId",
		"postDocumentId"
    );

    /**
     * Test if the passed key is valid
     *
     * @param string $key
     * @return boolean
     */
    public function isValidOrderKey($key) {
        return true;
    }

    /**
     * @return array
     */
    public function getBlogDocumentId() {
        return $this->blogDocumentId;
    }

    /**
     * @param integer $blogDocumentId
     * @return void
     */
    public function setBlogDocumentId($blogDocumentId) {
        $this->blogDocumentId = $blogDocumentId;
        return $this;
    }

    /**
     * @return array
     */
    public function getReferences() {
        if ($this->references === null) {
            $this->load();
        }
        return $this->references;
    }

    /**
     * @param string $references
     * @return void
     */
    public function setReferences($references) {
        $this->references = $references;
        return $this;
    }
    
    public function getCondition(){
    	return "blogDocumentId = " . $this->blogDocumentId;
    }
    
    /**
     *
     * Methods for Zend_Paginator_Adapter_Interface
     */

    public function count() {
        return $this->getTotalCount();
    }

    public function getItems($offset, $itemCountPerPage) {
        $this->setOffset($offset);
        $this->setLimit($itemCountPerPage);
        return $this->load();
    }

    public function getPaginatorAdapter() {
        return $this;
    }
    

    /**
     * Methods for Iterator
     */

    public function rewind() {
        $this->getReferences();
        reset($this->references);
    }

    public function current() {
        $this->getReferences();
        return current($this->references);
    }

    public function key() {
        return key($this->references);
    }

    public function next() {
        $this->getReferences();
        return next($this->references);
    }

    public function valid() {
        $this->getReferences();
        return $this->current() !== false;
    }
}