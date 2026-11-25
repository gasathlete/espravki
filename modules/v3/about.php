<?php
/* ==========================================================================
   v3 "За нас" (about + contacts). Included from contacts/index.php.
   The contact form posts to the existing ajax/contactus.php (same fields).
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

function v3_about_n($sql){ $r = mysql_query($sql); if(!$r) return 0; $x = mysql_fetch_row($r); return (int)$x[0]; }
$n_biz    = v3_about_n('SELECT COUNT(*) FROM adverts WHERE active = "1" AND deleted = "0"');
$n_prod   = v3_about_n('SELECT COUNT(*) FROM products p JOIN adverts a ON a.id = p.advert_id WHERE p.active = "1" AND p.sold = "0" AND p.deleted = "0" AND p.checked_by_admin = "1" AND a.active = "1" AND a.deleted = "0"');   // offers a visitor can really open
$n_towns  = v3_about_n('SELECT COUNT(DISTINCT town) FROM adverts WHERE active = "1" AND deleted = "0" AND town != ""');
$n_cats   = v3_about_n('SELECT COUNT(DISTINCT t.category_id) FROM adverts_to_product_categories t JOIN adverts a ON a.id = t.advert_id AND a.active = "1" AND a.deleted = "0" JOIN products_categories pc ON pc.id = t.category_id AND pc.visible = "1"');
function v3_num($n){ return number_format((int)$n, 0, ',', ' '); }

$fn = ''; $ln = ''; $em = ''; $ph = '';
if(!empty($_SESSION['customer_names'])){ $nm = preg_split('~\s+~', trim($_SESSION['customer_names'])); $fn = $nm[0]; $ln = isset($nm[1]) ? $nm[1] : ''; }
if(!empty($_SESSION['customer_email'])) $em = $_SESSION['customer_email'];
if(!empty($_SESSION['customer_phone'])) $ph = $_SESSION['customer_phone'];

$desc = 'Espravki.com е национален бизнес каталог, който помага на малкия и средния бизнес в България да се рекламира достъпно: профил, продукти, цени и контакти на едно място, без такси за обява.';
$meta  = '<title>За нас | Espravki.com – Национален бизнес каталог</title>';
$meta .= '<meta name="description" content="'.v3_e($desc).'" />';
$meta .= '<meta property="og:title" content="За нас | Espravki.com" /><meta property="og:type" content="website" /><meta property="og:description" content="'.v3_e($desc).'" />';
$meta .= '<meta property="og:image" content="'.v3_e(WebSite.'/assets/espravki/icon-512.png').'" />';
$meta .= !empty($V3_PREVIEW) ? '<meta name="robots" content="noindex, follow" />' : '<meta name="robots" content="index, follow" />'.seo_canonical_tags('/contacts/');
$ld = array('@context' => 'https://schema.org', '@type' => 'AboutPage', 'name' => 'За нас', 'url' => WebSite.'/contacts/',
	'mainEntity' => array('@type' => 'Organization', 'name' => 'Espravki.com', 'legalName' => 'Иванов & Милев Девелъпмънт ЕООД', 'url' => WebSite.'/',
		'logo' => WebSite.'/assets/espravki/icon-512.png', 'email' => 'eespravki@gmail.com', 'telephone' => site_phone,
		'address' => array('@type' => 'PostalAddress', 'streetAddress' => 'бул. България 13', 'addressLocality' => 'София', 'addressCountry' => 'BG')));
$meta .= '<script type="application/ld+json">'.str_replace('</', '<\/', json_encode($ld)).'</script>';

v3_head($meta);
v3_header();
?>
<section class="v3-about-hero">
	<div class="v3-wrap v3-about-hero-in">
		<div>
			<p class="v3-eyebrow">За Espravki.com</p>
			<h1>Реклама, която малкият бизнес <span class="v3-accent">може да си позволи.</span></h1>
			<p class="v3-lead">Espravki.com е национален бизнес каталог. Даваме на всяка фирма, занаятчия и специалист в България професионална онлайн витрина: снимки, продукти, цени, контакти и отзиви на едно място. Без агенция, без такса за всяка обява, без посредник между теб и клиента.</p>
			<div class="v3-hero-actions">
				<a class="v3-btn v3-btn-orange v3-btn-lg" href="<?php echo v3_cta_url(); ?>"><?php echo v3_cta_label(), ' ', v3_icon('arrow'); ?></a>
				<a class="v3-link-light" href="#kontakti">Свържи се с нас <?php echo v3_icon('arrow', 18); ?></a>
			</div>
		</div>
		<dl class="v3-about-stats">
			<div><dt>фирми в каталога</dt><dd><?php echo v3_num($n_biz); ?></dd></div>
			<div><dt>продукта и услуги</dt><dd><?php echo v3_num($n_prod); ?></dd></div>
			<div><dt>града и села</dt><dd><?php echo v3_num($n_towns); ?></dd></div>
			<div><dt>категории дейности</dt><dd><?php echo v3_num($n_cats); ?></dd></div>
		</dl>
	</div>
</section>

<section class="v3-section">
	<div class="v3-wrap">
		<div class="v3-about-intro">
			<h2 class="v3-h2">Защо съществуваме</h2>
			<p class="v3-muted">Две неща ни движат. Всичко в Espravki.com, от цената до бутоните, служи на тях.</p>
		</div>
		<div class="v3-about-pillars">
			<article class="v3-card">
				<span class="v3-about-ic"><?php echo v3_icon('tag', 26); ?></span>
				<h3>Достъпна реклама за малкия и средния бизнес</h3>
				<p>Шивачката в Търговище, майсторът на ремонти в Плевен и семейната пекарна в Бургас заслужават да бъдат намерени онлайн. Не им трябва сайт за хиляди левове или маркетинг бюджет. Профилът в Espravki.com е тяхната витрина и работи като фирмен сайт и онлайн магазин.</p>
			</article>
			<article class="v3-card">
				<span class="v3-about-ic v3-about-ic-o"><?php echo v3_icon('shield', 26); ?></span>
				<h3>Алтернатива на скъпите сайтове за обяви</h3>
				<p>Големите сайтове за обяви вдигат цените всяка година, таксуват всяко изкачване и всяка снимка и правят бизнеса зависим от тях. При нас има една ясна цена. Не взимаме комисиона, не крием контактите ти и не стоим между теб и клиента.</p>
			</article>
		</div>
	</div>
</section>

<section class="v3-section v3-tint">
	<div class="v3-wrap">
		<div class="v3-about-intro">
			<h2 class="v3-h2">Каталог или сайт за обяви: разликата</h2>
		</div>
		<div class="v3-about-table-wrap v3-card">
			<table class="v3-about-table">
				<thead><tr><th scope="col"></th><th scope="col">Espravki.com</th><th scope="col">Типичен сайт за обяви</th></tr></thead>
				<tbody>
					<tr><th scope="row">Какво получаваш</th><td>Цял фирмен профил: описание, снимки, продукти, цени, карта, отзиви</td><td>Отделни обяви, които изтичат</td></tr>
					<tr><th scope="row">Цена</th><td>Една месечна или годишна сума, ясна предварително</td><td>Плащаш за обява, изкачване, снимки и „ТОП“ позиция</td></tr>
					<tr><th scope="row">Комисиона от продажбите</th><td>Няма</td><td>Често има</td></tr>
					<tr><th scope="row">Контакт с клиента</th><td>Директно: телефонът и имейлът ти са видими</td><td>Често през платформата</td></tr>
					<tr><th scope="row">Видимост в Google</th><td>Профилът и всеки продукт имат собствена страница</td><td>Обявата изчезва, когато изтече</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="v3-section">
	<div class="v3-wrap">
		<div class="v3-about-intro">
			<h2 class="v3-h2">Какво получава всеки бизнес</h2>
		</div>
		<ul class="v3-about-features">
			<li><?php echo v3_icon('user', 22); ?><div><strong>Фирмен профил</strong><span>Описание, лого, снимки, работно време, адрес и карта.</span></div></li>
			<li><?php echo v3_icon('tag', 22); ?><div><strong>Продукти и услуги с цени</strong><span>Всеки продукт със собствена страница, снимки и бутон за поръчка.</span></div></li>
			<li><?php echo v3_icon('phone', 22); ?><div><strong>Директни обаждания и запитвания</strong><span>Клиентът ти звъни или пише направо. Запитванията идват в профила ти.</span></div></li>
			<li><?php echo v3_icon('chart', 22); ?><div><strong>Статистика</strong><span>Колко хора са видели профила ти и колко са се обадили.</span></div></li>
			<li><?php echo v3_icon('chat', 22); ?><div><strong>Отзиви</strong><span>Доволните клиенти оставят оценка, която новите виждат.</span></div></li>
			<li><?php echo v3_icon('search', 22); ?><div><strong>Намиране в Google</strong><span>Страниците са подготвени Google да показва адрес, телефон и карта.</span></div></li>
		</ul>
		<p class="v3-center"><a class="v3-btn v3-btn-line" href="<?php echo v3_url('/select-plan/'); ?>">Виж цените <?php echo v3_icon('arrow', 18); ?></a></p>
	</div>
</section>

<section class="v3-section v3-tint">
	<div class="v3-wrap v3-about-who">
		<div>
			<h2 class="v3-h2">Кои сме ние</h2>
			<p>Espravki.com се прави от малък екип в София, който вярва, че българският бизнес заслужава честна и евтина реклама. Сайтът е собственост на <strong>Иванов &amp; Милев Девелъпмънт ЕООД</strong> (ЕИК 206957326).</p>
			<p>Ако ти е трудно да направиш профила си сам, обади ни се или ни пиши. Ще го направим вместо теб.</p>
			<p class="v3-about-note"><?php echo v3_icon('shield', 18); ?> Благотворителни организации, сдружения с нестопанска цел и държавни институции получават профил безплатно. Регистрирай се и ни пиши.</p>
		</div>
		<div class="v3-card v3-about-partner">
			<h3>Работиш в маркетинга?</h3>
			<p>Ако имаш опит или контакти и искаш да работим заедно по този или други наши проекти, изпрати ни кратко представяне или CV през формата по-долу.</p>
		</div>
	</div>
</section>

<section class="v3-section" id="kontakti">
	<div class="v3-wrap v3-about-contact">
		<div>
			<h2 class="v3-h2">Контакти</h2>
			<dl class="v3-contact-list">
				<div><dt><?php echo v3_icon('phone', 18); ?> Телефон</dt><dd><a href="tel:<?php echo v3_e(site_phone); ?>"><?php echo v3_e(site_phone); ?></a></dd></div>
				<div><dt><?php echo v3_icon('mail', 18); ?> Имейл</dt><dd><a href="mailto:eespravki@gmail.com">eespravki@gmail.com</a></dd></div>
				<div><dt><?php echo v3_icon('pin', 18); ?> Адрес</dt><dd>гр. София, бул. България 13</dd></div>
				<div><dt><?php echo v3_icon('clock', 18); ?> Работно време</dt><dd>Понеделник – петък, 09:00 – 18:00</dd></div>
			</dl>
			<div class="v3-social v3-about-social">
				<a href="<?php echo v3_e(facebook_page); ?>" target="_blank" rel="noopener" aria-label="Facebook"><?php echo v3_icon('fb', 18); ?></a>
				<a href="<?php echo v3_e(instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram"><?php echo v3_icon('ig', 18); ?></a>
				<a href="<?php echo v3_e(tiktok); ?>" target="_blank" rel="noopener" aria-label="TikTok"><?php echo v3_icon('tt', 18); ?></a>
			</div>
		</div>
		<form class="v3-form v3-card v3-about-form" id="v3-about-form" novalidate>
			<h3>Пиши ни</h3>
			<div class="v3-form-row">
				<label><span>Име</span><input type="text" id="ab-name" autocomplete="given-name" value="<?php echo v3_e($fn); ?>" required /></label>
				<label><span>Фамилия</span><input type="text" id="ab-fname" autocomplete="family-name" value="<?php echo v3_e($ln); ?>" required /></label>
			</div>
			<div class="v3-form-row">
				<label><span>Имейл</span><input type="email" id="ab-mail" autocomplete="email" value="<?php echo v3_e($em); ?>" required /></label>
				<label><span>Телефон</span><input type="tel" id="ab-phone" autocomplete="tel" value="<?php echo v3_e($ph); ?>" required /></label>
			</div>
			<label><span>Относно</span>
				<select id="ab-subject" required>
					<option value="0">Избери тема</option>
					<option value="1">Регистрация на фирмен профил или оферта</option>
					<option value="2">Предложение за съвместен бизнес</option>
					<option value="3">Технически проблем</option>
					<option value="4">Въпрос за плащане</option>
					<option value="5">Друго</option>
				</select>
			</label>
			<label><span>Съобщение</span><textarea id="ab-message" rows="5" required placeholder="Поне 20 знака"></textarea></label>
			<label><span>Прикачи файл (по желание: снимка, PDF или Word до 3 MB)</span><input type="file" id="ab-file" accept=".png,.jpg,.jpeg,.pdf,.doc,.ppt" /></label>
			<p class="v3-form-msg" id="ab-msg" role="status" aria-live="polite"></p>
			<button class="v3-btn v3-btn-blue v3-btn-block" type="submit">Изпрати</button>
		</form>
	</div>
</section>

<section class="v3-section v3-about-cta">
	<div class="v3-wrap v3-center">
		<h2 class="v3-h2">Покажи бизнеса си онлайн още днес.</h2>
		<p>Профилът се прави за няколко минути.</p>
		<a class="v3-btn v3-btn-orange v3-btn-lg" href="<?php echo v3_cta_url(); ?>"><?php echo v3_cta_label(), ' ', v3_icon('arrow'); ?></a>
	</div>
</section>
<script>
(function(){
	var f = document.getElementById('v3-about-form'); if(!f) return;
	var msg = document.getElementById('ab-msg');
	function v(id){ return document.getElementById(id).value.replace(/^\s+|\s+$/g, ''); }
	function bad(id, text){ var el = document.getElementById(id); el.setAttribute('aria-invalid', 'true'); el.focus(); msg.className = 'v3-form-msg is-err'; msg.textContent = text; return false; }
	f.addEventListener('submit', function(e){
		e.preventDefault();
		[].forEach.call(f.querySelectorAll('[aria-invalid]'), function(x){ x.removeAttribute('aria-invalid'); });
		var name = v('ab-name'), fname = v('ab-fname'), mail = v('ab-mail'), phone = v('ab-phone').replace(/[^0-9]/g, ''), subject = v('ab-subject'), message = v('ab-message');
		var file = document.getElementById('ab-file').files[0];
		if(name.length < 2) return bad('ab-name', 'Напиши името си.');
		if(fname.length < 2) return bad('ab-fname', 'Напиши фамилията си.');
		if(!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(mail)) return bad('ab-mail', 'Провери имейла.');
		if(phone.length < 8) return bad('ab-phone', 'Провери телефона (поне 8 цифри).');
		if(subject === '0') return bad('ab-subject', 'Избери тема.');
		if(message.length < 20) return bad('ab-message', 'Съобщението трябва да е поне 20 знака.');
		if(file && file.size > 3000000) return bad('ab-file', 'Файлът е над 3 MB.');
		var fd = new FormData();
		fd.append('name', name); fd.append('fname', fname); fd.append('mail', mail); fd.append('phone', phone);
		fd.append('subject', subject); fd.append('message', message); if(file) fd.append('filetoup', file);
		var btn = f.querySelector('button[type=submit]'); btn.disabled = true; btn.textContent = 'Изпращане…';
		var x = new XMLHttpRequest();
		x.open('POST', '/ajax/contactus.php', true);
		x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		x.onreadystatechange = function(){
			if(x.readyState !== 4) return;
			var r = null; try { r = JSON.parse(x.responseText); } catch(err){}
			if(r && r[1] == 1){ f.innerHTML = '<h3>Благодарим ти!</h3><p>Съобщението е изпратено. Ще ти отговорим до един работен ден.</p>'; return; }
			btn.disabled = false; btn.textContent = 'Изпрати';
			msg.className = 'v3-form-msg is-err';
			msg.innerHTML = (r && r[0]) ? r[0] : 'Нещо не се получи. Опитай пак или ни се обади.';
		};
		x.send(fd);
	});
})();
</script>
<?php v3_footer(); ?>
