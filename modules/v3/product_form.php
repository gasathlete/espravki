<?php
/* ==========================================================================
   v3 "add product or service" form (design A07). Included from
   addproduct/index.php AFTER its own processing. The form posts the SAME
   fields to the same page, so validation, saving, image upload and limits
   are unchanged. On an error ($pmsg) the form is shown again with the values.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

/* edit mode: included from editproduct/index.php with $v3_edit = true (product ownership already verified) */
$edit = !empty($v3_edit);
$pid = $edit ? (int)$_SESSION['product_id'] : 0;
if($edit){
	// the business of this product (owned by the customer) - also needed by ajax/delete_image.php
	$r = mysql_query('SELECT advert_id FROM products WHERE id = "'.$pid.'" AND cid = "'.(int)$_SESSION['customer'].'"');
	if($r && ($x = mysql_fetch_row($r))) $_SESSION['advert_id'] = (int)$x[0];
}

$ad = array();
$r = mysql_query('SELECT id, supplier_name, town, site, max_offers FROM adverts WHERE id = "'.(int)$_SESSION['advert_id'].'" AND customer_id = "'.(int)$_SESSION['customer'].'"');
if($r) $ad = mysql_fetch_assoc($r);
$used = (int)mysql_result(mysql_query('SELECT COUNT(*) FROM products WHERE advert_id = "'.(int)$_SESSION['advert_id'].'" AND deleted = "0"'), 0);
$max = $ad ? (int)$ad['max_offers'] : 0;

$P = $_POST;
$val = function($k, $d = '') use ($P){ return isset($P[$k]) && !is_array($P[$k]) ? v3_e($P[$k]) : $d; };
$type = isset($P['offer_type']) ? (int)$P['offer_type'] : 2;
$deliver = isset($P['deliver_product']) ? (int)$P['deliver_product'] : ($type == 1 ? 1 : 2);
$tags = '';
if(isset($P['product_tags'])) $tags = is_array($P['product_tags']) ? implode(', ', $P['product_tags']) : implode(', ', array_filter(array_map('trim', explode(';', $P['product_tags']))));

/* existing variants (edit) */
$variants = array();
if($edit && !empty($P['product_options']) && is_array($P['product_options'])){
	foreach($P['product_options'] as $k => $name){
		$vals = isset($optionsnames[$k]) ? array_filter(array_map('trim', explode(';', $optionsnames[$k]))) : array();
		$variants[] = array($name, implode(', ', $vals));
	}
}
while(count($variants) < 2) $variants[] = array('', '');

/* existing pictures (edit) */
$existing = array();
if($edit) foreach(get_product_images($pid) as $img_id => $im) if(!empty($im['image'])) $existing[$img_id] = $im['image'];

