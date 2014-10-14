<?php

class Blog_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {
    
	/**
     * @see Blog_Plugin::install
     * @return string
     */
    public static function install(){
        $success = Blog_Install_Classes::installAll();

        if ($success && self::isInstalled()) {
            return "Plugin successfully installed.";
        } else {
            return "Plugin failed to install.";
        }
	}

	/**
     * @see Blog_Plugin::uninstall
     * @return string
     */
	public static function uninstall() {
        $success = Blog_Install_Classes::uninstallAll();

        if ($success && !self::isInstalled()) {
            return "Plugin successfully uninstalled.";
        } else {
            return "Plugin failed to uninstall.";
        }
    }

    /**
     * @see Blog_Plugin::isInstalled
     * @return boolean
     */
	public static function isInstalled(){
		return Blog_Install_Classes::haveAll();
	}
}

