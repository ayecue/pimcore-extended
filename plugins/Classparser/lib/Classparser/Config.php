<?php

class Classparser_Config {
	
	/**
     * @see Classparser_Config::getClassJsonPath
     * @return string
     */
	static public function getClassJsonPath() {
        return PIMCORE_PLUGINS_PATH . "/Classparser/install/class_Classhelper.json";
    }

    /**
     * @see Classparser_Config::getClassName
     * @return string
     */
    static public function getClassName(){
        return "Classhelper";
    }

    /**
     * @see Classparser_Config::getClassTypeString
     * @return string
     */
    static public function getClassTypeString(){
    	return "Object_" . self::getClassName();
    }

    /**
     * @see Classparser_Config::getClassType
     * @return Object_Classhelper
     */
	static public function getClassType(){
		return Object_Classhelper;
	}

	/**
     * @see Classparser_Config::getClassJsonPath
     * @return string
     */
    static public function getClassTagProperty(){
        return "classtag";
    }
    
}

