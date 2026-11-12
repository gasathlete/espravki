<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();

if(!empty($_REQUEST['aid']) && (intval($_REQUEST['aid']) > 0)){
$insert = 'insert into statistics_adverts_visits set advert_id = "'.mysql_real_escape_string($_REQUEST['aid']).'", sitevisited = "1", 
ip = "'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'", approved = "1"';
$result=mysql_query($insert) or die(send_error($insert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

$response[0] = 1;
$response[1] = 1;
}
echo(json_encode($response));

	}
}
?>