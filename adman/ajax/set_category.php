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
if((intval($_REQUEST['pid']) > 0) && (intval($_REQUEST['cat_id']) > 0)){

$query = 'select bg_category, url from shop_products_categories where id = "'.mysql_real_escape_string($_REQUEST['cat_id']).'"';
$queryresult=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($queryresult);
if($num_rows > 0){
$row=mysql_fetch_assoc($queryresult);
$update = 'update products set category_id = "'.mysql_real_escape_string($_REQUEST['cat_id']).'", category_name = "'.mysql_real_escape_string($row['bg_category']).'",
category_url = "'.mysql_real_escape_string($row['url']).'" where id = "'.mysql_real_escape_string($_REQUEST['pid']).'"';
$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

	
	$response[0] = $lang['suc_save'];
	$response[1] = $row['bg_category'];

	}else{
	$response[0] = 'Не успях да намеря такава категория';
	$response[1] = $html;
	}


}else{
$response[0] = 'Грешка !';
$response[1] = 0;
}

echo(json_encode($response));

		}
	}
}
?>