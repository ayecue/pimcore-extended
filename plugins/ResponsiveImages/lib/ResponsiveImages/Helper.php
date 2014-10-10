<?php

class ResponsiveImages_Helper {
	static public function createConfig($config){
        return ResponsiveImages_Controller_Plugin_Parser::createConfig($config);
    }

    static public function createConfigJson($config){
        return urlencode(ResponsiveImages_Controller_Plugin_Parser::createConfigJson($config));
    }

    static public function parseConfig($str){
        return ResponsiveImages_Controller_Plugin_Parser::parseConfig(urldecode($str));
    }
}