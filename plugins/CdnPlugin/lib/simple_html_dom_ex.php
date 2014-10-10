<?php

include_once(PIMCORE_PATH . "/lib/simple_html_dom.php");

// get html dom from string
function str_get_html_ex($str, $lowercase=true, $forceTagsClosed=false, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT)
{
    $dom = new simple_html_dom_ex(null, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
    if (empty($str))
    {
        $dom->clear();
        return false;
    }
    $dom->load($str, $lowercase, $stripRN);
    return $dom;
}

class simple_html_dom_ex extends simple_html_dom {
	// load html from string
    function load($str, $lowercase=true, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT) {
        global $debugObject;

        // prepare
        $this->prepare($str, $lowercase, $stripRN, $defaultBRText);
        // strip out cdata
        $this->remove_noise("'<!\[CDATA\[(.*?)\]\]>'is", true);
        // Per sourceforge http://sourceforge.net/tracker/?func=detail&aid=2949097&group_id=218559&atid=1044037
        // Script tags removal now preceeds style tag removal.
        // strip out <script> tags
        $this->remove_noise("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is");
        $this->remove_noise("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is");
        // strip out <style> tags
        $this->remove_noise("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is");
        $this->remove_noise("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is");
        // strip out preformatted tags
        $this->remove_noise("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is");
        // strip out server side scripts
        $this->remove_noise("'(<\?)(.*?)(\?>)'s", true);
        // strip smarty scripts
        $this->remove_noise("'(\{\w)(.*?)(\})'s", true);

        // parsing
        while ($this->parse());
        // end
        $this->root->_[HDOM_INFO_END] = $this->cursor;
        $this->parse_charset();
    }
}