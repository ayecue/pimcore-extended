if (!fxpatcher) {
    var fxpatcher = (function(){
    	var patcher = function(){
    		this.map = {};
            this.stack = [];

            this.inject();
    	};

        patcher.findClassPath = function(path,root) {
            if (!root) {
                root = window;
            }

            var splitted = path instanceof Array ? path : path.split('.'),
                obj = root;

            while (obj) {
                var prop = splitted.shift();

                if (prop in obj) {
                    obj = obj[prop];
                } else {
                    return;
                }

                if (splitted.length == 0) {
                    break;
                }
            }

            return obj;
        };

        patcher.patchClassPath = function(path,override){
            var me = this,
                splitted = path.split('.'),
                last = splitted.pop(),
                obj = me.findClassPath(path),
                parent = me.findClassPath(splitted);

            if (obj) {
                console.log('fxpatcher.patchClassPath','override',obj);
                Ext.override(obj,override);

                me.watch(parent,last.toString(),function(prop,oldval,val){
                    console.log('fxpatcher.watch',parent,'changed',prop,'to',val);
                    Ext.override(obj,override);
                });
            }

            return !!obj;
        };

        patcher.watch = function (obj ,prop, handler) {
            var oldval = obj[prop], 
                newval = oldval,
                getter = function () {
                    return newval;
                },
                setter = function (val) {
                    oldval = newval;
                    return newval = handler.call(obj, prop, oldval, val);
                };

            if (delete obj[prop]) { // can't watch constants
                if (Object.defineProperty) // ECMAScript 5
                    Object.defineProperty(obj, prop, {
                        get: getter,
                        set: setter
                    });
                else if (Object.prototype.__defineGetter__ && Object.prototype.__defineSetter__) { // legacy
                    Object.prototype.__defineGetter__.call(obj, prop, getter);
                    Object.prototype.__defineSetter__.call(obj, prop, setter);
                }
            }
        };

    	patcher.prototype = {
            self : patcher,
    		add : function(){
    			var me = this;

                for (var index = 0, len = arguments.length; index < len; index++) {
                    var patch = arguments[index];

                    if (me.self.patchClassPath(patch.library,patch.override)) {
                        continue;
                    }

                    me.map[patch.library] = patch.override;
                    me.stack.push(patch);
                }
    		},
            inject : function(){
                if (!pimcore) {
                    throw new Error('No pimcore found');
                }

                var me = this,
                    nativeRegisterNS = pimcore.registerNS;

                pimcore.registerNS = function(namespace) {
                    var currentLevel = nativeRegisterNS.apply(this,arguments);

                    if (namespace in me.map) {
                        me.self.patchClassPath(namespace,me.map[namespace]);
                    }

                    return currentLevel;
                };
            }
    	};

    	return new patcher();
    })();
}