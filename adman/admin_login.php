<?php
session_start();
if(empty($_SESSION['adman'])){
ob_start();
//var_dump($_SESSION);
require("../config.php");
require("../connect.php");
include WebHome.'/adman/suppliers/lang/lang.php';
require("./suppliers/admin_functions.php");

$_SESSION['is_human'] = 1;
//$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
//echo $_SERVER['REMOTE_ADDR'].'<br />';
//echo $hostname,'<br />';
//if($hostname!=$_SERVER['REMOTE_ADDR']) $_SESSION['proxy']=1; //die('Proxy detected.');


/*if(detect_proxy_fast($_SERVER['REMOTE_ADDR'])) $_SESSION['proxy']=2;//die('Proxy detected.');
if(!empty($_SESSION['proxy'])){
header("Location: https://www.yahoo.com/");
exit(0);
}*/

$msg='<span class="cent n">'.$lang['login'].'</span>';
if(isset($_POST['admin_submit']) && isset($_SESSION['is_human'])){

if(!empty($_POST['admin_mail']) && !empty($_POST['admin_password']) && !empty($_POST['captcha']) && !isset($_SESSION['adman'])){
	$logged_info=array();
	if(isset($_POST['captcha'])){
	$logged_info=check_login_action("admin","admins");
	}else $logged_info['changes']['message_div']['content']=$lang['err_lgin'];
	//var_dump($logged_info);echo '<br>';
	if($logged_info['result']){
	header("location: redirect.php");
	exit(0);
		}else $msg='<span class="cent">'.$logged_info['changes']['message_div']['content'].'</span>';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link href="../assets/css/style.css" rel="stylesheet" />
<link type="text/css" rel="stylesheet" href="./suppliers/css/admin_style.css"/>

</head>
<body>
<?php
//echo $_SESSION['country'].'tuk';//exit();
if(@$_SESSION['country']=="Bulgaria"){
?>
<div class="admlog">
<?php echo $msg;?>
<form method="post" action="" name="login">
<div style="color:red;" class="hidden_field"></div>
<div><h5>LOGIN</h5></div>
<input type="hidden" value="" name="floader" />
<script type="text/javascript">var admin_mail_flag=true; var admin_password_flag=true;</script>
<span>ID
<input type="text" value="" name="admin_mail" autocomplete="off" id="admin_mail"></span>
<span><?php echo $lang['pass'];?><input type="password" value="" name="admin_password" id="admin_password"></span>
<div><?php echo $lang['ent_code'];?>
<input type="text" autocomplete="off" name="captcha" value="" id="antispam" class="tocheck <?php if(empty($_POST['captcha']) || $_POST['captcha']!=$_SESSION['code']) echo 'red';?>" />
</div>
<input type="submit" value="<?php echo $lang['enter'];?>" name="admin_submit" id="lo ib mtb10" class="btn btn-secondary"></form>
</div>
<?php
}
?>
</body>
</html>
<?php
}else
header("location: index.php");
exit(0);
?>