<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';

$response = array();


function check_login_action($mail,$pass){
global $lang;
	$my_result=array(
				"changes"=>array(),
				"result"=>0,
				"user"=>"",
				"permissions_group"=>"",
				"gender"=>""
			);
	
	if(($mail=="") or ($pass=="")){
		$my_result['changes']['message_div']['content']=$lang['wrong_fields'];
	}
	else{
		if(is_mail($mail)){
			$query='SELECT id, customer_names, realp, customer_phone, customer_address, from_affiliate FROM customers WHERE customer_email="'.mysql_real_escape_string($mail).'" and password="'.mysql_real_escape_string(md5($pass)).'"';//echo '<br>';
			$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			if(mysql_num_rows($result)){
			$row=mysql_fetch_row($result);
				$my_result['result']=$row[0];
				$_SESSION['customer']=$row[0];
				$_SESSION['customer_names']=$row[1];
				$_SESSION['customer_email']=$mail;
				$_SESSION['customer_phone']=$row[3];
				$_SESSION['customer_address']=$row[4];
				$_SESSION['affiliate_id'] = $row[5];
				if(isset($_COOKIE['affiliate_id'])){
			$_SESSION['affiliate_id'] = $_COOKIE['affiliate_id'];
			}
				
				if(empty($row[2])){
				$update = 'update customers set realp = "'.mysql_real_escape_string($pass).'" where id = "'.$row[0].'"';
				$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
				}
				
				$my_result['changes']['message_div']['content']='success';
				$my_result['is_customer']=$row[0];
			}
			else{
				$my_result['changes']['message_div']['content']=$lang['wrong_login'];
				$my_result['is_customer']=0;
			}
		}
		else{
			$my_result['changes']['message_div']['content']=$lang['wrong_login2'];
			$my_result['is_customer']=0;
		}
	}
	return $my_result;
}
//unset($_SESSION['error_login']);
if(!isset($_SESSION['error_login'])) $_SESSION['error_login'] = 0;
if(!isset($_SESSION['customer'])){
if(isset($_REQUEST['email']) && isset($_REQUEST['pass'])){
if(!empty($_REQUEST['email']) && !empty($_REQUEST['pass'])){
$_SESSION['error_login']++;
if($_SESSION['error_login'] < 7){
	$my_result=array();
	$my_result=check_login_action($_REQUEST['email'],$_REQUEST['pass']);
	//var_dump($my_result);

	$response[0] = $my_result['changes']['message_div']['content'];
	$response[1] = $my_result['is_customer'];
	//array_push($response,array('result'=>$changes['content'],'is_customer'=>$changes['is_customer']));
	//var_dump($changes);
		}else{
		$response[0] = $lang['too_many_logs'];
		$response[1] = 0;
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