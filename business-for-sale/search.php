<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require '../config.php';
require '../project_functions.php';
require '../translation/'.$_SESSION['lang'].'_lang.php';


error_reporting('E_ALL ^ E_NOTICE');
if(!isset($_SESSION['search']) || empty($_SESSION['search'])) $_SESSION['search']=array();


//$pattern = '/[^\w_]+/u';
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";

if(!empty($_GET['search'])){
$_GET['search'] = trim(preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll} \-\w]/u', ' ', $_GET['search']));
$_GET['search'] = str_replace(array('"',"'",')','('),' ',$_GET['search']);
}

if(!empty($_GET['search_city'])){
$_GET['search_city'] = trim(preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll} \-\w]/u', ' ', $_GET['search_city']));
$_GET['search_city'] = str_replace(array('"',"'",')','('),' ',$_GET['search_city']);
}


if(!empty($_GET['category'])){
$_GET['category'] = trim(preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll} \-\w]/u', ' ', $_GET['category']));
$_GET['category'] = str_replace(array('"',"'",')','('),' ',$_GET['category']);
}

if((empty($_GET['search']) || !preg_match($pattern, $_GET['search'])) && (empty($_GET['category']) && empty($_GET['search_city']))){
header('Location: ../index.php');
exit(0);
}else{
$search = $_GET['search'];
}

$referer = '';
if(!empty($_SERVER['HTTP_REFERER'])){
$referer = $_SERVER['HTTP_REFERER'];
}
$website=WebSite.'/';


//echo ($referer === $website) ? 'true' : 'false';
if($referer === $website) $referer='';

$categoryname = '';
if(isset($_GET['category']) && !empty($_GET['category'])){
if(preg_match($pattern, $_GET['category'])) $categoryname = str_replace('-',' ',$_GET['category']);
else{
header('Location: ../search/');
exit(0);
}
}


$city = '';
if(!empty($_GET['search_city'])) $city = $_GET['search_city'];


