<?php
/* ==========================================================================
   eSpravki v3 layout: shared <head>, header, footer and cookie consent
   for the redesigned pages (design "Е-СПРАВКИ / ВЕРСИЯ 3").
   PHP 5.4 compatible. Old pages keep using modules/header.php + footer.php.
   ========================================================================== */

// Has the new pricing (stage 5) gone live? Until then no prices / "30 days free" claims are shown.
if(!defined('V3_NEW_PRICING')) define('V3_NEW_PRICING', false);

function v3_e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Same as v3_e() but for text stored HTML-encoded in the DB (avoids "&amp;quot;")
function v3_t($s){ return htmlspecialchars(trim(html_entity_decode(strip_tags((string)$s), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'); }

// Short plain-text excerpt
function v3_excerpt($s, $len){
	$s = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string)$s), ENT_QUOTES, 'UTF-8')));
	if(function_exists('mb_strlen') && mb_strlen($s, 'UTF-8') > $len) $s = rtrim(mb_substr($s, 0, $len, 'UTF-8')).'…';
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// Link inside the site (keeps Cyrillic readable, escapes for HTML)
function v3_url($path){ return v3_e(WebSite.$path); }

// Inline SVG icons (no icon-font dependency)
function v3_icon($name, $size = 20){
	$p = array(
		'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'pin'    => '<path d="M12 21s-7-6.1-7-11.5A7 7 0 0 1 19 9.5C19 14.9 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'phone'  => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
		'arrow'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'chev'   => '<path d="m9 6 6 6-6 6"/>',
		'plus'   => '<path d="M12 5v14M5 12h14"/>',
		'menu'   => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'  => '<path d="M6 6l12 12M18 6 6 18"/>',
		'grid'   => '<rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/>',
		'user'   => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'check'  => '<path d="m5 12 5 5 9-10"/>',
		'clock'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'chart'  => '<path d="M5 20V10M12 20V4M19 20v-7"/>',
		'chat'   => '<path d="M4 5h16v11H8l-4 4z"/>',
		'shield' => '<path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/>',
		'cookie' => '<circle cx="12" cy="12" r="9"/><circle cx="9" cy="9" r="1"/><circle cx="15" cy="11" r="1"/><circle cx="10" cy="15" r="1"/>',
		'news'   => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 9h8M8 13h8M8 17h5"/>',
		'tag'    => '<path d="M3 12V4h8l10 10-8 8z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
		'fb'     => '<path d="M15 3h-2a4 4 0 0 0-4 4v3H6v4h3v7h4v-7h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'ig'     => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".6"/>',
		'tt'     => '<path d="M14 3v11.5a3.5 3.5 0 1 1-3.5-3.5"/><path d="M14 3c.6 2.6 2.4 4.4 5 4.6"/>',
	);
	$d = isset($p[$name]) ? $p[$name] : '';
	return '<svg class="v3-ic" width="'.(int)$size.'" height="'.(int)$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$d.'</svg>';
}

// Logo: assets/img/logo-v3.png (kept in git, unlike /images). Falls back to a drawn version of the mark.
function v3_logo($variant = 'dark'){
	$alt = 'Espravki.com – Национален бизнес каталог';
	if(file_exists(dirname(__FILE__).'/../../assets/img/logo-v3.png')){
		return '<a class="v3-logo" href="'.v3_url('/').'"><img src="'.v3_url('/assets/img/logo-v3.png?v=1').'" alt="'.$alt.'" width="217" height="48" /></a>';
	}
	return '<a class="v3-logo v3-logo-'.$variant.'" href="'.v3_url('/').'" aria-label="'.$alt.'">
		<svg class="v3-logo-mark" width="44" height="44" viewBox="0 0 48 48" aria-hidden="true">
			<circle cx="20" cy="20" r="15" fill="#13a3e8"/>
			<path d="M11 14c4-1 5 2 8 1s3-4 6-3 2 4 5 5M8 23c3 0 4 3 8 2s5-3 8-1 1 5 4 6" stroke="#0b4f7a" stroke-width="2.4" fill="none" stroke-linecap="round"/>
			<circle cx="20" cy="20" r="15" fill="none" stroke="#0d1f3c" stroke-width="3.5"/>
			<path d="M31 31l10 10" stroke="#0d1f3c" stroke-width="5.5" stroke-linecap="round"/>
		</svg>
		<span class="v3-logo-text"><span class="v3-logo-name"><b>E</b>spravki.com</span><span class="v3-logo-sub">Национален бизнес каталог</span></span>
	</a>';
}

