fxpatcher.add({
    library : 'pimcore.document.tags.areablock',
    override : {
        createToolBar: function () {
            var buttons = [];
            var button;
            var bricksInThisArea = [];
            var groupsInThisArea = {};
            var areaBlockToolbarSettings = this.options["areablock_toolbar"];
            var itemCount = 0;

            if(pimcore.document.tags[this.toolbarGlobalVar] != false
                                                    && pimcore.document.tags[this.toolbarGlobalVar].itemCount) {
                itemCount = pimcore.document.tags[this.toolbarGlobalVar].itemCount;
            }

            if(typeof this.options.group != "undefined") {
                var groupMenu;
                var groupItemCount = 0;
                var isExistingGroup;
                var brickKey;
                var groups = Object.keys(this.options.group);

                for (var g=0; g<groups.length; g++) {
                    groupMenu = null;
                    isExistingGroup = false;
                    if(groups[g].length > 0) {

                        if(pimcore.document.tags[this.toolbarGlobalVar] != false) {
                            if(pimcore.document.tags[this.toolbarGlobalVar]["groups"][groups[g]]) {
                                groupMenu = pimcore.document.tags[this.toolbarGlobalVar]["groups"][groups[g]];
                                isExistingGroup = true;
                            }
                        }

                        if(!groupMenu) {
                            groupMenu = new Ext.Button({
                                xtype: "button",
                                text: groups[g],
                                iconCls: "pimcore_icon_area",
                                hideOnClick: false,
                                width: areaBlockToolbarSettings.buttonWidth,
                                menu: []
                            });
                        }

                        groupsInThisArea[groups[g]] = groupMenu;

                        for (var i=0; i<this.options.types.length; i++) {
                            if(in_array(this.options.types[i].type,this.options.group[groups[g]])) {
                                itemCount++;
                                brickKey = groups[g] + " - " + this.options.types[i].type;
                                button = this.getToolBarButton(this.options.types[i], brickKey, itemCount, "menu");
                                if(button) {
                                    bricksInThisArea.push(brickKey);
                                    groupMenu.menu.add(button);
                                    groupItemCount++;
                                }
                            }
                        }

                        if(!isExistingGroup && groupItemCount > 0) {
                            buttons.push(groupMenu);
                        }
                    }
                }
            } else {
                for (var i=0; i<this.options.types.length; i++) {
                    var brick = this.options.types[i];
                    itemCount++;

                    brickKey = brick.type;
                    button = this.getToolBarButton(brick, brickKey, itemCount);
                    if(button) {
                        bricksInThisArea.push(brickKey);
                        buttons.push(button);
                    }
                }
            }

            // only initialize the toolbar once, even when there are more than one area on the page
            if(pimcore.document.tags[this.toolbarGlobalVar] == false) {

                var x = areaBlockToolbarSettings["x"];
                if(areaBlockToolbarSettings["xAlign"] == "right") {
                    x = Ext.getBody().getWidth()-areaBlockToolbarSettings["x"]-areaBlockToolbarSettings["width"];
                }

                var toolbar = new Ext.Window({
                    title: areaBlockToolbarSettings.title,
                    width: areaBlockToolbarSettings.width,
                    border:false,
                    shadow: false,
                    resizable: false,
                    height: 400,
                    collapsed: true,
                    autoScroll: true,
                    style: "position:fixed;",
                    collapsible: true,
                    cls: "pimcore_areablock_toolbar",
                    closable: false,
                    x: x,
                    y: areaBlockToolbarSettings["y"],
                    items: [buttons],
                    listeners: {
                        move: function (win, x, y) {
                            var scroll = Ext.getBody().getScroll();
                            win.getEl().setStyle("top", y - scroll.top + "px");
                            win.getEl().setStyle("left", x - scroll.left + "px");
                        }
                    }
                });

                toolbar.show();
                toolbar.collapse();

                pimcore.document.tags[this.toolbarGlobalVar] = {
                    toolbar: toolbar,
                    groups: groupsInThisArea,
                    bricks: bricksInThisArea,
                    areablocks: [this],
                    itemCount: buttons.length
                };
            } else {
                pimcore.document.tags[this.toolbarGlobalVar].toolbar.add(buttons);
                pimcore.document.tags[this.toolbarGlobalVar].bricks =
                                        array_merge(pimcore.document.tags[this.toolbarGlobalVar].bricks, bricksInThisArea);
                pimcore.document.tags[this.toolbarGlobalVar].groups =
                                        array_merge(pimcore.document.tags[this.toolbarGlobalVar].groups, groupsInThisArea);
                pimcore.document.tags[this.toolbarGlobalVar].itemCount += buttons.length;
                pimcore.document.tags[this.toolbarGlobalVar].areablocks.push(this);
                pimcore.document.tags[this.toolbarGlobalVar].toolbar.doLayout();
            }

        }
    }
});