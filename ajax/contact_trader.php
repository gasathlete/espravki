<?php
session_start();
$_SESSION['is_human'] = 1;
if(isset($_SESSION['is_human'])  && isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){
require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();
$error_message='';
if(!isset($_SESSION['error_many_contacts'])) $_SESSION['error_many_contacts'] = 0;
$_SESSION['error_many_contacts']++;

$string_exp = "/^[\p{Cyrillic}\d\s\- A-Za-z]+$/u";	
if(!preg_match($string_exp,$_REQUEST['names'])){    
$error_message= ''.$lang['wrong_names'].'';
}


$phone_exp = "/^[0-9]+$/i";
if(strlen($_REQUEST['phone']) < 8 || !preg_match($phone_exp,$_REQUEST['phone'])) {
$error_message=$lang['wrong_phone'];
} 	

if(empty($_REQUEST['email']) || !email_valid($_REQUEST['email'])){   
$error_message= ''.$lang['wrong_email'].'';  
}


if(strlen($_REQUEST['message']) < 20) {    
$error_message= ''.$lang['wrong_message'].'';		
}
		
if(
!empty($_REQUEST['names']) && intval($_REQUEST['aid']) > 0 && !empty($_REQUEST['phone']) && !empty($_REQUEST['email']) && !empty($_REQUEST['message']) && empty($error_message)
){

$query = 'select supplier_name, mail, customer_id from adverts where id="'.mysql_real_escape_string($_REQUEST['aid']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_assoc($result);

if(!isset($_SESSION['customer']) || (isset($_SESSION['customer']) && $_SESSION['customer'] != $row['customer_id'])){
$subject = '';
if(isset($_REQUEST['pname'])) $subject = $lang['subject'].":<br /> ".$_REQUEST['pname'];

$about_pid = 0;
$about = $lang['tr_mes'];
if(isset($_REQUEST['pid'])){
 $about_pid = $_REQUEST['pid'];
 $about = $lang['tr_msg'];
 }

// create email headers

if(!isset($_SESSION['advert_contacted'])) $_SESSION['advert_contacted'] = array();

if(isset($_REQUEST['pid']) && $_REQUEST['pid'] > 0){
if(!isset($_SESSION['product_contacted'])) $_SESSION['product_contacted'] = array();

}

//var_dump($_SESSION['product_contacted']);
if((!isset($_REQUEST['pid']) && !in_array($_REQUEST['aid'], $_SESSION['advert_contacted'])) || ((isset($_REQUEST['pid']) && !in_array($_REQUEST['pid'], $_SESSION['product_contacted'])))){
$secret = randomcode();

		$email_subject = $lang['vi'].' '.official_site_name;          
		$mail_text=
		$email_message =  $lang['hello']." ".$row['supplier_name']."<br /><br />\n".
		$email_message =  $about.": <br />\n".
		$email_message =  $lang['cnames'].": ".replace_with_stars($_REQUEST['names'])."<br />\n".
		$email_message1 = $lang['email'].": ".replace_with_stars($_REQUEST['email'])."<br />\n".  
		$email_message2 = $lang['phone'].": ".replace_with_stars($_REQUEST['phone'])."<br />\n".  
		$email_message3 = $subject."<br />\n".		
		$email_message3 = $lang['m'].":<br /> ".replace_with_stars(strip_tags($_REQUEST['message']))."<br /><br /><br />\n".
		$email_message3 = $lang['to_view'].":<br /><br />\n".
		$email_message3 = $lang['thank_msg']."<br />".official_site_name."<br /><br />\n".$lang['attn'];
		
		$messagehtml =
		$email_message =  $lang['hello']." ".$row['supplier_name']."<br /><br />".
		$email_message =  $lang['tr_msg'].": <br />".
		$email_message =  $lang['cnames'].": ".$_REQUEST['names']."<br />".
		$email_message1 = $lang['email'].": ".$_REQUEST['email']."<br />".  
		$email_message2 = $lang['phone'].": ".$_REQUEST['phone']."<br />".  
		$email_message3 = $subject."<br />".		
		$email_message3 = $lang['m'].":<br /> ".strip_tags($_REQUEST['message'])."<br /><br /><br />".
		$email_message3 = $lang['thank_msg']."<br />".official_site_name."<br /><br />"; 
		
		$sender_id = 0;
		$names = $_REQUEST['names'];
		if(isset($_SESSION['customer'])) $sender_id = $_SESSION['customer'];
		if(isset($_SESSION['customer_names'])) $names = $_SESSION['customer_names'];
		$insertmes = 'insert into messages set 
		recipient_id = "'.mysql_real_escape_string($row['customer_id']).'",
		about_aid = "'.mysql_real_escape_string($_REQUEST['aid']).'",
		about_pid = "'.mysql_real_escape_string($about_pid).'",
		sender_id = "'.mysql_real_escape_string($sender_id).'",
		customer_names = "'.mysql_real_escape_string($names).'",
		cmail = "'.mysql_real_escape_string($_REQUEST['email']).'",
		cphone = "'.mysql_real_escape_string($_REQUEST['phone']).'",
		subject = "'.mysql_real_escape_string($email_subject).'",
		secret = "'.md5($secret).'",
		mail_text = "'.mysql_real_escape_string($messagehtml).'"';
		$insertmesresult=mysql_query($insertmes) or die(send_error($insertmes,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		
		$params=array(
		"content_type"=>"text/html",
		"replay_to"=>official_mail_sender,
		"from_name"=>official_mail_sender_name,
		"from_email"=>official_mail_sender,
		"to"=>$row['mail'],
		//"bcc"=>official_mail_sender,
		"subject"=>$email_subject,
		"body"=>$mail_text
		);
		
		send_mail($params);
		array_push($_SESSION['advert_contacted'],$_REQUEST['aid']);
		if(isset($_REQUEST['pid'])) array_push($_SESSION['product_contacted'],$_REQUEST['pid']);
		
		
		$response[0] = $lang['t_msg2'];
		$response[1] = 1;
			}else{
			$response[0] = $lang['t_msg3'];
			$response[1] = 1;
			}		
		

		}else{
		$response[0] = $lang['same_sender'];
		$response[1] = 0;
		}
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