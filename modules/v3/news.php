<?php
/* ==========================================================================
   v3 news & articles. Included from news/index.php.
   /news/            list (tabs: Новини = category 2, Полезно = category 1)
   /news/<article>   one article (also the terms pages)
   Article bodies are HTML written by the site admin in the admin panel.
   ========================================================================== */
if(!defined('MyConst')) exit;
require_once dirname(__FILE__).'/layout.php';
$V3_PREVIEW = !empty($V3_PREVIEW);

function v3_news_img($f){ return $f ? WebSite.'/images/articles/'.$f : ''; }
function v3_news_date($d){
	$m = array('', 'януари','февруари','март','април','май','юни','юли','август','септември','октомври','ноември','декември');
	$t = strtotime($d); return $t ? date('j', $t).' '.$m[(int)date('n', $t)].' '.date('Y', $t) : '';
}
function v3_news_list($sql){ $o = array(); $r = mysql_query($sql); if($r) while($x = mysql_fetch_assoc($r)) $o[] = $x; return $o; }

/* =========================== one article =========================== */
if(!empty($row)){
	$a = $row;
	$is_help = ($a['category_id'] == 1);
	$img = v3_news_img($a['small_image']);
	$meta  = '<title>'.seo_attr($a['bg_article_title']).' | Espravki.com</title>';
	$meta .= '<meta name="description" content="'.seo_attr($a['bg_meta_description']).'" />';
	$meta .= '<meta property="og:title" content="'.seo_attr($a['bg_article_title']).'" /><meta property="og:type" content="article" />';
	$meta .= '<meta property="og:description" content="'.seo_attr($a['bg_meta_description']).'" />';
	$meta .= '<meta property="og:image" content="'.v3_e($img ? $img : WebSite.'/assets/espravki/og-share.jpg').'" />';
	$meta .= ($V3_PREVIEW || $a['active'] != '1') ? '<meta name="robots" content="noindex, follow" />' : '<meta name="robots" content="index, follow" />'.seo_canonical_tags('/news/'.$a['unique_name']);
	$ld = array('@context' => 'https://schema.org', '@type' => 'Article', 'headline' => html_entity_decode(strip_tags($a['bg_article_title']), ENT_QUOTES, 'UTF-8'),
		'datePublished' => date('c', strtotime($a['added_date'])), 'mainEntityOfPage' => seo_canonical_url('/news/'.$a['unique_name']),
		'publisher' => array('@type' => 'Organization', 'name' => 'Espravki.com', 'logo' => array('@type' => 'ImageObject', 'url' => WebSite.'/assets/espravki/icon-512.png')));
	if($img) $ld['image'] = $img;
	$meta .= '<script type="application/ld+json">'.str_replace('</', '<\/', json_encode($ld)).'</script>';

	$comments = v3_news_list('SELECT cnames AS name, comment, added_date FROM comments_to_articles WHERE article_id = "'.(int)$a['id'].'" AND active = "1" ORDER BY id ASC');
	$more = v3_news_list('SELECT unique_name, bg_article_title, small_image, added_date FROM articles WHERE active = "1" AND category_id = "'.(int)$a['category_id'].'" AND id != "'.(int)$a['id'].'" ORDER BY added_date DESC LIMIT 4');
	$body = htmlspecialchars_decode((string)$a['bg_short_description']);   // stored encoded, as the old page decoded it
	$body = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $body);   // never run scripts from article text

	v3_head($meta);
	v3_header();
	?>
	<article class="v3-wrap v3-article">
		<nav class="v3-crumbs" aria-label="Път"><a href="<?php echo v3_url('/'); ?>">Начало</a> <span>/</span> <a href="<?php echo v3_url('/news/'.($is_help ? '?tab=help' : '')); ?>"><?php echo $is_help ? 'Полезно' : 'Новини'; ?></a></nav>
		<header class="v3-article-head">
			<h1><?php echo v3_t($a['bg_article_title']); ?></h1>
			<?php if(!$is_help){ ?><p class="v3-muted"><?php echo v3_icon('clock', 16), ' ', v3_news_date($a['added_date']); ?></p><?php } ?>
		</header>
		<?php if($img && !$is_help){ ?><figure class="v3-article-img"><img src="<?php echo v3_e($img); ?>" alt="" /></figure><?php } ?>
		<div class="v3-article-body"><?php echo $body; ?></div>
		<?php foreach(array('image1','image2','image3','image4') as $k) if(!empty($a[$k])) echo '<figure class="v3-article-img"><img src="', v3_e(v3_news_img($a[$k])), '" alt="" loading="lazy" /></figure>'; ?>

		<?php if($comments){ ?>
		<section class="v3-article-comments">
			<h2 class="v3-h3"><?php echo count($comments); ?> <?php echo count($comments) == 1 ? 'коментар' : 'коментара'; ?></h2>
			<?php foreach($comments as $c){ ?>
			<div class="v3-card v3-comment"><strong><?php echo v3_t($c['name']); ?></strong> <span class="v3-muted"><?php echo v3_news_date($c['added_date']); ?></span><p><?php echo nl2br(v3_e(strip_tags(html_entity_decode($c['comment'], ENT_QUOTES, 'UTF-8')))); ?></p></div>
			<?php } ?>
		</section>
		<?php } ?>

		<?php if($a['active'] == '1'){ ?>
		<section class="v3-article-comments" id="komentar">
			<form class="v3-form v3-card v3-comment-form" id="v3-comment-form" data-id="<?php echo (int)$a['id']; ?>" data-title="<?php echo v3_e(strip_tags(html_entity_decode($a['bg_article_title'], ENT_QUOTES, 'UTF-8'))); ?>" novalidate>
				<h2 class="v3-h3">Напиши коментар</h2>
				<p class="v3-muted v3-small">Коментарите се публикуват след одобрение от екипа. Имейлът ти не се показва.</p>
				<div class="v3-form-row">
					<label><span>Име</span><input type="text" id="cm-names" autocomplete="name" required value="<?php echo v3_e(isset($_SESSION['customer_names']) ? $_SESSION['customer_names'] : ''); ?>" /></label>
					<label><span>Имейл</span><input type="email" id="cm-email" autocomplete="email" required value="<?php echo v3_e(isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : ''); ?>" /></label>
				</div>
				<label><span>Коментар</span><textarea id="cm-text" rows="4" required placeholder="Поне 20 знака"></textarea></label>
				<p class="v3-form-msg" id="cm-msg" role="status" aria-live="polite"></p>
				<button class="v3-btn v3-btn-blue" type="submit">Изпрати коментара</button>
			</form>
		</section>
		<script>
		(function(){
			var f = document.getElementById('v3-comment-form'); if(!f) return;
			var msg = document.getElementById('cm-msg');
			function val(id){ return document.getElementById(id).value.replace(/^\s+|\s+$/g, ''); }
			function err(id, t){ var el = document.getElementById(id); el.setAttribute('aria-invalid', 'true'); el.focus(); msg.className = 'v3-form-msg is-err'; msg.textContent = t; }
			f.addEventListener('submit', function(e){
				e.preventDefault();
				[].forEach.call(f.querySelectorAll('[aria-invalid]'), function(x){ x.removeAttribute('aria-invalid'); });
				var n = val('cm-names'), m = val('cm-email'), c = val('cm-text');
				if(n.length < 3 || !/^[a-zA-Zа-яА-ЯёЁѝЍ\s\-]+$/.test(n)) return err('cm-names', 'Напиши името си с букви.');
				if(!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(m)) return err('cm-email', 'Провери имейла.');
				if(c.length < 20) return err('cm-text', 'Коментарът трябва да е поне 20 знака.');
				var b = f.querySelector('button'); b.disabled = true; b.textContent = 'Изпращане…';
				var body = 'names=' + encodeURIComponent(n) + '&email=' + encodeURIComponent(m) + '&review=' + encodeURIComponent(c) + '&id=' + encodeURIComponent(f.getAttribute('data-id')) + '&atitle=' + encodeURIComponent(f.getAttribute('data-title'));
				var x = new XMLHttpRequest(); x.open('POST', '/ajax/save_comment.php', true);
				x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8'); x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
				x.onreadystatechange = function(){
					if(x.readyState !== 4) return;
					var r = null; try { r = JSON.parse(x.responseText); } catch(er){}
					if(r && r[1] == 1){ f.innerHTML = '<h2 class="v3-h3">Благодарим!</h2><p>Коментарът ти е изпратен и ще се появи след одобрение.</p>'; return; }
					b.disabled = false; b.textContent = 'Изпрати коментара';
					msg.className = 'v3-form-msg is-err'; msg.innerHTML = (r && r[0]) ? r[0] : 'Нещо не се получи. Опитай пак.';
				};
				x.send(body);
			});
		})();
		</script>
		<?php } ?>

		<aside class="v3-card v3-article-cta">
			<div><strong>Покажи бизнеса си онлайн</strong><p>Профил, продукти, цени и контакти на едно място.</p></div>
			<a class="v3-btn v3-btn-orange" href="<?php echo v3_cta_url(); ?>"><?php echo v3_cta_label(), ' ', v3_icon('arrow', 18); ?></a>
		</aside>

		<?php if($more){ ?>
		<section class="v3-article-more">
			<h2 class="v3-h3"><?php echo $is_help ? 'Още полезни статии' : 'Още новини'; ?></h2>
			<div class="v3-news-grid">
				<?php foreach($more as $m){ $mi = v3_news_img($m['small_image']); ?>
				<a class="v3-news-card v3-card" href="<?php echo v3_url('/news/'.$m['unique_name']); ?>">
					<span class="v3-news-img<?php if(!$mi) echo ' v3-dir-noimg'; ?>"><?php echo $mi ? '<img src="'.v3_e($mi).'" alt="" loading="lazy" />' : v3_icon('news', 30); ?></span>
					<span class="v3-news-body"><strong><?php echo v3_excerpt($m['bg_article_title'], 90); ?></strong><?php if(!$is_help) echo '<small>', v3_news_date($m['added_date']), '</small>'; ?></span>
				</a>
				<?php } ?>
			</div>
		</section>
		<?php } ?>
	</article>
	<?php
	v3_footer();
	return;
}

