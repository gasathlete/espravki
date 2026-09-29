<?php
session_start();
require './config.php';
require './project_functions.php';
require './translation/'.$_SESSION['lang'].'_lang.php';
if(isset($_SESSION['customer'])){
if(isset($_GET['pid']) && (intval($_GET['pid']) > 0 && intval($_SESSION['customer']) > 0)){


$query='select id from products where id="'.mysql_real_escape_string($_GET['pid']).'" and cid="'.mysql_real_escape_string($_SESSION['customer']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$_SESSION['product_id'] = $_GET['pid'];
header('Location: ./editproduct/');
exit(0);
}else{
	header('Location: '.WebSite);
	exit(0);
	}
		
	}else{
	header('Location: '.WebSite);
	exit(0);
	}
}else{
header('Location: '.WebSite);
exit(0);
}
?>