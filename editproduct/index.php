<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require '../config.php';
require '../project_functions.php';
require '../translation/'.$_SESSION['lang'].'_lang.php';

function sanitaze_text5($str){
$str = trim($str);
//$str = preg_replace(array('/\r\n\r\n/','/\s\s+/','/\r\n/'), '<br/>', $str);
$str = preg_replace ("/[^\p{L}\p{N} <>&\s\/\,:.!?-]/u", '', $str);
//mahame paragraph white space ako se kopira text ot html
//$str=str_replace('  ',' ',$str);
//$str=str_replace(array('\\','[',']','{','}','^','%'),'',$str);
	return $str;
}

//echo $_SESSION['advert_id'];
if(isset($_SESSION['product_id']) && intval($_SESSION['product_id']) > 0 && isset($_SESSION['customer'])){
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


if(isset($_POST['prd_submit'])){
//echo $_POST['title'];echo '<br/>';
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9&, ’\"\',.\/\s\-]+$/u";
$pattern2  = "/[^\p{L}\p{N} \/\,.!?-]/u";

//$_POST['title'] = preg_replace ('/[^\p{L}\p{N} \/\,.!?-]/u', '', $_POST['title']);
$_POST['title'] = sanitaze_text5(strip_tags($_POST['title']));

$_POST['description'] = sanitaze_text5(strip_tags($_POST['description'],"<ul><li><div><span><p><br/><br><br />"));
$_POST['category_name'] = sanitaze_text5(strip_tags($_POST['category_name']));
$_POST['price'] = sanitaze_text5(floatval(strip_tags($_POST['price'])));
$_POST['promo_price'] = sanitaze_text5(floatval(strip_tags($_POST['promo_price'])));

$_POST['make'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['make'])));
$_POST['model'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['model'])));
$_POST['modification'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['modification'])));
$_POST['special_terms'] = strip_tags($_POST['special_terms'],"<p><br/><br><br />");

//var_dump($_POST);
//echo $_POST['title'];echo '<br/>';
$categoryname = (bool) preg_match($pattern, $_POST['prd_category']);
if(empty($_POST['title'])) {
	$pmsg = $lang['wrong_prd_title'];
}
elseif(empty($_POST['description']) && ((strlen($_POST['description']) > 3000))){
	$pmsg = $lang['wrong_prd_description'].strlen($_POST['description']);
}

if(empty($_POST['category_name']) || intval($_POST['category_name']) < 1) $pmsg = $lang['wrong_prd_category'];

//var_dump($_POST);
if(empty($_POST['price']) || !($_POST['price'] > 0)) $pmsg = $lang['wrong_prd_price'];
if($_POST['promo_price'] > $_POST['price']) $pmsg = $lang['wrong_prd_price'];

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

