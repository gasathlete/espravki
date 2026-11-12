<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';

$html='';

$response = array();
//echo $_SESSION['advert_id'],'aaa';
if(isset($_SESSION['customer']) && isset($_SESSION['advert_id']) && strlen($_REQUEST['title']) >= 5){

$query = 'select id, bg_category from shop_products_categories where visible="1" and 
bg_category LIKE "%'.mysql_real_escape_string($_REQUEST['title']).'%" or 
bg_description LIKE "%'.mysql_real_escape_string($_REQUEST['title']).'%" or 
meta_title LIKE "%'.mysql_real_escape_string($_REQUEST['title']).'%" limit 1';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_assoc($result);
$checkparent = 'select id from shop_products_categories where parent = "'.$row['id'].'" and visible="1"';
$checkresult=mysql_query($checkparent) or die(send_error($checkparent,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$check_num_rows=mysql_num_rows($checkresult);

if($check_num_rows < 1){
$response[0] = $row['id'];
$response[1] = $row['bg_category'];
		}
	}
}

echo(json_encode($response));

		}
	}
?>