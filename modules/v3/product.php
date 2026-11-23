<?php
/* ==========================================================================
   v3 product page (design A12). Included from products/index.php for a single product.
   Expects: $row (product), $pid, $meta_info, $lang.
   Forms post to the SAME endpoints as the old page:
   ajax/fast_order.php (quick order), ajax/contact_trader.php (inquiry), ajax/save_call.php
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$p = $row;
$ar = mysql_query('SELECT * FROM adverts WHERE id = "'.(int)$p['advert_id'].'" AND active = "1" AND deleted = "0"');
if(!$ar || mysql_num_rows($ar) < 1){ seo_not_found(); }
$a = mysql_fetch_assoc($ar);

$title = v3_t($p['title']);
$seller = v3_t($a['supplier_name']);
$is_owner = isset($_SESSION['customer']) && $_SESSION['customer'] == $a['customer_id'];

/* seller profile link: first category of the advert */
$seller_cat = '';
$cr = mysql_query('SELECT pc.url FROM products_categories pc JOIN adverts_to_product_categories atpc ON atpc.category_id = pc.id WHERE atpc.advert_id = "'.(int)$a['id'].'" LIMIT 1');
if($cr && ($c = mysql_fetch_row($cr))) $seller_cat = $c[0];
$seller_url = v3_url('/business-directory/'.$seller_cat.'/'.$a['link_name']);

/* images */
$images = array();
foreach(get_product_images($pid) as $im) if(!empty($im['image'])) $images[] = WebSite.'/images/products/'.$im['image'];
if(!$images) $images[] = WebSite.'/images/products/'.($p['image'] ? $p['image'] : 'no_product_image.jpg');

/* price */
$price = (float)$p['price']; $promo = (float)$p['promo_price'];
function v3_money($v){ return number_format((float)$v, 2, ',', ' ').' €'; }
$available = ($p['active'] > 0 && $p['sold'] < 1);
$external = !empty($p['redirect_url']) && preg_match('#^https?://#i', $p['redirect_url']);

/* options (variants) */
$options = echo_product_options_new_basket($pid);
if(!is_array($options)) $options = array();

/* phones */
$phones = array();
foreach(explode(';', (string)$a['company_phones']) as $ph){ $ph = trim($ph); if($ph !== '') $phones[] = $ph; }

/* tags */
$tags = array();
if(!empty($p['product_tags'])) foreach(explode(';', $p['product_tags']) as $t){ $t = trim($t); if($t !== '' && $t != $p['title'] && !in_array($t, $tags)) $tags[] = $t; }

/* pay methods */
$pay = @unserialize($a['pay_methods']);
$pay_labels = array();
if(is_array($pay)) foreach($lang['pay_methods'] as $k => $v) if(in_array($k, $pay)) $pay_labels[] = $v;

