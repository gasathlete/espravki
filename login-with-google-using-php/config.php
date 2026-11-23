<?php
session_start();
require '../config.php';
include_once("src/Google_Client.php");
include_once("src/contrib/Google_Oauth2Service.php");
######### edit details ##########

// Google keys live in the main config.php (not in git):
//   define("google_client_id", "...");  define("google_client_secret", "...");
// The previous OAuth client was deleted in Google Cloud and its keys were removed from this file.
$clientId = defined('google_client_id') ? google_client_id : ''; //Google CLIENT ID
$clientSecret = defined('google_client_secret') ? google_client_secret : ''; //Google CLIENT SECRET
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