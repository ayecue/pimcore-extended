pimcore.registerNS("pimcore.document.tags.blog");
pimcore.document.tags.blog = Class.create(pimcore.document.tag, {

    getType: function () {
        return "blog";
    },

    initialize: function (id, name, options, data, inherited) {
        var me = this;

        me.id = id;
        me.name = name;
        me.data = data;
        me.options = me.parseOptions(options);

        this.setupWrapper();

        me.list = new pimcore.document.tags.blog.list(me,data.posts);
        me.settings = new pimcore.document.tags.blog.settings(me,{
            rssActive : me.data.rssActive,
            limit : me.data.limit,
            postFolder : me.data.postFolder,
            postModule : me.data.postModule,
            postController : me.data.postController,
            postAction : me.data.postAction,
            postTemplate : me.data.postTemplate
        });
        me.editor = new pimcore.document.tags.blog.editor(me);
        me.element = new Ext.Panel({
            items: [
                me.editor.element,
                me.list.element
            ]
        });

        me.element.render(id);

        Ext.get(me.editor.id).setStyle({
            width: me.element.getWidth() + "px"
        });
    },

    getValue: function () {
        var me = this,
            settings = me.settings.getValue();

        return {
            rssActive : settings.rssActive,
            limit : settings.limit,
            postFolder : settings.postFolder,
            postModule : settings.postModule,
            postController : settings.postController,
            postAction : settings.postAction,
            postTemplate : settings.postTemplate,
            postIds : me.list.getValue()
        };
    },

    openCreateEditor : function(){
        var me = this;

        me.editor.open();
    },

    openBlogPost : function(grid, rowIndex){
        var me = this,
            store = me.list.store,
            rec = store.getAt(rowIndex);

        me.editor.open(rec.get('id'));
    }
});