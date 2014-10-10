<!DOCTYPE html>
<?php
	$config = new Website_Config($this);

	//fallback to main page document
    if(!$config->document) {
    	$config->setDocument($config->home);
    }

	 // Process variables
	 if ($config->has('title')) {
		$this->headTitle()->set($config->title);
	}

	if ($config->has('description')) {
		$this->headMeta()->appendName('description',$config->description);
	}

?>

<!--[if lt IE 7]>      <html id="<?php echo $config->pageId; ?>" class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php echo $config->language; ?>"> <![endif]-->
<!--[if IE 7]>         <html id="<?php echo $config->pageId; ?>" class="no-js lt-ie9 lt-ie8" lang="<?php echo $config->language; ?>"> <![endif]-->
<!--[if IE 8]>         <html id="<?php echo $config->pageId; ?>" class="no-js lt-ie9" lang="<?php echo $config->language; ?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html id="<?php echo $config->pageId; ?>" class="no-js" lang="<?php echo $config->language; ?>"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0"/>
		<meta name="robots" content="<?php echo $config->robots; ?>">
		<link rel="shortcut icon" href="/favicon.ico" type="image/ico">

		<?php echo $this->headTitle(); ?>
		<?php echo $this->headMeta(); ?>

		<link media="screen" rel="stylesheet/less" type="text/css" href="<?php echo $config->resourceUrlPrefix; ?>styles/application.less">

		<?php if ($config->editmode) { ?>
			<link rel="stylesheet/less" href="<?php echo $config->resourceUrlPrefix; ?>styles/editmode.less">
		<?php } ?>

	</head>
	<body>

	    <?php
	    	// Printing header
	    	Website_Tool_Output::header($this,$config);

	    	//Printing content
	    	$layoutResources = Website_Tool_Output::layout($this,$config);

			//Printing footer
    		Website_Tool_Output::footer($this,$config);
	    ?>

	    <?php if (!$config->editmode) {
	    	if (!empty($layoutResources)) {
	    		echo implode('',$layoutResources);
	    	}
		} ?>

	</body>
</html>
