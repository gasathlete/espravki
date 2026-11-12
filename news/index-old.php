<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require '../config.php';
require '../connect.php';
require '../project_functions.php';
require '../translation/lang.php';
include '../modules/geoip.php';


/*
if(!empty($_SESSION['proxy'])){
header("Location: https://www.yahoo.com/");
exit(0);
}
*/
//как-да-кандидатстваме-за-отпускане-на-безвъзмездна-финансова-помощ-по-процедура-за-подбор-на-проекти-подкрепа-на-микро-и-малки-предприятия-за-преодоляване-на-икономическите-последствия-от-пандемията-covid-19
$url=htmlentities($_SERVER['REQUEST_URI'],ENT_QUOTES);
//$url = strtok($url, '?');
if(isset($_GET['fbclid'])) $url = substr($url, 0, strpos($url, "?fbclid"));

$parts = explode('/', rtrim($url, '/'));
//var_dump($parts);
//exit();

$articlelink='';
//$articlelink=$parts[2];

if(!empty($parts[2])){
if(!preg_match('/^[A-Za-z\p{Cyrillic}\0-9-]+$/', $parts[2])){
header('Location: ../index.php');
exit(0);
	}else $articlelink=$parts[2]; 
}else{
header('Location: ../index.php');
exit(0);
}


