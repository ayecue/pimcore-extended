<?php

class Blog_Install_Abstract {

    public $dbConfig = NULL;
    public $db = NULL;

    /**
     * @see Blog_Install_Abstract::createClassByJson
     * @param string $classname, string $pathToJson
     * @return boolean
     */
    public function createClassByJson($classname,$pathToJson) {
        $json = file_get_contents($pathToJson);

        try {
            $class = Object_Class::create();
            $class->setName($classname);
            $class->save();
        } catch (Exception $e) {
            return false;
        }

        try {
            Object_Class_Service::importClassDefinitionFromJson($class,$json);
        } catch (Exception $e) {
            $class->delete();

            return false;
        }

        return true;
    }

    /**
     * @see Blog_Install_Abstract::removeClass
     * @param string $classname
     * @return boolean
     */
    public function removeClass($classname) {
         try {
            $class = Object_Class::getByName($classname);

            if ($class) {
                $class->delete();
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @see Blog_Install_Abstract::hasClass
     * @param string $classname
     * @return boolean
     */
    public function hasClass($classname) {
        return class_exists("Object_$classname") && Object_Class::getByName($classname);
    }

    /**
     * @see Blog_Install_Abstract::setDatabaseConfig
     * @param string $classname
     * @return boolean
     */
    public function setDatabaseConfig(){
        if (empty($this->dbConfig)) {
            $config = Pimcore_Config::getSystemConfig()->toArray();
            $this->dbConfig = $config["database"];
        }
        return $this;
    }

    /**
     * @see Blog_Install_Abstract::getDatabaseConfig
     * @param string $classname
     * @return boolean
     */
    public function getDatabaseConfig(){
        $this->setDatabaseConfig();
        return $this->dbConfig;
    }

    /**
     * @see Blog_Install_Abstract::setDatabase
     * @param string $classname
     * @return boolean
     */
    public function setDatabase(){
        $dbConfig = $this->getDatabaseConfig();
        if (empty($this->db)) {
            $this->db = Zend_Db::factory($dbConfig['adapter'],array(
                'host' => $dbConfig["params"]['host'],
                'username' => $dbConfig["params"]['username'],
                'password' => $dbConfig["params"]['password'],
                'dbname' => $dbConfig["params"]['dbname'],
                "port" => $dbConfig["params"]['port']
            ));
            $this->db->getConnection();
        }
        return $this;
    }

    /**
     * @see Blog_Install_Abstract::getDatabase
     * @param string $classname
     * @return boolean
     */
    public function getDatabase(){
        $this->setDatabase();
        return $this->db;
    }

    /**
     * @see Blog_Install_Abstract::insertSQL
     * @param string $classname
     * @return boolean
     */
    public function insertSQLFileToDatabase($sqlFilePath) {
        try {
            $db = $this->getDatabase();

            $mysqlInstallScript = file_get_contents($sqlFilePath);

            // remove comments in SQL script
            $mysqlInstallScript = preg_replace("/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/","",$mysqlInstallScript);

            // get every command as single part
            $mysqlInstallScripts = explode(";",$mysqlInstallScript);

            // execute every script with a separate call, otherwise this will end in a PDO_Exception "unbufferd queries, ..." seems to be a PDO bug after some googling
            foreach ($mysqlInstallScripts as $m) {
                $sql = trim($m);
                if(strlen($sql) > 0) {
                    $sql .= ";";
                    $db->query($m);
                }
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @see Blog_Install_Abstract::hasTableInDatabase
     * @param string $classname
     * @return boolean
     */
    public function hasTableInDatabase($table) {
        try {
            $db = $this->getDatabase();

            $result = $db->describeTable($table);
        } catch (Exception $e) {
            return false;
        }

        return !empty($result) && is_array($result);
    }
}