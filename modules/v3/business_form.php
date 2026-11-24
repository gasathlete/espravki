<?php
/* ==========================================================================
   v3 "edit business" form. Included from editbusiness/index.php AFTER its own
   processing. IMPORTANT: the server updates ALL columns on save, so this form
   always sends every field it expects (missing fields would be erased).
   Expects: $_POST (saved values or the submitted ones on error), $msg,
   $advertrow, $lang, $categories, $advert_images, $_SESSION['selected_plan'].
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$P = $_POST;
$plan = isset($_SESSION['selected_plan']) ? (int)$_SESSION['selected_plan'] : 1;
$aid = (int)$_SESSION['advert_id'];
$v = function($k) use ($P){ return isset($P[$k]) && !is_array($P[$k]) ? v3_e(html_entity_decode($P[$k], ENT_QUOTES, 'UTF-8')) : ''; };
$arr = function($k) use ($P){ return isset($P[$k]) && is_array($P[$k]) ? $P[$k] : array(); };

$saved = isset($_GET['msg']) && $_GET['msg'] != 'max_num_reached' && !isset($P['save_advert']);
$error = !empty($msg) && isset($P['save_advert']);
$desc_text = isset($P['description']) ? trim(html_entity_decode(strip_tags(str_ireplace(array('<br>','<br/>','<br />','</p>','</li>','</div>','</h2>','</h3>','</h4>'), "\n", htmlspecialchars_decode($P['description']))), ENT_QUOTES, 'UTF-8')) : '';
$desc_text = preg_replace("/\n{3,}/", "\n\n", $desc_text);

$days = $arr('work_days'); $pays = $arr('pay_methods'); $certs = $arr('certificates');
$opening = isset($P['opening']) ? $P['opening'] : '09:00'; $closing = isset($P['closing']) ? $P['closing'] : '18:00';
$allday = ($opening == '00:00' && $closing == '00:00');
$max_images = isset($lang['images_num'][$plan]) ? (int)$lang['images_num'][$plan] : 4;
$gallery = is_array($advert_images) ? $advert_images : array();
$cat_names = is_array($categories) ? $categories : array();
$public_url = '';
$r = mysql_query('SELECT pc.url FROM products_categories pc JOIN adverts_to_product_categories atpc ON atpc.category_id = pc.id WHERE atpc.advert_id = "'.$aid.'" LIMIT 1');
if($r && ($x = mysql_fetch_row($r))) $public_url = v3_url('/business-directory/'.$x[0].'/'.$advertrow['link_name']);

function v3_times($sel){ $o = ''; for($t = 0; $t < 48; $t++){ $h = sprintf('%02d:%02d', floor($t / 2), ($t % 2) * 30); $o .= '<option value="'.$h.'"'.($h == $sel ? ' selected' : '').'>'.$h.'</option>'; } return $o; }

$meta = '<title>Редакция на профила | Espravki.com</title><meta name="robots" content="noindex, nofollow" />';
v3_head($meta);
v3_header();
?>
<div class="v3-wrap v3-pform-page v3-bform-page">
	<nav class="v3-crumbs" aria-label="Навигация">
		<a href="<?php echo v3_url('/mydash.php'); ?>">Табло</a> <span>/</span>
		<a href="<?php echo v3_url('/mydash.php?my_listings=show'); ?>">Моите профили</a> <span>/</span> <span>Редакция</span>
	</nav>
	<div class="v3-dash-head">
		<h1 class="v3-h2"><?php echo v3_t($advertrow['supplier_name']); ?></h1>
		<?php if($public_url){ ?><a class="v3-btn v3-btn-line v3-btn-sm" target="_blank" href="<?php echo $public_url; ?>"><?php echo v3_icon('arrow', 16); ?> Виж публичната страница</a><?php } ?>
	</div>
	<div class="v3-status-row">
		<span class="v3-badge v3-badge-<?php echo $advertrow['active'] == '1' ? 'ok' : 'wait'; ?>"><?php echo $advertrow['active'] == '1' ? 'Публикуван' : 'Очаква одобрение'; ?></span>
		<span class="v3-badge v3-badge-<?php echo $advertrow['paid'] > 0 ? 'ok' : 'wait'; ?>"><?php echo $advertrow['paid'] > 0 ? 'Платен' : 'Очаква плащане'; ?></span>
		<?php if($advertrow['expired_date'] && $advertrow['expired_date'] != '0000-00-00'){ ?><span class="v3-muted">Активен до <?php echo date('d.m.Y', strtotime($advertrow['expired_date'])); ?></span><?php } ?>
		<?php if($cat_names){ ?><span class="v3-muted">Категории: <?php echo v3_e(implode(', ', $cat_names)); ?></span><?php } ?>
	</div>

	<?php if($saved){ ?><div class="v3-alert v3-alert-ok"><?php echo v3_icon('check', 18); ?> Промените са запазени.</div>
	<?php }elseif(isset($_GET['msg']) && $_GET['msg'] == 'max_num_reached'){ ?><div class="v3-alert v3-alert-wait"><?php echo v3_icon('clock', 18), ' ', v3_t($lang['max_num_reached']); ?></div>
	<?php }elseif($error){ ?><div class="v3-alert v3-alert-off"><?php echo v3_icon('clock', 18), ' ', strip_tags($msg); ?></div><?php } ?>

	<form class="v3-form v3-bform" method="post" action="<?php echo v3_url('/editbusiness/'); ?>" enctype="multipart/form-data" data-bform novalidate>
		<input type="hidden" name="selected_pay_way" value="<?php echo $v('selected_pay_way'); ?>" />

		<nav class="v3-tabs v3-bform-nav" aria-label="Секции">
			<a href="#osnovna">Основна информация</a><a href="#kontakti">Контакти</a><a href="#adres">Адрес и карта</a><a href="#vreme">Работно време</a><a href="#snimki">Снимки</a><a href="#faktura">Фактуриране</a>
		</nav>

		<section id="osnovna" class="v3-card v3-bsec">
			<h2 class="v3-h3"><?php echo v3_icon('news', 20); ?> Основна информация</h2>
			<label>Име на бизнеса <i class="v3-req">*</i><input type="text" name="supplier_name" value="<?php echo $v('supplier_name'); ?>" required maxlength="150" /></label>
			<label>Вид на бизнеса
				<?php $bt = !empty($P['business_type']) ? (int)$P['business_type'] : 1; /* empty in many old profiles (old form never sent it) -> local business */ ?>
				<select name="business_type"><?php foreach($lang['select_type'] as $k => $t){ if($k == 0) continue; ?><option value="<?php echo (int)$k; ?>"<?php if((int)$k === $bt) echo ' selected'; ?>><?php echo v3_e($t); ?></option><?php } ?></select>
			</label>
			<label>Описание <i class="v3-req">*</i><small class="v3-muted">Какво предлагате, с какво се отличавате, за кого е.</small><textarea name="description" rows="8" required data-original="<?php echo isset($P['description']) && !isset($P['save_advert']) ? v3_e(htmlspecialchars_decode($P['description'])) : ''; ?>"><?php echo v3_e($desc_text); ?></textarea></label>
			<?php /* data-original: if the text is not changed, the original formatted description is sent back unchanged */ ?>
			<label>Ключови думи <small class="v3-muted">По тях клиентите ви намират. Разделяйте със запетая.</small><input type="text" name="meta_keywords" value="<?php echo $v('meta_keywords'); ?>" /></label>
		</section>

		<section id="kontakti" class="v3-card v3-bsec">
			<h2 class="v3-h3"><?php echo v3_icon('phone', 20); ?> Контакти</h2>
			<div class="v3-form-row">
				<label>Телефон <i class="v3-req">*</i><small class="v3-muted">Няколко номера разделяйте с „;“</small><input type="tel" name="company_phones" value="<?php echo $v('company_phones'); ?>" required /></label>
				<label>Имейл <i class="v3-req">*</i><input type="email" name="mail" value="<?php echo $v('mail'); ?>" required /></label>
			</div>
			<div class="v3-form-row">
				<label>Уебсайт<input type="url" name="site" value="<?php echo $v('site'); ?>" placeholder="https://" /></label>
				<label>YouTube видео <small class="v3-muted">(кодът след „v=“)</small><input type="text" name="video" value="<?php echo $v('video'); ?>" placeholder="напр. dQw4w9WgXcQ" /></label>
			</div>
			<?php if($plan == 2){ ?>
			<div class="v3-form-row">
				<label>Facebook<input type="url" name="facebook" value="<?php echo $v('facebook'); ?>" /></label>
				<label>Instagram<input type="url" name="instagram" value="<?php echo $v('instagram'); ?>" /></label>
			</div>
			<div class="v3-form-row">
				<label>LinkedIn<input type="url" name="linkedin" value="<?php echo $v('linkedin'); ?>" /></label>
				<label>X / Twitter<input type="url" name="twitter" value="<?php echo $v('twitter'); ?>" /></label>
			</div>
			<?php } ?>
		</section>

		<section id="adres" class="v3-card v3-bsec">
			<h2 class="v3-h3"><?php echo v3_icon('pin', 20); ?> Адрес и карта</h2>
			<div class="v3-form-row v3-form-row-3">
				<label>Град <i class="v3-req">*</i><input type="text" name="town" list="v3-cities" value="<?php echo $v('town'); ?>" required /></label>
				<label>Квартал<input type="text" name="area" value="<?php echo $v('area'); ?>" /></label>
				<label>Пощенски код<input type="text" name="postcode" value="<?php echo $v('postcode'); ?>" /></label>
			</div>
			<datalist id="v3-cities"><?php foreach(echo_cities() as $c) echo '<option value="', v3_e($c), '">'; ?></datalist>
			<label>Адрес <i class="v3-req">*</i><input type="text" name="company_address" value="<?php echo $v('company_address'); ?>" required /></label>
			<div class="v3-form-row v3-geo-row">
				<label>Географска ширина <i class="v3-req">*</i><input type="text" name="latitude" value="<?php echo $v('latitude'); ?>" inputmode="decimal" required /></label>
				<label>Географска дължина <i class="v3-req">*</i><input type="text" name="longitude" value="<?php echo $v('longitude'); ?>" inputmode="decimal" required /></label>
			</div>
			<div class="v3-geo-actions">
				<button type="button" class="v3-btn v3-btn-line v3-btn-sm" data-geocode data-key="<?php echo v3_e(defined('google_api') ? google_api : ''); ?>"><?php echo v3_icon('search', 16); ?> Намери координатите по адреса</button>
				<a class="v3-btn v3-btn-ghost v3-btn-sm" target="_blank" rel="noopener" data-maplink href="https://www.google.com/maps/search/?api=1&amp;query=<?php echo (float)(isset($P['latitude']) ? $P['latitude'] : 0), ',', (float)(isset($P['longitude']) ? $P['longitude'] : 0); ?>"><?php echo v3_icon('pin', 16); ?> Провери на картата</a>
				<span class="v3-small" data-geo-msg></span>
			</div>
		</section>

		<section id="vreme" class="v3-card v3-bsec">
			<h2 class="v3-h3"><?php echo v3_icon('clock', 20); ?> Работно време</h2>
			<div class="v3-days">
				<?php foreach($lang['days_arr'] as $k => $d){ ?><label class="v3-day"><input type="checkbox" name="work_days[]" value="<?php echo v3_e($k); ?>"<?php if(in_array($k, $days)) echo ' checked'; ?> /><span><?php echo v3_e($d); ?></span></label><?php } ?>
			</div>
			<div class="v3-form-row v3-hours-row">
				<label>Отваря<select name="opening" data-open><?php echo v3_times($opening); ?></select></label>
				<label>Затваря<select name="closing" data-close><?php echo v3_times($closing); ?></select></label>
				<label class="v3-check"><input type="checkbox" data-allday<?php if($allday) echo ' checked'; ?> /> <span>Денонощно</span></label>
			</div>
		</section>

		<section class="v3-card v3-bsec">
			<h2 class="v3-h3"><?php echo v3_icon('tag', 20); ?> Плащане и сертификати</h2>
			<span class="v3-label">Приемани начини на плащане</span>
			<div class="v3-days"><?php foreach($lang['pay_methods'] as $k => $t){ ?><label class="v3-day"><input type="checkbox" name="pay_methods[]" value="<?php echo v3_e($k); ?>"<?php if(in_array($k, $pays)) echo ' checked'; ?> /><span><?php echo v3_e($t); ?></span></label><?php } ?></div>
			<span class="v3-label">Сертификати и лицензи</span>
			<div class="v3-days">
				<?php foreach($lang['certs_arr'] as $k => $t){ ?><label class="v3-day"><input type="checkbox" name="certificates[]" value="<?php echo v3_e($k); ?>"<?php if(in_array($k, $certs)) echo ' checked'; ?> /><span><?php echo v3_e($t); ?></span></label><?php } ?>
				<?php foreach($certs as $c){ if(isset($lang['certs_arr'][$c]) || $c === '') continue; ?><label class="v3-day"><input type="checkbox" name="certificates[]" value="<?php echo v3_e($c); ?>" checked /><span><?php echo v3_e($c); ?></span></label><?php } ?>
			</div>
		</section>

		<section id="snimki" class="v3-card v3-bsec">
			<h2 class="v3-h3"><?php echo v3_icon('search', 20); ?> Снимки</h2>
			<div class="v3-logo-edit">
				<span class="v3-label">Лого</span>
				<?php if(!empty($P['logo'])){ ?><img src="<?php echo v3_e(WebSite.'/images/adverts/'.$P['logo']); ?>" alt="Лого" /><label class="v3-check"><input type="checkbox" name="del_logo" value="<?php echo v3_e($P['logo']); ?>" /> <span>Изтрий логото</span></label><?php } ?>
				<label class="v3-file">Качи <?php echo !empty($P['logo']) ? 'ново ' : ''; ?>лого <small class="v3-muted">(JPG или PNG)</small><input type="file" name="logo" accept="image/jpeg,image/png" /></label>
			</div>
			<span class="v3-label">Галерия <small class="v3-muted">· до <?php echo $max_images; ?> снимки за едно качване; първата е главна</small></span>
			<div class="v3-upload-grid v3-upload-grid-5" data-gallery-edit>
				<?php $i = 0; foreach($gallery as $img_id => $g){ ?>
				<div class="v3-upload-thumb v3-upload-old" data-old-img><img src="<?php echo v3_e(WebSite.'/images/adverts/'.$g['image']); ?>" alt="" loading="lazy" /><?php if($i++ == 0) echo '<em>Главна</em>'; ?><button type="button" class="v3-upload-del" data-del-gimg="<?php echo (int)$img_id; ?>" aria-label="Изтрий снимката">✕</button></div>
				<?php } ?>
				<label class="v3-upload-add"><input type="file" name="images[]" accept="image/jpeg,image/png" multiple data-gimages data-max="<?php echo $max_images; ?>" /><?php echo v3_icon('plus', 22); ?><span>Добави снимки</span></label>
			</div>
		</section>

		<section id="faktura" class="v3-card v3-bsec">
			<h2 class="v3-h3"><?php echo v3_icon('user', 20); ?> Данни за фактуриране</h2>
			<p class="v3-small">Не се показват публично. Нужни са за издаване на фактури.</p>
			<div class="v3-form-row">
				<label>Фирма / име <i class="v3-req">*</i><input type="text" name="company_name" value="<?php echo $v('company_name'); ?>" required /></label>
				<label>МОЛ <i class="v3-req">*</i><input type="text" name="mol" value="<?php echo $v('mol') !== '' ? $v('mol') : v3_e(isset($_SESSION['customer_names']) ? $_SESSION['customer_names'] : ''); ?>" required /></label>
			</div>
			<div class="v3-form-row">
				<label>ЕИК / Булстат <i class="v3-req">*</i><input type="text" name="bulstat" value="<?php echo $v('bulstat'); ?>" required /></label>
				<label>ДДС номер <small class="v3-muted">(ако има)</small><input type="text" name="dds" value="<?php echo $v('dds'); ?>" /></label>
			</div>
			<div class="v3-form-row">
				<label>Град <i class="v3-req">*</i><input type="text" name="inv_city" list="v3-cities" value="<?php echo $v('inv_city') !== '' ? $v('inv_city') : $v('town'); ?>" required /></label>
				<label>Адрес за фактура <i class="v3-req">*</i><input type="text" name="inv_address" value="<?php echo $v('inv_address') !== '' ? $v('inv_address') : $v('company_address'); ?>" required /></label>
			</div>
		</section>

		<?php if($plan > 1){ ?>
		<section class="v3-card v3-bsec">
			<h2 class="v3-h3"><?php echo v3_icon('tag', 20); ?> Продажба на бизнеса</h2>
			<?php if($plan == 3){ ?>
			<input type="hidden" name="sellbusiness" value="1" />
			<label>Цена на бизнеса (EUR) <i class="v3-req">*</i><input type="number" name="business_price" min="1" step="1" value="<?php echo $v('business_price'); ?>" required /></label>
			<?php }else{ ?>
			<p class="v3-muted">Профилът <?php echo !empty($P['sellbusiness']) ? '<strong>е обявен за продажба</strong>' : 'не е обявен за продажба'; ?>. За промяна се свържете с нас.</p>
			<?php if(!empty($P['sellbusiness'])){ ?><input type="hidden" name="sellbusiness" value="1" /><input type="hidden" name="business_price" value="<?php echo $v('business_price'); ?>" /><?php } ?>
			<?php } ?>
		</section>
		<?php } ?>

		<p class="v3-form-msg" role="alert"></p>
		<div class="v3-pform-actions v3-bform-actions">
			<a class="v3-btn v3-btn-line" href="<?php echo v3_url('/mydash.php?my_listings=show'); ?>">Откажи</a>
			<button class="v3-btn v3-btn-orange v3-btn-lg" type="submit" name="save_advert" value="1">Запази промените</button>
		</div>
	</form>
</div>
<?php v3_footer(); ?>
