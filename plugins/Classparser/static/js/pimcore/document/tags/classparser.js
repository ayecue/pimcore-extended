pimcore.registerNS("pimcore.document.tags.classparser");
pimcore.document.tags.classparser = Class.create(pimcore.document.tags.multihref, {

    getType: function () {
        return "classparser";
    },

    initialize: function(id, name, options, data, inherited) {
        this.id = id;
        this.name = name;

        this.options = this.parseOptions(options);
        this.data = data;
        this.restrictions = this.getDefaultRestrictionArray();

        this.setupWrapper();


        this.store = new Ext.data.ArrayStore({
            data: this.data,
            fields: [
                "id",
                "path",
                "type",
                "subtype"
            ]
        });


        var elementConfig = {
            store: this.store,
            bodyStyle: "color:#000",
            sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
            colModel: new Ext.grid.ColumnModel({
                defaults: {
                    sortable: false
                },
                columns: [
                    {header: 'ID', dataIndex: 'id', width: 50},
                    {id: "path", header: t("path"), dataIndex: 'path', width: 200},
                    {header: t("type"), dataIndex: 'type', width: 100},
                    {header: t("subtype"), dataIndex: 'subtype', width: 100},
                    {
                        xtype:'actioncolumn',
                        width:30,
                        items:[
                            {
                                tooltip:t('up'),
                                icon:"/pimcore/static/img/icon/arrow_up.png",
                                handler:function (grid, rowIndex) {
                                    if (rowIndex > 0) {
                                        var rec = grid.getStore().getAt(rowIndex);
                                        grid.getStore().removeAt(rowIndex);
                                        grid.getStore().insert(rowIndex - 1, [rec]);
                                    }
                                }.bind(this)
                            }
                        ]
                    },
                    {
                        xtype:'actioncolumn',
                        width:30,
                        items:[
                            {
                                tooltip:t('down'),
                                icon:"/pimcore/static/img/icon/arrow_down.png",
                                handler:function (grid, rowIndex) {
                                    if (rowIndex < (grid.getStore().getCount() - 1)) {
                                        var rec = grid.getStore().getAt(rowIndex);
                                        grid.getStore().removeAt(rowIndex);
                                        grid.getStore().insert(rowIndex + 1, [rec]);
                                    }
                                }.bind(this)
                            }
                        ]
                    },
                    {
                        xtype: 'actioncolumn',
                        width: 30,
                        items: [{
                            tooltip: t('open'),
                            icon: "/pimcore/static/img/icon/pencil_go.png",
                            handler: function (grid, rowIndex) {
                                var data = grid.getStore().getAt(rowIndex);
                                var subtype = data.data.subtype;
                                if (data.data.type == "object" && data.data.subtype != "folder") {
                                    subtype = "object";
                                }
                                pimcore.helpers.openElement(data.data.id, data.data.type, subtype);
                            }.bind(this)
                        }]
                    },
                    {
                        xtype: 'actioncolumn',
                        width: 30,
                        items: [{
                            tooltip: t('remove'),
                            icon: "/pimcore/static/img/icon/cross.png",
                            handler: function (grid, rowIndex) {
                                grid.getStore().removeAt(rowIndex);
                            }.bind(this)
                        }]
                    }
                ]
            }),
            autoExpandColumn: 'path',
            tbar: {
                items: [
                    {
                        xtype: "tbspacer",
                        width: 20,
                        height: 16,
                        cls: "pimcore_icon_droptarget"
                    },
                    {
                        xtype: "tbtext",
                        text: "<b>" + (this.options.title ? this.options.title : "") + "</b>"
                    },
                    "->",
                    {
                        xtype: "button",
                        iconCls: "pimcore_icon_delete",
                        handler: this.empty.bind(this)
                    },
                    {
                        xtype: "button",
                        iconCls: "pimcore_icon_search",
                        handler: this.openSearchEditor.bind(this)
                    }
                ]
            }
        };

        // height specifics
        if(typeof this.options.height != "undefined") {
            elementConfig.height = this.options.height;
        } else {
            elementConfig.autoHeight = true;
        }

        // width specifics
        if(typeof this.options.width != "undefined") {
            elementConfig.width = this.options.width;
        }



        this.element = new Ext.grid.GridPanel(elementConfig);

        this.element.on("rowcontextmenu", this.onRowContextmenu);
        this.element.reference = this;

        this.element.on("render", function (el) {
            // register at global DnD manager
            dndManager.addDropTarget(this.element.getEl(),
                this.onNodeOver.bind(this),
                this.onNodeDrop.bind(this));

        }.bind(this));

        this.element.render(id);
    },

    getDefaultRestrictionArray: function(){
        return {
            type: ["object"],
            subtype: {
                object: ["object"]
            },
            specific : {
                classes : this.getSpecificRestrictionArray()
            }
        };
    },

    getSpecificRestrictionArray: function(){
        var allowedTypes = this.options['allowedTypes'],
            specific = [];

        if (allowedTypes) {
            for (var index = 0, length = allowedTypes.length; index < length; index++) {
                var allowedType = allowedTypes[index],
                    trimmed = allowedType.replace(/^[^_]+_/,'');

                specific.push(trimmed);
            }
        }

        return specific;
    },

    getDataNodeAttributes : function(data){
    	var initData = {
            id: data.node.attributes.id,
            path: data.node.attributes.path,
            type: data.node.attributes.elementType
        };

        if (initData.type == "object") {
            if (data.node.attributes.className) {
                initData.subtype = data.node.attributes.className;
            }
            else {
                initData.subtype = "folder";
            }
        }

        if (initData.type == "document" || initData.type == "asset") {
            initData.subtype = data.node.attributes.type;
        }

        return initData;
    },

    isAllowedType : function(data){
    	var allowedTypes = this.restrictions.specific.classes;

    	if (allowedTypes.length > 0) {
    		var subtype = data.subtype || this.getDataNodeAttributes(data).subtype;

    		for (var index = 0, length = allowedTypes.length; index < length; index++) {
    			var allowedType = allowedTypes[index];

    			if (allowedType == subtype) {
    				return true;
    			}
    		}

            return false;
    	}

    	return true;
    },

    onNodeOver: function(target, dd, e, data) {
        return this.isAllowedType(data) && Ext.dd.DropZone.prototype.dropAllowed;
    },

    onNodeDrop: function (target, dd, e, data) {

        var initData = this.getDataNodeAttributes(data);

        if (!this.isAllowedType(initData)) {
        	return false;
        }

        // check for existing element
        if (!this.elementAlreadyExists(initData.id, initData.type)) {
            this.store.add(new this.store.recordType(initData, this.store.getCount() + 1));
            return true;
        }
        return false;

    },

    openSearchEditor: function () {

        pimcore.helpers.itemselector(true, this.addDataFromSelector.bind(this),this.restrictions);

    },

    addDataFromSelector: function (items) {
        if (items.length > 0) {
            for (var i = 0; i < items.length; i++) {
                if (!this.elementAlreadyExists(items[i].id, items[i].type)) {

                    var subtype = items[i].subtype;
                    if (items[i].type == "object") {
                        if (items[i].subtype == "object") {
                            if (items[i].classname) {
                                subtype = items[i].classname;
                            }
                        }
                    }

                    var initData = {
                        id: items[i].id,
                        path: items[i].fullpath,
                        type: items[i].type,
                        subtype: subtype
                    };

                    if (this.isAllowedType(initData)) {
                    	this.store.add(new this.store.recordType(initData, this.store.getCount() + 1));
                    }
                }
            }
        }
    }
});