if(!empty($_FILES['images']['name'][0])){
//var_dump($_FILES["images"]);echo '<br/>';
foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
if(!empty($_FILES['images']['name'][$key])){
//echo $key,'-',$tmp_name,'<br/>';
if(!empty($tmp_name)){
list($width, $height, $type, $attr) = getimagesize($_FILES["images"]["tmp_name"][$key]);
//echo $_FILES["images"]["tmp_name"][$key];
//$aa=getimagesize($_FILES["images"]["tmp_name"][$key]);

//var_dump($aa);echo 'aaas<br/>';
//echo $key,'-',$type,'aaas<br/>';

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
}

if(empty($_POST['product_tags']) || count($_POST['product_tags']) < 1) $pmsg = $lang['wrong_product_tags'];
if(!isset($_POST['offer_type'])) $pmsg = $lang['wrong_ot'];
if(!isset($_POST['deliver_product'])) $pmsg = $lang['wrong_deliver_product'];


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
$update = 'update products set 
title = "'.mysql_real_escape_string($_POST['title']).'",
description = "'.mysql_real_escape_string($_POST['description']).'",
category_url = "'.mysql_real_escape_string($catrow['url']).'",
category_name = "'.mysql_real_escape_string($catrow['bg_category']).'",
category_id = "'.mysql_real_escape_string($_POST['category_name']).'",

make = "'.mysql_real_escape_string($_POST['make']).'",
model = "'.mysql_real_escape_string($_POST['model']).'",
modification = "'.mysql_real_escape_string($_POST['modification']).'",
price = "'.mysql_real_escape_string($_POST['price']).'",
promo_price = "'.mysql_real_escape_string($_POST['promo_price']).'",
product_tags = "'.mysql_real_escape_string($product_tags).'",
title_latin = "'.mysql_real_escape_string(translate($_POST['title'])).'",
tags_latin = "'.mysql_real_escape_string(translate($product_tags)).'",
offer_type = "'.mysql_real_escape_string($_POST['offer_type']).'",
special_terms = "'.mysql_real_escape_string($_POST['special_terms']).'",
deliver_product = "'.mysql_real_escape_string($_POST['deliver_product']).'"';
if(isset($_POST['active'])) $update.=',active = "1"';
if(isset($_POST['deleted'])) $update.=',deleted = "1"';
$update.=',sold = "'.mysql_real_escape_string($sold).'", checked_by_admin = "1" where id="'.mysql_real_escape_string($_SESSION['product_id']).'"';
$updateresult=mysql_query($update) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$update);
//exit();

$delete = 'delete from product_options where product_id="'.mysql_real_escape_string($_SESSION['product_id']).'"';
$deleteresult=mysql_query($delete) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$delete);


$options = '';
if(count($_POST['product_options']) > 0){
foreach($_POST['product_options'] as $optkey=>$option){
$options = '';
foreach($_POST['options'][$option] as $sngloption) $options.=mb_ucfirst($sngloption, 'UTF-8').';';
//foreach($_POST['product_tags'] as $tag) $product_tags.=$tag.';';
$optionsquery = 'insert into product_options set 
product_id = "'.mysql_real_escape_string($_SESSION['product_id']).'",
option_name = "'.mysql_real_escape_string($option).'",
option_values = "'.mysql_real_escape_string($options).'"';
$optionsqueryresult=mysql_query($optionsquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$optionsquery);
	}
}


$imagecounter = 1;
		if(!empty($_FILES['images']['name'][0])){
		$maxnum = 0;
		$max = 'SELECT MAX(order_id),count(id) as numfiles FROM products_gallery where product_id = "'.mysql_real_escape_string($_SESSION['product_id']).'" ';
		$maxresult=mysql_query($max) or die(send_error($max,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$maxrow = mysql_fetch_row($maxresult);
		$maxnum = $maxrow[0];
		
		if($maxrow[1] <= 4){
		foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
		if(!empty($tmp_name)){
		if($imagecounter <= 4){
		$imagecounter++;
		$maxnum++;
		$save_file_name = create_url($_POST['title'].'-'.($maxnum)).'-'.$_SESSION['product_id'].'.jpg';

		$insertimages = 'insert into products_gallery set image = "'.mysql_real_escape_string($save_file_name).'", product_id = "'.mysql_real_escape_string($_SESSION['product_id']).'", order_id = "'.mysql_real_escape_string($maxnum).'"';
		$insertimagesresult=mysql_query($insertimages) or die(send_error($insertimages,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$save_file_name,1200,1200,'products');		
						}
					}
				}
			}
		}
		
header("location: ../editproduct/index.php?msg=1");
exit(0);		
$pmsg = $lang['suc_save2'];
		}else $pmsg = $lang['wrong_prd_category'];
	}
}


