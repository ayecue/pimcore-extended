<?php

abstract class ResponsiveImages_Config extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {
	protected static function getWebsiteConfigPath(){
		return PIMCORE_CONFIGURATION_DIRECTORY . '/website.xml';
	}

	protected static function getSettingsConfigPath() {
        return PIMCORE_PLUGINS_PATH . '/ResponsiveImages/install/website.xml';                
	}

	protected static function saveConfig($data,$filename) {
    	if (class_exists('WebsiteSetting')) {
            foreach ($data as $key => $entry) {
                $new = new WebsiteSetting();

                $new->setName($key);
                $new->setType($entry['type']);
                $new->setData($entry['data']);
                $new->setSiteId($entry['siteId']);

                $new->save();
            }
        } else {
            $config = new Zend_Config($data, true);
            $writer = new Zend_Config_Writer_Xml(array(
                "config" => $config,
                "filename" => self::getWebsiteConfigPath()
            ));

            $writer->write();
        }
    }

    protected static function loadFileConfig($configFile){
        // file doesn't exist => send empty array to frontend
        if (!is_file($configFile)) {
            return array();
        }

        $rawConfig = new Zend_Config_Xml($configFile);

        return $rawConfig->toArray();
    }

    protected static function diff($a,$b) {
    	$arr = array();

    	foreach ($a as $key => $value) {
    		if (!array_key_exists($key,$b)) {
    			$arr[$key] = $value;
    		}
    	}

    	return $arr;
    }

    public static function install() {
		$data = array();

        //we need to write the website settings to the XML file manually because currently Pimcore has no API for this!
        try {
            $websiteData = Pimcore_Config::getWebsiteConfig();
            $pluginData = self::loadFileConfig(self::getSettingsConfigPath());
            $diff = self::diff($pluginData,$websiteData->toArray());

            foreach($diff as $key => $value) {
                $data[$key] = $value;
            }
        } catch(Exception $e) {
            $data = self::loadFileConfig(self::getSettingsConfigPath());
        }

        self::saveConfig($data);

        //remove old settings from cache, forces a reload from the file system
        Pimcore_Model_Cache::clearTags(array('output', 'system', 'website_config'));
    }

	public static function isInstalled() {
		// if this plugin exists it is installed? hopefully?
        try {
            $websiteData = Pimcore_Config::getWebsiteConfig();
            $pluginData = self::loadFileConfig(self::getSettingsConfigPath());
            $diff = self::diff($pluginData,$websiteData->toArray());

            if (!empty($diff)) {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }

        return true;
	}

	private $_configuration = NULL;

	public function setConfiguration($data){
		if (is_array($data)) {
			$this->_configuration = (object) $data;
		} else if (is_object($data)) {
			$this->_configuration = $data;
		}
	}

	public function updateConfiguration(){
		$config = Pimcore_Config::getWebsiteConfig();
        $this->setConfiguration($config->toArray());
	}

	public function getConfiguration(){
		if ($this->_configuration == NULL) {
			$this->updateConfiguration();
		}

		return $this->_configuration;
	}
}