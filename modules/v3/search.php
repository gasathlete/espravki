<?php
/* ==========================================================================
   v3 search results (design A10). Included from search/index.php.
   Uses search/search_query.php, the same query builder as the classic page,
   so results are identical; only presentation, filters and paging are new.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

// a chosen city should really filter (classic page needed a hidden "city_match" flag)
if(!empty($_GET['search_city'])) $_GET['city_match'] = 1;

include dirname(__FILE__).'/../../search/search_query.php';   // builds $query / $allquery

$per_page = 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$all = mysql_query($allquery) or die(send_error($allquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$total = mysql_num_rows($all);
$towns = array();
while($t = mysql_fetch_assoc($all)) if($t['town'] !== '' && !isset($towns[$t['town']])) $towns[$t['town']] = 1;
$towns = array_keys($towns);
sort($towns, SORT_STRING);
$pages = max(1, (int)ceil($total / $per_page));
if($page > $pages) $page = $pages;

$rows = array();
$res = mysql_query($allquery.' LIMIT '.(($page - 1) * $per_page).', '.$per_page) or die(send_error($allquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
while($r = mysql_fetch_assoc($res)) $rows[] = $r;

$q = isset($_GET['search']) ? $_GET['search'] : '';
$sel_city = isset($_GET['search_city']) ? $_GET['search_city'] : '';
$sel_cat = (!empty($_GET['category']) && $_GET['category'] !== '0') ? $_GET['category'] : '';
$sort = isset($_GET['sort']) && in_array($_GET['sort'], array('vip','newest','distance')) ? $_GET['sort'] : 'vip';

// link that keeps the current filters
function v3_search_link($change){
	$p = array();
	foreach(array('search','search_city','category','sort','exact_match') as $k) if(isset($_GET[$k]) && $_GET[$k] !== '' && $_GET[$k] !== '0') $p[$k] = $_GET[$k];
	foreach($change as $k => $v){ if($v === null || $v === '') unset($p[$k]); else $p[$k] = $v; }
	if(!empty($GLOBALS['V3_PREVIEW'])) $p['preview'] = 'v3';
	return v3_url('/search/'.($p ? '?'.http_build_query($p) : ''));
}

$heading = $q !== '' ? 'Резултати за „'.v3_e($q).'“' : ($categoryname ? v3_e(function_exists('mb_convert_case') ? mb_convert_case($categoryname, MB_CASE_TITLE, 'UTF-8') : $categoryname) : 'Резултати');
if($sel_city) $heading .= ' в '.v3_e($sel_city);

$meta  = '<title>'.strip_tags(html_entity_decode($heading, ENT_QUOTES, 'UTF-8')).' | Espravki.com</title>';
$meta .= '<meta name="description" content="Фирми и услуги'.($q !== '' ? ' за '.seo_attr($q) : '').($sel_city ? ' в '.seo_attr($sel_city) : '').' в каталога Espravki.com." />';
$meta .= empty($V3_PREVIEW) ? search_seo_tags($total) : '<meta name="robots" content="noindex, follow" />';

v3_head($meta);
v3_header();
?>
<section class="v3-search-hero">
	<div class="v3-wrap">
		<h1 class="v3-h2 v3-center">Какво търсиш днес?</h1>
		<p class="v3-center v3-muted">Открий бизнеси, продукти и услуги в цяла България.</p>
		<form class="v3-card v3-search-big" action="<?php echo v3_url('/search/'); ?>" method="get" role="search">
			<label><span>Какво търсиш?</span><span class="v3-field"><?php echo v3_icon('search'); ?><input type="search" name="search" value="<?php echo v3_e($q); ?>" placeholder="Напр. счетоводител" /></span></label>
			<label><span>Град</span><span class="v3-field"><?php echo v3_icon('pin'); ?><input type="text" name="search_city" list="v3-cities" value="<?php echo v3_e($sel_city); ?>" placeholder="Всички градове" autocomplete="off" /></span></label>
			<label><span>Категория</span><span class="v3-field v3-field-select"><?php echo v3_icon('grid'); ?><?php echo v3_category_select($sel_cat); ?></span></label>
			<input type="hidden" name="exact_match" value="0" />
			<?php if(!empty($V3_PREVIEW)) echo '<input type="hidden" name="preview" value="v3" />'; ?>
			<button class="v3-btn v3-btn-blue v3-btn-lg" type="submit"><?php echo v3_icon('search', 20); ?> Търси</button>
			<datalist id="v3-cities"><?php foreach(echo_cities() as $c) echo '<option value="', v3_e($c), '">'; ?></datalist>
		</form>
	</div>
</section>

<div class="v3-wrap v3-results-wrap">
	<aside class="v3-filters v3-card">
		<h2 class="v3-h3">Филтри</h2>
		<?php if($towns){ ?>
		<details open><summary>Град</summary>
			<ul class="v3-filter-list">
				<?php foreach(array_slice($towns, 0, 25) as $tw){ $on = ($tw == $sel_city); ?>
				<li><a class="<?php echo $on ? 'is-on' : ''; ?>" href="<?php echo v3_search_link(array('search_city' => $on ? null : $tw, 'page' => null)); ?>"><span class="v3-box"><?php if($on) echo v3_icon('check', 14); ?></span><?php echo v3_t($tw); ?></a></li>
				<?php } ?>
			</ul>
		</details>
		<?php } ?>
		<?php if($sel_city || $sel_cat){ ?><a class="v3-btn v3-btn-line v3-btn-block" href="<?php echo v3_search_link(array('search_city' => null, 'category' => null, 'page' => null)); ?>">Изчисти филтрите</a><?php } ?>
	</aside>

	<section class="v3-results">
		<div class="v3-results-head">
			<div><h2 class="v3-h2"><?php echo $heading; ?></h2><p class="v3-muted"><?php echo $total; ?> <?php echo $total == 1 ? 'резултат' : 'резултата'; ?> общо</p></div>
			<form method="get" action="<?php echo v3_url('/search/'); ?>" class="v3-sort">
				<?php foreach(array('search','search_city','category','exact_match','preview') as $k) if(!empty($_GET[$k])) echo '<input type="hidden" name="', $k, '" value="', v3_e($_GET[$k]), '" />'; ?>
				<label>Сортиране:
					<select name="sort" onchange="this.form.submit()">
						<option value="vip"<?php if($sort == 'vip') echo ' selected'; ?>>Препоръчани</option>
						<option value="newest"<?php if($sort == 'newest') echo ' selected'; ?>>Най-нови</option>
						<option value="distance"<?php if($sort == 'distance') echo ' selected'; ?>>Най-близки</option>
					</select>
				</label>
				<noscript><button class="v3-btn v3-btn-line v3-btn-sm">OK</button></noscript>
			</form>
		</div>

		<?php if($rows){ foreach($rows as $r){
			$url = '/business-directory/'.$r['url'].'/'.$r['link_name'];
			$img = !empty($r['small_image']) ? WebSite.'/images/adverts/'.$r['small_image'] : WebSite.'/images/no_product_image.jpg';
			$ph = ''; foreach(explode(';', (string)$r['company_phones']) as $x){ $x = trim($x); if($x !== ''){ $ph = $x; break; } }
		?>
		<article class="v3-result v3-card">
			<a class="v3-result-img" href="<?php echo v3_url($url); ?>"><?php $ri = !empty($r['small_image']) ? v3_ad_img($r['small_image'], $r['aid'], html_entity_decode($r['supplier_name'], ENT_QUOTES, 'UTF-8'), false, 'loading="lazy"') : ''; echo $ri ? $ri : '<img src="'.v3_e($img).'" alt="" loading="lazy" />'; ?></a>
			<div class="v3-result-body">
				<h3><a href="<?php echo v3_url($url); ?>"><?php echo v3_t($r['supplier_name']); ?></a><?php if($r['sellbusiness'] == 1) echo ' <span class="v3-badge v3-badge-sale">Продава се</span>'; ?></h3>
				<p class="v3-result-meta"><?php echo v3_icon('grid', 15), ' ', v3_t($r['bg_category']); ?><?php if($r['town']) echo ' &nbsp; ', v3_icon('pin', 15), ' ', v3_t($r['town']); ?></p>
				<p><?php echo v3_excerpt($r['meta_description'], 170); ?></p>
			</div>
			<div class="v3-result-actions">
				<a class="v3-btn v3-btn-dark" href="<?php echo v3_url($url); ?>">Виж профила <?php echo v3_icon('chev', 16); ?></a>
				<?php if($ph){ ?><a class="v3-btn v3-btn-line" href="tel:<?php echo v3_e(preg_replace('/[^0-9+]/', '', $ph)); ?>" data-track-call="<?php echo (int)$r['aid']; ?>"><?php echo v3_icon('phone', 16); ?> Обади се</a><?php } ?>
			</div>
		</article>
		<?php } }else{ ?>
		<div class="v3-card v3-empty">
			<h3>Няма намерени резултати.</h3>
			<p class="v3-muted">Опитай с по-обща дума, друг град или <a href="<?php echo v3_url('/business-directory/'); ?>">разгледай всички категории</a>.</p>
		</div>
		<?php } ?>

		<?php if($pages > 1){ ?>
		<nav class="v3-pager" aria-label="Страници">
			<?php if($page > 1){ ?><a href="<?php echo v3_search_link(array('page' => $page - 1)); ?>" aria-label="Предишна">‹</a><?php } ?>
			<?php for($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++){ ?>
			<a href="<?php echo v3_search_link(array('page' => $i > 1 ? $i : null)); ?>"<?php if($i == $page) echo ' class="is-on" aria-current="page"'; ?>><?php echo $i; ?></a>
			<?php } ?>
			<?php if($page < $pages){ ?><a href="<?php echo v3_search_link(array('page' => $page + 1)); ?>" aria-label="Следваща">›</a><?php } ?>
		</nav>
		<?php } ?>
	</section>
</div>
<?php v3_footer(); ?>
