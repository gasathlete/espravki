<?php
/* GDPR "право да бъдеш забравен": the logged-in customer deletes their own account.
   Businesses and products are taken off the site (deleted = 1), personal data in `customers`
   is wiped (the row stays, so old orders/invoices still add up). Owner gets an e-mail. */
session_start();
header("Content-Type: text/html; charset=utf-8");
require 'config.php';
if(empty($_SESSION['lang'])) $_SESSION['lang'] = 'bg';
require 'translation/'.$_SESSION['lang'].'_lang.php';
require 'project_functions.php';
require_once 'password_lib.php';
mysql_query('SET NAMES utf8');

if(empty($_SESSION['customer'])){ header('Location: '.WebSite.'/userlogins/'); exit; }
$me = (int)$_SESSION['customer'];
if(empty($_SESSION['del_token'])) $_SESSION['del_token'] = md5(uniqid(mt_rand(), true));

$r = mysql_query('SELECT customer_names, customer_email, password FROM customers WHERE id = '.$me);
$cust = $r ? mysql_fetch_assoc($r) : null;
if(!$cust){ header('Location: '.WebSite.'/'); exit; }
$needs_pass = !empty($cust['password']);   // Google-only accounts have no password

$err = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!isset($_POST['token']) || $_POST['token'] !== $_SESSION['del_token']) $err = 'Сесията е изтекла. Опитай пак.';
	elseif(!isset($_POST['confirm']) || trim($_POST['confirm']) !== 'ИЗТРИЙ') $err = 'Напиши думата ИЗТРИЙ с главни букви.';
	elseif($needs_pass && (empty($_POST['pass']) || !pass_verify($_POST['pass'], $cust['password']))) $err = 'Грешна парола.';
	else{
		mysql_query('UPDATE products SET deleted = "1", active = "0" WHERE advert_id IN (SELECT id FROM adverts WHERE customer_id = '.$me.')');
		mysql_query('UPDATE adverts SET deleted = "1", active = "0" WHERE customer_id = '.$me);
		$ok = mysql_query('UPDATE customers SET customer_names = "Изтрит потребител", customer_email = "deleted-'.$me.'@deleted.invalid",
			customer_phone = "", customer_address = "", city = "", postcode = "", facebook = "", linkedin = "", about_me = "",
			profile_image = "", password = "", realp = "" WHERE id = '.$me);
		if($ok){
			@send_mail(array('content_type' => 'text/html', 'replay_to' => official_mail_sender, 'from_name' => official_site_name, 'from_email' => official_mail_sender,
				'bcc' => '', 'to' => 'eespravki@gmail.com', 'subject' => 'Изтрит профил №'.$me,
				'body' => 'Потребител №'.$me.' ('.htmlspecialchars($cust['customer_email']).') изтри профила си на '.date('d.m.Y H:i').'. Обявите му са свалени, личните данни са заличени.<br />Ако има профилна снимка или фактури – провери ги ръчно.'));
			$_SESSION = array();
			session_destroy();
			header('Location: '.WebSite.'/?account_deleted=1');
			exit;
		}
		$err = 'Нещо не се получи. Пиши ни на eespravki@gmail.com.';
	}
}

require 'modules/v3/layout.php';
v3_head('<title>Изтриване на профила | Espravki.com</title><meta name="robots" content="noindex, nofollow" />');
v3_header();
?>
<article class="v3-wrap v3-article">
	<header class="v3-article-head"><h1>Изтриване на профила</h1></header>
	<div class="v3-card v3-form" style="max-width:560px;padding:1.5em">
		<p><strong>Това не може да се отмени.</strong> Ще бъдат:</p>
		<ul>
			<li>свалени от сайта всички твои бизнеси и продукти;</li>
			<li>заличени името, имейлът, телефонът и адресът ти;</li>
			<li>прекратени платените планове без връщане на суми за оставащия период (виж <a href="<?php echo v3_url('/pravila/plashtania-i-vrashtane'); ?>">условията</a>).</li>
		</ul>
		<p class="v3-small v3-muted">Счетоводните документи за плащания пазим 10 години, както изисква законът.</p>
		<?php if($err) echo '<div class="v3-alert v3-alert-off">', v3_e($err), '</div>'; ?>
		<form method="post" action="<?php echo v3_url('/delete_account.php'); ?>">
			<input type="hidden" name="token" value="<?php echo v3_e($_SESSION['del_token']); ?>" />
			<?php if($needs_pass){ ?><label>Парола<input type="password" name="pass" autocomplete="current-password" required /></label><?php } ?>
			<label>За потвърждение напиши <strong>ИЗТРИЙ</strong><input type="text" name="confirm" autocomplete="off" required /></label>
			<p><button class="v3-btn v3-btn-orange" type="submit">Изтрий профила завинаги</button> <a class="v3-btn v3-btn-line" href="<?php echo v3_url('/mydash.php?profile=show'); ?>">Откажи</a></p>
		</form>
	</div>
</article>
<?php v3_footer(); ?>