function echo_meta($search){
	global $lang;
	if(!empty($search)){
	$keyword1='';$keyword2='';$desc1=$splitsearch[0];
	$title = $search;
	$category = str_replace('-',' ',$_GET['category']);
	$description = $lang['meta_search']['description'].' '.$search.'. '.sprintf($lang['meta_search']['description51'], $search).'.'.$lang['meta_search']['description31'].' '.$search;
	
	if(!empty($_GET['category'])){
	$title = $category.'. '.$search;
	$description = $lang['meta_search']['description'].' '.$category.'. '.sprintf($lang['meta_search']['description51'], $search).'.'.$lang['meta_search']['description31'].' '.$search;
	}
	if(!empty($_GET['search_city'])){
	$title = $title.' '.$lang['in'].' '.$_GET['search_city'];
	$description = $lang['meta_search']['description'].' '.$category.' '.$lang['in'].' '.$_GET['search_city'].'. '.sprintf($lang['meta_search']['description51'], $search.' '.$lang['in'].' '.$_GET['search_city']).'.'.$lang['meta_search']['description31'].' '.$search;
	}
	echo '<title>',$title,'</title>';
	echo '<meta name="description" content="',sprintf($lang['meta_search']['description41'], $search),'. ',$description,'" />';
	echo '<meta name="keywords" content="',$lang['meta_search']['keywords'],' ',$keyword1,'',$keyword2,'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/interface/pageslogo.png">';
	echo '<meta property="og:title" content="',$title,'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/interface/pageslogo.png" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',sprintf($lang['meta_search']['description41'], $search),'. ',$description,'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}
	elseif(!empty($_GET['search_city'])){
	$title = sprintf($lang['bs2'], $_GET['search_city'], $_GET['search_city']);
	$description = sprintf($lang['bs_desc2'], $_GET['search_city'], $_GET['search_city']);
	
	echo '<title>',$title,'</title>';
	echo '<meta name="description" content="',$description,'" />';
	echo '<meta name="keywords" content="',$lang['meta_search']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/interface/pageslogo.png">';
	echo '<meta property="og:title" content="',$title,'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/interface/pageslogo.png" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$description,'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}
	
	else{
	echo '<title>',$lang['bs'],'</title>';
	echo '<meta name="description" content="',$lang['bs_desc'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_search']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/interface/pageslogo.png">';
	echo '<meta property="og:title" content="',$lang['bs'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/interface/pageslogo.png" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['bs_desc'],'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}
}




?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<?php echo echo_meta($search);?>

<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="author" content="Web-site-maker.eu">
<link rel="icon" type="image/png" href="<?php echo WebSite;?>/favicon.ico" />
<link href="<?php echo WebSite;?>/assets/css/style.css" rel="stylesheet" />
<link href="<?php echo WebSite;?>/assets/css/buttons.css" rel="stylesheet" />
<link id="theme" href="<?php echo WebSite;?>/assets/color-skins/color1.css"  rel="stylesheet"/>

</head>
<body>
<?php
include WebHome.'/modules/header.php';
if(empty($aid)){
?>
<section class="sptb pb-0">
<?php
if(empty($_SESSION['latitude'])) $_SESSION['latitude'] = '42.698334';
if(empty($_SESSION['longitude'])) $_SESSION['longitude'] = '23.319941';

$query='SELECT a.id as aid,supplier_name,link_name,meta_description,site,a.added_date,small_image,town,area,postcode,vip,longitude,latitude,company_address,company_phones, opening, closing, pc.url, bg_category, a.business_type, business_price, selected_plan,  
(
   6371 *
   acos(cos(radians('.$_SESSION['latitude'].')) * 
   cos(radians(latitude)) * 
   cos(radians(longitude) - 
   radians('.$_SESSION['longitude'].')) + 
   sin(radians('.$_SESSION['latitude'].')) * 
   sin(radians(latitude )))
) AS distance 
FROM adverts a,products_categories pc, adverts_to_product_categories atpc 
WHERE a.id=atpc.advert_id and a.sellbusiness ="1" and atpc.category_id=pc.id and a.active="1" ';

if(isset($_GET['category']) && !empty($_GET['category'])) $categorylink = $_GET['category'];
if(!empty($categorylink)){
$query.= ' and pc.url="'.mysql_real_escape_string($categorylink).'" ';
}
if(isset($search) && !empty($search)){
$query.= ' and ( REPLACE(supplier_name,"\'","") LIKE "%'.mysql_real_escape_string(str_replace("'","",$search)).'%" 
or REPLACE(bg_category,"\'","") LIKE "%'.mysql_real_escape_string(str_replace("'","",$search)).'%" or 
meta_keywords LIKE "%'.mysql_real_escape_string(str_replace("'","",$search)).'%" or 
meta_description LIKE "%'.mysql_real_escape_string(str_replace("'","",$search)).'%" 
)';

}

if(isset($_GET['search_city'])){
if(!empty($_GET['search_city']) && preg_match("/^[a-zA-Z\p{Cyrillic} \s\-]+$/u", $_GET['search_city'])){
$query.= ' and town = "'.mysql_real_escape_string($_GET['search_city']).'"';
	}else $_GET['search_city'] = '';
}

$query.=' group by a.id ';

if(!isset($_GET['sort'])) $query.= ' order by a.vip DESC, a.added_date DESC, distance ASC';
else{
	if($_GET['sort'] == 'distance') $query.=' order by distance ASC,vip DESC';
	if($_GET['sort'] == 'newest') $query.=' order by a.added_date DESC';
	if($_GET['sort'] == 'vip' || $_GET['sort'] == 'all') $query.=' order by a.vip DESC, distance ASC';
	}
$allquery = $query;
$query.=' limit 12';
//echo $query;
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);

$allresult=mysql_query($allquery) or die(send_error($allquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$allnum_rows=mysql_num_rows($allresult);

$markers = '';
$listings = '';
$head = '';
$cities = array();
$advertcities = array();
$distance = array();
$locations = array();
$category_array = array();

if($allnum_rows > 0){
for($b=0;$b<$allnum_rows;$b++){
$allrow=mysql_fetch_assoc($allresult);
if(!in_array($allrow['town'],$advertcities)) array_push($advertcities,$allrow['town']);
if(!array_key_exists($allrow['url'],$category_array)) $category_array[$allrow['url']] = $allrow['bg_category'];

//locations
//a.id as aid,supplier_name,link_name,meta_description,site,a.added_date,small_image,town,area,postcode,vip,longitude,latitude,company_address,company_phones, opening, closing, pc.url, bg_category,  
if($allrow['business_type'] != 2) $locations[]= $allrow['latitude'].','.$allrow['longitude'].','.$allrow['supplier_name'].','.$allrow['url'].','.$allrow['link_name'].','.$allrow['aid'].','.$allrow['company_phones'].','.$allrow['company_address'].','.$allrow['town'].','.$allrow['postcode'].','.$allrow['area'];

	}
}
//var_dump($category_array);
if($num_rows > 0){
$search_city = '';
if(!empty($_GET['search_city'])) $search_city = ' - '.$_GET['search_city'];

if(!empty($categoryname)){
$head= '<h1 class="ib mobw100 mt-3">'.$lang['showing_r'].' '.$categoryname.''.$search_city.'</h1>';
}else{
if(!empty($search)) $head= '<h1 class="ib mobw100 mt-3">'.$lang['showing_r'].' '.$search.''.$search_city.'</h1>';
elseif(!empty($_GET['category'])) $head= '<h1 class="ib mobw100 mt-3">'.$lang['showing_r'].' '.str_replace('-',' ',$_GET['category']).' '.$search_city.'</h1>';
else $head= '<h1 class="ib mobw100 mt-3">'.$lang['n_ads'];

if(!empty($_GET['search_city'])) $head.= ' '.$lang['t_sel'].' '.$_GET['search_city'];
$head.='</h1>';
}
//$head.='<div class="ib w48 mobw100 tar mt-3"><span class="showMe"><button class="btn btn-secondary" id="showMe"><i class="fa fa-map-marker" aria-hidden="true"></i> '.$lang['show_me'].'</button></span><span id="cityspan"></span><span class="iconstool"></span></div>';

for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);


$marker='';
	if(empty($row['small_image'])){
	$image = '/products/no_product_image.jpg';
	}else $image = '/adverts/'.$row['small_image'];
	
$your_date = strtotime($row['added_date']);
$datediff = time() - $your_date;
$new = round($datediff / (60 * 60 * 24));



$markers.='
<div class="axgmap-img" data-latlng="'.$row['latitude'].','.$row['longitude'].'" data-marker-image="'.WebSite.'/images/icons/markermap.png" data-title="'.$row['supplier_name'].'">
<h4>'.$row['supplier_name'].'</h4>
<img src="'.WebSite.'/images/'.$image.'" alt="'.$row['supplier_name'].'" class="w-150 h100 mb-3 mt-2" />';

if(!empty($row['company_phones'])){
$phones = explode(";", $row['company_phones']);
$markers.= '<h6 class="text-dark font-weight-normal mb-2 mt-2 item-card2-desc tal"><i class="fa fa-phone mr-1"></i> <a class="phone_dialer" data-aid="'.$row['aid'].'" href="tel:'.$phones[0].'"> '.$phones[0].'</a></h6>';
}
$markers.='
<h6 class="text-dark font-weight-normal mb-2 mt-2 item-card2-desc tal"><i class="fa fa-map mr-1"></i> '.$row['town'].', '.$row['company_address'].'</h6>
<a target="_blank" href="'.WebSite.'/business-for-sale/'.$row['url'].'/'.$row['link_name'].'" class="btn btn-sm btn-info mt-3 mb-3">'.$lang['view_profile'].'</a>
<a target="_blank" href="https://www.google.com/maps/place/'.$row['latitude'].','.$row['longitude'].'/" class="btn btn-sm btn-secondary">'.$lang['show_loc'].'</a>
</div>';

if(!in_array(number_format($row['distance'],2),$distance)) array_push($distance,number_format($row['distance'],2));

$listings.='
<div id="a'.$row['aid'].'" class="card overflow-hidden snglad" data-id="'.($i+1).'">';
if($new <= 7){
$listings.= '<div class="ribbon ribbon-top-left text-danger"><span class="bg-danger">'.$lang['new'].'</span></div>';
}else{
if($row['vip'] > 0) $listings.='<div class="power-ribbon power-ribbon-top-left text-warning"><span class="bg-warning"><i title="'.$lang['reccom'].'" class="fa fa-bolt"></i></span></div>';
}
$listings.='
<div class="d-md-flex">
	<div class="item-card9-img">
		<div class="item-card9-imgs pt-3">
			<a href="'.WebSite.'/business-for-sale/'.$row['url'].'/'.$row['link_name'].'"></a>
			<img src="'.WebSite.'/images/'.$image.'" alt="'.$row['supplier_name'].'" class="cover-image"/>
		</div>
		
<div class="item-cardreview-absolute bg-light border z99">
		<div class="item-card9-cost ffo ib mt-2 text-dark fs-18">
			 <label>'.$lang['prd_price'].'</label>: ';
			 setlocale(LC_MONETARY, 'bg_BG.UTF-8');
			 $listings.=money_format('%.2n',$row['business_price']);
			$listings.='</div>
		</div>	
	</div>
	
	<div class="card border-0 mb-0">
		<div class="card-body h-100">
			<div class="item-card9">
				<a target="_blank" href="'.WebSite.'/business-for-sale/'.$row['url'].'/'.$row['link_name'].'" class="text-dark">
				<h4 class="font-weight-semibold mt-1 mb-1 ib w48">'.$row['supplier_name'].' </h4></a>
				<div class="ib w48 dist pt-2 tar"><i class="fa fa-compass" aria-hidden="true"></i> 
				'.number_format($row['distance'],2).' km '.$lang['dist2'].'
				<div class="text-dark font-weight-normal mb-0 mt-0 item-card2-desc">';
				if($row['business_type'] != 2) $listings.='<a target="_blank" href="https://www.google.com/maps/place/'.$row['latitude'].','.$row['longitude'].'/"><i class="fa fa-map-marker"></i>  '.$lang['show_loc'].'</a>';
					else $listings.=$lang['select_type'][2];
					
					$listings.='</div>
				</div>
			
				<div class="mt-2 mb-2">
				<p class="desc">'.strip_tags(htmlspecialchars_decode($row['meta_description'])).'</p>
				</div>
				<div class="mt-2 mb-2">
					<i class="fa fa-map-marker mr-1"></i> '.$row['town'].' '.$row['postcode'].' '.$row['company_address'].'
				</div>
			
			</div>
		</div>
		<div class="card-footer pt-2 pb-2">
			<div class="item-card9-footer mt-1 pb-1 d-sm-flex">
				<div class="item-card9-cost ffo ib mt-1 ml-2 w48 fs-18">
			<a class="text-dark" target="_blank" href="'.WebSite.'/business-for-sale/'.$row['url'].'/">'.$row['bg_category'].'</a>
				</div>
				<div class="ml-auto ib w48 tar mt-3 mt-sm-0">
				<div class="text-dark ib mt-1 ml-2 item-card2-desc">
				<a target="_blank" href="'.WebSite.'/business-for-sale/'.$row['url'].'/'.$row['link_name'].'" class="btn btn-sm btn-secondary">'.$lang['view_profile'].'</a>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>';
	}
}
//var_dump($locations);
if(!empty($distance)) $maxdist = intval(max($distance));
else $maxdist = 0;
$countcities = count($advertcities);
if($countcities > 1) $zoom = 8;
elseif($maxdist > 100) $zoom = 7;
elseif($maxdist > 50) $zoom = 8;
elseif($maxdist > 10) $zoom = 10;
else $zoom = 14;

//$distance = distance($_SESSION['latitude'], $_SESSION['longitude'], 29.46786, -98.53506, "K");
//var_dump($locations) ;
if($num_rows > 0){
 if(!empty($locations)) echo '<div class="axgmap" id="googlemap"></div>';
}else{
echo '<script async src="https://cse.google.com/cse.js?cx=partner-pub-8616690735725377:8351055997"></script><div class="gcse-searchresults-only"></div>';
}
?>

</section>
<div class="bg-white border-bottom">
			<div class="pt-5 pb-5">
				<div class="header-text1 mb-0">
					<div class="container">
						
<?php
$cities = echo_cities();

//echo $_SESSION['city'],'-end';
	echo '
		<div class="row">
	<div class="col-xl-10 col-lg-12 col-md-12 d-block mx-auto">
		<div class="pb-3 pt-4 px-4 bg-gradient-secondary br-4 search-background">
		<form name="search_advert" id="search_advert_form" action="'.WebSite.'/business-for-sale/search.php" method="get">
			<div class="form row row-sm homesearch">
				<div class="form-group col-xl-4 col-lg-3 col-md-12 mb-0 pr">
				<input id="srctxt_for_sale" class="form-control input-lg" type="text" name="search" placeholder="',$lang['w_s'],'" value="',$search,'"/>
				<input id="exact_match" type="hidden" name="exact_match" value="0" />
				<input id="btn_search" type="hidden" name="btn_search" value="0" />
				<input type="hidden" name="sort" id="sort_ch2" value="vip"/>
				<span><i class="fa fa-search" aria-hidden="true"></i><i class="fa fa-times" aria-hidden="true"></i></span>
				<div id="srctxthldr" class="srctxthldr"></div>
				</div>
				<div class="form-group col-xl-3 col-lg-3 col-md-12 mb-0">
					<input type="text" class="form-control input-lg location-input" name="search_city" id="search_city" value="'.$city.'" placeholder=" '.$lang['where_s'].'" />
					<span><img title="',$lang['show_me'],'" id="showMe" src="../../assets/images/svgs/gps.svg" class="location-gps" alt="map"></span>
				</div>
								<div class="form-group col-xl-3 col-lg-3 col-md-12 select2-lg mb-0">
									',echo_categories_in_search_for_sale($categoryname),'
								</div>
								<div class="col-xl-2 col-lg-3 col-md-12 mb-0">
									<button type="submit" name="search_ar" id="search_ar" value="1" class="btn btn-lg btn-block btn-secondary">'.$lang['search'].'</button>
								</div>
							</div>
							</form>
						</div>
					</div>
				</div>';
				?>
						
						
					</div>
				</div><!-- /header-text -->
			</div>
		</div>
<section class="sptb mt-1 pt-1">
			<div class="container">
				<div class="row">
				
<div id="accordion" class="col-xl-3 col-lg-3 col-md-12">
<?php
if(count($advertcities) > 0){
setlocale(LC_ALL,$_SESSION['lang'].'_'.strtoupper($_SESSION['lang']).'.UTF-8');
asort($advertcities,SORT_LOCALE_STRING);
?>
<div class="card mt-1 mb-2">
<div class="card-header pr panel-title">
	<h3 class="card-title to_scroll"><?php echo $lang['f_cities'];?></h3>
<a class="chev accordion-toggle" aria-expanded="false" aria-controls="collapseOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"></a>
</div>
<div id="collapseOne" class="card-body collapse show">
	<div class="smcontainer">
		<div class="filter-product-checkboxs">
<?php
$cchecked = '';
if(empty($_GET['search_city'])) $cchecked = 'checked';

echo '<label class="custom-control custom-radio mb-3 traderlbl">
	<input ',$cchecked,' type="radio" class="custom-control-input cities" name="city" value=""/>
	<span class="custom-control-label">',$lang['all'],'</span>
</label>';
foreach($advertcities as $singlcity){
$checkedc = '';
if($singlcity == $city) $checkedc = 'checked="checked"';
echo '<label class="custom-control custom-radio mb-2 mr-4">
	<input ',$checkedc,' type="radio" class="custom-control-input cities" name="city" value="',$singlcity,'" />
	<span class="custom-control-label">
		<a class="text-dark">',$singlcity,'</a>
	</span>
</label>';
}
?>
		</div>
	</div>
</div>
</div>
<?php
}

if(count($category_array) > 0){
setlocale(LC_ALL,$_SESSION['lang'].'_'.strtoupper($_SESSION['lang']).'.UTF-8');
asort($category_array,SORT_LOCALE_STRING);

$achecked = '';
if(empty($categorylink)) $achecked = 'checked';

echo '
<div class="card mt-1 mb-2">
<div class="card-header pr panel-title">
	<h3 class="card-title">',$lang['f_cat'],'</h3>
<a class="chev accordion-toggle" aria-expanded="false" aria-controls="collapsetwo" data-toggle="collapse" data-parent="#accordion" href="#collapsetwo"></a>
</div>
<div id="collapsetwo" class="card-body collapse show">
	<div class="smcontainer">
		<div class="filter-product-checkboxs">
		<label class="custom-control custom-radio mb-3 traderlbl">
			<input ',$achecked,' type="radio" class="custom-control-input cats" name="cat" value="0"/>
			<span class="custom-control-label">',$lang['all'],'</span>
		</label>';
foreach($category_array as $catkey=>$cat){
$checkedcat = '';
if($catkey == $categorylink) $checkedcat = 'checked="checked"';
echo '<label class="custom-control custom-radio mb-2 mr-4">
	<input ',$checkedcat,' type="radio" class="custom-control-input cats" name="cat" value="',$catkey,'" />
	<span class="custom-control-label">
		<a class="text-dark">',$cat,'</a>
	</span>
</label>';
}

echo '</div></div></div></div>';
}

include '../modules/banners.php';
?>
</div>
				
					<div class="col-xl-9 col-lg-9 col-md-12">
								<div class="row"><?php echo $head;?></div>

						<!--Add lists-->
						<div class=" mb-lg-0">
							<div class="">
								<div class="item2-gl ">
									<div class="">
<?php
echo '
<div class="bg-white p-5 item2-gl-nav d-flex">
<h6 class="mb-0 mt-3">',$lang['showing'],' <span id="cr">',$num_rows,'</span> / ',$allnum_rows,' ',$lang['meta_search']['results'],'</h6>
<ul class="nav item2-gl-menu ml-auto mt-1">
<li></li>
</ul>
<div class="d-sm-flex">
<label class="mr-2 mt-2 mb-sm-1 sort-label">',$lang['sort'],':</label>
<div class="selectgroup ffol">

<label class="selectgroup-item mb-md-0">
<input type="radio" ';if(isset($_GET['sort']) && $_GET['sort'] == 'distance') echo 'checked'; echo ' name="sort" value="distance" class="selectgroup-input sort_ch2">
<span class="selectgroup-button">',$lang['by_distance'],'</span>
</label>
<label class="selectgroup-item mb-md-0">
<input type="radio" ';if(isset($_GET['sort']) && $_GET['sort'] == 'newest') echo 'checked'; echo ' name="sort" value="newest" class="selectgroup-input sort_ch2">
<span class="selectgroup-button">',$lang['newest'],'</span>
</label>
<label class="selectgroup-item mb-0">
<input type="radio" ';if(!isset($_GET['sort']) || $_GET['sort'] == 'vip') echo 'checked'; echo ' name="sort" value="vip" class="selectgroup-input sort_ch2">
<span class="selectgroup-button">',$lang['reccom'],'</span>
</label>
</div>
</div>
</div>';
?>
</div>
									
<div class="tab-content">
<div class="tab-pane active adverts_tab" id="tab-11">
<?php echo $listings;?>
</div>
</div>										

</div>
</div>
</div>
</div>



<?php
if($num_rows > 0 && !empty($locations)) echo '<div class="axgmap" id="googlemap"></div>';
else{
//google adverts
echo '
<script async src="https://cse.google.com/cse.js?cx=partner-pub-8616690735725377:8351055997"></script>
<div class="gcse-searchresults"></div>';
}
?>

</div>
</div>
</section>									
<?php
}

include '../modules/footer.php';
?>	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<?php
if($num_rows > 0 && !empty($locations)){
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo google_api;?>"></script>
<?php
}
?>
<script src="<?php echo WebSite;?>/assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="<?php echo WebSite;?>/assets/js/fullfunctions.js"></script>
<?php
if(isset($_SESSION['btn_search'])) unset($_SESSION['btn_search']);
?>
<script src="<?php echo WebSite;?>/assets/js/jquery-ui.js"></script>
<script>
var keywords = <?php echo json_encode($cities);?>;
//console.log(keywords);
$('#search_city').autocomplete({
//source: [keywords],
	source: keywords,
	minLength: 1,
	select: function(event, ui) {
	event.preventDefault();
	//console.log(ui.item.label);
	$("#search_city").val(ui.item.label);
  }
});

<?php
if($num_rows > 0 && !empty($locations)){
?>
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("<?php echo $lang['u_denied'];?>");
            break;
        case error.POSITION_UNAVAILABLE:
		alert("<?php echo $lang['l_unavailable'];?>");
		break;
        case error.TIMEOUT:
            alert("<?php echo $lang['time_out'];?>"); 
            break;
        case error.UNKNOWN_ERROR:
            alert("<?php echo $lang['unknown'];?>");
            break;
    }
	$('#global-loader').fadeOut();
	$("body").css('overflow-y','auto');
	if(typeof window.orientation !== 'undefined'){
	alert('<?php echo $lang['mobile_user'];?>');
	}else alert('<?php echo $lang['sorry'];?>');
}
function bindInfoWindow(marker, map, infowindow, content) {
        // Allow each marker to have an info window
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(content);
            infowindow.open(map, marker);
        });
    }
	
