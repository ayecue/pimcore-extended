<?php

class Blog_Config {
	
	/**
     * @see Blog_Config::getBlogPostClassJsonPath
     * @return string
     */
	static public function getBlogPostClassJsonPath() {
        return PIMCORE_PLUGINS_PATH . "/Blog/install/class_BlogPost.json";
    }

    /**
     * @see Blog_Config::getBlogPostClassName
     * @return string
     */
    static public function getBlogPostClassName() {
        return "BlogPost";
    }
    
    /**
     * @see Blog_Config::getBlogCategoryClassJsonPath
     * @return string
     */
    static public function getBlogCategoryClassJsonPath() {
        return PIMCORE_PLUGINS_PATH . "/Blog/install/class_BlogCategory.json";
    }

    /**
     * @see Blog_Config::getBlogCategoryClassName
     * @return string
     */
    static public function getBlogCategoryClassName() {
        return "BlogCategory";
    }

}

