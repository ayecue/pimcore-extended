<?php

class CdnPlugin_Plugin extends CdnPlugin_Config {
    const PLUGIN_STACK_INDEX = 1001;

    public static function install() {
		parent::install();
    }

    public static function uninstall() {
		// nothing to do
    }

	public static function isInstalled() {
        return parent::isInstalled();
	}

	public function preDispatch() {       
		$configuration = $this->getConfiguration();

	 	// Pimcore CDN is not enabled by default in Pimcore.php                  
		if(!isset($_SERVER['HTTP_SECURE']) && Pimcore_Tool::isFrontend() && ! Pimcore_Tool::isFrontentRequestByAdmin()){
			//die('Ende');
			$cdn = new CdnPlugin_Controller_Plugin_CDN();
            // replace example urls by real domains offered from your cdn provider
			$cdn->setCdnhostnames(array(
                $configuration->cdnDomain
			));

			//get allowed folders and extensions
			$folders = explode(",",$configuration->cdnFolders);
			$extensions = explode(",",$configuration->cdnExtensions);

            // set pattern for urls you want to deliver via cdn
			$cdn->setCdnpatterns(array(
                // all urls that have "/website/" somewhere in their path
                "/(" . implode("|",$folders) . ")\/.+\.(" . implode("|",$extensions) . ")(\?[^\?]*)?$/i"
            ));
            // 805 means trigger this plugin later than other plugins (with lower numbers)
			$instance = Zend_Controller_Front::getInstance();

			$instance->registerPlugin($cdn,self::PLUGIN_STACK_INDEX);
		}
	}
}

