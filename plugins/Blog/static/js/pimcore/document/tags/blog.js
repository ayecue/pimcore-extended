pimcore.registerNS("pimcore.document.tags.blog");
pimcore.document.tags.blog = Class.create(pimcore.document.tag, {

    getType: function () {
        return "blog";
    },

    getParentType: function () {
        return "object";
    },

    getBlogPostSubType : function () {
        return "BlogPost";
    },

    initialize: function (id, name, options, data, inherited) {
        var me = this;

        me.id = id;
        me.name = name;
        me.data = data;
        me.options = me.parseOptions(options);

        this.setupWrapper();

        me.store = new Ext.data.ArrayStore({
            data: me.data.blogPosts,
            fields: [
                "id",
                "path",
                "name"
            ]
        });

        var elementConfig = {
            store: me.store,
            bodyStyle: "color:#000",
            sm: new Ext.grid.RowSelectionModel({
                singleSelect:true
            }),
            colModel: new Ext.grid.ColumnModel({
                defaults: {
                    sortable: false
                },
                columns: [
                    {
                        header: 'ID', 
                        dataIndex: 'id', 
                        width: 50
                    },
                    {
                        id: "path", 
                        header: t("path"), 
                        dataIndex: 'path', 
                        width: 200
                    },
                    {
                        header: t("name"), 
                        dataIndex: 'name', 
                        width: 100
                    },
                    {
                        xtype:'actioncolumn',
                        width:30,
                        items:[
                            {
                                tooltip:t('up'),
                                icon:"/pimcore/static/img/icon/arrow_up.png",
                                handler: me.moveBlogPostUp.bind(me)
                            }
                        ]
                    },
                    {
                        xtype:'actioncolumn',
                        width:30,
                        items:[
                            {
                                tooltip: t('down'),
                                icon: "/pimcore/static/img/icon/arrow_down.png",
                                handler: me.moveBlogPostDown.bind(me)
                            }
                        ]
                    },
                    {
                        xtype: 'actioncolumn',
                        width: 30,
                        items: [{
                            tooltip: t('open'),
                            icon: "/pimcore/static/img/icon/pencil_go.png",
                            handler: me.openBlogPost.bind(me)
                        }]
                    },
                    {
                        xtype: 'actioncolumn',
                        width: 30,
                        items: [{
                            tooltip: t('remove'),
                            icon: "/pimcore/static/img/icon/cross.png",
                            handler: me.removeBlogPost.bind(me)
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
                        text: "<b>" + (me.options.title || "") + "</b>"
                    },
                    "->",
                    {
                        xtype: "button",
                        iconCls: "pimcore_icon_add",
                        handler: me.openCreateEditor.bind(me)
                    },
                    {
                        xtype: "button",
                        iconCls: "pimcore_icon_delete",
                        handler: me.empty.bind(me)
                    },
                    {
                        xtype: "button",
                        iconCls: "pimcore_icon_search",
                        handler: me.openSearchEditor.bind(me)
                    }
                ]
            }
        };

        // height specifics
        if(typeof me.options.height != "undefined") {
            elementConfig.height = me.options.height;
        } else {
            elementConfig.autoHeight = true;
        }

        // width specifics
        if(typeof me.options.width != "undefined") {
            elementConfig.width = me.options.width;
        }

        me.element = new Ext.grid.GridPanel(elementConfig);

        me.element.on("rowcontextmenu", me.onRowContextmenu.bind(me));
        me.element.reference = this;

        me.element.on("render", function (el) {
            // register at global DnD manager
            dndManager.addDropTarget(me.element.getEl(),
                me.onNodeOver.bind(this),
                me.onNodeDrop.bind(this)
            );
        }.bind(this));

        me.element.render(id);
    },

    onNodeOver: function(target, dd, e, data) {
        var me = this;

        if (me.droppingAllowed(data)) {
            return Ext.dd.DropZone.prototype.dropAllowed;
        } else {
            return Ext.dd.DropZone.prototype.dropNotAllowed;
        }
    },

    onNodeDrop: function (target, dd, e, data) {
        var me = this;

        if (me.droppingAllowed(data)) {
            if(data["grid"] && data["grid"] == me.component) {
                var rowIndex = me.component.getView().findRowIndex(e.target);

                if(rowIndex !== false) {
                    me.moveBlogPostTo(data.rowIndex,rowIndex);
                }
            } else {
                var attributes = data.node.attributes,
                    initData = {
                        id: attributes.id,
                        path: attributes.path,
                        name: attributes.text
                    };

                // check for existing element
                if (!me.blogPostAlreadyExists(initData.id, initData.name)) {
                    me.store.add(
                        new me.store.recordType(initData, me.store.getCount() + 1)
                    );

                    return true;
                }
            }

            return false;
        } else {
            return false;
        }
    },

    onRowContextmenu: function (grid, rowIndex, event) {
        var me = this,
            menu = new Ext.menu.Menu();

        menu.add(new Ext.menu.Item({
            text: t('remove'),
            iconCls: "pimcore_icon_delete",
            handler: function (item) {
                item.parentMenu.destroy();
                me.removeBlogPost(grid,rowIndex);
            }
        }));

        menu.add(new Ext.menu.Item({
            text: t('open'),
            iconCls: "pimcore_icon_open",
            handler: function (data, item) {
                item.parentMenu.destroy();
                me.openBlogPost(grid,rowIndex);
            }
        }));

        menu.add(new Ext.menu.Item({
            text: t('search'),
            iconCls: "pimcore_icon_search",
            handler: function (item) {
                item.parentMenu.destroy();
                me.openSearchEditor();
            }
        }));

        event.stopEvent();
        menu.showAt(event.getXY());
    },

    openSearchEditor: function () {
        var me = this;

        pimcore.helpers.itemselector(true, this.addDataFromSelector.bind(me), {
            type: [me.getParentType()],
            subtype: {
                object: [me.getParentType()]
            },
            specific : {
                classes : [me.getBlogPostSubType()]
            }
        });
    },

    blogPostAlreadyExists: function (id, name) {
        var me = this,
            store = me.store;

        // check for existing element
        var result = store.queryBy(function (id, type, record, rid) {
            if (record.get('id') == id && record.get('name') == name) {
                return true;
            }
            return false;
        }.bind(me, id, name));

        if (result.length < 1) {
            return false;
        }

        return true;
    },

    addDataFromSelector: function (items) {
        var me = this;

        if (items.length > 0) {
            for (var i = 0; i < items.length; i++) {
                var item = items[i];

                if (!this.blogPostAlreadyExists(item.id, item.text)) {
                    me.store.add(new me.store.recordType({
                        id: item.id,
                        path: item.fullpath,
                        name: item.text
                    }, me.store.getCount() + 1));
                }
            }
        }
    },

    empty: function () {
        this.store.removeAll();
    },

    getValue: function () {
        var me = this,
            tmData = [],
            data = me.store.queryBy(function(record, id) {
                return true;
            });

        for (var i = 0; i < data.items.length; i++) {
            tmData.push(data.items[i].data);
        }

        return {
            rssActive : me.data.rssActive,
            blogPostIds : tmData
        };
    },

    sourceIsTreeNode: function (source) {
        try {
            if (source.node) {
                return true;
            }
        } catch (e) {
            return false;
        }
        return false;
    },

    droppingAllowed: function(data) {
        var me = this;

        // check if data is a treenode, if not check if the source is the same grid because of the reordering
        if (!me.sourceIsTreeNode(data)) {
            if(data["grid"] && data["grid"] == me.component) {
                return true;
            }
            return false;
        }

        var attributes = data.node.attributes,
            type = attributes.elementType,
            subType = attributes.className;

        return type == me.getParentType() && subType == me.getBlogPostSubType();

    },

    moveBlogPostTo : function(blogPostIdx,moveToIdx){
        var me = this,
            store = me.store;

        if (rowIndex > 0 && rowIndex < store.getCount() - 1) {
            var rec = store.getAt(blogPostIdx);

            store.removeAt(blogPostIdx);
            store.insert(moveToIdx, [rec]);
        }
    },

    moveBlogPostUp : function(grid, rowIndex){
        this.moveBlogPostTo(rowIndex, rowIndex - 1);
    },

    moveBlogPostDown : function(grid, rowIndex){
        this.moveBlogPostTo(rowIndex, rowIndex + 1);
    },

    openCreateEditor : function(){

    },

    openBlogPost : function(grid, rowIndex){

    },

    removeBlogPost : function(grid, rowIndex){
        var me = this,
            store = me.store;

        store.removeAt(rowIndex);
    }
});