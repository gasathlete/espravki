<?php
/*
 * Brute-force protection for login forms, counted per IP address (cannot be bypassed by deleting cookies).
 * Stores small files in the server temp folder - no database changes needed. PHP 5.4 compatible.
 *
 *   if(login_guard_blocked('admin')) { ...show "too many attempts"... }
 *   on wrong password: login_guard_fail('admin');
 *   on success:        login_guard_ok('admin');
 */
if(!function_exists('login_guard_file')){

function login_guard_limits($scope){
	// array(max failed attempts, time window and lock time in seconds)
	if($scope == 'admin') return array(5, 3600);   // 5 wrong tries -> locked for 1 hour
	return array(10, 900);                          // 10 wrong tries -> locked for 15 minutes
}

function login_guard_file($scope){
	$dir = rtrim(sys_get_temp_dir(), '/\\').'/espravki_login_guard';
	if(!is_dir($dir)) @mkdir($dir, 0700, true);
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'none';
	return $dir.'/'.preg_replace('/[^a-z]/', '', $scope).'_'.md5($ip).'.txt';
}

function login_guard_read($scope){
	$f = login_guard_file($scope);
	$d = is_file($f) ? @json_decode(@file_get_contents($f), true) : null;
	if(!is_array($d) || !isset($d['n'], $d['t'])) $d = array('n'=>0, 't'=>time());
	$lim = login_guard_limits($scope);
	if(time() - $d['t'] > $lim[1]) $d = array('n'=>0, 't'=>time()); // window expired
	// the owner can lift the lock from the server: php tools/check_admin.php leaves this marker
	// (kept in the site folder, because the web and the command line may use different temp folders)
	$reset = dirname(__FILE__).'/tools/.guard_reset_'.preg_replace('/[^a-z]/', '', $scope);
	if($d['n'] > 0 && is_file($reset) && @filemtime($reset) >= $d['t']) $d = array('n'=>0, 't'=>time());
	return $d;
}

function login_guard_blocked($scope){
	$d = login_guard_read($scope);
	$lim = login_guard_limits($scope);
	return $d['n'] >= $lim[0];
}

function login_guard_fail($scope){
	$d = login_guard_read($scope);
	$d['n']++;
	$d['t'] = time();
	@file_put_contents(login_guard_file($scope), json_encode($d), LOCK_EX);
	usleep(500000); // slow down automated guessing
}

function login_guard_ok($scope){
	$f = login_guard_file($scope);
	if(is_file($f)) @unlink($f);
}

}
