<?php

class Less_Tool_Helper extends Pimcore_Tool_Less {
	static public function getLessPHPPath() {
        return PIMCORE_PLUGINS_PATH . "/Less/lib/Less/lessc.inc.php";
    }

	public static function compile ($path, $source = null) {

        $conf = Pimcore_Config::getWebsiteConfig();
        $compiledContent = "";

        // use the original less compiler if configured
        if($conf->lessPluginPathToLessC) {
            $output = array();
            exec($conf->lessPluginPathToLessC . " " . $path, $output);
            $compiledContent = implode(" ",$output);

            // add a comment to the css so that we know it's compiled by lessc
            if(!empty($compiledContent)) {
                $compiledContent = "\n\n/**** compiled with lessc (node.js) ****/\n\n" . $compiledContent;
            }
        }

        // use php implementation of lessc if it doesn't work
        if(empty($compiledContent)) {
            include_once(self::getLessPHPPath());
            $less = new lessc();
            $less->importDir = dirname($path);
            $compiledContent = $less->parse(file_get_contents($path));

            // add a comment to the css so that we know it's compiled by lessphp
            $compiledContent = "\n\n/**** compiled with lessphp ****/\n\n" . $compiledContent;
        }

        if($source) {
            // correct references inside the css
            $compiledContent = self::correctReferences($source, $compiledContent);
        }

        // put the compiled contents into the cache
        //Pimcore_Model_Cache::save($compiledContent, $cacheKey, array("less"));

        return $compiledContent;
    }
}