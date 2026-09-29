<?php
session_start();
require './config.php';
require './project_functions.php';
require './translation/'.$_SESSION['lang'].'_lang.php';

if(isset($_GET['edit_ad'])){
if(intval($_GET['edit_ad']) > 0){
$query='SELECT * FROM adverts WHERE id = "'.mysql_real_escape_string($_GET['edit_ad']).'" and customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
if(isset($_SESSION['advert_id'])) unset($_SESSION['advert_id']);

$_SESSION['advert_id'] = $_GET['edit_ad'];
header('Location: ./editbusiness/');
exit(0);
	}else{
	header('Location: '.WebSite);
	exit(0);
		}
	}else{
	header('Location: '.WebSite);
	exit(0);
		}
}

if(isset($_GET['add_product'])){
if(intval($_GET['add_product']) > 0){
$query='SELECT * FROM adverts WHERE id = "'.mysql_real_escape_string($_GET['add_product']).'" and customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_assoc($result);
if($row['active'] > 0){
if(isset($_SESSION['advert_id'])) unset($_SESSION['advert_id']);
$_SESSION['advert_id'] = $_GET['add_product'];
header('Location: ./addproduct/');
exit(0);
		}else{
		header('Location: ./editbusiness/');
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

}

if(isset($_GET['selected_plan']) && (intval($_GET['selected_plan']) > 0 && intval($_GET['selected_plan']) < 4)){
$_SESSION['selected_plan'] = $_GET['selected_plan'];
if(!isset($_SESSION['customer'])){
header('Location: ./userlogins/?register');
exit(0);
}else{
if(isset($_GET['usr']) && !empty($_SESSION['advert_id'])){
$max_offers = $lang['offers_num'][2];
$update='update adverts set selected_plan="2", amount="'.mysql_real_escape_string($lang['plans_prices'][$_SESSION['selected_plan']]).'", paid="0", max_offers="'.mysql_real_escape_string($max_offers).'" where id="'.mysql_real_escape_string($_SESSION['advert_id']).'"';
$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

//isprashtame si mail che nqkoi iska Premium plan
	$subject = 'Izbran Premium plan w '.WebSite.'';
	$body = 'Zdrasti Marianski,<br />Klient '.$_SESSION['customer_names'].' избра Premium plan за листинг - '.$_SESSION['advert_id'];
	$params=array(
	"content_type"=>"text/html",
	"replay_to"=>official_mail_sender,
	"from_name"=>official_mail_sender_name,
	"from_email"=>official_mail_sender,
	"to"=>'info@weddingburg.com',
	"subject"=>$subject,
	"body"=>$body
	);
	send_mail($params);
	header('Location: ./editbusiness/');
exit(0);
}else{
header('Location: ./addbusiness/');
exit(0);
		}
	}
}else{
header('Location: '.WebSite);
exit(0);
}
?>