$saved = $edit && isset($pmsg) && $pmsg == $lang['suc_save2'];
$action = $edit ? '/editproduct/' : '/addproduct/';
$heading = $edit ? 'Редактирай предложението' : 'Добави продукт или услуга';
$meta = '<title>'.$heading.' | Espravki.com</title><meta name="robots" content="noindex, nofollow" />';
v3_head($meta);
v3_header();
?>
<div class="v3-wrap v3-pform-page">
	<nav class="v3-crumbs" aria-label="Навигация">
		<a href="<?php echo v3_url('/mydash.php'); ?>">Табло</a> <span>/</span>
		<a href="<?php echo v3_url('/mydash.php?my_products=show'); ?>">Моите предложения</a> <span>/</span> <span><?php echo $edit ? 'Редакция' : 'Ново предложение'; ?></span>
	</nav>
	<h1 class="v3-h2"><?php echo $heading; ?></h1>
	<p class="v3-muted"><?php echo $edit ? 'Промени информацията, снимките или статуса на предложението' : 'Създай интересно и ясно предложение, за да достигнеш до повече клиенти'; ?><?php if($ad) echo ' – <strong>', v3_t($ad['supplier_name']), '</strong>'; ?>.</p>

	<?php if($saved){ ?><div class="v3-alert v3-alert-ok"><?php echo v3_icon('check', 18); ?> Промените са запазени. <?php if(!empty($P['product_url'])) echo '<a target="_blank" href="', v3_url('/products/'.$P['category_url'].'/'.$P['product_url']), '">Виж предложението</a>'; ?></div>
	<?php }elseif(!empty($pmsg)){ ?><div class="v3-alert v3-alert-off"><?php echo v3_icon('clock', 18), ' ', strip_tags($pmsg, '<br>'); ?></div><?php } ?>

	<div class="v3-pform-grid">
		<form class="v3-card v3-form v3-pform" method="post" action="<?php echo v3_url($action); ?>" enctype="multipart/form-data" data-pform data-pid="<?php echo $pid; ?>" novalidate>
			<div class="v3-type" role="radiogroup" aria-label="Тип на предложението">
				<label class="v3-type-opt"><input type="radio" name="offer_type" value="1"<?php if($type == 1) echo ' checked'; ?> /><span><?php echo v3_icon('tag', 22); ?><b>Продукт</b><small>Физически продукт с поръчка</small></span></label>
				<label class="v3-type-opt"><input type="radio" name="offer_type" value="2"<?php if($type != 1) echo ' checked'; ?> /><span><?php echo v3_icon('user', 22); ?><b>Услуга</b><small>Услуга с запитване</small></span></label>
			</div>

			<label>Име на предложението <i class="v3-req">*</i><input type="text" name="title" value="<?php echo $val('title'); ?>" maxlength="120" required data-preview="title" placeholder="Напр. Портретна фотосесия" /></label>

			<label>Категория <i class="v3-req">*</i>
				<select name="category_name" required data-preview="category"><option value="">Избери категория</option><?php
				// selected category: from the submitted form, otherwise (edit) from the saved product
				$_POST['category_id'] = isset($P['prd_submit']) ? (isset($P['category_name']) ? $P['category_name'] : '') : (isset($P['category_id']) ? $P['category_id'] : '');
				add_product_category_new(0, 0, 0); ?></select>
			</label>
			<?php if(!$edit){ ?><label class="v3-sub-field">или нова категория <small class="v3-muted">(ако няма подходяща)</small><input type="text" data-newcat placeholder="Напр. Фотографски услуги" /></label><?php }else{ ?><input type="hidden" data-newcat value="" /><?php } ?>

			<div class="v3-uploads">
				<span class="v3-label">Снимки <small class="v3-muted">· до 4 общо, JPG или PNG</small></span>
				<div class="v3-upload-grid" data-upload-grid data-existing="<?php echo count($existing); ?>">
					<?php $i = 0; foreach($existing as $img_id => $img){ ?>
					<div class="v3-upload-thumb v3-upload-old" data-old-img><img src="<?php echo v3_e(WebSite.'/images/products/'.$img); ?>" alt="" /><?php if($i++ == 0) echo '<em>Главна</em>'; ?><button type="button" class="v3-upload-del" data-del-img="<?php echo (int)$img_id; ?>" aria-label="Изтрий снимката">✕</button></div>
					<?php } ?>
					<label class="v3-upload-add"><input type="file" name="images[]" accept="image/jpeg,image/png" multiple data-images /><?php echo v3_icon('plus', 22); ?><span>Добави снимки</span></label>
				</div>
			</div>

			<div class="v3-form-row">
				<label>Цена (EUR) <i class="v3-req">*</i><span class="v3-input-ic v3-input-suffix"><input type="number" name="price" min="0.01" step="0.01" value="<?php echo $val('price'); ?>" required data-preview="price" placeholder="0,00" /><em>EUR</em></span></label>
				<label>Промо цена <small class="v3-muted">(по желание)</small><span class="v3-input-ic v3-input-suffix"><input type="number" name="promo_price" min="0" step="0.01" value="<?php echo $val('promo_price'); ?>" data-preview="promo" /><em>EUR</em></span></label>
			</div>

			<label>Описание <i class="v3-req">*</i><textarea name="description" rows="5" maxlength="1500" required data-preview="desc" placeholder="Какво включва, за кого е, срокове, условия..."><?php echo isset($P['description']) ? v3_e(trim(html_entity_decode(strip_tags(str_ireplace(array('<br/>','<br />','<br>','</p>','</li>'), "\n", $P['description'])), ENT_QUOTES, 'UTF-8'))) : ''; ?></textarea></label>

			<label>Ключови думи <i class="v3-req">*</i><small class="v3-muted">По тях клиентите ще те намират. Разделяй със запетая.</small><input type="text" data-tags value="<?php echo v3_e($tags); ?>" placeholder="Напр. фотограф, портрети, фотосесия София" /></label>

			<fieldset class="v3-deliver" data-deliver>
				<legend>Доставка</legend>
				<label class="v3-check"><input type="radio" name="deliver_product" value="1"<?php if($deliver == 1) echo ' checked'; ?> /> <span>Доставям с куриер</span></label>
				<label class="v3-check"><input type="radio" name="deliver_product" value="2"<?php if($deliver != 1) echo ' checked'; ?> /> <span>Само на място / по договаряне</span></label>
			</fieldset>

			<details class="v3-more-settings">
				<summary><?php echo v3_icon('grid', 18); ?> Допълнителни настройки <small class="v3-muted">(марка, модел, варианти, линк)</small></summary>
				<div class="v3-form-row v3-form-row-3">
					<label>Марка<input type="text" name="make" value="<?php echo $val('make'); ?>" /></label>
					<label>Модел<input type="text" name="model" value="<?php echo $val('model'); ?>" /></label>
					<label>Модификация<input type="text" name="modification" value="<?php echo $val('modification'); ?>" /></label>
				</div>
				<div class="v3-variants" data-variants>
					<span class="v3-label">Варианти <small class="v3-muted">напр. „Размер“: S, M, L</small></span>
					<?php foreach($variants as $vi => $v){ ?>
					<div class="v3-form-row"><input type="text" data-vname value="<?php echo v3_e($v[0]); ?>" placeholder="Име на варианта (напр. <?php echo $vi ? 'Размер' : 'Цвят'; ?>)" /><input type="text" data-vvals value="<?php echo v3_e($v[1]); ?>" placeholder="Стойности, разделени със запетая" /></div>
					<?php } ?>
				</div>
				<?php if(!$edit){ ?><label>Линк към продукта в твоя сайт <small class="v3-muted">(по желание)</small><input type="url" name="redirect_url" value="<?php echo $val('redirect_url'); ?>" placeholder="https://" /></label><?php } ?>
				<?php if($edit){ ?><label>Условия за покупка <small class="v3-muted">(доставка, връщане, гаранция)</small><textarea name="special_terms" rows="3"><?php echo isset($P['special_terms']) ? v3_e(str_ireplace(array('<br/>','<br />','<br>','<p>','</p>'), array("\n","\n","\n",'',"\n"), $P['special_terms'])) : ''; ?></textarea></label><?php } ?>
			</details>

			<?php if($edit){ ?>
			<fieldset class="v3-deliver v3-status-box">
				<legend>Статус</legend>
				<?php if(empty($P['active'])){ ?><label class="v3-check"><input type="checkbox" name="active" value="1" /> <span>Активирай предложението</span></label><?php }else{ ?><span class="v3-badge v3-badge-ok"><?php echo v3_icon('check', 14); ?> Активно</span><?php } ?>
				<label class="v3-check"><input type="checkbox" name="sold" value="1"<?php if(!empty($P['sold'])) echo ' checked'; ?> /> <span>Продадено / не е налично</span></label>
			</fieldset>
			<?php } ?>

			<div class="v3-note v3-note-info"><?php echo v3_icon('pin', 18); ?><span>Градът и контактите се взимат от профила на бизнеса.</span></div>

			<p class="v3-form-msg" role="alert"></p>
			<div class="v3-pform-actions">
				<a class="v3-btn v3-btn-line" href="<?php echo v3_url('/mydash.php?my_products=show'); ?>">Откажи</a>
				<button class="v3-btn v3-btn-orange v3-btn-lg" type="submit" name="prd_submit" value="save_prd"><?php echo $edit ? 'Запази промените' : 'Публикувай предложението'; ?></button>
				<?php if($max && !$edit){ ?><span class="v3-small">Лимит: <?php echo $used; ?> от <?php echo $max; ?> предложения</span><?php } ?>
			</div>
		</form>

		<aside class="v3-pform-preview">
			<div class="v3-card v3-preview-card">
				<p class="v3-preview-title"><?php echo v3_icon('search', 18); ?> <strong>Визуализация на предложението</strong><small>Така ще изглежда в каталога.</small></p>
				<div class="v3-preview-img" data-pv-img><?php if($existing){ ?><img src="<?php echo v3_e(WebSite.'/images/products/'.reset($existing)); ?>" alt="" /><?php }else{ ?><span><?php echo v3_icon('plus', 28); ?><br />Снимка</span><?php } ?></div>
				<h3 data-pv-title>Име на предложението</h3>
				<p class="v3-pv-price"><b data-pv-price>0,00 €</b> <s data-pv-old hidden></s></p>
				<p class="v3-muted v3-pv-meta"><?php echo v3_icon('grid', 16); ?> <span data-pv-cat>Категория</span></p>
				<?php if($ad && $ad['town']){ ?><p class="v3-muted v3-pv-meta"><?php echo v3_icon('pin', 16), ' ', v3_t($ad['town']); ?> <small>(градът е от профила)</small></p><?php } ?>
				<p class="v3-pv-desc" data-pv-desc>Кратко описание на предложението.</p>
				<span class="v3-btn v3-btn-orange v3-btn-block" data-pv-cta>Изпрати запитване</span>
			</div>
			<p class="v3-small v3-pv-hint"><?php echo v3_icon('chat', 16); ?> За продукт клиентите виждат бърза поръчка, за услуга – запитване.</p>
		</aside>
	</div>
</div>
<?php v3_footer(); ?>