function echo_meta($articlelink){
	global $lang;
	if(!empty($articlelink) && $articlelink=='select-plan'){
	echo '<title>',$lang['meta_news']['title'],' ',ShortDomainName,'</title>';
	echo '<meta name="description" content="',$lang['meta_news']['description'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_news']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/interface/pageslogo.png">';
	echo '<meta property="og:title" content="',$lang['meta_news']['title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/interface/pageslogo.png" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['meta_news']['description'],'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}else{
	$query='select bg_article_title, bg_meta_description,keywords, small_image from articles where unique_name="'.mysql_real_escape_string($articlelink).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($result) > 0){
	$row=mysql_fetch_row($result);
	if(!empty($row[3])) $image = '<meta itemprop="image" content="'.WebSite.'/images/articles/'.$row[3].'"/>';
	else $image = '<meta itemprop="image" content="'.WebSite.'/images/site_screen.jpg"/>';
	
	if(!empty($row[3])) $image2 = '<meta property="og:image" content="'.WebSite.'/images/articles/'.$row[3].'" />';
	else $image2 = '<meta property="og:image" content="'.WebSite.'/images/site_screen.jpg" />';
	
	echo '<title>',$row[0],'</title>';
	echo '<meta name="description" content="',$row[1],'" />';
	echo '<meta name="keywords" content="',$row[2],'" />';
	echo $image;
	echo '<meta property="og:title" content="',$row[0],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'/news/'.$articlelink.'" />';
	echo $image2;
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$row[1],'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}else{
	echo '<title>',$lang['no_results'],'</title>';
	echo '<meta name="description" content="',$lang['no_results'],'" />';
	echo '<meta name="keywords" content="',$lang['no_results'],'" />';
		}
	
	}
}

function echo_single_new($articlelink){
global $lang;
$query='SELECT * FROM articles WHERE unique_name="'.mysql_real_escape_string($articlelink).'" and active="1"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_assoc($result);
$_SESSION['article_id']=$row['id'];
echo '<h1>',$row['bg_article_title'],'</h1>
<div class="artcontent">';
if(!empty($row['small_image'])){
echo '<img class="lead" alt="" src="',WebSite,'/images/articles/',$row['small_image'],'" itemprop="image" />';
}

echo htmlspecialchars_decode($row['bg_short_description']),'</div>';

	}else echo '<h1>',$lang['article_not_found'],'</h1>';

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--html xmlns="http://www.w3.org/1999/xhtml"-->
<html itemscope itemtype="http://schema.org/LocalBusiness" lang="bg">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<?php echo echo_meta($articlelink);?>
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<link rel="icon" type="image/png" href="<?php echo WebSite;?>/images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="<?php echo WebSite;?>/css/style.css" />
<script type="text/javascript" src="<?php echo WebSite;?>/js/jalerts/jquery.js"></script>
<script src="<?php echo WebSite;?>/js/jquery-1.7.1.min.js"></script>
<script src="<?php echo WebSite;?>/js/listing.js"></script>
<script src="<?php echo WebSite;?>/js/slider/jquery.bxslider.min.js"></script>
<script>
$("document").ready(function(){
if($('.bxslider').length){
jQuery('.bxslider').bxSlider({
    slideWidth: 180,
    minSlides: 2,
    maxSlides: 3,
	moveSlides: 1,
    slideMargin: 15
  });
}
});
</script>
</head>
<body onload="img_resize();">
<?php
include '../modules/header.php';
echo '<div id="content">
<div id="menu"><div id="nav">',new_menu(),'</div></div>
<div id="wrapper" class="products">
<div class="breadcrumb"><a href="',WebSite,'">',$lang['home'],'</a>';
if(!empty($referer)) echo ' » <a href="',$referer,'">',$lang['go_back'],'</a>';

echo '</div>';
if(!empty($articlelink) && $articlelink=='select-plan'){
echo '<div id="left_column" class="alone">
<div id="plan">
<h1>',$lang['plan_h1'],'</h1>
<div class="planhead"><span class="first opt_label">',$lang['plan_includes'],'</span>
<span class="plan1"><label>',$lang['free_plan'],'</label><p class="life">',free_plan,' ',current_currency,' / ',$lang['lifetime'],'</p>
<span class="addplan"><a href="',WebSite,'/redirector.php?selected_plan=1">',$lang['select_plan'],'</a></span></span>
<span class="plan2"><label>',$lang['paid_plan'],'</label><p class="life">',premiumtax,' ',current_currency,' / ',$lang['lifetime'],'</p>
<span class="addplan"><a href="',WebSite,'/redirector.php?selected_plan=2">',$lang['select_plan'],'</a></span></span>
</div>

<div class="underfirst">
<span class="f">',$lang['company_logo2'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>

<div class="underfirst">
<span class="f">',$lang['company_contacts'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>

<div class="underfirst">
<span class="f">',$lang['website'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>

<div class="underfirst">
<span class="f">',$lang['business_description'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>
<div class="underfirst">
<span class="f">',$lang['user_may_contact'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>

<div class="underfirst">
<span class="f">',$lang['profile_visits_statistics'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>
<div class="underfirst">
<span class="f">',$lang['site_visits_statistics'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>';
/*
<div class="underfirst">
<span class="f">',$lang['backlink_requested'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
</div>
*/
echo '
<div class="underfirst">
<span class="f">',$lang['google_map'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>

<div class="underfirst">
<span class="f">',$lang['before_free'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>

<div class="underfirst seo_tools">',$lang['seo_tools'],'</div>

<div class="underfirst">
<span class="f">',$lang['website_link'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>


<div class="underfirst">
<span class="f">',$lang['company_images'],'</span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>

<div class="underfirst">
<span class="f">',$lang['business_can_publish'],'</span><span class="m mt13">20</span>
<span class="m mt13">50</span>
</div>

<div class="underfirst">
<span class="f">',$lang['social_link'],'</span><span class="m h"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m h"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>';
/*
<div class="underfirst">
<span class="f">',$lang['google_review'],'<img class="aa" alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/info.jpg"/>
<span class="info">',$lang['info0'],'</span></span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>';*/
/*
<div class="underfirst">
<span class="f">',$lang['google_participate'],'<img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/info.jpg"/>
<span class="info">',$lang['info1'],'</span></span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>
*/
echo '<div class="underfirst">
<span class="f">',$lang['pr_article'],'<img class="aa" alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/info.jpg"/>
<span class="info">',$lang['pr_article_info'],'</span></span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>';

echo '
<div class="underfirst">
<span class="f">',$lang['sell_business_listing'],'<img class="aa" alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/info.jpg"/>
<span class="info">',$lang['info2'],'</span></span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>';

/*<div class="underfirst">
<span class="f">',$lang['shown_in_shoppingbul'],'<img class="aa" alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/info.jpg"/>
<span class="info">',$lang['info3'],'</span></span><span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/no.png"/></span>
<span class="m"><img alt="',$lang['plan_includes'],'" src="',WebSite,'/images/interface/yes.png"/></span>
</div>';
*/

echo '</div>';

$query='select unique_name, bg_article_title, bg_meta_description,small_image from articles where id IN("15","14","13") order by id asc';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
echo '<div class="gadvert"><div class="drop_cap topper"><h2>',$lang['b_help'],'</h2></div>';
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);	
	echo '<div class="grid_4">
<div class="drop_cap"><img src="../../images/articles/'.$row[3].'"/></div>
<div class="page1_block_title">',$row[1],'</div><span class="newstxt">',$row[2],'</span>
<a class="btn" href="',WebSite,'/news/',$row[0],'">Прочети</a>
</div>';
	
}
echo '</div>';


echo '</div>';
}else{
echo '<div id="left_column">';
echo_single_new($articlelink);
latest_products();
echo '</div>
<div id="right_column">';
require '../modules/banners.php';
echo '</div>';
}
echo '</div>';
include '../modules/footer.php';?>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55fa5259900c6b50" async="async"></script>
</body>
</html>