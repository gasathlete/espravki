<?php
/* ==========================================================================
   v3 "Любими": the products a visitor marked with a heart, with price tracking.
   Included from favorites/index.php. Data: modules/v3/favs.php.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$list = v3_fav_list();               // also moves a guest list into the account after login
$items = array();
if($list){
	$ids = implode(',', array_map('intval', array_keys($list)));
	$r = mysql_query('SELECT p.id, p.title, p.price, p.promo_price, p.image, p.product_url, p.category_url, p.category_name, p.active, p.deleted, p.sold,
		a.supplier_name, a.town, a.active a_active, a.deleted a_deleted
		FROM products p LEFT JOIN adverts a ON a.id = p.advert_id WHERE p.id IN ('.$ids.')');
	$rows = array(); if($r) while($x = mysql_fetch_assoc($r)) $rows[(int)$x['id']] = $x;
	foreach($list as $pid => $saved){
		if(!isset($rows[$pid])) continue;
		$p = $rows[$pid];
		$p['saved'] = (float)$saved;
		$p['now'] = v3_fav_price($p);
		$p['gone'] = ($p['deleted'] == '1' || $p['active'] != '1' || $p['a_active'] != '1' || $p['a_deleted'] == '1');
		$p['sold_out'] = ($p['sold'] == '1');
		$items[] = $p;
	}
}
$drops = 0; foreach($items as $p) if(!$p['gone'] && $p['saved'] > 0 && $p['now'] > 0 && $p['now'] < $p['saved']) $drops++;
function v3_fav_money($v){ return number_format((float)$v, 2, ',', ' ').' €'; }

v3_head('<title>Любими | Espravki.com</title><meta name="robots" content="noindex, follow" />');
v3_header();
?>
<div class="v3-wrap v3-favpage">
	<nav class="v3-crumbs" aria-label="Път"><a href="<?php echo v3_url('/'); ?>">Начало</a> <span>/</span> <span>Любими</span></nav>
	<div class="v3-favpage-head">
		<div>
			<h1 class="v3-h2">Любими</h1>
			<p class="v3-muted"><?php
				if(!$items) echo 'Тук ще са продуктите, които маркираш със сърчице.';
				else echo count($items), ' ', count($items) == 1 ? 'запазен продукт' : 'запазени продукта', $drops ? ' · <strong class="v3-fav-drop-note">'.$drops.' '.($drops == 1 ? 'поевтиня' : 'поевтиняха').'</strong>' : '', '.';
			?></p>
		</div>
		<a class="v3-btn v3-btn-line" href="<?php echo v3_url('/products/'); ?>">Разгледай продуктите <?php echo v3_icon('arrow', 16); ?></a>
	</div>

	<?php if(!v3_fav_cid() && $items){ ?>
	<div class="v3-card v3-fav-note"><?php echo v3_icon('lock', 18); ?><p>Списъкът е запазен само в този браузър. <a href="<?php echo v3_url('/userlogins/index.php'); ?>">Влез в профила си</a>, за да го виждаш от всяко устройство. Ще го прехвърлим автоматично.</p></div>
	<?php } ?>

	<?php if($items){ ?>
	<ul class="v3-favlist">
		<?php foreach($items as $p){
			$url = v3_url('/products/'.$p['category_url'].'/'.$p['product_url']);
			$img = !empty($p['image']) ? WebSite.'/images/products/'.$p['image'] : '';
			$diff = ($p['saved'] > 0 && $p['now'] > 0) ? round($p['now'] - $p['saved'], 2) : 0;
		?>
		<li class="v3-card v3-favitem<?php if($p['gone']) echo ' is-gone'; ?>">
			<a class="v3-favitem-img" href="<?php echo $url; ?>" tabindex="-1" aria-hidden="true"><?php echo $img ? '<img src="'.v3_e($img).'" alt="" loading="lazy" />' : v3_icon('tag', 28); ?></a>
			<div class="v3-favitem-body">
				<p class="v3-dir-cat"><?php echo v3_t($p['category_name']); ?></p>
				<h2><?php echo $p['gone'] ? v3_t($p['title']) : '<a href="'.$url.'">'.v3_t($p['title']).'</a>'; ?></h2>
				<p class="v3-dir-town"><?php echo v3_icon('user', 14), ' ', v3_t($p['supplier_name']); ?><?php if($p['town']) echo ' · ', v3_t($p['town']); ?></p>
			</div>
			<div class="v3-favitem-price">
				<?php if($p['gone']){ ?>
					<span class="v3-badge v3-badge-off">Вече не се предлага</span>
				<?php }else{ ?>
					<b><?php echo $p['now'] > 0 ? v3_fav_money($p['now']) : 'По запитване'; ?></b>
					<?php if($diff < 0){ ?><span class="v3-fav-delta is-down">Поевтиня с <?php echo v3_fav_money(-$diff); ?></span><small>Беше <?php echo v3_fav_money($p['saved']); ?></small>
					<?php }elseif($diff > 0){ ?><span class="v3-fav-delta is-up">Поскъпна с <?php echo v3_fav_money($diff); ?></span><small>Беше <?php echo v3_fav_money($p['saved']); ?></small>
					<?php }else{ ?><small>Без промяна в цената</small><?php } ?>
					<?php if($p['sold_out']) echo '<span class="v3-badge v3-badge-wait">Изчерпан</span>'; ?>
				<?php } ?>
			</div>
			<div class="v3-favitem-actions">
				<?php if(!$p['gone']){ ?><a class="v3-btn v3-btn-dark v3-btn-sm" href="<?php echo $url; ?>">Виж</a><?php } ?>
				<?php echo v3_fav_btn($p['id'], 'v3-fav-remove', true); ?>
			</div>
		</li>
		<?php } ?>
	</ul>
	<?php }else{ ?>
	<div class="v3-card v3-empty v3-fav-empty">
		<span class="v3-fav-empty-ic"><svg class="v3-ic" width="34" height="34" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20.5s-7.5-4.6-9.3-9.4C1.5 7.8 3.6 4.5 7 4.5c2 0 3.6 1.1 5 3 1.4-1.9 3-3 5-3 3.4 0 5.5 3.3 4.3 6.6-1.8 4.8-9.3 9.4-9.3 9.4z"/></svg></span>
		<h2 class="v3-h3">Нямаш запазени продукти</h2>
		<p class="v3-muted">Натисни сърчицето на всеки продукт, за да го запазиш тук. Ще виждаш, ако цената му падне.</p>
		<a class="v3-btn v3-btn-orange" href="<?php echo v3_url('/products/'); ?>">Към продуктите</a>
	</div>
	<?php } ?>
</div>
<script>
// on this page removing a heart removes the row
document.addEventListener('v3:fav', function(e){ if(!e.detail || e.detail.on) return; var li = e.detail.btn.closest ? e.detail.btn.closest('.v3-favitem') : null; if(li && li.parentNode) li.parentNode.removeChild(li); });
</script>
<?php v3_footer(); ?>
