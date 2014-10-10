<?php


class PageConfigurator_Config {
	private $properties = array();
	private $view;
	private $document;

	public function __construct($view) {
		$this->setView($view);
    }

    public function getView() {
    	return $this->view;
    }

    public function setView(Pimcore_View $view){
		$this->setDocument($view->_getParam('document'));

        $this->properties['editmode'] = $view->editmode;
        $this->properties['language'] = $view->language;

        $this->view = $view;
    }

    public function getDocument() {
    	return $this->document;
    }

    public function setDocument(Document_Page $page) {
    	$properties = $page->getProperties();

		foreach ($properties as $property) {
			$name = $property->name;
			$data = $property->data;
            $type = $property->type;
            $applied = false;

            if ($type == 'object') {
                $object = Object_Concrete::getById($data);

                if ($object instanceof Object_PageConfiguration) {
                    $fields = PageConfigurator_Helper::getClassFields($object);

                    foreach ($fields as $field) {
                        $key = $field->name;
                        $getter = $field->getter;

                        $this->properties[$key] = $object->$getter();
                    }

                    $applied = true;
                }
            }

            if (!$applied) {
                $this->properties[$name] = $data;
            }
		}

        $this->properties['title'] = $page->getTitle();
        $this->properties['description'] = $page->getDescription();
        $this->properties['pageId'] = $page->getId();
        $this->properties['path'] = $page->path;
        $this->properties['document'] = $page;

    	$this->document = $page;
    }

    public function getProperties() {
    	return $this->properties;
    }

    public function has($property) {
    	return isset($this->properties[$property]);
    }

    public function __get($property) {
        return $this->properties[$property];
    }
}