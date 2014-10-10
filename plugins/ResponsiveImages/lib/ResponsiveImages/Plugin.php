<?php

class ResponsiveImages_Plugin extends ResponsiveImages_Config {
    const PLUGIN_STACK_INDEX = 1000;

    public static function install(){
		parent::install();
	}
	
	public static function uninstall(){
		// nothing to do
	}

	public static function isInstalled(){
		return parent::isInstalled();
	}

	public function preDispatch() {
		$configuration = $this->getConfiguration();

	 	// Pimcore CDN is not enabled by default in Pimcore.php                  
		if(!isset($_SERVER['HTTP_SECURE']) && Pimcore_Tool::isFrontend() && ! Pimcore_Tool::isFrontentRequestByAdmin()){
			$parser = new ResponsiveImages_Controller_Plugin_Parser();

			$parser->setScriptSource($configuration->responsiveImageScript);
			$parser->setAttrSelector($configuration->responsiveImageAttrSelector);
			$parser->setParseAttr($configuration->responsiveImageParseAttr);

            // 805 means trigger this plugin later than other plugins (with lower numbers)
			$instance = Zend_Controller_Front::getInstance();

			$instance->registerPlugin($parser,self::PLUGIN_STACK_INDEX);
		}
	}
}

