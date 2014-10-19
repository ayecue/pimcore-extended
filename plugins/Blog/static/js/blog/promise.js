// Tiny Promise
// @author: swe 
function Promise() {
    this._c = [];
};
 
Promise.prototype = {
    /**
     *  Default error message when Promise has been already fired.
     */
    exEmptyPromise : 'Error: Promise already fired.',
    /**
     *  Register callbacks for different states. 
     *  All states are possible but to keep it simple I added three standart methods: then, failure and always.
     */
    then : function(fn){return this.after(fn,true);},
    failure : function(fn){return this.after(fn,false);},
    always : function(fn){return this.after(fn,null);},
    after : function (fn,state) {
        var me = this, c = me._c;
        if (!c) throw new Error(me.exEmptyPromise);
        c.push({state : state,fn : fn});
        return me;
    },
    /**
     *  Resolve all callbacks of a certain state.
     *  Same thing like above. Basicly all states are possible but to keep it simple I added two standart methods: resolve and reject
     */
    resolve : function(scope,args){this.fire(true,scope,args);},
    reject : function(scope,args){this.fire(false,scope,args);},
    fire : function (state,scope,args) {
        var me = this;
        if (!me._c) throw new Error(me.exEmptyPromise);
        for (var call; (call = me._c.shift()) != null; (call.state === state || call.state == null) && call.fn.apply(scope,args));
        me._c = null;
    }
};