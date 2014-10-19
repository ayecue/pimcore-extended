<?php

class Blog_Service {
    
    static public function getEntry(Pimcore_View $view) {
        $documentId = $view->document->getId();
        $post = Blog_Post::getByPostDocumentId($documentId);
        
        return $post;
    }

    static public function createEntryById(Document_Tag_Blog $blog,$postId) {
    	$object = Object_BlogPost::getById($postId);

    	return self::createEntry($blog,$object);
    }

    static public function createEntry(Document_Tag_Blog $blog,Object_BlogPost $post) {
    	$parentId = $blog->getDocumentId();

    	try {
    		return Blog_Reference::getByPostObjectId($parentId,$post->getId());
    	} catch (Exception $e) {
    		Logger::log('Cannot find blog post document with blog post object id', $post->getId());
    	}

    	$module = $blog->getPostModule();
    	$controller = $blog->getPostController();
    	$action = $blog->getPostAction();
    	$template = $blog->getPostTemplate();
    	$urlTitle = $post->getUrlTitle();
    	$key = Pimcore_File::getValidFilename(empty($urlTitle) ? $post->getKey() : $urlTitle);

    	$page = new Document_Page();

    	$page->setParentId($parentId);
    	$page->setModule($module);
    	$page->setController($controller);
    	$page->setAction($action);
    	$page->setTemplate($template);
    	$page->setKey($key);

    	try {
    		$page->save();

    		self::createReference($blog,$post,$page);
    	} catch (Exception $e) {

    	}

    	return $page;
    }

    static public function createReference(Document_Tag_Blog $blog,Object_BlogPost $post,Document_Page $page){
    	$reference = new Blog_Reference();
    	$parentId = $blog->getDocumentId();
    	$componentName = $blog->getName();
    	$postId = $post->getId();
    	$pageId = $page->getId();

    	$reference->setBlogDocumentId($parentId);
    	$reference->setBlogComponentName($componentName);
    	$reference->setPostObjectId($postId);
    	$reference->setPostDocumentId($pageId);

    	$reference->getResource()->update();

    	return $reference;
    }
}