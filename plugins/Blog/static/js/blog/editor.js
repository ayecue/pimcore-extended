var editor = Class.create({

    initialize: function (oid) {
        var me = this;

        me.element = new Ext.Panel({
            layout : "fit",
            region:"center",
            border:false
        });

        me.view = new Ext.Viewport({
            layout : "fit",
            items : [
                me.element
            ]
        });

        me.open(oid);
    },

    openWithoutId : function(){
    	return;
    	Ext.Ajax.request({
                url: "/admin/object/add",
                params: {
                    className: className,
                    classId: classId,
                    parentId: parentId,
                    key: pimcore.helpers.getValidFilename(name)
                },
                success: function(response) {
                    var data = Ext.decode(response.responseText);
                    if (data.success) {
                        this.store.add(new this.store.recordType({
                            id: data.id,
                            path: parent + "/" + pimcore.helpers.getValidFilename(name),
                            type: className
                        }, this.store.getCount() + 1));
                        pimcore.helpers.openElement(data.id, "object", "object");
                        this.window.close();
                    } else {
                        pimcore.helpers.showNotification(t("error"), t("error_saving_object"), "error",data.message);
                    }

                }.bind(this)
            });
    },

    openWithId : function(id){
    	var me = this;

    	Ext.Ajax.request({
            url: "/admin/object/get/",
            params: {
                id: id
            },
            success: function(response) {
                var data = Ext.decode(response.responseText),
                	edit = new pimcore.object.edit(data),
                    cmp = edit.getLayout(data.layout);

                me.element.add(cmp);
                cmp.suspendEvents();
                me.element.doLayout();
                cmp.resumeEvents();
            }
        });
    },

    open : function(id){
        this[id != null ? "openWithId" : "openWithoutId"](id);
    }
});