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

	/* full-screen image viewer: arrows, keyboard, swipe, Esc / click outside to close */
	var lb = null, lbList = [], lbIndex = 0, lbLast = null;
	function lbShow(i){
		lbIndex = (i + lbList.length) % lbList.length;
		lb.img.src = lbList[lbIndex];
		lb.count.textContent = lbList.length > 1 ? (lbIndex + 1) + ' / ' + lbList.length : '';
		lb.prev.hidden = lb.next.hidden = lbList.length < 2;
	}
	function lbClose(){ if(!lb) return; lb.root.hidden = true; document.body.style.overflow = ''; if(lbLast) lbLast.focus(); }
	function lightbox(list, start, alt){
		if(!lb){
			var r = document.createElement('div');
			r.className = 'v3-lb'; r.setAttribute('role', 'dialog'); r.setAttribute('aria-modal', 'true'); r.setAttribute('aria-label', 'Преглед на снимка');
			r.innerHTML = '<button type="button" class="v3-lb-x" aria-label="Затвори">✕</button>' +
				'<button type="button" class="v3-lb-nav v3-lb-prev" aria-label="Предишна снимка">‹</button>' +
				'<img class="v3-lb-img" alt="" />' +
				'<button type="button" class="v3-lb-nav v3-lb-next" aria-label="Следваща снимка">›</button>' +
				'<span class="v3-lb-count"></span>';
			document.body.appendChild(r);
			lb = { root:r, img:r.querySelector('.v3-lb-img'), prev:r.querySelector('.v3-lb-prev'), next:r.querySelector('.v3-lb-next'), count:r.querySelector('.v3-lb-count'), x:r.querySelector('.v3-lb-x') };
			lb.prev.addEventListener('click', function(e){ e.stopPropagation(); lbShow(lbIndex - 1); });
			lb.next.addEventListener('click', function(e){ e.stopPropagation(); lbShow(lbIndex + 1); });
			lb.x.addEventListener('click', lbClose);
			r.addEventListener('click', function(e){ if(e.target === r) lbClose(); });
			document.addEventListener('keydown', function(e){
				if(lb.root.hidden) return;
				if(e.key === 'Escape') lbClose();
				else if(e.key === 'ArrowLeft') lbShow(lbIndex - 1);
				else if(e.key === 'ArrowRight') lbShow(lbIndex + 1);
			});
			var sx = null;
			r.addEventListener('touchstart', function(e){ sx = e.touches[0].clientX; }, { passive:true });
			r.addEventListener('touchend', function(e){
				if(sx === null) return; var dx = e.changedTouches[0].clientX - sx; sx = null;
				if(Math.abs(dx) > 50) lbShow(lbIndex + (dx < 0 ? 1 : -1));
			});
		}
		lbLast = document.activeElement;
		lbList = list; lb.img.alt = alt || '';
		lb.root.hidden = false; document.body.style.overflow = 'hidden';
		lbShow(start || 0); lb.x.focus();
	}

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

		/* product gallery: thumbs switch the main image, main image opens the lightbox */
		var gal = document.querySelector('[data-gallery]');
		if(gal) gal.addEventListener('click', function(e){
			var main = gal.querySelector('[data-gallery-main]');
			if(e.target.closest('[data-lb-open]')){
				var srcs = Array.prototype.map.call(gal.querySelectorAll('[data-gallery-src]'), function(x){ return x.getAttribute('data-gallery-src'); });
				if(!srcs.length) srcs = [main.src];
				var i = srcs.indexOf(main.getAttribute('src')); lightbox(srcs, i < 0 ? 0 : i, main.alt);
				return;
			}
			var b = e.target.closest('[data-gallery-src]'); if(!b) return;
			main.src = b.getAttribute('data-gallery-src');
			Array.prototype.forEach.call(gal.querySelectorAll('[data-gallery-src]'), function(x){ x.classList.toggle('is-active', x === b); });
		});

		/* profile gallery: links open in the lightbox */
		Array.prototype.forEach.call(document.querySelectorAll('[data-lb-group]'), function(g){
			g.addEventListener('click', function(e){
				var a = e.target.closest('[data-lb-item]'); if(!a) return;
				e.preventDefault();
				var items = Array.prototype.slice.call(g.querySelectorAll('[data-lb-item]'));
				var img = a.querySelector('img');
				lightbox(items.map(function(x){ return x.getAttribute('href'); }), items.indexOf(a), img ? img.alt : '');
			});
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

	/* login / register / forgot password (same endpoints as the classic page) */
	function initAuth(){
		var box = document.querySelector('[data-auth]'); if(!box) return;
		var hashView = { '#vhod':'login', '#registracia':'register', '#parola':'forgot' };
		function show(view){
			Array.prototype.forEach.call(box.querySelectorAll('[data-view]'), function(s){ s.hidden = s.getAttribute('data-view') !== view; });
			var first = box.querySelector('[data-view="' + view + '"] input:not([type=checkbox])'); if(first) first.focus();
		}
		show(hashView[location.hash] || box.getAttribute('data-start') || 'login');
		box.addEventListener('click', function(e){
			var go = e.target.closest('[data-go]');
			if(go){ e.preventDefault(); history.replaceState(null, '', go.getAttribute('href')); show(go.getAttribute('data-go')); return; }
			var eye = e.target.closest('[data-eye]');
			if(eye){ var inp = eye.parentNode.querySelector('input'); inp.type = inp.type === 'password' ? 'text' : 'password'; eye.setAttribute('aria-label', inp.type === 'password' ? 'Покажи паролата' : 'Скрий паролата'); }
		});
		var emailOk = function(v){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); };
		function busy(f, on){ var b = f.querySelector('button[type=submit]'); b.disabled = on; }

		var login = box.querySelector('[data-form="login"]');
		login.addEventListener('submit', function(e){
			e.preventDefault();
			var em = login.elements.namedItem('email'), pw = login.elements.namedItem('pass');
			if(!mark(em, !emailOk(em.value.trim()))) return formMsg(login, 'Моля, въведи валиден имейл.', false);
			if(!mark(pw, !pw.value)) return formMsg(login, 'Моля, въведи паролата си.', false);
			busy(login, true); formMsg(login, 'Влизане…', true);
			post('/ajax/check_login.php', { email: em.value.trim(), pass: pw.value }, function(status, res){
				if(status === 200 && res && parseInt(res[1], 10) > 0){ formMsg(login, 'Успешен вход. Пренасочване…', true); location.href = '/userlogins/index.php'; }
				else { busy(login, false); formMsg(login, res && res[0] ? stripTags(res[0]) : 'Неуспешен вход. Провери имейла и паролата.', false); }
			});
		});

		var reg = box.querySelector('[data-form="register"]');
		var step1 = reg.querySelector('[data-step="1"]'), step2 = reg.querySelector('[data-step="2"]'), regBtn = reg.querySelector('button[type=submit]');
		function regStep(n){
			step1.hidden = n !== 1; step2.hidden = n !== 2;
			regBtn.textContent = regBtn.getAttribute(n === 2 ? 'data-confirm-label' : 'data-submit-label');
			if(n === 2) reg.elements.namedItem('code').focus();
		}
		reg.querySelector('[data-back-step]').addEventListener('click', function(e){ e.preventDefault(); formMsg(reg, '', true); regStep(1); });
		reg.addEventListener('submit', function(e){
			e.preventDefault();
			var el = reg.elements, nm = el.namedItem('reg_name'), em = el.namedItem('reg_email'), pw = el.namedItem('reg_password'), code = el.namedItem('code');
			var confirming = !step2.hidden;
			if(!confirming){
				if(!mark(nm, nm.value.trim().length < 3 || !/^[Ѐ-ӿA-Za-z\s\-]+$/.test(nm.value.trim()))) return formMsg(reg, 'Моля, въведи име и фамилия (само букви).', false);
				if(!mark(em, !emailOk(em.value.trim()))) return formMsg(reg, 'Моля, въведи валиден имейл.', false);
				if(!mark(pw, pw.value.length < 8)) return formMsg(reg, 'Паролата трябва да е поне 8 знака.', false);
				if(!el.namedItem('terms').checked) return formMsg(reg, 'Моля, приеми Общите условия.', false);
			}else if(!mark(code, !code.value.trim())) return formMsg(reg, 'Моля, въведи кода от имейла.', false);
			busy(reg, true); formMsg(reg, confirming ? 'Създаване на акаунта…' : 'Изпращане на код…', true);
			post('/ajax/register.php', { reg_name: nm.value.trim(), reg_email: em.value.trim(), reg_password: pw.value, code: confirming ? code.value.trim() : '' }, function(status, res){
				busy(reg, false);
				if(status === 200 && res && parseInt(res[1], 10) === 1){ formMsg(reg, 'Профилът е създаден. Пренасочване…', true); location.href = box.getAttribute('data-after-register'); return; }
				if(status === 200 && res && parseInt(res[2], 10) === 1){
					reg.querySelector('[data-sent-to]').textContent = em.value.trim();
					regStep(2);
					formMsg(reg, confirming ? stripTags(res[0]) : '', !confirming);
					return;
				}
				formMsg(reg, res && res[0] ? stripTags(res[0]) : 'Регистрацията не беше успешна. Опитай отново.', false);
			});
		});

		var forgot = box.querySelector('[data-form="forgot"]');
		forgot.addEventListener('submit', function(e){
			e.preventDefault();
			var em = forgot.elements.namedItem('email');
			if(!mark(em, !emailOk(em.value.trim()))) return formMsg(forgot, 'Моля, въведи валиден имейл.', false);
			busy(forgot, true);
			post('/ajax/passforgotten.php', { email: em.value.trim() }, function(status, res){
				busy(forgot, false);
				// real problems (already logged in, e-mail could not be sent, invalid address) are shown;
				// otherwise the same neutral answer whether the e-mail exists or not (does not reveal registered addresses)
				if(status === 200 && res && res[0] && parseInt(res[1], 10) === 0) formMsg(forgot, stripTags(res[0]), false);
				else if(status === 200) formMsg(forgot, 'Ако има профил с този имейл, ще получиш нова парола до няколко минути. Провери и папка „Спам“.', true);
				else formMsg(forgot, 'Нещо се обърка. Опитай отново.', false);
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function(){
		initProfile();
		initAuth();
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
