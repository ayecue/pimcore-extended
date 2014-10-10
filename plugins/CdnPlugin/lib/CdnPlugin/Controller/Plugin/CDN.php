<?php

class CdnPlugin_Controller_Plugin_CDN extends Pimcore_Controller_Plugin_CDN {

    public function dispatchLoopShutdown() {
        
        if(!Pimcore_Tool::isHtmlResponse($this->getResponse())) {
            return;
        }
        
        if ($this->enabled) {
            
            include_once("simple_html_dom_ex.php");
            
            $body = $this->getResponse()->getBody();
            
            $html = str_get_html_ex($body);
            if($html) {
                $elements = $html->find("link[rel=stylesheet], link[href], img, script[src], source");

                foreach ($elements as $element) {
                    if($element->tag == "link") {
                        if($this->pathMatch($element->href)) {
                            $element->href = $this->rewritePath($element->href);
                        }
                    }
                    else if ($element->tag == "img") {
                        if($this->pathMatch($element->src)) {
                            $element->src = $this->rewritePath($element->src);
                        }
                    }
                    else if ($element->tag == "script") {
                        if($this->pathMatch($element->src)) {
                            $element->src = $this->rewritePath($element->src);
                        }
                    } else if ($element->tag == "source") {
                        if($this->pathMatch($element->src)) {
                            $element->src = $this->rewritePath($element->src);
                        }
                    }
                }

                $body = $html->save();

                $html->clear();
                unset($html);

                $this->getResponse()->setBody($body);

                // save storage
                Pimcore_Model_Cache::save($this->cachedItems, self::cacheKey, array(), 3600);
            }
        }
    }
}

