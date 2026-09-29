<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require '../config.php';
require '../project_functions.php';
require '../translation/'.$_SESSION['lang'].'_lang.php';

//echo $_SESSION['advert_id'],'aaaaaaa';
if(isset($_SESSION['product_id'])) unset($_SESSION['product_id']);
if(isset($_SESSION['advert_id']) && isset($_SESSION['customer'])){

//$check_for_not_approved = check_for_not_approved();
$check_for_published_offers = check_for_published_offers();

if(!empty($check_for_not_approved)){
header("location: ../editbusiness/");
exit(0);
}

if($check_for_published_offers > 0){
header("location: ../editbusiness/index.php?msg=max_num_reached");
exit(0);
}


function sanitaze_text5($str){
$str = trim($str);
//$str = preg_replace(array('/\r\n\r\n/','/\s\s+/','/\r\n/'), '<br/>', $str);
$str = preg_replace ("/[^\p{L}\p{N} <>&\s\/\,:.!?-]/u", '', $str);
//mahame paragraph white space ako se kopira text ot html
//$str=str_replace('  ',' ',$str);
//$str=str_replace(array('\\','[',']','{','}','^','%'),'',$str);
	return $str;
}

function echo_meta(){
	global $lang;
	echo '<title>',$lang['meta_prds_add']['title'],'</title>';
	echo '<meta name="description" content="',$lang['meta_prds_add']['description'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_prds_add']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['meta_prds_add']['title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'/addbusiness/" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['meta_prds_add']['description'],'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';	
}

$optionshtml = '';


$newcategory = 0;

if(isset($_POST['prd_submit'])){
//var_dump($_POST);echo '<br/>';

$_POST['title'] = sanitaze_text5(strip_tags($_POST['title']));
$_POST['description'] = sanitaze_text5(strip_tags($_POST['description'],"<ul><li><div><span><p><br/><br><br />"));
$_POST['category_name'] = sanitaze_text5(strip_tags($_POST['category_name']));
$_POST['price'] = sanitaze_text5(floatval(strip_tags($_POST['price'])));
$_POST['promo_price'] = sanitaze_text5(floatval(strip_tags($_POST['promo_price'])));

$_POST['make'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['make'])));
$_POST['model'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['model'])));
$_POST['modification'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['modification'])));


//var_dump($_POST);echo '<br/>';
//var_dump($_POST);echo '<br/>';echo '<br/>';
//exit();
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9&, | ’\"\',.\/\s\-]+$/u";
$pattern2  = "/^[a-zA-Z\p{Cyrillic}0-9&;\s\-]+$/u";
$categoryname = (bool) preg_match($pattern, $_POST['prd_category']);
if(empty($_POST['title']) || !preg_match($pattern, $_POST['title']) == 1) {
	$pmsg = $lang['wrong_prd_title'].' <br/>'.$_POST['title'];
}
elseif(empty($_POST['description'])){
	$pmsg = $lang['wrong_prd_description'].strlen($_POST['description']);
}

