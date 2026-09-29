<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){
$_SESSION['is_human'] = 1;
require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name && isset($_SESSION['is_human'])){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';

$response = array();

if(!empty($_REQUEST['t']) && $_REQUEST['t'] == 'a' && !empty($_REQUEST['v']) && !empty($_REQUEST['aid'])){

$check = 'SELECT * FROM statistics_adverts_visits where advert_id = "'.mysql_real_escape_string($_REQUEST['aid']).'" and ip="'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'"';
$result=mysql_query($check) or die(send_error($check,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows < 1){
$insert = 'insert into statistics_adverts_visits set advert_id ="'.mysql_real_escape_string($_REQUEST['aid']).'", 
ip="'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'",
voted="'.mysql_real_escape_string($_REQUEST['v']).'", approved = "1"';
$insertresult=mysql_query($insert) or die(send_error($insert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$rating = echo_advert_rating($_REQUEST['aid']);
$html = '<input type="number" readonly="readonly" class="rating-value star" name="rating-stars-value" value="'.$rating[3].'">
<div class="rating-stars-container mr-2">
<div class="rating-star sm">
<i class="fa fa-star"></i>
</div>
<div class="rating-star sm">
<i class="fa fa-star"></i>
</div>
<div class="rating-star sm">
<i class="fa fa-star"></i>
</div>
<div class="rating-star sm">
<i class="fa fa-star"></i>
</div>
<div class="rating-star sm">
<i class="fa fa-star"></i>
</div>
</div> ( '.$rating[1].' ) '.$lang['votes'];
$response[0] = $html;
$response[1] = 2;
}else{
$response[0] = $lang['alredy_voted'];
$response[1] = 2;
}
	

}

elseif(!empty($_REQUEST['name']) && !empty($_REQUEST['comment']) && !empty($_REQUEST['star']) && !empty($_REQUEST['aid'])){
$cid = 0;
if(isset($_SESSION['customer'])) $cid = $_SESSION['customer'];

$check = 'SELECT * FROM statistics_adverts_visits where advert_id = "'.mysql_real_escape_string($_REQUEST['aid']).'" and ip="'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'"';
$result=mysql_query($check) or die(send_error($check,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows < 1){
$insert = 'insert into statistics_adverts_visits set advert_id ="'.mysql_real_escape_string($_REQUEST['aid']).'", 
ip="'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'",
cid = "'.mysql_real_escape_string($cid).'",
username = "'.mysql_real_escape_string($_REQUEST['name']).'",
comment = "'.mysql_real_escape_string(strip_tags($_REQUEST['comment'])).'",
voted="'.mysql_real_escape_string($_REQUEST['star']).'", approved = "0"';
$insertresult=mysql_query($insert) or die(send_error($insert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

$response[0] = $lang['saved_comment'];
$response[1] = 1;
}else{
$row=mysql_fetch_assoc($result);
if(empty($row['comment'])){

$update = 'update statistics_adverts_visits set advert_id ="'.mysql_real_escape_string($_REQUEST['aid']).'", 
cid = "'.mysql_real_escape_string($cid).'",
username = "'.mysql_real_escape_string($_REQUEST['name']).'",
comment = "'.mysql_real_escape_string(strip_tags($_REQUEST['comment'])).'",
voted="'.mysql_real_escape_string($_REQUEST['star']).'", approved = "0" where id = "'.mysql_real_escape_string($row['id']).'"';
$updateresult=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$response[0] = $lang['saved_comment'];
$response[1] = 1;
}else{
$response[0] = $lang['alredy_voted'];
$response[1] = 2;
		}
	}
}
else{
$response[0] = 0;
$response[1] = 0;
}
//echo $response;
echo(json_encode($response));

	}
}
?>