<?php
/* Admin home: what needs attention today. */
if(!isset($_SESSION['adman'])){ @header("Location:../index.php"); exit(0); }
require_once '../modules/content_filter.php';
require_once '../modules/reports_db.php';
mysql_query('SET NAMES utf8');
reports_table();

function home_n($sql){ $r = @mysql_query($sql); if(!$r) return 0; $x = mysql_fetch_row($r); return (int)$x[0]; }
function home_e($s){ return htmlspecialchars(strip_tags(html_entity_decode((string)$s, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'); }

$cards = array(
	array('Нови листинги', home_n('SELECT COUNT(*) FROM adverts WHERE active = "0" AND deleted = "0"'), 'index.php?page=new_adverts', 'чакат одобрение'),
	array('Нови продукти', home_n('SELECT COUNT(*) FROM products WHERE checked_by_admin = "0" AND deleted = "0"'), 'index.php?page=new_products', 'чакат одобрение'),
	array('Сигнали', home_n('SELECT COUNT(*) FROM reports WHERE status = 0'), 'index.php?page=reports', 'нови, непрегледани'),
	array('Отзиви', home_n('SELECT COUNT(*) FROM statistics_adverts_visits WHERE approved = "0" AND comment != ""'), 'index.php?page=comments&new=show', 'чакат одобрение'),
	array('Изтичат до 7 дни', home_n('SELECT COUNT(*) FROM adverts WHERE active = "1" AND deleted = "0" AND expired_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)'), '#expiring', 'платени планове'),
	array('Активни бизнеси', home_n('SELECT COUNT(*) FROM adverts WHERE active = "1" AND deleted = "0"'), 'index.php?page=adverts', 'общо на сайта'),
);
?>
<style>
.ah-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;margin:18px 0 28px}
.ah-card{display:block;background:#fff;border:1px solid #dfe3ea;border-radius:10px;padding:16px;text-decoration:none;color:#223}
.ah-card strong{display:block;font-size:32px;line-height:1.1}
.ah-card.hot{border-color:#e67e22;background:#fff7ef}
.ah-card small{color:#778}
.ah-table{width:100%;border-collapse:collapse;background:#fff;margin-bottom:28px}
.ah-table th,.ah-table td{border:1px solid #dfe3ea;padding:7px 9px;text-align:left;vertical-align:top}
.ah-table th{background:#f4f6fa}
</style>
<h1>Добре дошли, <?php echo home_e($_SESSION['admin_name']); ?></h1>
<p>Какво чака вашето внимание днес (<?php echo date('d.m.Y'); ?>):</p>
<div class="ah-grid">
<?php foreach($cards as $i => $c){ ?>
	<a class="ah-card<?php if($i < 5 && $c[1] > 0) echo ' hot'; ?>" href="<?php echo $c[2]; ?>"><small><?php echo $c[0]; ?></small><strong><?php echo $c[1]; ?></strong><small><?php echo $c[3]; ?></small></a>
<?php } ?>
</div>

<h2 id="expiring">Планове, които изтичат до 7 дни</h2>
<p>Добре е да напомните на търговеца да поднови плана.</p>
<table class="ah-table"><tr><th>Бизнес</th><th>Изтича на</th><th>Имейл</th><th>Телефон</th><th></th></tr>
<?php
$r = mysql_query('SELECT id, supplier_name, expired_date, mail, company_phones FROM adverts WHERE active = "1" AND deleted = "0" AND expired_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY expired_date ASC LIMIT 100');
$any = false;
if($r) while($a = mysql_fetch_assoc($r)){ $any = true;
	echo '<tr><td>', home_e($a['supplier_name']), '</td><td>', date('d.m.Y', strtotime($a['expired_date'])), '</td>
	<td>', ($a['mail'] ? '<a href="mailto:'.home_e($a['mail']).'?subject='.rawurlencode('Вашият план в Espravki.com изтича скоро').'">'.home_e($a['mail']).'</a>' : '–'), '</td>
	<td>', home_e(str_replace(';', ', ', $a['company_phones'])), '</td><td><a href="index.php?page=edit_advert&aid=', (int)$a['id'], '">Отвори</a></td></tr>';
}
if(!$any) echo '<tr><td colspan="5">Няма планове, които изтичат тази седмица.</td></tr>';
?>
</table>

<h2>Подозрителни активни обяви</h2>
<p>Автоматична проверка на последните 300 активни бизнеса и продукта за думи като „предплата“, „реплика“, оръжия, лекарства и др. Това е само подсказка – отворете обявата и преценете сами. Думите се добавят в <code>modules/content_filter.php</code>.</p>
<table class="ah-table"><tr><th>Обява</th><th>Какво е намерено</th><th></th></tr>
<?php
$any = false;
$r = mysql_query('SELECT id, supplier_name, meta_description, description FROM adverts WHERE active = "1" AND deleted = "0" ORDER BY id DESC LIMIT 300');
if($r) while($a = mysql_fetch_assoc($r)){
	$f = content_flags($a['supplier_name'].' '.$a['meta_description'].' '.$a['description']);
	if($f){ $any = true; echo '<tr><td>Бизнес: ', home_e($a['supplier_name']), '</td><td style="color:#c0392b">', home_e(implode(', ', $f)), '</td><td><a href="index.php?page=edit_advert&aid=', (int)$a['id'], '">Отвори</a></td></tr>'; }
}
$r = mysql_query('SELECT id, title, description FROM products WHERE active = "1" AND deleted = "0" AND checked_by_admin = "1" ORDER BY id DESC LIMIT 300');
if($r) while($p = mysql_fetch_assoc($r)){
	$f = content_flags($p['title'].' '.$p['description']);
	if($f){ $any = true; echo '<tr><td>Продукт: ', home_e($p['title']), '</td><td style="color:#c0392b">', home_e(implode(', ', $f)), '</td><td><a href="index.php?page=edit_product&pid=', (int)$p['id'], '">Отвори</a></td></tr>'; }
}
if(!$any) echo '<tr><td colspan="3">Не са открити подозрителни обяви.</td></tr>';
?>
</table>

<h2>Последни сигнали</h2>
<table class="ah-table"><tr><th>Дата</th><th>Причина</th><th>Текст</th><th>Статус</th></tr>
<?php
$reasons = reports_reasons(); $any = false;
$r = mysql_query('SELECT * FROM reports ORDER BY id DESC LIMIT 5');
if($r) while($x = mysql_fetch_assoc($r)){ $any = true;
	echo '<tr><td>', date('d.m.Y H:i', strtotime($x['added_date'])), '</td><td>', home_e(isset($reasons[$x['reason']]) ? $reasons[$x['reason']] : '?'), '</td><td>', home_e(mb_substr($x['message'], 0, 120, 'UTF-8')), '</td><td>', ($x['status'] == 0 ? '<b style="color:#e67e22">нов</b>' : 'прегледан'), '</td></tr>';
}
if(!$any) echo '<tr><td colspan="4">Все още няма сигнали.</td></tr>';
?>
</table>
<p><a href="index.php?page=reports">Всички сигнали →</a></p>
