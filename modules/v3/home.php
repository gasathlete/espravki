<?php
/* ==========================================================================
   v3 home page (design A01). Included from index.php.
   Uses only existing data: adverts, categories, products, articles, cities.
   ========================================================================== */
require_once dirname(__FILE__).'/layout.php';

function v3_q($sql){
	$r = mysql_query($sql) or die(send_error($sql,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$rows = array();
	while($row = mysql_fetch_assoc($r)) $rows[] = $row;
	return $rows;
}

function v3_advert_img($row){
	foreach(array('small_image','logo') as $k){
		if(!empty($row[$k])) return WebSite.'/images/adverts/'.$row[$k];
	}
	return WebSite.'/images/no_product_image.jpg';
}
function v3_product_img($img){
	return WebSite.'/images/products/'.(empty($img) ? 'no_product_image.jpg' : $img);
}
function v3_price($p){
	if($p === '' || $p === null || (float)$p <= 0) return '';
	return number_format((float)$p, 2, ',', ' ').' €';
}

/* ---- Showcase profile for the hero: a real listing that has products ---- */
$showcase = v3_q('SELECT a.id, a.supplier_name, a.link_name, a.small_image, a.logo, a.town, a.company_phones, pc.bg_category, pc.url, COUNT(p.id) AS n
	FROM adverts a
	JOIN adverts_to_product_categories atpc ON atpc.advert_id = a.id
	JOIN products_categories pc ON pc.id = atpc.category_id
	JOIN products p ON p.advert_id = a.id AND p.active = "1" AND p.deleted = "0" AND p.checked_by_admin = "1" AND p.image != ""
	WHERE a.active = "1" AND a.deleted = "0" AND a.sellbusiness = "0" AND (a.small_image != "" OR a.logo != "")
	GROUP BY a.id ORDER BY n DESC, a.added_date DESC LIMIT 1');
$showcase = $showcase ? $showcase[0] : null;
$showcase_products = array();
if($showcase){
	$showcase_products = v3_q('SELECT title, price, image, product_url, category_url FROM products
		WHERE advert_id = "'.(int)$showcase['id'].'" AND active = "1" AND deleted = "0" AND checked_by_admin = "1" AND image != ""
		ORDER BY addeddate DESC LIMIT 2');
}

/* ---- Top categories with a real photo ---- */
$top_cats = v3_q('SELECT pc.bg_category, pc.url, COUNT(DISTINCT a.id) AS n, MAX(a.small_image) AS img
	FROM products_categories pc
	JOIN adverts_to_product_categories atpc ON atpc.category_id = pc.id
	JOIN adverts a ON a.id = atpc.advert_id AND a.active = "1" AND a.deleted = "0"
	WHERE pc.bg_category != "Временна категория" AND pc.url != ""
	GROUP BY pc.id ORDER BY n DESC LIMIT 6');

/* ---- Latest listings / products / news (internal links help Google) ---- */
$latest = v3_q('SELECT a.supplier_name, a.link_name, a.small_image, a.logo, a.town, a.meta_description, pc.bg_category, pc.url
	FROM adverts a, products_categories pc, adverts_to_product_categories atpc
	WHERE a.active = "1" AND a.deleted = "0" AND a.sellbusiness = "0" AND a.id = atpc.advert_id AND atpc.category_id = pc.id
	GROUP BY a.id ORDER BY a.added_date DESC LIMIT 6');
$products = v3_q('SELECT p.title, p.price, p.image, p.product_url, p.category_url, a.supplier_name
	FROM products p, adverts a
	WHERE p.advert_id = a.id AND a.active = "1" AND p.active = "1" AND p.deleted = "0" AND p.checked_by_admin = "1"
	GROUP BY p.id ORDER BY p.addeddate DESC LIMIT 4');
$news = v3_q('SELECT unique_name, bg_article_title, bg_meta_description, small_image FROM articles
	WHERE active = "1" AND top_article = "1" ORDER BY added_date DESC LIMIT 3');

$cities = echo_cities();
$n_listings = total_listings();
// only offers a visitor can really open (old count included deleted ones)
$r_ = mysql_query('SELECT COUNT(*) FROM products p JOIN adverts a ON a.id = p.advert_id WHERE p.active = "1" AND p.sold = "0" AND p.deleted = "0" AND p.checked_by_admin = "1" AND a.active = "1" AND a.deleted = "0"'); $x_ = $r_ ? mysql_fetch_row($r_) : array(0); $n_products = (int)$x_[0];

/* ---- Meta (same SEO data as before) ---- */
$meta  = '<title>'.seo_attr($lang['meta_home']['title']).'</title>';
$meta .= '<meta name="description" content="'.seo_attr($lang['meta_home']['description']).'" />';
$meta .= seo_canonical_tags('/');
$meta .= '<meta property="og:type" content="website" /><meta property="og:title" content="'.seo_attr($lang['meta_home']['title']).'" />';
$meta .= '<meta property="og:description" content="'.seo_attr($lang['meta_home']['description']).'" />';
$meta .= '<meta property="og:image" content="'.WebSite.'/images/site_screen.jpg" /><meta property="og:site_name" content="'.ShortDomainName.'" />';
$meta .= '<meta name="verify-paysera" content="575ca41c780aa866ad404a67fec2370c" />';
$meta .= '<meta name="google-site-verification" content="rxKzfCyp_uHB7d8KM2tyfw3o_Xsn0dLNzgnO98apIM0" />';
$meta .= !empty($V3_PREVIEW) ? '<meta name="robots" content="noindex, follow" />' : '<meta name="robots" content="index, follow" />';
$meta .= '<script type="application/ld+json">'.str_replace('</', '<\/', json_encode(array(
	'@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => 'Espravki.com', 'url' => WebSite.'/',
	'potentialAction' => array('@type' => 'SearchAction', 'target' => WebSite.'/search/?search={search_term_string}', 'query-input' => 'required name=search_term_string')
), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)).'</script>';
// Organization + logo (Google uses it for the logo next to the site in search results)
$meta .= '<script type="application/ld+json">'.str_replace('</', '<\/', json_encode(array(
	'@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'Espravki.com', 'url' => WebSite.'/',
	'logo' => WebSite.'/assets/espravki/icon-512.png',
	'sameAs' => array_values(array_filter(array(facebook_page, instagram, tiktok), function($u){ return preg_match('#^https?://#', $u); }))
), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)).'</script>';

v3_head($meta);
v3_header();
?>

<!-- HERO -->
<section class="v3-hero">
	<div class="v3-wrap v3-hero-in">
		<div class="v3-hero-copy">
			<h1>От идея до публичен профил.<?php if(V3_NEW_PRICING){ ?><span class="v3-accent">Започни безплатно за 30 дни.</span><?php }else{ ?><span class="v3-accent">Покажи бизнеса си онлайн.</span><?php } ?></h1>
			<p class="v3-lead">За фирми, специалисти и хора с нещо стойностно за предлагане.</p>
			<div class="v3-hero-actions">
				<a class="v3-btn v3-btn-orange v3-btn-lg" href="<?php echo v3_cta_url(); ?>"><?php echo v3_cta_label(), ' ', v3_icon('arrow'); ?></a>
				<a class="v3-link-light" href="<?php echo v3_url('/business-directory/'); ?>">Разгледай каталога <?php echo v3_icon('arrow', 18); ?></a>
			</div>
			<p class="v3-hero-note"><?php echo number_format((int)$n_listings, 0, ',', ' '); ?>+ фирми и <?php echo number_format((int)$n_products, 0, ',', ' '); ?>+ продукта в каталога.</p>
			<a class="v3-hero-about" href="<?php echo v3_url('/contacts/'); ?>">Кои сме ние и защо го правим <?php echo v3_icon('chev', 14); ?></a>
		</div>

		<?php if($showcase){ $sc_url = '/business-directory/'.$showcase['url'].'/'.$showcase['link_name']; ?>
		<article class="v3-showcase" aria-label="Примерен профил от каталога">
			<span class="v3-showcase-tag">Профил от каталога</span>
			<div class="v3-showcase-cover"><img src="<?php echo v3_e(v3_advert_img($showcase)); ?>" alt="<?php echo v3_t($showcase['supplier_name']); ?>" loading="eager" /></div>
			<div class="v3-showcase-head">
				<div>
					<h2><a href="<?php echo v3_url($sc_url); ?>"><?php echo v3_t($showcase['supplier_name']); ?></a></h2>
					<p><?php echo v3_t($showcase['bg_category']); ?><?php if($showcase['town']) echo ' · ', v3_icon('pin', 14), ' ', v3_t($showcase['town']); ?></p>
				</div>
				<div class="v3-showcase-btns">
					<?php if($showcase['company_phones']){ $ph = explode(';', $showcase['company_phones']); ?><a class="v3-btn v3-btn-blue v3-btn-sm" href="tel:<?php echo v3_e(preg_replace('/[^0-9+]/', '', $ph[0])); ?>"><?php echo v3_icon('phone', 16); ?> Обади се</a><?php } ?>
					<a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_url($sc_url); ?>"><?php echo v3_icon('chat', 16); ?> Виж профила</a>
				</div>
			</div>
			<?php if($showcase_products){ ?>
			<div class="v3-showcase-offers">
				<?php foreach($showcase_products as $sp){ ?>
				<a class="v3-mini-offer" href="<?php echo v3_url('/products/'.$sp['category_url'].'/'.$sp['product_url']); ?>">
					<img src="<?php echo v3_e(v3_product_img($sp['image'])); ?>" alt="" loading="lazy" />
					<span><strong><?php echo v3_excerpt($sp['title'], 38); ?></strong><?php $pr = v3_price($sp['price']); if($pr) echo '<em>', $pr, '</em>'; ?><u>Виж повече</u></span>
				</a>
				<?php } ?>
			</div>
			<?php } ?>
		</article>
		<?php } ?>
	</div>
</section>

<!-- 3 STEPS -->
<section class="v3-section" id="kak-raboti">
	<div class="v3-wrap">
		<h2 class="v3-h2">Три стъпки. Твоята страница е готова.</h2>
		<ol class="v3-steps">
			<li>
				<span class="v3-step-n">1</span>
				<div><h3>Избираш дейност</h3><p>Избери категория, която най-добре описва това, което предлагаш.</p>
				<div class="v3-mini-form"><label>Избери категория</label><span class="v3-fake-input"><?php echo v3_icon('grid', 16); ?> <?php echo $top_cats ? v3_t($top_cats[0]['bg_category']) : 'Категория'; ?></span></div></div>
			</li>
			<li>
				<span class="v3-step-n">2</span>
				<div><h3>Попълваш профила</h3><p>Въведи основната информация: име, град, контакти и снимки.</p>
				<div class="v3-mini-form"><label>Основна информация</label><span class="v3-fake-input">Име на бизнеса</span><span class="v3-fake-input"><?php echo v3_icon('pin', 16); ?> Град</span></div></div>
			</li>
			<li>
				<span class="v3-step-n">3</span>
				<div><h3>Добавяш продукт или услуга</h3><p>Опиши какво предлагаш, добави цена и снимки.</p>
				<div class="v3-mini-form"><label>Ново предложение</label><span class="v3-fake-input">Име на предложението</span><span class="v3-fake-row"><span class="v3-fake-input">0,00 €</span><span class="v3-fake-btn">Добави</span></span></div></div>
			</li>
		</ol>
		<p class="v3-center"><a class="v3-btn v3-btn-orange" href="<?php echo v3_cta_url(); ?>"><?php echo v3_cta_label(), ' ', v3_icon('arrow'); ?></a></p>
	</div>
</section>

<!-- DISCOVER -->
<section class="v3-section v3-tint">
	<div class="v3-wrap">
		<h2 class="v3-h2">Открий бизнеси и услуги</h2>
		<form class="v3-search" action="<?php echo v3_url('/search/'); ?>" method="get" role="search">
			<label class="v3-field"><span class="v3-sr">Какво търсиш?</span><?php echo v3_icon('search'); ?><input type="search" name="search" placeholder="Какво търсиш? Напр. счетоводител, автосервиз" autocomplete="off" /></label>
			<label class="v3-field"><span class="v3-sr">Град</span><?php echo v3_icon('pin'); ?><input type="text" name="search_city" list="v3-cities" placeholder="Град" autocomplete="off" /></label>
			<datalist id="v3-cities"><?php foreach($cities as $c) echo '<option value="', v3_e($c), '">'; ?></datalist>
			<input type="hidden" name="exact_match" value="0" />
			<input type="hidden" name="city_match" value="1" />
			<button class="v3-btn v3-btn-blue" type="submit"><?php echo v3_icon('search', 18); ?> Търси</button>
		</form>
		<?php if($top_cats){ ?>
		<div class="v3-cat-grid">
			<?php foreach($top_cats as $c){ ?>
			<a class="v3-cat-card" href="<?php echo v3_url('/business-directory/'.$c['url'].'/'); ?>">
				<span class="v3-cat-img"><img src="<?php echo v3_e($c['img'] ? WebSite.'/images/adverts/'.$c['img'] : WebSite.'/images/no_product_image.jpg'); ?>" alt="" loading="lazy" /></span>
				<span class="v3-cat-body"><strong><?php echo v3_t($c['bg_category']); ?></strong><small><?php echo (int)$c['n']; ?> <?php echo $c['n'] == 1 ? 'фирма' : 'фирми'; ?></small><em>Разгледай</em></span>
			</a>
			<?php } ?>
		</div>
		<p class="v3-center"><a class="v3-btn v3-btn-line" href="<?php echo v3_url('/business-directory/'); ?>">Всички категории <?php echo v3_icon('arrow', 18); ?></a></p>
		<?php } ?>
	</div>
</section>

<!-- PROFILE THAT WORKS -->
<section class="v3-section">
	<div class="v3-wrap v3-split">
		<div>
			<h2 class="v3-h2">Профил, който работи за теб.</h2>
			<ul class="v3-ticks">
				<li><?php echo v3_icon('check'); ?> Покажи какво предлагаш – продукти, услуги, цени и снимки.</li>
				<li><?php echo v3_icon('check'); ?> Дай ясен начин за контакт – телефон, имейл, съобщение, карта.</li>
				<li><?php echo v3_icon('check'); ?> Проследи интереса – статистика за посещенията на профила.</li>
				<li><?php echo v3_icon('check'); ?> Открит в Google – всяка фирма има собствена страница в каталога.</li>
			</ul>
			<a class="v3-btn v3-btn-orange" href="<?php echo v3_cta_url(); ?>"><?php echo v3_cta_label(), ' ', v3_icon('arrow'); ?></a>
		</div>
		<?php if($products){ ?>
		<div class="v3-card v3-offer-list">
			<h3>Последни предложения</h3>
			<?php foreach($products as $p){ ?>
			<a class="v3-offer-row" href="<?php echo v3_url('/products/'.$p['category_url'].'/'.$p['product_url']); ?>">
				<img src="<?php echo v3_e(v3_product_img($p['image'])); ?>" alt="" loading="lazy" />
				<span><strong><?php echo v3_excerpt($p['title'], 60); ?></strong><small><?php echo v3_t($p['supplier_name']); ?></small></span>
				<em><?php echo v3_price($p['price']); ?></em><?php echo v3_icon('chev', 18); ?>
			</a>
			<?php } ?>
			<a class="v3-more" href="<?php echo v3_url('/products/'); ?>">Всички продукти и услуги <?php echo v3_icon('arrow', 16); ?></a>
		</div>
		<?php } ?>
	</div>
</section>

<?php if($latest){ ?>
<!-- LATEST LISTINGS -->
<section class="v3-section v3-tint">
	<div class="v3-wrap">
		<div class="v3-head-row"><h2 class="v3-h2">Последно добавени фирми</h2><a class="v3-more" href="<?php echo v3_url('/business-directory/'); ?>">Виж всички <?php echo v3_icon('arrow', 16); ?></a></div>
		<div class="v3-listing-grid">
			<?php foreach($latest as $l){ $lu = '/business-directory/'.$l['url'].'/'.$l['link_name']; ?>
			<article class="v3-listing">
				<a class="v3-listing-img" href="<?php echo v3_url($lu); ?>"><img src="<?php echo v3_e(v3_advert_img($l)); ?>" alt="<?php echo v3_t($l['supplier_name']); ?>" loading="lazy" /></a>
				<div class="v3-listing-body">
					<a class="v3-listing-cat" href="<?php echo v3_url('/business-directory/'.$l['url'].'/'); ?>"><?php echo v3_t($l['bg_category']); ?></a>
					<h3><a href="<?php echo v3_url($lu); ?>"><?php echo v3_t($l['supplier_name']); ?></a></h3>
					<?php if($l['town']){ ?><p class="v3-muted"><?php echo v3_icon('pin', 15), ' ', v3_t($l['town']); ?></p><?php } ?>
					<p><?php echo v3_excerpt($l['meta_description'], 110); ?></p>
				</div>
			</article>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>

<?php if(V3_NEW_PRICING){ ?>
<!-- PRICING (shown only after stage 5 goes live) -->
<section class="v3-section" id="ceni">
	<div class="v3-wrap">
		<h2 class="v3-h2">Всичко включено. Ясна цена.</h2>
		<p class="v3-sub">Един абонамент. Еднакви функции.</p>
		<div class="v3-prices">
			<div class="v3-price"><b>14,99 €</b><span>/ месец</span><small>Гъвкав месечен план.</small></div>
			<div class="v3-price v3-price-best"><em>Най-изгодно</em><b>89,99 €</b><span>/ година</span><small class="v3-orange">Спестяваш близо 50%.</small></div>
			<div class="v3-price"><b>24,99 €</b><span>/ първите 3 платени месеца</span><small>Начална оферта.</small></div>
		</div>
		<p class="v3-center v3-price-cta"><strong>Първите 30 дни са безплатни.</strong> <a class="v3-btn v3-btn-orange" href="<?php echo v3_cta_url(); ?>">Започни безплатно <?php echo v3_icon('arrow'); ?></a></p>
		<p class="v3-center v3-small">Началната оферта е еднократна за нови потребители.</p>
	</div>
</section>
<?php } ?>

<?php if($news){ ?>
<!-- NEWS -->
<section class="v3-section">
	<div class="v3-wrap">
		<div class="v3-head-row"><h2 class="v3-h2">Новини и полезно</h2><a class="v3-more" href="<?php echo v3_url('/news/'); ?>">Всички новини <?php echo v3_icon('arrow', 16); ?></a></div>
		<div class="v3-news-grid">
			<?php foreach($news as $n){ ?>
			<article class="v3-news">
				<a class="v3-news-img" href="<?php echo v3_url('/news/'.$n['unique_name']); ?>"><img src="<?php echo v3_e(WebSite.'/images/articles/'.($n['small_image'] ? $n['small_image'] : 'no_product_image.jpg')); ?>" alt="" loading="lazy" /></a>
				<div><span class="v3-eyebrow">Новини</span><h3><a href="<?php echo v3_url('/news/'.$n['unique_name']); ?>"><?php echo v3_t($n['bg_article_title']); ?></a></h3><p><?php echo v3_excerpt($n['bg_meta_description'], 120); ?></p></div>
			</article>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>

<!-- FAQ -->
<section class="v3-section v3-tint">
	<div class="v3-wrap v3-narrow">
		<h2 class="v3-h2">Често задавани въпроси</h2>
		<details class="v3-faq"><summary>Как да добавя бизнеса си?</summary><p>Натисни „<?php echo v3_cta_label(); ?>“, избери план, създай профил и попълни данните на бизнеса си – име, категория, град, контакти и снимки.</p></details>
		<details class="v3-faq"><summary>Как ме намират клиентите?</summary><p>Всеки профил има собствена страница в каталога, която се показва при търсене в сайта и може да бъде открита в Google. Клиентите виждат контактите ти и могат да ти пишат директно.</p></details>
		<details class="v3-faq"><summary>Мога ли да добавя продукти и услуги?</summary><p>Да. След като профилът ти е активен, от „Моят профил“ можеш да добавяш продукти и услуги със снимки и цени.</p></details>
		<details class="v3-faq"><summary>Виждам ли кой разглежда профила ми?</summary><p>Да. В профила си имаш статистика за посещенията и обажданията.</p></details>
	</div>
</section>

<?php v3_footer(); ?>
