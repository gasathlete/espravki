<?php
/* Forgotten password, step 2: the link from the e-mail opens this page to choose a new password. */
session_start();
header('Content-Type: text/html; charset=utf-8');
header('Referrer-Policy: no-referrer');   // the token must not leak to other sites
require '../config.php';
require '../project_functions.php';
if(empty($_SESSION['lang'])) $_SESSION['lang'] = 'bg';
require '../translation/'.$_SESSION['lang'].'_lang.php';
require_once '../modules/v3/layout.php';
require_once '../modules/v3/password_reset.php';

$token = isset($_REQUEST['t']) ? (string)$_REQUEST['t'] : '';
$cid = pw_reset_check($token);
$err = ''; $done = false;
if($cid && $_SERVER['REQUEST_METHOD'] == 'POST'){
	$p1 = isset($_POST['p1']) ? (string)$_POST['p1'] : ''; $p2 = isset($_POST['p2']) ? (string)$_POST['p2'] : '';
	if(empty($_POST['csrf']) || empty($_SESSION['pw_csrf']) || $_POST['csrf'] !== $_SESSION['pw_csrf']) $err = 'Страницата е стояла отворена твърде дълго. Опитай отново.';
	elseif(strlen($p1) < 8) $err = 'Паролата трябва да е поне 8 знака.';
	elseif($p1 !== $p2) $err = 'Двете пароли не съвпадат.';
	elseif(pw_reset_finish($token, $p1)){ $done = true; unset($_SESSION['pw_csrf']); }
	else $err = 'Не успяхме да запишем паролата. Опитай отново или ни пиши на eespravki@gmail.com.';
}
if(empty($_SESSION['pw_csrf'])) $_SESSION['pw_csrf'] = bin2hex(pass_random_bytes(16));

v3_head('<title>Нова парола | Espravki.com</title><meta name="robots" content="noindex, nofollow" />');
?>
<div class="v3-auth-page">
	<div class="v3-auth-box v3-card">
		<div class="v3-auth-top"><?php echo v3_logo(); ?><a class="v3-auth-back" href="<?php echo v3_url('/'); ?>">Към сайта ↗</a></div>
		<?php if($done){ ?>
			<h1>Паролата е сменена</h1>
			<p class="v3-muted">Вече можеш да влезеш с новата си парола.</p>
			<a class="v3-btn v3-btn-dark v3-btn-block" href="<?php echo v3_url('/userlogins/index.php'); ?>">Към входа</a>
		<?php }elseif(!$cid){ ?>
			<h1>Линкът не е валиден</h1>
			<p class="v3-muted">Линкът е изтекъл (важи 1 час), вече е използван или е копиран непълно. Поискай нов от „Забравена парола?“.</p>
			<a class="v3-btn v3-btn-dark v3-btn-block" href="<?php echo v3_url('/userlogins/index.php#parola'); ?>">Поискай нов линк</a>
		<?php }else{ ?>
			<h1>Задай нова парола</h1>
			<p class="v3-muted">Поне 8 знака. Добре е да има букви и цифри.</p>
			<form class="v3-form v3-auth-form" method="post" action="<?php echo v3_url('/userlogins/reset.php'); ?>" autocomplete="off">
				<input type="hidden" name="t" value="<?php echo v3_e($token); ?>" />
				<input type="hidden" name="csrf" value="<?php echo v3_e($_SESSION['pw_csrf']); ?>" />
				<label><span>Нова парола</span><input type="password" name="p1" minlength="8" required autocomplete="new-password" /></label>
				<label><span>Повтори паролата</span><input type="password" name="p2" minlength="8" required autocomplete="new-password" /></label>
				<?php if($err) echo '<p class="v3-form-msg is-err" role="alert">', v3_e($err), '</p>'; ?>
				<button class="v3-btn v3-btn-dark v3-btn-block" type="submit">Запази новата парола</button>
			</form>
		<?php } ?>
	</div>
</div>
<?php v3_footer(true); ?>
