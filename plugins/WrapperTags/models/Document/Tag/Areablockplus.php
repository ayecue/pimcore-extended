<?php
/**
 * Pimcore
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.pimcore.org/license
 *
 * @category   Pimcore
 * @package    Document
 * @copyright  Copyright (c) 2009-2014 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     New BSD License
 */

class Document_Tag_Areablockplus extends Document_Tag_Areablock {
    public function getType() {
        return "areablockplus";
    }


    /**
     * @param array $options
     * @return void
     */
    public function setOptions($options) {

        // we need to set this here otherwise custom areaDir's won't work
        $this->options = $options;

        // read available types
        $areaConfigs = $this->getBrickConfigs();
        $availableAreas = array();
        $availableAreasSort = array();

        if(!isset($options["allowed"]) || !is_array($options["allowed"])) {
            $options["allowed"] = array();
        }

        foreach ($areaConfigs as $areaName => $areaConfig) {

            // don't show disabled bricks
            if(!$options['dontCheckEnabled']){
                if(!$this->isBrickEnabled($areaName)) {
                    continue;
                }
            }


            if(empty($options["allowed"]) || in_array($areaName,$options["allowed"])) {

                $disabled = (bool) $areaConfig->disabletoolbar;

                if ($disabled) {
                    continue;
                }

                $n = (string) $areaConfig->name;
                $d = (string) $areaConfig->description;
                $icon = (string) $areaConfig->icon;

                if($this->view->editmode) {
                    if(empty($icon)) {
                        $path = $this->getPathForBrick($areaName);
                        $iconPath = $path . "/icon.png";
                        if(file_exists($iconPath)) {
                            $icon = str_replace(PIMCORE_DOCUMENT_ROOT, "", $iconPath);
                        }
                    }

                    if($this->view){
                        $n = $this->view->translateAdmin((string) $areaConfig->name);
                        $d = $this->view->translateAdmin((string) $areaConfig->description);
                    }
                }

                $availableAreas[] = array(
                    "name" => $n,
                    "description" => $d,
                    "type" => $areaName,
                    "icon" => $icon
                );
            }
        }

        // sort with translated names
        usort($availableAreas,function($a, $b) {
            if ($a["name"] == $b["name"]) {
                return 0;
            }
            return ($a["name"] < $b["name"]) ? -1 : 1;
        });

        $options["types"] = $availableAreas;

        if(isset($options["group"]) && is_array($options["group"])) {
            $groupingareas = array();
            foreach ($availableAreas as $area) {
                $groupingareas[$area["type"]] = $area["type"];
            }

            $groups = array();
            foreach ($options["group"] as $name => $areas) {

                $n = $name;
                if($this->view && $this->editmode){
                    $n = $this->view->translateAdmin($name);
                }
                $groups[$n] = $areas;

                foreach($areas as $area) {
                    unset($groupingareas[$area]);
                }
            }

            if(count($groupingareas) > 0) {
                $uncatAreas = array();
                foreach ($groupingareas as $area) {
                    $uncatAreas[] = $area;
                }
                $n = "Uncategorized";
                if($this->view && $this->editmode){
                    $n = $this->view->translateAdmin($n);
                }
                $groups[$n] = $uncatAreas;
            }

            $options["group"] = $groups;
        }

        if (empty($options["limit"])) {
            $options["limit"] = 1000000;
        }


        $this->options = $options;
        return $this;
    }
}
