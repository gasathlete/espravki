<?php
session_start();
$_SESSION['is_human'] = 1;
if(isset($_SESSION['is_human'])  && isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';

$html='';

$response = array();
if(isset($_SESSION['advert_id']) && isset($_SESSION['customer'])){
if(isset($_REQUEST['pid']) && intval($_REQUEST['pid'] > 0) && isset($_REQUEST['image_id']) && intval($_REQUEST['image_id'] > 0)){

if($_REQUEST['pid'] == $_SESSION['product_id']){
	$getimage_name = 'select image, order_id from products_gallery where id = "'.mysql_real_escape_string($_REQUEST['image_id']).'" and product_id = "'.mysql_real_escape_string($_SESSION['product_id']).'"';
		$getimage_nameresult=mysql_query($getimage_name) or die(send_error($getimage_name,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$num_rows=mysql_num_rows($getimage_nameresult);
		if($num_rows){
		$row = mysql_fetch_assoc($getimage_nameresult);
		$image_name = $row['image'];
		if(file_exists('../images/products/'.$image_name)) unlink('../images/products/'.$image_name);
		
		if($row['order_id'] == 1){
		$updatep = 'update products set image="" where id = "'.mysql_real_escape_string($_SESSION['product_id']).'"';
		$presult=mysql_query($updatep) or die(send_error($updatep,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	
			}
		}
		
		$update = 'delete from products_gallery where id = "'.mysql_real_escape_string($_REQUEST['image_id']).'" and product_id = "'.mysql_real_escape_string($_SESSION['product_id']).'"';
		$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		
	$product_images = get_product_images($_SESSION['product_id']);
	if(!empty($product_images)){
	foreach($product_images as $imgkey=>$image){
	$mainimg = '';
	if($image['ordering'] == 1) $mainimg = '<div title="'.$lang['main_image_txt'].'" class="arrow-ribbon bg-success">'.$lang['main_image'].'</div>';
	$html.='<div class="imgdiv">'.$mainimg.'<img class="thumbnail" alt="" src="../images/products/'.$image['image'].'"/>
	<span class="fileinfo">
	<label class="form-label text-dark">'.$lang['ordering'].'</label>
	<input min="0" type="number" class="form-control ordering" data-pid="'.$_SESSION['product_id'].'" data-id="'.$imgkey.'" value="'.$image['ordering'].'"/></span>
	<button class="remove_file" value="'.$imgkey.'" data-pid="'.$_SESSION['product_id'].'" type="button" title="'.$lang['delete_picture'].'"></button></div>';
			}
		}
		$response[0] = $lang['suc_save'];
		$response[1] = $html;
	}
}
else{

if(isset($_REQUEST['ri']) && intval($_REQUEST['ri'] > 0) && isset($_REQUEST['id'])){
if(isset($_SESSION['temp_images'][$_REQUEST['id']])) unset($_SESSION['temp_images'][$_REQUEST['id']]);
}else{
if(isset($_REQUEST['image_id']) && intval($_REQUEST['image_id'] > 0)){
		
		$getimage_name = 'select image from adverts_gallery where id = "'.mysql_real_escape_string($_REQUEST['image_id']).'" and advert_id = "'.mysql_real_escape_string($_SESSION['advert_id']).'"';
		$getimage_nameresult=mysql_query($getimage_name) or die(send_error($getimage_name,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$num_rows=mysql_num_rows($getimage_nameresult);
		if($num_rows){
		$row = mysql_fetch_assoc($getimage_nameresult);
		$image_name = $row['image'];
		if(file_exists('../images/adverts/'.$image_name)) unlink('../images/adverts/'.$image_name);
		}

			$update = 'delete from adverts_gallery where id = "'.mysql_real_escape_string($_REQUEST['image_id']).'" and advert_id = "'.mysql_real_escape_string($_SESSION['advert_id']).'"';
			$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			
			$advert_images = get_advert_images($_SESSION['advert_id']);
			if(!empty($advert_images)){
			foreach($advert_images as $imgkey=>$image){
			$mainimg = '';
			if($image['ordering'] == 1) $mainimg = '<div title="'.$lang['main_image_txt'].'" class="arrow-ribbon bg-success">'.$lang['main_image'].'</div>';
			$html.='<div class="imgdiv">'.$mainimg.'<img class="thumbnail" alt="" src="../images/adverts/'.$image['image'].'"/>
			<span class="fileinfo">
			<label class="form-label text-dark">'.$lang['ordering'].'</label>
			<input min="0" type="number" class="form-control ordering" data-id="'.$imgkey.'" value="'.$image['ordering'].'"/></span>
			<button class="remove_file" value="'.$imgkey.'" type="button" title="'.$lang['delete_picture'].'"></button></div>';
				}
			}
			$response[0] = $lang['suc_save'];
			$response[1] = $html;
			}
		}
	}
}else{
$response[0] = $lang['wrong_email'];
$response[1] = 0;
}

echo(json_encode($response));

		}
	}
?>