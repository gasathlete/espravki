<?php
/* ==========================================================================
   v3 business catalogue ("Бизнеси"). Included from business-directory/index.php
   for /business-directory/ (all businesses) and /business-directory/<category>/.
   Shop-style: category tree on the left, business cards in a grid,
   search inside the catalogue, city filter, sorting and pages.
   Read-only: no data is changed here.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$V3_PREVIEW = !empty($V3_PREVIEW);
// "Продай бизнес" uses the same catalogue, limited to businesses for sale
$SALE = !empty($V3_DIR_SALE);
$BASE = $SALE ? '/business-for-sale/' : '/business-directory/';
$SW = $SALE ? ' AND a.sellbusiness = "1"' : '';
$per_page = 24;

/* ---- categories with the number of active businesses in each ---- */
$cats = array();      // id => row
$res = mysql_query('SELECT pc.id, pc.url, pc.bg_category, pc.parent, COUNT(DISTINCT a.id) n
	FROM products_categories pc
	LEFT JOIN adverts_to_product_categories t ON t.category_id = pc.id
	LEFT JOIN adverts a ON a.id = t.advert_id AND a.active = "1" AND a.deleted = "0"'.$SW.'
	WHERE pc.visible = "1" AND pc.url != "" AND pc.url != "0"
	GROUP BY pc.id') or die(send_error('dir cats',$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
while($r = mysql_fetch_assoc($res)) $cats[$r['id']] = $r;

function v3_dir_name($s){
	$s = trim($s);
	if(function_exists('mb_strtoupper')) $s = mb_strtoupper(mb_substr($s, 0, 1, 'UTF-8'), 'UTF-8').mb_substr($s, 1, mb_strlen($s, 'UTF-8'), 'UTF-8');
	return $s;
}

// groups: a top category that has sub-categories; top categories without children go to "Други"
$children = array();
foreach($cats as $id => $c) if($c['parent'] != 0 && isset($cats[$c['parent']])) $children[$c['parent']][] = $id;
$groups = array(); $other = array();
foreach($cats as $id => $c){
	if($c['parent'] != 0) continue;
	if(!empty($children[$id])) $groups[$id] = $children[$id];
	elseif($c['n'] > 0) $other[] = $id;
}

// businesses per group (distinct, a business may sit in several sub-categories)
$group_n = array();
if($groups){
	$res = mysql_query('SELECT pc.parent, COUNT(DISTINCT a.id) n FROM products_categories pc
		JOIN adverts_to_product_categories t ON t.category_id = pc.id
		JOIN adverts a ON a.id = t.advert_id AND a.active = "1" AND a.deleted = "0"'.$SW.'
		WHERE pc.visible = "1" AND pc.parent IN ('.implode(',', array_map('intval', array_keys($groups))).') GROUP BY pc.parent');
	if($res) while($r = mysql_fetch_assoc($res)) $group_n[$r['parent']] = (int)$r['n'];
}
foreach($groups as $gid => $kids){
	$cnt = isset($group_n[$gid]) ? $group_n[$gid] : 0;
	if($cnt + $cats[$gid]['n'] == 0){ unset($groups[$gid]); continue; }
	$group_n[$gid] = $cnt + (int)$cats[$gid]['n'];
	usort($groups[$gid], function($a, $b) use ($cats){ return strcmp(mb_strtolower($cats[$a]['bg_category'], 'UTF-8'), mb_strtolower($cats[$b]['bg_category'], 'UTF-8')); });
}
uasort($groups, function($a, $b){ return count($b) - count($a); });
$gorder = array_keys($groups);
usort($gorder, function($a, $b) use ($group_n){ return $group_n[$b] - $group_n[$a]; });

/* ---- what is selected ---- */
$sel = null; $sel_ids = array(); $sel_group = 0;
if(!empty($categorylink)){
	foreach($cats as $id => $c) if($c['url'] === $categorylink){ $sel = $c; break; }
	if(!$sel){
		// a category that was hidden or merged: send visitors (and Google) to the catalogue instead of an error
		$h = mysql_query('SELECT id FROM products_categories WHERE url = "'.mysql_real_escape_string($categorylink).'" LIMIT 1');
		if($h && mysql_num_rows($h)){ header('Location: '.WebSite.$BASE, true, 301); exit; }
		seo_not_found();
	}
	$sel_ids = array((int)$sel['id']);
	if(isset($groups[$sel['id']])){ $sel_group = (int)$sel['id']; foreach($groups[$sel['id']] as $k) $sel_ids[] = (int)$k; }
	elseif($sel['parent'] != 0) $sel_group = (int)$sel['parent'];
}
$q    = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$city = isset($_GET['city']) ? trim((string)$_GET['city']) : '';
$sort = isset($_GET['sort']) && in_array($_GET['sort'], array('vip','newest','az')) ? $_GET['sort'] : 'vip';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
if($q !== '' && !preg_match("/^[a-zA-Z\p{Cyrillic}0-9 '\-\.]{1,60}$/u", $q)) $q = '';
if($city !== '' && !preg_match("/^[a-zA-Z\p{Cyrillic} \-\.]{1,60}$/u", $city)) $city = '';

/* ---- the businesses ---- */
$where = 'a.active = "1" AND a.deleted = "0" AND pc.visible = "1"'.$SW;
if($sel_ids) $where .= ' AND pc.id IN ('.implode(',', $sel_ids).')';
if($city !== '') $where .= ' AND a.town = "'.mysql_real_escape_string($city).'"';
if($q !== ''){
	$e = mysql_real_escape_string(str_replace("'", '', $q));
	$where .= ' AND (REPLACE(a.supplier_name,"\'","") LIKE "%'.$e.'%" OR pc.bg_category LIKE "%'.$e.'%" OR a.meta_keywords LIKE "%'.$e.'%" OR a.meta_description LIKE "%'.$e.'%")';
}
$from = 'FROM adverts a JOIN adverts_to_product_categories t ON t.advert_id = a.id JOIN products_categories pc ON pc.id = t.category_id';

$total = 0;
$res = mysql_query('SELECT COUNT(DISTINCT a.id) '.$from.' WHERE '.$where);
if($res){ $x = mysql_fetch_row($res); $total = (int)$x[0]; }
$pages = max(1, (int)ceil($total / $per_page));
if($page > $pages) $page = $pages;

$order = array('vip' => 'a.vip DESC, a.added_date DESC', 'newest' => 'a.added_date DESC', 'az' => 'a.supplier_name ASC');
$rows = array();
$res = mysql_query('SELECT a.id, a.supplier_name, a.link_name, a.small_image, a.logo, a.town, a.company_phones, a.meta_description, a.vip, a.added_date, a.sellbusiness,
	MIN(pc.url) url, MIN(pc.bg_category) bg_category '.$from.' WHERE '.$where.'
	GROUP BY a.id ORDER BY '.$order[$sort].' LIMIT '.(($page - 1) * $per_page).', '.$per_page)
	or die(send_error('dir list',$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
while($r = mysql_fetch_assoc($res)) $rows[] = $r;

// towns that have businesses in the current selection (for the city filter)
$towns = array();
$res = mysql_query('SELECT a.town, COUNT(DISTINCT a.id) n '.$from.' WHERE a.active = "1" AND a.deleted = "0" AND pc.visible = "1"'.$SW
	.($sel_ids ? ' AND pc.id IN ('.implode(',', $sel_ids).')' : '').' AND a.town != "" GROUP BY a.town ORDER BY n DESC, a.town ASC');
if($res) while($r = mysql_fetch_assoc($res)) $towns[$r['town']] = (int)$r['n'];

/* best picture of a business: main image, logo, gallery, placeholder */
function v3_dir_thumb($ad){
	$dir = dirname(__FILE__).'/../../images/adverts/';
	$checkable = is_dir($dir);
	foreach(array('small_image', 'logo') as $k) if(!empty($ad[$k]) && (!$checkable || is_file($dir.$ad[$k]))) return WebSite.'/images/adverts/'.$ad[$k];
	$g = mysql_query('SELECT image FROM adverts_gallery WHERE advert_id = "'.(int)$ad['id'].'" ORDER BY order_id ASC LIMIT 5');
	if($g) while($x = mysql_fetch_row($g)) if(!empty($x[0]) && (!$checkable || is_file($dir.$x[0]))) return WebSite.'/images/adverts/'.$x[0];
	return '';
}
// link that keeps the current filters
function v3_dir_link($cat_url, $change = array()){
	$p = array();
	foreach(array('q','city','sort') as $k) if(isset($_GET[$k]) && $_GET[$k] !== '' && !($k == 'sort' && $_GET[$k] == 'vip')) $p[$k] = $_GET[$k];
	foreach($change as $k => $v){ if($v === null || $v === '') unset($p[$k]); else $p[$k] = $v; }
	if(!empty($GLOBALS['V3_PREVIEW'])) $p['preview'] = 'v3';
	return v3_url($GLOBALS['BASE'].($cat_url !== '' ? rawurlencode($cat_url).'/' : '').($p ? '?'.http_build_query($p) : ''));
}
$cur_url = $sel ? $sel['url'] : '';

/* ---- head / SEO ---- */
$title = $sel ? v3_dir_name($sel['bg_category']).($SALE ? ' за продажба' : '') : ($SALE ? 'Готов бизнес за продажба' : 'Всички бизнеси');
if($city !== '') $title .= ' в '.$city;
$canon = $BASE.($sel ? $sel['url'].'/' : '');
$meta  = '<title>'.v3_e($title.($page > 1 ? ' – страница '.$page : '')).' | Espravki.com</title>';
$meta .= '<meta name="description" content="'.v3_e(($sel ? v3_dir_name($sel['bg_category']).': ' : ($SALE ? 'Готов бизнес за продажба в България: ' : 'Каталог на фирмите в България по категории: ')).$total.' бизнеса с контакти, снимки, продукти и отзиви в Espravki.com.').'" />';
$filtered = ($q !== '' || $city !== '' || $sort != 'vip');
if($V3_PREVIEW || $filtered) $meta .= '<meta name="robots" content="noindex, follow" />';
else {
	$meta .= '<meta name="robots" content="index, follow" />';
	if($page > 1){ $u = v3_e(seo_canonical_url($canon).'?page='.$page); $meta .= '<link rel="canonical" href="'.$u.'" /><meta property="og:url" content="'.$u.'" />'; }
	else $meta .= seo_canonical_tags($canon);
}
$meta .= '<meta property="og:title" content="'.v3_e($title).' | Espravki.com" /><meta property="og:type" content="website" />';
// breadcrumbs for Google
$crumbs = array(array('Начало', WebSite.'/'), array($SALE ? 'Продай бизнес' : 'Бизнеси', WebSite.$BASE));
if($sel_group && $sel && $sel_group != $sel['id']) $crumbs[] = array(v3_dir_name($cats[$sel_group]['bg_category']), seo_canonical_url($BASE.$cats[$sel_group]['url'].'/'));
if($sel) $crumbs[] = array(v3_dir_name($sel['bg_category']), seo_canonical_url($canon));
$ld = array('@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => array());
foreach($crumbs as $i => $c) $ld['itemListElement'][] = array('@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0], 'item' => $c[1]);
$meta .= '<script type="application/ld+json">'.str_replace('</', '<\/', json_encode($ld)).'</script>';

v3_head($meta);
v3_header();

$all_n = 0; $res = mysql_query('SELECT COUNT(DISTINCT a.id) '.$from.' WHERE a.active = "1" AND a.deleted = "0" AND pc.visible = "1"'.$SW); if($res){ $x = mysql_fetch_row($res); $all_n = (int)$x[0]; }

function v3_dir_cat_item($c, $cur_url, $cls = ''){
	$on = ($c['url'] === $cur_url);
	return '<li><a class="'.trim($cls.($on ? ' is-on' : '')).'" href="'.v3_dir_link($c['url'], array('page' => null)).'"'.($on ? ' aria-current="page"' : '').'><span>'.v3_e(v3_dir_name($c['bg_category'])).'</span><em>'.(int)$c['n'].'</em></a></li>';
}
?>
<section class="v3-dir-hero">
	<div class="v3-wrap">
		<nav class="v3-crumbs" aria-label="Път">
			<?php foreach($crumbs as $i => $c){ if($i) echo ' <span aria-hidden="true">/</span> ';
				echo $i < count($crumbs) - 1 ? '<a href="'.v3_e(str_replace(WebSite, v3_url(''), $c[1])).'">'.v3_e($c[0]).'</a>' : '<span>'.v3_e($c[0]).'</span>'; } ?>
		</nav>
		<h1 class="v3-h2"><?php echo v3_e($sel ? v3_dir_name($sel['bg_category']) : ($SALE ? 'Готов бизнес за продажба' : 'Бизнеси в България')); ?></h1>
		<p class="v3-muted"><?php echo $sel ? v3_e($total.' '.($total == 1 ? 'бизнес' : 'бизнеса').' в тази категория.') : v3_e($SALE ? $all_n.' работещи бизнеса, заведения, магазини и обекти, които се продават.' : $all_n.' фирми, занаятчии и специалисти, подредени по категории.'); ?></p>
		<form class="v3-dir-search v3-card" action="<?php echo v3_e(v3_url($BASE.($cur_url !== '' ? rawurlencode($cur_url).'/' : ''))); ?>" method="get" role="search">
			<label class="v3-field"><?php echo v3_icon('search'); ?><input type="search" name="q" value="<?php echo v3_e($q); ?>" placeholder="<?php echo $sel ? 'Търси в тази категория' : 'Име на фирма или дейност'; ?>" aria-label="Търси" /></label>
			<label class="v3-field v3-field-select"><?php echo v3_icon('pin'); ?>
				<select name="city" aria-label="Град"><option value="">Всички градове</option>
				<?php foreach($towns as $tw => $n) echo '<option value="', v3_e($tw), '"', ($tw === $city ? ' selected' : ''), '>', v3_e($tw), ' (', $n, ')</option>'; ?>
				</select></label>
			<?php if($sort != 'vip') echo '<input type="hidden" name="sort" value="', v3_e($sort), '" />'; if($V3_PREVIEW) echo '<input type="hidden" name="preview" value="v3" />'; ?>
			<button class="v3-btn v3-btn-blue" type="submit">Търси</button>
		</form>
	</div>
</section>

<div class="v3-wrap v3-dir-wrap">
	<aside class="v3-dir-cats">
		<details class="v3-card" id="v3-dir-cats" <?php echo 'open'; ?>>
			<summary><span>Категории</span><?php echo v3_icon('grid', 18); ?></summary>
			<ul class="v3-dir-tree">
				<li><a class="<?php echo $sel ? '' : 'is-on'; ?>" href="<?php echo v3_dir_link('', array('page' => null)); ?>"><span>Всички бизнеси</span><em><?php echo $all_n; ?></em></a></li>
				<?php foreach($gorder as $gid){ $g = $cats[$gid]; $open = ($sel_group == $gid || ($sel && $sel['id'] == $gid)); ?>
				<li class="v3-dir-group">
					<details<?php if($open) echo ' open'; ?>>
						<summary><span><?php echo v3_e(v3_dir_name($g['bg_category'])); ?></span><em><?php echo (int)$group_n[$gid]; ?></em></summary>
						<ul>
							<?php echo v3_dir_cat_item(array('url' => $g['url'], 'bg_category' => 'Всички в „'.v3_dir_name($g['bg_category']).'“', 'n' => $group_n[$gid]), $cur_url, 'v3-dir-all');
							foreach($groups[$gid] as $kid) if($cats[$kid]['n'] > 0) echo v3_dir_cat_item($cats[$kid], $cur_url); ?>
						</ul>
					</details>
				</li>
				<?php } ?>
				<?php if($other){ usort($other, function($a, $b) use ($cats){ return $cats[$b]['n'] - $cats[$a]['n']; }); $open = $sel && in_array($sel['id'], $other); ?>
				<li class="v3-dir-group">
					<details<?php if($open) echo ' open'; ?>>
						<summary><span>Други</span><em><?php $s = 0; foreach($other as $o) $s += $cats[$o]['n']; echo $s; ?></em></summary>
						<ul><?php foreach($other as $o) echo v3_dir_cat_item($cats[$o], $cur_url); ?></ul>
					</details>
				</li>
				<?php } ?>
			</ul>
		</details>
		<div class="v3-card v3-dir-cta">
			<strong><?php echo $SALE ? 'Продаваш бизнес?' : 'Твоят бизнес го няма?'; ?></strong>
			<p><?php echo $SALE ? 'Публикувай обява с описание, снимки и цена. Купувачите се свързват директно с теб.' : 'Добави го за няколко минути: профил, снимки, продукти и контакти.'; ?></p>
			<a class="v3-btn v3-btn-orange v3-btn-block" href="<?php echo v3_e(v3_cta_url()); ?>"><?php echo v3_e(v3_cta_label()); ?></a>
		</div>
	</aside>

	<section class="v3-dir-main">
		<div class="v3-results-head">
			<p class="v3-muted"><?php
				if($total) echo 'Показани ', (($page - 1) * $per_page + 1), '–', min($total, $page * $per_page), ' от ', $total, ($q !== '' ? ' за „'.v3_e($q).'“' : ''), ($city !== '' ? ' в '.v3_e($city) : '');
				?></p>
			<form method="get" action="<?php echo v3_e(v3_url($BASE.($cur_url !== '' ? rawurlencode($cur_url).'/' : ''))); ?>" class="v3-sort">
				<?php foreach(array('q' => $q, 'city' => $city) as $k => $v) if($v !== '') echo '<input type="hidden" name="', $k, '" value="', v3_e($v), '" />'; if($V3_PREVIEW) echo '<input type="hidden" name="preview" value="v3" />'; ?>
				<label>Подреди:
					<select name="sort" onchange="this.form.submit()">
						<option value="vip"<?php if($sort == 'vip') echo ' selected'; ?>>Препоръчани</option>
						<option value="newest"<?php if($sort == 'newest') echo ' selected'; ?>>Най-нови</option>
						<option value="az"<?php if($sort == 'az') echo ' selected'; ?>>По азбучен ред</option>
					</select>
				</label>
				<noscript><button class="v3-btn v3-btn-line v3-btn-sm">OK</button></noscript>
			</form>
		</div>

		<?php if($sel_group && isset($groups[$sel['id']])){ ?>
		<div class="v3-chips v3-dir-sub">
			<?php foreach($groups[$sel['id']] as $kid) if($cats[$kid]['n'] > 0) echo '<a class="v3-chip-link" href="', v3_dir_link($cats[$kid]['url'], array('page' => null)), '">', v3_e(v3_dir_name($cats[$kid]['bg_category'])), ' <em>', (int)$cats[$kid]['n'], '</em></a>'; ?>
		</div>
		<?php } ?>

		<?php if($rows){ ?>
		<div class="v3-dir-grid">
			<?php foreach($rows as $r){
				$url = v3_url($BASE.$r['url'].'/'.$r['link_name']);
				$ph = ''; foreach(explode(';', (string)$r['company_phones']) as $x){ $x = trim($x); if($x !== ''){ $ph = $x; break; } }
				$is_new = (time() - strtotime($r['added_date'])) < 14 * 86400;
			?>
			<article class="v3-dir-card v3-card">
				<?php $img = v3_dir_thumb($r); ?>
				<a class="v3-dir-img<?php if(!$img) echo ' v3-dir-noimg'; ?>" href="<?php echo $url; ?>" tabindex="-1" aria-hidden="true"><?php
					if($img) echo '<img src="', v3_e($img), '" alt="" loading="lazy" width="400" height="250" />';
					else echo '<span>', v3_e(function_exists('mb_substr') ? mb_strtoupper(mb_substr(trim(html_entity_decode($r['supplier_name'], ENT_QUOTES, 'UTF-8')), 0, 1, 'UTF-8'), 'UTF-8') : substr($r['supplier_name'], 0, 1)), '</span>';
				?></a>
				<?php if($r['sellbusiness'] == 1) echo '<span class="v3-badge v3-badge-sale v3-dir-flag">Продава се</span>'; elseif($is_new) echo '<span class="v3-badge v3-dir-flag v3-dir-new">Нов</span>'; elseif($r['vip'] > 0) echo '<span class="v3-badge v3-dir-flag v3-dir-vip">Препоръчан</span>'; ?>
				<div class="v3-dir-body">
					<p class="v3-dir-cat"><?php echo v3_e(v3_dir_name($r['bg_category'])); ?></p>
					<h2><a href="<?php echo $url; ?>"><?php echo v3_t($r['supplier_name']); ?></a></h2>
					<?php if($r['town']) echo '<p class="v3-dir-town">', v3_icon('pin', 15), ' ', v3_t($r['town']), '</p>'; ?>
					<p class="v3-dir-desc"><?php echo v3_excerpt($r['meta_description'], 110); ?></p>
				</div>
				<div class="v3-dir-actions">
					<a class="v3-btn v3-btn-dark v3-btn-sm" href="<?php echo $url; ?>">Виж профила</a>
					<?php if($ph){ ?><a class="v3-btn v3-btn-line v3-btn-sm" href="tel:<?php echo v3_e(preg_replace('/[^0-9+]/', '', $ph)); ?>" data-track-call="<?php echo (int)$r['id']; ?>" aria-label="Обади се на <?php echo v3_t($r['supplier_name']); ?>"><?php echo v3_icon('phone', 16); ?> Обади се</a><?php } ?>
				</div>
			</article>
			<?php } ?>
		</div>
		<?php }else{ ?>
		<div class="v3-card v3-empty">
			<h2 class="v3-h3">Няма намерени бизнеси.</h2>
			<p class="v3-muted">Опитай с друга дума или друг град, или <a href="<?php echo v3_dir_link('', array('q' => null, 'city' => null, 'page' => null)); ?>">виж всички бизнеси</a>.</p>
		</div>
		<?php } ?>

		<?php if($pages > 1){ ?>
		<nav class="v3-pager" aria-label="Страници">
			<?php if($page > 1){ ?><a href="<?php echo v3_dir_link($cur_url, array('page' => $page > 2 ? $page - 1 : null)); ?>" rel="prev" aria-label="Предишна">‹</a><?php } ?>
			<?php
			$shown = array_unique(array_merge(array(1), range(max(1, $page - 2), min($pages, $page + 2)), array($pages)));
			sort($shown); $prev = 0;
			foreach($shown as $i){
				if($prev && $i > $prev + 1) echo '<span class="v3-pager-gap">…</span>';
				echo '<a href="', v3_dir_link($cur_url, array('page' => $i > 1 ? $i : null)), '"', ($i == $page ? ' class="is-on" aria-current="page"' : ''), '>', $i, '</a>';
				$prev = $i;
			} ?>
			<?php if($page < $pages){ ?><a href="<?php echo v3_dir_link($cur_url, array('page' => $page + 1)); ?>" rel="next" aria-label="Следваща">›</a><?php } ?>
		</nav>
		<?php } ?>
	</section>
</div>
<script>
// on phones the category list starts folded so the businesses are visible first
(function(){ var d = document.getElementById('v3-dir-cats'); if(d && window.matchMedia && window.matchMedia('(max-width: 900px)').matches) d.removeAttribute('open'); })();
</script>
<?php v3_footer(); ?>
