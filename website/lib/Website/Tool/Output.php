<?php


class Website_Tool_Output {
	const LAYOUT_EXTERNAL_RESOURCES_PATTERN = "/<(script|link)[^>]*?>[^<]*?<\/(script|link)>|<(script|link)[^>]*?>/i";

	public static function header (Pimcore_View $view,Website_Config $config = NULL) {
		if ($config == NULL) {
			$config = new Website_Config($view);
		}

		if (!$config->has('enableHeader') || $config->enableHeader) {
	    	echo $view->snippet("header",array("config" => $config));
    	}
	}

	public static function layout (Pimcore_View $view,Website_Config $config = NULL) {
		if ($config == NULL) {
			$config = new Website_Config($view);
		}

		$layoutResources = array();
		$layout = $view->layout(array("config" => $config))->content;

		if (!empty($layout)) {
			if (!$config->editmode) {
				$pattern = self::LAYOUT_EXTERNAL_RESOURCES_PATTERN;

				//get and remove layout resources
				$layout = preg_replace_callback($pattern,function($match) use (&$layoutResources) {
					if (!empty($match[0])) {
						$layoutResources[] = $match[0];
					}

					return '';
				},$layout);
			}

			//render layout
			echo $layout;
		}

		return $layoutResources;
	}

	public static function footer (Pimcore_View $view,Website_Config $config = NULL) {
		if ($config == NULL) {
			$config = new Website_Config($view);
		}

		if (!$config->has('enableFooter') || $config->enableFooter) {
    		echo $view->snippet('footer',array("config" => $config));
    	}
    }
}
