<?php

class Less_Plugin extends Less_Config {
    const PLUGIN_STACK_INDEX = 1003;

	public static function install() {
		parent::install();

		return "Plugin successfully installed.";
    }

    public static function uninstall() {
		return "Plugin successfully uninstalled.";
    }

	public static function isInstalled() {
        return parent::isInstalled();
	}

	public function preDispatch() {

	 	// Pimcore CDN is not enabled by default in Pimcore.php                  
		if(!isset($_SERVER['HTTP_SECURE']) && Pimcore_Tool::isFrontend()){
			//die('Ende');
			$less = new Less_Controller_Plugin_Parser();

            // 805 means trigger this plugin later than other plugins (with lower numbers)
			$instance = Zend_Controller_Front::getInstance();

			$instance->registerPlugin($less,self::PLUGIN_STACK_INDEX);
		}
	}
}

