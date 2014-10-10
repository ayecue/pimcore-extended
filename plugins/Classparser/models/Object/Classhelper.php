<?php 


class Object_Classhelper_Backup extends Object_Concrete {

public $o_classId = 5;
public $o_className = "Classhelper";
public $classtag;
public $description;


/**
* @param array $values
* @return Object_Classhelper
*/
public static function create($values = array()) {
	$object = new static();
	$object->setValues($values);
	return $object;
}

/**
* Get classtag - classtag
* @return string
*/
public function getClasstag () {
	$preValue = $this->preGetValue("classtag"); 
	if($preValue !== null && !Pimcore::inAdmin()) { 
		return $preValue;
	}
	$data = $this->classtag;
	return $data;
}

/**
* Set classtag - classtag
* @param string $classtag
* @return Object_Classhelper
*/
public function setClasstag ($classtag) {
	$this->classtag = $classtag;
	return $this;
}

/**
* Get description - description
* @return string
*/
public function getDescription () {
	$preValue = $this->preGetValue("description"); 
	if($preValue !== null && !Pimcore::inAdmin()) { 
		return $preValue;
	}
	$data = $this->description;
	return $data;
}

/**
* Set description - description
* @param string $description
* @return Object_Classhelper
*/
public function setDescription ($description) {
	$this->description = $description;
	return $this;
}

protected static $_relationFields = array (
);

public $lazyLoadedFields = NULL;

}

