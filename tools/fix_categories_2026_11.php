<?php
/* One-time data fix for the business catalogue (2026-11-25).
   Run ONCE on the server from the site folder:   php tools/fix_categories_2026_11.php
   Safe to run again: every step checks first and changes nothing that is already right.
   It only touches products_categories (names, parent group) and adds missing category links
   for 4 businesses. No business data, no URLs are changed. */
if(php_sapi_name() !== 'cli'){ header('HTTP/1.1 404 Not Found'); exit; }
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
chdir(dirname(__FILE__).'/..');
require dirname(__FILE__).'/mysql_compat.php';   // newer command-line PHP has no mysql_*
require 'config.php';
mysql_query('SET NAMES utf8');

$done = 0;
function run($sql, $what){
	global $done;
	if(!mysql_query($sql)){ echo "ГРЕШКА при: $what\n  ", mysql_error(), "\n"; return; }
	$n = mysql_affected_rows();
	echo ($n > 0 ? "OK      " : "вече е  "), $what, "\n";
	$done += max(0, $n);
}
function cat_exists($id){ $r = mysql_query('SELECT id FROM products_categories WHERE id = '.(int)$id); return $r && mysql_num_rows($r) > 0; }

echo "== 1. Имена на категории ==\n";
$names = array(
	10183 => 'Бизнес и посредничество',
	10147 => 'Банки и финансови институции',
	10278 => 'Солна стая',
	10266 => 'Термопомпи',
	10283 => 'Юридически услуги',
	10272 => 'Електроуслуги',
	10271 => 'Технически прегледи',
	10288 => 'Супермаркети и хипермаркети',
	10284 => 'Търговски вериги и магазини',
);
foreach($names as $id => $n) if(cat_exists($id))
	run('UPDATE products_categories SET bg_category = "'.mysql_real_escape_string($n).'" WHERE id = '.$id.' AND BINARY bg_category != BINARY "'.mysql_real_escape_string($n).'"', "категория $id → $n");

echo "\n== 2. Двойна категория „електроуслуги“ → „Електроуслуги“ ==\n";
if(cat_exists(10280) && cat_exists(10272)){
	run('INSERT INTO adverts_to_product_categories (advert_id, category_id)
		SELECT t.advert_id, 10272 FROM adverts_to_product_categories t
		WHERE t.category_id = 10280 AND NOT EXISTS (SELECT 1 FROM adverts_to_product_categories x WHERE x.advert_id = t.advert_id AND x.category_id = 10272)', 'фирмите от 10280 добавени в 10272');
	run('UPDATE products_categories SET visible = "0" WHERE id = 10280 AND visible = "1"', 'категория 10280 скрита (дубликат)');
}

echo "\n== 3. Самостоятелни категории → в своята група ==\n";
$parents = array(
	10190 => 10263, 10262 => 10263,                   // недвижими имоти
	10147 => 10183, 10291 => 10183, 10292 => 10183,   // бизнес и посредничество
	10275 => 10228,                                   // заведения
	10289 => 10197, 10239 => 10197, 10267 => 10197, 10277 => 10197, // търговия
	10246 => 10058, 10255 => 10058, 10273 => 10058,   // производители
	10286 => 10040, 10287 => 10040, 10285 => 10040,   // държавни институции
	10272 => 10048, 10274 => 10048, 10281 => 10048, 10268 => 10048, 10244 => 10048, 10283 => 10048, // услуги
	10279 => 10134,                                   // туризъм
	10290 => 10051,                                   // онлайн магазини
	10282 => 10057, 10288 => 10057, 10284 => 10057,   // магазини
	10241 => 10076,                                   // компютри
	10278 => 10090,                                   // здраве, красота и релакс
	10266 => 10175,                                   // строителство
	10271 => 10060,                                   // авто мото
);
foreach($parents as $id => $p) if(cat_exists($id) && cat_exists($p))
	run('UPDATE products_categories SET parent = '.$p.' WHERE id = '.$id.' AND parent = 0', "категория $id → група $p");

echo "\n== 4. Фирми без категория ==\n";
$links = array(
	960  => array(10291, 10129),  // Щендер в Terra Mall, Габрово (продава се)
	972  => array(10291, 10255),  // Овощна градина Кичево (продава се)
	974  => array(10267),         // BoatMarket, Бургас
	1017 => array(10291, 10231),  // Kebab Zone, Севлиево (продава се)
);
foreach($links as $aid => $cs){
	$r = mysql_query('SELECT supplier_name FROM adverts WHERE id = '.$aid);
	if(!$r || !mysql_num_rows($r)){ echo "няма фирма $aid, пропускам\n"; continue; }
	$x = mysql_fetch_row($r);
	foreach($cs as $c) if(cat_exists($c))
		run('INSERT INTO adverts_to_product_categories (advert_id, category_id) SELECT '.$aid.', '.$c.' FROM DUAL
			WHERE NOT EXISTS (SELECT 1 FROM adverts_to_product_categories WHERE advert_id = '.$aid.' AND category_id = '.$c.')', "фирма $aid ({$x[0]}) → категория $c");
}

$r = mysql_query('SELECT COUNT(*) FROM adverts a WHERE a.active = "1" AND a.deleted = "0" AND a.id NOT IN (SELECT t.advert_id FROM adverts_to_product_categories t JOIN products_categories pc ON pc.id = t.category_id WHERE pc.visible = "1")');
$x = $r ? mysql_fetch_row($r) : array('?');
echo "\nГотово. Променени записи: $done. Активни фирми, които още не се виждат в каталога: {$x[0]}\n";
