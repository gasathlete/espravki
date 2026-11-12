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
if(!isset($_SESSION['error_many_contacts'])) $_SESSION['error_many_contacts'] = 0;
$_SESSION['error_many_contacts']++;

$string_exp = "/^[\p{Cyrillic}\d\s\-A-Za-z]+$/u";	
if(!preg_match($string_exp,$_REQUEST['name'])){    
$error_message= ''.$lang['wrong_names'].'';
}
if(!preg_match($string_exp,$_REQUEST['fname'])){    
$error_message= ''.$lang['wrong_names'].'';
}

$phone_exp = "/^[0-9]+$/i";
if(strlen($_REQUEST['phone']) < 8 || !preg_match($phone_exp,$_REQUEST['phone'])) {
$error_message=$lang['wrong_phone'];
} 	

if(empty($_REQUEST['mail']) || !email_valid($_REQUEST['mail'])){   
$error_message= ''.$lang['wrong_email'].'';  
}

if($_REQUEST['subject'] < 1) {    
$error_message= ''.$lang['ent_sub'].'';		
}


if(strlen($_REQUEST['message']) < 20) {    
$error_message= ''.$lang['wrong_message'].'';		
}
		
if(
!empty($_REQUEST['name']) && !empty($_REQUEST['fname']) && !empty($_REQUEST['phone']) && !empty($_REQUEST['subject']) && !empty($_REQUEST['message']) && empty($error_message)
){


// create email headers
		$email_subject = $_REQUEST['name'].' '.$_REQUEST['fname'].' ви изпрати съобщение от '.official_site_name;              
		$mail_text=
		$email_message = "Names: ".$_REQUEST['name'].' '.$_REQUEST['fname']."<br />\n".
		$email_message1 = "Email: ".$_REQUEST['mail']."<br />\n".  
		$email_message2 = "Phone: ".$_REQUEST['phone']."<br />\n".  
		$email_message3 = "Относно: ".$lang['mail_sbj'][$_REQUEST['subject']]."<br />\n".		
		$email_message3 = "Message: ".strip_tags($_REQUEST['message'])."<br />\n".
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
		if(isset($_FILES) && !empty($_FILES["filetoup"])) send_mail_with_attachement($params,$_FILES["filetoup"]);
		else send_mail($params);
		
		}
		
		
		//}
		
$response[0] = $lang['t_msg'];
$response[1] = 1;	
}else{
$response[0] = $error_message;
$response[1] = 0;
}
//echo $response;
echo(json_encode($response));

	}
}
?>