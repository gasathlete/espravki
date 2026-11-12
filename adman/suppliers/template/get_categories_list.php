<?php
require_once '../../../config.php';
$default_model='';
if(!empty($_GET['default_model'])) $default_model=$_GET['default_model'];

mysql_connect($db_host,$db_user,$db_pass,$db_name);
mysql_select_db($db_name);
mysql_set_charset('utf8');

$pid= $_REQUEST['pid'];

//$q='DELETE from products_to_makes WHERE pid ="'.mysql_real_escape_string($pid).'"';
//$r=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
	
$html='';
	$checked_values=array();
	if($pid!=0){
		$query="SELECT category_id FROM products_to_categories WHERE product_id='".mysql_real_escape_string($pid)."'";
		$result=mysql_query($query) or hjdie();
		if(mysql_num_rows($result)){
			while($rw=mysql_fetch_row($result)){
				$checked_values[$rw[0]]=1;
			}
		}
	}
	
	$que='select product_category_id,pc.bg_category from adverts_to_products_categories atpc, products_categories pc where 
	atpc.advert_id="'.mysql_real_escape_string($_REQUEST['advid']).'" and pc.id=atpc.product_category_id group by pc.id';
	$res=mysql_query($que) or die($que);
	$num_rows=mysql_num_rows($res);
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($res);
	$checked='';
	if(array_key_exists($row[0],$checked_values)) $checked=' checked="true"';
	$html.='<div>
	<input class="categories" type="radio" name="categories[]" id="c'.$row[0].'" value="'.$row[0].'"'.$checked.'>
	<label for="c'.$row[0].'">'.$row[1].'</label></div>';
	}
	
	echo $html;
?>