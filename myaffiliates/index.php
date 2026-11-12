<?php
session_start();
header("Content-Type: text/html; charset=utf-8");

$url=$_SERVER['REQUEST_URI'];
$parts = explode('/', rtrim($url, '/'));
//var_dump($parts);
unset($_SESSION['affiliate_id']);
//echo $_SESSION['affiliate_id'],'aaaa';
$url = 'index.php'; 
if((!empty($parts[2]) && preg_match('/^[A-Za-z0-9\-_@&]+$/', $parts[2])) || 
isset($_GET['aff']) && !empty($_GET['aff']) && preg_match('/^[A-Za-z0-9\-_@&]+$/', $_GET['aff'])
){
	//require '../config.php';
	

$db_host = "localhost";
$db_user = "infojnug_espravkiUser";
$db_pass = "UR[I*&I4?8_W";  
$db_name = "infojnug_espravki";

	require '../connect.php';
	require '../project_functions.php';
	require '../translation/bg_lang.php';
	
	if(!empty($_GET['aff'])) $parts[2] = $_GET['aff'];
	$query='select id from affiliates where affiliate_code="'.mysql_real_escape_string($parts[2]).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_row($result);
	$_SESSION['affiliate_id'] = $row[0];
	setcookie('affiliate_id', $row[0], time() + (86400 * 365), "/");
	}
	
	if(isset($_GET['redirect'])) $url = $_GET['redirect'];
}
//echo $url;
//echo $_SESSION['affiliate_id'];exit();
header('Location: ../'.$url);
exit(0);
?>