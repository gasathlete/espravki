<?php
require_once dirname(__FILE__).'/../password_lib.php';
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';



$response = array();
if(isset($_SESSION['customer'])){
	// already logged in: say so instead of silently doing nothing
	$response[0] = 'Вече си влязъл в профила си. За смяна на паролата отвори „Моят профил“.';
	$response[1] = 0;
	echo(json_encode($response));
	exit;
}
if(!isset($_SESSION['advert_id']) && !isset($_SESSION['customer'])){
if(isset($_REQUEST['email']) && !empty($_REQUEST['email']) && is_mail(trim($_REQUEST['email']))){

			// match regardless of upper/lower case and stray spaces in the stored address
			$query='SELECT id, customer_names, customer_email FROM customers WHERE LOWER(TRIM(customer_email))=LOWER("'.mysql_real_escape_string(trim($_REQUEST['email'])).'") LIMIT 1';
			$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			if(mysql_num_rows($result)){
			$row=mysql_fetch_row($result);
			
			$newpass = randomcode();
			// the new password is saved only AFTER the e-mail was sent (see below),
			// so a failed e-mail never locks the customer out of the account

			$my_subject=$lang['password_sent_mail']['body']['subject']." ".official_mail_sender_name;
			$body=$lang['password_sent_mail']['body']['text1']." ".$row[1]." ,<br/>
			".$lang['password_sent_mail']['body']['text2'].'<br/><b>'.$newpass.'</b><br/><br/>
			'.$lang['password_sent_mail']['body']['text3']."<br />".official_mail_sender_name.'<br />';
			
			
				$mail_params=array(
					"content_type"=>"text/html",
					"from_email"=>official_mail_sender,
					"from_name"=>official_mail_sender_name,
					// no BCC: new passwords must not be copied to any other mailbox
					"subject"=>$my_subject,
					"to"=>trim($row[2]),
					"body"=>$body
				);
				$aa = send_mail($mail_params);

			if($aa){
			$update = 'update customers set password = "'.mysql_real_escape_string(pass_hash_for_db($newpass)).'", realp = "" where id = "'.intval($row[0]).'"';
			$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			$response[0] = $lang['password_sent'];
			$response[1] = 1;
			}else{
			error_log('passforgotten: mail() failed for customer id '.intval($row[0]));
			$response[0] = 'Не успяхме да изпратим имейла в момента. Моля, опитай отново по-късно или се свържи с нас.';
			$response[1] = 0;
			}
			}
	
}else{
$response[0] = $lang['wrong_email'];
$response[1] = 0;
}

echo(json_encode($response));

		}
	}
}
?>