function initialize(){
//userbrowser detect start

var userbrowser = navigator.sayswho= (function(){
var ua= navigator.userAgent, tem,
M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
if(/trident/i.test(M[1])){
	tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
	return 'IE '+(tem[1] || '');
}
if(M[1]=== 'Chrome'){
	tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
	if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
}
M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
return M.join(' ');
})();
//userbrowser detect end

userbrowser = userbrowser.replace(/\d/g,'');

if(userbrowser.indexOf("Firefox") > -1 || userbrowser.indexOf("Chrome") > -1 || userbrowser.indexOf("IE") > -1){
$("#showMe").show();
}else $("#showMe").hide();


document.getElementById("showMe").addEventListener('click', function (){

if(typeof window.orientation !== 'undefined'){
	tmout = "10000";
	}else tmout = "10000";

//var url = <?php echo json_encode(WebSite);?>;
//var bimage = url + '/images/' + userbrowser.replace(/\s/g,'') +'.jpg';
//$('#bimage').html('<img src="' + bimage + '"/>');
    if(navigator.geolocation){
		$('#global-loader span').html(messages.share_loc);
  $('#global-loader').show();
    navigator.geolocation.getCurrentPosition(function(position) {

	$("body").css('overflow-y','auto');
	//alert(position.coords.latitude);
      var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);

      marker = new google.maps.InfoWindow({
        map: map,
        position: pos,
		zoom:5,
        content: '<div class="uh">'+<?php echo json_encode($lang['you_are_here']); ?>+'</div>',
		
      });
    bounds.extend(pos);
	map.fitBounds(bounds);
    $('#global-loader').fadeOut();
	$('html, body').animate({scrollTop: $('#googlemap').offset().top - 150}, 800);

    }, showError,{timeout: tmout}
	);
  } else {

    // Browser doesn't support Geolocation
	$('#global-loader').fadeOut();
	$("body").css('overflow-y','auto');
	alert('<?php echo $lang['sorry'];?>');
    handleNoGeolocation(false);
  }
});
var map;
var locations = <?php echo json_encode($locations); ?>;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
// Display a map on the page
    map = new google.maps.Map(document.getElementById("googlemap"), mapOptions);

    // Multiple Markers
	var markers = locations;        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow();
    
    // Loop through our array of markers & place each one on the map 
    for( i = 0; i < markers.length; i++ ){
	loc_array = markers[i].split(",");
	var lat = parseFloat(loc_array[0]);
	var lng = parseFloat(loc_array[1]);
        var position = new google.maps.LatLng(lat,lng);
		//alert(loc_array[0]+' - '+loc_array[1]);
		//alert(isNaN(loc_array[0]));
		
		
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
			draggable: false,
			raiseOnDrag: true,
            title: loc_array[0]
        });
		// Info Window Content
//$locations[]= $allrow['latitude'].','.$allrow['longitude'].','.$allrow['aid'].','.$allrow['company_phones'].','.$allrow['company_address'].','.$allrow['town'].','.$allrow['postcode'].','.$allrow['area'];

       content="<h4>"+loc_array[2] + "</h4> - <a class='ac' onclick='move("+ loc_array[5] +");' href='../business-for-sale/"+ loc_array[3] +"/"+ loc_array[4] +"'>" + <?php echo json_encode($lang['view_profile']);?> + "</a><span class='p'><label>" + <?php echo json_encode($lang['phone']);?> + "</label>: " + loc_array[6] + " </span><span class='p'><label>" + <?php echo json_encode($lang['address']);?> + "</label>: " + loc_array[7] + ", " + loc_array[8] + ", " + loc_array[9] + ", " + loc_array[10] + "</span>";
	//content = '';
		bindInfoWindow(marker, map, infoWindow, content);

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }
	//bounds.extend(marker.position); 
    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
  /* var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
       map.fitBounds(bounds);
        google.maps.event.removeListener(boundsListener);
    });*/

}
<?php
}
?>
</script>
<script src="<?php echo WebSite;?>/assets/js/listings.js"></script>

	</body>
</html>