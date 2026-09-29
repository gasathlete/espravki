<?php
session_start();
$_SESSION['is_human'] = 1;
if(isset($_SESSION['is_human'])  && isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){
require '../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';

$response = array();

function password_generator(){
	$symbols=array('A','a','B','b','C','c','D','d','E','e','F','f','E','e','G','g','H','h','I','i','J','j','K','k','L','l','M','m','N','n','O','o','P','p','Q','q','R','r','S','s','T','t','U','u','V','v','W','w','X','x','Y','y','Z','z','1','2','3','4','5','6','7','8','9','0');
	$password_lenght=rand(6,8);
	$password="";
	for($i=0;$i<$password_lenght;$i++){
		$symbol=rand(0,63);
		if(array_key_exists($symbol,$symbols)) $password.=$symbols[$symbol];
		else $password.="_";
	}
	return $password;
}





if(!isset($_SESSION['advert_id']) && !isset($_SESSION['customer'])){
if(isset($_REQUEST['reg_name']) && isset($_REQUEST['reg_email']) && isset($_REQUEST['reg_password'])){
if(!empty($_REQUEST['reg_name']) && !empty($_REQUEST['reg_email']) && is_mail($_REQUEST['reg_email']) && !empty($_REQUEST['reg_password'])){

if(!isset($_REQUEST['code']) || empty($_REQUEST['code'])){
$_SESSION['validation_code'] = password_generator();

//if(!isset($_SESSION['validation_code'])){

//izprashtame mail
$my_subject = $lang['register_sent_mail']['body']['subject'];
$body = $lang['register_sent_mail']['body']['text1'].' '.$_REQUEST['reg_name'].',<br />'.
$lang['register_sent_mail']['body']['text2'].'<div style="display:block;padding:8px;width:100%;margin:15px 0;text-align:center;background:darkorange; color:#333;font-size:16px !important;line-height:35px;height:35px;"><b>'.$_SESSION['validation_code'].'</b></div><br/>'.
$lang['register_sent_mail']['body']['text3'].'<br/>'.$lang['register_sent_mail']['body']['mail_ps'];


$body.= '<br/>Email:'.$_REQUEST['reg_email'];

$mail_params=array(
"content_type"=>"text/html",
"from_email"=>official_mail_sender,
"from_name"=>official_mail_sender_name,
"bcc"=>"eespravki@gmail.com",
"cc"=>"",
"subject"=>$my_subject,
"to"=>$_REQUEST['reg_email'],
"body"=>$body
);
//var_dump($mail_params);
$aaa = send_mail_no_header($mail_params);
$response[0] = $lang['ent_code'];
$response[1] = 0;
$response[2] = 1;
	/*}else{
	$response[0] = $lang['ent_code'];
	$response[1] = 0;
	$response[2] = 1;
	}*/
}else{

if($_REQUEST['code'] == $_SESSION['validation_code']){

	$check = 'select * from customers where customer_email = "'.mysql_real_escape_string($_REQUEST['reg_email']).'"';
	$checkresult=mysql_query($check) or die(send_error($check,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($checkresult) < 1){
	$row=mysql_fetch_assoc($checkresult);
	
	$insert = 'insert into customers set 
	customer_names = "'.mysql_real_escape_string($_REQUEST['reg_name']).'",
	customer_email = "'.mysql_real_escape_string($_REQUEST['reg_email']).'",
	password = "'.md5($_REQUEST['reg_password']).'",
	realp = "'.mysql_real_escape_string($_REQUEST['reg_password']).'",
	city = "'.mysql_real_escape_string($_SESSION['city']).'",
	ip="'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'",
	reg_code="'.mysql_real_escape_string($_SESSION['validation_code']).'"';
	if(isset($_SESSION['affiliate_id']) && intval($_SESSION['affiliate_id']) > 0) $insert.=', from_affiliate="'.mysql_real_escape_string($_SESSION['affiliate_id']).'"';
	$insert.=', active = "1"';
	
	$insertresult=mysql_query($insert) or die(send_error($insert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$last_id = mysql_insert_id();
	$_SESSION['customer']=$last_id;
	$_SESSION['customer_names']=$_REQUEST['reg_name'];
	$_SESSION['customer_email']=$_REQUEST['reg_email'];
	$_SESSION['customer_phone']='';
	$response[0] = 'success';
	$response[1] = 1;
	}else{
	$response[0] = $lang['err_reg'];
	$response[1] = 0;
	$response[2] = 0;
		}
	}else{
	$response[0] = $lang['wrong_code'];
	$response[1] = 0;
	$response[2] = 1;
	}
		}
	}else{
	$response[0] = $lang['wrong_fields'];
	$response[1] = 0;
	} 
}else{
$response[0] = $lang['wrong_fields'];
$response[1] = 0;
}

echo(json_encode($response));

		}
	}
}
?>