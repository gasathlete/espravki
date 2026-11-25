<?php
/* ==========================================================================
   v3 business profile (design A11). Included from business-directory/index.php
   and business-for-sale/index.php when a single listing is opened.
   Expects: $advertrow, $aid, $categoryname, $categorylink, $meta_info, $lang,
            $v3_section ('business-directory' | 'business-for-sale').
   All interactive features post to the SAME endpoints as the old page:
   ajax/save_call.php, ajax/link_clicker.php, ajax/contact_trader.php, ajax/votes.php
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$a = $advertrow;
$section = isset($v3_section) ? $v3_section : 'business-directory';
$name = v3_t($a['supplier_name']);
$is_owner = isset($_SESSION['customer']) && $_SESSION['customer'] == $a['customer_id'];
$public_service = in_array($categoryname, array('ТД на НАП','Посолства','Агенции за социално подпомагане','Детски градини'));
$has_place = ($a['business_type'] != 2);
$has_geo = $has_place && is_numeric($a['latitude']) && is_numeric($a['longitude']) && (float)$a['latitude'] != 0;
$site_allowed = ($a['selected_plan'] > 1 || $a['vip'] == '1') && preg_match('#^https?://#i', $a['site']);

/* phones */
$phones = array();
foreach(explode(';', (string)$a['company_phones']) as $ph){ $ph = trim($ph); if($ph !== '') $phones[] = $ph; }
function v3_tel($p){ return preg_replace('/[^0-9+]/', '', $p); }

/* address */
// add area / town only when the street address does not already contain them
function v3_lc($s){ return function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s); }
$address = trim(html_entity_decode(strip_tags((string)$a['company_address']), ENT_QUOTES, 'UTF-8'), " ,");
foreach(array('area','town') as $k){
	$part = trim(html_entity_decode(strip_tags((string)$a[$k]), ENT_QUOTES, 'UTF-8'));
	if($part !== '' && strpos(v3_lc($address), v3_lc($part)) === false) $address .= ($address !== '' ? ', ' : '').$part;
}
$address = v3_e($address);

/* working time */
$days = @unserialize($a['work_days']);
if(!is_array($days)) $days = array();
$hours = ($a['opening'] == '00:00' && $a['closing'] == '00:00') ? 'Денонощно' : v3_e($a['opening'].' – '.$a['closing']);
$open_now = null;
if(!empty($days)){
	$today = strtolower(date('D'));
	$map = array('mon'=>'mon','tue'=>'tue','wed'=>'wed','thu'=>'thu','fri'=>'fri','sat'=>'sat','sun'=>'sun');
	$works_today = in_array($today, $days);
	if($a['opening'] == '00:00' && $a['closing'] == '00:00') $open_now = $works_today;
	else{
		$now = time(); $b = strtotime($a['opening']); $e = strtotime($a['closing']);
		$open_now = $works_today && (($b < $e && $now >= $b && $now <= $e) || ($b > $e && ($now >= $b || $now <= $e)));
	}
}

/* images */
$gallery = get_advert_images($aid);
$cover = '';
foreach($gallery as $g){ $cover = WebSite.'/images/adverts/'.$g['image']; break; }
if(!$cover && !empty($a['small_image'])) $cover = WebSite.'/images/adverts/'.$a['small_image'];
$logo = !empty($a['logo']) ? WebSite.'/images/adverts/'.$a['logo'] : '';

