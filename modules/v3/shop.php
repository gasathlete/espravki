<?php
/* ==========================================================================
   v3 shop list "Продукти и услуги". Included from products/index.php for
   /products/ and /products/<category>/ (single products use product.php).
   Category tree (shop_products_categories, up to 3 levels), product cards,
   search, sorting and pages. Same look as the business catalogue.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$V3_PREVIEW = !empty($V3_PREVIEW);
$per_page = 24;
$live = 'p.active = "1" AND p.sold = "0" AND p.deleted = "0" AND p.checked_by_admin = "1" AND a.active = "1" AND a.deleted = "0"';

/* ---- categories, with products counted up the tree ---- */
$cats = array();
$res = mysql_query('SELECT id, url, bg_category, parent FROM shop_products_categories WHERE visible = "1" AND url != ""');
if($res) while($r = mysql_fetch_assoc($res)){ $r['n'] = 0; $r['kids'] = array(); $cats[$r['id']] = $r; }
$own = array();
$res = mysql_query('SELECT p.category_id, COUNT(*) n FROM products p JOIN adverts a ON a.id = p.advert_id WHERE '.$live.' GROUP BY p.category_id');
if($res) while($r = mysql_fetch_assoc($res)) $own[$r['category_id']] = (int)$r['n'];
foreach($cats as $id => $c) if($c['parent'] && isset($cats[$c['parent']])) $cats[$c['parent']]['kids'][] = $id;
foreach($own as $cid => $n){
	$guard = 0; $x = $cid;
	while(isset($cats[$x]) && $guard++ < 6){ $cats[$x]['n'] += $n; $x = $cats[$x]['parent']; }
}
function v3_shop_desc($cats, $id, &$out, $d = 0){ $out[] = (int)$id; if($d < 6 && isset($cats[$id])) foreach($cats[$id]['kids'] as $k) v3_shop_desc($cats, $k, $out, $d + 1); }
function v3_shop_name($s){
	$s = trim($s);
	if(function_exists('mb_strtoupper')) $s = mb_strtoupper(mb_substr($s, 0, 1, 'UTF-8'), 'UTF-8').mb_substr($s, 1, mb_strlen($s, 'UTF-8'), 'UTF-8');
	return $s;
}
$sortn = function($a, $b) use (&$cats){ return $cats[$b]['n'] - $cats[$a]['n']; };
$tops = array();
foreach($cats as $id => $c) if((!$c['parent'] || !isset($cats[$c['parent']])) && $c['n'] > 0) $tops[] = $id;
usort($tops, $sortn);

/* ---- selection ---- */
$sel = null; $sel_ids = array(); $path = array();
if(!empty($categorylink)){
	foreach($cats as $id => $c) if($c['url'] === $categorylink){ $sel = $c; break; }
	if(!$sel) seo_not_found();
	v3_shop_desc($cats, $sel['id'], $sel_ids);
	$x = $sel['id']; $g = 0; while(isset($cats[$x]) && $g++ < 6){ array_unshift($path, $x); $x = $cats[$x]['parent']; }
}
$q    = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$sort = isset($_GET['sort']) && in_array($_GET['sort'], array('vip','newest','cheap','dear')) ? $_GET['sort'] : 'vip';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
if($q !== '' && !preg_match("/^[a-zA-Z\p{Cyrillic}0-9 '\-\.,]{1,60}$/u", $q)) $q = '';

