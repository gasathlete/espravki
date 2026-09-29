<?php
if (!isset($_SESSION['lang'])) {
	$_SESSION['lang'] = 'bg';
}

$info = geoip_record_by_name($_SERVER['REMOTE_ADDR']);


//echo $_SERVER['REMOTE_ADDR'];
//var_dump($info);
// init curl object        
//echo $info['city'];
if (!isset($_SESSION['latitude'])) $_SESSION['latitude'] = $info['latitude'];
if (!isset($_SESSION['longitude'])) $_SESSION['longitude'] = $info['longitude'];
if (!isset($_SESSION['country']) && !empty($info['country_name'])) $_SESSION['country'] = $info['country_name'];


if (empty($_SESSION['latitude'])) $_SESSION['latitude'] = '42.698334';
if (empty($_SESSION['longitude'])) $_SESSION['longitude'] = '23.319941';


$db_host = "host.docker.internal";
$db_user = "root";
$db_pass = "";
$db_name = "espravki";

mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8");


//if(!isset($_SESSION['city']) && !empty($info['city'])) $_SESSION['city']=$info['city'];

//unset($_SESSION['city']);
if (!empty($info['city']) && !isset($_SESSION['city'])) {
	//echo $aa['geoplugin_city'],'aa';
	$query = 'select name from cities where en_name = "' . mysql_real_escape_string($info['city']) . '"';
	$result = mysql_query($query) or die(send_error($query, $_SERVER["REQUEST_URI"], $_SERVER["PHP_SELF"], $error = mysql_error(), $_SERVER['REMOTE_ADDR']));
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		$row = mysql_fetch_assoc($result);
		$_SESSION['city'] = $row['name'];
	}
}

define('MyConst', TRUE);
define("WebSite", "https://www.espravki.com"); // add https:// before the address - example https://www.zoo-paradise.com Place empty if it is not used!
define("WebSite2", "https://localhost"); // add https:// before the address - example https://localhost or https://zoo-paradise.com. Place empty if it is not used!
define("ShortDomainName", "espravki.com"); // do not add https:// before the address - example hyp
define("ShortDomainName2", "localhost"); // do not add https:// before the address - example localhost
define("WebHome", "/home/infojnug/addon-domain/espravki.com/"); // add / at the begining and the end of the string!
define("SitePath", ""); // add / at the beginning and the end of the string!
define("ProjectSubDir", ""); // add / at the end of the string!
define("ImagesSubDir", "images/"); // add / at the end of the string!
define("AdmSubDir", "admin/"); // add / at the end of the string!
define("webCSSDir", SitePath . ProjectSubDir);
define("servTemplateDir", WebHome . SitePath . ProjectSubDir);
define("webShopHome", SitePath);
define("servShopHome", WebHome . SitePath);
define("webImagesDir", SitePath . ProjectSubDir . ImagesSubDir);
define("servImagesDir", servShopHome . ProjectSubDir . ImagesSubDir);
define("servFontDir", servShopHome . ProjectSubDir . "/fonts/");
define("webAdmImagesDir", SitePath . AdmSubDir . ImagesSubDir);
define("servAdmImagesDir", servShopHome . AdmSubDir . ImagesSubDir);
define("servAdminIcons", WebSite . SitePath . ImagesSubDir . "interface/icons/");
define("servImagesCompaniesDir", WebHome . SitePath . ImagesSubDir . "companies/");
define("default_country", "GB");
define("default_language", "en");
define("default_admin_language", "bg");
define("default_application", "customer");/*site-a ot strana na customers*/
define("default_usergroup", "undefined");
define("_hj_debug", true);
define("default_tables_rows_count", 30);
define("default_search_result_rows", 1);
define("defined_gallery_limit", 20);
define("default_template", "template1.php");
define("official_mail_sender", "info@espravki.com"); //e-mail ot koito se prashtat maili kum ostanalite potebiteli info@weddingburg.com
define("official_mail_sender_name", "Espravki.com - намери каквото търсиш");
define("official_site_name", "www.espravki.com");
define("purchases_schedules_columns_count", 10);
define("default_template_color", "yellow");
define("hc_error_log", 1);
define("hc_module_id", 1);
define("hc_account_id", 611);
define("default_encoding", "UTF-8");
define("send_mail_opened", 1);
define("current_currency", "EUR");
define("current_currency_sign", "€");
define("tax", "96.00");
define("premiumtax", "96.00");
define("analytics", "<script async src='https://www.googletagmanager.com/gtag/js?id=UA-18165888-8'></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-18165888-8');</script>");
define("google_api", "AIzaSyA7tS3MOMTGQGHjCUydbTh8A6-LHppesuo");
define("paysera", "43df1271f869519e2d181677962f6617");

