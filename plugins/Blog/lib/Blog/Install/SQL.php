<?php

class Blog_Install_SQL extends Blog_Install_Abstract {
    /**
     * @see Blog_Install_SQL::installAll
     * @return boolean
     */
    public function installAll() {
        $this->uninstallAll();
        $sqlFilePath = Blog_Config::getInstallSQLFilePath();
        $success = $this->insertSQLFileToDatabase($sqlFilePath);

        return $success;
    }

    /**
     * @see Blog_Install_SQL::uninstallAll
     * @return boolean
     */
    public function uninstallAll() {
        $sqlFilePath = Blog_Config::getUninstallSQLFilePath();
        $success = $this->insertSQLFileToDatabase($sqlFilePath);

        return $success;
    }

    /**
     * @see Blog_Install_SQL::haveAll
     * @return boolean
     */
    public function haveAll() {
        $tables = Blog_Config::getDatabaseTables();

        foreach ($tables as $table) {
            if (!$this->hasTableInDatabase($table)) {
                return false;
            }
        }

        return true;
    }
}