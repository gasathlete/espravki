<?php
/* E-mail delivery test. Run on the server from the site folder:
       php tools/mailtest.php eespravki@gmail.com
   Sends one test message through the site's own send_mail() (the same way
   password links, contact forms and comments are sent) and says whether the
   server accepted it. Then check the inbox AND the Spam folder. */
if(php_sapi_name() !== 'cli'){ header('HTTP/1.1 404 Not Found'); exit; }
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
chdir(dirname(__FILE__).'/..');
$_SERVER['HTTP_HOST'] = 'www.espravki.com'; $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
require 'config.php';
require 'project_functions.php';

$to = isset($argv[1]) ? trim($argv[1]) : '';
if(!filter_var($to, FILTER_VALIDATE_EMAIL)){ echo "Напиши имейл след командата, например:\n  php tools/mailtest.php eespravki@gmail.com\n"; exit(1); }

echo "Подател (From):  ", official_mail_sender, "\n";
echo "sendmail_path:   ", ini_get('sendmail_path'), "\n";
$ok = send_mail(array(
	'from_email' => official_mail_sender, 'from_name' => official_mail_sender_name,
	'subject' => 'Тест на имейлите от Espravki.com', 'to' => $to,
	'body' => '<p>Здравей,</p><p>Това е тестово писмо от сайта, изпратено на '.date('d.m.Y H:i').'.</p><p>Ако го виждаш във „Входящи“, имейлите на сайта работят. Ако е в „Спам“, кажи ни.</p>',
));
echo $ok ? "OK      Сървърът прие писмото. Провери пощата на $to (и папка „Спам“) до 5 минути.\n"
         : "ГРЕШКА  Сървърът НЕ прие писмото (mail() върна false). Пощата на хостинга не работи за този сайт.\n";
