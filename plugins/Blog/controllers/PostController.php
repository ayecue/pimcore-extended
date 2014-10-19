<?php

class Blog_PostController extends Pimcore_Controller_Action_Admin {
	public function editAction(){
		$this->enableLayout();

		$this->view->config = Pimcore_Config::getSystemConfig();
        $this->view->objectid = $this->_getParam('oid');
	}
}