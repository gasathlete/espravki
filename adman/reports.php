<?php
/* Сигнали от посетителите ("Подай сигнал" на бизнеси и продукти). */
if(!isset($_SESSION['adman'])){ @header("Location:../index.php"); exit(0); }
require_once '../modules/reports_db.php';
mysql_query('SET NAMES utf8');
reports_table();
$reasons = reports_reasons();
$statuses = array(0 => 'Нов', 1 => 'Решен – обявата е коригирана/скрита', 2 => 'Отхвърлен – няма нарушение');

$msg = '';
if(isset($_POST['report_id'])){
	$rid = (int)$_POST['report_id'];
	if(isset($_POST['report_delete'])){
		mysql_query('DELETE FROM reports WHERE id = '.$rid);
		$msg = 'Сигналът е изтрит.';
	}else{
		$st = isset($_POST['status']) && isset($statuses[(int)$_POST['status']]) ? (int)$_POST['status'] : 0;
		$note = mb_substr(trim(strip_tags((string)$_POST['admin_note'])), 0, 500, 'UTF-8');
		mysql_query('UPDATE reports SET status = '.$st.', admin_note = "'.mysql_real_escape_string($note).'" WHERE id = '.$rid);
		// hide a business: it goes back to "Нови листинги" for checking
		if(!empty($_POST['hide_item'])){
			$x = mysql_fetch_assoc(mysql_query('SELECT item_type, item_id FROM reports WHERE id = '.$rid));
			if($x && $x['item_type'] == 'advert') mysql_query('UPDATE adverts SET active = "0" WHERE id = '.(int)$x['item_id']);
			if($x && $x['item_type'] == 'product') mysql_query('UPDATE products SET checked_by_admin = "0" WHERE id = '.(int)$x['item_id']);
		}
		$msg = 'Записано.';
	}
}

$show = isset($_GET['all']) ? 'all' : 'new';
$q = mysql_query('SELECT * FROM reports'.($show == 'new' ? ' WHERE status = 0' : '').' ORDER BY id DESC LIMIT 300');
function rep_e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

echo '<h2>Сигнали от посетители</h2>';
echo '<p><a href="./index.php?page=reports">Нови</a> | <a href="./index.php?page=reports&all=1">Всички</a></p>';
if($msg) echo '<div class="msg">', $msg, '</div>';
echo '<p style="max-width:1000px">Отворете обявата, проверете сигнала и изберете решение. „Скрий обявата“ сваля бизнеса от сайта и го връща в „Нови листинги“ (за продукт – в „Нови продукти“). По закон (Акт за цифровите услуги) търговецът трябва да бъде уведомен с кратка причина, когато обявата му е скрита.</p>';
echo '<table width="1000" class="p"><tbody><tr><th>Дата</th><th>Обява</th><th>Причина</th><th>Текст</th><th>Подател</th><th>Решение</th></tr>';
if($q && mysql_num_rows($q)){
	while($r = mysql_fetch_assoc($q)){
		$name = '(изтрита)'; $url = '';
		if($r['item_type'] == 'advert'){
			$a = mysql_fetch_assoc(mysql_query('SELECT a.supplier_name, a.link_name, a.active, (SELECT pc.url FROM products_categories pc JOIN adverts_to_product_categories t ON t.category_id = pc.id WHERE t.advert_id = a.id LIMIT 1) AS cat FROM adverts a WHERE a.id = '.(int)$r['item_id']));
			if($a){ $name = 'Бизнес: '.strip_tags(html_entity_decode($a['supplier_name'], ENT_QUOTES, 'UTF-8')).($a['active'] == '1' ? '' : ' [скрит]'); $url = WebSite.'/business-directory/'.$a['cat'].'/'.$a['link_name']; }
		}else{
			$p = mysql_fetch_assoc(mysql_query('SELECT title, product_url, category_url FROM products WHERE id = '.(int)$r['item_id']));
			if($p){ $name = 'Продукт: '.strip_tags(html_entity_decode($p['title'], ENT_QUOTES, 'UTF-8')); $url = WebSite.'/products/'.$p['category_url'].'/'.$p['product_url']; }
		}
		echo '<form method="POST" action="./index.php?page=reports', ($show == 'all' ? '&all=1' : ''), '"><input type="hidden" name="report_id" value="', (int)$r['id'], '"/>
		<tr><td>', rep_e(date('d.m.Y H:i', strtotime($r['added_date']))), '</td>
		<td>', ($url ? '<a href="'.rep_e($url).'" target="_blank">'.rep_e($name).'</a>' : rep_e($name)), '</td>
		<td>', rep_e(isset($reasons[$r['reason']]) ? $reasons[$r['reason']] : '?'), '</td>
		<td style="max-width:300px">', nl2br(rep_e($r['message'])), '</td>
		<td>', ($r['email'] ? '<a href="mailto:'.rep_e($r['email']).'">'.rep_e($r['email']).'</a>' : '–'), '<br/><small>', rep_e($r['ip']), '</small></td>
		<td><select name="status">';
		foreach($statuses as $k => $v) echo '<option value="', $k, '"', ($k == $r['status'] ? ' selected' : ''), '>', $v, '</option>';
		echo '</select><br/><input type="text" name="admin_note" placeholder="Бележка" value="', rep_e($r['admin_note']), '"/>
		<br/><label><input type="checkbox" name="hide_item" value="1"/> Скрий обявата</label>
		<br/><input type="submit" value="Запази"/> <input type="submit" name="report_delete" value="Изтрий" onclick="return confirm(\'Да изтрия ли сигнала?\')"/></td></tr></form>';
	}
}else echo '<tr><td align="center" colspan="6">Няма сигнали</td></tr>';
echo '</tbody></table>';
