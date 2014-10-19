<?php

class Document_Tag_Blog_Resource extends Document_Tag_Resource {



    public function save() {
    	$data = $this->model->getDataForResource();
    	$documentId = $this->model->getDocumentId();
    	$blogPostIds = $data["postIds"];
        $existingReferences = Blog_Reference::getPostDocumentList(array(
            "blogDocumentId" => $documentId
        ));

        if (!empty($blogPostIds)) {
        	foreach ($blogPostIds as $blogPostId) {
                if (!in_array($blogPostId["id"],$existingReferences)) {
                    Blog_Service::createEntryById($this->model,$blogPostId["id"]);
                }
        	}
        }

        return parent::save();
    }
    
    public function delete () {


    	return parent::delete();
    }

}
