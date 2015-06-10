# pimcore-extended

Added a pack of custom plugins, everything is tested with Pimcore (Version 2.0 - 2.3).

**Recently I moved all the plugins in own repositories and fixed certain parts of it so it works with Pimcore 3.0:**

* <a href="https://github.com/ayecue/PimcoreCdn">CdnPlugin for Pimcore 3.0</a>
* <a href="https://github.com/ayecue/Classparser">Classparser for Pimcore 3.0</a>
* <a href="https://github.com/ayecue/FxPatcher">FXPatcher for Pimcore 3.0</a>
* <a href="https://github.com/ayecue/CustomLess">Less for Pimcore 3.0</a>
* <a href="https://github.com/ayecue/PageConfigurator">PageConfigurator for Pimcore 3.0</a>
* <a href="https://github.com/ayecue/ResponsiveImages">ResponsiveImages for Pimcore 3.0</a>
* <a href="https://github.com/ayecue/WrapperTags">WrappedTags for Pimcore 3.0</a>


### Content

* <a href="#cdnplugin">CdnPlugin</a>
* <a href="#classparser">Classparser</a>
* <a href="#fxpatcher">FXPatcher</a>
* <a href="#less">Less</a>
* <a href="#pageconfigurator">PageConfigurator</a>
* <a href="#responsiveimages">ResponsiveImages</a>
* <a href="#wrappedtags">WrappedTags</a>


## CdnPlugin

Easy CDN Plugin for Pimcore. Before it's useful you need to install it and configurate these website properties:

* cdnDomain - Path to your CDN
* cdnFolders - Which of your folders should be in the CDN. For example you got a file in a folder named scripts. Then just add the string "scripts" to this property. If you got multiple folders seperate them with a comma. ("scripts,otherFolder")
* cdnExtensions - Which file extensions should be in the CDN. For example you just want to have images in your cdn then add this string: "png,jpeg,gif"


## Classparser

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


## FXPatcher

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


## Less

Basicly it's like the native pimcore integration. I just updated the less.php file to the newest version and I also updated the less.js file the newest.

Also this file uses a website property named "lessPluginPathToLessC" if you got lessc installed on your server.


## PageConfigurator

This plugin allows you to create so called "PageConfiguration" objects and also save all properties of your view in one object.

To create a "PageConfiguration" object you just create a normal class in the classes panel. There you need to write this in the "parent" field: "Object_PageConfiguration". Add as much fields as you want to your created class. Now create a new object from this class. Add this object to the page properties of a page of your choice. Now add this code in the code of your page:
```
$config = new PageConfigurator_Config($this);
```

Now you got all informations in this variable. All PageConfiguration objects get automaticly merged to the config object. This means that you can access directly the properties of the different objects. Just remember that same field names can get overwritten.

You can use the "PageConfigurator" even without the "PageConfiguration" objects. It will automaticly get all properties from you view.

This plugin is useful if you want to write less page properties.


## ResponsiveImages

This plugin add his own implementation of responsive images. (This feature wasn't there in Pimcore < 2.2) Before it's useful you need to install it and configurate these website properties:

* responsiveImageScript - The Javascript library you want to use to enable responsive images. From default it's mobify.
* responsiveImageAttrSelector - To this field you have to add a string which allows the plugin to recognize the image which should be responsive. You could for example use the attribute "data-imgresponsive". Every image with this attribute will get converted to a responsive image.
* responsiveImageParseAttr - To this field you have to a string. Basicly you can use the same string you used in the "responsiveImageAttrSelector" website property. This field is used to get the data from the right attribute field.

Here an example how to use the responsive image plugin in your script:
```
//Json of the responsive images config you need for the plugin to work
$responsiveImageConfigJson = ResponsiveImages_Helper::createConfigJson(array(
	array(
		"percent" => 0.5,
		"minWidth" => "0px",
		"maxWidth" => "320px"
	),
	array(
		"percent" => 0.7,
		"minWidth" => "321px",
		"maxWidth" => "640px"
	),
	array(
		"percent" => 1,
		"minWidth" => "641px"
	)
));
//Attribute field name
$responsiveImageParseAttr = "data-imgresponsive";

//Attribute array
$attributes = array();

//Apply config to attribute array
$attributes[$responsiveImageParseAttr] = $responsiveImageConfigJson;

//Output image
echo $this->image("myImage",array(
	"attributes" => $attributes
));
```


## WrappedTags

This plugin wrap all native pimcore tags so that you can easily extend them. To use your extended version just add the suffix "plus" to your tag.

For example if you want to use the wrapped version of the areablock write this in your view:
```
echo $this->areablockplus('myAreablock');
```
