<?php

class Classparser_Install {

    /**
     * @see Classparser_Install::createClassByJson
     * @param string $classname, string $pathToJson
     * @return boolean
     */
    static public function createClassByJson($classname,$pathToJson) {
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
     * @see Classparser_Install::removeClass
     * @param string $classname
     * @return boolean
     */
    static public function removeClass($classname) {
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
     * @see Classparser_Install::hasClass
     * @param string $classname
     * @return boolean
     */
    static public function hasClass($classname) {
        return class_exists("Object_$classname") && Object_Class::getByName($classname);
    }

}