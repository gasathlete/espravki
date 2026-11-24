<?php
/* ==========================================================================
   v3 customer dashboard (designs A06 / A09). Included from mydash.php AFTER
   its own processing (profile save, message read), so all saving logic is
   unchanged. Expects: $row (customer), $msg, $lang.
   Actions use the existing endpoints: ajax/delete_prd.php, ajax/delete_advert.php,
   ajax/change_ord_status.php, redirector.php, product_redirect.php
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$me = (int)$_SESSION['customer'];
$cust = $row;

/* which view */
$view = 'overview';
if(isset($_GET['profile'])) $view = 'settings';
elseif(isset($_GET['my_listings'])) $view = 'profiles';
elseif(isset($_GET['my_products'])) $view = 'products';
elseif(isset($_GET['messages'])) $view = 'messages';
elseif(isset($_GET['my_orders'])) $view = 'orders';

function v3_dq($sql){
	$r = mysql_query($sql) or die(send_error($sql,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$out = array(); while($x = mysql_fetch_assoc($r)) $out[] = $x; return $out;
}
function v3_dcount($sql){ $r = mysql_query($sql); if(!$r) return 0; $x = mysql_fetch_row($r); return (int)$x[0]; }
function v3_dash_url($q = ''){ return v3_url('/mydash.php'.($q ? '?'.$q : '')); }
function v3_d($d){ $t = strtotime($d); return $t ? date('d.m.Y', $t) : ''; }

/* my businesses (same query as the classic page) */
$adverts = v3_dq('SELECT * FROM adverts WHERE customer_id = "'.$me.'" AND deleted = "0" ORDER BY added_date DESC');
$aids = array(); foreach($adverts as $ad) $aids[] = (int)$ad['id'];
$aid_list = $aids ? implode(',', $aids) : '0';
$first_cat = array();
if($aids) foreach(v3_dq('SELECT atpc.advert_id, pc.url, pc.bg_category FROM adverts_to_product_categories atpc JOIN products_categories pc ON pc.id = atpc.category_id WHERE atpc.advert_id IN ('.$aid_list.') GROUP BY atpc.advert_id') as $c) $first_cat[$c['advert_id']] = $c;
function v3_ad_url($ad, $first_cat){ $c = isset($first_cat[$ad['id']]) ? $first_cat[$ad['id']]['url'] : ''; return v3_url('/business-directory/'.$c.'/'.$ad['link_name']); }
function v3_ad_status($ad){
	if($ad['active'] != '1') return array('Очаква одобрение', 'wait');
	if($ad['expired_date'] != '0000-00-00' && $ad['expired_date'] != '' && strtotime($ad['expired_date']) < time()) return array('Изтекъл', 'off');
	return array('Публикуван', 'ok');
}

/* counters */
$n_new_msgs = v3_dcount('SELECT COUNT(*) FROM messages WHERE recipient_id = "'.$me.'" AND readed = "0"');

/* shell */
$titles = array('overview'=>'Твоето табло','profiles'=>'Моите профили','products'=>'Продукти и услуги','messages'=>'Съобщения','orders'=>'Поръчки','settings'=>'Настройки на акаунта');
$meta = '<title>'.$titles[$view].' | Espravki.com</title><meta name="robots" content="noindex, nofollow" />';
v3_head($meta);
$fname = explode(' ', trim($cust['customer_names'])); $fname = $fname[0];
$avatar = !empty($cust['profile_image']) ? WebSite.'/images/users/'.$cust['profile_image'] : '';
$nav = array(
	array('overview', '', 'grid', 'Преглед'),
	array('profiles', 'my_listings=show', 'news', 'Моите профили'),
	array('products', 'my_products=show', 'tag', 'Продукти и услуги'),
	array('messages', 'messages=show', 'chat', 'Съобщения'),
	array('orders', 'my_orders=show', 'clock', 'Поръчки'),
	array('settings', 'profile=show', 'user', 'Настройки'),
);
?>
<div class="v3-dash">
	<aside class="v3-dash-side" id="v3-dash-side">
		<div class="v3-dash-brand"><?php echo v3_logo(); ?></div>
		<nav aria-label="Табло">
			<?php foreach($nav as $n){ ?>
			<a href="<?php echo v3_dash_url($n[1]); ?>" class="<?php echo $view == $n[0] ? 'is-on' : ''; ?>"<?php if($view == $n[0]) echo ' aria-current="page"'; ?>><?php echo v3_icon($n[2], 20), ' ', $n[3]; ?><?php if($n[0] == 'messages' && $n_new_msgs) echo ' <em class="v3-count">', $n_new_msgs, '</em>'; ?></a>
			<?php } ?>
			<a href="<?php echo v3_url('/logout.php'); ?>"><?php echo v3_icon('arrow', 20); ?> Изход</a>
		</nav>
	</aside>
	<div class="v3-dash-main">
		<header class="v3-dash-top">
			<button class="v3-burger v3-dash-burger" type="button" aria-controls="v3-dash-side" aria-expanded="false" aria-label="Меню"><?php echo v3_icon('menu', 24); ?></button>
			<a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_url('/'); ?>"><?php echo v3_icon('arrow', 16); ?> Към каталога</a>
			<a class="v3-dash-user" href="<?php echo v3_dash_url('profile=show'); ?>"><?php if($avatar){ ?><img src="<?php echo v3_e($avatar); ?>" alt="" /><?php }else echo v3_icon('user', 20); ?><span>Здравей, <?php echo v3_t($fname); ?></span></a>
		</header>
		<main id="main" class="v3-dash-content">
<?php
/* ======================= OVERVIEW (A06) ======================= */
if($view == 'overview'){
	$main_ad = $adverts ? $adverts[0] : null;
	$views = $aids ? v3_dcount('SELECT COUNT(*) FROM statistics_adverts_visits WHERE advert_id IN ('.$aid_list.')') : 0;
	$calls = $aids ? v3_dcount('SELECT COUNT(*) FROM statistics_adverts_calls WHERE advert_id IN ('.$aid_list.')') : 0;
	$inq   = v3_dcount('SELECT COUNT(*) FROM messages WHERE recipient_id = "'.$me.'"');
	$ords  = v3_dcount('SELECT COUNT(DISTINCT o.id) FROM orders o JOIN products p ON p.id = o.pid WHERE p.cid = "'.$me.'"');
	$offers = $aids ? v3_dq('SELECT id, title, price, image, product_url, category_url, active, sold FROM products WHERE advert_id IN ('.$aid_list.') AND deleted = "0" ORDER BY addeddate DESC LIMIT 5') : array();
	$n_offers = $aids ? v3_dcount('SELECT COUNT(*) FROM products WHERE advert_id IN ('.$aid_list.') AND deleted = "0"') : 0;
	$max_offers = $main_ad ? (int)$main_ad['max_offers'] : 0;
?>
	<div class="v3-dash-head">
		<h1>Твоето табло</h1>
		<?php if(count($adverts) > 1){ ?><span class="v3-muted"><?php echo count($adverts); ?> профила</span><?php } ?>
		<a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_url('/select-plan/'); ?>"><?php echo v3_icon('plus', 16); ?> Нов профил</a>
	</div>

	<?php if(!$main_ad){ ?>
	<section class="v3-card v3-next">
		<div><span class="v3-eyebrow">Следваща стъпка</span><h2>Добави своя бизнес</h2><p class="v3-muted">Създай публичен профил, за да могат клиентите да те открият, да ти се обадят и да ти пишат.</p>
		<a class="v3-btn v3-btn-orange" href="<?php echo v3_url('/select-plan/'); ?>"><?php echo v3_icon('plus', 18); ?> Добави бизнеса си</a></div>
	</section>
	<?php }else{ $st = v3_ad_status($main_ad); ?>
	<div class="v3-alert v3-alert-<?php echo $st[1]; ?>"><?php echo v3_icon($st[1] == 'ok' ? 'check' : 'clock', 18); ?> <strong><?php echo $st[0]; ?></strong><?php if($main_ad['expired_date'] != '0000-00-00' && $main_ad['expired_date']) echo ' · активен до ', v3_d($main_ad['expired_date']); ?></div>

	<div class="v3-dash-grid">
		<section class="v3-card v3-dprofile">
			<div class="v3-dprofile-cover"><?php $img = $main_ad['small_image'] ? $main_ad['small_image'] : $main_ad['logo']; if($img){ ?><img src="<?php echo v3_e(WebSite.'/images/adverts/'.$img); ?>" alt="" /><?php } ?></div>
			<h2><?php echo v3_t($main_ad['supplier_name']); ?></h2>
			<p class="v3-muted"><?php echo isset($first_cat[$main_ad['id']]) ? v3_t($first_cat[$main_ad['id']]['bg_category']) : ''; ?><?php if($main_ad['town']) echo ' · ', v3_t($main_ad['town']); ?></p>
			<a class="v3-btn v3-btn-line v3-btn-block v3-btn-sm" target="_blank" href="<?php echo v3_ad_url($main_ad, $first_cat); ?>"><?php echo v3_icon('arrow', 16); ?> Виж публичната страница</a>
		</section>
		<section class="v3-card v3-next">
			<div>
				<span class="v3-eyebrow">Следваща стъпка</span>
				<?php if($n_offers == 0){ ?>
				<h2>Добави първата си услуга</h2>
				<p class="v3-muted">Представи с какво се занимаваш и какво предлагаш, за да могат клиентите лесно да те открият.</p>
				<?php }else{ ?>
				<h2>Добави ново предложение</h2>
				<p class="v3-muted">Повече предложения означават повече начини клиентите да те намерят.</p>
				<?php } ?>
				<a class="v3-btn v3-btn-orange" href="<?php echo v3_url('/redirector.php?add_product='.(int)$main_ad['id']); ?>"><?php echo v3_icon('plus', 18); ?> Добави продукт или услуга</a>
			</div>
		</section>
	</div>

	<?php
	$checks = array(
		array('Име и категория', !empty($main_ad['supplier_name']) && isset($first_cat[$main_ad['id']])),
		array('Контакт', !empty($main_ad['company_phones']) || !empty($main_ad['mail'])),
		array('Снимка', !empty($main_ad['small_image']) || !empty($main_ad['logo'])),
		array('Първо предложение', $n_offers > 0),
	);
	?>
	<ol class="v3-card v3-checklist">
		<?php foreach($checks as $c){ ?><li class="<?php echo $c[1] ? 'is-done' : ''; ?>"><span><?php if($c[1]) echo v3_icon('check', 16); ?></span><div><strong><?php echo $c[0]; ?></strong><small><?php echo $c[1] ? 'Попълнено' : 'Остава да добавиш'; ?></small></div></li><?php } ?>
	</ol>
	<?php } ?>

	<div class="v3-stats">
		<?php foreach(array(array('Преглеждания на профила', $views, 'search', 'blue'), array('Кликвания на телефон', $calls, 'phone', 'green'), array('Запитвания', $inq, 'mail', 'orange'), array('Поръчки', $ords, 'tag', 'violet')) as $s){ ?>
		<div class="v3-card v3-stat"><span class="v3-stat-ic v3-tone-<?php echo $s[3]; ?>"><?php echo v3_icon($s[2], 20); ?></span><div><small><?php echo $s[0]; ?></small><b><?php echo $s[1] ? number_format($s[1], 0, ',', ' ') : '—'; ?></b><?php if(!$s[1]) echo '<em>Все още няма отчетени действия.</em>'; ?></div></div>
		<?php } ?>
	</div>
	<?php if($main_ad){ ?><p class="v3-small">Подробна статистика по месеци: <a href="<?php echo v3_url('/profile_visits.php?aid='.(int)$main_ad['id']); ?>">посещения</a> · <a href="<?php echo v3_url('/call_statistics.php?aid='.(int)$main_ad['id']); ?>">обаждания</a></p><?php } ?>

	<?php if($main_ad){ ?>
	<section class="v3-card v3-dsection">
		<div class="v3-dsection-head">
			<h2>Моите предложения</h2>
			<?php if($max_offers){ ?><span class="v3-usage"><?php echo $n_offers; ?> от <?php echo $max_offers; ?> използвани<span class="v3-bar"><i style="width:<?php echo min(100, round($n_offers * 100 / max(1, $max_offers))); ?>%"></i></span></span><?php } ?>
			<a class="v3-btn v3-btn-dark v3-btn-sm" href="<?php echo v3_url('/redirector.php?add_product='.(int)$main_ad['id']); ?>"><?php echo v3_icon('plus', 16); ?> Добави предложение</a>
		</div>
		<?php if($offers){ ?>
		<ul class="v3-dlist"><?php foreach($offers as $o){ ?>
			<li><img src="<?php echo v3_e(WebSite.'/images/products/'.($o['image'] ? $o['image'] : 'no_product_image.jpg')); ?>" alt="" /><div><strong><?php echo v3_excerpt($o['title'], 70); ?></strong><small><?php echo (float)$o['price'] > 0 ? number_format((float)$o['price'], 2, ',', ' ').' €' : ''; ?> <?php echo $o['active'] ? '' : '· <span class="v3-orange">неактивно</span>'; ?></small></div><a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_url('/product_redirect.php?pid='.(int)$o['id']); ?>">Редактирай</a></li>
		<?php } ?></ul>
		<p><a class="v3-more" href="<?php echo v3_dash_url('my_products=show'); ?>">Всички предложения <?php echo v3_icon('arrow', 16); ?></a></p>
		<?php }else{ ?>
		<div class="v3-empty-row"><?php echo v3_icon('news', 28); ?><div><strong>Нямаш публикувани предложения.</strong><p class="v3-muted">Добави продукти или услуги, за да ги виждат клиентите в твоя профил.</p></div></div>
		<?php } ?>
	</section>
	<?php { ?>
	<div class="v3-note v3-note-info v3-multi"><?php echo v3_icon('user', 20); ?><span><strong>Можеш да управляваш няколко профила.</strong> Абонаментът и лимитите са отделни за всеки.</span><a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_url('/select-plan/'); ?>"><?php echo v3_icon('plus', 16); ?> Нов профил</a></div>
	<?php } } ?>
<?php
/* ======================= MY PROFILES ======================= */
}elseif($view == 'profiles'){ ?>
	<div class="v3-dash-head"><h1>Моите профили</h1><a class="v3-btn v3-btn-orange v3-btn-sm" href="<?php echo v3_url('/select-plan/'); ?>"><?php echo v3_icon('plus', 16); ?> Нов профил</a></div>
	<?php if(!$adverts){ ?><div class="v3-card v3-empty"><h3>Все още нямаш профил.</h3><p class="v3-muted">Добави бизнеса си, за да се появи в каталога.</p><a class="v3-btn v3-btn-orange" href="<?php echo v3_url('/select-plan/'); ?>">Добави бизнеса си</a></div><?php } ?>
	<?php foreach($adverts as $ad){ $st = v3_ad_status($ad); $img = $ad['small_image'] ? $ad['small_image'] : $ad['logo']; ?>
	<article class="v3-card v3-prow">
		<span class="v3-prow-img"><?php if($img){ ?><img src="<?php echo v3_e(WebSite.'/images/adverts/'.$img); ?>" alt="" /><?php } ?></span>
		<div class="v3-prow-body">
			<h2><?php echo v3_t($ad['supplier_name']); ?></h2>
			<p class="v3-muted"><?php echo isset($first_cat[$ad['id']]) ? v3_t($first_cat[$ad['id']]['bg_category']) : ''; ?><?php if($ad['town']) echo ' · ', v3_t($ad['town']); ?></p>
			<span class="v3-badge v3-badge-<?php echo $st[1]; ?>"><?php echo $st[0]; ?></span><?php if($ad['expired_date'] != '0000-00-00' && $ad['expired_date']) echo ' <small class="v3-muted">до ', v3_d($ad['expired_date']), '</small>'; ?>
		</div>
		<div class="v3-prow-actions">
			<a class="v3-btn v3-btn-dark v3-btn-sm" href="<?php echo v3_url('/redirector.php?edit_ad='.(int)$ad['id']); ?>">Редактирай</a>
			<a class="v3-btn v3-btn-line v3-btn-sm" target="_blank" href="<?php echo v3_ad_url($ad, $first_cat); ?>">Виж</a>
			<a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_url('/profile_visits.php?aid='.(int)$ad['id']); ?>">Статистика</a>
			<button type="button" class="v3-btn v3-btn-ghost v3-btn-sm v3-danger" data-del-advert="<?php echo (int)$ad['id']; ?>" data-name="<?php echo v3_t($ad['supplier_name']); ?>">Изтрий</button>
		</div>
	</article>
	<?php } ?>
<?php
/* ======================= PRODUCTS ======================= */
}elseif($view == 'products'){ ?>
	<div class="v3-dash-head"><h1>Продукти и услуги</h1></div>
	<?php if(!$adverts){ ?><div class="v3-card v3-empty"><h3>Първо добави бизнеса си.</h3><p class="v3-muted">Продуктите и услугите се показват в профила на бизнеса.</p><a class="v3-btn v3-btn-orange" href="<?php echo v3_url('/select-plan/'); ?>">Добави бизнеса си</a></div><?php } ?>
	<?php foreach($adverts as $ad){
		$prods = v3_dq('SELECT id, title, price, promo_price, image, product_url, category_url, active, sold, checked_by_admin FROM products WHERE advert_id = "'.(int)$ad['id'].'" AND deleted = "0" ORDER BY addeddate DESC');
		$max = (int)$ad['max_offers']; ?>
	<section class="v3-card v3-dsection">
		<div class="v3-dsection-head">
			<h2><?php echo v3_t($ad['supplier_name']); ?></h2>
			<?php if($max){ ?><span class="v3-usage"><?php echo count($prods); ?> от <?php echo $max; ?> използвани<span class="v3-bar"><i style="width:<?php echo min(100, round(count($prods) * 100 / max(1, $max))); ?>%"></i></span></span><?php } ?>
			<a class="v3-btn v3-btn-dark v3-btn-sm" href="<?php echo v3_url('/redirector.php?add_product='.(int)$ad['id']); ?>"><?php echo v3_icon('plus', 16); ?> Добави предложение</a>
		</div>
		<?php if($prods){ ?><ul class="v3-dlist"><?php foreach($prods as $p){
			$pst = !$p['checked_by_admin'] ? array('Очаква проверка','wait') : (!$p['active'] ? array('Неактивно','off') : ($p['sold'] ? array('Продадено','off') : array('Публикувано','ok'))); ?>
			<li data-prow>
				<img src="<?php echo v3_e(WebSite.'/images/products/'.($p['image'] ? $p['image'] : 'no_product_image.jpg')); ?>" alt="" />
				<div><strong><?php echo v3_excerpt($p['title'], 80); ?></strong><small><?php echo (float)$p['price'] > 0 ? number_format((float)$p['price'], 2, ',', ' ').' €' : 'Цена по запитване'; ?> · <span class="v3-badge v3-badge-<?php echo $pst[1]; ?>"><?php echo $pst[0]; ?></span></small></div>
				<span class="v3-dlist-actions">
					<a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_url('/product_redirect.php?pid='.(int)$p['id']); ?>">Редактирай</a>
					<a class="v3-btn v3-btn-ghost v3-btn-sm" target="_blank" href="<?php echo v3_url('/products/'.$p['category_url'].'/'.$p['product_url']); ?>">Виж</a>
					<button type="button" class="v3-btn v3-btn-ghost v3-btn-sm v3-danger" data-del-product="<?php echo (int)$p['id']; ?>" data-aid="<?php echo (int)$ad['id']; ?>" data-name="<?php echo v3_excerpt($p['title'], 60); ?>">Изтрий</button>
				</span>
			</li>
		<?php } ?></ul><?php }else{ ?>
		<div class="v3-empty-row"><?php echo v3_icon('news', 28); ?><div><strong>Нямаш публикувани предложения.</strong><p class="v3-muted">Добави продукти или услуги, за да ги виждат клиентите в твоя профил.</p></div></div>
		<?php } ?>
	</section>
	<?php } ?>
<?php
/* ======================= MESSAGES (A09) ======================= */
}elseif($view == 'messages'){
	if(isset($msgrow) && !empty($msgrow)){
		$about = $msgrow['subject']; $plink = '';
		if($msgrow['about_pid'] > 0){ $pd = get_product_name($msgrow['about_pid']); if($pd){ $about = $pd['name']; $plink = v3_url('/products/'.$pd['curl'].'/'.$pd['purl']); } } ?>
	<div class="v3-dash-head"><h1>Съобщение</h1><a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_dash_url('messages=show'); ?>">← Всички съобщения</a></div>
	<article class="v3-card v3-dsection v3-message">
		<p class="v3-muted"><?php echo v3_d($msgrow['added_date']); ?> · Относно: <strong><?php echo v3_t($about); ?></strong><?php if($plink) echo ' · <a target="_blank" href="', $plink, '">виж продукта</a>'; ?></p>
		<div class="v3-message-text"><?php echo nl2br(v3_t(str_replace(array('<br>','<br/>','<br />'), "\n", $msgrow['mail_text']))); ?></div>
		<dl class="v3-contact-list">
			<div><dt><?php echo v3_icon('user'); ?> Име</dt><dd><?php echo v3_t($msgrow['customer_names']); ?></dd></div>
			<?php if($msgrow['cmail']){ ?><div><dt><?php echo v3_icon('mail'); ?> Имейл</dt><dd><a href="mailto:<?php echo v3_e($msgrow['cmail']); ?>"><?php echo v3_e($msgrow['cmail']); ?></a></dd></div><?php } ?>
			<?php if($msgrow['cphone']){ ?><div><dt><?php echo v3_icon('phone'); ?> Телефон</dt><dd><a href="tel:<?php echo v3_e(preg_replace('/[^0-9+]/', '', $msgrow['cphone'])); ?>"><?php echo v3_e($msgrow['cphone']); ?></a></dd></div><?php } ?>
		</dl>
		<div class="v3-actions">
			<?php if($msgrow['cmail']){ ?><a class="v3-btn v3-btn-orange" href="mailto:<?php echo v3_e($msgrow['cmail']); ?>?subject=<?php echo rawurlencode('Re: '.html_entity_decode(strip_tags($about), ENT_QUOTES, 'UTF-8')); ?>"><?php echo v3_icon('mail', 18); ?> Отговори по имейл</a><?php } ?>
			<?php if($msgrow['cphone']){ ?><a class="v3-btn v3-btn-line" href="tel:<?php echo v3_e(preg_replace('/[^0-9+]/', '', $msgrow['cphone'])); ?>"><?php echo v3_icon('phone', 18); ?> Обади се</a><?php } ?>
		</div>
	</article>
	<?php }else{
		$msgs = v3_dq('SELECT m.subject, m.customer_names msgcustname, m.secret, m.readed, m.about_pid, m.added_date, a.supplier_name
			FROM messages m, adverts a, customers c WHERE recipient_id = "'.$me.'" AND about_aid = a.id AND a.customer_id = recipient_id
			GROUP BY m.id ORDER BY readed ASC, m.added_date DESC');
		$only_new = isset($_GET['new']); ?>
	<div class="v3-dash-head"><h1>Съобщения</h1><p class="v3-muted">Запитвания от твоя публичен профил.</p></div>
	<div class="v3-seg v3-seg-inline"><a class="v3-btn <?php echo $only_new ? 'v3-btn-line' : 'v3-btn-blue'; ?> v3-btn-sm" href="<?php echo v3_dash_url('messages=show'); ?>">Всички</a><a class="v3-btn <?php echo $only_new ? 'v3-btn-blue' : 'v3-btn-line'; ?> v3-btn-sm" href="<?php echo v3_dash_url('messages=show&new=1'); ?>">Непрочетени<?php if($n_new_msgs) echo ' (', $n_new_msgs, ')'; ?></a></div>
	<?php $shown = 0; ?>
	<ul class="v3-card v3-inbox">
		<?php foreach($msgs as $m){ if($only_new && $m['readed']) continue; $shown++; ?>
		<li class="<?php echo $m['readed'] ? '' : 'is-new'; ?>"><a href="<?php echo v3_dash_url('messages=show&msg='.$m['secret']); ?>">
			<span class="v3-dot"></span>
			<div><strong><?php echo v3_t($m['msgcustname']); ?></strong><small><?php echo v3_t($m['supplier_name']); ?> · <?php echo v3_excerpt($m['subject'], 70); ?></small></div>
			<time><?php echo v3_d($m['added_date']); ?></time>
		</a></li>
		<?php } ?>
		<?php if(!$shown){ ?><li class="v3-inbox-empty"><?php echo v3_icon('mail', 36); ?><strong>Нямаш <?php echo $only_new ? 'нови ' : ''; ?>съобщения.</strong><span class="v3-muted">Запитванията от твоя публичен профил ще се появят тук.</span></li><?php } ?>
	</ul>
	<?php } ?>
<?php
/* ======================= ORDERS (A09) ======================= */
}elseif($view == 'orders'){
	$statuses = $lang['statuses'];
	if(isset($_GET['order']) && strlen($_GET['order']) == 32){
		$o = v3_dq('SELECT o.id oid, qty, o.price, options, delivery_address, order_date, order_status, order_secret, category_url, product_url, title, supplier_name, p.cid sup_id, o.cid cust_id, o.order_comments, cnames, cphone
			FROM orders o, products p, adverts a WHERE o.order_secret = "'.mysql_real_escape_string($_GET['order']).'" AND o.pid = p.id AND o.aid = a.id
			AND (p.cid = "'.$me.'" OR o.cid = "'.$me.'") GROUP BY o.id LIMIT 1');
		$o = $o ? $o[0] : null; ?>
	<div class="v3-dash-head"><h1>Детайли на поръчка</h1><a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_dash_url('my_orders=show'); ?>">← Всички поръчки</a></div>
	<?php if(!$o){ ?><div class="v3-card v3-empty"><h3>Поръчката не е намерена.</h3></div><?php }else{ $seller = ($o['sup_id'] == $me); ?>
	<article class="v3-card v3-dsection">
		<dl class="v3-contact-list v3-order-dl">
			<div><dt>Номер</dt><dd>#<?php echo (int)$o['oid']; ?></dd></div>
			<div><dt>Продукт</dt><dd><a target="_blank" href="<?php echo v3_url('/products/'.$o['category_url'].'/'.$o['product_url']); ?>"><?php echo v3_t($o['title']); ?></a></dd></div>
			<div><dt>Профил</dt><dd><?php echo v3_t($o['supplier_name']); ?></dd></div>
			<div><dt>Количество</dt><dd><?php echo (int)$o['qty'] ? (int)$o['qty'] : 1; ?></dd></div>
			<?php if((float)$o['price'] > 0){ ?><div><dt>Сума</dt><dd><?php echo number_format((float)$o['price'] * max(1, (int)$o['qty']), 2, ',', ' '); ?> €</dd></div><?php } ?>
			<div><dt>Дата</dt><dd><?php echo v3_d($o['order_date']); ?></dd></div>
			<div><dt>Клиент</dt><dd><?php echo v3_t($o['cnames']); ?><?php if($o['cphone']) echo ' · <a href="tel:', v3_e(preg_replace('/[^0-9+]/', '', $o['cphone'])), '">', v3_e($o['cphone']), '</a>'; ?></dd></div>
			<div><dt>Доставка</dt><dd><?php echo nl2br(v3_t($o['delivery_address'])); ?></dd></div>
			<div><dt>Статус</dt><dd><span class="v3-badge v3-badge-<?php echo $o['order_status'] == 'finished' ? 'ok' : ($o['order_status'] == 'canceled' ? 'off' : 'wait'); ?>"><?php echo isset($statuses[$o['order_status']]) ? $statuses[$o['order_status']] : v3_e($o['order_status']); ?></span></dd></div>
		</dl>
		<?php if($seller || $o['order_status'] == 'not_active'){ ?>
		<form class="v3-status-form" data-order-status="<?php echo v3_e($o['order_secret']); ?>">
			<label>Промени статуса
				<select name="status"><?php foreach($statuses as $k => $v){ if(!$seller && $k != 'canceled' && $k != $o['order_status']) continue; ?><option value="<?php echo v3_e($k); ?>"<?php if($k == $o['order_status']) echo ' selected'; ?>><?php echo v3_e($v); ?></option><?php } ?></select>
			</label>
			<button class="v3-btn v3-btn-dark" type="submit">Запази статуса</button>
			<p class="v3-form-msg" role="status"></p>
		</form>
		<?php } ?>
	</article>
	<?php } }else{
		$orders = v3_dq('SELECT o.id, qty, o.price, order_date, order_status, order_secret, title, supplier_name, p.cid sup_id, cnames
			FROM orders o, products p, adverts a WHERE o.pid = p.id AND o.aid = a.id AND (p.cid = "'.$me.'" OR o.cid = "'.$me.'")
			GROUP BY o.id ORDER BY order_date DESC');
		$tab = isset($_GET['st']) && isset($statuses[$_GET['st']]) ? $_GET['st'] : ''; ?>
	<div class="v3-dash-head"><h1>Поръчки</h1><p class="v3-muted">Проследявай поръчките и техния статус.</p></div>
	<div class="v3-seg v3-seg-inline">
		<a class="v3-btn v3-btn-sm <?php echo $tab == '' ? 'v3-btn-blue' : 'v3-btn-line'; ?>" href="<?php echo v3_dash_url('my_orders=show'); ?>">Всички</a>
		<?php foreach(array('not_active'=>'Нови','pending'=>'В обработка','finished'=>'Завършени','canceled'=>'Отказани') as $k => $v){ ?><a class="v3-btn v3-btn-sm <?php echo $tab == $k ? 'v3-btn-blue' : 'v3-btn-line'; ?>" href="<?php echo v3_dash_url('my_orders=show&st='.$k); ?>"><?php echo $v; ?></a><?php } ?>
	</div>
	<div class="v3-card v3-table-wrap">
		<table class="v3-table">
			<thead><tr><th>Номер</th><th>Продукт</th><th>Клиент</th><th>Статус</th><th>Сума</th><th></th></tr></thead>
			<tbody>
			<?php $shown = 0; foreach($orders as $o){ if($tab && $o['order_status'] != $tab) continue; $shown++; ?>
			<tr><td>#<?php echo (int)$o['id']; ?><small><?php echo v3_d($o['order_date']); ?></small></td><td><?php echo v3_excerpt($o['title'], 50); ?><small><?php echo $o['sup_id'] == $me ? 'продажба' : 'покупка'; ?> · <?php echo v3_t($o['supplier_name']); ?></small></td><td><?php echo v3_t($o['cnames']); ?></td>
			<td><span class="v3-badge v3-badge-<?php echo $o['order_status'] == 'finished' ? 'ok' : ($o['order_status'] == 'canceled' ? 'off' : 'wait'); ?>"><?php echo isset($statuses[$o['order_status']]) ? $statuses[$o['order_status']] : v3_e($o['order_status']); ?></span></td>
			<td><?php echo (float)$o['price'] > 0 ? number_format((float)$o['price'] * max(1, (int)$o['qty']), 2, ',', ' ').' €' : '—'; ?></td>
			<td><a class="v3-btn v3-btn-line v3-btn-sm" href="<?php echo v3_dash_url('my_orders=show&order='.$o['order_secret']); ?>">Виж</a></td></tr>
			<?php } if(!$shown){ ?><tr><td colspan="6" class="v3-table-empty"><?php echo v3_icon('news', 32); ?><br />Все още няма поръчки.</td></tr><?php } ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
/* ======================= SETTINGS (A09) ======================= */
}else{
	$saved = isset($_GET['msg']) && empty($_POST['save_profile']); ?>
	<div class="v3-dash-head"><h1>Настройки на акаунта</h1><p class="v3-muted">Редактирай основната информация и паролата си.</p></div>
	<?php if($msg){ ?><div class="v3-alert v3-alert-<?php echo $saved ? 'ok' : 'off'; ?>"><?php echo v3_icon($saved ? 'check' : 'clock', 18), ' ', v3_t($msg); ?></div><?php } ?>
	<form class="v3-card v3-form v3-settings" method="post" action="<?php echo v3_dash_url('profile=show'); ?>" enctype="multipart/form-data">
		<h2 class="v3-h3"><?php echo v3_icon('user', 20); ?> Акаунт</h2>
		<div class="v3-form-row">
			<label>Име<input type="text" name="first_name" value="<?php echo v3_e($_POST['first_name']); ?>" required /></label>
			<label>Фамилия<input type="text" name="second_name" value="<?php echo v3_e($_POST['second_name']); ?>" required /></label>
		</div>
		<label>Имейл за вход<input type="email" value="<?php echo v3_e($_POST['customer_email']); ?>" name="customer_email" readonly /></label>
		<div class="v3-form-row">
			<label>Телефон<input type="tel" name="customer_phone" value="<?php echo v3_e($_POST['customer_phone']); ?>" required /></label>
			<label>Град<input type="text" name="city" list="v3-cities" value="<?php echo v3_e($_POST['city']); ?>" required /></label>
		</div>
		<datalist id="v3-cities"><?php foreach(echo_cities() as $c) echo '<option value="', v3_e($c), '">'; ?></datalist>
		<div class="v3-form-row">
			<label>Адрес<input type="text" name="customer_address" value="<?php echo v3_e($_POST['customer_address']); ?>" /></label>
			<label>Пощенски код<input type="text" name="postcode" value="<?php echo v3_e($_POST['postcode']); ?>" required /></label>
		</div>
		<label>За мен <small class="v3-muted">(по желание)</small><textarea name="about_me" rows="3"><?php echo v3_e($_POST['about_me']); ?></textarea></label>
		<div class="v3-form-row">
			<label>Facebook <small class="v3-muted">(по желание)</small><input type="url" name="facebook" value="<?php echo v3_e($_POST['facebook']); ?>" placeholder="https://facebook.com/..." /></label>
			<label>LinkedIn <small class="v3-muted">(по желание)</small><input type="url" name="linkedin" value="<?php echo v3_e($_POST['linkedin']); ?>" placeholder="https://linkedin.com/in/..." /></label>
		</div>
		<label>Снимка на профила <small class="v3-muted">(JPG или PNG)</small><input type="file" name="profile_image" accept="image/jpeg,image/png" /></label>
		<?php if(!empty($_POST['profile_image'])){ ?><label class="v3-check"><input type="checkbox" name="delete_picture" value="<?php echo v3_e($_POST['profile_image']); ?>" /> <span>Изтрий сегашната снимка</span></label><?php } ?>

		<h2 class="v3-h3"><?php echo v3_icon('lock', 20); ?> Парола</h2>
		<label>Нова парола <small class="v3-muted">(остави празно, ако не я сменяш)</small><span class="v3-input-ic"><?php echo v3_icon('lock', 18); ?><input type="password" name="password" autocomplete="new-password" value="" /><button type="button" class="v3-eye" data-eye aria-label="Покажи паролата">👁</button></span></label>
		<button class="v3-btn v3-btn-dark v3-btn-lg" type="submit" name="save_profile" value="1">Запази промените</button>
	</form>
<?php } ?>
		</main>
	</div>
</div>
<?php v3_footer(true); ?>