/* =========================== list =========================== */
$tab  = (isset($_GET['tab']) && $_GET['tab'] === 'help') ? 'help' : 'news';
$q    = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
if($q !== '' && !preg_match('/^[a-zA-Z\p{Cyrillic}\d\s\-]{2,60}$/u', $q)) $q = '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per  = 12;
$where = 'active = "1" AND category_id = "'.($tab == 'help' ? 1 : 2).'"';
if($q !== ''){ $e = mysql_real_escape_string($q); $where .= ' AND (bg_article_title LIKE "%'.$e.'%" OR bg_meta_description LIKE "%'.$e.'%" OR bg_tags LIKE "%'.$e.'%")'; }
$r = mysql_query('SELECT COUNT(*) FROM articles WHERE '.$where); $x = $r ? mysql_fetch_row($r) : array(0); $total = (int)$x[0];
$pages = max(1, (int)ceil($total / $per)); if($page > $pages) $page = $pages;
$list = v3_news_list('SELECT unique_name, bg_article_title, bg_meta_description, small_image, added_date FROM articles WHERE '.$where.' ORDER BY added_date DESC LIMIT '.(($page - 1) * $per).', '.$per);

function v3_news_link($change){
	$p = array();
	foreach(array('tab','q') as $k) if(!empty($_GET[$k])) $p[$k] = $_GET[$k];
	foreach($change as $k => $v){ if($v === null || $v === '') unset($p[$k]); else $p[$k] = $v; }
	if(!empty($GLOBALS['V3_PREVIEW'])) $p['preview'] = 'v3';
	return v3_url('/news/'.($p ? '?'.http_build_query($p) : ''));
}
$title = $tab == 'help' ? 'Полезно за бизнеса' : 'Новини';
$meta  = '<title>'.$title.($page > 1 ? ' – страница '.$page : '').' | Espravki.com</title>';
$meta .= '<meta name="description" content="'.($tab == 'help' ? 'Съвети как да представиш бизнеса си онлайн, условия и помощ за Espravki.com.' : 'Новини и истории за малкия и средния бизнес в България от Espravki.com.').'" />';
$meta .= ($V3_PREVIEW || $q !== '' || $page > 1 || $tab == 'help') ? '<meta name="robots" content="noindex, follow" />' : '<meta name="robots" content="index, follow" />'.seo_canonical_tags('/news/');
v3_head($meta);
v3_header();
?>
<section class="v3-dir-hero">
	<div class="v3-wrap">
		<nav class="v3-crumbs" aria-label="Път"><a href="<?php echo v3_url('/'); ?>">Начало</a> <span>/</span> <span><?php echo $title; ?></span></nav>
		<h1 class="v3-h2"><?php echo $title; ?></h1>
		<div class="v3-news-top">
			<nav class="v3-tabs-inline" aria-label="Раздели">
				<a href="<?php echo v3_news_link(array('tab' => null, 'page' => null, 'q' => null)); ?>"<?php if($tab == 'news') echo ' class="is-on" aria-current="page"'; ?>>Новини</a>
				<a href="<?php echo v3_news_link(array('tab' => 'help', 'page' => null, 'q' => null)); ?>"<?php if($tab == 'help') echo ' class="is-on" aria-current="page"'; ?>>Полезно и условия</a>
			</nav>
			<form class="v3-field v3-news-search" action="<?php echo v3_url('/news/'); ?>" method="get" role="search">
				<?php echo v3_icon('search'); ?><input type="search" name="q" value="<?php echo v3_e($q); ?>" placeholder="Търси в статиите" aria-label="Търси в статиите" />
				<?php if($tab == 'help') echo '<input type="hidden" name="tab" value="help" />'; if($V3_PREVIEW) echo '<input type="hidden" name="preview" value="v3" />'; ?>
			</form>
		</div>
	</div>