if(empty($_POST['category_name'])){
$pmsg = $lang['wrong_prd_category'];

}else{
if(intval($_POST['category_name']) < 1){
$newcategory = 1;
//dobawqme new category
$checkcatquery = 'select id from shop_products_categories where bg_category = "'.mysql_real_escape_string(trim($_POST['category_name'])).'"';
$checkcatresult=mysql_query($checkcatquery) or die(send_error($checkcatquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$checkcat_num_rows=mysql_num_rows($checkcatresult);
if($checkcat_num_rows < 1){

$inscat = 'insert into shop_products_categories set bg_category = "'.mysql_real_escape_string($_POST['category_name']).'", bg_description = "'.mysql_real_escape_string($_POST['category_name']).'", url = "'.mysql_real_escape_string(create_url($_POST['category_name'])).'"';
$insertresult=mysql_query($inscat) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$inscat);
$_POST['category_name']=mysql_insert_id();

		}else{
		$checkcatrow=mysql_fetch_assoc($checkcatresult);
		$_POST['category_name'] = $checkcatrow['id'];
		
		}
	}
}


if(empty($_POST['price']) || !($_POST['price'] > 0)) $pmsg = $lang['wrong_prd_price'];
if($_POST['promo_price'] > $_POST['price']) $pmsg = $lang['wrong_prd_price'];


//var_dump($_POST);echo '<br/>';echo '<br/>';
//exit();

if(count($_POST['product_options']) > 0){
	foreach($_POST['product_options'] as $option){
	if(!preg_match($pattern, $option) == 1) $pmsg = $lang['wrong_option'];
	foreach($_POST['options'][$option] as $sngloption){
	//echo $sngloption;echo '-',$option,'<br/>';
	if(empty($sngloption) || !preg_match($pattern, $sngloption) == 1) $pmsg = $lang['wrong_option2'].' - '.$sngloption;
		}
	}

foreach($_POST['product_options'] as $optkey=>$option){
	$optionshtml.='<label class="form-label text-dark mt-4">'.$lang['ent_opts'].' '.$option.'</label><select multiple class="form-control select2-show-search-option w100 mandselect border-bottom-0" id="'.$option.'" name="options['.$option.'][]">
<option value="0">'.$lang['select_option'].'</option>';	
	$opts = explode(";",rtrim($optionsnames[$optkey],';'));
foreach($opts as $opt){
if(!empty($opt)) $optionshtml.='<option selected value="'.$opt.'">'.$opt.'</option>';
			}
			$optionshtml.='</select>';
		}
}

//echo $pmsg;	
//var_dump($_POST);
//exit();

if(isset($_SESSION['temp_images']) && count($_SESSION['temp_images']) > 0){
foreach($_SESSION['temp_images'] as $imag){
list($width, $height, $type, $attr) = getimagesize($imag);
if (!isset($type) || !in_array($type, array(
    IMAGETYPE_PNG, IMAGETYPE_JPEG))){
 $pmsg = $lang['UNSUPPORTED_FILE_TYPE'].' '.$_FILES["images"]["name"][$key];
		}
	}
}

elseif(!empty($_FILES['images']['name'][0])){
//var_dump($_FILES["images"]);
foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
if(!empty($tmp_name)){
list($width, $height, $type, $attr) = getimagesize($_FILES["images"]["tmp_name"][$key]);
if (!isset($type) || !in_array($type, array(
    IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
 $pmsg = $lang['UNSUPPORTED_FILE_TYPE'].' '.$_FILES["images"]["name"][$key];
}

if($_FILES["images"]['type'][$key]=='image/jpeg' || $_FILES["images"]['type'][$key]=='image/pjpeg' || $_FILES["images"]['type'][$key]=='image/jpg' || $_FILES["images"]['type'][$key]=='image/JPEG' || $_FILES["images"]['type'][$key]=='image/png' || $f['type']=='image/x-png'){
			if(exif_imagetype($_FILES["images"]["tmp_name"][$key])===FALSE){
			$pmsg=$_FILES["images"]['name'][$key].': '.$lang['UNSUPPORTED_FILE_TYPE'];
			}//else echo exif_imagetype($_FILES["images"]["tmp_name"][$key]);
			}else $pmsg=$_FILES["images"]['name'][$key].': '.$lang['UNSUPPORTED_FILE_TYPE'];
		}
	}
}

if(empty($_POST['product_tags']) || count($_POST['product_tags']) < 1) $pmsg = $lang['wrong_product_tags'];
if(!isset($_POST['offer_type'])) $pmsg = $lang['wrong_ot'];
if(!isset($_POST['deliver_product'])) $pmsg = $lang['wrong_deliver_product'];

//var_dump($_POST);


if(empty($pmsg)){

//$active = 0;
$sold = 0;
if(isset($_POST['sold'])) $sold = 1;

$product_tags = '';
if(count($_POST['product_tags']) > 0){
foreach($_POST['product_tags'] as $tag) $product_tags.=$tag.';';

}

$catquery = 'select url, bg_category from shop_products_categories where id = "'.mysql_real_escape_string($_POST['category_name']).'"';
$catresult=mysql_query($catquery) or die(send_error($catquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$cat_num_rows=mysql_num_rows($catresult);
if($cat_num_rows > 0){
$catrow=mysql_fetch_assoc($catresult);

$insert = 'insert into products set 
cid = "'.mysql_real_escape_string($_SESSION['customer']).'",
title = "'.mysql_real_escape_string(strip_tags($_POST['title'])).'",
product_url = "'.mysql_real_escape_string(create_url(strip_tags($_POST['title']))).'",
advert_id="'.mysql_real_escape_string($_SESSION['advert_id']).'",
description = "'.mysql_real_escape_string($_POST['description']).'",
category_url = "'.mysql_real_escape_string($catrow['url']).'",
category_name = "'.mysql_real_escape_string($catrow['bg_category']).'",
category_id = "'.mysql_real_escape_string($_POST['category_name']).'",

make = "'.mysql_real_escape_string($_POST['make']).'",
model = "'.mysql_real_escape_string($_POST['model']).'",
modification = "'.mysql_real_escape_string($_POST['modification']).'",

price = "'.mysql_real_escape_string(strip_tags($_POST['price'])).'",
promo_price = "'.mysql_real_escape_string($_POST['promo_price']).'",
product_tags = "'.mysql_real_escape_string($product_tags).'",
offer_type = "'.mysql_real_escape_string($_POST['offer_type']).'",
redirect_url = "'.mysql_real_escape_string($_POST['redirect_url']).'",
deliver_product = "'.mysql_real_escape_string($_POST['deliver_product']).'",
active = "1",sold = "0",checked_by_admin = "1"';
$insertresult=mysql_query($insert) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$insert);
$last_id=mysql_insert_id();

$update_url = 'update products set product_url = "'.mysql_real_escape_string(create_url($_POST['title']).'-'.$last_id).'" where id = "'.mysql_real_escape_string($last_id).'"';
$update_urlresult=mysql_query($update_url) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$update_url);

$delete = 'delete from product_options where product_id="'.mysql_real_escape_string($last_id).'"';
$deleteresult=mysql_query($delete) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$delete);

$options = '';
if(count($_POST['product_options']) > 0){
foreach($_POST['product_options'] as $optkey=>$option){
$options = '';
foreach($_POST['options'][$option] as $sngloption) $options.=mb_ucfirst($sngloption, 'UTF-8').';';
$optionsquery = 'insert into product_options set 
product_id = "'.mysql_real_escape_string($last_id).'",
option_name = "'.mysql_real_escape_string($option).'",
option_values = "'.mysql_real_escape_string($options).'"';
$optionsqueryresult=mysql_query($optionsquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$optionsquery);
	}
}


$imagecounter = 1;
$maxnum = 0;
if(isset($_SESSION['temp_images']) && count($_SESSION['temp_images']) > 0){
foreach($_SESSION['temp_images'] as $imgkey=>$imag){

$save_file_name = create_url($_POST['title'].'-'.($imagecounter)).'-'.$last_id.'.jpg';
$imageString = file_get_contents($imag);
$save = file_put_contents('../images/products/'.$save_file_name,$imageString);

$insertimages = 'insert into products_gallery set image = "'.mysql_real_escape_string($save_file_name).'", product_id = "'.mysql_real_escape_string($last_id).'", order_id = "'.mysql_real_escape_string($imagecounter).'"';
$insertimagesresult=mysql_query($insertimages) or die(send_error($insertimages,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

if($imagecounter == 1){
$update_image = 'update products set image = "'.mysql_real_escape_string($save_file_name).'" where id = "'.mysql_real_escape_string($last_id).'"';
$update_imageresult=mysql_query($update_image) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$update_image);
}
$imagecounter++;
$maxnum++;
	}
}
		if(!empty($_FILES['images']['name'][0])){
		
		$max = 'SELECT MAX(order_id),count(id) as numfiles FROM products_gallery where product_id = "'.mysql_real_escape_string($last_id).'" ';
		$maxresult=mysql_query($max) or die(send_error($max,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$maxrow = mysql_fetch_row($maxresult);
		$maxnum = $maxrow[0];
		
		if($maxrow[1] <= 4){
		foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
		if(!empty($tmp_name)){
		if($imagecounter <= 4){
		$imagecounter++;
		$maxnum++;
		$save_file_name = create_url($_POST['title'].'-'.($maxnum)).'-'.$last_id.'.jpg';

		$insertimages = 'insert into products_gallery set image = "'.mysql_real_escape_string($save_file_name).'", product_id = "'.mysql_real_escape_string($last_id).'", order_id = "'.mysql_real_escape_string($maxnum).'"';
		$insertimagesresult=mysql_query($insertimages) or die(send_error($insertimages,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		
		
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$save_file_name,1200,1200,'products');		
						}
					}
				}
			}
		}
//var_dump($_FILES['images']);

//exit();
if(isset($_SESSION['temp_images'])) unset($_SESSION['temp_images']);
$_SESSION['product_id'] = $last_id;		
header("location: ../editproduct/index.php?msg=1");
exit(0);		
$pmsg = $lang['suc_save'];
		}else echo $pmsg = $lang['wrong_prd_category'];
	}
}
//var_dump($_SESSION);

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
//var_dump($_SESSION['temp_images']);echo '<br/>';
//unset($_SESSION['temp_images']);
?>
<section>
	<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/banner2.jpg">
		<div class="header-text mb-0">
			<div class="container">
				<div class="text-center text-white">
					<h1 class=""><?php echo $lang['meta_prds_add']['title'];?></h1>
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
<li class="breadcrumb-item">'.$lang['meta_prds_add']['title'].'</li>';
?>				
</ol>
</div>
</div>
</div>

<section class="sptb">
<form id="productform" action="" name="productform" method="post" enctype="multipart/form-data" class="form-horizontal mb-0">
			<div class="container">
				<div class="row ">
					<div class="col-lg-8 col-md-12">
						<div class="card ">
						<div class="form-group tac orange ib w100 mt-4 mb-2"><?php echo $pmsg;?></div>
							<div class="card-header ">
								<h3 class="card-title"><?php echo $lang['ad_prds'];?></h3>
							</div>
<?php
$checkautomated = 'select site, advert_title_indentificator, advert_desc_indentificator, advert_price_indentificator, advert_img_indentificator, advert_category_indentificator from adverts where id = "'.mysql_real_escape_string($_SESSION['advert_id']).'"';
$checkautomatedresult=mysql_query($checkautomated) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$checkautomated);
$checkautomated_num_rows=mysql_num_rows($checkautomatedresult);
if($checkautomated_num_rows > 0){
$chrow = mysql_fetch_assoc($checkautomatedresult);
if(!empty($chrow['site']) && !empty($chrow['advert_title_indentificator']) && !empty($chrow['advert_desc_indentificator']) && !empty($chrow['advert_price_indentificator'])){
echo '
<div class="db p-3">
<h4 class="card-title ">',$lang['use_aut'],'</h4>
<small>За да ползвате тази опция моля копирайте пълен УРЛ адрес на продукт от вашият уебсайт:<br/> ',$chrow['site'],' в полето по долу:</small>
<div class="col-lg-10 col-md-12 ib">
<input data="',$chrow['site'],'" class="form-control mt-2" placeholder="Въведете урл на продукта" type="text" id="prd_link" name="redirect_url" value="" />
</div>

<div class="ib vat">
<i title="',$lang['dow_info'],'" class="fa fa-play text-success mr-2" id="loaddata"></i>
</div>
</div>
';
	}
}
?>
<div class="card-body">
<div class="form-group rws">
<label class="form-label text-dark"><?php echo $lang['prd_title'];?></label>
<input maxlength="100" class="form-control mand" type="text" id="title" name="title" value="<?php echo @$_POST['title']?>" onclick="show_help('#help_prd_title');" />
<?php echo '<div id="help_prd_title" class="bar"><span></span>'.$lang['help_prd_title'].'</div>';?>
</div>
<div class="form-group ib w100 mt-3 mb-3">
<label class="form-label text-dark"><?php echo $lang['category'];?></label>
<?php
echo '<select name="category_name" id="category_name" class="form-control select2-show-search-prdcat border-bottom-0 mand" data-placeholder="',$lang['ccat'],'">
<option value="0">',$lang['please_select'],'</option>';
echo add_product_category_new(0,@$level,0);
echo '</select>';
?>									
</div>

<div class="row mb-3">
<div class="col-lg-4 col-md-12 mt-3 mb-3 ib">
<label class="form-label text-dark"><?php echo $lang['marka'];?></label>
<?php
$makes = echo_makes();
echo '<input type="text" class="form-control input-lg keywords-input" id="make" name="make" value="',@$_POST['make'],'" placeholder="',$lang['enter'],'" />';
?>									
</div>
<div class="col-lg-4 col-md-12 mt-3 mb-3 ib">
<label class="form-label text-dark"><?php echo $lang['model'];?></label>
<?php
$models = echo_models();
echo '<input type="text" class="form-control input-lg keywords-input" id="model" name="model" value="',@$_POST['model'],'" placeholder="',$lang['enter'],'" />';
?>									
</div>

<div class="col-lg-4 col-md-12 mt-3 mb-3 ib">
<label class="form-label text-dark"><?php echo $lang['modification'];?></label>
<?php
$modifications = echo_modifications();
echo '<input type="text" class="form-control input-lg keywords-input" id="modification" name="modification" value="',@$_POST['modification'],'" placeholder="',$lang['enter'],'" />';
?>									
</div>
</div>

<div class="row mb-3">
<div class="col-lg-4 col-md-12 mt-3 mb-3 ib">
<label class="form-label text-dark"><?php echo $lang['prd_price'];?></label>
<input type="text" class="form-control ib mand" onkeypress="return(priceFormat(event));" id="prd_price" name="price" value="<?php echo @$_POST['price']?>" /><?php echo '<span class="ib addon ml-2">',current_currency,'</span>';?>
</div>

<div class="col-lg-4 col-md-12 mt-3 mb-3 ib">
<label class="form-label text-dark"><?php echo $lang['promo_price'];?></label>
<input type="text" class="form-control ib" onkeypress="return(priceFormat(event));" id="promo_price" name="promo_price" value="<?php echo @$_POST['promo_price']?>" /><?php echo '<span class="ib addon ml-2">',current_currency,'</span>';?>
</div>
</div>
								
<div class="form-group mt-4 mb-4">
	<label class="form-label text-dark"><?php echo $lang['prd_description'];?></label>
	<label class="short"><?php echo $lang['total_words2'];?></label><span id="display_count">0</span><label class="short"><?php echo $lang['words_left2'];?></label>
	<span id="word_left">200</span><br /><span class="desc_msg"></span>
	<textarea class="form-control" id="description" name="description" value="<?php echo @$_POST['description']?>" placeholder="<?php echo $lang['help_prd_description'];?>"><?php echo @$_POST['description']?></textarea>
</div>

<?php
//var_dump($_FILES);
?>								
<div class="form-group mt-4 mb-4">
<label class="form-label text-dark"><?php echo $lang['prd_opts'];?></label>
<small class="ib w100"><?php echo $lang['help_prd_options'];?></small>
<select multiple class="form-control select2-show-search border-bottom-0" id="product_options" name="product_options[]" class="w100">
<option value="0"><?php echo $lang['select_option'];?></option>
<option <?php if(!empty($_POST['product_options']) && in_array($lang['color'],$_POST['product_options'])) echo 'selected="selected"' ;?> value="<?php echo $lang['color'];?>"><?php echo $lang['color'];?></option>
<option <?php if(!empty($_POST['product_options']) && in_array($lang['size'],$_POST['product_options'])) echo 'selected="selected"' ;?> value="<?php echo $lang['size'];?>"><?php echo $lang['size'];?></option>
<option <?php if(!empty($_POST['product_options']) && in_array($lang['types'],$_POST['product_options'])) echo 'selected="selected"' ;?> value="<?php echo $lang['types'];?>"><?php echo $lang['types'];?></option>
<option <?php if(!empty($_POST['product_options']) && in_array($lang['material'],$_POST['product_options'])) echo 'selected="selected"' ;?> value="<?php echo $lang['material'];?>"><?php echo $lang['material'];?></option>
<option <?php if(!empty($_POST['product_options']) && in_array($lang['shape'],$_POST['product_options'])) echo 'selected="selected"' ;?> value="<?php echo $lang['shape'];?>"><?php echo $lang['shape'];?></option>
<option <?php if(!empty($_POST['product_options']) && in_array($lang['marka'],$_POST['product_options'])) echo 'selected="selected"' ;?> value="<?php echo $lang['marka'];?>"><?php echo $lang['marka'];?></option>
</select>
</div>								


<div class="form-group mt-4 mb-4 rws">
<div id="pohldr" class="form-group mt-4 mb-4 rws"><?php echo $optionshtml;?></div>
</div>
	
<div class="form-group mb-5">
<div class="msg imgmsg mt-3 mb-3"></div>
<label class="form-label text-dark"><?php echo $lang['images'],' - <small>',$lang['you_can'],' ',$lang['max_4_images'],'</small>';?></label>
<small class="ib w100"><?php echo $lang['hold_ctrl'];?></small>
<div class="custom-file">
<input multiple onchange="previewImages(this,4);" data-num="4" id="product_images" type="file" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input" name="images[]"/>
<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>
</div>
<div id="img_preview" class="mb-5"></div>
</div>

<div class="form-group mt-4 mb-4">
<label class="form-label text-dark"><?php echo $lang['product_tags'];?></label>
<small class="ib w100"><?php echo $lang['product_tags_sm'];?></small>
<select multiple class="form-control select2-show-search-tags border-bottom-0" id="product_tags" name="product_tags[]" class="w100">

<?php

if(!empty($_POST['product_tags'])){
if(is_array($_POST['product_tags'])){
foreach($_POST['product_tags'] as $tag){
if(mb_strlen($tag, 'UTF-8') > 3) echo '<option selected value="',$tag,'">',$tag,'</option>';
		}
}else{
if(mb_strlen($_POST['product_tags'], 'UTF-8') > 3){
$ptags = explode(";",rtrim($_POST['product_tags'],';'));
foreach($ptags as $tag){
echo '<option selected value="',$tag,'">',$tag,'</option>';
		}
		}
	}
}
?>
</select>
</div>

<div class="control-group form-group mb-2 mt-5">
<div class="card-header">
	<h3 class="card-title"><?php echo $lang['th_of'];?>:</h3>
</div>
<div class="d-md-flex ad-post-details mb-3 mt-3">
<?php
foreach($lang['off_type_arr'] as $okey=>$oname){
$checked = '';
if($_POST['offer_type'] == $okey) $checked = 'checked="checked"';
echo '<label class="custom-control custom-radio mb-2 mr-4 otlbl">
		<input ',$checked,' type="radio" class="custom-control-input offer_type" name="offer_type" value="',$okey,'" />
		<span class="custom-control-label"><a class="text-muted">',$oname,'</a></span>
	</label>';
}
?>
</div>
</div>

<div class="control-group form-group mb-5 mt-3 <?php if($_POST['offer_type'] != 1) echo 'deldiv';?>">
<div class="db w100 card-header">
	<h3 class="db w100 card-title"><?php echo $lang['deli_product'];?></h3>
	<small class="db w100"><?php echo $lang['deli_product_txt'];?></small>
</div>

<div class="d-md-flex ad-post-details mb-3 mt-1">
<?php
foreach($lang['deli_product_arr'] as $odkey=>$odval){
$checked = '';
if($_POST['deliver_product'] == $odkey) $checked = 'checked="checked"';
$not_del='';
if($odkey == 2) $not_del = 'not_del';
echo '<label class="custom-control custom-radio mb-2 mr-4 dlbl">
		<input ',$checked,' type="radio" class="custom-control-input deliver_product ',$not_del,'" name="deliver_product" value="',$odkey,'" />
		<span class="custom-control-label"><a class="text-muted">',$odval,'</a></span>
	</label>';
}
?>
</div>
</div>

						</div>
						</div>
						
						<div class="text-center mb-5 mb-lg-0">
							<button type="submit" name="prd_submit" name="prd_submit" id="save_prd" value="save_prd" class="btn btn-lg btn-success"><?php echo $lang['save'];?></button>
						</div>
				</div>
				
			<div class="col-lg-4 col-md-12">
			<div class="card">
					<div class="banner"><img src="../images/banners/banner_support.jpg"/></div>
			</div>
			<div class="card">
					<div class="banner"><a href="../contacts/" target="_blank"><img src="../images/banners/new_site.jpg"/></a></div>
			</div>
			<div class="card">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['hot_to_p'];?></h3>
							</div>
							<div class="card-body p-0">
								<ul class="list-unstyled widget-spec mb-0">
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['hot_to_p1'];?>
									</li>
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['hot_to_p2'];?>
									</li>
									<li>
										<i class="fa fa-check text-success" aria-hidden="true"></i><?php echo $lang['hot_to_p3'];?>
									</li>
									<li class="ml-5 mb-0">
										<a target="_blank" href="#"> <?php echo $lang['read'];?>..</a>
									</li>
								</ul>
							</div>
						</div>
			
			</div>
</div>
</div>
</form>
</section>						
<?php
include '../modules/footer.php';
?>	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<script src="../assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="../assets/js/fullfunctions.js"></script>
<script src="../assets/js/jquery-ui.js"></script>
<script src="../assets/js/contacts.js"></script>
<script src="https://cdn.tiny.cloud/1/1gk9wazgim3qrlmipqqxzu36f4m5tt469qzmcy5lkvz5wg48/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
<script src="../assets/js/add_products.js"></script>
<script>
var makes = <?php echo json_encode($makes);?>;
var models = <?php echo json_encode($models);?>;
var modifications = <?php echo json_encode($modifications);?>;

$('#make').autocomplete({
//source: [makes],
	source: makes,
	minLength: 1,
	select: function(event, ui) {
	event.preventDefault();
	//console.log(ui.item.label);
	$("#make").val(ui.item.label);
  }
});

$('#model').autocomplete({
//source: [models],
	source: models,
	minLength: 1,
	select: function(event, ui) {
	event.preventDefault();
	//console.log(ui.item.label);
	$("#model").val(ui.item.label);
  }
});

$('#modification').autocomplete({
//source: [modifications],
	source: modifications,
	minLength: 1,
	select: function(event, ui) {
	event.preventDefault();
	//console.log(ui.item.label);
	$("#modification").val(ui.item.label);
  }
});
</script>
</body>
</html>
<?php

	}else{
header('Location: '.WebSite);
exit(0);
}
?>