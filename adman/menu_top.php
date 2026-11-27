<?php
require_once './suppliers/functions2.php';

function get_new(){
$query='select distinct id from adverts where active="0"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}

function get_new_products(){
$query='select distinct id from products where checked_by_admin="0"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}

function get_new_comments(){
$query='select distinct id from statistics_adverts_visits where approved="0" and comment!=""';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}
function get_new_reports(){
	$r = @mysql_query('select count(*) from reports where status="0"');   // table appears after the first report
	if(!$r) return 0; $x = mysql_fetch_row($r); return (int)$x[0];
}
function get_online(){
$query='select distinct sid from statistics where timest > "'.date('Y-m-d H:i:s', strtotime("-2 min")).'"';
	//$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}

/* new admin design (2026-11-27): top bar with counters, "Още" menu opens on hover/click without scripts */
function adm_nav($page, $label, $count = 0, $hot = 'orange'){
	$cur = isset($_GET['page']) ? $_GET['page'] : '';
	parse_str($page, $q);
	$on = (isset($q['page']) ? $q['page'] : '') === $cur;
	echo '<a class="adm-link', ($on ? ' is-on' : ''), '" href="./index.php', ($page ? '?'.$page : ''), '">', $label,
		($count ? ' <span class="adm-badge adm-badge-'.$hot.'">'.(int)$count.'</span>' : ''), '</a>';
}
if(isset($_SESSION['adman'])){
	$n_comments = get_new_comments();
?>
<header class="adm-bar">
	<a class="adm-logo" href="../adman/"><img src="../assets/espravki/logo.svg?v=4" alt="Espravki.com" /><span>Админ</span></a>
	<nav class="adm-nav" aria-label="Админ меню">
		<?php
		adm_nav('', 'Начало');
		adm_nav('page=new_adverts', 'Нови листинги', get_new());
		adm_nav('page=new_products', 'Нови продукти', get_new_products());
		adm_nav($n_comments ? 'page=comments&new=show' : 'page=comments', 'Отзиви', $n_comments);
		adm_nav('page=reports', 'Сигнали', get_new_reports(), 'red');
		?>
		<div class="adm-more" tabindex="0">
			<span class="adm-link">Още ▾</span>
			<div class="adm-drop">
				<?php
				adm_nav('page=adverts', 'Доставчици (всички бизнеси)');
				adm_nav('page=products', 'Продукти');
				adm_nav('page=customers', 'Клиенти');
				adm_nav('page=categories', 'Категории търговци');
				adm_nav('page=shop_categories', 'Категории магазин');
				adm_nav('page=articles', 'Статии');
				adm_nav('page=mail-marketing', 'Мейл маркетинг');
				adm_nav('page=mail_import', 'Мейл импорт');
				adm_nav('page=affiliates', 'Афилиейти');
				if($_SESSION['adman'] == '1') adm_nav('page=admins', 'Администратори');
				?>
			</div>
		</div>
	</nav>
	<div class="adm-user">
		<a class="adm-site" href="../" target="_blank">Към сайта ↗</a>
		<span><?php echo htmlspecialchars($_SESSION['admin_name'], ENT_QUOTES, 'UTF-8'); ?></span>
		<a class="adm-out" href="logout.php">Изход</a>
	</div>
</header>
<?php
}
else{
?>
<a href="../index.php">Home</a>
<?php
}
?>