/* reviews: only approved, real average (no fake 5 stars) */
$reviews = array();
$r = mysql_query('SELECT voted, username, comment, UNIX_TIMESTAMP(addeddate) AS t FROM statistics_adverts_visits
	WHERE advert_id = "'.(int)$aid.'" AND approved = "1" AND comment != "" ORDER BY addeddate DESC LIMIT 30');
if($r) while($row = mysql_fetch_assoc($r)) $reviews[] = $row;
$avg = 0; $nvotes = 0;
$r = mysql_query('SELECT AVG(voted) AS a, COUNT(*) AS n FROM statistics_adverts_visits WHERE advert_id = "'.(int)$aid.'" AND approved = "1" AND voted > 0');
if($r && ($row = mysql_fetch_assoc($r))){ $avg = (float)$row['a']; $nvotes = (int)$row['n']; }
$already_voted = false;
$r = mysql_query('SELECT id FROM statistics_adverts_visits WHERE advert_id = "'.(int)$aid.'" AND ip = "'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'" AND (voted <> 0 OR comment != "") LIMIT 1');
if($r && mysql_num_rows($r)) $already_voted = true;

/* products */
$offers = array();
$r = mysql_query('SELECT id, title, price, image, product_url, category_url, category_name FROM products
	WHERE advert_id = "'.(int)$aid.'" AND active = "1" AND deleted = "0" AND checked_by_admin = "1" ORDER BY addeddate DESC LIMIT 24');
if($r) while($row = mysql_fetch_assoc($r)) $offers[] = $row;

$docs = get_advert_docs($a['id']);
$tags = array();
if(!empty($a['meta_keywords'])) foreach(explode(',', $a['meta_keywords']) as $t){ $t = trim(html_entity_decode($t, ENT_QUOTES, 'UTF-8')); if($t !== '' && !in_array($t, $tags)) $tags[] = $t; }

/* description: stored HTML-escaped with a whitelist of tags at save time */
$desc = strip_tags(htmlspecialchars_decode($a['description']), '<p><br><b><strong><i><em><ul><ol><li><h2><h3><h4>');
$desc = preg_replace('/\s(on\w+|style)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $desc);

/* contact form prefill */
$pre_names = isset($_SESSION['customer_names']) ? $_SESSION['customer_names'] : '';
$pre_email = isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : '';
$pre_phone = isset($_SESSION['customer_phone']) ? $_SESSION['customer_phone'] : '';

$profile_url = WebSite.'/'.$section.'/'.$categorylink.'/'.$a['link_name'];
$days_bg = array('mon'=>'Понеделник','tue'=>'Вторник','wed'=>'Сряда','thu'=>'Четвъртък','fri'=>'Петък','sat'=>'Събота','sun'=>'Неделя');

function v3_stars($v){
	$out = '<span class="v3-stars" aria-label="'.number_format($v, 1, ',', '').' от 5">';
	for($i = 1; $i <= 5; $i++) $out .= '<i class="'.($v >= $i - .25 ? 'on' : '').'">★</i>';
	return $out.'</span>';
}

/* breadcrumb structured data */
$crumbs = array(
	array('Бизнеси', WebSite.'/business-directory/'),
	array($categoryname, WebSite.'/'.$section.'/'.$categorylink.'/'),
	array(html_entity_decode($a['supplier_name'], ENT_QUOTES, 'UTF-8'), $profile_url)
);
$ld = array('@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>array());
foreach($crumbs as $i => $c) $ld['itemListElement'][] = array('@type'=>'ListItem','position'=>$i+1,'name'=>$c[0],'item'=>$c[1]);
$extra = '<script type="application/ld+json">'.str_replace('</', '<\/', json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)).'</script>';
if(!empty($V3_PREVIEW)) $meta_info = str_replace('<meta name="robots" content="index, follow" />', '<meta name="robots" content="noindex, follow" />', $meta_info);

v3_head($meta_info, $extra);
v3_header();
?>
<div class="v3-wrap v3-profile">
	<nav class="v3-crumbs" aria-label="Навигация">
		<a href="<?php echo v3_url('/business-directory/'); ?>">Бизнеси</a> <span>/</span>
		<a href="<?php echo v3_url('/'.$section.'/'.$categorylink.'/'); ?>"><?php echo v3_e($categoryname); ?></a>
		<?php if($a['town']){ ?><span>/</span> <span><?php echo v3_t($a['town']); ?></span><?php } ?>
	</nav>

	<?php if($cover){ ?>
	<div class="v3-cover"><img src="<?php echo v3_e($cover); ?>" alt="<?php echo $name; ?>" /></div>
	<?php } ?>

	<div class="v3-profile-grid">
		<div class="v3-profile-main">
			<header class="v3-profile-head">
				<?php if($logo){ ?><img class="v3-profile-logo" src="<?php echo v3_e($logo); ?>" alt="" /><?php } ?>
				<div>
					<h1><?php echo $name; ?></h1>
					<?php if($has_place && $address){ ?><p class="v3-profile-addr"><?php echo $address; ?></p><?php } ?>
					<p class="v3-muted"><?php echo v3_e($categoryname); ?><?php if($a['town']) echo ' &nbsp;|&nbsp; ', v3_t($a['town']); ?><?php if($section == 'business-for-sale') echo ' &nbsp;|&nbsp; <strong class="v3-orange">Продава се</strong>'; ?></p>
				</div>
			</header>

			<div class="v3-actions">
				<?php if($phones){ ?><a class="v3-btn v3-btn-blue" href="tel:<?php echo v3_e(v3_tel($phones[0])); ?>" data-track-call="<?php echo (int)$a['id']; ?>"><?php echo v3_icon('phone', 18); ?> Обади се</a><?php } ?>
				<?php if($has_geo){ ?><a class="v3-btn v3-btn-line" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&amp;destination=<?php echo (float)$a['latitude'], ',', (float)$a['longitude']; ?>"><?php echo v3_icon('pin', 18); ?> Виж маршрута</a><?php } ?>
				<?php if($site_allowed){ ?><a class="v3-btn v3-btn-line" target="_blank" rel="nofollow noopener" href="<?php echo v3_e($a['site']); ?>" data-track-link="<?php echo (int)$a['id']; ?>"><?php echo v3_icon('arrow', 18); ?> Уебсайт</a><?php } ?>
				<button type="button" class="v3-btn v3-btn-ghost" data-share data-title="<?php echo $name; ?>"><?php echo v3_icon('chat', 18); ?> Сподели</button>
			</div>

			<a class="v3-rating-line" href="#otzivi">
				<?php if($nvotes > 0){ echo v3_stars($avg), ' <strong>', number_format($avg, 1, ',', ''), '</strong> <span class="v3-muted">(', $nvotes, ' ', $nvotes == 1 ? 'оценка' : 'оценки', ')</span>'; }
				else { echo v3_icon('chat', 18), ' <span class="v3-muted">Все още няма отзиви.</span>'; } ?>
			</a>

			<nav class="v3-tabs" aria-label="Раздели">
				<a href="#za-biznesa">За бизнеса</a>
				<?php if($offers){ ?><a href="#predlozhenia">Предложения (<?php echo count($offers); ?>)</a><?php } ?>
				<a href="#rabotno-vreme">Работно време</a>
				<a href="#otzivi">Отзиви</a>
				<?php if(!$public_service){ ?><a href="#kontakt">Контакт</a><?php } ?>
			</nav>

			<section id="za-biznesa" class="v3-block">
				<div class="v3-prose"><?php echo $desc ? $desc : '<p>'.v3_excerpt($a['meta_description'], 400).'</p>'; ?></div>
				<?php if(count($gallery) > 1){ ?>
				<h2 class="v3-h3">Галерия</h2>
				<div class="v3-gallery" data-lb-group>
					<?php foreach($gallery as $g){ ?><a href="<?php echo v3_e(WebSite.'/images/adverts/'.$g['image']); ?>" data-lb-item><img src="<?php echo v3_e(WebSite.'/images/adverts/'.$g['image']); ?>" alt="<?php echo $name; ?>" loading="lazy" /></a><?php } ?>
				</div>
				<?php } ?>
				<?php if(!empty($a['video']) && preg_match('/^[A-Za-z0-9_-]{6,20}$/', $a['video'])){ ?>
				<h2 class="v3-h3">Видео</h2>
				<div class="v3-video"><iframe src="https://www.youtube-nocookie.com/embed/<?php echo v3_e($a['video']); ?>" title="Видео" loading="lazy" allow="encrypted-media; picture-in-picture" allowfullscreen></iframe></div>
				<?php } ?>
				<?php if($tags){ ?>
				<h2 class="v3-h3">Предлагани продукти и услуги</h2>
				<div class="v3-chips"><?php foreach(array_slice($tags, 0, 20) as $t){ ?><a class="v3-chip-link" href="<?php echo v3_url('/search/?search='.rawurlencode($t).'&exact_match=0'); ?>"><?php echo v3_e($t); ?></a><?php } ?></div>
				<?php } ?>
				<?php if($docs){ ?>
				<h2 class="v3-h3">Документи</h2>
				<ul class="v3-docs"><?php foreach($docs as $d){ ?><li><?php echo v3_icon('news', 18); ?> <a target="_blank" rel="noopener" href="<?php echo v3_e(WebSite.'/images/files/'.$d['doc_url']); ?>"><?php echo v3_t($d['doc_name']); ?></a> <span class="v3-muted">· <?php echo v3_e(date('d.m.Y', strtotime($d['added_date']))); ?></span></li><?php } ?></ul>
				<?php } ?>
			</section>

			<?php if($offers){ ?>
			<section id="predlozhenia" class="v3-block">
				<h2 class="v3-h3">Предложения от <?php echo $name; ?></h2>
				<div class="v3-offer-grid">
					<?php foreach($offers as $o){ ?>
					<a class="v3-offer-card" href="<?php echo v3_url('/products/'.$o['category_url'].'/'.$o['product_url']); ?>">
						<span class="v3-offer-img"><img src="<?php echo v3_e(WebSite.'/images/products/'.($o['image'] ? $o['image'] : 'no_product_image.jpg')); ?>" alt="" loading="lazy" /></span>
						<span class="v3-offer-body"><strong><?php echo v3_excerpt($o['title'], 70); ?></strong><?php if((float)$o['price'] > 0){ ?><em><?php echo number_format((float)$o['price'], 2, ',', ' '); ?> €</em><?php } ?></span>
					</a>
					<?php } ?>
				</div>
			</section>
			<?php } ?>

			<section id="rabotno-vreme" class="v3-block">
				<h2 class="v3-h3">Работно време <?php if($open_now === true) echo '<span class="v3-badge v3-badge-ok">Отворено сега</span>'; elseif($open_now === false) echo '<span class="v3-badge v3-badge-off">Затворено сега</span>'; ?></h2>
				<?php if($days){ ?>
				<table class="v3-hours"><?php foreach($days_bg as $k => $d){ ?><tr<?php if($k == strtolower(date('D'))) echo ' class="is-today"'; ?>><th><?php echo $d; ?></th><td><?php echo in_array($k, $days) ? $hours : '<span class="v3-muted">Почивен ден</span>'; ?></td></tr><?php } ?></table>
				<?php }else{ ?><p class="v3-muted">Няма информация за работното време.</p><?php } ?>
			</section>

			<section id="otzivi" class="v3-block">
				<h2 class="v3-h3">Отзиви</h2>
				<?php if($reviews){ foreach($reviews as $rv){ ?>
				<article class="v3-review">
					<header><strong><?php echo v3_t($rv['username']); ?></strong><?php if($rv['voted'] > 0) echo v3_stars($rv['voted']); ?><time class="v3-muted"><?php echo date('d.m.Y', $rv['t']); ?></time></header>
					<p><?php echo nl2br(v3_t($rv['comment'])); ?></p>
				</article>
				<?php } }else{ ?><p class="v3-muted">Все още няма отзиви. Бъди първият, който ще сподели мнение.</p><?php } ?>

				<?php if(!$already_voted && !$is_owner){ ?>
				<form class="v3-form v3-card" data-v3-review="<?php echo (int)$aid; ?>" novalidate>
					<h3>Сподели мнение за <?php echo $name; ?></h3>
					<fieldset class="v3-star-input"><legend>Твоята оценка</legend>
						<?php for($i = 5; $i >= 1; $i--){ ?><input type="radio" name="star" id="st<?php echo $i; ?>" value="<?php echo $i; ?>" /><label for="st<?php echo $i; ?>" title="<?php echo $i; ?> от 5">★</label><?php } ?>
					</fieldset>
					<label>Име<input type="text" name="name" maxlength="100" value="<?php echo v3_e($pre_names); ?>" required /></label>
					<label>Мнение<textarea name="comment" rows="4" minlength="10" maxlength="500" required></textarea></label>
					<p class="v3-small">Отзивите се публикуват след проверка.</p>
					<button class="v3-btn v3-btn-orange" type="submit">Изпрати отзив</button>
					<p class="v3-form-msg" role="status"></p>
				</form>
				<?php }elseif($already_voted){ ?><p class="v3-small">Вече си оценил този бизнес. Благодарим!</p><?php } ?>
			</section>

			<?php if(!$public_service && !$is_owner){ ?>
			<section id="kontakt" class="v3-block">
				<form class="v3-form v3-card" data-v3-contact="<?php echo (int)$a['id']; ?>" novalidate>
					<h2 class="v3-h3">Изпрати запитване до <?php echo $name; ?></h2>
					<div class="v3-form-row">
						<label>Име и фамилия<input type="text" name="names" value="<?php echo v3_e($pre_names); ?>" autocomplete="name" required /></label>
						<label>Телефон<input type="tel" name="phone" value="<?php echo v3_e($pre_phone); ?>" autocomplete="tel" required /></label>
					</div>
					<label>Имейл<input type="email" name="email" value="<?php echo v3_e($pre_email); ?>" autocomplete="email" required /></label>
					<label>Съобщение<textarea name="message" rows="5" minlength="20" required placeholder="Опиши накратко от какво имаш нужда (поне 20 символа)."></textarea></label>
					<button class="v3-btn v3-btn-orange" type="submit"><?php echo v3_icon('chat', 18); ?> Изпрати запитване</button>
					<p class="v3-form-msg" role="status"></p>
				</form>
			</section>
			<?php } ?>
		</div>

		<aside class="v3-profile-side">
			<div class="v3-card v3-side-card">
				<h2 class="v3-h3">Контактна информация</h2>
				<dl class="v3-contact-list">
					<?php if($has_place && $address){ ?><div><dt><?php echo v3_icon('pin'); ?> Адрес</dt><dd><?php echo $address; ?></dd></div><?php } ?>
					<?php if($phones){ ?><div><dt><?php echo v3_icon('phone'); ?> Телефон</dt><dd><?php foreach($phones as $ph){ ?><a href="tel:<?php echo v3_e(v3_tel($ph)); ?>" data-track-call="<?php echo (int)$a['id']; ?>"><?php echo v3_e($ph); ?></a><?php } ?></dd></div><?php } ?>
					<?php if(!empty($a['mail']) && filter_var($a['mail'], FILTER_VALIDATE_EMAIL)){ ?><div><dt><?php echo v3_icon('chat'); ?> Имейл</dt><dd><a href="mailto:<?php echo v3_e($a['mail']); ?>"><?php echo v3_e($a['mail']); ?></a></dd></div><?php } ?>
					<div><dt><?php echo v3_icon('clock'); ?> Работно време</dt><dd><?php echo $days ? $hours : '<span class="v3-muted">Няма информация</span>'; ?></dd></div>
				</dl>
				<?php if($has_geo){ ?><a class="v3-btn v3-btn-blue v3-btn-block" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&amp;query=<?php echo (float)$a['latitude'], ',', (float)$a['longitude']; ?>"><?php echo v3_icon('pin', 18); ?> Виж картата</a><?php } ?>
				<div class="v3-owner-box">
					<?php echo v3_icon('user', 22); ?>
					<?php if($is_owner){ ?>
					<span>Това е твоят бизнес. <a href="<?php echo v3_url('/redirector.php?edit_ad='.(int)$a['id']); ?>">Редактирай профила.</a></span>
					<?php }else{ ?>
					<span>Това е твоят бизнес? <a href="<?php echo v3_url('/contacts/'); ?>">Управлявай информацията.</a></span>
					<?php } ?>
				</div>
			</div>
			<p class="v3-small v3-center">В каталога от <?php echo date('Y', strtotime($a['added_date'])); ?> г. · <a href="<?php echo v3_url('/contacts/'); ?>">Сигнализирай за нередност</a></p>
		</aside>
	</div>
</div>

<?php if($phones){ ?>
<a class="v3-sticky-call" href="tel:<?php echo v3_e(v3_tel($phones[0])); ?>" data-track-call="<?php echo (int)$a['id']; ?>"><?php echo v3_icon('phone', 20); ?> Обади се</a>
<?php } ?>
<?php v3_footer(); ?>
