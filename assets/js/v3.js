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

	/* POST to the existing ajax endpoints (they require the XMLHttpRequest header) */
	function post(url, data, done){
		var body = Object.keys(data).map(function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]); }).join('&');
		var x = new XMLHttpRequest();
		x.open('POST', url, true);
		x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
		x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		x.onreadystatechange = function(){
			if(x.readyState !== 4 || !done) return;
			var res = null; try { res = JSON.parse(x.responseText); } catch(e){}
			done(x.status, res);
		};
		x.send(body);
	}
	/* tracking that must survive leaving the page (tel: / external link) */
	function beacon(url, data){
		var body = Object.keys(data).map(function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]); }).join('&');
		if(window.fetch){
			fetch(url, { method:'POST', keepalive:true, credentials:'same-origin',
				headers:{ 'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8', 'X-Requested-With':'XMLHttpRequest' }, body: body }).catch(function(){});
		}else post(url, data);
	}
	function stripTags(s){ var d = document.createElement('div'); d.innerHTML = s || ''; return d.textContent || ''; }
	function formMsg(form, text, ok){
		var m = form.querySelector('.v3-form-msg'); if(!m) return;
		m.textContent = text; m.className = 'v3-form-msg ' + (ok ? 'is-ok' : 'is-err');
	}
	function mark(input, bad){ if(input){ input.classList.toggle('is-invalid', !!bad); if(bad) input.focus(); } return !bad; }

	function initProfile(){
		document.addEventListener('click', function(e){
			var t = e.target.closest ? e.target.closest('[data-track-call],[data-track-link],[data-share]') : null;
			if(!t) return;
			if(t.hasAttribute('data-track-call')) beacon('/ajax/save_call.php', { aid: t.getAttribute('data-track-call'), pid: t.getAttribute('data-pid') || 0 });
			else if(t.hasAttribute('data-track-link')) beacon('/ajax/link_clicker.php', { aid: t.getAttribute('data-track-link') });
			else if(t.hasAttribute('data-share')){
				e.preventDefault();
				var url = location.href.split('#')[0];
				if(navigator.share){ navigator.share({ title: t.getAttribute('data-title') || document.title, url: url }).catch(function(){}); }
				else if(navigator.clipboard){ navigator.clipboard.writeText(url).then(function(){ t.lastChild.textContent = ' Връзката е копирана'; }); }
				else window.prompt('Копирай връзката:', url);
			}
		});

		var contact = document.querySelector('[data-v3-contact]');
		if(contact) contact.addEventListener('submit', function(e){
			e.preventDefault();
			var f = contact, el = f.elements, n = el.namedItem('names'), p = el.namedItem('phone'), m = el.namedItem('email'), msg = el.namedItem('message');
			var phone = p.value.replace(/[^0-9]/g, '');
			if(!mark(n, n.value.trim().length < 3 || !/^[Ѐ-ӿA-Za-z0-9\s\-]+$/.test(n.value.trim()))) return formMsg(f, 'Моля, въведи име и фамилия (само букви).', false);
			if(!mark(p, phone.length < 8)) return formMsg(f, 'Моля, въведи телефон (поне 8 цифри).', false);
			if(!mark(m, !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(m.value.trim()))) return formMsg(f, 'Моля, въведи валиден имейл.', false);
			if(!mark(msg, msg.value.trim().length < 20)) return formMsg(f, 'Съобщението трябва да е поне 20 символа.', false);
			var btn = f.querySelector('button[type=submit]'); btn.disabled = true;
			formMsg(f, 'Изпращане…', true);
			var payload = { names: n.value.trim(), email: m.value.trim(), phone: phone, aid: f.getAttribute('data-v3-contact'), message: msg.value.trim() };
			if(f.getAttribute('data-pid')){ payload.pid = f.getAttribute('data-pid'); payload.pname = f.getAttribute('data-pname') || ''; }
			post('/ajax/contact_trader.php', payload, function(status, res){
				if(status === 200 && res && res[1] == 1){ formMsg(f, stripTags(res[0]), true); btn.remove(); }
				else if(status === 200 && res && res[0]){ btn.disabled = false; formMsg(f, stripTags(res[0]), false); }
				else { btn.disabled = false; formMsg(f, 'Неуспешно изпращане. Опитай отново или се обади по телефона.', false); }
			});
		});

		/* product gallery */
		var gal = document.querySelector('[data-gallery]');
		if(gal) gal.addEventListener('click', function(e){
			var b = e.target.closest ? e.target.closest('[data-gallery-src]') : null; if(!b) return;
			gal.querySelector('[data-gallery-main]').src = b.getAttribute('data-gallery-src');
			Array.prototype.forEach.call(gal.querySelectorAll('[data-gallery-src]'), function(x){ x.classList.toggle('is-active', x === b); });
		});

		/* order / inquiry switch */
		Array.prototype.forEach.call(document.querySelectorAll('[data-pane]'), function(btn){
			btn.addEventListener('click', function(){
				var name = btn.getAttribute('data-pane');
				Array.prototype.forEach.call(document.querySelectorAll('[data-pane]'), function(x){
					var on = x === btn; x.classList.toggle('is-active', on); x.setAttribute('aria-selected', on ? 'true' : 'false');
					x.classList.toggle('v3-btn-orange', on); x.classList.toggle('v3-btn-line', !on);
				});
				Array.prototype.forEach.call(document.querySelectorAll('[data-pane-body]'), function(f){ f.hidden = f.getAttribute('data-pane-body') !== name; });
			});
		});

		/* quick order -> ajax/fast_order.php (needs names, cphone >= 8 digits, da >= 15 chars) */
		var order = document.querySelector('[data-v3-order]');
		if(order) order.addEventListener('submit', function(e){
			e.preventDefault();
			var f = order, el = f.elements, n = el.namedItem('names'), p = el.namedItem('cphone'), da = el.namedItem('da'), note = el.namedItem('note'), terms = el.namedItem('terms');
			var phone = p.value.replace(/[^0-9]/g, '');
			if(!mark(n, n.value.trim().length < 3 || !/^[Ѐ-ӿA-Za-z0-9\s\-]+$/.test(n.value.trim()))) return formMsg(f, 'Моля, въведи име (само букви).', false);
			if(!mark(p, phone.length < 8)) return formMsg(f, 'Моля, въведи телефон (поне 8 цифри).', false);
			if(!mark(da, da.value.trim().length < 15)) return formMsg(f, 'Моля, въведи пълен адрес за доставка (поне 15 символа).', false);
			if(!terms.checked) return formMsg(f, 'Моля, приеми условията за поръчка.', false);
			var extra = [];
			Array.prototype.forEach.call(document.querySelectorAll('[data-option]'), function(s){ if(s.value) extra.push(s.getAttribute('data-option') + ': ' + s.value); });
			if(note.value.trim()) extra.push('Бележка: ' + note.value.trim());
			var address = da.value.trim() + (extra.length ? '\n' + extra.join('\n') : '');
			var btn = f.querySelector('button[type=submit]'); btn.disabled = true; formMsg(f, 'Изпращане…', true);
			post('/ajax/fast_order.php', { pname: f.getAttribute('data-pname') || '', names: n.value.trim(), cphone: phone, da: address, aid: f.getAttribute('data-aid'), pid: f.getAttribute('data-v3-order') }, function(status, res){
				if(status === 200 && res && res[1] == 1){ formMsg(f, stripTags(res[0]), true); btn.remove(); }
				else if(status === 200 && res && res[0]){ btn.disabled = false; formMsg(f, stripTags(res[0]), false); }
				else { btn.disabled = false; formMsg(f, 'Неуспешно изпращане. Опитай отново или се обади на търговеца.', false); }
			});
		});

		var review = document.querySelector('[data-v3-review]');
		if(review) review.addEventListener('submit', function(e){
			e.preventDefault();
			var f = review, el = f.elements, star = f.querySelector('input[name=star]:checked');
			var nm = el.namedItem('name'), cm = el.namedItem('comment');
			if(!star) return formMsg(f, 'Моля, избери оценка от 1 до 5 звезди.', false);
			if(!mark(nm, nm.value.trim().length < 2)) return formMsg(f, 'Моля, въведи име.', false);
			if(!mark(cm, cm.value.trim().length < 10)) return formMsg(f, 'Мнението трябва да е поне 10 символа.', false);
			var btn = f.querySelector('button[type=submit]'); btn.disabled = true;
			post('/ajax/votes.php', { name: nm.value.trim(), comment: cm.value.trim(), star: star.value, aid: f.getAttribute('data-v3-review') }, function(status, res){
				if(status === 200 && res && res[1] == 1){ formMsg(f, stripTags(res[0]), true); btn.remove(); }
				else if(status === 200 && res && res[0]){ formMsg(f, stripTags(res[0]), false); btn.remove(); }
				else { btn.disabled = false; formMsg(f, 'Неуспешно изпращане. Опитай отново.', false); }
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function(){
		initProfile();
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
