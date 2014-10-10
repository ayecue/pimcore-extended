<?php
include_once("simple_html_dom.php");

class Document_Tag_Wysiwygplus extends Document_Tag_Wysiwyg {
	public function frontend() {
        if($this->getView()->editmode) {
            echo '<script type="text/javascript" src="/website/static/js/document/tag/wysiwygplus.js"></script>';
        }

        parent::frontend();
    }

	//your modifications here
	public function getType() {
        return "wysiwygplus";
    }
}