/* description / terms: stored escaped with whitelisted tags */
function v3_clean_html($s){
	$s = strip_tags(htmlspecialchars_decode((string)$s), '<p><br><b><strong><i><em><ul><ol><li><h2><h3><h4>');
	return preg_replace('/\s(on\w+|style)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $s);
}
$desc = v3_clean_html($p['description']);
$terms = !empty($p['special_terms']) ? v3_clean_html($p['special_terms']) : v3_clean_html($lang['spterms']);
$short = v3_excerpt($p['description'], 160);

/* similar: same seller or same category */
$similar = array();
$sr = mysql_query('SELECT p.title, p.price, p.image, p.product_url, p.category_url, a.supplier_name FROM products p JOIN adverts a ON a.id = p.advert_id
	WHERE p.id != "'.(int)$pid.'" AND (a.id = "'.(int)$a['id'].'" OR p.category_url = "'.mysql_real_escape_string($p['category_url']).'")
	AND a.active = "1" AND p.active = "1" AND p.sold = "0" AND p.deleted = "0" AND p.checked_by_admin = "1"
	GROUP BY p.id ORDER BY p.addeddate DESC LIMIT 8');
if($sr) while($s = mysql_fetch_assoc($sr)) $similar[] = $s;

/* prefill */
$pre_names = isset($_SESSION['customer_names']) ? $_SESSION['customer_names'] : '';
$pre_email = isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : '';
$pre_phone = isset($_SESSION['customer_phone']) ? $_SESSION['customer_phone'] : '';

/* structured data: Product with an Offer (no fake ratings) */
$ld = array('@context'=>'https://schema.org','@type'=>'Product','name'=>html_entity_decode($p['title'], ENT_QUOTES, 'UTF-8'),
	'image'=>$images, 'description'=>html_entity_decode(strip_tags($short), ENT_QUOTES, 'UTF-8'),
	'brand'=>array('@type'=>'Brand','name'=>html_entity_decode($a['supplier_name'], ENT_QUOTES, 'UTF-8')),
	'url'=>seo_canonical_url('/products/'.$p['category_url'].'/'.$p['product_url']));
if($price > 0) $ld['offers'] = array('@type'=>'Offer','priceCurrency'=>'EUR','price'=>number_format($promo > 0 ? $promo : $price, 2, '.', ''),
	'availability'=>$available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
	'seller'=>array('@type'=>'Organization','name'=>html_entity_decode($a['supplier_name'], ENT_QUOTES, 'UTF-8')));
$crumbs = array('@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>array(
	array('@type'=>'ListItem','position'=>1,'name'=>'Продукти и услуги','item'=>WebSite.'/products/'),
	array('@type'=>'ListItem','position'=>2,'name'=>html_entity_decode($p['category_name'], ENT_QUOTES, 'UTF-8'),'item'=>WebSite.'/products/'.$p['category_url'].'/'),
	array('@type'=>'ListItem','position'=>3,'name'=>html_entity_decode($p['title'], ENT_QUOTES, 'UTF-8'))));
$extra = '';
foreach(array($ld, $crumbs) as $j) $extra .= '<script type="application/ld+json">'.str_replace('</', '<\/', json_encode($j, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)).'</script>';
if(!empty($V3_PREVIEW)) $meta_info = str_replace('<meta name="robots" content="index, follow" />', '<meta name="robots" content="noindex, follow" />', $meta_info);

v3_head($meta_info, $extra);
v3_header();
?>
<div class="v3-wrap v3-product">
	<nav class="v3-crumbs" aria-label="Навигация">
		<a href="<?php echo v3_url('/'); ?>" aria-label="Начало">Начало</a> <span>/</span>
		<a href="<?php echo v3_url('/products/'); ?>">Продукти и услуги</a> <span>/</span>
		<a href="<?php echo v3_url('/products/'.$p['category_url'].'/'); ?>"><?php echo v3_t($p['category_name']); ?></a>
	</nav>

	<div class="v3-product-grid">
		<div class="v3-pgallery" data-gallery>
			<div class="v3-pgallery-main"><img src="<?php echo v3_e($images[0]); ?>" alt="<?php echo $title; ?>" data-gallery-main /></div>
			<?php if(count($images) > 1){ ?>
			<div class="v3-pgallery-thumbs">
				<?php foreach($images as $i => $im){ ?><button type="button" class="<?php echo $i == 0 ? 'is-active' : ''; ?>" data-gallery-src="<?php echo v3_e($im); ?>" aria-label="Снимка <?php echo $i + 1; ?>"><img src="<?php echo v3_e($im); ?>" alt="" loading="lazy" /></button><?php } ?>
			</div>
			<?php } ?>
		</div>

		<div class="v3-pinfo">
			<h1><?php echo $title; ?></h1>
			<?php if($short){ ?><p class="v3-plead"><?php echo $short; ?></p><?php } ?>
			<?php if($tags){ ?><div class="v3-chips"><?php foreach(array_slice($tags, 0, 6) as $t){ ?><a class="v3-chip-link" href="<?php echo v3_url('/search/?search='.rawurlencode($t).'&exact_match=0'); ?>"><?php echo v3_e($t); ?></a><?php } ?></div><?php } ?>

			<?php if($price > 0){ ?>
			<div class="v3-pprice">
				<?php if($promo > 0 && $promo < $price){ ?><b><?php echo v3_money($promo); ?></b> <s><?php echo v3_money($price); ?></s><?php }else{ ?><b><?php echo v3_money($price); ?></b><?php } ?>
			</div>
			<?php } ?>
			<?php if(!$available){ ?><p class="v3-badge v3-badge-off">Офертата е изтекла или продуктът е продаден</p><?php } ?>

			<?php if($options){ ?>
			<div class="v3-poptions">
				<?php foreach($options as $oname => $ovals){ ?>
				<label><?php echo v3_e($oname); ?>
					<select data-option="<?php echo v3_e($oname); ?>"><option value="">Избери</option><?php foreach($ovals as $ov){ ?><option><?php echo v3_e($ov); ?></option><?php } ?></select>
				</label>
				<?php } ?>
			</div>
			<?php } ?>

			<div class="v3-card v3-order-box">
				<?php if($available && !$is_owner){ ?>
				<div class="v3-seg" role="tablist">
					<?php if($external){ ?>
					<a class="v3-btn v3-btn-orange" target="_blank" rel="nofollow noopener" href="<?php echo v3_e($p['redirect_url']); ?>">Поръчай от сайта на търговеца</a>
					<?php }else{ ?>
					<button type="button" class="v3-btn v3-btn-orange is-active" data-pane="order" role="tab" aria-selected="true">Бърза поръчка</button>
					<?php } ?>
					<button type="button" class="v3-btn v3-btn-line<?php echo $external ? ' is-active' : ''; ?>" data-pane="ask" role="tab">Изпрати запитване</button>
				</div>

				<?php if(!$external){ ?>
				<form class="v3-form v3-pane" data-pane-body="order" data-v3-order="<?php echo (int)$pid; ?>" data-aid="<?php echo (int)$a['id']; ?>" data-pname="<?php echo $title; ?>" novalidate>
					<label>Име<input type="text" name="names" value="<?php echo v3_e($pre_names); ?>" autocomplete="name" required placeholder="Вашето име" /></label>
					<label>Телефон<input type="tel" name="cphone" value="<?php echo v3_e($pre_phone); ?>" autocomplete="tel" required placeholder="Телефон за връзка" /></label>
					<label>Адрес за доставка<textarea name="da" rows="2" required placeholder="Град, адрес или офис на куриер"></textarea></label>
					<label>Бележка за поръчката <small class="v3-muted">(по желание)</small><textarea name="note" rows="2" placeholder="Например: име, дата, размер, цвят или други подробности"></textarea></label>
					<label class="v3-check"><input type="checkbox" name="terms" required /> <span>Приемам <a href="#uslovia">условията за поръчка</a></span></label>
					<button class="v3-btn v3-btn-orange v3-btn-block" type="submit">Изпрати поръчката</button>
					<p class="v3-small">Търговецът ще се свърже с теб за потвърждение, доставка и плащане.</p>
					<p class="v3-form-msg" role="status"></p>
				</form>
				<?php } ?>

				<form class="v3-form v3-pane" data-pane-body="ask" data-v3-contact="<?php echo (int)$a['id']; ?>" data-pid="<?php echo (int)$pid; ?>" data-pname="<?php echo $title; ?>" <?php echo $external ? '' : 'hidden'; ?> novalidate>
					<div class="v3-form-row">
						<label>Име и фамилия<input type="text" name="names" value="<?php echo v3_e($pre_names); ?>" autocomplete="name" required /></label>
						<label>Телефон<input type="tel" name="phone" value="<?php echo v3_e($pre_phone); ?>" autocomplete="tel" required /></label>
					</div>
					<label>Имейл<input type="email" name="email" value="<?php echo v3_e($pre_email); ?>" autocomplete="email" required /></label>
					<label>Въпрос<textarea name="message" rows="4" minlength="20" required placeholder="Какво искаш да попиташ за „<?php echo $title; ?>“? (поне 20 символа)"></textarea></label>
					<button class="v3-btn v3-btn-blue v3-btn-block" type="submit"><?php echo v3_icon('chat', 18); ?> Изпрати запитване</button>
					<p class="v3-form-msg" role="status"></p>
				</form>
				<?php }elseif($is_owner){ ?>
				<p>Това е твой продукт. <a href="<?php echo v3_url('/mydash.php?my_products=show'); ?>">Управлявай продуктите си.</a></p>
				<?php }else{ ?>
				<p class="v3-muted">Този продукт в момента не се предлага. Виж <a href="<?php echo $seller_url; ?>">други предложения от <?php echo $seller; ?></a>.</p>
				<?php } ?>
				<?php if($phones){ ?><a class="v3-pcall" href="tel:<?php echo v3_e(preg_replace('/[^0-9+]/', '', $phones[0])); ?>" data-track-call="<?php echo (int)$a['id']; ?>" data-pid="<?php echo (int)$pid; ?>"><?php echo v3_icon('phone', 18); ?> Или се обади: <strong><?php echo v3_e($phones[0]); ?></strong></a><?php } ?>
			</div>
		</div>

		<aside class="v3-pside">
			<div class="v3-card v3-seller">
				<h2 class="v3-h3">Търговец</h2>
				<?php $slogo = !empty($a['logo']) ? $a['logo'] : $a['small_image']; if($slogo){ ?><img class="v3-seller-logo" src="<?php echo v3_e(WebSite.'/images/adverts/'.$slogo); ?>" alt="<?php echo $seller; ?>" /><?php } ?>
				<strong class="v3-seller-name"><?php echo $seller; ?></strong>
				<?php if($a['town']){ ?><p class="v3-muted"><?php echo v3_icon('pin', 15), ' ', v3_t($a['town']); ?></p><?php } ?>
				<a class="v3-btn v3-btn-line v3-btn-block" href="<?php echo $seller_url; ?>">Виж профила</a>
			</div>
			<div class="v3-card v3-seller">
				<h2 class="v3-h3"><?php echo v3_icon('tag', 20); ?> Доставка и плащане</h2>
				<?php if($pay_labels){ ?><ul class="v3-ticks v3-ticks-sm"><?php foreach($pay_labels as $pl){ ?><li><?php echo v3_icon('check', 18), ' ', v3_e($pl); ?></li><?php } ?></ul><?php }else{ ?><p class="v3-muted">Условията се уточняват с търговеца.</p><?php } ?>
			</div>
		</aside>
	</div>

	<nav class="v3-tabs" aria-label="Раздели">
		<a href="#opisanie">Описание</a>
		<a href="#uslovia">Условия за покупка</a>
		<?php if($similar){ ?><a href="#podobni">Подобни предложения</a><?php } ?>
	</nav>
	<section id="opisanie" class="v3-block v3-prose"><?php echo $desc ? $desc : '<p class="v3-muted">Няма допълнително описание.</p>'; ?></section>
	<section id="uslovia" class="v3-block"><h2 class="v3-h3">Условия за покупка</h2><div class="v3-prose"><?php echo $terms; ?></div></section>

	<?php if($similar){ ?>
	<section id="podobni" class="v3-block">
		<h2 class="v3-h3">Подобни предложения</h2>
		<div class="v3-offer-grid">
			<?php foreach($similar as $s){ ?>
			<a class="v3-offer-card" href="<?php echo v3_url('/products/'.$s['category_url'].'/'.$s['product_url']); ?>">
				<span class="v3-offer-img"><img src="<?php echo v3_e(WebSite.'/images/products/'.($s['image'] ? $s['image'] : 'no_product_image.jpg')); ?>" alt="" loading="lazy" /></span>
				<span class="v3-offer-body"><strong><?php echo v3_excerpt($s['title'], 70); ?></strong><small class="v3-muted"><?php echo v3_t($s['supplier_name']); ?></small><?php if((float)$s['price'] > 0){ ?><em><?php echo v3_money($s['price']); ?></em><?php } ?></span>
			</a>
			<?php } ?>
		</div>
	</section>
	<?php } ?>
	<p class="v3-small v3-center">Публикувано на <?php echo date('d.m.Y', strtotime($p['addeddate'])); ?> · <a href="<?php echo v3_url('/contacts/'); ?>">Сигнализирай за нередност</a></p>
</div>
<?php
// product visit statistics (same as the old page)
mysql_query('INSERT INTO statistics_products_visits SET product_id = "'.(int)$pid.'", advert_id = "'.(int)$a['id'].'", ip = "'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'"');
v3_footer();
