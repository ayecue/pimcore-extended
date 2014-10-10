<?php

class Less_Controller_Plugin_Parser extends Pimcore_Controller_Plugin_Less {

    static public function getLessJSPath() {
        return "/plugins/Less/static/js/lib/less-1.7.5.min.js";
    }

    static public function getScriptTag() {
        return "\n" .
                '<script type="text/javascript">' .
                    'var less = {"env": "development"};' . 
                '</script>' . 
                '<script type="text/javascript" src="' . 
                    self::getLessJSPath() . 
                '"></script>' .
                "\n";
    }

    public function routeStartup(Zend_Controller_Request_Abstract $request) {

        $this->conf = Pimcore_Config::getSystemConfig();

        if($request->getParam('disable_less_compiler') || $_COOKIE["disable_less_compiler"]){
            return $this->disable();
        }

    }

    public function dispatchLoopShutdown() {

        parent::dispatchLoopShutdown();

    }

    protected function frontend () {

        $body = $this->getResponse()->getBody();

        $body = Less_Tool_Helper::processHtml($body);

        $this->getResponse()->setBody($body);
    }

    protected function editmode () {
        $body = $this->getResponse()->getBody();

        $html = str_get_html($body);

        if($html) {
            $head = $html->find("head",0);
            if($head) {
                $head->innertext = $head->innertext . self::getScriptTag();

                $body = $html->save();
                $this->getResponse()->setBody($body);
            }

            $html->clear();
            unset($html);
        }
    }

}

