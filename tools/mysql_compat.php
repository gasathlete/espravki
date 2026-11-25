<?php
/* The command-line "php" on the server can be newer than the site's PHP 5.4 and
   has no mysql_* functions. This file adds them on top of mysqli, ONLY for the
   scripts in tools/ (the website itself is not affected). */
if(!function_exists('mysql_connect') && function_exists('mysqli_connect')){

function mysql_compat_link($l = null){ return $l ? $l : (isset($GLOBALS['__mysql_compat_link']) ? $GLOBALS['__mysql_compat_link'] : null); }
function mysql_connect($host = null, $user = null, $pass = null){
	$port = null;
	if($host && strpos($host, ':') !== false){ list($host, $port) = explode(':', $host, 2); $port = (int)$port; }
	$l = @mysqli_connect($host, $user, $pass, '', $port ? $port : 3306);
	if($l) $GLOBALS['__mysql_compat_link'] = $l;
	return $l;
}
function mysql_pconnect($h = null, $u = null, $p = null){ return mysql_connect($h, $u, $p); }
function mysql_select_db($db, $l = null){ return mysqli_select_db(mysql_compat_link($l), $db); }
function mysql_query($q, $l = null){ return mysqli_query(mysql_compat_link($l), $q); }
function mysql_error($l = null){ $l = mysql_compat_link($l); return $l ? mysqli_error($l) : mysqli_connect_error(); }
function mysql_errno($l = null){ $l = mysql_compat_link($l); return $l ? mysqli_errno($l) : mysqli_connect_errno(); }
function mysql_real_escape_string($s, $l = null){ return mysqli_real_escape_string(mysql_compat_link($l), (string)$s); }
function mysql_escape_string($s){ return mysql_real_escape_string($s); }
function mysql_fetch_row($r){ $x = mysqli_fetch_row($r); return $x === null ? false : $x; }
function mysql_fetch_assoc($r){ $x = mysqli_fetch_assoc($r); return $x === null ? false : $x; }
function mysql_fetch_array($r, $t = 3){ $x = mysqli_fetch_array($r, $t == 1 ? MYSQLI_ASSOC : ($t == 2 ? MYSQLI_NUM : MYSQLI_BOTH)); return $x === null ? false : $x; }
function mysql_num_rows($r){ return mysqli_num_rows($r); }
function mysql_affected_rows($l = null){ return mysqli_affected_rows(mysql_compat_link($l)); }
function mysql_insert_id($l = null){ return mysqli_insert_id(mysql_compat_link($l)); }
function mysql_set_charset($c, $l = null){ return mysqli_set_charset(mysql_compat_link($l), $c); }
function mysql_free_result($r){ mysqli_free_result($r); return true; }
function mysql_close($l = null){ return mysqli_close(mysql_compat_link($l)); }
if(!defined('MYSQL_ASSOC')){ define('MYSQL_ASSOC', 1); define('MYSQL_NUM', 2); define('MYSQL_BOTH', 3); }
}
