<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();

if(!empty($_REQUEST['pid']) && (intval($_REQUEST['pid']) > 0) && (!empty($_REQUEST['qty']) && (intval($_REQUEST['qty']) > 0)) && !empty($_REQUEST['act'])){

if($_REQUEST['act'] == 'add' && !empty($_REQUEST['url'])){

$check='select id from products where id = "'.mysql_real_escape_string($_REQUEST['pid']).'" and product_url = "'.mysql_real_escape_string($_REQUEST['url']).'" group by id';
$result=mysql_query($check) or die(send_error($check,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
	if(!isset($_SESSION['products']) || empty($_SESSION['products'])){
	$_SESSION['products'] = array();
	$_SESSION['products'][$_REQUEST['pid']] = $_REQUEST['qty'];
		}else{
		if(!array_key_exists($_REQUEST['pid'],$_SESSION['products'])) $_SESSION['products'][$_REQUEST['pid']] = $_REQUEST['qty'];
		}

$_SESSION['product_options'][$_REQUEST['pid']] = array();		
		if(isset($_REQUEST['so']) && count($_REQUEST['so']) >0){
		//var_dump($_REQUEST['so']);echo '<br/>';
foreach($_REQUEST['so'] as $key=>$val){
//echo $val['id'],'-',$val['value'];echo '<br/>';
if(isset($_SESSION['product_options'][$_REQUEST['pid']]) && !array_key_exists($val['id'],$_SESSION['product_options'][$_REQUEST['pid']])) $_SESSION['product_options'][$_REQUEST['pid']][$val['id']] = $val['value'];
}		
		}
		//var_dump($_SESSION['product_options'][$_REQUEST['pid']]);echo '<br/>';
	}
}

$response[0] = $lang['prd_added'];
$response[1] = 1;
$response[2] = count($_SESSION['products']);
}else{
$response[0] = $lang['latlng_not_saved'];
$response[1] = 0;
}
//echo $response;
echo(json_encode($response));

	}
}
?>