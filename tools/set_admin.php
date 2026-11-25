<?php
/* Leaves ONE administrator for the admin panel (/adman/) and removes all others.
   Run on the server from the site folder:   php tools/set_admin.php
   It ASKS for the e-mail, password and code on the screen, so they are never
   stored in the code (the code is public on GitHub).
   Login form: e-mail + password + code (the "код" field). */
if(php_sapi_name() !== 'cli'){ header('HTTP/1.1 404 Not Found'); exit; }
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
chdir(dirname(__FILE__).'/..');
require 'config.php';
mysql_query('SET NAMES utf8');

function ask($q){ echo $q; return trim(fgets(STDIN)); }

$r = mysql_query('SELECT id, admin_name, email FROM admins ORDER BY id');
echo "Сега има ", $r ? mysql_num_rows($r) : 0, " администратори:\n";
if($r) while($x = mysql_fetch_row($r)) echo "  #{$x[0]}  {$x[1]}\n";
echo "\n";

$email = ask('Имейл за вход: ');
$pass  = ask('Парола: ');
$code  = ask('Код: ');
$name  = ask('Име (по желание, Enter за „Даниел“): '); if($name === '') $name = 'Даниел';
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ echo "Невалиден имейл. Нищо не е променено.\n"; exit(1); }
if(strlen($pass) < 8){ echo "Паролата трябва да е поне 8 знака. Нищо не е променено.\n"; exit(1); }
if(strlen($code) < 4){ echo "Кодът трябва да е поне 4 знака. Нищо не е променено.\n"; exit(1); }
if(strtolower(ask("\nОставям САМО $email и махам всички други. Да продължа? (da/ne): ")) !== 'da'){ echo "Отказано. Нищо не е променено.\n"; exit; }

// same format the admin login checks (adman/suppliers/admin_functions.php)
$e = mysql_real_escape_string($email);
$set = 'user_group = 1, admin_name = "'.mysql_real_escape_string($name).'", email = "'.$e.'", password = "'.md5($pass).'", code = "'.md5($code).'", active = "1", registrated = "1"';
$r = mysql_query('SELECT id FROM admins WHERE LOWER(email) = LOWER("'.$e.'") LIMIT 1');
if($r && mysql_num_rows($r)){ $x = mysql_fetch_row($r); $keep = (int)$x[0]; $ok = mysql_query('UPDATE admins SET '.$set.' WHERE id = '.$keep); }
else { $ok = mysql_query('INSERT INTO admins SET '.$set); $keep = (int)mysql_insert_id(); }
if(!$ok || !$keep){ echo "ГРЕШКА: ", mysql_error(), "\nНищо друго не е променено.\n"; exit(1); }

mysql_query('DELETE FROM admins WHERE id != '.$keep);
echo "\nOK      Администратор: $email (#$keep). Махнати други: ", mysql_affected_rows(), ".\n";
echo "Вход: https://www.espravki.com/adman/  (имейл + парола + код)\n";