</section>
<div class="v3-wrap v3-news-wrap">
	<?php if($list){ ?>
	<div class="v3-news-grid v3-news-grid-big">
		<?php foreach($list as $i => $n){ $im = v3_news_img($n['small_image']); ?>
		<a class="v3-news-card v3-card<?php if($i == 0 && $page == 1 && $q === '') echo ' v3-news-lead'; ?>" href="<?php echo v3_url('/news/'.$n['unique_name']); ?>">
			<span class="v3-news-img<?php if(!$im) echo ' v3-dir-noimg'; ?>"><?php echo $im ? '<img src="'.v3_e($im).'" alt="" loading="lazy" />' : v3_icon('news', 34); ?></span>
			<span class="v3-news-body">
				<?php if($tab == 'news') echo '<small>', v3_news_date($n['added_date']), '</small>'; ?>
				<strong><?php echo v3_t($n['bg_article_title']); ?></strong>
				<span class="v3-news-desc"><?php echo v3_excerpt($n['bg_meta_description'], 150); ?></span>
			</span>
		</a>
		<?php } ?>
	</div>
	<?php }else{ ?>
	<div class="v3-card v3-empty"><h2 class="v3-h3">Няма намерени статии.</h2><p class="v3-muted"><a href="<?php echo v3_news_link(array('q' => null, 'page' => null)); ?>">Виж всички</a></p></div>
	<?php } ?>
	<?php if($pages > 1){ ?>
	<nav class="v3-pager" aria-label="Страници">
		<?php for($i = 1; $i <= $pages; $i++) echo '<a href="', v3_news_link(array('page' => $i > 1 ? $i : null)), '"', ($i == $page ? ' class="is-on" aria-current="page"' : ''), '>', $i, '</a>'; ?>
	</nav>
	<?php } ?>
</div>
<?php v3_footer(); ?>
