<?php
require '../config.php';
require '../connect.php';
function create_url($s = ''){
  $c = mb_strtolower((trim($s)), 'UTF-8');
  $c = preg_replace ( '/[^0-9\p{Cyrillic}\p{Ll}\w]/u', '-', $c);
  $c = htmlentities(strip_tags($c), ENT_QUOTES, 'UTF-8');
  return rtrim($c,'-');
}
function send_error($query,$url1,$url2,$error,$user_ip){
$message="error in ".$_SERVER["REQUEST_URI"]."\r\n".$_SERVER["PHP_SELF"]."\r\n<br />".$query."\r\n<br />".$error."\r\n<br />User:".$user_ip;
mail(official_mail_sender,"error in ".WebSite."",$message);
}

$query='select p.id,models from products p, products_to_categories ptc where ptc.product_id=p.id and ptc.category_id="4" group by p.id';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
echo $num_rows = mysql_num_rows($result);echo '<br>';
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	echo $row[0].'-'.$row[1];echo '<br>';
	//$row[1]=htmlspecialchars($row[1]);
	//echo $newmodel=str_replace('&quot;',' инча',$row[1]);echo '<br>';
	//echo $newmodel= preg_replace('/[^0-9.,]*/','',$row[1]).' инча дисплей';echo '<br>';
	//echo $q='update products set models="'.$newmodel.'" where id="'.$row[0].'"';
	//$r=mysql_query($q) or die();
	}


/*$query='select p.id,p.models,bg_product_name,bg_short_description from products p, products_to_categories ptc where 
p.id=ptc.product_id and ptc.category_id=21 and models!="" group by p.id';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
echo $num_rows = mysql_num_rows($result);echo '<br>';
//$num_rows=1;
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	$aa=substr($row[2], 0, 10);
	$aa=rtrim($aa,'(');
	$inch = str_replace('&quot;','\'\' дисплей',$aa);
	echo $row[0].'-'.$row[1].' - '.$inch.'<br>';
	$unique_name=create_url($row[0].'- Лаптоп '.$row[1].' '.$inch);
	$unique_name=str_replace('--','-',$unique_name);
	$row[3] = str_replace(array('<br />','<br/>'),'<br>',htmlspecialchars_decode($row[3]));

	$updquery='update products set makes="'.$row[1].'", models="'.$inch.'",bg_product_name="Лаптоп '.$row[1].' '.$inch.'",
	unique_name="'.$unique_name.'", bg_short_description="'.htmlspecialchars($row[2]).'",additional_info="'.htmlspecialchars($row[3]).'" where id="'.$row[0].'"';
	//mysql_query($updquery) or die(mysql_error());
	}*/
	
/*$query='select p.id,unique_name,bg_product_name from products p, products_to_categories ptc where 
p.id=ptc.product_id and ptc.category_id=21 and models!="" group by p.id';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);echo '<br>';
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	$q='select id,image,type from products_gallery where product_id="'.$row[0].'" order by id';
	$res=mysql_query($q) or die(send_error($q,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
echo $num_r = mysql_num_rows($res);echo '<br>';
	for($k=0;$k<$num_r;$k++){
	$rw=mysql_fetch_row($res);
	//echo $rw[1].'<br>';
	if(file_exists('../images/products/'.$rw[2].'/'.$rw[1])){
	echo 'file exists ../images/products/'.$rw[2].'/'.$rw[1].' - '.$row[2];echo '<br>';
	echo $newfile='../images/products/'.$rw[2].'/'.$row[1].'-1.jpg';echo '<br>';
	echo $oldfile='../images/products/'.$rw[2].'/'.$rw[1];
	//rename($oldfile,$newfile);
	//echo $updquery='update products_gallery set image="'.$row[1].'-1.jpg'.'" where id="'.$rw[0].'"';
	//mysql_query($updquery) or die(mysql_error());
	}else echo 'file doesnt exists'.$rw[0];echo '<br>';
	
	
		}
	}*/
?>