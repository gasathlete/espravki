<?php
// Standalone error page (no database needed, so it works even when the DB is down)
$code = 404;
if(isset($_SERVER['REDIRECT_STATUS']) && (int)$_SERVER['REDIRECT_STATUS'] >= 400) $code = (int)$_SERVER['REDIRECT_STATUS'];
$titles = array(
	400 => 'Невалидна заявка',
	401 => 'Нужен е вход',
	403 => 'Достъпът е забранен',
	404 => 'Страницата не е намерена',
	500 => 'Временна грешка',
	502 => 'Временна грешка',
	504 => 'Временна грешка'
);
$title = isset($titles[$code]) ? $titles[$code] : 'Грешка';
if(!headers_sent()){
	header('Content-Type: text/html; charset=utf-8');
	header('HTTP/1.1 '.$code.' '.$title, true, $code);
}
?><!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="robots" content="noindex, follow" />
<title><?php echo $title; ?> | eSpravki.com</title>
<style>
body{font-family:Arial,Helvetica,sans-serif;background:#f5f6fa;color:#333;margin:0;padding:16px;}
.box{max-width:560px;margin:10vh auto;background:#fff;border-radius:8px;padding:32px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,.08);}
h1{font-size:64px;margin:0;color:#f5a623;}
h2{margin:8px 0 16px;}
a.btn{display:inline-block;margin:6px;padding:10px 18px;background:#f5a623;color:#fff;text-decoration:none;border-radius:4px;}
</style>
</head>
<body>
<div class="box">
	<h1><?php echo $code; ?></h1>
	<h2><?php echo $title; ?></h2>
	<p>Съжаляваме, но тази страница не е налична. Може да е преместена или премахната.</p>
	<a class="btn" href="https://www.espravki.com/">Към началната страница</a>
	<a class="btn" href="https://www.espravki.com/business-directory/">Бизнес каталог</a>
</div>
</body>
</html>
