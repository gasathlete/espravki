/* eSpravki v3 – menu + cookie consent (no jQuery). Analytics loads only after consent. */
(function(){
	'use strict';
	var COOKIE = 'es_consent';

	function getCookie(n){
		var m = document.cookie.match(new RegExp('(?:^|; )' + n + '=([^;]*)'));
		return m ? decodeURIComponent(m[1]) : null;
	}
	function setCookie(n, v, days){
		var d = new Date(); d.setTime(d.getTime() + days * 864e5);
		document.cookie = n + '=' + encodeURIComponent(v) + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax' + (location.protocol === 'https:' ? ';Secure' : '');
	}
	function readConsent(){
		var c = getCookie(COOKIE); if(!c) return null;
		return { analytics: c.indexOf('a1') > -1, marketing: c.indexOf('m1') > -1 };
	}

	function loadAnalytics(){
		var id = window.V3_GA4; if(!id || window.__v3ga) return;
		window.__v3ga = 1;
		var s = document.createElement('script'); s.async = true;
		s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(id);
		document.head.appendChild(s);
		window.dataLayer = window.dataLayer || [];
		window.gtag = function(){ window.dataLayer.push(arguments); };
		window.gtag('js', new Date()); window.gtag('config', id, { anonymize_ip: true });
	}

	function save(analytics, marketing){
		setCookie(COOKIE, 'v1.a' + (analytics ? 1 : 0) + '.m' + (marketing ? 1 : 0), 180);
		hide('v3-cookie'); hide('v3-cookie-modal');
		if(analytics) loadAnalytics();
	}
	function show(id){ var e = document.getElementById(id); if(e) e.hidden = false; }
	function hide(id){ var e = document.getElementById(id); if(e) e.hidden = true; }

	function openSettings(){
		var c = readConsent() || { analytics: false, marketing: false };
		var a = document.getElementById('v3-c-analytics'), m = document.getElementById('v3-c-marketing');
		if(a) a.checked = c.analytics; if(m) m.checked = c.marketing;
		show('v3-cookie-modal');
		var box = document.querySelector('#v3-cookie-modal .v3-modal-box button'); if(box) box.focus();
	}

	document.addEventListener('DOMContentLoaded', function(){
		/* mobile menu */
		var burger = document.querySelector('.v3-burger'), nav = document.getElementById('v3-nav');
		if(burger && nav){
			burger.addEventListener('click', function(){
				var open = nav.classList.toggle('is-open');
				burger.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
		}

		/* consent */
		var c = readConsent();
		if(c === null) show('v3-cookie'); else if(c.analytics) loadAnalytics();

		document.addEventListener('click', function(e){
			var t = e.target.closest ? e.target.closest('[data-consent],[data-cookie-settings],[data-close]') : null;
			if(!t) return;
			if(t.hasAttribute('data-cookie-settings')){ e.preventDefault(); openSettings(); return; }
			if(t.hasAttribute('data-close')){ hide('v3-cookie-modal'); return; }
			var v = t.getAttribute('data-consent');
			if(v === 'all') save(true, true);
			else if(v === 'none') save(false, false);
			else if(v === 'save') save(document.getElementById('v3-c-analytics').checked, document.getElementById('v3-c-marketing').checked);
		});
		document.addEventListener('keydown', function(e){ if(e.key === 'Escape') hide('v3-cookie-modal'); });
		var modal = document.getElementById('v3-cookie-modal');
		if(modal) modal.addEventListener('click', function(e){ if(e.target === modal) hide('v3-cookie-modal'); });
	});
})();
