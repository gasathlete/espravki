<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require '../config.php';
require '../connect.php';
require '../project_functions.php';
	
$url=$_SERVER['REQUEST_URI'];
$parts = explode('/', rtrim($url, '/'));
//var_dump($_SESSION);
//echo $_GET['code'],'aaaaaaaaaaaaaa';

//exit();
if( !empty($_GET['code']) && preg_match('/^[A-Za-z0-9 \-_@&]+$/', $_GET['code']) && !isset($_GET['act'])){
	$code = $_GET['code'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$info = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
	//var_dump($info);
	$country=$info['country_name'];
	if(empty($country)) $country='0';
	
	if(empty($info['city'])){
	$city=detect_city_fast($_SERVER['REMOTE_ADDR']);
}else $city=$info['city'];


	$query='update mail_campaigns set opened = opened +1, ip="'.mysql_real_escape_string($ip).'", country="'.mysql_real_escape_string($country).'",
	city="'.mysql_real_escape_string($city).'" where code="'.mysql_real_escape_string($code).'"';
	mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

	$image = WebSite.'/images/interface/blank.gif';
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Location: ".$image."");
	return;

}

elseif( !empty($_GET['act']) && (!empty($_GET['code']) && preg_match('/^[A-Za-z0-9 \-_@&]+$/', $_GET['code'])) ){

	$ip = $_SERVER['REMOTE_ADDR'];
	$info = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
	//var_dump($info);
	$country=$info['country_name'];
	if(empty($country)) $country='0';
	
	if(empty($info['city'])){
	$city=detect_city_fast($_SERVER['REMOTE_ADDR']);
}else $city=$info['city'];


	$query='update mail_campaigns set clicked = clicked +1, country="'.mysql_real_escape_string($country).'",
	city="'.mysql_real_escape_string($city).'" where code="'.mysql_real_escape_string($_GET['code']).'"';
	mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	

	
	$q='select redirect_url from mail_campaigns where code="'.mysql_real_escape_string($_GET['code']).'"';
	$r=mysql_query($q) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$row=mysql_fetch_row($r);
	$redirect = WebSite;
	if(!empty($row[0])) $redirect = $row[0];
	header('Location: '.$redirect);
	exit(0);
}else{
header('Location: ../index.php');
exit(0);
}
?>