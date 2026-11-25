<?php
/* Why does the admin login fail? Run on the server from the site folder:
       php tools/check_admin.php
   Asks for the same e-mail, password and code you type in /adman/, says which
   part does not match, and removes the 1-hour lock after wrong tries.
   Nothing is stored in the code. */
if(php_sapi_name() !== 'cli'){ header('HTTP/1.1 404 Not Found'); exit; }
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
chdir(dirname(__FILE__).'/..');
require 'config.php';
mysql_query('SET NAMES utf8');
function ask($q){ echo $q; return trim(fgets(STDIN)); }

$r = mysql_query('SELECT id, admin_name, email, active FROM admins ORDER BY id');
if(!$r){ echo "ГРЕШКА с базата: ", mysql_error(), "\n"; exit(1); }
echo "Администратори в базата: ", mysql_num_rows($r), "\n";
while($x = mysql_fetch_row($r)) echo "  #{$x[0]}  {$x[1]}  ", $x[3] == '1' ? 'активен' : 'НЕАКТИВЕН', "\n";

$email = ask("\nИмейл (както го пишете на входа): ");
$pass  = ask('Парола: ');
$code  = ask('Код: ');

$r = mysql_query('SELECT id, password, code, active FROM admins WHERE email = "'.mysql_real_escape_string($email).'"');
if(!$r || !mysql_num_rows($r)) echo "\nНЕ     Няма администратор с този имейл. Пуснете: php tools/set_admin.php\n";
else {
	$a = mysql_fetch_assoc($r);
	echo "\nимейл:   OK\n";
	echo "парола:  ", $a['password'] === md5($pass) ? "OK" : "НЕ съвпада", "\n";
	echo "код:     ", $a['code'] === md5($code) ? "OK" : "НЕ съвпада", "\n";
	echo "активен: ", $a['active'] == '1' ? "OK" : "НЕ", "\n";
	if($a['password'] === md5($pass) && $a['code'] === md5($code) && $a['active'] == '1') echo "\nДанните са верни. Ако входът пак не става, причината е заключване след грешни опити (махнато по-долу) или стара сесия: затворете браузъра или опитайте в частен прозорец.\n";
}

// remove the "too many wrong tries" lock (kept as small files in the temp folder)
$n = 0;
// the web server may use another temp folder than the command line: try all usual ones
$home = preg_match('#^(/home\d*/[^/]+)/#', getcwd().'/', $hm) ? $hm[1] : getenv('HOME');
$sp = ini_get('session.save_path'); if(strpos($sp, ';') !== false) $sp = substr($sp, strrpos($sp, ';') + 1);
foreach(array_unique(array(sys_get_temp_dir(), '/tmp', '/var/tmp', ini_get('upload_tmp_dir'), $sp, $home.'/tmp', getenv('HOME').'/tmp')) as $d){
	$d = rtrim((string)$d, '/\\').'/espravki_login_guard';
	if($d !== '/espravki_login_guard' && is_dir($d)) foreach(glob($d.'/admin_*.txt') as $f) if(@unlink($f)) $n++;
}
echo "\nЗаключвания за админ вход, махнати сега: $n\n";
