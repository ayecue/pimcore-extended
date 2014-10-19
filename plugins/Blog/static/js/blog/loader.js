var ScriptLoader = (function(){
	function Loader() {
		this.loaded = {};
	};

	Loader.prototype = {
		RECOGNIZE_STYLESHEET_PATTERN : /(^|\.)css(\??[^#]*?)(#?.*)$/i,
		RECOGNIZE_SCRIPT_PATTERN : /(^|\.)js(\??[^#]*?)(#?.*)$/i,
		queue : function(){
			var me = this,
				dfd = new Promise(),
				args = arguments,
				size = 0,
				length = args.length,
				next = function(){
					if (++size == length) {
						dfd.resolve(true);
					} else {
						load();
					}
				},
				load = function(){
					var ctx = args[size];

					if (me.loaded[ctx]) {
						return next();
					}

					me.load(ctx).then(function(){
						next();
					}).failure(function(){
						dfd.resolve(false);
					});
				};

			setTimeout(function(){
				load();
			},0);

			return dfd;
		},
		load : function(ctx){
			var me = this,
				path, type;

			if (typeof ctx == 'string') {
				type = path = ctx;
			} else {
				path = ctx.path;
				type = ctx.type;
			}

			if (me.loaded[path]) {
				var dfd = new Promise()

		    	setTimeout(function(){
					dfd.resolve(true);
				},0);

				return dfd;
			}

			if (me.RECOGNIZE_STYLESHEET_PATTERN.test(type)) {
				return me.loadStylesheet(path);
			} else if (me.RECOGNIZE_SCRIPT_PATTERN.test(type)) {
				return me.loadScript(path);
			}

			throw new Error("No loader found.");
		},
	    loadScript : function(path){
	    	var me = this,
	    		dfd = new Promise(),
				script = document.createElement("script"),
				onload = function( _, failure ) {
					if (!script) return;
					
					var state = script.readyState;
				
					if (failure || !state || /loaded|complete/i.test( state ) ) 
					{					
						script.onerror = script.onload = script.onreadystatechange = null;
						!!script.parentNode && script.parentNode.removeChild( script );
						dfd.resolve(!failure);
					}
				},
				onerror = function(_){
					onload(_,true);
				};

			script.type = 'text/javascript';
			script.charset = 'utf-8';
			script.async = true;
			script.onload = onload;
			script.onreadystatechange = onload;
			script.onerror = onerror;
			script.src = path;

			setTimeout(function(){
				document.head.appendChild(script);
			},0);

			return dfd.then(function(){
				me.loaded[path] = true;
			});
	    },
	    loadStylesheet : function(path){
			var me = this,
				dfd = new Promise(),
				style = document.createElement('link'),
				onload = function( _, failure){
					if (!style) return;

					var state = style.readyState;
					
					if (failure || !state || /loaded|complete/i.test( state ) ) 
					{
						clearInterval(interval);
						style.onload = style.onreadystatechange = null;
						!!failure && !!style.parentNode && style.parentNode.removeChild( style );
						dfd.resolve(!failure);
					}
				},
				onerror = function(_){
					onload(_,true);
				},
				trys = 0,
				interval = setInterval(function(){
					if (trys > 60) return onerror();
					
					try{!!style.sheet.cssRules && onload();}catch(e){trys++;}
				},10);

			style.type = 'text/css';
			style.rel = 'stylesheet';
			style.charset = 'utf-8';
			style.onload = onload;
			style.onreadystatechange = onload;
			style.onerror = onerror;
			style.href = path;

			setTimeout(function(){
				document.head.appendChild(style);
			},0);
			
			return dfd.then(function(){
				me.loaded[path] = true;
			});
	    }
	};

	return new Loader();
})();