// Main call-to-action target (registration flow; reworked in stage 5)
function v3_cta_url(){ return v3_url('/select-plan/'); }
function v3_cta_label(){ return V3_NEW_PRICING ? 'Започни безплатно' : 'Добави бизнеса си'; }

/* ---------- <head> ---------- */
function v3_head($meta_html, $extra_head = ''){
	echo '<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="theme-color" content="#0d1f3c" />
', $meta_html, '
<link rel="icon" href="', v3_url('/favicon.ico'), '" />
<link rel="preconnect" href="https://fonts.googleapis.com" /><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;subset=cyrillic&amp;display=swap" rel="stylesheet" />
<link href="', v3_url('/assets/css/v3.css?v=5'), '" rel="stylesheet" />
', $extra_head, '
</head>
<body class="v3">
<a class="v3-skip" href="#main">Към съдържанието</a>
';
}

/* ---------- Header ---------- */
function v3_header(){
	$logged = !empty($_SESSION['customer']);
	echo '<header class="v3-header">
	<div class="v3-wrap v3-header-in">
		', v3_logo(), '
		<nav class="v3-nav" id="v3-nav" aria-label="Основно меню">
			<a href="', v3_url('/#kak-raboti'), '">Как работи</a>
			<a href="', v3_url('/business-directory/a-z/'), '">Бизнеси</a>
			<a href="', v3_url('/products/'), '">Продукти и услуги</a>
			<a href="', v3_url('/select-plan/'), '">Цени</a>
			<a href="', v3_url('/news/'), '">Новини</a>
			<div class="v3-nav-actions">';
	if($logged){
		echo '<a class="v3-btn v3-btn-ghost" href="', v3_url('/mydash.php?profile=show'), '">', v3_icon('user', 18), ' Моят профил</a>';
	}else{
		echo '<a class="v3-login" href="', v3_url('/userlogins/index.php'), '">Вход</a>';
	}
	echo '		<a class="v3-btn v3-btn-orange" href="', v3_cta_url(), '">', v3_cta_label(), '</a>
			</div>
		</nav>
		<button class="v3-burger" type="button" aria-controls="v3-nav" aria-expanded="false" aria-label="Меню">', v3_icon('menu', 26), '</button>
	</div>
</header>
<main id="main">
';
}

/* ---------- Footer ---------- */
function v3_footer(){
	global $lang, $msg;
	echo '</main>
<footer class="v3-footer">
	<div class="v3-wrap v3-footer-in">
		<div class="v3-footer-brand">
			', v3_logo(), '
			<p>Намирай. Проверявай. Развивай бизнеса си.</p>
			<div class="v3-social">
				<a href="', v3_e(facebook_page), '" target="_blank" rel="noopener" aria-label="Facebook">', v3_icon('fb', 18), '</a>
				<a href="', v3_e(instagram), '" target="_blank" rel="noopener" aria-label="Instagram">', v3_icon('ig', 18), '</a>
				<a href="', v3_e(tiktok), '" target="_blank" rel="noopener" aria-label="TikTok">', v3_icon('tt', 18), '</a>
			</div>
		</div>
		<nav class="v3-footer-col" aria-label="Каталог">
			<a href="', v3_url('/business-directory/a-z/'), '">Бизнеси</a>
			<a href="', v3_url('/products/'), '">Продукти и услуги</a>
			<a href="', v3_url('/select-plan/'), '">Цени</a>
			<a href="', v3_url('/news/'), '">Новини</a>
		</nav>
		<nav class="v3-footer-col" aria-label="Информация">
			<a href="', v3_url('/business-for-sale/'), '">Продай бизнес</a>
			<a href="', v3_url('/contacts/'), '">Контакти</a>
			<a href="', v3_url('/news/terms-and-conditions'), '">Условия за ползване</a>
			<a href="', v3_url('/news/terms-for-traders'), '">Условия за търговци</a>
			<a href="#" data-cookie-settings>Настройки на бисквитките</a>
		</nav>
		<div class="v3-footer-col v3-footer-contact">
			<strong>Иванов &amp; Милев Девелъпмънт ЕООД</strong>
			<span>ЕИК 206957326</span>
			<span>гр. София, бул. България 13</span>
			<a href="mailto:eespravki@gmail.com">eespravki@gmail.com</a>
			<a href="tel:', v3_e(site_phone), '">', v3_e(site_phone), '</a>
		</div>
	</div>
	<div class="v3-wrap v3-footer-bottom">
		<span>© ', date('Y'), ' Espravki.com. Всички права запазени.</span>
	</div>
</footer>
';
	v3_cookie_banner();
	$ga = (defined('ga4_id') && preg_match('/^G-[A-Z0-9]+$/', ga4_id)) ? ga4_id : '';
	echo '<script>window.V3_GA4 = ', json_encode($ga), ';</script>
<script src="', v3_url('/assets/js/v3.js?v=4'), '" defer></script>
';
	// visit statistics (same as classic footer)
	include dirname(__FILE__).'/../footer_tracking.php';
	echo '</body></html>';
}

