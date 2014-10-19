<?php

class Blog_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {
    
    public static $installer = NULL;

    public static function setInstaller(){
        if (empty(self::$installer)) {
            self::$installer = new Blog_Install();
        }
        return self;
    }

    public static function getInstaller(){
        self::setInstaller();
        return self::$installer;
    }

	/**
     * @see Blog_Plugin::install
     * @return string
     */
    public static function install(){
        $installer = self::getInstaller();
        $success = $installer->install();

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
        $installer = self::getInstaller();
        $success = $installer->uninstall();

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
        $installer = self::getInstaller();

		return $installer->installed();
	}
}

