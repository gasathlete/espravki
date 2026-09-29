<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();

if(!isset($_SESSION['error_many_contacts'])) $_SESSION['error_many_contacts'] = 0;
$_SESSION['error_many_contacts']++;



if(!empty($_REQUEST['email']) && filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)){

$response[0] = $lang['subscribe_success'];
$response[1] = 1;	
}else{
$response[0] = $lang['subscribe_plchldr'];
$response[1] = 0;
}
//echo $response;
echo(json_encode($response));

	}
}
?>