<?php

class Blog_Install_Classes extends Blog_Install {

    /**
     * @see Blog_Install_Classes::installAll
     * @return boolean
     */
    static public function installAll() {
        return self::installBlogPost() && self::installBlogCategory();
    }

    /**
     * @see Blog_Install_Classes::installBlogPost
     * @return boolean
     */
    static public function installBlogPost() {
    	$classname = Blog_Config::getBlogPostClassName();
        $pathToJson = Blog_Config::getBlogPostClassJsonPath();
        $success = Blog_Install::createClassByJson($classname,$pathToJson);

        return $succes;
    }

    /**
     * @see Blog_Install_Classes::installBlogCategory
     * @return boolean
     */
    static public function installBlogCategory() {
    	$classname = Blog_Config::getBlogCategoryClassName();
        $pathToJson = Blog_Config::getBlogCategoryClassJsonPath();
        $success = Blog_Install::createClassByJson($classname,$pathToJson);

        return $succes;
    }

    /**
     * @see Blog_Install_Classes::uninstallAll
     * @return boolean
     */
    static public function uninstallAll() {
        return self::uninstallBlogPost() && self::uninstallBlogCategory();
    }

    /**
     * @see Blog_Install_Classes::uninstallBlogPost
     * @return boolean
     */
    static public function uninstallBlogPost() {
    	$classname = Blog_Config::getBlogPostClassName();
        $success = Blog_Install::removeClass($classname);

        return $succes;
    }

    /**
     * @see Blog_Install_Classes::uninstallBlogCategory
     * @return boolean
     */
    static public function uninstallBlogCategory() {
    	$classname = Blog_Config::getBlogCategoryClassName();
        $success = Blog_Install::removeClass($classname);

        return $succes;
    }

    /**
     * @see Blog_Install_Classes::haveAll
     * @return boolean
     */
    static public function haveAll() {
        return self::hasBlogPost() && self::hasBlogCategory();
    }

    /**
     * @see Blog_Install_Classes::hasBlogPost
     * @return boolean
     */
    static public function hasBlogPost() {
    	$classname = Blog_Config::getBlogPostClassName();
        $success = Blog_Install::hasClass($classname);

        return $success;
    }

    /**
     * @see Blog_Install_Classes::hasBlogCategory
     * @return boolean
     */
    static public function hasBlogCategory() {
    	$classname = Blog_Config::getBlogCategoryClassName();
        $success = Blog_Install::hasClass($classname);

        return $success;
    }

}