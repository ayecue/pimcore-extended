<?php

class Blog_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {
    
	/**
     * @see Blog_Plugin::install
     * @return string
     */
    public static function install(){
		return "Plugin successfully installed.";
	}

	/**
     * @see Blog_Plugin::uninstall
     * @return string
     */
	public static function uninstall() {
        return "Plugin successfully uninstalled.";
    }

    /**
     * @see Blog_Plugin::isInstalled
     * @return boolean
     */
	public static function isInstalled(){
		return true;
	}
}

