<?php
if(isset($_SESSION['adman'])){
	require_once '../config.php';
	require_once './suppliers/lang/lang.php';
	require_once './suppliers/functions2.php';
	
	$message='';
	require_once './menu_top.php';
	//echo $_POST['btn'];
	
	switch(@$_POST['btn']){
		case 'add':{
			require_once './suppliers/template/mail-marketing.tpl.php';
			break;
		}
		default: require_once './suppliers/template/mail-marketing.tpl.php'; break;
	}
}
else{
header("Location: index.php");
}
?>