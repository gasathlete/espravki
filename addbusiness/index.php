<?php
session_start();
require '../config.php';
require '../project_functions.php';

require '../translation/'.$_SESSION['lang'].'_lang.php';
if(isset($_SESSION['customer'])){

$check_for_not_approved = check_for_not_approved();

if(!empty($check_for_not_approved)){
header("location: ../editbusiness/");
exit(0);
}

if(isset($_SESSION['selected_plan'])){

function echo_meta(){
	global $lang;
	echo '<title>',$lang['meta_add']['title'],'</title>';
	echo '<meta name="description" content="',$lang['meta_add']['description'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_add']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['meta_add']['title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'/addbusiness/" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['meta_add']['description'],'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';	
}

$help_business_type='<div id="help_business_type" class="bar" style="top:35%;"><span></span>'.$lang['help_business_type'].'</div>';
$help_supplier_name='<div id="help_supplier_name" class="bar"><span></span>'.$lang['help_supplier_name'].'</div>';
$help_email='<div id="help_email" class="bar"><span></span>'.$lang['used_for'].'</div>';
$help_password='<div id="help_password" class="bar"><span></span>'.$lang['used_for2'].'</div>';
$help_website='<div id="help_website" class="bar"><span></span>'.$lang['website_txt'].'</div>';
$help_city='<div id="help_city" class="bar"><span></span>'.$lang['city1'].'</div>';
$help_area='<div id="help_area" class="bar"><span></span>'.$lang['help_area'].'</div>';
$help_address='<div id="help_address" class="bar"><span></span>'.$lang['city1'].'</div>';
$help_phone='<div id="help_phone" class="bar"><span></span>'.$lang['help_phone'].'</div>';
$help_description='<div id="help_description" class="bar"><span></span>'.$lang['descr_txt'].'</div>';
$help_fsearch='<div id="help_fsearch" class="bar"><span></span>'.$lang['category_txt'].'</div>';
$help_keywords='<div id="help_keywords" class="bar"><span></span>'.$lang['keywords_txt'].'</div>';

$help_lat='<div id="help_lat" class="bar"><span></span>'.$lang['help_lat'].'</div>';
$help_lng='<div id="help_lng" class="bar"><span></span>'.$lang['help_lng'].'</div>';

$cities = echo_cities();

$msg = '';

if(!isset($_POST['inv_city']) || empty($_POST['inv_city'])) $city = $_SESSION['city'];
else $city = $_POST['inv_city'];

if(!isset($_POST['town']) || empty($_POST['town'])) $town = $_SESSION['city'];
else $town = $_POST['town'];


if(!isset($_POST['mol']) || empty($_POST['mol'])) $mol = $_SESSION['customer_names'];
else $mol = $_POST['mol'];

if (isset($_POST['save_advert'])){

$mand_fields = array("supplier_name", "business_type", "category", "mail", "company_name", "bulstat", "inv_city", "inv_address", "mol", "town", "company_address", "latitude", "longitude", "company_phones", "description", "bulstat", "payment", "terms");


$_POST['company_phones'] = str_replace(array('/','(',')'),'',trim($_POST['company_phones']));

//echo $_POST['description'];echo '<br/>';


foreach($mand_fields as $fild){

if(!is_array($_POST[$fild])){
$_POST[$fild] = str_replace('"',"'",$_POST[$fild]);

$_POST[$fild] = strip_tags($_POST[$fild],'<div><span><ul><li><br><p>');
}else{
foreach($_POST[$fild] as $fkey=>$fval) $_POST[$fild][$fkey] = strip_tags($_POST[$fild][$fkey],'<p>');//echo '<br/>';
}
if(!isset($_POST[$fild]) || empty($_POST[$fild])) $msg = $lang['enter'].' '.$lang[$fild];//.'-'.$fild

	}


if(isset($_POST['sellbusiness'])){
if(empty($_POST['business_price'])) $msg = $lang['enter'].' '.$lang['business_price'];
}	

if(!empty($_POST['company_phones'])){
if(strlen($_POST['company_phones']) > 13){
	if (strpos($_POST['company_phones'], ';') == false) {
    $msg = $lang['en_c_ph'];
		}
	}
}

if(!empty($_FILES['images']['name'][0])){
//var_dump($_FILES["images"]);
foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
if(!empty($tmp_name)){
list($width, $height, $type, $attr) = getimagesize($_FILES["images"]["tmp_name"][$key]);
if (!isset($type) || !in_array($type, array(
    IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
 $msg = $lang['UNSUPPORTED_FILE_TYPE'].' '.$_FILES["images"]["name"][$key];
}

if($_FILES["images"]['type'][$key]=='image/jpeg' || $_FILES["images"]['type'][$key]=='image/pjpeg' || $_FILES["images"]['type'][$key]=='image/jpg' || $_FILES["images"]['type'][$key]=='image/JPEG' || $_FILES["images"]['type'][$key]=='image/png' || $f['type']=='image/x-png'){
			if(exif_imagetype($_FILES["images"]["tmp_name"][$key])===FALSE){
			$msg=$_FILES["images"]['name'][$key].': '.$lang['UNSUPPORTED_FILE_TYPE'];
			}//else echo exif_imagetype($_FILES["images"]["tmp_name"][$key]);
			}else $msg=$_FILES["images"]['name'][$key].': '.$lang['UNSUPPORTED_FILE_TYPE'];
		}
	}
}


	
//exit();		
if(empty($msg)){
$linkname = create_url($_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode']);
$max_offers = $lang['offers_num'][$_SESSION['selected_plan']];
if(isset($_POST['work_days'])) $work_days = serialize($_POST['work_days']);
else $work_days = '';

if(isset($_POST['opening'])) $opening = $_POST['opening'];
else $opening = '00:00';

if(isset($_POST['closing'])) $closing = $_POST['closing'];
else $closing = '00:00';

$pay_methods = serialize($_POST['pay_methods']);
$certificates = serialize($_POST['certificates']);

$checka = 'select id from adverts where link_name="'.mysql_real_escape_string($linkname).'"';
$checkaresult=mysql_query($checka) or die(send_error($checka,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$checka_num_rows=mysql_num_rows($checkaresult);

if($checka_num_rows < 1){
$insert = 'insert into adverts set 
customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'",
supplier_name="'.mysql_real_escape_string (htmlspecialchars($_POST['supplier_name'])).'",
description="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['description'],'<p><span><div><h1><h2><h3><h4><h5><br><br/><ul><li>'))).'",
meta_title="'.mysql_real_escape_string (htmlspecialchars($_POST['supplier_name'])).'",
meta_keywords="'.mysql_real_escape_string(htmlspecialchars($_POST['meta_keywords'])).'",
link_name="'.mysql_real_escape_string($linkname).'",
latitude="'.mysql_real_escape_string($_POST['latitude']).'",
longitude="'.mysql_real_escape_string($_POST['longitude']).'",
town="'.mysql_real_escape_string($_POST['town']).'",
area="'.mysql_real_escape_string($_POST['area']).'",
postcode="'.mysql_real_escape_string($_POST['postcode']).'",
company_address="'.mysql_real_escape_string($_POST['company_address']).'",
country="Bulgaria",
country_code="BG",
company_phones="'.mysql_real_escape_string($_POST['company_phones']).'",
mail="'.mysql_real_escape_string($_POST['mail']).'",
site="'.mysql_real_escape_string($_POST['site']).'",
expired_date="0000-00-00", 
business_type="'.mysql_real_escape_string($_POST['business_type']).'",
max_offers = "'.mysql_real_escape_string($max_offers).'",
selected_plan = "'.mysql_real_escape_string($_SESSION['selected_plan']).'",
selected_pay_way = "'.mysql_real_escape_string($_POST['payment']).'",
work_days = "'.mysql_real_escape_string($work_days).'",
pay_methods = "'.mysql_real_escape_string($pay_methods).'",
certificates = "'.mysql_real_escape_string($certificates).'",
opening="'.mysql_real_escape_string($opening).'",
closing="'.mysql_real_escape_string($closing).'",
amount="'.mysql_real_escape_string($lang['plans_prices'][$_SESSION['selected_plan']]).'",
company_name = "'.mysql_real_escape_string($_POST['company_name']).'",
bulstat = "'.mysql_real_escape_string($_POST['bulstat']).'",
dds = "'.mysql_real_escape_string($_POST['dds']).'",
inv_city = "'.mysql_real_escape_string($_POST['inv_city']).'",
inv_address = "'.mysql_real_escape_string($_POST['inv_address']).'",
mol = "'.mysql_real_escape_string($_POST['mol']).'"';

if($_SESSION['selected_plan'] == 2){
$insert.='
,facebook = "'.mysql_real_escape_string($_POST['facebook']).'",
twitter = "'.mysql_real_escape_string($_POST['twitter']).'",
linkedin = "'.mysql_real_escape_string($_POST['linkedin']).'",
instagram = "'.mysql_real_escape_string($_POST['instagram']).'"';
}

if($_SESSION['selected_plan'] > 1 && isset($_POST['sellbusiness'])){
$insert.=',sellbusiness = "1",business_price = "'.mysql_real_escape_string($_POST['business_price']).'"';
}
$insert.=',ip = "'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'"';
if(isset($_SESSION['affiliate_id']) && intval($_SESSION['affiliate_id']) > 0) $insert.=', affiliate="1", affiliate_id="'.mysql_real_escape_string($_SESSION['affiliate_id']).'"';
if(!empty($_SESSION['referral'])) $insert.=', referral="'.mysql_real_escape_string($_SESSION['referral']).'"';
//echo $insert;
//exit();
$result=mysql_query($insert) or die(send_error($insert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$last_id=mysql_insert_id();
$_SESSION['advert_id']=$last_id;

if(!empty($_POST['category'])){
$newcategory = '';
$cats = '';
foreach($_POST['category'] as $category){
		$q='select id, bg_category,url from products_categories where bg_category="'.mysql_real_escape_string($category).'"';
		$r=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
		$num_rows=mysql_num_rows($r);
		if($num_rows > 0){
		$row=mysql_fetch_row($r);
		$query='insert into adverts_to_product_categories set advert_id="'.mysql_real_escape_string($last_id).'", category_id="'.$row[0].'"';
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$cats.= $category.',';
		}else{
		//dobawqme nowa kategoriq
		$inscat = 'insert into products_categories set bg_category = "'.mysql_real_escape_string($category).'", url = "'.mysql_real_escape_string(create_url($category)).'", parent="0"';
		$inscatresult=mysql_query($inscat) or die(send_error($inscat,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$last_cat_id=mysql_insert_id();
		
		$query='insert into adverts_to_product_categories set advert_id="'.mysql_real_escape_string($last_id).'", category_id="'.mysql_real_escape_string($last_cat_id).'"';
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
				
		$newcategory.= $category.',';
				
				}
			}
			
			if(!empty($newcategory)){
			//ako klienta e predlozil kategoriq slagame listinga vav vremenna kategoriq
				//$query='insert into adverts_to_product_categories set advert_id="'.mysql_real_escape_string($last_id).'", category_id="9999"';
				//$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
				
				//isprashtame si mail s towa kakwo e predlozil klienta kato kategoriq
				$subject = 'Nova registraciq w '.WebSite.'';
				$body = 'Zdrasti Marianski,<br />Klient '.$_POST['supplier_name'].' dobawi listing i predlaga nowa kategoriq - '.$newcategory;
				$params=array(
				"content_type"=>"text/html",
				"replay_to"=>official_mail_sender,
				"from_name"=>official_mail_sender_name,
				"from_email"=>official_mail_sender,
				"to"=>'eespravki@gmail.com',
				"subject"=>$subject,
				"body"=>$body
				);
				send_mail_no_header($params);
			}else{
				//isprashtame si mail s towa kakwo e predlozil klienta kato kategoriq
				$subject = 'Nova registraciq w '.WebSite.'';
				$body = 'Здрасти Мариянски,<br />Фирма '.$_POST['supplier_name'].' добави листинг в категория - '.$cats;
				$params=array(
				"content_type"=>"text/html",
				"replay_to"=>official_mail_sender,
				"from_name"=>official_mail_sender_name,
				"from_email"=>official_mail_sender,
				"to"=>'eespravki@gmail.com',
				"bcc"=>'',
				"subject"=>$subject,
				"body"=>$body
				);
				send_mail_no_header($params);
			
			}
		}
		
		$imagecounter = 1;
		if(!empty($_FILES['images']['name'][0])){
		foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
		if(!empty($tmp_name)){
		if($imagecounter <= $lang['images_num'][$_SESSION['selected_plan']]){
		$imagecounter++;
		$save_file_name = create_url($_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode'].'-'.($key+1)).'-'.$last_id.'.jpg';
		$smallfilename = create_url($_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode']).'-small-'.$last_id.'.jpg';

		if(($key+1) == 1){
		$update = 'update adverts set small_image = "'.$smallfilename.'" where id = "'.mysql_real_escape_string($last_id).'"';
		$updateresult=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$smallfilename,400,400,'adverts');
		}
		
		$insertimages = 'insert into adverts_gallery set image = "'.mysql_real_escape_string($save_file_name).'", advert_id = "'.mysql_real_escape_string($last_id).'", order_id = "'.mysql_real_escape_string($key+1).'"';
		$insertimagesresult=mysql_query($insertimages) or die(send_error($insertimages,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$save_file_name,1920,1200,'adverts');		
					}
				}
			}
		}
		
		
		$msg = 'success saved!';
		
		header("location: ../editbusiness/");
		exit(0);
		}else $msg = $lang['change_name'];
	}	
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<?php echo echo_meta();?>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="author" content="Web-site-maker.eu">
<link rel="icon" type="image/png" href="<?php echo WebSite;?>/favicon.ico" />
<link href="../assets/css/style.css" rel="stylesheet" />
<link href="../assets/css/buttons.css" rel="stylesheet" />
<link id="theme" href="../assets/color-skins/color1.css"  rel="stylesheet"/>
<link href="../assets/plugins/fancyuploder/fancy_fileupload.css" rel="stylesheet" />

</head>
<body>
<?php
include '../modules/header.php';
//var_dump($_POST);echo '<br/>';
?>

<section>
	<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/banner2.jpg">
		<div class="header-text mb-0">
			<div class="container">
				<div class="text-center text-white">
					<h1 class=""><?php echo $lang['meta_news_add']['title'];?></h1>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="bg-white border-bottom">
<div class="container">
<div class="page-header">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="../"><?php echo $lang['home'];?></a></li>
<?php

echo '
<li class="breadcrumb-item">'.$lang['meta_news_add']['title'].'</li>';
?>				
</ol>
</div>
</div>
</div>

<section class="sptb">
			<div class="container">
				<div class="row">
					<div class="col-xl-8">
						<div class="card mb-xl-0">
						<div class="cmsg"><?php echo $msg;?></div>

							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['a_lst'],' - ',$lang['plans_array'][$_SESSION['selected_plan']],' - € ',$lang['plans_prices'][$_SESSION['selected_plan']];?> </h3>
							</div>

							<div class="card-body">
								<form id="advertForm" action="" name="advertForm" method="post" enctype="multipart/form-data" class="form-horizontal mb-0">
									<div id="rootwizard" class="border pt-0">
										<ul class="nav nav-tabs nav-justified bbd">
											<li class="nav-item ib"><a id="firsttab" href="#first" data-toggle="tab" class="nav-link font-bold active"><?php echo $lang['base_info'];?></a></li>
											<li class="nav-item ib"><a href="#ab_com" class="nav-link font-bold sublink"><?php echo $lang['ab_com'];?></a></li>
											<li class="nav-item ib"><a href="#images" class="nav-link font-bold sublink"><?php echo $lang['images'];?></a></li>
											<li class="nav-item ib"><a href="#paymnt" class="nav-link font-bold sublink"><?php echo $lang['paymnt'];?></a></li>
										</ul>
										<hr class="mb-1"/>
										<div class="tab-content mb-0 b-0">
<div class="tab-pane fade card-body active show" id="first">
<div class="control-group form-group">
	<div class="form-group rws">
		<label class="form-label text-dark"><?php echo $lang['title'];?></label>
		<input data-tab="first" onClick="show_help('#help_supplier_name');" type="text" maxlength="100" class="form-control mand" id="supplier_name" name="supplier_name" value="<?php echo @$_POST['supplier_name'];?>" placeholder="<?php echo $lang['enter'];?>" />
	<?php echo $help_supplier_name;?>
	</div>
</div>
<div class="form-group mt-4">
	<label class="form-label text-dark"><?php echo $lang['business_type'];?></label>
	<?php echo select_business_type(@$_POST['business_type']);?>
</div>

<div class="form-group mt-4">
	<div class="form-group">
		<label class="form-label text-dark"><?php echo $lang['category'];if($_SESSION['selected_plan'] == 2) echo ' ( <small>',$lang['al_2'],'</small> ) ';?></label>
<?php
$multiple = '';
if($_SESSION['selected_plan'] == 2) $multiple = 'multiple="multiple"';				
		
echo '<select ',$multiple,' name="category[]" id="add_listing_categories" class="form-control select2-show-search-prdcat border-bottom-0 mand" data-placeholder="',$lang['ccat'],'">
<option value="0">',$lang['please_select'],'</option>';
echo add_listing_category(0,@$level,0);
echo '</select>';
?>

	</div>
</div>

<div class="control-group form-group rws">
	<div class="form-group">
		<label class="form-label text-dark"><?php echo $lang['website'];?></label>
		<input onClick="show_help('#help_website');" type="text" class="form-control " id="site" name="site" value="<?php echo @$_POST['site'];?>" placeholder="<?php echo $lang['enter'],' ',$lang['website'];?>" />
	<?php echo $help_website;?>
	</div>
</div>

<div class="control-group form-group rws">
	<div class="form-group">
		<label class="form-label text-dark"><?php echo $lang['mail_orders'];?></label>
		<input onClick="show_help('#help_email');" data-tab="first" type="email" class="form-control mand" id="mail" name="mail" value="<?php echo $_SESSION['customer_email'];?>" placeholder="<?php echo $lang['enter'];?>" />
	<?php echo $help_email;?>
	</div>
</div>



<!--tab2 -->
<div id="ab_com" class="ib w100 tac border-0 mt-4">
<h2 class="head ib pb-3 w100 bbd"><?php echo $lang['ab_com'];?></h2>
</div>
<div class="row mb-3 addmsg"><?php echo $lang['addmsg'];?></div>

	<div class="row mb-3">
	<div class="col-lg-4 col-md-12 mt-3 rws">
		<label class="form-label text-dark"><?php echo $lang['city'];?></label>
		<input onClick="show_help('#help_city');" data-tab="second" id="town" name="town" value="<?php echo $town;?>" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['city'];?>" />
		<?php echo $help_city;?>
	</div>
	
	<div class="col-lg-4 col-md-12 mt-3 rws">
		<label class="form-label text-dark"><?php echo $lang['area'];?></label>
		<input onClick="show_help('#help_area');" data-tab="second" id="area" name="area" value="<?php echo @$_POST['area'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'],' ',$lang['area'];?>" />
		<?php echo $help_area;?>
	</div>
	
	<div class="col-lg-4 col-md-12 mt-3 rws">
		<label class="form-label text-dark"><?php echo $lang['postcode'];?></label>
		<input id="postcode" name="postcode" value="<?php echo @$_POST['postcode'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'],' ',$lang['postcode'];?>" />
	</div>
	
	</div>
	
	
	<div class="row">
	<div class="col-lg-10 col-md-12 mt-3 rws">
		<label class="form-label text-dark"><?php echo $lang['address'];?></label>
		<input onClick="show_help('#help_address');" data-tab="second" id="company_address" name="company_address" value="<?php echo @$_POST['company_address'];?>" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['address'];?>" />
		<?php echo $help_address;?>
	</div>
	
	<div class="col-lg-2 col-md-12 mt-3 pr-0 pl-0">
	<a onclick="codeAddress();" class="btn btn-primary mt-4 mb-0"><?php echo $lang['get_coo'];?></a>
	</div>
	</div>
						
<div class="row mt-4">
<div class="col-sm-6">
<div class="form-group rws"><label class="form-label tal"><?php echo $lang['latitude'];?></label>
<input onClick="show_help('#help_lat');" class="form-control mt10 w90 mand" data-tab="second" type="text" id="latitude" name="latitude" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['latitude'];?>"/>
<?php echo $help_lat;?>
</div>
</div>

<div class="col-sm-6">
<div class="form-group rws"><label class="form-label tal"><?php echo $lang['longitude'];?></label>
<input onClick="show_help('#help_lng');" class="form-control mt10 w100 mand" data-tab="second" type="text" id="longitude" name="longitude" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['longitude'];?>"/>
<?php echo $help_lng;?>
</div>
</div>
</div>

<div class="w100 mt-4 mapholder">
<label class="form-label ib w100"><?php echo $lang['google_map'];?></label>
<div id="map" class="form-group rws"></div>
</div>

<div class="form-group rws mt-4">
<label class="form-label text-dark"><?php echo $lang['phone']; echo ' ( <small>',$lang['help_phone'],'</small> ) '?></label>
<input id="company_phones" name="company_phones" value="<?php echo @$_POST['company_phones'];?>" data-tab="second" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['phone'];?>"/>
</div>

<?php
if($_SESSION['selected_plan'] !="3"){
?>

<div class="form-group">
<label class="form-label text-dark"><?php echo $lang['workdays'];?></label>
<select name="work_days[]" id="work_days" class="form-control select2" data-placeholder="<?php echo $lang['ch_days'];?>" multiple>
<?php
if(!isset($_POST['work_days'])) echo '<option value="ev_day" Selected>',$lang['ev_day'],'</option>';
foreach($lang['days_arr'] as $daykey=>$day){
$selected='';
if(isset($_POST['work_days']) && in_array($daykey,$_POST['work_days'])) $selected='selected="selected"';
echo '<option ',$selected,' value="',$daykey,'">',$day,'</option>';
}
?>
</select>
</div>


<div class="row">
<div class="col-sm-6">
<div class="form-group">
<label class="form-label text-dark"><?php echo $lang['opening'];?></label>

<?php
$start = "00:00";
$end = "23:30";

$tStart = strtotime($start);
$tEnd = strtotime($end);
$tNow = $tStart;
echo '<select name="opening" id="opening" class="form-control select2" data-placeholder="',$lang['worktime'],'">';

while($tNow <= $tEnd){
$selected='';
if(isset($_POST['opening']) && $_POST['opening'] == date("H:i",$tNow)) $selected='selected="selected"';

echo '<option '.$selected.' value="'.date("H:i",$tNow).'">'.date("H:i",$tNow).'</option>';
$tNow = strtotime('+30 minutes',$tNow);
}
echo '</select>';
?>
</div>
</div>
<div class="col-sm-6">
<div class="form-group">
<label class="form-label text-dark"><?php echo $lang['closing'];?></label>
<?php
//$start = "08:00";
//$end = "07:30";

$tStart = strtotime($start);
$tEnd = strtotime($end);
$tNow = $tStart;
echo '<select name="closing" id="closing" class="form-control select2" data-placeholder="',$lang['worktime'],'">';
while($tNow <= $tEnd){
$selected='';
if(isset($_POST['closing']) && $_POST['closing'] == date("H:i",$tNow)) $selected='selected="selected"';
//}else{
//if(date("H:i",$tNow)=='18:00') $selected='selected="selected"';
//}
echo '<option '.$selected.' value="'.date("H:i",$tNow).'">'.date("H:i",$tNow).'</option>';
$tNow = strtotime('+30 minutes',$tNow);
}
echo '</select>';
?>	
</div>
</div>
</div>

<?php
}


$readonly = 'readonly';
$disabled = 'disabled';
$hlp = '- '.$lang['hlp'];
if($_SESSION['selected_plan'] > 1){
$readonly = '';
$disabled = '';
$hlp = '';
}
?>


<div class="form-group rws mt-4">
<label class="form-label text-dark"><?php echo $lang['description'];?></label>

<span class="label">
<span class="infosq"><?php echo $lang['desc_help'];?></span>
<label class="short"><?php echo $lang['total_words'];?></label><span id="display_count">0</span><label class="short"><?php echo $lang['words_left'];?></label>
<span id="word_left">50</span> <i class="fa fa-info-circle" aria-hidden="true"></i><br />
<span class="desc_msg"></span></span>
<textarea id="f9" onClick="show_help('#help_description');" class="form-control" name="description" rows="6" value="<?php echo @$_POST['description'];?>"><?php echo @$_POST['description'];?></textarea><?php echo $help_description;?>
</div>

<div class="form-group rws">
<label class="form-label text-dark"><?php echo $lang['keywords'];?></label>
<input onClick="show_help('#help_keywords');" maxlength="200" id="meta_keywords" name="meta_keywords" value="<?php echo @$_POST['meta_keywords'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'],' ',$lang['keywords'];?>" />
<?php echo $help_keywords;?>
</div>


<?php
if($_SESSION['selected_plan'] !="3"){
?>
<div class="form-group mt-4">
<label class="form-label text-dark"><?php echo $lang['p_meth'],' ( <small>',$lang['p_meth_a'],'</small> ) ';?></label>
<select id="pay_methods" name="pay_methods[]" class="form-control select2" data-placeholder="Choose Payment" multiple>
<?php
foreach($lang['pay_methods'] as $paykey=>$payway){
$selected='';
if(isset($_POST['pay_methods']) && in_array($paykey,$_POST['pay_methods'])) $selected='selected="selected"';
echo '<option ',$selected,' value="',$paykey,'">',$payway,'</option>';
}
?>
</select>
</div>
<?php
}
?>

<div class="form-group mt-4">
<label class="form-label text-dark"><?php echo $lang['certs'];?></label>
<select id="certificates" name="certificates[]" class="form-control select_with_new" data-placeholder="Choose Certification" multiple>
<?php
foreach($lang['certs_arr'] as $certkey=>$cert){
$selected='';
if(isset($_POST['certificates']) && in_array($certkey,$_POST['certificates'])) $selected='selected="selected"';
echo '<option ',$selected,' value="',$certkey,'">',$cert,'</option>';
}
?>
</select>
</div>
<?php

$hlp = '- '.$lang['hlp'];
if($_SESSION['selected_plan'] > 1){
$hlp = '';
}
if($_SESSION['selected_plan'] > 1){
?>		

<div class="form-group row clearfix mt-5 mb-5">
<div class="col-lg-12 <?php if($_SESSION['selected_plan'] == 3) echo 'dn';?>">
<div class="checkbox checkbox-info">
<label class="custom-control mt-4 custom-checkbox">
<input <?php echo $disabled;?> name="sellbusiness" id="sellbusiness"  <?php if(isset($_POST['sellbusiness']) || $_SESSION['selected_plan'] == 3) echo 'checked';?> value="1" type="checkbox" class="custom-control-input" />
<span class="custom-control-label text-dark pl-2"><?php echo $lang['sellbusiness'],' ',$hlp;?></span>
</label>
</div>
</div>

<div class="form-group rws">
<label class="form-label text-dark"><?php echo $lang['business_price']; echo ' ( <small>',$lang['business_price_sm'],'</small> ) '?></label>
<div class="col-lg-4 col-md-12 mt-3 mb-3 ib">
<input <?php if($_SESSION['selected_plan'] < 3) echo 'readonly';?> id="business_price" name="business_price" value="<?php echo @$_POST['business_price'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'];?>"/>
<span class="ib addon2 ml-2"><?php echo $lang['bgn'];?></span>
</div>
</div>
</div>
<?php
if($_SESSION['selected_plan'] == 2){
?>
<h4 class="mt-5 mb-4"><?php echo $lang['social'],' ',$hlp;?></h4>
<div class="input-group mb-4">
<div class="input-group-prepend">
<div class="input-group-text w-7">
<i class="fa fa-facebook tx-16 lh-0 op-6 text-center mx-auto"></i>
</div>
</div><!-- input-group-prepend -->
<input <?php echo $readonly;?> name="facebook" id="facebook" value="<?php echo @$_POST['facebook'];?>" class="form-control" placeholder="Facebook URL" type="text"/>
</div>
<div class="input-group mb-4">
<div class="input-group-prepend">
<div class="input-group-text w-7">
<i class="fa fa-twitter tx-16 lh-0 op-6 text-center mx-auto"></i>
</div>
</div><!-- input-group-prepend -->
<input <?php echo $readonly;?> name="twitter" id="twitter" value="<?php echo @$_POST['twitter'];?>" class="form-control" placeholder="Twitter URL" type="text"/>
</div>
<div class="input-group mb-4">
<div class="input-group-prepend">
<div class="input-group-text w-7">
<i class="fa fa-linkedin tx-16 lh-0 op-6 text-center mx-auto"></i>
</div>
</div>
<input <?php echo $readonly;?> name="linkedin" id="linkedin" value="<?php echo @$_POST['linkedin'];?>" class="form-control" placeholder="Linkedin URL" type="text"/>
</div>

<div class="input-group mb-4">
<div class="input-group-prepend">
<div class="input-group-text w-7">
<i class="fa fa-instagram tx-16 lh-0 op-6 text-center mx-auto"></i>
</div>
</div>
<input <?php echo $readonly;?> name="instagram" id="instagram" value="<?php echo @$_POST['instagram'];?>" class="form-control" placeholder="Instagram URL" type="text"/>
</div>
<?php
	}
}
?>
	
<!--tab2 end-->

<!--tab3-->

<div id="images" class="ib w100 tac border-0 mt-4">
<h2 class="ib head w100 bbd"><?php echo $lang['images'];?></h2>
</div>
<div class="form-group mb-5">
<div class="msg imgmsg mt-3 mb-3"></div>
<label class="form-label text-dark"><?php echo $lang['company_images'],' - ',$lang['you_can'],' ',$lang['images_num'][$_SESSION['selected_plan']],' ',$lang['images'];?></label>
<div class="custom-file">
<input multiple onchange="previewImages(this,<?php echo $lang['images_num'][$_SESSION['selected_plan']];?>);" data-num="<?php echo $lang['images_num'][$_SESSION['selected_plan']];?>" id="advert_images" type="file" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input" name="images[]"/>
<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>
</div>
<div id="img_preview" class="mb-5"></div>
</div>

<!--
<div class="form-group mb-3 mt-5">
<label class="form-label text-dark"><!--?php echo $lang['company_images'],' - ',$lang['you_can'],' ',$lang['images_num'][$_SESSION['selected_plan']],' ',$lang['images'];?></label>
<input data-num="<!--?php echo $lang['images_num'][$_SESSION['selected_plan']];?>" id="adv_images" type="file" name="files" accept=".jpg, .png, image/jpeg, image/png" multiple />
</div>-->



<!--tab3 end-->

<!--tab4-->

<!--tab4 end-->

<!--tab5-->
<div id="paymnt" class="ib w100 tac border-0 mt-4">
<h2 class="ib head pb-3 w100 bbd"><?php echo $lang['paymnt'];?></h2>
</div>

<div class="tac ib w100 vat mt20 pln">
<span class="label bbd tac ib w100"><h4><?php echo $lang['inv_details'];?>:</h4></span>
<div class="control-group form-group tal">
<label class="form-label tal"><?php echo $lang['supplier_name'];?></label>
<input data-tab="first" class="form-control mt10 w98 mand" type="text" id="company_name" name="company_name" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['company_name'];?>"/>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['bulstat'];?></label>
<input data-tab="first" class="form-control mt10 w90 mand" type="text" id="bulstat" name="bulstat" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['bulstat'];?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['vat'];?></label>
<input class="form-control mt10 w100" type="text" id="dds" name="dds" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['dds'];?>"/>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['city'];?></label>
<input class="form-control mt10 w90 mand" data-tab="first" type="text" id="inv_city" name="inv_city" placeholder="<?php echo $lang['enter'];?>" value="<?php echo $city;?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['address'];?></label>
<input data-tab="first" class="form-control mt10 w100 mand" type="text" id="inv_address" name="inv_address" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['inv_address'];?>"/>
</div>
</div>
</div>

<div class="tac ib w100 vat">
<div class="control-group form-group"><label class="form-label tal"><?php echo $lang['mol'];?></label>
<input data-tab="first" class="form-control mt10 w100 mand" type="text" id="mol" name="mol" placeholder="<?php echo $lang['enter'];?>" value="<?php echo $mol;?>"/>
</div>
</div>
</div>
<div class="panel panel-secondary">
<div class=" tab-menu-heading border-0 pl-0 pr-0 pt-0">
<h4 class="mt-2 mb-4"><?php echo $lang['how_pay'];?></h4>
<div class="tabs-menu1 ">
<!-- Tabs -->
<ul class="nav panel-tabs">
<li><a href="#tab5" onclick="set_pay('<?php echo $lang['c_bank'];?>','c_bank');" class="active" data-toggle="tab"><?php echo $lang['pay_methods']['bank'];?></a></li>
<li><a href="#tab6" onclick="set_pay('<?php echo $lang['c_easy'];?>','c_easy');" data-toggle="tab"><?php echo $lang['pay_methods']['easypay'];?></a></li>
<!--<li><a href="#tab7" onclick="set_pay('<--?php echo $lang['c_card'];?>','c_card');" data-toggle="tab"><--?php echo $lang['pay_methods']['card'];?></a></li>-->
</ul>
</div>
</div>
<div class="panel-body tabs-menu-body pl-0 pr-0 border-0">
<div class="tab-content">
<div class="tab-pane active " id="tab5">
<h4 class="mt-3 mb-4"><?php echo $lang['bank_txt'];?></h4>
<div class="form-group">
<label class="form-label" ><?php echo $lang['supplier_name'];?></label>
<input disabled="" class="form-control" value="<?php echo $lang['our_c_name'];?>"/>
</div>

<div class="form-group">
<label class="form-label" ><?php echo $lang['prib'];?></label>
<input disabled="" class="form-control" value="Първа инвестиционна банка АД"/>
</div>
											
<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal">IBAN</label>
<input disabled="" class="form-control mt10 w90" type="text" value="<?php echo $lang['our_c_iban'];?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal">BIC</label>
<input disabled="" class="form-control mt10 w100" type="text" value="FINVBGSF"/>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['osn'];?></label>
<input disabled="" class="form-control mt10 w90" type="text" value="<?php echo $lang['reg_txt'],' - ',$lang['plans_array'][$_SESSION['selected_plan']],' | ',date('Y'),' ',$lang['y'];?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['sum'];?></label>
<input disabled="" class="form-control mt10 w100" type="text" value="<?php echo $lang['plans_prices'][$_SESSION['selected_plan']],' ',current_currency;?>"/>
</div>
</div>
</div>
</div>
										
<div class="tab-pane " id="tab6">
	<h6 class="font-weight-semibold"><?php echo $lang['easypay_txt'];?></h6>
<div class="form-group">
<label class="form-label" ><?php echo $lang['supplier_name'];?></label>
<input disabled="" class="form-control" value="<?php echo $lang['our_c_name'];?>"/>
</div>

<div class="form-group">
<label class="form-label" ><?php echo $lang['prib'];?></label>
<input disabled="" class="form-control" value="Първа инвестиционна банка АД"/>
</div>
											
<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal">IBAN</label>
<input disabled="" class="form-control mt10 w90" type="text" value="<?php echo $lang['our_c_iban'];?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal">BIC</label>
<input disabled="" class="form-control mt10 w100" type="text" value="FINVBGSF"/>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['osn'];?></label>
<input disabled="" class="form-control mt10 w90" type="text" value="<?php echo $lang['reg_txt'],' - ',$lang['plans_array'][$_SESSION['selected_plan']],' | ',date('Y'),' ',$lang['y'];?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['sum'];?></label>
<input disabled="" class="form-control mt10 w100" type="text" value="<?php echo $lang['plans_prices'][$_SESSION['selected_plan']],' ',current_currency;?>"/>
</div>
</div>
</div>
<p class="mb-0"><?php echo $lang['easypay_txt2'];?></p>
</div>

<!--<div class="tab-pane " id="tab7">
	<div class="control-group form-group">
		<div class="form-group">
			<label class="form-label text-dark"><--?php echo $lang['paypal_txt2'];?></label>
			
		</div>
	</div>
</div>-->
										
									</div>
								</div>
							</div>
							
							<div id="paymentdiv" class="form-group row clearfix">
								<div class="col-lg-12">
									<div class="checkbox checkbox-info">
										<label class="custom-control mt-4 custom-checkbox">
											<input id="payment" name="payment" <?php if(isset($_POST['payment'])) echo 'checked';?> type="checkbox" value="c_bank" class="custom-control-input" />
											<span id="payment_txt" class="custom-control-label text-dark pl-2"><?php echo $lang['c_bank'];?></span>
										</label>
									</div>
								</div>
							</div>
							
							<div class="form-group row clearfix">
								<div class="col-lg-12">
									<div class="checkbox checkbox-info">
										<label class="custom-control mt-4 custom-checkbox">
											<input type="checkbox" id="terms" <?php if(isset($_POST['terms'])) echo 'checked';?> name="terms" value="1" class="custom-control-input" />
											<span id="termslbl" class="custom-control-label text-dark pl-2"><?php echo $lang['terms']?></span>
										</label>
									</div>
								</div>
							</div>
							<ul class="list-inline wizard mb-0 mt-4 px-4 py-4 bg-light border-top">
							<li id="saveadvert" class="next tac"><button onclick="return aa();" type="submit" id="save_advert" class="btn btn-secondary mb-0" name="save_advert" value="save"><?php echo $lang['reg_f'];?></button></li>
						</ul>
<!--tab5 end-->
</div>

						
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
					<div class="col-xl-4">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['u_ch'];?></h3>
							</div>
							<div class="card-body p-0">
								<ul class="list-unstyled widget-spec mb-0">
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['selected_plan'],': ',$lang['plans_array'][$_SESSION['selected_plan']];?>
									</li>
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['prd_price'],' € ',$lang['plans_prices'][$_SESSION['selected_plan']];?> 
									</li>
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['dura'],': ',$lang['plans_lenght'][$_SESSION['selected_plan']];?>
									</li>
								</ul>
							</div>
						</div>
						<div class="card">
					<div class="banner"><img src="../images/banners/ban.jpg"/></div>
					</div>
					
						<div class="card mb-0 overflow-hidden">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['benef_pre'];?></h3>
							</div>
							<div class="card-body p-0">
								<ul class="list-unstyled widget-spec mb-0">
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['ben1'];?>
									</li>
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['ben2'];?>
									</li>
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['ben3'];?>
									</li>
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['ben4'];?>
									</li>
									
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['profile_visits_statistics'];?>
									</li>
									
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['more_seo'];?>
									</li>
									
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['google_map'];?>
									</li>
									
									<?php
									if($_SESSION['selected_plan'] == 1){
									echo '<li class="tac">
										<a class="btn btn-secondary ib ma" href="../redirector.php?selected_plan=2">',$lang['become_premium_btn'],'</a>
									</li>';
									}
									
									
									if($_SESSION['selected_plan'] == 1){
									?>
									<li class="ml-5 mb-0">
										<a target="_blank" href="../select-plan/"> <?php echo $lang['read'];?>..</a>
									</li>
									<?php
									}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div> <!-- end row -->
			</div>
		</section>
<?php
include '../modules/footer.php';
?>	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<script src="../assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="../assets/js/fullfunctions.js"></script>
<script src="../../assets/js/jquery-ui.js"></script>

<script src="../../assets/plugins/fancyuploder/fancy_uploader_full.js"></script>
<script src="../assets/js/contacts.js"></script>
<!--<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>-->
<script src="https://cdn.tiny.cloud/1/1gk9wazgim3qrlmipqqxzu36f4m5tt469qzmcy5lkvz5wg48/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo google_api;?>"></script>
<script src="../assets/js/add_listings.js?t=<?php echo time();?>"></script>
<script>
var keywords = <?php echo json_encode($cities);?>;
$('#town').autocomplete({
//source: [keywords],
	source: keywords,
	minLength: 1,
	select: function(event, ui) {
	event.preventDefault();
	//console.log(ui.item.label);
	$("#town").val(ui.item.label);
  }
});



	</body>
</html>
<?php
}else{
header("location: ../select-plan/");
exit(0);
}


}else{
header("location: ../index.php");
exit(0);
}
?>