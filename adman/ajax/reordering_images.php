<?php
session_start();

if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../../translation/bg_lang.php';
require '../../project_functions.php';

$html='';

$response = array();
if(isset($_SESSION['adman'])){
if((intval($_REQUEST['pid']) > 0) && (intval($_REQUEST['image_id']) > 0) && (intval($_REQUEST['ordernum']) >= 0)){

$update = 'update products_gallery set order_id = "'.mysql_real_escape_string($_REQUEST['ordernum']).'" where 
id = "'.mysql_real_escape_string($_REQUEST['image_id']).'" and product_id = "'.mysql_real_escape_string($_REQUEST['pid']).'"';
$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

$product_images = get_product_images($_REQUEST['pid']);
	if(!empty($product_images)){
	foreach($product_images as $imgkey=>$image){
	$mainimg = '';
	if($image['ordering'] == 1){
	$mainimg = '<div title="'.$lang['main_image_txt'].'" class="arrow-ribbon bg-success">'.$lang['main_image'].'</div>';
	$uppr = 'update products set image = "'.mysql_real_escape_string($image['image']).'" where id = "'.mysql_real_escape_string($_REQUEST['pid']).'"';
	$upprresult=mysql_query($uppr) or die(send_error($uppr,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	}
	$html.='<div class="imgdiv">'.$mainimg.'<img class="thumbnail" alt="" src="../images/products/'.$image['image'].'"/>
	<span class="fileinfo">
	<label class="form-label text-dark">'.$lang['ordering'].'</label>
	<input min="0" type="number" class="form-control ordering" data-pid="'.$_REQUEST['pid'].'" data-id="'.$imgkey.'" value="'.$image['ordering'].'"/></span>
	<button class="remove_file" value="'.$imgkey.'" data-pid="'.$_REQUEST['pid'].'" type="button" title="'.$lang['delete_picture'].'"></button></div>';
		}
	}
	$response[0] = $lang['suc_save'];
	$response[1] = $html;




}else{
if((intval($_REQUEST['aid']) > 0) && (intval($_REQUEST['image_id'] > 0)) && (intval($_REQUEST['ordernum'] >= 0))){	
			if($_REQUEST['ordernum'] == 1){
			$getimage_name = 'select image from adverts_gallery where id = "'.mysql_real_escape_string($_REQUEST['image_id']).'" and advert_id = "'.mysql_real_escape_string($_REQUEST['aid']).'"';
			$getimage_nameresult=mysql_query($getimage_name) or die(send_error($getimage_name,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			$num_rows=mysql_num_rows($getimage_nameresult);
			if($num_rows){
			$row = mysql_fetch_assoc($getimage_nameresult);
			$image_name = $row['image'];
			
			//main image
			$updatemain = 'update adverts set small_image = "'.mysql_real_escape_string($image_name).'" where id = "'.mysql_real_escape_string($_REQUEST['aid']).'"';
			$mainresult=mysql_query($updatemain) or die(send_error($updatemain,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			
			//if(file_exists('../images/adverts/'.$image_name)) unlink('../images/adverts/'.$image_name);
				}
			}
			$update = 'update adverts_gallery set order_id = "'.mysql_real_escape_string($_REQUEST['ordernum']).'" where 
			id = "'.mysql_real_escape_string($_REQUEST['image_id']).'" and advert_id = "'.mysql_real_escape_string($_REQUEST['aid']).'"';
			$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			
			$advert_images = get_advert_images($_REQUEST['aid']);
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
			}else{
			$response[0] = $lang['wrong_email'];
			$response[1] = 0;
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