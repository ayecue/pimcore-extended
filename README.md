pimcore-extended
================

Added a pack of custom plugins, everything is tested with Pimcore (Version 2.0 - 2.3).


CdnPlugin
=========

Easy CDN Plugin for Pimcore. Before it's useful you need to install it and configurate these website properties:

* cdnDomain - Path to your CDN
* cdnFolders - Which of your folders should be in the CDN. For example you got a file in a folder named scripts. Then just add the string "scripts" to this property. If you got multiple folders seperate them with a comma. ("scripts,otherFolder")
* cdnExtensions - Which file extensions should be in the CDN. For example you just want to have images in your cdn then add this string: "png,jpeg,gif"


Classparser
===========

Plugin to enable user in the CMS to add their own classes to a component. When you install this plugin an Object named "Classhelper" will be automaticly added. This object got two default fields. One mandatory field named "classtag" and one optional field named "description".

Use the field "classtag" to define classes like for example "bgcolor-blue". Use the field "description" to describe to the user what this class does. In this example I just would write something like "Makes background blue!".

Example:

Imagine you got this CSS file:
```
.bgcolor-blue {
	background: blue;
}
.bar {
	color: black;
}
.foo {
	border: 0;
}
```

Also imagine that you got a view like this:
```
<?php
	$myClassparser = $this->classparser("myDivClasses");

	if ($this->editmode) {
		echo $myClassparser;
	}
?>
<div class="<?php echo $myClassparser->getClass(); ?>">
</div>
```

Now imagine that you have added three different classes to the classparser like "bgcolor-blue", "bar" and "doh". The output would be:
```
<div class="bgcolor-blue bar doh">
</div>
```


FXPatcher
=========

Allows you to easily patch pimcore JS files. For example:
```
fxpatcher.add({
    library : 'pimcore.document.tags.areablock',
    override : {
        createToolBar: function () {
        	//code
        }
    }
});
```

Just look at the areablock example in the plugin.


Less
====

Basicly it's like the native pimcore integration. I just updated the less.php file to the newest version and I also updated the less.js file the newest.

Also this file uses a website property named "lessPluginPathToLessC" if you got lessc installed on your server.


PageConfigurator
================

This plugin allows you to create so called "PageConfiguration" objects and also save all properties of your view in one object.

To create a "PageConfiguration" object you just create a normal class in the classes panel. There you need to write this in the "parent" field: "Object_PageConfiguration". Add as much fields as you want to your created class. Now create a new object from this class. Add this object to the page properties of a page of your choice. Now add this code in the code of your page:
```
$config = new PageConfigurator_Config($this);
```

Now you got all informations in this variable. All PageConfiguration objects get automaticly merged to the config object. This means that you can access directly the properties of the different objects. Just remember that same field names can get overwritten.

You can use the "PageConfigurator" even without the "PageConfiguration" objects. It will automaticly get all properties from you view.

This plugin is useful if you want to write less page properties.


ResponsiveImages
================

This plugin add his own implementation of responsive images. (This feature wasn't there in Pimcore < 2.2)

Detailed description will be added.


WrappedTags
===========

This plugin wrap all native pimcore tags so that you can easily extend them. To use your extended version just add the suffix "plus" to your tag.

For example if you want to use the wrapped version of the areablock write this in your view:
```
echo $this->areablockplus('myAreablock');
```
