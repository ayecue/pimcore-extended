<?php

class ResponsiveImages_Polyfill_Config {
	private $percent;
	private $minWidth;
	private $maxWidth;
	private $width;

	public function setPercent($percent){
        $this->percent = $percent;
        return $this;
    }

    public function getPercent(){
        return $this->percent;
    }

    public function setMinWidth($minWidth){
        $this->minWidth = $minWidth;
        return $this;
    }

    public function getMinWidth(){
        return $this->minWidth;
    }

    public function setMaxWidth($maxWidth){
        $this->maxWidth = $maxWidth;
        return $this;
    }

    public function getMaxWidth(){
        return $this->maxWidth;
    }

    public function setWidth($width){
        $this->width = $width;
        return $this;
    }

    public function getWidth(){
        return $this->width;
    }

    public function insertItem($item){
        if (isset($item["percent"])) {
            $this->setPercent($item["percent"]);
        }
        if (isset($item["minWidth"])) {
            $this->setMinWidth($item["minWidth"]);
        }
        if (isset($item["maxWidth"])) {
            $this->setMaxWidth($item["maxWidth"]);
        }
        if (isset($item["width"])) {
            $this->setWidth($item["width"]);
        }
    }

    public function toArray(){
        $var = array();

        if (isset($this->percent)) {
            $var["percent"] = $this->percent;
        }
        if (isset($this->minWidth)) {
            $var["minWidth"] = $this->minWidth;
        }
        if (isset($this->maxWidth)) {
            $var["maxWidth"] = $this->maxWidth;
        }
        if (isset($this->width)) {
            $var["width"] = $this->width;
        }

        return $var;
    }
}