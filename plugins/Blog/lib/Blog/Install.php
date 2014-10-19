<?php

class Blog_Install {

    public $classInstaller = NULL;
    public $sqlInstaller = NULL;

    public function __construct() {
        $this->classInstaller = new Blog_Install_Classes();
        $this->sqlInstaller = new Blog_Install_SQL();
    }

    public function install(){
        return 
            $this->classInstaller->installAll() &&
            $this->sqlInstaller->installAll();
    }

    public function uninstall(){
        return 
            $this->classInstaller->uninstallAll() &&
            $this->sqlInstaller->uninstallAll();
    }

    public function installed(){
        return 
            $this->classInstaller->haveAll() &&
            $this->sqlInstaller->haveAll();
    }
}