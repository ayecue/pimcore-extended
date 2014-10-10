<?php
	/**
	 *	Setup variables
	 */
	$editmode = $this->editmode; //set editmode
?>

<?php if($editmode) { ?>
    <h3 class="black bold">Content area:</h3>
<?php } ?>

<?php 
	$classparserField = $this->classparser("test");

	echo $classparserField;
?>

	<div class="wrapper <?php echo $classparserField->getClass(); ?>">

	</div>

<?php
	echo $this->areablockplus("content"); 
?>