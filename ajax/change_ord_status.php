<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';

$html='';

$response = array();
if(isset($_SESSION['customer'])){
if(isset($_REQUEST['id']) && (strlen($_REQUEST['id']) == 32) && isset($_REQUEST['status']) && array_key_exists($_REQUEST['status'], $lang['statuses'])){
$query='select o.id as oid, order_status, p.cid as sup_id,a.supplier_name, a.mail as advert_mail, c.customer_names, customer_email, o.cid as cust_id  
from orders o, products p, adverts a, customers c where o.order_secret = "'.mysql_real_escape_string($_REQUEST['id']).'" and 
(o.cid=c.id or o.cid = 0) and o.pid=p.id and o.aid=a.id group by o.id order by order_date ASC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);

if($num_rows > 0){
$row=mysql_fetch_assoc($result);
	if($row['sup_id'] == $_SESSION['customer'] || $row['cust_id'] == $_SESSION['customer']){
	
	$update = 'update orders set order_status = "'.mysql_real_escape_string($_REQUEST['status']).'" where order_secret = "'.mysql_real_escape_string($_REQUEST['id']).'"';
	$updateresult=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	
	$sendmail = 0;
	if($row['sup_id'] == $_SESSION['customer']){
	if($_REQUEST['status'] == 'canceled'){
	$response[0] = $lang['cancel_success2'];
	$response[2] = 1;
	
	//sending mail to customer 
	$email_subject=$lang['canceled_order'];
	$mail_text= $lang['dear'].' '.$row['customer_names'].', <br />'.$lang['canceled_order1'].' '.$drow[0].' '.$lang['canceled_order2'].' '.$row['supplier_name'].'	<br />
	'.$lang['canceled_order3'].'<br /><br />'.$lang['successful_reg2'];
	$email = $row['customer_email'];
	$sendmail = 1;
	}
	else $response[0] = $lang['suc_save'];
	}
	
	if($row['cust_id'] == $_SESSION['customer']){
	if($_REQUEST['status'] == 'canceled'){
	$response[0] = $lang['cancel_success'];
	$response[2] = 1;
	
	
	//sending mail to trader 
	$email_subject=$lang['canceled_order'];
	$mail_text= $lang['dear'].' '.$row['supplier_name'].', <br />'.$lang['canceled_order1'].' '.$row['oid'].' '.$lang['canceled_order22'].'<br />
	<br /><br />'.$lang['successful_reg2'];
	$email = $row['advert_mail'];
	$sendmail = 1;
	}
	else $response[0] = $lang['suc_save'];
	
	}
	
	if($_REQUEST['status'] == 'finished') $response[2] = 1;
	
	
	if($sendmail > 0){
	$params=array(
	"content_type"=>"text/html",
	"replay_to"=>official_mail_sender,
	"from_name"=>mail_name,
	"from_email"=>official_mail_sender,
	"bcc"=>official_mail_sender,
	"to"=>$email,
	"subject"=>$email_subject,
	"body"=>$mail_text
	);
	send_mail($params);
	//var_dump($params);
	
	}
	$response[1] = 1;
		}
	}


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