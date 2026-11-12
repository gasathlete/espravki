<?php
 session_start();

require("../config.php");
require("../connect.php");
//require("../functions.php");
require("../translation/lang.php");
require("../project_functions.php");

if ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || ini_get('magic_quotes_sybase')){
	foreach($_GET as $k => $v){
		if(is_array($v)){
			foreach($v as $kk=>$kv)
				$_GET[$k][$kk]=stripslashes($kv);
		}else
			$_GET[$k] = stripslashes($v);
	}
	foreach($_POST as $k => $v) {
		if(is_array($v)){
			foreach($v as $kk=>$kv)
			$_POST[$k][$kk]=stripslashes($kv);
		}else
			$_POST[$k] = stripslashes($v);
	}	
	foreach($_REQUEST as $k => $v){
		if(is_array($v)){
			foreach($v as $kk=>$kv)
			$_REQUEST[$k][$kk]=stripslashes($kv);
		}else
			$_REQUEST[$k] = stripslashes($v);
	} 
	foreach($_COOKIE as $k => $v){
		if(is_array($v)){
			foreach($v as $kk=>$kv)
			$_COOKIE[$k][$kk]=stripslashes($kv);
		}else
			$_COOKIE[$k] = stripslashes($v);	
	}
}

$votes=0;
$visits=0;
$html="<?xml version=\"1.0\" encoding=\"utf-8\"?>";
if(isset($_REQUEST['aid']) && isset($_REQUEST['vote_value'])){
	$product_query="SELECT id,supplier_name FROM `adverts` WHERE `id`='".addslashes($_REQUEST['aid'])."'";
	$product_result=mysql_query($product_query) or die(mysql_query($product_query));
	if(mysql_num_rows($product_result)){
		$product_row=mysql_fetch_assoc($product_result);
		$query="UPDATE `statistics_adverts_visits` SET `voted`='".addslashes($_REQUEST['vote_value'])."' WHERE `ip`='".addslashes($_SERVER['REMOTE_ADDR'])."' AND `advert_id`='".addslashes($product_row['id'])."'";
		$result=mysql_query($query) or die(mysql_query($query));
		
		$html_in_xml=html_product_rating($product_row['id'],'html',$product_row['supplier_name']);
		$html_in_xml_final="";
		$html_in_xml_len = mb_strlen($html_in_xml, "UTF-8");
		for ($i = 0; $i < $html_in_xml_len / 1024; $i++){
			$html_in_xml_final .= 
"		<html>".htmlspecialchars(mb_substr($html_in_xml, $i * 1024, 1024, "UTF-8"))."</html>
";
		}
			$html.="<result>
				<htmls>".
				$html_in_xml_final."
				</htmls>
				<found>true</found>
				</result>";
	}
}
else
	$html.="<result>
				<yellow>.</yellow>
				<half>.</half>
				<gray>.</gray>
				<votes>.</votes>
				<found>false</found>
			</result>";

header('Content-Type: text/xml');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
echo $html;
?>