<?php

class Document_Tag_Classparser extends Document_Tag_Multihref {
    /**
     * @see Document_Tag_Classparser::getClassName
     * @return string
     */
    static public function getClassName(){
        return Classparser_Config::getClassName();
    }

    /**
     * @see Document_Tag_Classparser::getClassTypeString
     * @return string
     */
	static public function getClassTypeString(){
		return Classparser_Config::getClassTypeString();
	}

    /**
     * @see Document_Tag_Classparser::getClassType
     * @return Object_Class
     */
	static public function getClassType(){
		return Classparser_Config::getClassType();
	}

    /**
     * @see Document_Tag_Classparser::getClassTagProperty
     * @return string
     */
    static public function getClassTagProperty(){
        return Classparser_Config::getClassTagProperty();
    }

    /**
     * @see Document_Tag_Classparser::getType
     * @return string
     */
    public function getType() {
        return "classparser";
    }

    /**
     * @see Document_Tag_Classparser::admin
     * @return string
     */
    public function admin() {
        $options = $this->getOptions();

        if (!isset($options) || empty($options)) {
            $options = array();
        }

        $options = array_merge(
            $options,
            array(
                'title' => self::getClassName(),
                'allowedTypes' => array(
                    self::getClassTypeString()
                )
            )
        );

        $this->setOptions($options);

        return parent::admin();
    }

    /**
     * @see Document_Tag_Classparser::getClass
     * @param boolean $doPrintOutput
     * @return string
     */
    public function getClass($doPrintOutput = FALSE){
        $classTypeString = self::getClassTypeString();

    	if (!class_exists($classTypeString)) {
            Logger::error("Class $classTypeString doesn't exist.");

    		return;
    	}

        $classType = self::getClassType();
        $classTagProperty = self::getClassTagProperty();
        $getClassTagPropertyMethod = "get" . ucfirst($classTagProperty);
    	$classStack = array();

    	foreach ($this as $class) {
    		if ($class instanceof $classType && method_exists($class,$getClassTagPropertyMethod)) {
    			$value = $class->$getClassTagPropertyMethod();

    			if (!empty($value)) {
    				$classStack[] = $value;
    			}
    		} else {
                Logger::error("Invalid class " . get_class($class) . ".");
            }
    	}

    	$output = implode(" ",$classStack);

        if ($doPrintOutput) {
            echo $output;
        }

    	return $output;
    }
}