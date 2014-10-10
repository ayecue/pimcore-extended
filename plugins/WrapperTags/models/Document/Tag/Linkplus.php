<?php

class Document_Tag_Linkplus extends Document_Tag_Link {
	public function plainFrontend($html) {
        $url = $this->getHref();

        if (strlen($url) > 0) {
            // add attributes to link
            $attribs = array();
            if (is_array($this->options)) {
                foreach ($this->options as $key => $value) {
                    if (is_string($value) || is_numeric($value)) {
                        $attribs[] = $key . '="' . $value . '"';
                    }
                }
            }
            // add attributes to link
            $allowedAttributes = array("charset", "coords", "hreflang", "name", "rel", "rev", "shape", "target", "accesskey", "class", "dir", "id", "lang", "style", "tabindex", "title", "xml:lang", "onblur", "onclick", "ondblclick", "onfocus", "onmousedown", "onmousemove", "onmouseout", "onmouseover", "onmouseup", "onkeydown", "onkeypress", "onkeyup");
            $defaultAttributes = array();

            if (!is_array($this->options)) {
                $this->options = array();
            }
            if (!is_array($this->data)) {
                $this->data = array();
            }

            $availableAttribs = array_merge($defaultAttributes, $this->data, $this->options);

            foreach ($availableAttribs as $key => $value) {
                if ((is_string($value) || is_numeric($value)) && in_array($key, $allowedAttributes)) {
                    if (!empty($value)) {
                        $attribs[] = $key . '="' . $value . '"';
                    }
                }
            }

            $attribs = array_unique($attribs);

            if (array_key_exists("attributes", $this->data) && !empty($this->data["attributes"])) {
                $attribs[] = $this->data["attributes"];
            }

            return '<a href="' . $url . '" ' . implode(" ", $attribs) . '>' . (isset($html) ? $html : $this->data["text"]). '</a>';
        }

        return "";
    }

	public function getType() {
        return "linkplus";
	}
}