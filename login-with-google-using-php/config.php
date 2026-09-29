<?php
session_start();
require '../config.php';
include_once("src/Google_Client.php");
include_once("src/contrib/Google_Oauth2Service.php");
######### edit details ##########

$clientId = '889124978323-pkrf36t8in9ci83rsskq3i1k6hoicsii.apps.googleusercontent.com'; //Google CLIENT ID
$clientSecret = 't_FzNrieTC_Ss9sTt3XR9lSb'; //Google CLIENT SECRET
$redirectUrl = WebSite.'/login-with-google-using-php/index.php';  //return url (url to script)
$homeUrl = WebSite.'/index.php';  //return to home

##################################

$gClient = new Google_Client();
$gClient->setApplicationName('Login to '.mail_name);
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>