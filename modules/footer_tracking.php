<?php
// Visit statistics (used by business owners' dashboards). Shared by the classic footer and the v3 layout.
// Moved unchanged from modules/footer.php.

if(isset($_COOKIE['partner_id'])) {
if(empty($_SESSION['affiliate_id'])) $_SESSION['affiliate_id']=$_COOKIE['partner_id'];
}

$affiliate_id = 0;
if(!empty($_SESSION['affiliate_id'])) $affiliate_id = $_SESSION['affiliate_id'];


$error='NE';
if(!empty($msg)) $error=$msg;

$ref='';
if(empty($_POST['adverts'])){
$advert_id=0;
}else $advert_id=$_POST['adverts'];

if(isset($_SERVER['HTTP_REFERER'])) $ref=$_SERVER['HTTP_REFERER'];
elseif(isset($_ENV['HTTP_REFERER'])) $ref=$_ENV['HTTP_REFERER'];

if(!isset($_SERVER['HTTP_USER_AGENT'])) $_SERVER['HTTP_USER_AGENT'] = 'unknown HTTP_USER_AGENT';

$user_type = 'guest';
if(isset($_SESSION['advert_name'])){
$user_type = 'advert';
$advert_id = $_SESSION['advert_id'];
}


$search = '';
if(isset($_POST['search']) && !empty($_POST['search'])) $search = $_POST['search'];
if(isset($_POST['search_text']) && !empty($_POST['search_text'])) $search = $_POST['search_text'];
if(isset($_GET['search']) && !empty($_GET['search'])) $search = $_GET['search'];

if(!_bot_detected()){
	$query='insert into statistics set user_type="'.mysql_real_escape_string($user_type).'",
	ip="'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'",
	city="'.mysql_real_escape_string(@$_SESSION['city']).'",country="'.mysql_real_escape_string(@$_SESSION['country']).'",
	url="'.mysql_real_escape_string($_SERVER['REQUEST_URI']).'", timest=now(), sid="'.mysql_real_escape_string(session_id()).'",
	user_agent="'.mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']).'",
	referer="'.mysql_real_escape_string($ref).'",
	post="'.mysql_real_escape_string(var_export($_POST,true)).'",
	advert_id="'.mysql_real_escape_string($advert_id).'",on_site="1",
	user_latitude="'.mysql_real_escape_string(@$_SESSION['latitude']).'", user_longitude="'.mysql_real_escape_string(@$_SESSION['longitude']).'",errorsession="'.mysql_real_escape_string($error).'",
	from_affiliate = "'.mysql_real_escape_string($affiliate_id).'"';
	if(!empty($search)) $query.=',searched_term="'.mysql_real_escape_string($search).'"';
	mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	}

if(!isset($_SESSION['real_user'])) $_SESSION['real_user'] = 1;
