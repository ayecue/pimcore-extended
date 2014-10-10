<?php

class ContentController extends Website_Controller_Action
{
	public function init() 
	{
        parent::init();

        $this->setLayout($this->document->getProperty("layout"));
    }

	public function defaultAction () 
	{
        $this->enableLayout();
    }
}