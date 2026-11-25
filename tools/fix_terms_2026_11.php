<?php
/* One-time text fix (2026-11-25): in "Условия за включване на търговец" (articles.id = 41)
   the supplier was an old company. The supplier is Иванов & Милев Девелъпмънт ЕООД.
   Run ONCE on the server from the site folder:   php tools/fix_terms_2026_11.php
   Safe to run again: if the old text is not there, nothing changes. */
if(php_sapi_name() !== 'cli'){ header('HTTP/1.1 404 Not Found'); exit; }
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
chdir(dirname(__FILE__).'/..');
require dirname(__FILE__).'/mysql_compat.php';   // newer command-line PHP has no mysql_*
require 'config.php';
mysql_query('SET NAMES utf8');

$old = 'Глобал Комерс Енд Дистрибюшън ЕООД, с Булстат: 200250810 и адрес за кореспонденция: гр. Бургас, ул. Ради Николов 30, представлявано от Мариян Маринов';
// the article text is stored HTML-encoded, so "&" is kept as "&amp;amp;" (shown as "&")
$new = 'Иванов &amp;amp; Милев Девелъпмънт ЕООД, с Булстат: 206957326 и адрес на управление: гр. София, бул. България 13, представлявано от Даниел Милев';

$r = mysql_query('SELECT id, bg_article_title, bg_short_description FROM articles WHERE id = 41');
if(!$r || !mysql_num_rows($r)){ echo "Няма статия 41. Нищо не е променено.\n"; exit; }
$a = mysql_fetch_assoc($r);
$n = substr_count($a['bg_short_description'], $old);
if($n == 0){ echo "вече е  Доставчикът в „{$a['bg_article_title']}“ е правилен. Нищо не е променено.\n"; exit; }
$text = str_replace($old, $new, $a['bg_short_description']);
if(mysql_query('UPDATE articles SET bg_short_description = "'.mysql_real_escape_string($text).'" WHERE id = 41'))
	echo "OK      „{$a['bg_article_title']}“: доставчикът е сменен на Иванов & Милев Девелъпмънт ЕООД ($n място).\n";
else echo "ГРЕШКА: ", mysql_error(), "\n";
