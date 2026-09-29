<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
exit();
require './config.php';
require './project_functions.php';
//bg_category!="Детски градини" and bg_category!="Посолства" and 
$query='SELECT supplier_name, town, bg_category, postcode, company_phones, a.id as aid, a.mail, a.pass  
FROM adverts a,products_categories pc, adverts_to_product_categories atpc 
 WHERE a.id=atpc.advert_id and atpc.category_id=pc.id and bg_category = "ТД на НАП" and customer_id!="0" group by a.id order by added_date DESC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
echo $num_rows=mysql_num_rows($result);echo '<br/>';
//exit();
if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_assoc($result);
echo $row['aid'],' ',$row['supplier_name'],'-',$row['bg_category'];echo '<br/>';


echo $updateworktime= 'update adverts set closing="17:30", work_days="'.mysql_real_escape_string('a:5:{i:0;s:3:"mon";i:1;s:3:"tue";i:2;s:3:"wed";i:3;s:3:"thu";i:4;s:3:"fri";}').'" where id = "'.$row['aid'].'"';
$updateworktimeresult=mysql_query($updateworktime) or die(send_error($updateworktime,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

/*
echo $check = 'select id from customers where id = "489"';echo '<br/>';
$checkresult=mysql_query($check) or die(send_error($check,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
echo $checknum_rows=mysql_num_rows($checkresult);echo '<br/>';
if($checknum_rows > 0){
$crow=mysql_fetch_assoc($checkresult);

echo $upd = 'update adverts set customer_id="'.$crow['id'].'" where id="'.$row['aid'].'"';echo '<br/>';
$updresult=mysql_query($upd) or die(send_error($upd,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
echo 'aaaaaaaaaaaaaaaaaaaa';echo '<br/>';
//update
}else{
//insert
echo $insert = 'insert into customers set 
customer_names = "'.$row['supplier_name'].'",
customer_email = "'.$row['mail'].'",
password = "'.$row['pass'].'",
customer_phone = "'.$row['company_phones'].'",
city = "'.$row['town'].'",
postcode = "'.$row['postcode'].'",
reg_code = "1357911",
active = "1"';echo '<br/>';
$insertresult=mysql_query($insert) or die(send_error($insert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

echo $last_id = mysql_insert_id();echo '<br/>';
echo $upd = 'update adverts set customer_id="'.$last_id.'" where id="'.$row['aid'].'"';echo '<br/>';
$updresult=mysql_query($upd) or die(send_error($upd,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

}*/

	}
}
?>