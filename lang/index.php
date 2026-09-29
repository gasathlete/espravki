<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"].'/config.php';
if(isset($_GET['lang']) && ($_GET['lang'] == 'bg' || $_GET['lang'] == 'en' || $_GET['lang'] == 'ru' )){
unset($_SESSION['lang']);
$_SESSION['lang'] = $_GET['lang'];
$newurl = WebSite;

//echo $_SESSION['lang'];
//exit();
if(isset($_SERVER['HTTP_REFERER'])){
$newurl = str_replace(array('/en/','/bg/','/ru/'),'/'.$_GET['lang'].'/',$_SERVER['HTTP_REFERER']);
header("Location: ".$newurl);
}else{
header("Location: ".$newurl);
} 
exit(0);

}else{
header("Location: ".WebSite);
exit(0);

}
//echo $_SERVER['HTTP_REFERER'];
?>