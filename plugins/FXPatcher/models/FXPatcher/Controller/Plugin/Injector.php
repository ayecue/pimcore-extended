<?php

class FXPatcher_Controller_Plugin_Injector extends Zend_Controller_Plugin_Abstract {
    const DOM_VERSION = "1.0";
    const DOM_ENCODING = "utf-8";

    private $DOM = NULL;

    public function setDOM($handle){
        $this->DOM = $handle;
        return $this;
    }

    public function getDOM(){
        return $this->DOM;
    }

    public function initDOM(){
        $this->setDOM(new DOMDocument(self::DOM_VERSION,self::DOM_ENCODING));
    }

    public function getJavascriptPath(){
        return "/plugins/FXPatcher/static/js/";
    }

    public function getPatcher(){
        return array(
            $this->getJavascriptPath() . "patcher.js",
            $this->getJavascriptPath() . "document/tags/areablock/patch.js"
        );
    }

    public function addJavascript(simple_html_dom $html,$src){
        $found = $html->find("head");

        if (isset($found) && !empty($found)) {
            foreach ($found as $head) {
                $this->initDOM();
                $scriptElement = $this->getDOM()->createElement("script");
                $scriptElement->setAttribute("type","text/javascript");
                $scriptElement->setAttribute("src",$src);
                $this->getDOM()->appendChild($scriptElement);
                $head->innertext = $head->innertext . $this->getDOM()->saveHTML();

                return TRUE;
            }
        }
    }

    public function dispatchLoopShutdown() {
        if(!Pimcore_Tool::isHtmlResponse($this->getResponse())) {
            return;
        }

        $request = $this->getRequest();
        
        if ($request->getParam('pimcore_editmode') && $request->getParam('module') == 'website') {
            $body = $this->getResponse()->getBody();
            $html = str_get_html($body);
            $patcher = $this->getPatcher();

            foreach ($patcher as $path) {
                $this->addJavascript($html,$path);
            }

            $body = $html->save();

            $html->clear();
            unset($html);

            $this->getResponse()->setBody($body);
        }
    }
}

