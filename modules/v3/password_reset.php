<?php
/* ==========================================================================
   Password reset by e-mail link (used by ajax/passforgotten.php and
   userlogins/reset.php). Tokens: table `password_resets` (created on first use).
   Only a SHA-256 of the token is stored; the link works once and for 1 hour.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/../../password_lib.php';

define('PW_RESET_TTL', 3600);

function pw_reset_table(){
	static $ok = null; if($ok !== null) return $ok;
	$ok = (bool)mysql_query('CREATE TABLE IF NOT EXISTS password_resets (
		id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		customer_id INT UNSIGNED NOT NULL,
		token_hash CHAR(64) NOT NULL,
		expires DATETIME NOT NULL,
		used TINYINT(1) NOT NULL DEFAULT 0,
		ip VARCHAR(45) NOT NULL DEFAULT "",
		created DATETIME NOT NULL,
		UNIQUE KEY token_hash (token_hash),
		KEY customer (customer_id)
	) DEFAULT CHARSET=utf8');
	return $ok;
}

/* create a token and e-mail the link; true when mail() accepted it */
function pw_reset_send($cid, $name, $email){
	if(!pw_reset_table()) return false;
	$token = bin2hex(pass_random_bytes(32));
	mysql_query('UPDATE password_resets SET used = 1 WHERE customer_id = '.(int)$cid.' AND used = 0');   // only the newest link works
	mysql_query('INSERT INTO password_resets SET customer_id = '.(int)$cid.', token_hash = "'.hash('sha256', $token).'",
		expires = DATE_ADD(NOW(), INTERVAL '.(int)PW_RESET_TTL.' SECOND), ip = "'.mysql_real_escape_string(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '').'", created = NOW()');
	$link = WebSite.'/userlogins/reset.php?t='.$token;
	$hi = trim(strip_tags((string)$name)); $hi = $hi !== '' ? 'Здравей, '.htmlspecialchars($hi, ENT_QUOTES, 'UTF-8').',' : 'Здравей,';
	$body = '<p>'.$hi.'</p>
<p>Получихме заявка за нова парола за профила ти в Espravki.com.</p>
<p><a href="'.$link.'" style="display:inline-block;background:#eb5c00;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 22px;border-radius:8px;">Задай нова парола</a></p>
<p>Ако бутонът не работи, копирай този адрес в браузъра:<br/>'.$link.'</p>
<p>Линкът важи 1 час и може да се ползва само веднъж. Ако не си искал нова парола, не прави нищо: старата ти парола продължава да работи.</p>
<p>Екипът на Espravki.com</p>';
	return (bool)send_mail(array(
		'content_type' => 'text/html',
		'from_email' => official_mail_sender,
		'from_name' => official_mail_sender_name,
		'replay_to' => 'eespravki@gmail.com',
		'subject' => 'Нова парола за Espravki.com',
		'to' => $email,
		'body' => $body,
	));
}

/* the customer id for a valid, unused, not expired token; 0 otherwise */
function pw_reset_check($token){
	if(!preg_match('/^[a-f0-9]{64}$/', (string)$token) || !pw_reset_table()) return 0;
	$r = mysql_query('SELECT customer_id FROM password_resets WHERE token_hash = "'.hash('sha256', $token).'" AND used = 0 AND expires > NOW() LIMIT 1');
	if(!$r || !mysql_num_rows($r)) return 0;
	$x = mysql_fetch_row($r); return (int)$x[0];
}

/* set the new password and burn the token */
function pw_reset_finish($token, $plain){
	$cid = pw_reset_check($token); if(!$cid) return false;
	$ok = mysql_query('UPDATE customers SET password = "'.mysql_real_escape_string(pass_hash_for_db($plain)).'", realp = "" WHERE id = '.$cid);
	if(!$ok) return false;
	mysql_query('UPDATE password_resets SET used = 1 WHERE customer_id = '.$cid);
	return $cid;
}