$query='select * from products where id="'.mysql_real_escape_string($_SESSION['product_id']).'" and cid="'.mysql_real_escape_string($_SESSION['customer']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){


if(empty($pmsg) || $pmsg == $lang['suc_save2']){
while($row = mysql_fetch_assoc($result)){
foreach($row as $key => $value) {
	$_POST[$key] = $value;
	//echo $key,' - ',$value;echo '<br/>';

		}
	}
$optionshtml = '';
$newoptionshtml = get_product_options($_SESSION['product_id']);
$_POST['product_options'] = $newoptionshtml[0];
$optionsnames = $newoptionshtml[1];

//var_dump($newoptionshtml);
if(count($_POST['product_options']) > 0){
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
}

$product_images = get_product_images($_SESSION['product_id']);
//var_dump($_POST['product_options']);

//var_dump($_POST);
$status = '<a href="#active"><i class="fa fa-times text-danger ml-2 mr-2"></i>'.$lang['p_active_status'][0].'</a>';
if($_POST['active'] > 0) $status = '<i class="fa fa-check text-success ml-2 mr-2"></i>'.$lang['p_active_status'][$_POST['active']];

if(!isset($_POST['active']) || empty($_POST['active'])) $statusactive = 0;
else $statusactive = 1;

if(!isset($_POST['sold']) || empty($_POST['sold'])) $statussold = 0;
else $statussold = 1;

//var_dump($_POST);
//echo $pmsg,'aaaa';
//exit();

if(isset($_GET['msg'])) $pmsg = $lang['suc_save2'];
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
<?php
$row=mysql_fetch_assoc($result);
?>
<section class="sptb">
<form id="productform" action="../editproduct/index.php" name="productform" method="post" enctype="multipart/form-data" class="form-horizontal mb-0">
	<div class="container">
		<div class="row ">
			<div class="col-lg-8 col-md-12">
				<div class="card ">
				<?php
				$max_offers=get_advert_max_offers($_POST['advert_id']);
				$count_prds=count_adv_products($_POST['advert_id']);
				if($count_prds < $max_offers) echo '
				<div class="form-group tar w100 ib mt-4 mb-2 pl-3 pr-3">
				<a href="../redirector.php?add_product=',$_POST['advert_id'],'" class="btn btn-secondary m-3"><i class="fa fa-plus mr-2" aria-hidden="true"></i> ',$lang['add_offer'],'</a>
				</div>
				';
				?>
				
				<div class="form-group tac orange ib mt-4 mb-2 pl-3 pr-3"><?php echo $pmsg;?></div>
					<div class="card-header ">
						<h3 class="card-title"><?php echo $lang['edit_of'],' ',$_POST['title'],' - <span class="ml-4">',$lang['status'],': ',$status,'</span>';?></h3>
					</div>

<div class="card-body">
<div class="form-group rws">
<label class="form-label text-dark"><?php echo $lang['prd_title'];?></label>
<input maxlength="100" class="form-control" type="text" id="title" name="title" value="<?php echo @$_POST['title']?>" onclick="show_help('#help_prd_title');" />
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
<input type="text" class="form-control ib" onkeypress="return(priceFormat(event));" id="prd_price" name="price" value="<?php echo @$_POST['price']?>" /><?php echo '<span class="ib addon ml-2">',current_currency,'</span>';?>
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

<?php
if(count($product_images) < 4){
?>
<input multiple onchange="previewImages(this,4);" data-num="<?php echo $lang['images_num'][$_SESSION['selected_plan']];?>" id="product_images" type="file" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input" name="images[]"/>
<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>

<?php
}
?>
</div>
<div id="img_preview" class="mb-5">
<?php
if(!empty($product_images)){
foreach($product_images as $imgkey=>$image){
$mainimg = '';
if($image['ordering'] == 1) $mainimg = '<div title="'.$lang['main_image_txt'].'" class="arrow-ribbon bg-success">'.$lang['main_image'].'</div>';
echo '<div class="imgdiv">'.$mainimg.'<img class="thumbnail" alt="'.$_POST['title'].'" src="../images/products/',$image['image'],'"/>
<span class="fileinfo">
<label class="form-label text-dark">',$lang['ordering'],'</label>
<input min="1" type="number" class="form-control ordering" data-pid="',$_SESSION['product_id'],'" data-id="'.$imgkey.'" value="',$image['ordering'],'"/></span>
<button class="remove_file" value="'.$imgkey.'" data-pid="',$_SESSION['product_id'],'" type="button" title="',$lang['delete_picture'],'"></button></div>';
	}
}
?>

</div>
</div>

<?php
//var_dump($_POST['product_tags']);
?>
<div class="form-group mt-4 mb-4">
<label class="form-label text-dark"><?php echo $lang['product_tags'];?></label>
<small class="ib w100"><?php echo $lang['product_tags_sm'];?></small>
<select multiple class="form-control select2-show-search-tags border-bottom-0" id="product_tags" name="product_tags[]" class="w100">
<option value="0"><?php echo $lang['select_option'];?></option>
<?php

if(!empty($_POST['product_tags'])){
if(is_array($_POST['product_tags'])){
foreach($_POST['product_tags'] as $tag){
echo '<option selected value="',$tag,'">',$tag,'</option>';
		}
}else{
$ptags = explode(";",rtrim($_POST['product_tags'],';'));
foreach($ptags as $tag){
echo '<option selected value="',$tag,'">',$tag,'</option>';
		}
	}
}
?>
</select>
</div>

<div class="control-group form-group mb-2 mt-5">
<div class="card-header pl-0">
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

<div class="control-group form-group mb-2 mt-5 mb-4">
<div class="form-group mt-4 mb-4">
<h3 class="card-title mb-2"><?php echo $lang['terms_buy_2'];?></h3>
	<small><?php echo $lang['tbtxt'];?></small>	<textarea class="form-control" id="special_terms" name="special_terms" value="<?php echo @$_POST['special_terms']?>" placeholder="<?php echo $lang['sp_ter'];?>"><?php echo @$_POST['special_terms']?></textarea>
</div>
</div>


<div class="control-group form-group mb-3 mt-3 <?php if($_POST['offer_type'] != 1) echo 'deldiv';?>">
<div class="db w100 card-header pl-0">
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

<?php

if(!empty($_POST['redirect_url'])){
echo '
<div class="db p-0">
<h4 class="m-0">',sprintf($lang['priordr'],$_POST['redirect_url']),'</h4>
<div class="col-lg-12 p-0 col-md-12 ib">
<input data="',$chrow['site'],'" class="form-control mt-2" readonly type="text" id="prd_link" name="redirect_url" value="',$_POST['redirect_url'],'" />
</div>
</div>
';
}



if($_POST['active'] < 1){
?>
<div class="form-group row clearfix">
	<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input type="checkbox" name="active" value="1" class="custom-control-input" />
				<span id="active" class="custom-control-label text-dark pl-2"><?php echo $lang['activate_status'][$statusactive];?></span>
			</label>
		</div>
	</div>
</div>
<?php
}
?>
<div class="form-group row clearfix">
	<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input type="checkbox" <?php if($_POST['sold'] > 0) echo 'checked';?> name="sold" value="1" class="custom-control-input" />
				<span id="sold" class="custom-control-label text-dark pl-2"><?php echo $lang['sold_status'][$statussold];?></span>
			</label>
		</div>
	</div>
</div>

<div class="form-group row clearfix">
	<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input type="checkbox" <?php if($_POST['deleted'] > 0) echo 'checked';?> name="deleted" value="1" class="custom-control-input" />
				<span id="deleted" class="custom-control-label text-dark pl-2"><?php echo $lang['del_prd'];?></span>
			</label>
		</div>
	</div>
</div>


						</div>
						</div>
						
						<div class="float-right mb-5 mb-lg-0">
							<button type="submit" name="prd_submit" name="prd_submit" value="save_prd" class="btn btn-lg btn-success"><?php echo $lang['save'];?></button>
						</div>
				</div>
				
			<div class="col-lg-4 col-md-12">
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
<!--
<script src="../assets/plugins/fancyuploder/fancy_uploader_full.js"></script>-->
<script src="../assets/js/contacts.js"></script>
<!--<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>-->
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
}else{
header('Location: '.WebSite);
exit(0);
}
?>