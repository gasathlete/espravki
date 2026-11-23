<?php
/*
 * Customer password hashing (PHP 5.4 compatible - password_hash() needs PHP 5.5).
 * New passwords: bcrypt ($2y$, cost 10). Old passwords: md5 - still accepted,
 * and upgraded to bcrypt automatically on the next successful login.
 * Needs customers.password to be VARCHAR(60) or longer.
 */
if(!function_exists('pass_hash')){

function pass_random_bytes($n){
	if(function_exists('openssl_random_pseudo_bytes')){
		$b = openssl_random_pseudo_bytes($n, $strong);
		if($b !== false && strlen($b) == $n) return $b;
	}
	if(function_exists('mcrypt_create_iv')){
		$b = @mcrypt_create_iv($n, MCRYPT_DEV_URANDOM);
		if($b !== false && strlen($b) == $n) return $b;
	}
	if(@is_readable('/dev/urandom')){
		$f = @fopen('/dev/urandom', 'rb');
		if($f){ $b = fread($f, $n); fclose($f); if(strlen($b) == $n) return $b; }
	}
	$b = '';
	for($i = 0; $i < $n; $i++) $b .= chr(mt_rand(0, 255));
	return $b;
}

function pass_hash($plain){
	$alphabet = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$bytes = pass_random_bytes(22);
	$salt = '';
	for($i = 0; $i < 22; $i++) $salt .= $alphabet[ord($bytes[$i]) % 64];
	$h = crypt((string)$plain, '$2y$10$'.$salt.'$');
	if(strlen($h) == 60) return $h;
	return md5((string)$plain); // bcrypt unavailable (should not happen on PHP 5.3.7+)
}

// constant-time compare
function pass_equals($a, $b){
	if(strlen($a) != strlen($b)) return false;
	$r = 0;
	for($i = 0; $i < strlen($a); $i++) $r |= ord($a[$i]) ^ ord($b[$i]);
	return $r === 0;
}

function pass_verify($plain, $stored){
	$stored = trim((string)$stored);
	if($stored === '') return false;
	if(substr($stored, 0, 4) == '$2y$' && strlen($stored) == 60) return pass_equals(crypt((string)$plain, $stored), $stored);
	if(strlen($stored) == 32) return pass_equals(md5((string)$plain), strtolower($stored));
	return false;
}

// true when the stored hash is old md5 and should be replaced by bcrypt
function pass_needs_upgrade($stored){
	return substr((string)$stored, 0, 4) != '$2y$' && pass_column_ok();
}

// safety: bcrypt is stored only if customers.password can hold 60 characters
function pass_column_ok(){
	static $ok = null;
	if($ok !== null) return $ok;
	$ok = false;
	$r = @mysql_query("SHOW COLUMNS FROM customers LIKE 'password'");
	if($r && ($c = mysql_fetch_assoc($r)) && preg_match('/\((\d+)\)/', $c['Type'], $m)) $ok = intval($m[1]) >= 60;
	elseif($r && isset($c['Type']) && stripos($c['Type'], 'text') !== false) $ok = true;
	return $ok;
}

// hash for saving a new password: bcrypt if the column is wide enough, else md5
function pass_hash_for_db($plain){
	return pass_column_ok() ? pass_hash($plain) : md5((string)$plain);
}

}