/* ---------- Cookie consent (A14): equal choices, optional categories off by default ---------- */
function v3_cookie_banner(){
	echo '<div class="v3-cookie" id="v3-cookie" role="region" aria-label="Бисквитки" hidden>
	<div class="v3-cookie-in">
		<span class="v3-cookie-ic">', v3_icon('cookie', 28), '</span>
		<div class="v3-cookie-txt">
			<strong>Бисквитки и поверителност</strong>
			<p>Използваме необходими бисквитки за работата на сайта. С твое съгласие включваме и анализ на посещенията.</p>
		</div>
		<div class="v3-cookie-btns">
			<button type="button" class="v3-btn v3-btn-line" data-consent="all">Приеми всички</button>
			<button type="button" class="v3-btn v3-btn-line" data-consent="none">Откажи незадължителните</button>
			<button type="button" class="v3-btn v3-btn-line" data-cookie-settings>Настройки</button>
		</div>
	</div>
</div>
<div class="v3-modal" id="v3-cookie-modal" hidden>
	<div class="v3-modal-box" role="dialog" aria-modal="true" aria-labelledby="v3-cm-title">
		<button type="button" class="v3-modal-x" data-close aria-label="Затвори">', v3_icon('close', 22), '</button>
		<h2 id="v3-cm-title">Твоите предпочитания</h2>
		<p class="v3-muted">Избери кои бисквитки да използваме. Можеш да промениш избора си по всяко време от футъра на сайта.</p>
		<div class="v3-pref"><span>', v3_icon('shield'), '</span><div><strong>Необходими бисквитки</strong><p>Нужни са за правилната работа на сайта (вход, език, кошница) и не могат да бъдат изключени.</p></div><em class="v3-chip">Винаги активни</em></div>
		<label class="v3-pref"><span>', v3_icon('chart'), '</span><div><strong>Аналитични бисквитки</strong><p>Помагат ни да разберем как се използва сайтът, за да го подобряваме (Google Analytics).</p></div><input type="checkbox" class="v3-switch" id="v3-c-analytics" /></label>
		<label class="v3-pref"><span>', v3_icon('tag'), '</span><div><strong>Маркетингови бисквитки</strong><p>Използват се за показване на по-подходящо съдържание и реклами.</p></div><input type="checkbox" class="v3-switch" id="v3-c-marketing" /></label>
		<p class="v3-small"><a href="', v3_url('/news/terms-and-conditions'), '">Условия и поверителност</a></p>
		<div class="v3-modal-btns">
			<button type="button" class="v3-btn v3-btn-orange" data-consent="save">Запази избора</button>
			<button type="button" class="v3-btn v3-btn-line" data-consent="none">Откажи незадължителните</button>
		</div>
	</div>
</div>
';
}
