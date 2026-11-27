<?php
/* "Подай сигнал" – saves a report about a business or product and e-mails the owner. */
session_start();
header('Content-Type: application/json; charset=utf-8');
function report_out($ok, $err = ''){ echo json_encode($ok ? array('ok' => 1) : array('ok' => 0, 'error' => $err)); exit; }

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' || $_SERVER['REQUEST_METHOD'] != 'POST') report_out(false, 'Невалидна заявка.');
require '../config.php';
if(empty($_SESSION['lang'])) $_SESSION['lang'] = 'bg';
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
require '../modules/reports_db.php';
mysql_query('SET NAMES utf8');

// the form token proves the request came from our page (no forged requests)
if(empty($_SESSION['report_token']) || !isset($_POST['token']) || $_POST['token'] !== $_SESSION['report_token']) report_out(false, 'Сесията е изтекла. Презареди страницата.');
if(!empty($_POST['website'])) report_out(true);   // hidden field filled = bot; pretend success

$type   = isset($_POST['type']) && in_array($_POST['type'], array('advert','product')) ? $_POST['type'] : '';
$id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$reason = isset($_POST['reason']) ? (int)$_POST['reason'] : 0;
$text   = isset($_POST['message']) ? trim(strip_tags((string)$_POST['message'])) : '';
$email  = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
$reasons = reports_reasons();
if(!$type || $id < 1) report_out(false, 'Невалидна обява.');
if(!isset($reasons[$reason])) report_out(false, 'Избери причина.');
if(mb_strlen($text, 'UTF-8') < 10) report_out(false, 'Опиши проблема с поне 10 знака.');
if($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) report_out(false, 'Провери имейла.');
$text = mb_substr($text, 0, 2000, 'UTF-8');

// the item must exist
$r = $type == 'advert'
	? mysql_query('SELECT supplier_name FROM adverts WHERE id = '.$id)
	: mysql_query('SELECT title FROM products WHERE id = '.$id);
if(!$r || !mysql_num_rows($r)) report_out(false, 'Обявата не е намерена.');
$x = mysql_fetch_row($r); $item_name = strip_tags(html_entity_decode($x[0], ENT_QUOTES, 'UTF-8'));

reports_table();
$ip = mysql_real_escape_string(substr($_SERVER['REMOTE_ADDR'], 0, 45));
// limits: 5 reports per hour from one address, one report per item per address per day
$c = mysql_fetch_row(mysql_query('SELECT COUNT(*) FROM reports WHERE ip = "'.$ip.'" AND added_date > DATE_SUB(NOW(), INTERVAL 1 HOUR)'));
if($c[0] >= 5) report_out(false, 'Изпратил си много сигнали. Опитай пак по-късно.');
$c = mysql_fetch_row(mysql_query('SELECT COUNT(*) FROM reports WHERE ip = "'.$ip.'" AND item_type = "'.$type.'" AND item_id = '.$id.' AND added_date > DATE_SUB(NOW(), INTERVAL 1 DAY)'));
if($c[0] > 0) report_out(true);

$cust = isset($_SESSION['customer']) ? (int)$_SESSION['customer'] : 0;
$ok = mysql_query('INSERT INTO reports (item_type, item_id, reason, message, email, customer_id, ip, added_date) VALUES ("'.$type.'", '.$id.', '.$reason.', "'.mysql_real_escape_string($text).'", "'.mysql_real_escape_string($email).'", '.$cust.', "'.$ip.'", NOW())');
if(!$ok) report_out(false, 'Нещо не се получи. Опитай пак.');

$body = 'Нов сигнал за '.($type == 'advert' ? 'бизнес' : 'продукт').': <strong>'.htmlspecialchars($item_name, ENT_QUOTES, 'UTF-8').'</strong> (№ '.$id.')<br />'
	.'Причина: '.$reasons[$reason].'<br />Текст: '.nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8')).'<br />'
	.'Имейл за отговор: '.($email ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : '–').'<br />IP: '.htmlspecialchars($_SERVER['REMOTE_ADDR']).'<br /><br />'
	.'Прегледай в админ панела: '.WebSite.'/adman/index.php?page=reports';
@send_mail(array('content_type' => 'text/html', 'replay_to' => ($email ? $email : official_mail_sender), 'from_name' => official_site_name,
	'from_email' => official_mail_sender, 'bcc' => '', 'to' => 'eespravki@gmail.com', 'subject' => 'Нов сигнал: '.$reasons[$reason], 'body' => $body));
report_out(true);
