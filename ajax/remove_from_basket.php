<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();

if(!empty($_REQUEST['pid']) && (intval($_REQUEST['pid']) > 0)){
if(isset($_SESSION['products']) || !empty($_SESSION['products'])){
if(isset($_SESSION['products']) && array_key_exists($_REQUEST['pid'],$_SESSION['products'])) unset($_SESSION['products'][$_REQUEST['pid']]);

//removing options
if(isset($_SESSION['product_options'][$_REQUEST['pid']])) unset($_SESSION['product_options'][$_REQUEST['pid']]);

}

$response[0] = $lang['u_bas_updated'];
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