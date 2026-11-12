var FBSideLike = {};

FBSideLike.init = function() {
	
	var scr = $$('script[fbpage]');
	
	if (scr.length == 1) {
		scr = scr[0];
		var fbpage = scr.get('fbpage');
	} else {
		return;
	}

	var border = scr.get('bordercolor') || '3b5997';
	var image  = scr.get('image') || 'plain';
	var base   = scr.get('src').replace('fb-sidelike.js', '');
	
	var box = new Element('div', {
		id: 'fb-side-like',
		styles: {
			backgroundImage: 'url(' + base + 'fb-sidebar-' + image + '.png)',
			backgroundRepeat: 'no-repeat',
			position: 'fixed',
			top: '31%',
			right: -292,
			width: 322,
			height: 258
		}
	}).inject(document.body);
	
	var like = new Element('div', {
		styles: {
			position: 'absolute',
			left: 30,
			width: 292,
			height: 258,
			background: '#fff'
		}
	}).inject(box);
	
	var toggle = function(dir) {
		box.tween('right', dir < 0 ? -1 : -292);
	}
	
	like.set('html', '<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F'
					+ fbpage
					+ '&amp;width=292&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23'
					+ border
					+ '&amp;wmode=opaque&amp;stream=false&amp;header=false&amp;appId=185006554980818"'
					+ 'scrolling="no" frameborder="0" style="border:none; position:absolute;background:#fff;overflow:hidden; width:292px; height:258px;z-index:900;" allowTransparency="false" wmode="Opaque"></iframe>');
	
	box.set('tween', {
		duration: 550,
		transition: Fx.Transitions.Pow.easeInOut
	}).addEvents({
		'mouseenter': function() { toggle(-1); },
		'mouseleave': function() { toggle(1); }
	});
}

if (typeof(MooTools) == 'undefined') {
	(function() {
		var mt = document.createElement("script");
		mt.type = "text/javascript";
		//mt.src = ("https:" == document.location.protocol ? "https" : "http") + "://ajax.googleapis.com/ajax/libs/mootools/1.2.5/mootools-yui-compressed.js";
		mt.src = ("https:" == document.location.protocol ? "https" : "http") + "://dealsbulgaria.com/js/facebook/mootools-yui-compressed.js";
		mt.async = true;
		mt.onload = FBSideLike.init;
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(mt, s);
	})();
} else {
	window.addEvent('load', FBSideLike.init);
}