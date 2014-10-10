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
 * @copyright  Copyright (c) 2009-2014 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     New BSD License
 */

/*global CKEDITOR*/
pimcore.registerNS("pimcore.document.tags.wysiwygplus");
pimcore.document.tags.wysiwygplus = Class.create(pimcore.document.tags.wysiwyg, {

    type: "wysiwygplus",

    getType: function () {
        return "wysiwygplus";
    }
});

CKEDITOR.disableAutoInline = true;

// IE Hack see: http://dev.ckeditor.com/ticket/9958
// problem is that every button in a CKEDITOR window fires the onbeforeunload event
CKEDITOR.on('instanceReady', function (event) {
    event.editor.on('dialogShow', function (dialogShowEvent) {
        if (CKEDITOR.env.ie) {
            $(dialogShowEvent.data._.element.$).find('a[href*="void(0)"]').removeAttr('href');
        }
    });
});