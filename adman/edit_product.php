<?php
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
if(isset($_GET['pid']) && intval($_GET['pid']) > 0 && isset($_SESSION['adman'])){


$optionshtml = '';

if(isset($_POST['prd_submit'])){
//echo $_POST['title'];echo '<br/>';
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9&, ’\"\',.\/\s\-]+$/u";
$pattern2  = "/[^\p{L}\p{N} \/\,.!?-]/u";

//$_POST['title'] = preg_replace ('/[^\p{L}\p{N} \/\,.!?-]/u', '', $_POST['title']);
$_POST['title'] = sanitaze_text5(strip_tags($_POST['title']));

$_POST['description'] = sanitaze_text5(strip_tags($_POST['description'],"<p><br/><br><br />"));
$_POST['category_name'] = sanitaze_text5(strip_tags($_POST['category_name']));
$_POST['price'] = sanitaze_text5(strip_tags($_POST['price']));
$_POST['special_terms'] = strip_tags($_POST['special_terms'],"<p><br/><br><br />");


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

//echo $pmsg,'aaaa';
//var_dump($_POST);
//exit();
if(empty($pmsg)){

//$active = 0;
$sold = 0;
if(isset($_POST['sold'])) $sold = 1;

$product_tags = '';
if(count($_POST['product_tags']) > 0){
foreach($_POST['product_tags'] as $tag) $product_tags.=$tag.';';
}

if(empty($_POST['title_latin'])) $_POST['title_latin'] = translate2($_POST['title']);

$catquery = 'select url, bg_category from shop_products_categories where id = "'.mysql_real_escape_string($_POST['category_name']).'"';
$catresult=mysql_query($catquery) or die(send_error($catquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$cat_num_rows=mysql_num_rows($catresult);
if($cat_num_rows > 0){
$catrow=mysql_fetch_assoc($catresult);
$update = 'update products set 
title = "'.mysql_real_escape_string($_POST['title']).'",
description = "'.mysql_real_escape_string($_POST['description']).'",
price = "'.mysql_real_escape_string($_POST['price']).'",
promo_price = "'.mysql_real_escape_string($_POST['promo_price']).'",
product_tags = "'.mysql_real_escape_string($product_tags).'",
title_latin = "'.mysql_real_escape_string($_POST['title_latin']).'",
tags_latin = "'.mysql_real_escape_string(translate2($product_tags)).'",
category_url = "'.mysql_real_escape_string($catrow['url']).'",
category_name = "'.mysql_real_escape_string($catrow['bg_category']).'",
category_id = "'.mysql_real_escape_string($_POST['category_name']).'",
redirect_url = "'.mysql_real_escape_string($_POST['redirect_url']).'",
offer_type = "'.mysql_real_escape_string($_POST['offer_type']).'",
special_terms = "'.mysql_real_escape_string($_POST['special_terms']).'",
deliver_product = "'.mysql_real_escape_string($_POST['deliver_product']).'"';
if(isset($_POST['active'])) $update.=',active = "1"';
else $update.=',active = "0"';
if(isset($_POST['deleted'])) $update.=',deleted = "1"';
else $update.=',deleted = "0"';
if(isset($_POST['checked_by_admin'])) $update.=',checked_by_admin = "1"';
else $update.=',checked_by_admin = "0"';

$update.=',sold = "'.mysql_real_escape_string($sold).'" where id="'.mysql_real_escape_string($_GET['pid']).'"';
$updateresult=mysql_query($update) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$update);
//exit();

$delete = 'delete from product_options where product_id="'.mysql_real_escape_string($_GET['pid']).'"';
$deleteresult=mysql_query($delete) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$delete);


$options = '';
if(count($_POST['product_options']) > 0){
foreach($_POST['product_options'] as $optkey=>$option){
$options = '';
foreach($_POST['options'][$option] as $sngloption) $options.=mb_ucfirst($sngloption, 'UTF-8').';';
//foreach($_POST['product_tags'] as $tag) $product_tags.=$tag.';';
$optionsquery = 'insert into product_options set 
product_id = "'.mysql_real_escape_string($_GET['pid']).'",
option_name = "'.mysql_real_escape_string($option).'",
option_values = "'.mysql_real_escape_string($options).'"';
$optionsqueryresult=mysql_query($optionsquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$optionsquery);
	}
}


$imagecounter = 1;
		if(!empty($_FILES['images']['name'][0])){
		$maxnum = 0;
		$max = 'SELECT MAX(order_id),count(id) as numfiles FROM products_gallery where product_id = "'.mysql_real_escape_string($_GET['pid']).'" ';
		$maxresult=mysql_query($max) or die(send_error($max,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$maxrow = mysql_fetch_row($maxresult);
		$maxnum = $maxrow[0];
		
		if($maxrow[1] <= 4){
		foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
		if(!empty($tmp_name)){
		if($imagecounter <= 4){
		$imagecounter++;
		$maxnum++;
		$save_file_name = create_url($_POST['title'].'-'.($maxnum)).'-'.$_GET['pid'].'.jpg';

		$insertimages = 'insert into products_gallery set image = "'.mysql_real_escape_string($save_file_name).'", product_id = "'.mysql_real_escape_string($_GET['pid']).'", order_id = "'.mysql_real_escape_string($maxnum).'"';
		$insertimagesresult=mysql_query($insertimages) or die(send_error($insertimages,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$save_file_name,1200,1200,'products');		
						}
					}
				}
			}
		}
		
//header("location: ../editproduct/index.php?msg=1");
echo '<script>window.location.href = "../adman/index.php?page=edit_product&pid='.$_GET['pid'].'&msg=1";</script>';
exit(0);		
$pmsg = $lang['suc_save2'];
		}else $pmsg = $lang['wrong_prd_category'];
	}
}


$query='select * from products where id="'.mysql_real_escape_string($_GET['pid']).'"';
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
$newoptionshtml = get_product_options($_GET['pid']);
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

$product_images = get_product_images($_GET['pid']);
//var_dump($_POST['product_options']);

//var_dump($_POST);
$status = '<a href="#active"><i class="fa fa-times text-danger ml-2 mr-2"></i>'.$lang['p_active_status'][0].'</a>';
if($_POST['active'] > 0 && $_POST['checked_by_admin'] > 0 && $_POST['sold'] < 1) $status = '<i class="fa fa-check text-success ml-2 mr-2"></i>'.$lang['p_active_status'][$_POST['active']];

if(!isset($_POST['active']) || empty($_POST['active'])) $statusactive = 0;
else $statusactive = 1;

if(!isset($_POST['sold']) || empty($_POST['sold'])) $statussold = 0;
else $statussold = 1;

//var_dump($_POST);
//echo $pmsg,'aaaa';
//exit();

if(isset($_GET['msg'])) $pmsg = $lang['suc_save2'];

$row=mysql_fetch_assoc($result);
?>
<section class="sptb">
<form id="productform" action="index.php?page=edit_product&pid=<?php echo $_GET['pid'];?>" name="productform" method="post" enctype="multipart/form-data" class="form-horizontal mb-0">
<div class="container">
<div class="row ">
<div class="col-lg-12 col-md-12 tal">
	<div class="card ">
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

<div class="form-group rws">
<label class="form-label text-dark">Заглавие на латиница</label>
<input maxlength="100" class="form-control" type="text" id="title_latin" name="title_latin" value="<?php echo @$_POST['title_latin']?>" />
</div>



<div class="form-group rws">
<label class="form-label text-dark">Линк от където е взет продукта</label>
<input class="form-control" type="text" id="redirect_url" name="redirect_url" value="<?php echo @$_POST['redirect_url']?>" />
</div>

<div class="form-group ib w100 mt-3 mb-3">
<label class="form-label text-dark"><?php echo $lang['category'];?></label>
<?php
echo '<select name="category_name" id="category_name" class="form-control select2-show-search-prdcat border-bottom-0 mand" data-placeholder="',$lang['ccat'],'">';
echo add_product_category_new(0,@$level,0);
echo '</select>';
?>									
</div>

<div class="form-group ib wa mt-3 mb-3">
<label class="form-label text-dark"><?php echo $lang['prd_price'];?></label>
<input type="text" class="form-control short ib" onkeypress="return(priceFormat(event));" id="prd_price" name="price" value="<?php echo @$_POST['price']?>" /><?php echo '<span class="ib ml-2">',current_currency,'</span>';?>
</div>

<div class="form-group ib wa ml-4">
<label class="form-label text-dark"><?php echo $lang['promo_price'];?></label>
<input type="text" class="form-control short ib" onkeypress="return(priceFormat(event));" id="promo_price" name="promo_price" value="<?php echo @$_POST['promo_price']?>" /><?php echo '<span class="ib ml-2">',current_currency,'</span>';?>
</div>

<div class="form-group mt-4 mb-4">
	<label class="form-label text-dark"><?php echo $lang['prd_description'];?></label>
	<label class="short ib wa"><?php echo $lang['total_words2'];?></label><span id="display_count">0</span><label class="short ib wa"><?php echo $lang['words_left2'];?></label>
	<span class=" ib wa" id="word_left">200</span><br /><span class="desc_msg"></span>
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
<input min="1" type="number" class="form-control ordering" data-pid="',$_GET['pid'],'" data-id="'.$imgkey.'" value="',$image['ordering'],'"/></span>
<button class="remove_file" value="'.$imgkey.'" data-pid="',$_GET['pid'],'" type="button" title="',$lang['delete_picture'],'"></button></div>';
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


<div class="control-group form-group mb-2 mt-5 mb-4">
<div class="form-group mt-4 mb-4">
<h3 class="card-title mb-2"><?php echo $lang['terms_buy_2'];?></h3>
	<small><?php echo $lang['tbtxt'];?></small>	<textarea class="form-control" id="special_terms" name="special_terms" value="<?php echo @$_POST['special_terms']?>" placeholder="<?php echo $lang['sp_ter'];?>"><?php echo @$_POST['special_terms']?></textarea>
</div>
</div>

<div class="control-group form-group mb-2 mt-5">
<div class="card-header pl-0">
	<h3 class="card-title"><?php echo $lang['th_of'];?>:</h3>
</div>
<div class="d-md-flex ad-post-details mb-4 mt-4 tal">
<?php
foreach($lang['off_type_arr'] as $okey=>$oname){
$checked = '';
if($_POST['offer_type'] == $okey) $checked = 'checked="checked"';
echo '<label class="custom-control custom-radio ib wa mb-2 mr-4 otlbl">
		<input ',$checked,' type="radio" class="custom-control-input offer_type" name="offer_type" value="',$okey,'" />
		<span class="custom-control-label"><a class="text-muted">',$oname,'</a></span>
	</label>';
}
?>
</div>
</div>


<div class="control-group form-group mb-5 mt-3 <?php if($_POST['offer_type'] != 1) echo 'deldiv';?>">
<div class="db w100 card-header pl-0">
	<h3 class="db w100 card-title"><?php echo $lang['deli_product'];?></h3>
	<small class="db w100"><?php echo $lang['deli_product_txt'];?></small>
</div>

<div class="d-md-flex ad-post-details mb-4 mt-1">
<?php
foreach($lang['deli_product_arr'] as $odkey=>$odval){
$checked = '';
if($_POST['deliver_product'] == $odkey) $checked = 'checked="checked"';
$not_del='';
if($odkey == 2) $not_del = 'not_del';
echo '<label class="custom-control custom-radio ib wa mb-2 mr-4 dlbl">
		<input ',$checked,' type="radio" class="custom-control-input deliver_product ',$not_del,'" name="deliver_product" value="',$odkey,'" />
		<span class="custom-control-label"><a class="text-muted">',$odval,'</a></span>
	</label>';
}
?>
</div>
</div>


<div class="form-group row clearfix">
	<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input type="checkbox" <?php if($_POST['active'] > 0) echo 'checked';?> name="active" value="" class="custom-control-input" />
				<span id="active" class="custom-control-label text-dark pl-2">Активен продукт</span>
			</label>
		</div>
	</div>
</div> 


<div class="form-group row clearfix">
	<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input type="checkbox" <?php if($_POST['sold'] > 0) echo 'checked';?> name="sold" value="" class="custom-control-input" />
				<span id="sold" class="custom-control-label text-dark pl-2"><?php echo $lang['sold_status'][$statussold];?></span>
			</label>
		</div>
	</div>
</div>

<div class="form-group row clearfix">
	<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input type="checkbox" <?php if($_POST['deleted'] > 0) echo 'checked';?> name="deleted" value="" class="custom-control-input" />
				<span id="deleted" class="custom-control-label text-dark pl-2"><?php echo $lang['del_prd'];?></span>
			</label>
		</div>
	</div>
</div>

<div class="form-group row clearfix">
	<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input type="checkbox" <?php if($_POST['checked_by_admin'] > 0) echo 'checked';?> name="checked_by_admin" value="" class="custom-control-input" />
				<span id="checked_by_admin" class="custom-control-label text-dark pl-2">Проверен от администратор</span>
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
</div>
</div>
</form>
</section>						
	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>

<?php

	}else{
//header('Location: '.WebSite);
echo '<script>window.location.href = "../index.php";</script>';
exit(0);
}
}else{
//header('Location: '.WebSite);
echo '<script>window.location.href = "../index.php";</script>';
exit(0);
}
?>