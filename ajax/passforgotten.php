<?php
/* Forgotten password, step 1: e-mail a one-time link (valid 1 hour) to set a new password.
   The password itself is never sent by e-mail and is not changed until the customer
   opens the link and chooses a new one (userlogins/reset.php).
   The answer is the same whether the e-mail exists or not (no account guessing). */
require_once dirname(__FILE__).'/../password_lib.php';
session_start();
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') exit;
require '../config.php';
if($_SERVER['HTTP_HOST'] != full_domain_name) exit;
if(empty($_SESSION['lang'])) $_SESSION['lang'] = 'bg';
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
require_once '../modules/v3/password_reset.php';

$response = array('', 0);
if(isset($_SESSION['customer'])){
	echo json_encode(array('Вече си влязъл в профила си. Паролата се сменя от „Моят профил“ → „Настройки“.', 0)); exit;
}
$email = isset($_REQUEST['email']) ? trim($_REQUEST['email']) : '';
if($email === '' || !is_mail($email)){
	echo json_encode(array(isset($lang['wrong_email']) ? $lang['wrong_email'] : 'Невалиден имейл.', 0)); exit;
}
// a few requests per visitor session is enough; stops someone flooding a mailbox
if(!isset($_SESSION['pw_reset_n'])) $_SESSION['pw_reset_n'] = 0;
if(++$_SESSION['pw_reset_n'] > 5){
	echo json_encode(array('Твърде много опити. Опитай отново след малко или ни пиши на eespravki@gmail.com.', 0)); exit;
}

$generic = 'Ако има профил с този имейл, изпратихме линк за нова парола. Провери пощата си и папка „Спам“. Линкът важи 1 час.';
$q = 'SELECT id, customer_names, customer_email FROM customers WHERE LOWER(TRIM(customer_email)) = LOWER("'.mysql_real_escape_string($email).'") LIMIT 1';
$r = mysql_query($q);
if($r && mysql_num_rows($r)){
	$row = mysql_fetch_row($r);
	$sent = pw_reset_send((int)$row[0], $row[1], trim($row[2]));
	if(!$sent){
		error_log('passforgotten: mail() failed for customer id '.(int)$row[0]);
		echo json_encode(array('Не успяхме да изпратим имейла в момента. Опитай отново по-късно или ни пиши на eespravki@gmail.com.', 0)); exit;
	}
}
echo json_encode(array($generic, 1));
