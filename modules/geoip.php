<?php
if((isset($_SESSION['country']) && !empty($_SESSION['country'])) && $_SESSION['country'] !='Bulgaria'){
if(!_bot_detected()){
echo '<h1>Not Found</h1><br />';
echo 'The requested URL was not found on this server.<br />';
echo 'Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.';
//header('Location: https://www.google.com/');
exit(0);
	}
}


if(!_bot_detected()){
//if(empty($_SESSION['city'])){


if(!isset($_SESSION['country'])){
//$_SESSION['country']=detect_country_fast($_SERVER['REMOTE_ADDR']);
if(empty($_SESSION['country'])) $_SESSION['country']='0';
	}
	
if(empty($_SESSION['city'])){
if(!empty($info['city'])){
 $_SESSION['city']=$info['city'];
 }//else $_SESSION['city']=detect_city_fast($_SERVER['REMOTE_ADDR']);
if(empty($_SESSION['city'])) $_SESSION['city']='0';
		}
	//}
}else{
$_SESSION['latitude'] = '';
$_SESSION['longitude'] = '';
$_SESSION['country'] = '';
}
?>