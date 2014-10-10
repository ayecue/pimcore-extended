<?php

class Classparser_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {
    
	/**
     * @see Classparser_Plugin::install
     * @return string
     */
    public static function install(){
    	$classname = Classparser_Config::getClassName();
    	$pathToJson = Classparser_Config::getClassJsonPath();
    	$success = Classparser_Install::createClassByJson($classname,$pathToJson);

    	if ($success && self::isInstalled()) {
			return "Plugin successfully installed.";
		} else {
			return "Plugin failed to install.";
		}
	}

	/**
     * @see Classparser_Plugin::uninstall
     * @return string
     */
	public static function uninstall() {
		$classname = Classparser_Config::getClassName();
        $success = Classparser_Install::removeClass($classname);

        if ($success && !self::isInstalled()) {
        	return "Plugin successfully uninstalled.";
        } else {
			return "Plugin failed to uninstall.";
		}
    }

    /**
     * @see Classparser_Plugin::isInstalled
     * @return boolean
     */
	public static function isInstalled(){
		$classname = Classparser_Config::getClassName();

		return Classparser_Install::hasClass($classname);
	}
}