$where = $live;
if($sel_ids) $where .= ' AND p.category_id IN ('.implode(',', $sel_ids).')';
if($q !== ''){
	foreach(preg_split('/\s+/u', $q) as $w){
		if(mb_strlen($w, 'UTF-8') < 2) continue;
		$e = mysql_real_escape_string(str_replace(array('%','_',"'"), '', $w));
		$where .= ' AND (p.title LIKE "%'.$e.'%" OR p.product_tags LIKE "%'.$e.'%" OR p.category_name LIKE "%'.$e.'%" OR a.supplier_name LIKE "%'.$e.'%")';
	}
}
$from = 'FROM products p JOIN adverts a ON a.id = p.advert_id';
$total = 0; $res = mysql_query('SELECT COUNT(*) '.$from.' WHERE '.$where); if($res){ $x = mysql_fetch_row($res); $total = (int)$x[0]; }
$pages = max(1, (int)ceil($total / $per_page)); if($page > $pages) $page = $pages;
$price = 'IF(p.promo_price > 0 AND p.promo_price < p.price, p.promo_price, p.price)';
$order = array('vip' => 'a.vip DESC, p.addeddate DESC', 'newest' => 'p.addeddate DESC', 'cheap' => $price.' ASC', 'dear' => $price.' DESC');
$rows = array();
$res = mysql_query('SELECT p.id, p.title, p.price, p.promo_price, p.image, p.product_url, p.category_url, p.category_name, p.addeddate, a.supplier_name, a.town, a.id aid
	'.$from.' WHERE '.$where.' ORDER BY '.$order[$sort].' LIMIT '.(($page - 1) * $per_page).', '.$per_page)
	or die(send_error('shop list',$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
while($r = mysql_fetch_assoc($res)) $rows[] = $r;

function v3_shop_img($img){
	if(empty($img)) return '';
	$dir = dirname(__FILE__).'/../../images/products/';
	if(is_dir($dir) && !is_file($dir.$img)) return '';
	return WebSite.'/images/products/'.$img;
}
function v3_shop_link($cat_url, $change = array()){
	$p = array();
	foreach(array('q','sort') as $k) if(isset($_GET[$k]) && $_GET[$k] !== '' && !($k == 'sort' && $_GET[$k] == 'vip')) $p[$k] = $_GET[$k];
	foreach($change as $k => $v){ if($v === null || $v === '') unset($p[$k]); else $p[$k] = $v; }
	if(!empty($GLOBALS['V3_PREVIEW'])) $p['preview'] = 'v3';
	return v3_url('/products/'.($cat_url !== '' ? rawurlencode($cat_url).'/' : '').($p ? '?'.http_build_query($p) : ''));
}
function v3_money($n){ return number_format((float)$n, 2, ',', ' ').' €'; }
$cur_url = $sel ? $sel['url'] : '';

/* ---- head ---- */
$title = $sel ? v3_shop_name($sel['bg_category']) : 'Продукти и услуги';
$canon = '/products/'.($sel ? $sel['url'].'/' : '');
$meta  = '<title>'.v3_e($title.($page > 1 ? ' – страница '.$page : '')).' | Espravki.com</title>';
$meta .= '<meta name="description" content="'.v3_e(($sel ? v3_shop_name($sel['bg_category']).': ' : 'Продукти и услуги от български фирми: ').$total.' предложения с цени и директен контакт с търговеца в Espravki.com.').'" />';
if($V3_PREVIEW || $q !== '' || $sort != 'vip') $meta .= '<meta name="robots" content="noindex, follow" />';
else {
	$meta .= '<meta name="robots" content="index, follow" />';
	if($page > 1){ $u = v3_e(seo_canonical_url($canon).'?page='.$page); $meta .= '<link rel="canonical" href="'.$u.'" /><meta property="og:url" content="'.$u.'" />'; }
	else $meta .= seo_canonical_tags($canon);
}
$meta .= '<meta property="og:title" content="'.v3_e($title).' | Espravki.com" /><meta property="og:type" content="website" />';
$crumbs = array(array('Начало', WebSite.'/'), array('Продукти и услуги', WebSite.'/products/'));
foreach($path as $pid_) $crumbs[] = array(v3_shop_name($cats[$pid_]['bg_category']), seo_canonical_url('/products/'.$cats[$pid_]['url'].'/'));
$ld = array('@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => array());
foreach($crumbs as $i => $c) $ld['itemListElement'][] = array('@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0], 'item' => $c[1]);
$meta .= '<script type="application/ld+json">'.str_replace('</', '<\/', json_encode($ld)).'</script>';

v3_head($meta);
v3_header();
$all_n = 0; foreach($tops as $t) $all_n += $cats[$t]['n'];
$open_top = $path ? $path[0] : 0;
$sub_of = $sel ? (int)$sel['id'] : 0;
?>
<section class="v3-dir-hero">
	<div class="v3-wrap">
		<nav class="v3-crumbs" aria-label="Път">
			<?php foreach($crumbs as $i => $c){ if($i) echo ' <span aria-hidden="true">/</span> ';
				echo $i < count($crumbs) - 1 ? '<a href="'.v3_e($c[1]).'">'.v3_e($c[0]).'</a>' : '<span>'.v3_e($c[0]).'</span>'; } ?>
		</nav>
		<h1 class="v3-h2"><?php echo v3_e($title); ?></h1>
		<p class="v3-muted"><?php echo $sel ? v3_e($total.' '.($total == 1 ? 'предложение' : 'предложения').' в тази категория.') : v3_e($all_n.' продукта и услуги от български фирми. Купуваш директно от търговеца.'); ?></p>
		<form class="v3-dir-search v3-shop-search v3-card" action="<?php echo v3_e(v3_url('/products/'.($cur_url !== '' ? rawurlencode($cur_url).'/' : ''))); ?>" method="get" role="search">
			<label class="v3-field"><?php echo v3_icon('search'); ?><input type="search" name="q" value="<?php echo v3_e($q); ?>" placeholder="<?php echo $sel ? 'Търси в тази категория' : 'Какво търсиш? Напр. подарък, стикер'; ?>" aria-label="Търси" /></label>
			<?php if($sort != 'vip') echo '<input type="hidden" name="sort" value="', v3_e($sort), '" />'; if($V3_PREVIEW) echo '<input type="hidden" name="preview" value="v3" />'; ?>
			<button class="v3-btn v3-btn-blue" type="submit">Търси</button>
		</form>
	</div>
</section>

<div class="v3-wrap v3-dir-wrap">
	<aside class="v3-dir-cats">
		<details class="v3-card" id="v3-dir-cats" open>
			<summary><span>Категории</span><?php echo v3_icon('grid', 18); ?></summary>
			<ul class="v3-dir-tree">
				<li><a class="<?php echo $sel ? '' : 'is-on'; ?>" href="<?php echo v3_shop_link('', array('page' => null)); ?>"><span>Всички</span><em><?php echo $all_n; ?></em></a></li>
				<?php foreach($tops as $t){ $c = $cats[$t]; $kids = array_values(array_filter($c['kids'], function($k) use (&$cats){ return $cats[$k]['n'] > 0; })); usort($kids, $sortn);
					if(!$kids){ $on = ($c['url'] === $cur_url); ?>
				<li><a class="<?php echo $on ? 'is-on' : ''; ?>" href="<?php echo v3_shop_link($c['url'], array('page' => null)); ?>"><span><?php echo v3_e(v3_shop_name($c['bg_category'])); ?></span><em><?php echo $c['n']; ?></em></a></li>
				<?php continue; } ?>
				<li class="v3-dir-group">
					<details<?php if($open_top == $t) echo ' open'; ?>>
						<summary><span><?php echo v3_e(v3_shop_name($c['bg_category'])); ?></span><em><?php echo $c['n']; ?></em></summary>
						<ul>
							<li><a class="v3-dir-all<?php if($c['url'] === $cur_url) echo ' is-on'; ?>" href="<?php echo v3_shop_link($c['url'], array('page' => null)); ?>"><span>Всички в „<?php echo v3_e(v3_shop_name($c['bg_category'])); ?>“</span><em><?php echo $c['n']; ?></em></a></li>
							<?php foreach($kids as $k){ $on = ($cats[$k]['url'] === $cur_url || in_array($k, $path)); ?>
							<li><a class="<?php echo $on ? 'is-on' : ''; ?>" href="<?php echo v3_shop_link($cats[$k]['url'], array('page' => null)); ?>"><span><?php echo v3_e(v3_shop_name($cats[$k]['bg_category'])); ?></span><em><?php echo $cats[$k]['n']; ?></em></a></li>
							<?php } ?>
						</ul>
					</details>
				</li>
				<?php } ?>
			</ul>
		</details>
		<div class="v3-card v3-dir-cta">
			<strong>Продаваш нещо?</strong>
			<p>Добави продуктите си с цени и снимки. Клиентите поръчват директно от теб.</p>
			<a class="v3-btn v3-btn-orange v3-btn-block" href="<?php echo v3_e(v3_cta_url()); ?>"><?php echo v3_e(v3_cta_label()); ?></a>
		</div>
	</aside>

	<section class="v3-dir-main">
		<div class="v3-results-head">
			<p class="v3-muted"><?php if($total) echo 'Показани ', (($page - 1) * $per_page + 1), '–', min($total, $page * $per_page), ' от ', $total, ($q !== '' ? ' за „'.v3_e($q).'“' : ''); ?></p>
			<form method="get" action="<?php echo v3_e(v3_url('/products/'.($cur_url !== '' ? rawurlencode($cur_url).'/' : ''))); ?>" class="v3-sort">
				<?php if($q !== '') echo '<input type="hidden" name="q" value="', v3_e($q), '" />'; if($V3_PREVIEW) echo '<input type="hidden" name="preview" value="v3" />'; ?>
				<label>Подреди:
					<select name="sort" onchange="this.form.submit()">
						<option value="vip"<?php if($sort == 'vip') echo ' selected'; ?>>Препоръчани</option>
						<option value="newest"<?php if($sort == 'newest') echo ' selected'; ?>>Най-нови</option>
						<option value="cheap"<?php if($sort == 'cheap') echo ' selected'; ?>>Най-ниска цена</option>
						<option value="dear"<?php if($sort == 'dear') echo ' selected'; ?>>Най-висока цена</option>
					</select>
				</label>
				<noscript><button class="v3-btn v3-btn-line v3-btn-sm">OK</button></noscript>
			</form>
		</div>

		<?php if($sel && $sel['kids']){ $kids = array_values(array_filter($sel['kids'], function($k) use (&$cats){ return $cats[$k]['n'] > 0; })); usort($kids, $sortn); if($kids){ ?>
		<div class="v3-chips v3-dir-sub">
			<?php foreach($kids as $k) echo '<a class="v3-chip-link" href="', v3_shop_link($cats[$k]['url'], array('page' => null)), '">', v3_e(v3_shop_name($cats[$k]['bg_category'])), ' <em>', $cats[$k]['n'], '</em></a>'; ?>
		</div>
		<?php } } ?>

		<?php if($rows){ ?>
		<div class="v3-dir-grid v3-shop-grid">
			<?php foreach($rows as $r){
				$url = v3_url('/products/'.$r['category_url'].'/'.$r['product_url']);
				$img = v3_shop_img($r['image']);
				$promo = ((float)$r['promo_price'] > 0 && (float)$r['promo_price'] < (float)$r['price']);
			?>
			<article class="v3-dir-card v3-card v3-shop-card">
				<?php echo v3_fav_btn($r['id'], 'v3-fav-float'); ?>
				<a class="v3-dir-img v3-shop-img<?php if(!$img) echo ' v3-dir-noimg'; ?>" href="<?php echo $url; ?>" tabindex="-1" aria-hidden="true"><?php
					echo $img ? '<img src="'.v3_e($img).'" alt="" loading="lazy" width="400" height="400" />' : '<span>'.v3_e(mb_strtoupper(mb_substr(trim(html_entity_decode($r['title'], ENT_QUOTES, 'UTF-8')), 0, 1, 'UTF-8'), 'UTF-8')).'</span>'; ?></a>
				<?php if($promo) echo '<span class="v3-badge v3-dir-flag v3-shop-promo">−', round(100 - 100 * $r['promo_price'] / $r['price']), '%</span>'; elseif(time() - strtotime($r['addeddate']) < 14 * 86400) echo '<span class="v3-badge v3-dir-flag v3-dir-new">Ново</span>'; ?>
				<div class="v3-dir-body">
					<p class="v3-dir-cat"><?php echo v3_e(v3_shop_name($r['category_name'])); ?></p>
					<h2><a href="<?php echo $url; ?>"><?php echo v3_excerpt($r['title'], 80); ?></a></h2>
					<p class="v3-shop-price"><?php if((float)$r['price'] > 0){ if($promo) echo '<b>', v3_money($r['promo_price']), '</b> <s>', v3_money($r['price']), '</s>'; else echo '<b>', v3_money($r['price']), '</b>'; } else echo '<b>Цена по запитване</b>'; ?></p>
					<p class="v3-dir-town"><?php echo v3_icon('user', 14), ' ', v3_t($r['supplier_name']); ?><?php if($r['town']) echo ' · ', v3_t($r['town']); ?></p>
				</div>
				<div class="v3-dir-actions"><a class="v3-btn v3-btn-dark v3-btn-sm" href="<?php echo $url; ?>">Виж</a></div>
			</article>
			<?php } ?>
		</div>
		<?php }else{ ?>
		<div class="v3-card v3-empty">
			<h2 class="v3-h3">Няма намерени предложения.</h2>
			<p class="v3-muted">Опитай с друга дума или <a href="<?php echo v3_shop_link('', array('q' => null, 'page' => null)); ?>">виж всички продукти и услуги</a>.</p>
		</div>
		<?php } ?>

		<?php if($pages > 1){ ?>
		<nav class="v3-pager" aria-label="Страници">
			<?php if($page > 1){ ?><a href="<?php echo v3_shop_link($cur_url, array('page' => $page > 2 ? $page - 1 : null)); ?>" rel="prev" aria-label="Предишна">‹</a><?php } ?>
			<?php $shown = array_unique(array_merge(array(1), range(max(1, $page - 2), min($pages, $page + 2)), array($pages))); sort($shown); $prev = 0;
			foreach($shown as $i){ if($prev && $i > $prev + 1) echo '<span class="v3-pager-gap">…</span>';
				echo '<a href="', v3_shop_link($cur_url, array('page' => $i > 1 ? $i : null)), '"', ($i == $page ? ' class="is-on" aria-current="page"' : ''), '>', $i, '</a>'; $prev = $i; } ?>
			<?php if($page < $pages){ ?><a href="<?php echo v3_shop_link($cur_url, array('page' => $page + 1)); ?>" rel="next" aria-label="Следваща">›</a><?php } ?>
		</nav>
		<?php } ?>
	</section>
</div>
<script>
(function(){ var d = document.getElementById('v3-dir-cats'); if(d && window.matchMedia && window.matchMedia('(max-width: 900px)').matches) d.removeAttribute('open'); })();
</script>
<?php v3_footer(); ?>
