<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require '../config.php';
require '../project_functions.php';

require '../translation/'.$_SESSION['lang'].'_lang.php';

$url=$_SERVER['REQUEST_URI'];
//$url=htmlentities($_SERVER['REQUEST_URI'],ENT_QUOTES);

$parts = explode('/', rtrim($url, '/'));
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";

//var_dump($_POST);
//exit();

if(isset($_SESSION['catlist'])) unset($_SESSION['catlist']);

$category='';
$categoryname='';
$product='';
$categorylink='';
$productlink='';
$productname='';
$category_id='';

$pid = 0;
$categories_array = '';
//echo urldecode($parts[2]);
if(!empty($parts[2])) $categorylink = preg_match($pattern, urldecode($parts[2]));
if(!empty($parts[3])) $product = preg_match($pattern, urldecode($parts[3]));

if(!empty($categorylink)){
$categorylink = urldecode($parts[2]);
$catcheck = 'select id, bg_category, url from shop_products_categories where url = "'.mysql_real_escape_string($categorylink).'"';
$catcheckresult=mysql_query($catcheck) or die(send_error($catcheck,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$catcheck_num_rows=mysql_num_rows($catcheckresult);
if($catcheck_num_rows > 0){
$catcheckrow=mysql_fetch_assoc($catcheckresult);
$category_id = $catcheckrow['id'];
$categoryname = $catcheckrow['bg_category'];
$categorylink = $catcheckrow['url'];
$categories_array = get_subcategories($category_id);

//var_dump($categories_array);
$meta_info= '<title>'.$catcheckrow['bg_category'].'</title>';
$meta_info.= '<meta name="description" content="'.sprintf($lang['shop_meta_desc'], $catcheckrow['bg_category'], $catcheckrow['bg_category']).'" />';
$meta_info.= '<meta name="keywords" content="'.$catcheckrow['bg_category'].'" />';
$meta_info.= '<meta itemprop="image" content="'.WebSite.'/images/site_screen.jpg"/>';
$meta_info.= '<meta property="og:title" content="'.$catcheckrow['bg_category'].'" />';
$meta_info.= '<meta property="og:type" content="product" />';
$meta_info.= '<meta property="og:url" content="https://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'" />';
$meta_info.= '<meta property="og:image" content="'.WebSite.'/images/site_screen.jpg" />';
$meta_info.= '<meta property="og:site_name" content="'.ShortDomainName.'" />';
$meta_info.= '<meta property="og:description" content="'.sprintf($lang['shop_meta_desc'], $catcheckrow['bg_category'], $catcheckrow['bg_category']).'" />';
$meta_info.= '<meta name="robots" content="index, follow" />';
$meta_info.= '<meta name="revisit-after" content="2 days" /> ';
	
}else{
header('Location: '.WebSite.'/products/');
exit(0);
}

}else{
$meta_info= '<title>'.$lang['meta_product']['title'].'</title>';
$meta_info.= '<meta name="description" content="'.$lang['meta_product']['description'].'" />';
$meta_info.= '<meta name="keywords" content="'.$lang['meta_product']['keywords'].'" />';
$meta_info.= '<meta itemprop="image" content="'.WebSite.'/images/site_screen.jpg"/>';
$meta_info.= '<meta property="og:title" content="'.$lang['meta_product']['title'].'" />';
$meta_info.= '<meta property="og:type" content="product" />';
$meta_info.= '<meta property="og:url" content="https://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'" />';
$meta_info.= '<meta property="og:image" content="'.WebSite.'/images/site_screen.jpg" />';
$meta_info.= '<meta property="og:site_name" content="'.ShortDomainName.'" />';
$meta_info.= '<meta property="og:description" content="'.$lang['meta_product']['description'].'" />';
$meta_info.= '<meta name="robots" content="index, follow" />';
$meta_info.= '<meta name="revisit-after" content="2 days" /> ';
//header('Location: '.WebSite);
//exit(0);
}

if(!empty($product)) $productlink = urldecode($parts[3]);

if(isset($_POST['category']) && !empty($_POST['category']) && preg_match($pattern, $_POST['category'])){
 if(intval($_POST['category']) > 0){
$category_id = $_POST['category'];
	}else $categorylink = $_POST['category'];
}






if(!empty($productlink)){
$check = 'select * from products where product_url = "'.mysql_real_escape_string($productlink).'" and deleted="0" and checked_by_admin = "1"';
$checkresult=mysql_query($check) or die(send_error($check,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($checkresult);
if($num_rows > 0){
	$row=mysql_fetch_assoc($checkresult);
	$categoryname = $row['category_name'];
	$categorylink = $row['category_url'];
	$productname=$row['title'];
	$pid = $row['id'];
	$image = WebSite.'/images/site_screen.jpg';
	if(!empty($row['image'])) $image = WebSite.'/images/products/'.$row['image'];
	$meta_info= '<title>'.$row['title'].' - '.$row['category_name'].'</title>';
	$meta_info.= '<meta name="description" content="'.$row['description'].'" />';
	$meta_info.= '<meta name="keywords" content="'.$row['category_name'].'" />';
	$meta_info.= '<meta itemprop="image" content="'.$image.'"/>';
	$meta_info.= '<meta property="og:title" content="'.$row['title'].' - '.$row['category_name'].'" />';
	$meta_info.= '<meta property="og:type" content="product" />';
	$meta_info.= '<meta property="og:url" content="https://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'" />';
	$meta_info.= '<meta property="og:image" content="'.$image.'" />';
	$meta_info.= '<meta property="og:site_name" content="'.ShortDomainName.'" />';
	$meta_info.= '<meta property="og:description" content="'.$row['description'].'" />';
	$meta_info.= '<meta name="robots" content="index, follow" />';
	$meta_info.= '<meta name="revisit-after" content="2 days" /> ';
	}else{
	header('Location: '.WebSite);
	exit(0);
	}
}


/*
elseif((!empty($categorylink) || !empty($category_id)) && empty($productlink)){
if(!empty($category_id)){

$categories_array = get_subcategories($category_id);
var_dump($categories_array);
if(is_array($categories_array) && count($categories_array) > 0) $check = 'select category_name, category_url from products where category_id IN (' . implode(",", $categories_array) . ') group by category_name';
else $check = 'select category_name, category_url from products where category_id = "'.mysql_real_escape_string($category_id).'" group by category_name';
}else{
$check = 'select spc.id, spc.bg_category, spc.url, p.category_name,p.category_url from shop_products_categories spc, products p where 
spc.url = "'.mysql_real_escape_string($categorylink).'" and spc.id = p.category_id group by spc.id ';
}

echo $check;
$checkresult=mysql_query($check) or die(send_error($check,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($checkresult);
if($num_rows > 0){
	$row=mysql_fetch_assoc($checkresult);
	$categoryname = $row['category_name'];
	$categorylink = $row['category_url'];
	//$category_id = $row['id'];
	}else{
	$categories_array = get_subcategories_by_url($categorylink);
	if(empty($categories_array)){
	header('Location: '.WebSite);
	exit(0);
		}
	}
	//var_dump($categories_array);
	
	
}else{

}
*/



if(!empty($categories_array)) $_SESSION['catlist'] = $categories_array;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<?php
echo $meta_info;
?>
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

$product_categories = return_product_categories();
echo '<div class="cover-image sptb-1 bg-background" data-image-src="../../assets/images/banners/shop2.jpg">
				<div class="header-text1 mb-0">
					<div class="container">
						<div class="row">
							<div class="col-xl-8 col-lg-12 col-md-12 d-block mx-auto">
								<div class="text-center text-white ">';
									if(empty($pid)) echo '<h2 class="">',$lang['shop2'],'</h2>';
									else echo '<h2 class="">',$lang['search_shop'],'</h2>';
									//',WebSite,'/products/
								echo '</div>
								<div class="search-background px-4 py-3 mt-4">
								<form name="search_product" id="search_product_form" action="',WebSite,'/products/" method="post">
								<input type="hidden" name="sort" id="sort_ch" value=""/>
								<input type="hidden" name="categoryurl" id="categoryfield" value="'.$categorylink.'"/>';
								if(!empty($categories_array)) echo '<input type="hidden" name="category_list" id="category_list" value="'.$categories_array.'"/>';
								
								echo '<input type="hidden" name="offer_type" id="offer_type" value=""/>
								<input type="hidden" name="make" id="make" value="',@$_POST['make'],'"/>
								<input type="hidden" name="model" id="model" value="',@$_POST['model'],'"/>
								<input type="hidden" name="modification" id="modification" value="',@$_POST['modification'],'"/>
								<div class="form row row-sm">
								<div class="form-group col-xl-8 col-lg-5 col-md-12 mb-0">
									<input type="text" class="form-control input-lg" value="',@$_POST['search_text'],'" name="search_text" id="search_text" placeholder="',$lang['w_s'],'">
								</div>';
//categories
/*
if(count($product_categories) > 0){
echo '<div class="form-group col-xl-4 col-lg-4 select2-lg  col-md-12 mb-0"><select name="products_category" id="products_category" class="form-control select2-show-search  border-bottom-0" data-placeholder="',$lang['ccat'],'">
<optgroup label="',$lang['cats'],'"><option>',$lang['ccat'],'</option>';
foreach($product_categories as $pckey=>$categorynames){
$selected = '';
	if(isset($_POST['products_category']) && ($categorynames['url'] == $_POST['products_category'])) $selected = 'selected="selected"';
	echo '<option '.$selected.' value="'.$categorynames['url'].'">'.$categorynames['name'].'</option>';
	}
echo '</optgroup></select></div>';
}
*/									

										echo '
										<div class="col-xl-4 col-lg-3 col-md-12 mb-0">
											<button id="search_product" name="search_product_btn" value="1" type="submit" class="btn btn-lg btn-block btn-info">',$lang['search'],'</button>
										</div>
									</div>
									</form>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
?>
<section>
		<div class="bg-white border-bottom">
			<div class="container">
				<div class="page-header">
					<h1 class="page-title to_scroll"><?php if(!empty($categoryname)) echo $categoryname;else echo $lang['store'];?></h1>
<?php
echo breadcrumb($categorylink);
?>
					</div>
			</div>
		</div>
</section>

<?php
//single product
if(!empty($pid)){

$advert = 'select * from adverts where id = "'.mysql_real_escape_string($row['advert_id']).'" and active = "1"';
$advertresult=mysql_query($advert) or die(send_error($advert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$advert_num_rows=mysql_num_rows($advertresult);

if($advert_num_rows < 1){
echo '<script>window.location.href = "'.Website.'";</script>';
}else $advertrow = mysql_fetch_assoc($advertresult);

$advert_categories = get_advert_categories_link($advertrow['id']);
//var_dump($advert_categories);
if(count($advert_categories) > 0){
foreach($advert_categories as $category) $advert_category = $category;
}
$views = count_products_visits($pid);
echo '
<section class="sptb">
			<div class="container">
				<div class="row">
					<div class="col-xl-8 col-lg-8 col-md-12">
						<div class="card overflow-hidden">';
							if(!empty($advertrow['vip'])) echo '<div class="ribbon ribbon-top-right text-danger"><span class="bg-danger">featured</span></div>';
							echo '<div class="card-body">
								<div class="item-det mb-4">
									<h1 class="pt">',$row['title'],'</h1>
									<div class=" d-flex">
									<input type="hidden" id="pid" value="'.$pid.'"/>
									<input type="hidden" id="aid" value="'.$advertrow['id'].'"/>
									<input type="hidden" id="pname" value="'.$row['title'].'"/>
										<ul class="d-flex mb-0">
											<li class="mr-5"><a href="../',$row['category_url'],'" class="icons"><i class="icon icon-briefcase text-muted mr-1"></i> ',$row['category_name'],'</a></li>';
											if($advertrow['business_type'] != 2) echo '<li class="mr-5"><a href="#location" class="icons"><i class="icon icon-location-pin text-muted mr-1"></i> ',$advertrow['town'],', ',$advertrow['area'],'</a></li>';
if($advertrow['customer_id'] == $_SESSION['customer']) echo '<li class="mr-5"><a href="#" class="icons"><i class="icon icon-eye text-muted mr-1"></i> ',$views,'</a></li>';
										echo '</ul>
										<div class="rating-stars d-flex mr-5">
											<input type="number" readonly="readonly" class="rating-value star" name="rating-stars-value" value="4">
											<div class="rating-stars-container mr-2">
												<div class="rating-star sm">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm">
													<i class="fa fa-star"></i>
												</div>
											</div> 4.0
										</div>
										
									</div>
								</div>';
								$pimages = get_product_images($pid);
								//var_dump($pimages);
								if(count($pimages) > 0){
								echo '<div class="product-slider tac">
									<div id="carousel" class="carousel slide" data-ride="carousel">';

$your_date = strtotime($row['addeddate']);
$datediff = time() - $your_date;
$new = round($datediff / (60 * 60 * 24));


										if($new <= 7) echo '<div class="arrow-ribbon bg-secondary">',$lang['new'],'</div>';
										echo '<div class="carousel-inner">';
										$imgcount = 0;
										foreach($pimages as $imgkey=>$singleimg){
										$imgactive = '';
										$imgcount++;
										if($imgcount == 1) $imgactive = 'active';
										echo '<div class="carousel-item '.$imgactive.'"><img src="',WebSite,'/images/products/',$singleimg['image'],'" alt="',$row['title'],'"/> </div>';
										}
											
										echo '</div>
										<a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
											<i class="fa fa-angle-left" aria-hidden="true"></i>
										</a>
										<a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
											<i class="fa fa-angle-right" aria-hidden="true"></i>
										</a>
									</div>
									<div class="clearfix">
										<div id="thumbcarousel" class="carousel slide" data-interval="false">
											<div class="carousel-inner">
												<div class="carousel-item active">';
										$imgcount = 0;
										foreach($pimages as $imgkey=>$singleimg){
					
										echo '<div data-target="#carousel" data-slide-to="',$imgcount,'" class="thumb"><img src="',WebSite,'/images/products/',$singleimg['image'],'" alt="',$row['title'],'"/> </div>';
										$imgcount++;
										}
													
												echo '</div>
												<div class="carousel-item ">';
												$imgcount = 0;
										foreach($pimages as $imgkey=>$singleimg){
					
										echo '<div data-target="#carousel" data-slide-to="',$imgcount,'" class="thumb"><img src="',WebSite,'/images/products/',$singleimg['image'],'" alt="',$row['title'],'"/> </div>';
										$imgcount++;
										}
											echo '</div>
											</div>
											<a class="carousel-control-prev" href="#thumbcarousel" role="button" data-slide="prev">
												<i class="fa fa-angle-left" aria-hidden="true"></i>
											</a>
											<a class="carousel-control-next" href="#thumbcarousel" role="button" data-slide="next">
												<i class="fa fa-angle-right" aria-hidden="true"></i>
											</a>
										</div>
									</div>
								</div>';
								}else echo '<div class="product-slider tac">
								<img src="',WebSite,'/images/products/no_product_image.jpg" alt="',$row['title'],'"/>
								</div>';
							echo '</div>
						</div>
						
						<div class="wideget-user-tab wideget-user-tab3">
	<div class="tab-menu-heading">
		<div class="tabs-menu1">
			<ul class="nav">
				<li class=""><a href="#tab-15" class="active" data-toggle="tab">',$lang['prd_description'],'</a></li>
				<li><a href="#tab-16" data-toggle="tab" class="">',$lang['terms_buy'],'</a></li>';
				if(!isset($_SESSION['customer_names'])) echo '<li><a href="#tab-17" data-toggle="tab" class="">',$lang['fast_order'],'</a></li>';
			echo '</ul>
		</div>
	</div>
</div>';

$spterms = $lang['spterms'];
if(!empty($row['special_terms'])) $spterms = htmlspecialchars_decode($row['special_terms']);
	echo '<div class="card">
		<div class="card-body">
			<div class="tab-content">
			<div class="tab-pane active" id="tab-15">
			<div class="card-header pl-0">
					<h3 class="card-title mb-3">',$lang['prd_description'],'</h3>
				</div>
			<div class="mb-0 pt-3 pb-3">',htmlspecialchars_decode($row['description']),'</div>
			</div>
			
			<div class="tab-pane" id="tab-16">
			<div class="card-header pl-0">
				<h3 class="card-title mb-3">',$lang['terms_buy'],'</h3>
			</div>
			<div class="mb-0 pb-4">',$spterms,'</div>
			</div>
			
			<div class="tab-pane" id="tab-17">
			<div class="card-header pl-0">
				<h3 class="card-title mb-3">',$lang['fast_order'],'</h3>
			</div>
			<div class="mt-4">							
				<h4>',sprintf($lang['c_a3'],$row['title']),'</h4>
					<div class="msg tac msgaaa"></div>
					<div class="mt-4 mb-4">
						<div class="form-group">
						<label class="form-label">',$lang['names'],'</label>
							<input type="text" class="form-control man" id="names" value="',$names,'" placeholder="',$lang['names'],'"/>
						</div>
						
						<div class="form-group">
						<label class="form-label">',$lang['phone'],'</label>
							<input onkeypress="return(numberFormat(event));" type="text" class="form-control man" id="cphone" value="',$customer_phone,'" placeholder="',$lang['phone'],'"/>
						</div>
						<div class="form-group">
						<label class="form-label">',$lang['delivery_address'],'</label>
							<textarea class="form-control man" name="delivery_address" id="delivery_address" value="" rows="6" placeholder="',$lang['enter'],'"></textarea>
						</div>
						<div class="form-group ib w100 tac mb-5">
						<a id="send_fast_order" class="btn btn-success db text-white">',$lang['send'],'</a>
						</div>
					</div>
				</div>
			</div>
			
			</div>';
							
							
								
$product_options = echo_product_options_new_basket($pid);
/*if(count($product_options) > 0){
echo '<div class="mb-0"><label class="form-label text-orange mt-5">',$lang['prd_opts'],'</label>
<small class="ib w100">',$lang['f_order_sm'],'</small>';
//var_dump($product_options);
foreach($product_options as $optkey=>$option){
echo '<div class="mb-2 mt-2 ib mr-5"><label class="form-label text-dark mb-2">',$optkey,': </label>',str_replace(";"," | ",$option).'</div>';
	}
	echo '</div>';
}
*/

$optionhtml = '';
$count = 0;
//var_dump($product_options);
if(!empty($product_options) && count($product_options) > 0){
$optionhtml.= '<div class="ib w100 mb-3 mt-5"><label class="form-label text-orange mt-5">'.$lang['prd_opts'].'</label>
<small class="ib w100">'.$lang['f_order_sm'].'</small>';
foreach($product_options as $optionkey=>$optionval){
//echo $optionkey;
//$selected = '';
$count++;
$optionhtml.='<div class="ib w200p mr-3 mt-2 mb-2"><select name="options['.$optionkey.'][]" data-name="'.$optionkey.'" data-id="'.$pid.'" id="'.$optionkey.'" class="form-control select_option" data-placeholder="">
<option value="0">'.$lang['select'].' '.$optionkey.'</option>';
foreach($optionval as $optionvalname){
$selected = '';
if(isset($_SESSION['product_options'][$pid]) && in_array($optionvalname,$_SESSION['product_options'][$pid])) $selected = 'selected="selected"';
$optionhtml.='<option '.$selected.' value="'.$optionvalname.'">'.$optionvalname.'</option>';
}
$optionhtml.='</select></div>';
	}
	$optionhtml.='</div>';
}

echo $optionhtml;


if(!empty($advertrow['company_phones'])){
$phones = explode(";", $advertrow['company_phones']);
}

$adcl = 'tdlt';
$qty = 1;
if(isset($_SESSION['products'][$pid])) $qty = $_SESSION['products'][$pid];
if($row['active'] > 0 && $row['sold'] < 1) $adcl = '';

if($row['offer_type'] == '1' && $row['deliver_product'] < 2){
	if(empty($row['redirect_url'])) echo '<div class="ib w100 orange mt-3"><h4>',$lang['obph'],' - <a data-aid="',$advertrow['id'],'" data-pid="',$pid,'" href="tel:',$phones[0],'" class="btn btn-info text-white icons phone_dialer mr-1 mt-2 mb-2"><i class="icon fa fa-phone-square mr-1"></i> ',$lang['call'],'</a></h4></div>';

	echo '
	<div class="card-footer p-2">
	<div class="ib mt-3 mb-3">
	<div class="ib wa vat">
	<label class="form-label text-orange">',$lang['qty_short'],'</label>
	<input min="1" max="100" type="number" data-id="'.$pid.'" class="form-control qty w60p" name="qty" value="',$qty,'"/></div>';
	if($row['promo_price'] > 0){
	echo '<h3 class="ib wa lh40 ml-4 mb-0 vab mr-4 promo_p ',$adcl,'">',$lang['prd_price'],' ',current_currency_sign,' ',$row['price'],'</h3>';
	echo '<h3 class="ib wa lh40 ml-4 mb-0 vab mr-4 ',$adcl,'">',$lang['prd_price'],' ',current_currency_sign,' ',$row['promo_price'],'</h3>';
	}else echo '<h3 class="ib wa lh40 ml-4 mb-0 vab mr-4 ',$adcl,'">',$lang['prd_price'],' ',current_currency_sign,' ',$row['price'],'</h3>';
	echo '</div>';
	//echo $row['active'],'aaa';
	if($row['active'] > 0 && $row['sold'] < 1){
	
	echo '<div id="atbholder" class="mt-1 mb-3 ib wa vab">';
	
	if(!empty($row['redirect_url'])) echo '<a href="',$row['redirect_url'],'" target="_blank" class="btn btn-secondary text-white fs1 mt-3" >',$lang['order'],'</a>';
	
	//if(isset($_SESSION['products']) && array_key_exists($pid,$_SESSION['products'])) echo '<a href="../../cart/" class="btn btn-secondary fs1" >',$lang['f_order'],'</a>';
	//else echo '<a data-act="add" data-id="',$pid,'" data-url="',$productlink,'" class="btn btn-secondary text-white fs1 add_to_basket mt-3" >',$lang['order'],'</a>';
	
	//else echo '<a data-aid="',$advertrow['id'],'" data-pid="',$pid,'" href="tel:',$phones[0],'" class="btn btn-info text-white icons phone_dialer mr-1 mt-2 mb-2"><i class="icon fa fa-phone-square mr-1"></i> ',$lang['order_phone'],'</a>';
	
	echo '</div>';
	}else echo '<div class="mt-1 mb-3 ib wa vab"><a class="btn btn-danger">',$lang['exp_of'],'</a></div>';
	
	echo '</div>';
	}else echo '<div class="card-footer p-2">
	<div class="ib mt-3 mb-3"><h3>',$lang['npo'],'</h3>
	<h4 class="ib wa lh40 mb-0 vab orange mr-4 ',$adcl,'">',$lang['prd_price'],' ',$row['price'],' ',current_currency,'</h4>
	</div>
	
	<div>
		<label>',$lang['ofad'],':</label>
		<div class="item-user mt-2">
			<div>
				<h6><span class="font-weight-semibold"><i class="fa fa-map mr-3 mb-0"></i></span><a target="_blank" href="https://www.google.com/maps/place/',$advertrow['latitude'],',',$advertrow['longitude'],'/" class="text-body"> ',$advertrow['town'],', ',$advertrow['area'],', ',$advertrow['company_address'],'</a></h6>
			</div>
		</div>
		
		<div class="mt-4">
			<a class="btn btn-secondary" target="_blank" href="https://www.google.com/maps/place/',$advertrow['latitude'],',',$advertrow['longitude'],'/">',$lang['map_dir'],'</a>
		</div>
	</div>
	</div>';
	
	if(!empty($advertrow['pay_methods'])){
	$pay_methods = unserialize($advertrow['pay_methods']);

	if(is_array($pay_methods) && count($pay_methods) > 0){
	echo '
	<div class="card-footer p-2">
	<h4 class="ib wa lh40 mb-0 vab">',$lang['pay_ways_txt'],'</h4>
	<ul class="list-unstyled mb-0">';
	foreach($lang['pay_methods'] as $pkey=>$pval){
	if(in_array($pkey,$pay_methods)) echo '
	<li class="ib wa mr-4 mt-3 mb-2"><i class="fa fa-check text-success" aria-hidden="true"></i> ',$pval,'</li>';
		}
	echo '</ul></div>';
		}
	}
							echo '</div>
							<div class="pt-4 pb-4 pl-5 pr-5 border-top border-top">
								<div class="list-id">
									<div class="row">
										<div class="col col-auto">
											',$lang['post_by'],' <a target="_blank" href="../../business-directory/',$advertrow['link_name'],'" class="mb-0 font-weight-bold">',$advertrow['supplier_name'],'</a> / ',date('d-m-Y',strtotime($row['addeddate'])),' ',$lang['y'],'
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer bg-white">
								<div class="icons">';

if(!empty($phones[0])){
echo '<a data-aid="',$advertrow['id'],'" data-pid="',$pid,'" href="tel:',$phones[0],'" class="btn btn-info text-white icons phone_dialer mr-1 mt-2 mb-2"><i class="icon fa fa-phone-square mr-1"></i> ',$lang['call'],'</a>';
}
echo '<a id="socialmedia" class="btn btn-danger icons mt-2 mb-2"><i class="icon icon-share mr-1"></i> ',$lang['share'],'</a>';
if($advertrow['customer_id'] == $_SESSION['customer'])  echo '<a href="#" class="btn btn-pink icons ml-1 mr-1 mt-2 mb-2"><i class="icon icon-share icon-eye mr-1"></i> ',$views,' </a>';
echo '<a onclick="window.print();" class="btn btn-primary icons ml-1 mt-2 mb-2"><i class="icon icon-printer mr-1"></i> ',$lang['print'],' </a>
<a href="../../../contacts/" class="btn btn-light icons mt-2 mb-2"><i class="icon icon-exclamation mr-1"></i> ',$lang['abuse'],' </a>
</div>
								
								<div id="loadhere"></div>
							</div>
						</div>';
						
$names = '';
$customer_email = '';
$customer_phone = '';
if(isset($_SESSION['customer_names'])) $names = $_SESSION['customer_names'];
if(isset($_SESSION['customer_email'])) $customer_email = $_SESSION['customer_email'];
if(isset($_SESSION['customer_phone'])) $customer_phone = $_SESSION['customer_phone'];
						echo '<div class="card mb-lg-0">
							<div class="card-header">
								<h3 class="card-title">',$lang['have_q'],'</h3>
							</div>
							<div class="card-body">							
								<h4>',$lang['have_q1'],'</h4>
								<div class="msg tac msgaaa nmsg"></div>
								<div class="mt-4">
									<div class="form-group">
									<label class="form-label">',$lang['names'],'</label>
										<input type="text" class="form-control" id="cnames" value="',$names,'" placeholder="Your Name"/>
									</div>
									<div class="form-group">
									<label class="form-label">',$lang['email'],'</label>
										<input type="email" class="form-control" id="email" value="',$customer_email,'" placeholder="Email Address"/>
									</div>
									<div class="form-group">
									<label class="form-label">',$lang['phone'],'</label>
										<input onkeypress="return(numberFormat(event));" type="text" class="form-control" id="phone" value="',$customer_phone,'" placeholder="Phone"/>
									</div>
									<div class="form-group">
									<label class="form-label">',$lang['message'],'</label>
										<textarea class="form-control" name="message" id="message" value="" rows="6" placeholder="',$lang['pls_ent'],'"></textarea>
									</div>
									<a id="contact_trader" class="btn btn-secondary">',$lang['send'],'</a>
								</div>
							</div>
						</div>

						
					
					</div>

					<!--Right Side Content-->
					<div class="col-xl-4 col-lg-4 col-md-12">
						<div class="card overflow-hidden">
							<div class="card-header">
								<h3 class="card-title ib w100 tac">',$lang['supplier_data'],'</h3>
							</div>
							<div class="card-body item-user">
								<div class="profile-details pr tar">';
								$logo = '<img src="../../assets/images/other/logo.jpg" class="brround w-150 h-150" alt="'.$advertrow['supplier_name'].'"/>';
								if(!empty($advertrow['small_image'])) $logo = '<img src="../../images/adverts/'.$advertrow['small_image'].'" class="brround w-150 h-150" alt="'.$advertrow['supplier_name'].'"/>';
								if(!empty($advertrow['logo'])) $logo = '<img src="../../images/adverts/'.$advertrow['logo'].'" class="brround w-150 h-150" alt="'.$advertrow['supplier_name'].'"/>';
								if(isset($_SESSION['customer']) && $_SESSION['customer'] == $advertrow['customer_id']) echo '
								<a href="',WebSite,'/redirector.php?edit_ad=',$advertrow['id'],'" class="btn btn-success btn-sm text-white" data-toggle="tooltip" data-original-title="Редакция">
								<i class="fa fa-pencil"></i></a>
								';
									
									echo '<div class="profile-pic mb-0 mx-5">
										',$logo,'
									</div>
								</div>
								<div class="text-center mt-2">
									<a href="',WebSite,'/business-directory/',$advert_category,'/',$advertrow['link_name'],'" target="_blank" class="text-dark text-center"><h4 class="mt-0 mb-0 font-weight-semibold">',$advertrow['supplier_name'],'</h4></a>
									<span class="text-muted ffo ib w100 mt-2">',$lang['member_since'],' ',date('Y',strtotime($advertrow['added_date'])),' ',$lang['y'],'</span>
								</div>
							</div>';
							$workingdays = unserialize($advertrow['work_days']);
							echo '<div class="profile-user-tabs">
								<div class="tab-menu-heading border-0 p-0">
									<div class="tabs-menu1">
										<ul class="nav">
											<li class=""><a href="#tab-contact" class="active" data-toggle="tab">',$lang['cont'],'</a></li>';
											echo '<li><a href="#tab-timings" data-toggle="tab">',$lang['worktime'],'</a></li>';
										echo '</ul>
									</div>
								</div>
							</div>
							<div class="tab-content border-0 bg-white">
								<div class="tab-pane active" id="tab-contact">
									<div class="card-body item-user">
										<h4 class="mb-4">',$lang['contact_details'],'</h4>
										<div>
											<h6><span class="font-weight-semibold"><i class="fa fa-map mr-3 mb-2"></i></span><a href="#" class="text-body"> ',$advertrow['town'],', ',$advertrow['area'],', ',$advertrow['company_address'],'</a></h6>
											<h6><span class="font-weight-semibold"><i class="fa fa-envelope mr-3 mb-2"></i></span><a href="mailto:',$advertrow['mail'],'" class="text-body"> ',$advertrow['mail'],'</a></h6>';
											
											if(!empty($advertrow['company_phones'])){
							echo '<span class="font-weight-semibold"><i class="fa fa-phone mr-3 mb-2"></i></span>';
							$phones = explode(";", $advertrow['company_phones']);
							foreach($phones as $phone) echo '<h6 class="ib wa"><a class="phone_dialer" data-aid="',$advertrow['id'],'" data-pid="',$pid,'" href="tel:',$phone,'" class="text-secondary"> ',$phone,'</a></h6> | ';
							//var_dump($phones);
							}
	if($advertrow['selected_plan'] > 1) echo '<h6><span class="font-weight-semibold"><i class="fa fa-link mr-3 "></i></span><a target="_blank" data-aid="',$advertrow['id'],'" href="',$advertrow['site'],'" rel="nofollow" class="text-secondary link_clicker">',$advertrow['site'],'</a></h6>';
	else echo '<h6><span class="font-weight-semibold"><i class="fa fa-link mr-3 "></i></span>',$advertrow['site'],'</h6>';
										echo '</div>
									</div>
								</div>';
								
								//var_dump($workingdays);
								echo '<div class="tab-pane" id="tab-timings">
									<div class="table-responsive card-body">
										<table class="table table-bordered border-top mb-0">
											<tbody>';
											if(!empty($workingdays) && count($workingdays) > 0){
											foreach($workingdays as $day){
											echo '<tr>
													<td>',$lang['days_arr'][$day],'</td>
													<td class="font-weight-semibold">',$advertrow['opening'],' - ',$advertrow['closing'],'</td>
												</tr>';
												}
											}else echo '<tr><td colspan="2" class="tac">',$lang['no_info'],'</td></tr>';
											echo '</tbody>
										</table>
									</div>
								</div>
								<div class="card-footer">
									<div class="tac">
										<a href="',WebSite,'/business-directory/',$advert_category,'/',$advertrow['link_name'],'" class="btn btn-info mt-1 mb-1"><i class="fa fa-external-link"></i> ',$lang['view_profile'],'</a></div>
								</div>
							</div>
						</div>';
						
						if($advertrow['business_type'] != 2){
						echo '<div class="card" id="location">
							<div class="card-header">
								<h3 class="card-title">',$lang['trad_adr'],'</h3>
							</div>
							<div class="card-body tac">
								<div class="map-header">
									<div class="map-header-layer" id="map2">
									<a target="_blank" href="https://www.google.com/maps/place/',$advertrow['latitude'],',',$advertrow['longitude'],'/">
									<img id="mapsmpl" title="',$lang['listing_on_map'],'" src="',WebSite,'/images/mapsample.jpg"/>
									</a></div>
								</div>
								<div class="item-user mt-5">
									<div>
										<h6><span class="font-weight-semibold"><i class="fa fa-map mr-3 mb-0"></i></span><a href="#" class="text-body"> ',$advertrow['town'],', ',$advertrow['area'],', ',$advertrow['company_address'],'</a></h6>
									</div>
								</div>
							</div>
							<div class="card-footer tac">
								<a class="btn btn-secondary" target="_blank" href="https://www.google.com/maps/place/',$advertrow['latitude'],',',$advertrow['longitude'],'/">',$lang['map_dir'],'</a>
							</div>
						</div>';
						}
						echo '<div class="card">
							<div class="card-header">
								<h3 class="card-title">',$lang['tags'],'</h3>
							</div>
							<div class="card-body product-filter-desc">
								<div class="product-tags clearfix">
									<ul class="list-unstyled mb-0">';
if(!empty($row['product_tags'])){
$ptags = explode(";",rtrim($row['product_tags'],';'));
foreach($ptags as $tag){
if($row['category_name'] != $tag && $row['title'] != $tag) echo '<li><a class="tags">',$tag,'</a></li>';
	}
}
echo '
<li><a class="tags">',$row['category_name'],'</a></li>
<li><a class="tags">',$row['title'],'</a></li>';
if(!empty($row['tags_latin'])){
$latintags = explode(";",rtrim($row['tags_latin'],';'));
foreach($latintags as $ltag){
echo '<li><a class="tags">',$ltag,'</a></li>';
	}
}
										
		if(!empty($row['title_latin'])) echo '<li><a class="tags">',$row['title_latin'],'</a></li>';
									echo '</ul>
								</div>
							</div>
						</div>
						
						
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">',$lang['search_shop'],'</h3>
							</div>
							<div class="card-body">
							
								<div class="form-group">
									<input onkeyup="document.getElementById(\'search_text\').value = this.value" type="text" class="form-control keywords-input" placeholder="',$lang['w_s'],'">
								</div>
								
								<div>
									<button id="search_p" class="btn btn-block btn-secondary">',$lang['search'],'</button>
								</div>
								
							</div>
						</div>
					</div>
					<!--/Right Side Content-->
					
					<div class="col-xl-12 col-lg-12 col-md-12">
					<h3 class="mt-5 mb-4 fs-20">',$lang['similar'],'</h3>';

$prquery='SELECT p.title,p.description,p.price,p.image, a.link_name, category_url,a.supplier_name,product_url,category_url, category_name, url  
FROM products p,adverts a, adverts_to_product_categories atpc, products_categories pc   
	WHERE p.id != "'.$pid.'" and p.advert_id=a.id and atpc.advert_id = a.id and pc.id = atpc.category_id and (a.id = "'.mysql_real_escape_string($advertrow['id']).'" or category_url = "'.mysql_real_escape_string($row['category_url']).'") and a.active="1" and p.active="1" and p.sold="0" and p.deleted="0" and p.checked_by_admin = "1" group by p.id, a.id ORDER BY addeddate DESC LIMIT 8';

echo_products($prquery);
					echo '</div>
				</div>
			</div>';
//zapiswame poseshtenieto
$insertstatistics = 'insert into statistics_products_visits set product_id = "'.$pid.'", advert_id = "'.$advertrow['id'].'", ip="'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'"';
$insertstatisticsresult=mysql_query($insertstatistics) or die(send_error($insertstatistics,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	

if(!empty($advertrow['company_phones'])){
$phones = explode(";", $advertrow['company_phones']);
echo '<a data-aid="'.$advertrow['id'].'" data-pid="'.$pid.'" href="tel:'.str_replace(' ','',$phones[0]).'" class="btn btn-success text-white icons phone_dialer mr-1 mt-2 mb-2 bphdial" style=""><i class="icon fa fa-phone-square mr-1"></i> '.$lang['order_phone'].'</a>';			
}
echo '</section>';
}else{
include './products.php';
}


include '../modules/footer.php';
?>	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<script src="<?php echo WebSite;?>/assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js?t=<?php echo time();?>"></script>
<script src="<?php echo WebSite;?>/assets/js/fullfunctions.js"></script>
<script src="<?php echo WebSite;?>/assets/js/shop.js?t=<?php echo time();?>"></script>

	</body>
</html>