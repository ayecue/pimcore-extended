<?php

class ResponsiveImages_Path {
    const ALLOW_DIR_REPLACE = FALSE;

	private $parsedUrl = NULL;
	private $dirname = NULL;
    private $filename = NULL;
    private $extension = NULL;
    private $asset = NULL;

	public function setParsedUrl($parsedUrl){
        $this->parsedUrl = $parsedUrl;
        return $this;
    }

    public function getParsedUrl(){
        return $this->parsedUrl;
    }

    public function getParsedUrlProperty($prop){
        return $this->parsedUrl[$prop];
    }

    public function setDirname($dirname){
        if (self::ALLOW_DIR_REPLACE) {
            $this->dirname = preg_replace("/^\/?([a-z0-9]+\/)*assets/i","/assets", $dirname);
        } else {
            $this->dirname = $dirname;
        }

        return $this;
    }

    public function getDirname(){
        return $this->dirname;
    }

    public function setFilename($filename){
        $this->filename = $filename;
        return $this;
    }

    public function getFilename(){
        return $this->filename;
    }

    public function setExtension($extension){
        $this->extension = $extension;
        return $this;
    }

    public function getExtension(){
        return $this->extension;
    }

    public function setAsset($asset){
        $this->asset = $asset;
        return $asset;
    }

    public function getAsset(){
        return $this->asset;
    }

    public function hasAsset(){
        return isset($this->asset);
    }

    public function getPath(){
    	return implode("",array(
    		$this->getDirname(),
    		"/",
    		$this->getFilename(),
    		".",
    		$this->getExtension()
    	));
    }

    public function getAltParsedUrl($percent){
    	$altParsedUrl = array_merge(array(),$this->getParsedUrl());
		$percentFloat = floatval($percent);
		$width = $this->getAsset()->getWidth() * $percentFloat;
		$height = $this->getAsset()->getHeight() * $percentFloat;
		$thumbnail = $this->getAsset()->getThumbnail(array(
			"width" => $width,
			"height" => $height
		));
		$altParsedUrl["path"] = $thumbnail->getPath();

		return $altParsedUrl;
    }

    public function getAltHttpUrl($percent){
    	$parsedUrl = $this->getAltParsedUrl($percent);

    	if (!isset($parsedUrl)) {
    		return;
    	}

    	return (isset($parsedUrl["scheme"])    ? $parsedUrl["scheme"] . "://" : "") .
               (isset($parsedUrl["user"])      ? $parsedUrl["user"] . ":" : "") .
               (isset($parsedUrl["pass"])      ? $parsedUrl["pass"] . "@" : "") .
               (isset($parsedUrl["host"])      ? $parsedUrl["host"] : "") .
               (isset($parsedUrl["port"])      ? ":" . $parsedUrl["port"] : "") .
               (isset($parsedUrl["path"])      ? $parsedUrl["path"] : "") .
               (isset($parsedUrl["query"])     ? "?" . $parsedUrl["query"] : "") .
               (isset($parsedUrl["fragment"])  ? "#" . $parsedUrl["fragment"] : "");
    }

    function __construct($src){
        $this->setParsedUrl(parse_url($src));
        $this->setDirname(pathinfo($this->getParsedUrlProperty("path"),PATHINFO_DIRNAME));
        $this->setFilename(pathinfo($this->getParsedUrlProperty("path"),PATHINFO_FILENAME));
        $this->setExtension(pathinfo($this->getParsedUrlProperty("path"),PATHINFO_EXTENSION));
       	$this->setAsset(Asset_Image::getByPath($this->getPath()));
    }
}

