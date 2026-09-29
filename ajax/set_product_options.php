<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();

if(!empty($_REQUEST['pid']) && (intval($_REQUEST['pid']) > 0) && (!empty($_REQUEST['optname']) && !empty($_REQUEST['so']))){
//var_dump($_SESSION['product_options'][$_REQUEST['pid']]);echo '<br/>';
if(isset($_SESSION['product_options'][$_REQUEST['pid']]) && array_key_exists($_REQUEST['optname'],$_SESSION['product_options'][$_REQUEST['pid']])) $_SESSION['product_options'][$_REQUEST['pid']][$_REQUEST['optname']] = $_REQUEST['so'];

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