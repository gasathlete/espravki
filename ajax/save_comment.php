<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){
$_SESSION['is_human'] = 1;
require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();
$error_message='';
if(!isset($_SESSION['error_many_revies'])) $_SESSION['error_many_revies'] = 0;
$_SESSION['error_many_revies']++;

$string_exp = "/^[\p{Cyrillic}\d\s\-A-Za-z]+$/u";	
if(!preg_match($string_exp,$_REQUEST['names'])){    
$error_message= ''.$lang['wrong_names'].'';
}


if(empty($_REQUEST['email']) || !email_valid($_REQUEST['email'])){   
$error_message= ''.$lang['wrong_email'].'';  
}

if(empty($_REQUEST['id'])) {    
$error_message= 'problem';		
}


if(strlen($_REQUEST['review']) < 20) {    
$error_message= ''.$lang['wrong_comment'].'';		
}
		
if(
!empty($_REQUEST['names']) && !empty($_REQUEST['email']) && !empty($_REQUEST['rating']) && !empty($_REQUEST['id']) && !empty($_REQUEST['review']) && empty($error_message)
){

$check = 'select id from comments_to_articles where article_id = "'.mysql_real_escape_string($_REQUEST['id']).'" and 
(
cmail = "'.mysql_real_escape_string($_REQUEST['email']).'" or 
ip = "'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'" or 
comment = "'.mysql_real_escape_string($_SERVER['review']).'"
)
and active = "0"';
$checkresult=mysql_query($check) or die(send_error($check,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($checkresult) < 1){
	
$insert = 'insert into comments_to_articles set 
article_id = "'.mysql_real_escape_string($_REQUEST['id']).'",';
if(isset($_SESSION['customer'])) $insert.= ' customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'",';
$insert.= 'cnames = "'.mysql_real_escape_string($_REQUEST['names']).'",
cmail = "'.mysql_real_escape_string($_REQUEST['email']).'" ,
comment = "'.mysql_real_escape_string($_REQUEST['review']).'" ,
rating = "'.mysql_real_escape_string($_REQUEST['rating']).'" , 
city = "'.mysql_real_escape_string($_SESSION['city']).'" , 
ip = "'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'" ';
$insertresult=mysql_query($insert) or die(send_error($insert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	
	
	
// create email headers
		$email_subject = $_REQUEST['names'].' коментира статия от '.official_site_name;              
		$mail_text=
		$email_message = "Имена: ".$_REQUEST['names']."<br />\n".
		$email_message1 = "Email: ".$_REQUEST['email']."<br />\n".  
		$email_message2 = "Статия: ".$_REQUEST['atitle']." - ID: ".$_REQUEST['id']."<br />\n".  
		$email_message3 = "Коментар: ".strip_tags($_REQUEST['review'])."<br />\n".
		$email_message2 = "Рейтинг: ".$_REQUEST['rating']."<br />\n". 
		$email_message3 = "Country: ".$_SESSION['country']."<br />\n".
		$email_message3 = "City: ".$_SESSION['city']."<br />\n".
		$email_message3 = "IP: ".$_SERVER['REMOTE_ADDR']."<br />\n";   
		$params=array(
		"content_type"=>"text/html",
		"replay_to"=>$_REQUEST['mail'],
		"from_name"=>$_REQUEST['name'].' '.$_REQUEST['fname'],
		"from_email"=>official_mail_sender,
		"bcc"=>"",
		"to"=>official_mail_sender,
		//"to"=>'info@weddingburg.com',
		"subject"=>$email_subject,
		"body"=>$mail_text
		);
		//echo $_SESSION['is_human'];
		//if($_SERVER['HTTP_HOST'] == 'www.espravki.com')){
		if(isset($_SESSION['is_human'])){
		send_mail($params);
		
		}
		
	
		
$response[0] = $lang['saved_comment'];
$response[1] = 1;
	}else{
	$response[0] = $lang['saved_comment'];
$response[1] = 0;
	}
}else{
$response[0] = $error_message;
$response[1] = 0;
}
//echo $response;
echo(json_encode($response));

	}
}
?>