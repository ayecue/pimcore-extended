<?php


class PageConfigurator_Helper {
    static private function cycleClassFields($context,&$data){
        if (!isset($context) || !($context instanceof Object_Class_Layout)) {
            return;
        }

        if (!method_exists($context,"getChilds")) {
            return;
        }

        $panels = $context->getChilds();

        if (empty($panels)) {
            return;
        }

        foreach ($panels as $panel) {
            if (!is_object($panel)) {
                continue;
            }

            if ($panel instanceof Object_Class_Layout_Panel && $panel->hasChilds()) {
                $panelChilds = $panel->getChilds();
                $panelName = $panel->getName();

                foreach ($panelChilds as $panelChild) {
                    if (!($panelChild instanceof Object_Class_Data)) {
                        continue;
                    }

                    $name = $panelChild->getName();
                    $setter = "set" . ucfirst($name);
                    $getter = "get" . ucfirst($name);
                    $options = NULL;

                    if (isset($panelChild->options) && !empty($panelChild->options)) {
                        $map = array();

                        foreach ($panelChild->options as $option) {
                            $property = $option["value"];
                            $value = $option["key"];
                            $map[$property] = $value;
                        }

                        $options = (object) $map;
                    }

                    $data[] = (object) array(
                        "name"      => $name,
                        "options"   => $options,
                        "setter"    => $setter,
                        "getter"    => $getter,
                        "title"     => $panelChild->title,
                        "fieldtype" => $panelChild->fieldtype
                    );
                }
            } elseif ($panel instanceof Object_Class_Layout && $panel->hasChilds()) {
                self::cycleClassFields($panel,$data);
            }
        }
    }

    static public function getClassFields($class){
        $constructor = $class->getClass();
        $context = $constructor->getLayoutDefinitions();
        $data = array();

        //cycle through layout
        self::cycleClassFields($context,$data);

        return $data;
    }
}