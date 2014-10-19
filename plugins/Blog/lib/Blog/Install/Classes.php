<?php

class Blog_Install_Classes extends Blog_Install_Abstract {

    /**
     * @see Blog_Install_Classes::installAll
     * @return boolean
     */
    public function installAll() {
        return self::haveAll() || (self::installBlogPost() && self::installBlogCategory());
    }

    /**
     * @see Blog_Install_Classes::installBlogPost
     * @return boolean
     */
    public function installBlogPost() {
    	$classname = Blog_Config::getBlogPostClassName();
        $pathToJson = Blog_Config::getBlogPostClassJsonPath();
        $success = $this->createClassByJson($classname,$pathToJson);

        return $succes;
    }

    /**
     * @see Blog_Install_Classes::installBlogCategory
     * @return boolean
     */
    public function installBlogCategory() {
    	$classname = Blog_Config::getBlogCategoryClassName();
        $pathToJson = Blog_Config::getBlogCategoryClassJsonPath();
        $success = $this->createClassByJson($classname,$pathToJson);

        return $succes;
    }

    /**
     * @see Blog_Install_Classes::uninstallAll
     * @return boolean
     */
    public function uninstallAll() {
        return self::haveAll() && self::uninstallBlogPost() && self::uninstallBlogCategory();
    }

    /**
     * @see Blog_Install_Classes::uninstallBlogPost
     * @return boolean
     */
    public function uninstallBlogPost() {
    	$classname = Blog_Config::getBlogPostClassName();
        $success = $this->removeClass($classname);

        return $succes;
    }

    /**
     * @see Blog_Install_Classes::uninstallBlogCategory
     * @return boolean
     */
    public function uninstallBlogCategory() {
    	$classname = Blog_Config::getBlogCategoryClassName();
        $success = $this->removeClass($classname);

        return $succes;
    }

    /**
     * @see Blog_Install_Classes::haveAll
     * @return boolean
     */
    public function haveAll() {
        return self::hasBlogPost() && self::hasBlogCategory();
    }

    /**
     * @see Blog_Install_Classes::hasBlogPost
     * @return boolean
     */
    public function hasBlogPost() {
    	$classname = Blog_Config::getBlogPostClassName();
        $success = $this->hasClass($classname);

        return $success;
    }

    /**
     * @see Blog_Install_Classes::hasBlogCategory
     * @return boolean
     */
    public function hasBlogCategory() {
    	$classname = Blog_Config::getBlogCategoryClassName();
        $success = $this->hasClass($classname);

        return $success;
    }

}