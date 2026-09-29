<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();

if(!empty($_REQUEST['lat']) && !empty($_REQUEST['lng'])){
$_SESSION['latitude'] = $_REQUEST['lat'];
$_SESSION['longitude'] = $_REQUEST['lng'];
$cityfound = 0 ;
if(!empty($_REQUEST['postcode'])){
$query = 'select name from bulgariacities where post_code = "'.mysql_real_escape_string($_REQUEST['postcode']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_assoc($result);
	$_SESSION['city'] = $row['name'];
	$cityfound = 1;
	}else $_SESSION['city'] = $_REQUEST['city'];
			
}

if($cityfound < 1){
$query = 'select name from bulgariacities where name like "%'.mysql_real_escape_string($_REQUEST['city']).'%" or name_en like "%'.mysql_real_escape_string($_REQUEST['city']).'%"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_assoc($result);
	$_SESSION['city'] = $row['name'];
		}else $_SESSION['city'] = $_REQUEST['city'];
	}
//$_SESSION['city'] = $_REQUEST['lng'];
$response[0] = $lang['latlng_saved'];
$response[1] = 1;
$response[2] = $_SESSION['city'];
//$response[3] = $query;

}else{
$response[0] = $lang['latlng_not_saved'];
$response[1] = 0;
}
//echo $response;
echo(json_encode($response));

	}
}
?>