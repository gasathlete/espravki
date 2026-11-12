<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();

if(!empty($_REQUEST['pid']) && (intval($_REQUEST['pid']) > 0) && (!empty($_REQUEST['qty']) && (intval($_REQUEST['qty']) > 0))){

//var_dump($_SESSION['products']);
if(!isset($_SESSION['products']) || empty($_SESSION['products'])){
	$_SESSION['products'] = array();
	$_SESSION['products'][$_REQUEST['pid']] = $_REQUEST['qty'];
		}else{
if(!array_key_exists($_REQUEST['pid'],$_SESSION['products'])) $_SESSION['products'][$_REQUEST['pid']] = $_REQUEST['qty'];
else $_SESSION['products'][$_REQUEST['pid']] = $_REQUEST['qty'];
}

$response[0] = $lang['prd_added'];
$response[1] = 1;
$response[2] = count($_SESSION['products']);
}else{

}
//echo $response;
echo(json_encode($response));

	}
}
?>