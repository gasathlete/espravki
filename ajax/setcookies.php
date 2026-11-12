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

$pid = $_REQUEST['pid'];
$cookiename = "fav_prod";

//setcookie('prod', '', time() - (86400 * 30), '/');

if (isset($_COOKIE[$cookiename])) {
//echo 'aaa';
    $cookie = unserialize( base64_decode($_COOKIE[$cookiename]));
	if(!array_key_exists($pid,$cookie)){
	//adding
	$cookie[$pid] = $pid;
	$response[0] = 'add';
	}else{
	//removing
	foreach($cookie as $cookiekey=>$cookieval){
	if($cookiekey == $pid) unset($cookie[$pid]);
	}
	$response[0] = 'rem';
	}
}else{
    $cookie = array();
	$cookie[$pid] = $pid;
	$response[0] = 'add';
}


//var_dump($cookie);echo '4<br/>';
//exit();
setcookie($cookiename, '', time() - (86400 * 30), '/');
setcookie($cookiename, base64_encode( serialize($cookie)), time() + (86400 * 30), '/');

$cookie = unserialize( base64_decode($_COOKIE[$cookiename]));
//var_dump($cookie);echo '3<br/>';
//var_dump($_COOKIE);echo '2<br/>';

$response[1] = 1;
}
echo(json_encode($response));

	}
}
?>