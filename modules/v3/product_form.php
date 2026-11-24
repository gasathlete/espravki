<?php
/* ==========================================================================
   v3 "add product or service" form (design A07). Included from
   addproduct/index.php AFTER its own processing. The form posts the SAME
   fields to the same page, so validation, saving, image upload and limits
   are unchanged. On an error ($pmsg) the form is shown again with the values.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$ad = array();
$r = mysql_query('SELECT id, supplier_name, town, site, max_offers FROM adverts WHERE id = "'.(int)$_SESSION['advert_id'].'" AND customer_id = "'.(int)$_SESSION['customer'].'"');
if($r) $ad = mysql_fetch_assoc($r);
$used = (int)mysql_result(mysql_query('SELECT COUNT(*) FROM products WHERE advert_id = "'.(int)$_SESSION['advert_id'].'" AND deleted = "0"'), 0);
$max = $ad ? (int)$ad['max_offers'] : 0;

$P = $_POST;
$val = function($k, $d = '') use ($P){ return isset($P[$k]) && !is_array($P[$k]) ? v3_e($P[$k]) : $d; };
$type = isset($P['offer_type']) ? (int)$P['offer_type'] : 2;
$deliver = isset($P['deliver_product']) ? (int)$P['deliver_product'] : ($type == 1 ? 1 : 2);
$tags = (isset($P['product_tags']) && is_array($P['product_tags'])) ? implode(', ', $P['product_tags']) : '';

$meta = '<title>Добави продукт или услуга | Espravki.com</title><meta name="robots" content="noindex, nofollow" />';
v3_head($meta);
v3_header();
?>
<div class="v3-wrap v3-pform-page">
	<nav class="v3-crumbs" aria-label="Навигация">
		<a href="<?php echo v3_url('/mydash.php'); ?>">Табло</a> <span>/</span>
		<a href="<?php echo v3_url('/mydash.php?my_products=show'); ?>">Моите предложения</a> <span>/</span> <span>Ново предложение</span>
	</nav>
	<h1 class="v3-h2">Добави продукт или услуга</h1>
	<p class="v3-muted">Създай интересно и ясно предложение, за да достигнеш до повече клиенти<?php if($ad) echo ' от <strong>', v3_t($ad['supplier_name']), '</strong>'; ?>.</p>

	<?php if(!empty($pmsg)){ ?><div class="v3-alert v3-alert-off"><?php echo v3_icon('clock', 18), ' ', strip_tags($pmsg, '<br>'); ?></div><?php } ?>

	<div class="v3-pform-grid">
		<form class="v3-card v3-form v3-pform" method="post" action="<?php echo v3_url('/addproduct/'); ?>" enctype="multipart/form-data" data-pform novalidate>
			<div class="v3-type" role="radiogroup" aria-label="Тип на предложението">
				<label class="v3-type-opt"><input type="radio" name="offer_type" value="1"<?php if($type == 1) echo ' checked'; ?> /><span><?php echo v3_icon('tag', 22); ?><b>Продукт</b><small>Физически продукт с поръчка</small></span></label>
				<label class="v3-type-opt"><input type="radio" name="offer_type" value="2"<?php if($type != 1) echo ' checked'; ?> /><span><?php echo v3_icon('user', 22); ?><b>Услуга</b><small>Услуга с запитване</small></span></label>
			</div>

			<label>Име на предложението <i class="v3-req">*</i><input type="text" name="title" value="<?php echo $val('title'); ?>" maxlength="120" required data-preview="title" placeholder="Напр. Портретна фотосесия" /></label>

			<label>Категория <i class="v3-req">*</i>
				<select name="category_name" required data-preview="category"><option value="">Избери категория</option><?php $_POST['category_id'] = isset($P['category_name']) ? $P['category_name'] : ''; add_product_category_new(0, 0, 0); ?></select>
			</label>
			<label class="v3-sub-field">или нова категория <small class="v3-muted">(ако няма подходяща)</small><input type="text" data-newcat placeholder="Напр. Фотографски услуги" /></label>

			<div class="v3-uploads">
				<span class="v3-label">Качи снимки <small class="v3-muted">· до 4, JPG или PNG</small></span>
				<div class="v3-upload-grid" data-upload-grid>
					<label class="v3-upload-add"><input type="file" name="images[]" accept="image/jpeg,image/png" multiple data-images /><?php echo v3_icon('plus', 22); ?><span>Добави снимки</span></label>
				</div>
			</div>

			<div class="v3-form-row">
				<label>Цена (EUR) <i class="v3-req">*</i><span class="v3-input-ic v3-input-suffix"><input type="number" name="price" min="0.01" step="0.01" value="<?php echo $val('price'); ?>" required data-preview="price" placeholder="0,00" /><em>EUR</em></span></label>
				<label>Промо цена <small class="v3-muted">(по желание)</small><span class="v3-input-ic v3-input-suffix"><input type="number" name="promo_price" min="0" step="0.01" value="<?php echo $val('promo_price'); ?>" data-preview="promo" /><em>EUR</em></span></label>
			</div>

			<label>Описание <i class="v3-req">*</i><textarea name="description" rows="5" maxlength="1500" required data-preview="desc" placeholder="Какво включва, за кого е, срокове, условия..."><?php echo isset($P['description']) ? v3_e(str_ireplace(array('<br/>','<br />','<br>'), "\n", $P['description'])) : ''; ?></textarea></label>

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
					<div class="v3-form-row"><input type="text" data-vname placeholder="Име на варианта (напр. Цвят)" /><input type="text" data-vvals placeholder="Стойности, разделени със запетая" /></div>
					<div class="v3-form-row"><input type="text" data-vname placeholder="Име на варианта (напр. Размер)" /><input type="text" data-vvals placeholder="Стойности, разделени със запетая" /></div>
				</div>
				<label>Линк към продукта в твоя сайт <small class="v3-muted">(по желание)</small><input type="url" name="redirect_url" value="<?php echo $val('redirect_url'); ?>" placeholder="https://" /></label>
			</details>

			<div class="v3-note v3-note-info"><?php echo v3_icon('pin', 18); ?><span>Градът и контактите се взимат от профила на бизнеса.</span></div>

			<p class="v3-form-msg" role="alert"></p>
			<div class="v3-pform-actions">
				<a class="v3-btn v3-btn-line" href="<?php echo v3_url('/mydash.php?my_products=show'); ?>">Откажи</a>
				<button class="v3-btn v3-btn-orange v3-btn-lg" type="submit" name="prd_submit" value="save_prd">Публикувай предложението</button>
				<?php if($max){ ?><span class="v3-small">Лимит: <?php echo $used; ?> от <?php echo $max; ?> предложения</span><?php } ?>
			</div>
		</form>

		<aside class="v3-pform-preview">
			<div class="v3-card v3-preview-card">
				<p class="v3-preview-title"><?php echo v3_icon('search', 18); ?> <strong>Визуализация на предложението</strong><small>Така ще изглежда в каталога.</small></p>
				<div class="v3-preview-img" data-pv-img><span><?php echo v3_icon('plus', 28); ?><br />Снимка</span></div>
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
