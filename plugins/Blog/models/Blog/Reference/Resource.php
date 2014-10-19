<?php

class Blog_Reference_Resource extends Pimcore_Model_Resource_Abstract {
	public function getTableName () {
        return "blog_references";
    }

	public function getByPostObjectId($blogDocumentId,$postObjectId){
		$table = $this->getTableName();
		$query = vsprintf("SELECT * FROM %s WHERE blogDocumentId = %s AND postObjectId = %s",array(
			$table,
			$blogDocumentId,
			$postObjectId
		));
		$data = $this->db->fetchRow($query);

		if ($data) {
            $this->assignVariablesToModel($data);
        } else {
            throw new Exception(vsprintf("reference with blogDocumentId: %s and postObjectId: %s doesn't exist",array(
            	$blogDocumentId,
				$postObjectId
            )));
        }
	}

	public function getByPostDocumentId($postDocumentId){
		$table = $this->getTableName();
		$query = vsprintf("SELECT * FROM %s WHERE postDocumentId = %s",array(
			$table,
			$postDocumentId
		));
		$data = $this->db->fetchRow($query);

		if ($data) {
            $this->assignVariablesToModel($data);
        } else {
            throw new Exception(vsprintf("reference with postDocumentId: %s doesn't exist",array(
				$postDocumentId
            )));
        }
	}

	public function create(){
        try {
        	$table = $this->getTableName();
            $this->db->insert($table, array(
                "blogDocumentId" => $this->model->getBlogDocumentId(),
                "blogComponentName" => $this->model->getBlogComponentName(),
                "postObjectId" => $this->model->getPostObjectId(),
                "postDocumentId" => $this->model->getPostDocumentId()
            ));

            $date = time();
            $this->model->setId($this->db->lastInsertId());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(){
        $data = $this->model->getData();

        if (is_array($data) || is_object($data)) {
            $data = Pimcore_Tool_Serialize::serialize($data);
        }

        $table = $this->getTableName();

        $this->db->insertOrUpdate($table, array(
            "blogDocumentId" => $this->model->getBlogDocumentId(),
            "blogComponentName" => $this->model->getBlogComponentName(),
            "postObjectId" => $this->model->getPostObjectId(),
            "postDocumentId" => $this->model->getPostDocumentId()
        ));
    }

    public function delete(){
        try {
        	$table = $this->getTableName();
        	$query = vsprintf("blogDocumentId = %s AND postObjectId = %s",array(
				$this->model->getBlogDocumentId(),
				$this->model->getPostObjectId()
			));
            $this->db->delete($table, $query);
        } catch (Exception $e) {
            throw $e;
        }
    }
}