define("site_phone", "+359894404189");
define("hostname", "espravki.com/");
define("hostnameshort", "espravki");
define("mail_name", "Espravki.com");
define("serverhome", "espravki.com/");

define("full_domain_name", "www.espravki.com");
//social profiles
define("facebook_page", "https://www.facebook.com/profile.php?id=61579651808137");
define("twitter_page", "#");
define("instagram", "https://www.instagram.com/espravki.comm");
define("tiktok", "https://www.tiktok.com/@espravki?is_from_webapp=1&sender_device=pc");
define("google_page", "https://plus.google.com/u/0/b/111742125661456387344/111742125661456387344/posts");
define("pinterest_page", "#");
define("linkedin", "#");
$usehttps = false;


//unset($_SESSION);
/*
function detect_city_fast2($ip){
	$c='-';
	if(!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost') return $c;
	$useragent='Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.18) Gecko/20110628 Ubuntu/10.10 (maverick) Firefox/3.6.18';
	$url='http://api.ipinfodb.com/v3/ip-city/?key=2fc53ebd63c943fc0a469e288d376fbc5ee8e3748415e602abe7c8d8bc04985d&ip='.urlencode($ip);
	$ch=curl_init();
	$curl_opt=array(
		CURLOPT_FOLLOWLOCATION => false,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERAGENT => $useragent,
		CURLOPT_URL => $url,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_REFERER => 'http://'.$_SERVER['HTTP_HOST'],
	);
	curl_setopt_array($ch, $curl_opt);
	$content=curl_exec($ch);
	curl_close($ch);
	if(strlen($content)){
		$content=explode(';',$content);
		$content[5]= preg_replace("/[^A-Za-z0-9 ]/", '', $content[5]);
		$c=$content[5];
		if(!empty($content[6])) $c=preg_replace("/[^A-Za-z0-9 ]/", '', $content[6]);
	}
	
	return $c;
}*/

/*
if(empty($_SESSION['city'])){

//$aa = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
//var_dump($aa);
//$city = detect_city_fast2($_SERVER['REMOTE_ADDR']);

$ip = $_SERVER['REMOTE_ADDR'];
$c='-';
	if(!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost') return $c;
	$useragent='Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.18) Gecko/20110628 Ubuntu/10.10 (maverick) Firefox/3.6.18';
	$url='http://api.ipinfodb.com/v3/ip-city/?key=2fc53ebd63c943fc0a469e288d376fbc5ee8e3748415e602abe7c8d8bc04985d&ip='.urlencode($ip);
	$ch=curl_init();
	$curl_opt=array(
		CURLOPT_FOLLOWLOCATION => false,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERAGENT => $useragent,
		CURLOPT_URL => $url,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_REFERER => 'http://'.$_SERVER['HTTP_HOST'],
	);
	curl_setopt_array($ch, $curl_opt);
	$content=curl_exec($ch);
	curl_close($ch);
	if(strlen($content)){
		$content=explode(';',$content);
		$content[5]= preg_replace("/[^A-Za-z0-9 ]/", '', $content[5]);
		$c=$content[5];
		if(!empty($content[6])) $c=preg_replace("/[^A-Za-z0-9 ]/", '', $content[6]);
	}
	
	$city = $c;
if(!empty($city)){
$query = 'select name from cities where en_name = "'.mysql_real_escape_string($city).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_assoc($result);
	$_SESSION['city'] = $row['name'];
	
			}
			
		}
		if(!empty($aa['geoplugin_latitude'])) $_SESSION['latitude'] = $aa['geoplugin_latitude'];
		if(!empty($aa['geoplugin_longitude'])) $_SESSION['longitude'] = $aa['geoplugin_longitude'];	
}*/
