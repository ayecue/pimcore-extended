<?php

class WrapperTags_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {
    public static function install(){
		return "Are you sure Plugin successfully installed.";
	}
	
	public static function uninstall(){
		return "Are you sure Plugin successfully uninstalled.";
	}

	public static function isInstalled(){
		return true;
	}
}

