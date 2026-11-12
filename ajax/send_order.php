<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';
//require '../geoip.php';

$response = array();
$msg = '';
if(isset($_SESSION['customer']) && (intval($_SESSION['customer']) > 0) && (isset($_SESSION['products']) && count($_SESSION['products']) > 0) && 
!empty($_REQUEST['address']) && (mb_strlen($_REQUEST['address'], 'UTF-8') >= 10) && !empty($_REQUEST['phone']) && (strlen($_REQUEST['phone']) > 8)){
//var_dump($_REQUEST['qty']);echo '<br/>';
//var_dump($_SESSION);echo '<br/>';


if(empty($_SESSION['customer_phone']) || empty($_SESSION['customer_address'])){
$updatecustomer = 'update customers set 
customer_phone = "'.mysql_real_escape_string(sanitaze_text(strip_tags($_REQUEST['phone']))).'",
customer_address = "'.mysql_real_escape_string(sanitaze_text(strip_tags($_REQUEST['address']))).'" where id = "'.mysql_real_escape_string($_SESSION['customer']).'"';
$updateresult=mysql_query($updatecustomer) or die(send_error($updatecustomer,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$_SESSION['customer_phone'] = $_REQUEST['phone'];
$_SESSION['customer_address'] = $_REQUEST['address'];
}


$adverts_array = array();
foreach($_SESSION['products'] as $pkey=>$pval){

if(!empty($pval) && $pval > 0){
//echo $_SESSION['products'][$pkey];echo ' - ',$pkey,'<br/>';
$queryp = 'select p.advert_id, p.title, p.price,p.promo_price, p.product_url, p.category_url, a.supplier_name, a.mail from products p, adverts a where 
p.id = "'.mysql_real_escape_string($pkey).'" and p.advert_id = a.id and p.active="1" and p.sold="0" and p.deleted="0" and a.active="1" and a.deleted="0" 
group by p.id order by p.advert_id ASC, p.id asc';
$presult=mysql_query($queryp) or die(send_error($queryp,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($presult);


if($num_rows > 0){
$prow=mysql_fetch_assoc($presult);
//echo $prow['mail'];
//echo $prow['advert_id'];echo '<br/>';
if(!array_key_exists($prow['advert_id'],$adverts_array)){
$adverts_array[$prow['advert_id']][] = $prow['advert_id'];
array_push($adverts_array[$prow['advert_id']],$prow['mail'],$prow['supplier_name']);
		}
$option = '';
if(isset($_SESSION['product_options'][$pkey]) && !empty($_SESSION['product_options'][$pkey])){
if(is_array($_SESSION['product_options'][$pkey])){
	//foreach($_SESSION['product_options'][$pkey] as $opt) $option.= $opt.';';
	$option = serialize($_SESSION['product_options'][$pkey]);
	}
}
$secret=randomPassword();

if($prow['promo_price'] > 0) $price = $prow['promo_price'];
else $price = $prow['price'];
$insertorder = 'insert into orders set 
cid = "'.mysql_real_escape_string($_SESSION['customer']).'",
aid = "'.mysql_real_escape_string($prow['advert_id']).'",
pid = "'.mysql_real_escape_string($pkey).'",
qty = "'.mysql_real_escape_string($pval).'",
price = "'.mysql_real_escape_string($price).'",
options = "'.mysql_real_escape_string($option).'",
delivery_address = "'.mysql_real_escape_string($_REQUEST['address']).'",
order_comments = "'.mysql_real_escape_string(sanitaze_text(strip_tags($_REQUEST['comments']))).'",
order_secret = "'.md5($secret).'"';//echo '<br/>';
$iresult=mysql_query($insertorder) or die(send_error($insertorder,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

		}		
	}
}
//var_dump($adverts_array);echo '<br/>';
if(!empty($adverts_array)){
foreach($adverts_array as $akey => $aval){
$mail_text = '';

		$email_subject = $lang['new_order'].' '.official_site_name;              
		$mail_text= $lang['hello'].' '.$aval[2].',<br />'.
		$mail_text.= $lang['received_order'].'<br />'; 
		$params=array(
		"content_type"=>"text/html",
		//"replay_to"=>$_POST['email'],
		"from_name"=>mail_name,
		"from_email"=>official_mail_sender,
		"bcc"=>"",
		"to"=>$aval[1],
		"subject"=>$email_subject,
		"body"=>$mail_text
		);
		
		send_mail($params);
		//var_dump($params);	echo '<br/>';echo '<br/>';
	}
	unset($_SESSION['product_options']);
	unset($_SESSION['products']);
	
	$response[0] = $lang['ord_success'];
	$response[1] = 1;
	$response[2] = count($_SESSION['products']);
	}else{
	$response[0] = $lang['data_not_collected'];
	$response[1] = 0;
	}
	
	

}else{
$response[0] = $lang['data_not_collected'];
$response[1] = 0;
}
echo(json_encode($response));

	}
}
?>