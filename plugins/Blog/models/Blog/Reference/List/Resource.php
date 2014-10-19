<?php

class Blog_Reference_List_Resource extends Pimcore_Model_List_Resource_Abstract {
	public function getTableName () {
        return "blog_references";
    }

    public function load() {

    	$table = $this->getTableName();
    	$query = vsprintf("SELECT * FROM %s",array(
			$table . 
			$this->getCondition() . 
			$this->getOrder() . 
			$this->getOffsetLimit()
		));
        $references = array();
        $referencesData = $this->db->fetchAll($query, $this->model->getConditionVariables());

        foreach ($referencesData as $referenceData) {
            if($referenceData["postObjectId"]) {
                if($reference = Blog_Reference::getByPostObjectId($referenceData["blogDocumentId"],$referenceData["postObjectId"])) {
                    $references[] = $reference;
                }
            }
        }

        $this->model->setReferences($references);
        return $references;
    }

    public function loadPostObjectIdList() {
    	$table = $this->getTableName();
    	$query = vsprintf("SELECT postObjectId FROM %s",array(
			$table . 
			$this->getCondition() . 
			$this->getOrder() . 
			$this->getOffsetLimit()
		));
        $referenceIds = $this->db->fetchCol($query, $this->model->getConditionVariables());
        return $referenceIds;
    }

    public function loadPostDocumentIdList() {
    	$table = $this->getTableName();
    	$query = vsprintf("SELECT postDocumentId FROM %s",array(
			$table . 
			$this->getCondition() . 
			$this->getOrder() . 
			$this->getOffsetLimit()
		));
        $referenceIds = $this->db->fetchCol($query, $this->model->getConditionVariables());
        return $referenceIds;
    }

    public function getCount() {
    	$table = $this->getTableName();
    	$query = vsprintf("SELECT COUNT(*) as amount FROM %s",array(
			$table . 
			$this->getCondition() . 
			$this->getOffsetLimit()
		));
        $amount = (int) $this->db->fetchOne($query, $this->model->getConditionVariables());
        return $amount;
    }

    public function getTotalCount() {
    	$table = $this->getTableName();
    	$query = vsprintf("SELECT COUNT(*) as amount FROM %s",array(
			$table . 
			$this->getCondition()
		));
        $amount = (int) $this->db->fetchOne($query, $this->model->getConditionVariables());
        return $amount;
    }
}