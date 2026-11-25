<?php
/* ==========================================================================
   v3 monthly statistics for one business (owner only).
   Included from profile_visits.php, call_statistics.php and site_visits.php
   AFTER their own ownership check ($_GET['aid'] belongs to the logged-in customer).
   $v3_stat: 'visits' | 'calls' | 'site'
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';

$aid = (int)$_GET['aid'];
$kinds = array(
	'visits' => array('Посещения на профила', '/profile_visits.php', 'statistics_adverts_visits', 'addeddate', ''),
	'calls'  => array('Обаждания', '/call_statistics.php', 'statistics_adverts_calls', 'added_date', ''),
	'site'   => array('Кликове към сайта ви', '/site_visits.php', 'statistics_adverts_visits', 'addeddate', ' AND sitevisited = "1"'),
);
if(!isset($kinds[$v3_stat])) $v3_stat = 'visits';
list($label, $self, $table, $col, $extra) = $kinds[$v3_stat];

$ad = array('supplier_name' => '', 'site' => '');
$r = mysql_query('SELECT supplier_name, site FROM adverts WHERE id = "'.$aid.'"'); if($r && mysql_num_rows($r)) $ad = mysql_fetch_assoc($r);

// all months with data, newest year first
$data = array(); $years = array();
$r = mysql_query('SELECT YEAR('.$col.') y, MONTH('.$col.') m, COUNT(*) n FROM '.$table.' WHERE advert_id = "'.$aid.'"'.$extra.' GROUP BY y, m');
if($r) while($x = mysql_fetch_assoc($r)){ $data[(int)$x['y']][(int)$x['m']] = (int)$x['n']; $years[(int)$x['y']] = 1; }
krsort($years); $years = array_keys($years);
$year = isset($_GET['year']) ? (int)$_GET['year'] : ($years ? $years[0] : (int)date('Y'));
if($years && !in_array($year, $years)) $year = $years[0];
$months = array(1=>'Яну','Фев','Мар','Апр','Май','Юни','Юли','Авг','Сеп','Окт','Ное','Дек');
$months_full = array(1=>'Януари','Февруари','Март','Април','Май','Юни','Юли','Август','Септември','Октомври','Ноември','Декември');
$vals = array(); for($m = 1; $m <= 12; $m++) $vals[$m] = isset($data[$year][$m]) ? $data[$year][$m] : 0;
$max = max(1, max($vals)); $sum = array_sum($vals);
$all = 0; foreach($data as $y => $ms) $all += array_sum($ms);
$last_m = ($year == (int)date('Y')) ? (int)date('n') : 12;

v3_head('<title>'.v3_e($label).' | Моето табло | Espravki.com</title><meta name="robots" content="noindex, nofollow" />');
v3_header();
?>
<div class="v3-wrap v3-statpage">
	<nav class="v3-crumbs" aria-label="Път"><a href="<?php echo v3_url('/mydash.php'); ?>">Моето табло</a> <span>/</span> <span>Статистика</span></nav>
	<h1 class="v3-h2"><?php echo v3_e($label); ?></h1>
	<p class="v3-muted"><?php echo v3_t($ad['supplier_name']); ?></p>

	<nav class="v3-tabs-inline v3-stats-tabs" aria-label="Вид статистика">
		<?php foreach($kinds as $k => $v){ if($k == 'site' && trim($ad['site']) === '') continue; ?>
		<a href="<?php echo v3_url($v[1].'?aid='.$aid); ?>"<?php if($k == $v3_stat) echo ' class="is-on" aria-current="page"'; ?>><?php echo v3_e($v[0]); ?></a>
		<?php } ?>
	</nav>

	<div class="v3-stats-sum">
		<div class="v3-card"><span>През <?php echo $year; ?></span><strong><?php echo number_format($sum, 0, ',', ' '); ?></strong></div>
		<div class="v3-card"><span>Средно на месец</span><strong><?php echo number_format($sum / max(1, $last_m), 1, ',', ' '); ?></strong></div>
		<div class="v3-card"><span>Общо от началото</span><strong><?php echo number_format($all, 0, ',', ' '); ?></strong></div>
	</div>

	<section class="v3-card v3-stats-chart">
		<div class="v3-stats-chart-head">
			<h2 class="v3-h3"><?php echo v3_e($label); ?> по месеци</h2>
			<?php if(count($years) > 1){ ?>
			<form method="get" action="<?php echo v3_url($self); ?>" class="v3-sort">
				<input type="hidden" name="aid" value="<?php echo $aid; ?>" />
				<label>Година: <select name="year" onchange="this.form.submit()"><?php foreach($years as $y) echo '<option', ($y == $year ? ' selected' : ''), '>', $y, '</option>'; ?></select></label>
				<noscript><button class="v3-btn v3-btn-line v3-btn-sm">OK</button></noscript>
			</form>
			<?php } ?>
		</div>
		<?php if($all == 0){ ?>
		<p class="v3-muted">Все още няма отчетени <?php echo $v3_stat == 'calls' ? 'обаждания' : ($v3_stat == 'site' ? 'кликове' : 'посещения'); ?>. Те се броят автоматично, когато клиент отвори профила ви или натисне бутона за обаждане.</p>
		<?php }else{ ?>
		<div class="v3-mbars" role="img" aria-label="<?php echo v3_e($label.' по месеци за '.$year); ?>">
			<?php foreach($vals as $m => $v){ $h = round(100 * $v / $max); ?>
			<div class="v3-mbar<?php if($m > $last_m) echo ' is-future'; ?>" title="<?php echo $months_full[$m], ' ', $year, ': ', $v; ?>">
				<span class="v3-mbar-n"><?php echo $v ? $v : ''; ?></span>
				<span class="v3-mbar-fill" style="height:<?php echo max($v ? 3 : 0, $h); ?>%"></span>
				<span class="v3-mbar-m"><?php echo $months[$m]; ?></span>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</section>

	<p><a class="v3-btn v3-btn-line" href="<?php echo v3_url('/mydash.php'); ?>">‹ Обратно към таблото</a></p>
</div>
<?php v3_footer(); ?>
