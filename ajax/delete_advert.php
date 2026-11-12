<?php
session_start();
$_SESSION['is_human'] = 1;
if(isset($_SESSION['is_human'])  && isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';
if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';

$html='';

$response = array();
if(isset($_SESSION['customer'])){
if(isset($_REQUEST['aid']) && intval($_REQUEST['aid'] > 0)){
		
			$query='select id from adverts where id="'.mysql_real_escape_string($_REQUEST['aid']).'" and customer_id="'.mysql_real_escape_string($_SESSION['customer']).'"';
			$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			$num_rows=mysql_num_rows($result);
			if($num_rows > 0){

			$update = 'update adverts set deleted = "1" where id="'.mysql_real_escape_string($_REQUEST['aid']).'" and customer_id="'.mysql_real_escape_string($_SESSION['customer']).'"';
			$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			
			
			$response[0] = $lang['suc_del'];
			$response[1] = $html;
			

				}
			}
					echo(json_encode($response));
		}
	}
}
?>