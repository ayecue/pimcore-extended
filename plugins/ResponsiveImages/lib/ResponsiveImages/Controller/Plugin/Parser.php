<?php

class ResponsiveImages_Controller_Plugin_Parser extends Zend_Controller_Plugin_Abstract {
    const CACHE_KEY = "responsiveimage_src";
    const DOM_VERSION = "1.0";
    const DOM_ENCODING = "utf-8";

    private $DOM = NULL;
    private $scriptSource = NULL;
    private $attrSelector = NULL;
    private $parseAttr = NULL;

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

    public function setScriptSource($scriptSource){
        $this->scriptSource = $scriptSource;
        return $this;
    }

    public function getScriptSource(){
        return $this->scriptSource;
    }

    public function setAttrSelector($attrSelector){
        $this->attrSelector = $attrSelector;
        return $this;
    }

    public function getAttrSelector(){
        return $this->attrSelector;
    }

    public function setParseAttr($parseAttr){
        $this->parseAttr = $parseAttr;
        return $this;
    }

    public function getParseAttr(){
        return $this->parseAttr;
    }

    static public function createConfig($config){
        return ResponsiveImages_Polyfill::getByConfig($config);
    }

    static public function createConfigJson($config){
        return ResponsiveImages_Polyfill::getByConfig($config)->toJSON();
    }

    static public function parseConfig($str){
        return ResponsiveImages_Polyfill::getByString($str);
    }

    public function addJavascript(simple_html_dom $html){
        $found = $html->find("head");

        if (isset($found) && !empty($found)) {
            foreach ($found as $head) {
                $this->initDOM();
                $scriptElement = $this->getDOM()->createElement("script");
                $scriptElement->setAttribute("type","text/javascript");
                $scriptElement->setAttribute("src",$this->getScriptSource());
                $this->getDOM()->appendChild($scriptElement);
                $head->innertext = $this->getDOM()->saveHTML() . $head->innertext;

                return TRUE;
            }
        }
    }

    public function convertImage(simple_html_dom_node $element){
        //create new dom
        $this->initDOM();

        //create picture polyfill
        $pictureElement = $this->getDOM()->createElement("picture");
        $pictureElement->setAttribute("data-src",$element->src);

        //get element config
        $attr = $this->getParseAttr();
        $val = $element->getAttribute($attr);

        if (!isset($val)) {
            return;
        }

        $config = ResponsiveImages_Helper::parseConfig($val);
        $items = $config->getItems();
        $element->removeAttribute($attr);

        if (empty($items)) {
            return;
        }

        //create path object
        $path = new ResponsiveImages_Path($element->src);
        
        if (!$path->hasAsset()) {
            return;
        }

        foreach ($items as $item) {
            //get values
            $percent = $item->getPercent();
            $minWidth = $item->getMinWidth();
            $maxWidth = $item->getMaxWidth();
            $width = $item->getWidth();

            //create source element
            $sourceElement = $this->getDOM()->createElement("source");
            $sourceElement->setAttribute("src",$path->getAltHttpUrl($percent));

            //set min and max width
            if (isset($minWidth) && !isset($maxWidth)) {
                $sourceElement->setAttribute("media","(min-width: $minWidth)");
            } elseif (!isset($minWidth) && isset($maxWidth)) {
                $sourceElement->setAttribute("media","(max-width: $maxWidth)");
            } elseif (isset($minWidth) && isset($maxWidth)) {
                $sourceElement->setAttribute("media","(min-width: $minWidth) and (max-width: $maxWidth)");
            }

            //set data width
            if (isset($width)) {
                $sourceElement->setAttribute("data-width",$width);
            }

            //append to picture polyfill
            $pictureElement->appendChild($sourceElement);
        }

        //create image
        $imageElement = $this->getDOM()->createElement("img");

        foreach ($element->attr as $prop => $value) {
            $imageElement->setAttribute($prop,$value);
        }

        //get first element of sources
        $firstSource = $pictureElement->getElementsByTagName("source")->item(0);
        $imageElement->setAttribute("src",$firstSource->getAttribute("src"));

        //append everything to root
        $pictureElement->appendChild($imageElement);
        $this->getDOM()->appendChild($pictureElement);

        //replace image with polyfill
        $element->outertext = $this->getDOM()->saveHTML();
    }

    public function dispatchLoopShutdown() {
        if(!Pimcore_Tool::isHtmlResponse($this->getResponse())) {
            return;
        }
            
        include_once(PIMCORE_PATH . "/lib/simple_html_dom.php");
        
        $body = $this->getResponse()->getBody();
        
        $html = str_get_html($body);

        if($html) {
            $elements = $html->find("img[{$this->getAttrSelector()}]");

echo "<!--";
var_dump("img[{$this->getAttrSelector()}]");
echo "-->";

            if (isset($elements) && !empty($elements)) {
                foreach ($elements as $element) {
                    if ($element->tag == "img") {
                        $this->convertImage($element);
                    }
                }

                $this->addJavascript($html);

                $body = $html->save();

                $html->clear();
                unset($html);

                $this->getResponse()->setBody($body);

                // save storage
                Pimcore_Model_Cache::save($this->cachedItems, self::CACHE_KEY, array(), 3600);
            }
        }
    }
}

