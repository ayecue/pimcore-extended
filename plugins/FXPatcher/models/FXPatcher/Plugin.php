<?php

class FXPatcher_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {
	public static function install(){
		return "Are you sure Plugin successfully installed.";
	}
	
	public static function uninstall(){
		return "Are you sure Plugin successfully uninstalled.";
	}

	public static function isInstalled(){
		return true;
	}

	public static function getTranslationFile($language) {
        if(file_exists(PIMCORE_PLUGINS_PATH . "/FXImproter/texts/" . $language . ".csv")){
            return "/FXImproter/texts/" . $language . ".csv";
        }
        
        return "/FXImproter/texts/en.csv";
    }

    public function preDispatch() {
		$injector = new FXPatcher_Controller_Plugin_Injector();
		$instance = Zend_Controller_Front::getInstance();
		$instance->registerPlugin($injector);	
	}
}