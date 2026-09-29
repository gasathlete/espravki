<?php
session_start();
require '../config.php';
require '../project_functions.php';

require '../translation/'.$_SESSION['lang'].'_lang.php';


if(isset($_SESSION['customer']) && !empty($_SESSION['advert_id'])){

$ch = 'select * from adverts where customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'" and id = "'.mysql_real_escape_string($_SESSION['advert_id']).'" ';
$chresult=mysql_query($ch) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$ch);
$check_num_rows=mysql_num_rows($chresult);

if($check_num_rows < 1){
header("location: ../index.php");
exit(0);
}else{
$advertrow = mysql_fetch_assoc($chresult);

}


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

//var_dump($_POST);
//exit();
if(isset($_POST['save_advert'])){


$selected_pay_way = '';
if($advertrow['paid'] < 1) $selected_pay_way = "selected_pay_way";

$mand_fields = array("supplier_name", "business_type", "mail", "company_name", "bulstat", "inv_city", "inv_address", "mol", "town", "company_address", "latitude", "longitude", "company_phones", "description", "bulstat", $selected_pay_way);

$_POST['company_phones'] = str_replace(array('/','(',')'),'',trim($_POST['company_phones']));
foreach($mand_fields as $fild){
$_POST[$fild] = str_replace('"',"'",$_POST[$fild]);
if(!empty($fild)){
if(isset($_POST[$fild]) && empty($_POST[$fild])) $msg = $lang['enter'].' '.$lang[$fild].'-'.$fild;//.'-'.$fild

		}
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

foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
if(!empty($tmp_name)){
list($width, $height, $type, $attr) = getimagesize($_FILES["images"]["tmp_name"][$key]);
if (!isset($type) || !in_array($type, array(
    IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
 $msg = $lang['UNSUPPORTED_FILE_TYPE'].' '.$_FILES["images"]["name"][$key];
}

if($_FILES["images"]['type'][$key]=='image/jpeg' || $_FILES["images"]['type'][$key]=='image/pjpeg' || $_FILES["images"]['type'][$key]=='image/jpg' || $_FILES["images"]['type'][$key]=='image/JPEG' || $_FILES["images"]['type'][$key]=='image/png'){
			if(exif_imagetype($_FILES["images"]["tmp_name"][$key])===FALSE){
			$msg=$_FILES["images"]['name'][$key].': '.$lang['UNSUPPORTED_FILE_TYPE'];
			}//else echo exif_imagetype($_FILES["images"]["tmp_name"][$key]);
			}else $msg=$_FILES["images"]['name'][$key].': '.$lang['UNSUPPORTED_FILE_TYPE'];
		}
	}
}
//var_dump($_FILES["images"]);
//exit();

if(!empty($_FILES['logo']['name'])){
if(!empty($_FILES["logo"]["tmp_name"])){
list($width, $height, $type, $attr) = getimagesize($_FILES["logo"]["tmp_name"]);
if (!isset($type) || !in_array($type, array(
    IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
 $msg = $lang['UNSUPPORTED_FILE_TYPE'].' '.$_FILES["logo"]["name"];
}
if($_FILES["logo"]['type']=='image/jpeg' || $_FILES["logo"]['type']=='image/pjpeg' || $_FILES["logo"]['type']=='image/jpg' || $_FILES["logo"]['type']=='image/JPEG' || $_FILES["logo"]['type']=='image/png'){
			if(exif_imagetype($_FILES["logo"]["tmp_name"])===FALSE){
			$msg=$_FILES["logo"]['name'].': '.$lang['UNSUPPORTED_FILE_TYPE'];
			}//else echo exif_imagetype($_FILES["logo"]["tmp_name"]);
			}else $msg=$_FILES["logo"]['name'].': '.$lang['UNSUPPORTED_FILE_TYPE'];
		}

}


//exit();		
if(empty($msg)){
$max_offers = $lang['offers_num'][$_SESSION['selected_plan']];
$work_days = serialize($_POST['work_days']);
$pay_methods = serialize($_POST['pay_methods']);
$certificates = serialize($_POST['certificates']);

if(isset($_POST['del_logo'])){
if(strpos($_POST['del_logo'], '-'.$_SESSION['advert_id'].'.') !== false) {
//echo 'ima';
if(file_exists('../images/adverts/'.$_POST['del_logo'])) unlink('../images/adverts/'.$_POST['del_logo']);
$logoname = '';
$updatelogo = 'update adverts set logo = "" where id="'.mysql_real_escape_string($_SESSION['advert_id']).'" ';
$updatelogoresult=mysql_query($updatelogo) or die(send_error($updatelogo,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
	}
}


if(!empty($_FILES['logo']['name'])){
$logoname = create_url('logo_'.$_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode']).'-'.$_SESSION['advert_id'].'.jpg';
copy_and_resize_image_new($_FILES["logo"]["tmp_name"],$_FILES["logo"]["type"],$logoname,400,400,'adverts');
$updatelogo2 = 'update adverts set logo="'.mysql_real_escape_string(strip_tags($logoname)).'" where id="'.mysql_real_escape_string($_SESSION['advert_id']).'" ';
$updatelogo2result=mysql_query($updatelogo2) or die(send_error($updatelogo2,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
}


if(isset($_POST['opening'])) $opening = $_POST['opening'];
else $opening = '00:00';

if(isset($_POST['closing'])) $closing = $_POST['closing'];
else $closing = '00:00';

$update = 'update adverts set 
customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'",
supplier_name="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['supplier_name']))).'",
description="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['description'],'<p><span><div><h1><h2><h3><h4><h5><br><br/><ul><li>'))).'",
meta_title="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['supplier_name']))).'",
meta_keywords="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['meta_keywords']))).'",
latitude="'.mysql_real_escape_string(strip_tags($_POST['latitude'])).'",
longitude="'.mysql_real_escape_string(strip_tags($_POST['longitude'])).'",
town="'.mysql_real_escape_string(strip_tags($_POST['town'])).'",
area="'.mysql_real_escape_string(strip_tags($_POST['area'])).'",
postcode="'.mysql_real_escape_string(strip_tags($_POST['postcode'])).'",
company_address="'.mysql_real_escape_string(strip_tags($_POST['company_address'])).'",
country="Bulgaria",
country_code="BG",
company_phones="'.mysql_real_escape_string(strip_tags($_POST['company_phones'])).'",
mail="'.mysql_real_escape_string(strip_tags($_POST['mail'])).'",
site="'.mysql_real_escape_string(strip_tags($_POST['site'])).'",
business_type="'.mysql_real_escape_string(strip_tags($_POST['business_type'])).'",
max_offers = "'.mysql_real_escape_string(strip_tags($max_offers)).'",
selected_plan = "'.mysql_real_escape_string(strip_tags($_SESSION['selected_plan'])).'",
selected_pay_way = "'.mysql_real_escape_string(strip_tags($_POST['selected_pay_way'])).'",
work_days = "'.mysql_real_escape_string($work_days).'",
pay_methods = "'.mysql_real_escape_string($pay_methods).'",
certificates = "'.mysql_real_escape_string($certificates).'",
opening="'.mysql_real_escape_string(strip_tags($opening)).'",
closing="'.mysql_real_escape_string(strip_tags($closing)).'",
company_name = "'.mysql_real_escape_string(strip_tags($_POST['company_name'])).'",
bulstat = "'.mysql_real_escape_string(strip_tags($_POST['bulstat'])).'",
dds = "'.mysql_real_escape_string(strip_tags($_POST['dds'])).'",
inv_city = "'.mysql_real_escape_string(strip_tags($_POST['inv_city'])).'",
inv_address = "'.mysql_real_escape_string(strip_tags($_POST['inv_address'])).'",
mol = "'.mysql_real_escape_string(strip_tags($_POST['mol'])).'",
video = "'.mysql_real_escape_string(strip_tags($_POST['video'])).'"';

if($_SESSION['selected_plan'] == 2){
$update.='
,facebook = "'.mysql_real_escape_string(strip_tags($_POST['facebook'])).'",
twitter = "'.mysql_real_escape_string(strip_tags($_POST['twitter'])).'",
linkedin = "'.mysql_real_escape_string(strip_tags($_POST['linkedin'])).'",
instagram = "'.mysql_real_escape_string(strip_tags($_POST['instagram'])).'"';
}

if($_SESSION['selected_plan'] > 1 && $_POST['business_price'] > 0){
$update.=',sellbusiness = "1",business_price = "'.mysql_real_escape_string(strip_tags($_POST['business_price'])).'"';
}
$update.=' where id="'.mysql_real_escape_string($_SESSION['advert_id']).'"';
//echo $update;
$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	

		
		$imagecounter = 1;
		if(!empty($_FILES['images']['name'][0])){
		
		$maxnum = 0;
		$max = 'SELECT MAX(order_id) FROM adverts_gallery where advert_id = "'.mysql_real_escape_string($_SESSION['advert_id']).'" ';
		$maxresult=mysql_query($max) or die(send_error($max,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$maxrow = mysql_fetch_row($maxresult);
		$maxnum = $maxrow[0];
		
		foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
		if(!empty($tmp_name)){
		if($imagecounter <= $lang['images_num'][$_SESSION['selected_plan']]){
		$imagecounter++;
		$maxnum++;
		$save_file_name = create_url($_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode'].'-'.($maxnum)).'-'.$_SESSION['advert_id'].'.jpg';
		$smallfilename = create_url($_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode']).'-small-'.$_SESSION['advert_id'].'.jpg';

		
		//if(($key+1) == 1){
		if($maxnum == 1){
		$update = 'update adverts set small_image = "'.$smallfilename.'" where id = "'.mysql_real_escape_string($_SESSION['advert_id']).'"';
		$updateresult=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$smallfilename,400,400,'adverts');
		}
		
		$insertimages = 'insert into adverts_gallery set image = "'.mysql_real_escape_string($save_file_name).'", advert_id = "'.mysql_real_escape_string($_SESSION['advert_id']).'", order_id = "'.mysql_real_escape_string($maxnum).'"';
		$insertimagesresult=mysql_query($insertimages) or die(send_error($insertimages,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$save_file_name,1920,1200,'adverts');		
					}
				}
			}
		}
		
		$subject = 'Obnowen profil w '.official_site_name.'';
		$body = 'Zdrasti Marianski,<br />Klient '.$_POST['supplier_name'].' redaktira listing '.$_POST['company_name'].' s ID '.$_SESSION['advert_id'].'<br/>
Ako iskash moze da go poglednesh! Ako iskash moze da go poglednesh!Ako iskash moze da go poglednesh. Ako iskash moze da go pogledneshAko iskash moze da go poglednesh! Ako iskash moze da go poglednesh!Ako iskash moze da go poglednesh. Ako iskash moze da go poglednesh';
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
		
		$msg = $lang['suc_save'];
		header("location: ../editbusiness/index.php?msg=1");
		exit(0);
	}	
}


if(empty($msg) || $msg == $lang['suc_save']){
$check = 'select * from adverts where customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'" and id = "'.mysql_real_escape_string($_SESSION['advert_id']).'" ';
$checkresult=mysql_query($check) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$check);

while($checkrow = mysql_fetch_assoc($checkresult)){
foreach($checkrow as $checkkey => $checkvalue) {

         //echo "$checkkey = $checkvalue <br />";
		 if($checkkey == 'selected_plan') $_SESSION['selected_plan'] = $checkvalue;
		 elseif($checkkey == 'work_days') $_POST['work_days'] = unserialize($checkvalue);
		 elseif($checkkey == 'pay_methods') $_POST['pay_methods'] = unserialize($checkvalue);
		 elseif($checkkey == 'certificates') $_POST['certificates'] = unserialize($checkvalue);
		 else $_POST[$checkkey] = $checkvalue;
     }
}
$categories = get_advert_categories($_SESSION['advert_id']);
//echo $_POST['dds'],'aaa';
$advert_images = get_advert_images($_SESSION['advert_id']);
//var_dump($advert_images);

$status = '<i class="fa fa-times text-danger ml-2 mr-2"></i>'.$lang['active_status'][$_POST['active']];
if($_POST['active'] > 0) $status = '<i class="fa fa-check text-success ml-2 mr-2"></i>'.$lang['active_status'][$_POST['active']];

$statuspaid = '<i class="fa fa-times text-danger ml-2 mr-2"></i>'.$lang['pay_status'][$_POST['paid']];
if($_POST['paid'] > 0) $statuspaid = '<i class="fa fa-check text-success ml-2 mr-2"></i>'.$lang['pay_status'][$_POST['paid']];

}

//echo $_POST['inv_city'],'aaaaaa';
if(!isset($_POST['inv_city']) || empty($_POST['inv_city'])) $city = $_SESSION['city'];
else $city = $_POST['inv_city'];

if(!isset($_POST['town']) || empty($_POST['town'])) $town = $_SESSION['city'];
else $town = $_POST['town'];


if(!isset($_POST['mol']) || empty($_POST['mol'])) $mol = $_SESSION['customer_names'];
else $mol = $_POST['mol'];

if(isset($_GET['msg'])){
if($_GET['msg'] == 'max_num_reached') $msg = $lang['max_num_reached'];
else $msg = $lang['suc_save'];
}

//echo $_SERVER['HTTP_REFERER'];
//echo WebSite.'/addbusiness/';
if($_SERVER['HTTP_REFERER'] == WebSite.'/addbusiness/') $msg = $lang['svd_pay'];

if($_POST['paid'] < 1) $msg.= '<span class="ib w100 tac">'.$lang['wait_pay'].'</span>';


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
								<h3 class="card-title"><?php echo $lang['e_lst'],' - ',$lang['plans_array'][$_SESSION['selected_plan']],' | ',$advertrow['amount'],' ',current_currency,'   <span class="ml-4">',$lang['status'],': ',$status,'</span>';?></h3>
							</div>

							<div class="card-body p-2">
								
									<div id="rootwizard" class="border pt-0">
										<ul class="nav nav-tabs nav-justified bbd">
											<li class="nav-item ib"><a id="firsttab" href="#first" data-toggle="tab" class="nav-link font-bold active"><?php echo $lang['base_info'];?></a></li>
											<li class="nav-item ib"><a href="#ab_com" class="nav-link font-bold sublink"><?php echo $lang['ab_com'];?></a></li>
											<li class="nav-item ib"><a href="#images" class="nav-link font-bold sublink"><?php echo $lang['images'];?></a></li>
											<li class="nav-item ib"><a href="#paymnt" class="nav-link font-bold sublink"><?php echo $lang['paymnt'];?></a></li>
											<li class="nav-item ib"><a id="fourthtab" href="#fourth" data-toggle="tab" class="nav-link font-bold"><?php echo $lang['of_pr'];?></a></li>
										</ul>
<div class="tab-content mb-0 b-0">
	<div class="tab-pane fade card-body active show" id="first">
<form id="advertForm" action="../editbusiness/index.php" name="advertForm" method="post" enctype="multipart/form-data" class="form-horizontal mb-0">
<input type="hidden" disabled name="aid" id="aid" value="<?php echo $_SESSION['advert_id'];?>"/>
								
<div class="form-group mb-5">
<label class="form-label text-dark"><?php echo $lang['company_logo'];?></label>
<?php
if(!empty($_POST['logo'])){
echo '
<div class="logo_preview pr">
<img src="../images/adverts/',$_POST['logo'],'" alt="',$lang['company_logo'],' ',$_POST['supplier_name'],'"/>
<div class="checkbox checkbox-info">
<label class="custom-control custom-checkbox">
<input type="checkbox" name="del_logo" value="',$_POST['logo'],'"/>
<span class="custom-control-label text-dark pl-2">',$lang['delete_picture'],'</span>
</label>
</div>
</div>
';
}else{
?>
<div class="custom-file">
<input data-num="1" id="advert_logo" type="file" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input" name="logo"/>
<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>
</div>
<?php
}
?>
</div>
<!--<hr class="mb-1"/>-->

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
		<label class="form-label text-dark"><?php echo $lang['cats'],' ( <small>',$lang['cats_cant'],'</small> ) ';?></label>
		<?php
		if(!empty($categories)){
		foreach($categories as $category) echo '<div class="col-lg-6 col-md-12 mt-3 rws"><i class="fa fa-check text-success mr-3" aria-hidden="true"></i>',$category,'</div>';
		}
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
		<input data-tab="first" onClick="show_help('#help_email');" type="email" class="form-control mand" id="mail" name="mail" value="<?php echo $_SESSION['customer_email'];?>" placeholder="<?php echo $lang['enter'];?>" />
	<?php echo $help_email;?>
	</div>
</div>



<!--tab2-->
<div id="ab_com" class="ib w100 tac border-0 mt-4">
<h2 class="head ib pb-3 w100 bbd"><?php echo $lang['ab_com'];?></h2>
</div>
<div class="row mb-3 addmsg"><?php echo $lang['addmsg'];?></div>
											
		<div class="row mb-3">
		<div class="col-lg-4 col-md-12 mt-3 rws">
			<label class="form-label text-dark"><?php echo $lang['city'];?></label>
			<input data-tab="second" onClick="show_help('#help_city');" id="town" name="town" value="<?php echo $town;?>" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['city'];?>" />
			<?php echo $help_city;?>
		</div>
		
		<div class="col-lg-4 col-md-12 mt-3 rws">
			<label class="form-label text-dark"><?php echo $lang['area'];?></label>
			<input data-tab="second" onClick="show_help('#help_area');" id="area" name="area" value="<?php echo @$_POST['area'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'],' ',$lang['area'];?>" />
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
			<input data-tab="second" onClick="show_help('#help_address');" id="company_address" name="company_address" value="<?php echo @$_POST['company_address'];?>" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['address'];?>" />
			<?php echo $help_address;?>
		</div>
		
		<div class="col-lg-2 col-md-12 mt-3 pr-0 pl-0">
		<a onclick="codeAddress();" class="btn btn-primary mt-4 mb-0"><?php echo $lang['get_coo'];?></a>
		</div>
		</div>
												
<div class="row mt-4">
<div class="col-sm-6">
<div class="form-group rws"><label class="form-label tal"><?php echo $lang['latitude'];?></label>
<input data-tab="second" onClick="show_help('#help_lat');" class="form-control mt10 w90 mand" type="text" id="latitude" name="latitude" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['latitude'];?>"/>
<?php echo $help_lat;?>
</div>
</div>

<div class="col-sm-6">
<div class="form-group rws"><label class="form-label tal"><?php echo $lang['longitude'];?></label>
<input data-tab="second" onClick="show_help('#help_lng');" class="form-control mt10 w100 mand" type="text" id="longitude" name="longitude" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['longitude'];?>"/>
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
<input data-tab="second" id="company_phones" name="company_phones" value="<?php echo @$_POST['company_phones'];?>" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['phone'];?>"/>
</div>
<div class="form-group">
<label class="form-label text-dark"><?php echo $lang['workdays'];?></label>
<select name="work_days[]" id="work_days" class="form-control select2" data-placeholder="<?php echo $lang['ch_days'];?>" multiple>
<?php
if(!isset($_POST['work_days']) or empty($_POST['work_days'])) echo '<option value="ev_day" Selected>',$lang['ev_day'],'</option>';
foreach($lang['days_arr'] as $daykey=>$day){
$selected='';
if(isset($_POST['work_days']) && !empty($_POST['work_days']) && in_array($daykey,$_POST['work_days'])) $selected='selected="selected"';
if(isset($_POST['work_days']) && !empty($_POST['work_days']) && in_array('ev_day',$_POST['work_days'])) $selected='selected="selected"';
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

		

<div class="form-group mt-4">
<label class="form-label text-dark"><?php echo $lang['p_meth'],' ( <small>',$lang['p_meth_a'],'</small> ) ';?></label>
<input type="hidden" id="fnum" value="<?php echo $lang['images_num'][$_SESSION['selected_plan']];?>"/>
<select id="pay_methods" name="pay_methods[]" class="form-control select2" data-placeholder="Choose Payment" multiple>
<?php
foreach($lang['pay_methods'] as $paykey=>$payway){
$selected='';
if(isset($_POST['pay_methods']) && !empty($_POST['pay_methods']) && in_array($paykey,$_POST['pay_methods'])) $selected='selected="selected"';
echo '<option ',$selected,' value="',$paykey,'">',$payway,'</option>';
}
?>
</select>
</div>

<div class="form-group mt-4">
<label class="form-label text-dark"><?php echo $lang['certs'];?></label>
<select id="certificates" name="certificates[]" class="form-control select_with_new" data-placeholder="Choose Certification" multiple>
<?php
foreach($lang['certs_arr'] as $certkey=>$cert){
$selected='';
if(isset($_POST['certificates']) && !empty($_POST['certificates']) && in_array($certkey,$_POST['certificates'])) $selected='selected="selected"';
echo '<option ',$selected,' value="',$certkey,'">',$cert,'</option>';
}
?>
</select>
</div>
<?php

$hlp = '- '.$lang['hlp'];
if($_SESSION['selected_plan'] == 2){
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
<!--end tab2-->


<!--start tab3-->

<div id="images" class="ib w100 tac border-0 mt-4">
<h2 class="ib head w100 bbd"><?php echo $lang['images'];?></h2>
</div>
<div class="form-group mb-5">
<div class="msg imgmsg mb-3"></div>
<label class="form-label text-dark"><?php echo $lang['company_images'],' - ',$lang['you_can'],' ',$lang['images_num'][$_SESSION['selected_plan']],' ',$lang['images'];?></label>
<small class="ib w100"><?php echo $lang['hold_ctrl'];?></small>
<div class="custom-file">
<?php
if(count($advert_images) < $lang['images_num'][$_SESSION['selected_plan']]){
?>
<input multiple onchange="previewImages(this,<?php echo $lang['images_num'][$_SESSION['selected_plan']];?>);" data-num="<?php echo $lang['images_num'][$_SESSION['selected_plan']];?>" id="advert_images" type="file" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input" name="images[]"/>
<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>

<?php
}
?>
</div>
<div id="img_preview" class="mb-5">
<?php
if(!empty($advert_images)){
foreach($advert_images as $imgkey=>$image){
$mainimg = '';
if($image['ordering'] == 1) $mainimg = '<div title="'.$lang['main_image_txt'].'" class="arrow-ribbon bg-success">'.$lang['main_image'].'</div>';
echo '<div class="imgdiv">'.$mainimg.'<img class="thumbnail" alt="'.$_POST['supplier_name'].'" src="../images/adverts/',$image['image'],'"/>
<span class="fileinfo">
<label class="form-label text-dark">',$lang['ordering'],'</label>
<input min="1" type="number" class="form-control ordering" data-id="'.$imgkey.'" value="',$image['ordering'],'"/></span>
<button class="remove_file" value="'.$imgkey.'" type="button" title="',$lang['delete_picture'],'"></button></div>';
}
}
?>

</div>
</div>

<div class="ib w100 tac border-0 mt-4 mb-4">
<h2 class="ib head w100 bbd"><?php echo $lang['video'];?></h2>
<small class="ib w100"><?php echo $lang['video_sm'];?></small>
<div class="form-group tal mt-3">
	<label class="form-label text-dark"><?php echo $lang['videol'];?></label>
	<input data-tab="first" type="text" class="form-control" id="video" name="video" value="<?php echo @$_POST['video'];?>" placeholder="enter video code" />
</div>
<?php
if(!empty($_POST['video'])){
echo '<div id="videoholder"><iframe width="100%" height="315" src="https://www.youtube.com/embed/',$_POST['video'],'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
}else echo '<div id="videoholder"></div>';
?>

</div>
<?php
/*
<!--
<div class="form-group mb-3 mt-5">
<label class="form-label text-dark"><!--?php echo $lang['company_images'],' - ',$lang['you_can'],' ',$lang['images_num'][$_SESSION['selected_plan']],' ',$lang['images'];?></label>
<input data-num="<!--?php echo $lang['images_num'][$_SESSION['selected_plan']];?>" id="adv_images" type="file" name="files" accept=".jpg, .png, image/jpeg, image/png" multiple />
</div>-->
*/
?>

<!--end tab3-->

<!--start tab4-->



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
<input data-tab="first" class="form-control mt10 w100" type="text" id="dds" name="dds" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['dds'];?>"/>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['city'];?></label>
<input data-tab="first" class="form-control mt10 w90 mand" type="text" id="inv_city" name="inv_city" placeholder="<?php echo $lang['enter'];?>" value="<?php echo $city;?>"/>
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

<div id="paymnt" class="ib w100 tac border-0 mt-4">
<h2 class="ib head pb-3 w100 bbd"><?php echo $lang['paymnt'];?></h2>
</div>
<?php
if($advertrow['paid'] < 1){
?>
<div class="panel panel-secondary">
<div class=" tab-menu-heading border-0 pl-0 pr-0 pt-0">
<h4 class="mt-2 mb-4"><?php echo $lang['how_pay'];?></h4>
<div class="tabs-menu1 ">
<!-- Tabs -->
<ul class="nav panel-tabs">
<li><a href="#tab5" onclick="set_pay('<?php echo $lang['c_bank'];?>','c_bank');" class="<?php if(isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_bank') echo 'active';?>" data-toggle="tab"><?php echo $lang['pay_methods']['bank'];?></a></li>
<li><a href="#tab6" onclick="set_pay('<?php echo $lang['c_easy'];?>','c_easy');" class="<?php if(isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_easy') echo 'active';?>" data-toggle="tab"><?php echo $lang['pay_methods']['easypay'];?></a></li>
<li><a href="#tab7" id="aaa" onclick="set_pay('<?php echo $lang['c_card'];?>','c_card');" class="<?php if(isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_card') echo 'active';?>" data-toggle="tab"><?php echo $lang['pay_methods']['card'];?></a></li>
</ul>
</div>
</div>
<div class="panel-body tabs-menu-body pl-0 pr-0 border-0">
<div class="tab-content">

<div class="tab-pane <?php if(isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_bank') echo 'active';?> " id="tab5">
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
<input disabled="" class="form-control mt10 w100" type="text" value="<?php echo $advertrow['amount'],' ',current_currency;?>"/>
</div>
</div>
</div>
</div>

<div class="tab-pane <?php if(isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_easy') echo 'active';?>" id="tab6">
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
<input disabled="" class="form-control mt10 w100" type="text" value="<?php echo $advertrow['amount'],' ',current_currency;?>"/>
</div>
</div>
</div>
<p class="mb-0"><?php echo $lang['easypay_txt2'];?></p>
</div>


<div class="tab-pane <?php if(isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_card') echo 'active';?>" id="tab7">
	<div class="control-group form-group">
		<div class="form-group">
			<label class="form-label text-dark"><?php echo $lang['paypal_txt'];?></label>
			
		</div>
	</div>
</div>

		</div>
	</div>
</div>


<?php
}
//else echo '<div class="col-lg-12"><h3 class="card-title">',$lang['paid_to'],': ',date("d-m-Y", strtotime(date("d-m-Y", strtotime($advertrow['paid_date'])) . " + 1 year")),'</h3></div>';

if($advertrow['paid'] > 0) echo '
<div class="col-lg-12 mt-4">
<label class="form-label text-dark">',$lang['pay_st'],'</label>
<h3 class="card-title">',$lang['paid_to'],': ',date("d-m-Y", strtotime(date("d-m-Y", strtotime($advertrow['paid_date'])) . " + 1 year")),'</h3></div>';
?>

<div id="paymentdiv" class="form-group row clearfix">
<div class="col-lg-12">
	<div class="checkbox checkbox-info">
		<label class="custom-control mt-4 custom-checkbox">
			<input id="payment" name="selected_pay_way" <?php if(isset($_POST['selected_pay_way'])) echo 'checked';?> type="checkbox" value="<?php echo $_POST['selected_pay_way'];?>" class="custom-control-input" />
			<span id="payment_txt" class="custom-control-label text-dark pl-2"><?php echo $lang[''.$_POST['selected_pay_way'].''];?></span>
			
		</label>
	</div>
</div>
<?php
if(($_POST['paid'] < 1) && (isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_card') ){
echo '<span id="pwc" class="ib w100 tac ccpay"><a class="btn btn-success mb-0" onclick="set_pay(\''.$lang['c_card'].'\',\'c_card\');" href="#payment_btn">',$lang['pwc'],'</a></span>';

}

?>
</div>

<ul class="list-inline wizard mb-0 mt-4 px-4 py-4 bg-light border-top">
<li id="saveadvert" class="next tac"><button onclick="return aa();" type="submit" id="save_advert" class="btn btn-secondary mb-0" name="save_advert" value="save"><?php echo $lang['edit_prd'];?></button></li>
</ul>
</form>
<?php
$button = '';
if($_POST['paid'] < 1){
if($_SESSION['selected_plan'] == 1){
if(($_POST['paid'] < 1) && (isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_card') ) $subclass ='db';

//spiram paypal 06.02.2023
/*$button = '
<div class="col-lg-12 ccpay" id="ccpay">
<label class="form-label text-dark mt-4">'.$lang['c_card'].'</label>
<div class="ib w100">
<img alt="" src="../images/interface/paypal.png"/>
</div>
<div class="ib w100">
<form target="_blank" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="LA36JW3DBZECE">
<input type="submit" class="btn btn-success mb-0" name="submit" alt="PayPal - The safer, easier way to pay online!" value="Плати '.number_format($advertrow['amount'],2).' '.current_currency.'"/>
<img alt="PayPal" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<div class="ib w100 tac">
<small class="ib w100 mt-3 mb-4">'.$lang['paypal_txt'].'</small>

<ul class="payments mb-0 mt-4 ib">
<li><i class="fa fa-cc-amex" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-visa" aria-hidden="true"></i></li>
<li><i class="fa fa-credit-card-alt" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-mastercard" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-paypal" aria-hidden="true"></i></li>
</ul>
</div>
</div>';*/
}

if($_SESSION['selected_plan'] == 2){
if(($_POST['paid'] < 1) && (isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_card') ) $subclass ='db';
//spiram paypal 06.02.2023
/*$button = '
<div class="col-lg-12 ccpay" id="ccpay">
<label class="form-label text-dark mt-4">'.$lang['c_card'].'</label>
<div class="ib w100">
<img alt="PayPal" src="../images/interface/paypal.png"/>
</div>
<div class="ib w100">
<form target="_blank" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="84JTYLNAH8L7W">
<input type="submit" class="btn btn-success mb-0" name="submit" alt="PayPal - The safer, easier way to pay online!" value="Плати '.number_format($advertrow['amount'],2).' '.current_currency.'"/>
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<div class="ib w100 tac">
<small class="ib w100 mt-3 mb-4">'.$lang['paypal_txt'].'</small>

<ul class="payments mb-0 mt-4 ib">
<li><i class="fa fa-cc-amex" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-visa" aria-hidden="true"></i></li>
<li><i class="fa fa-credit-card-alt" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-mastercard" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-paypal" aria-hidden="true"></i></li>
</ul>
</div>
</div>';*/
}

if($_SESSION['selected_plan'] == 3){
if(($_POST['paid'] < 1) && (isset($_POST['selected_pay_way']) && $_POST['selected_pay_way'] == 'c_card') ) $subclass ='db';
//spiram paypal 06.02.2023
/*$button = '
<div class="col-lg-12 ccpay" id="ccpay">
<label class="form-label text-dark mt-4">'.$lang['c_card'].'</label>
<div class="ib w100">
<img alt="PayPal" src="../images/interface/paypal.png"/>
</div>
<div class="ib w100">
<form target="_blank" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="FNSZ9W8BPZ72G">
<input type="submit" class="btn btn-success mb-0" name="submit" alt="PayPal - The safer, easier way to pay online!" value="Плати '.number_format($advertrow['amount'],2).' '.current_currency.'"/>
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<div class="ib w100 tac">
<small class="ib w100 mt-3 mb-4">'.$lang['paypal_txt'].'</small>

<ul class="payments mb-0 mt-4 ib">
<li><i class="fa fa-cc-amex" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-visa" aria-hidden="true"></i></li>
<li><i class="fa fa-credit-card-alt" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-mastercard" aria-hidden="true"></i></li>
<li><i class="fa fa-cc-paypal" aria-hidden="true"></i></li>
</ul>
</div>
</div>';*/
	}
}
?>



<div class="tab-pane fade card-body border-top-0 p-1" id="fourth">
<?php
if($_POST['paid'] < 1){
?>
<div class="form-group mb-3">
<label class="form-label text-dark"><?php echo $lang['af_pay'];?></label>
</div>
<?php
}else{
echo '<div class="col-xl-12 col-lg-12 col-md-12 p-1">';
echo_adverts_products($_SESSION['advert_id'],$_POST['paid'],$_POST['active'],1,$_POST['supplier_name']);
echo '</div>';
}
?>

</div>
</div>
		
			</div>
		</div>
</div>
<span id="payment_btn" class="text-dark pl-2 ib w100 tac"><?php echo $button;?></span>
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
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['prd_price'],' ',$advertrow['amount'],' ',current_currency;?>
									</li>
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['dura'],': ',$lang['plans_lenght'][$_SESSION['selected_plan']];?>
									</li>
									<li>
										<?php echo $lang['status'],': ',$status;?>
									</li>
									
									<li>
										<?php echo $lang['pay_st'],': ',$statuspaid;?>
									</li>
									<?php
									if($_POST['paid'] > 0){
									echo '<li>',$lang['p_date'],' ',date('d-m-Y',strtotime($_POST['paid_date'])),' ',$lang['y'],'</li>
									<li>',$lang['next_p'],' ',date("d-m-Y", strtotime(date('d-m-Y',strtotime($_POST['paid_date'])).' + 1 year')),' ',$lang['y'],'</li>';
									}
									?>
									
								</ul>
							</div>
						</div>
						
						<div class="card">
					<div class="banner"><img src="../images/banners/banner_support.jpg"/></div>
					</div>
					<div class="card">
					<div class="banner"><img src="../images/banners/new_site.jpg"/></div>
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
									if($_SESSION['selected_plan'] == 1 && $_POST['paid'] == 0){
									echo '<li class="tac">
										<a class="btn btn-secondary ib ma" href="../redirector.php?selected_plan=2&usr=1">',$lang['become_premium_btn'],'</a>
									</li>';
									}
									?>
									<li class="ml-5 mb-0">
										<a target="_blank" href="../news/How-to-choose-which-plan-suits-best-my-business-needs"> <?php echo $lang['read'];?>..</a>
									</li>
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
<script src="../assets/js/add_listings.js"></script>
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
</script>
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="deletediv" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
						<h5 class="modal-title ib w100"><?php echo $lang['pl_con'];?></h5><hr/>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
						<span class="ib w100 tac formsg" ><?php echo $lang['are_you_s'];?></span>
					</div>
					<div class="modal-body tac">
						<button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $lang['canc'];?></button>
						<button type="button" id="confirm_del" data-id="" class="btn btn-success ml-3"><?php echo $lang['confirm'];?></button>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
}else{
header("location: ../index.php");
exit(0);
}
?>