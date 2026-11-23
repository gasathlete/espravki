<?php
/* ==========================================================================
   v3 login / register / password reset (design A03). Included from
   userlogins/index.php for visitors who are not logged in.
   Uses the SAME endpoints as the classic page (and their protections):
   ajax/check_login.php, ajax/register.php (2 steps: e-mail code), ajax/passforgotten.php
   Expects: $authUrl (Google login), $names, $mail, $lang.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$start = isset($_GET['showform']) ? 'register' : 'login';
$has_plan = !empty($_SESSION['selected_plan']) || !empty($_SESSION['products']);
// where to go after a new registration: continue the started flow, otherwise the new user's dashboard
$after_register = $has_plan ? WebSite.'/userlogins/index.php' : WebSite.'/mydash.php?profile=show';

$meta  = '<title>Вход и регистрация | Espravki.com</title>';
$meta .= '<meta name="description" content="Влез в профила си в Espravki.com, за да управляваш своя бизнес и обяви, или създай нов профил." />';
$meta .= '<meta name="robots" content="noindex, follow" />';
$meta .= '<link rel="canonical" href="'.v3_e(WebSite.'/userlogins/index.php').'" />';

v3_head($meta);
?>
<div class="v3-auth-page">
	<div class="v3-auth-box v3-card" data-auth data-start="<?php echo $start; ?>" data-after-register="<?php echo v3_e($after_register); ?>">
		<div class="v3-auth-top">
			<?php echo v3_logo(); ?>
			<a class="v3-auth-back" href="<?php echo v3_url('/'); ?>">Към сайта ↗</a>
		</div>

		<!-- LOGIN -->
		<section data-view="login">
			<h1>Добре дошъл отново</h1>
			<p class="v3-muted">Влез в профила си, за да управляваш своя бизнес и обяви.</p>
			<form class="v3-form v3-auth-form" data-form="login" novalidate>
				<label>Имейл<span class="v3-input-ic"><?php echo v3_icon('mail', 18); ?><input type="email" name="email" autocomplete="email" placeholder="name@example.com" required /></span></label>
				<label>Парола<span class="v3-input-ic"><?php echo v3_icon('lock', 18); ?><input type="password" name="pass" autocomplete="current-password" required /><button type="button" class="v3-eye" data-eye aria-label="Покажи паролата">👁</button></span></label>
				<a class="v3-auth-link v3-right" href="#parola" data-go="forgot">Забравена парола?</a>
				<p class="v3-form-msg" role="alert"></p>
				<button class="v3-btn v3-btn-dark v3-btn-block v3-btn-lg" type="submit">Вход</button>
			</form>
			<?php if(!empty($authUrl) && defined('google_client_id') && google_client_id){ // hidden until new Google keys are set in config.php ?>
			<div class="v3-or"><span>или</span></div>
			<a class="v3-btn v3-btn-line v3-btn-block v3-google" href="<?php echo v3_e($authUrl); ?>"><svg width="20" height="20" viewBox="0 0 48 48" aria-hidden="true"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.3 0-9.7-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C37 39.2 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/></svg> Вход с Google</a>
			<?php } ?>
			<p class="v3-center v3-auth-switch">Нямаш профил? <a href="#registracia" data-go="register"><strong>Създай профил.</strong></a></p>
		</section>

		<!-- REGISTER -->
		<section data-view="register" hidden>
			<h1>Запази своя профил</h1>
			<p class="v3-muted">Създай профил безплатно и добави бизнеса си, когато си готов.</p>
			<form class="v3-form v3-auth-form" data-form="register" novalidate>
				<div data-step="1">
					<label>Име и фамилия<span class="v3-input-ic"><?php echo v3_icon('user', 18); ?><input type="text" name="reg_name" autocomplete="name" value="<?php echo v3_e($names); ?>" required /></span></label>
					<label>Имейл<span class="v3-input-ic"><?php echo v3_icon('mail', 18); ?><input type="email" name="reg_email" autocomplete="email" value="<?php echo v3_e($mail); ?>" placeholder="name@example.com" required /></span></label>
					<label>Парола<span class="v3-input-ic"><?php echo v3_icon('lock', 18); ?><input type="password" name="reg_password" autocomplete="new-password" minlength="8" required /><button type="button" class="v3-eye" data-eye aria-label="Покажи паролата">👁</button></span></label>
					<p class="v3-small">Използвай дълга, уникална парола (поне 8 знака).</p>
					<label class="v3-check"><input type="checkbox" name="terms" required /> <span>Приемам <a href="<?php echo v3_url('/news/terms-and-conditions'); ?>" target="_blank">Общите условия</a> и <a href="<?php echo v3_url('/news/terms-for-traders'); ?>" target="_blank">Условията за търговци</a></span></label>
				</div>
				<div data-step="2" hidden>
					<div class="v3-note v3-note-info"><?php echo v3_icon('mail', 20); ?><span>Изпратихме код за потвърждение на <strong data-sent-to></strong>. Провери пощата си (и папка „Спам“).</span></div>
					<label>Код от имейла<span class="v3-input-ic"><?php echo v3_icon('check', 18); ?><input type="text" name="code" inputmode="text" autocomplete="one-time-code" required /></span></label>
					<a class="v3-auth-link" href="#registracia" data-back-step>← Промени имейла</a>
				</div>
				<p class="v3-form-msg" role="alert"></p>
				<button class="v3-btn v3-btn-orange v3-btn-block v3-btn-lg" type="submit" data-submit-label="Създай акаунт" data-confirm-label="Потвърди и създай акаунт">Създай акаунт</button>
			</form>
			<p class="v3-center v3-auth-switch">Вече имаш акаунт? <a href="#vhod" data-go="login"><strong>Вход.</strong></a></p>
		</section>

		<!-- FORGOT -->
		<section data-view="forgot" hidden>
			<h1>Възстанови достъпа</h1>
			<p class="v3-muted">Въведи имейла си и ще ти изпратим нова парола. След вход можеш да я смениш от профила си.</p>
			<form class="v3-form v3-auth-form" data-form="forgot" novalidate>
				<label>Имейл<span class="v3-input-ic"><?php echo v3_icon('mail', 18); ?><input type="email" name="email" autocomplete="email" placeholder="name@example.com" required /></span></label>
				<p class="v3-form-msg" role="alert"></p>
				<button class="v3-btn v3-btn-blue v3-btn-block v3-btn-lg" type="submit">Изпрати нова парола</button>
			</form>
			<p class="v3-center v3-auth-switch"><a href="#vhod" data-go="login">← Обратно към вход</a></p>
		</section>
	</div>
	<p class="v3-center v3-small v3-auth-foot"><a href="<?php echo v3_url('/news/terms-and-conditions'); ?>">Условия</a> · <a href="<?php echo v3_url('/contacts/'); ?>">Помощ</a> · <a href="#" data-cookie-settings>Бисквитки</a></p>
</div>
<?php v3_footer(true); ?>
