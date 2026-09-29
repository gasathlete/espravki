<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';



$response = array();
if(!isset($_SESSION['advert_id']) && !isset($_SESSION['customer'])){
if(isset($_REQUEST['email']) && !empty($_REQUEST['email']) && is_mail($_REQUEST['email'])){
	
			$query='SELECT id, customer_names FROM customers WHERE customer_email="'.mysql_real_escape_string($_REQUEST['email']).'"';//echo '<br>';
			$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			if(mysql_num_rows($result)){
			$row=mysql_fetch_row($result);
			
			$newpass = randomcode();
			
			$update = 'update customers set password = "'.md5($newpass).'", realp = "'.$newpass.'" where id = "'.$row[0].'"';
			$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			
			$my_subject=$lang['password_sent_mail']['body']['subject']." ".official_mail_sender_name;
			$body=$lang['password_sent_mail']['body']['text1']." ".$row[1]." ,<br/>
			".$lang['password_sent_mail']['body']['text2'].'<br/><b>'.$newpass.'</b><br/><br/>
			'.$lang['password_sent_mail']['body']['text3']."<br />".official_mail_sender_name.'<br />';
			
			
				$mail_params=array(
					"content_type"=>"text/html",
					"from_email"=>official_mail_sender,
					"from_name"=>official_mail_sender_name,
					"bcc"=>"info@weddingburg.com",
					"subject"=>$my_subject,
					"to"=>$_REQUEST['email'],
					"body"=>$body
				);
				$aa = send_mail($mail_params);
				
			$response[0] = $lang['password_sent'];
			$response[1] = 1;
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