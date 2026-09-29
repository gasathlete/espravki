<?php
require_once '../config.php';
require_once '../connect.php';
require_once '../project_functions.php';

    $q = strtolower(sanitaze_text($_GET["term"]));
    $return = array();
	$row['unique_name']='';
    $query = mysql_query("SELECT id,bg_category,url,parent FROM products_categories WHERE (bg_category LIKE '%".mysql_real_escape_string($q)."%' or meta_title LIKE '%".mysql_real_escape_string($q)."%') AND visible='1' group by id order by bg_category ASC LIMIT 10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
	if($row['parent'] > 0){
	array_push($return,
		array('label'=>$row['bg_category'],
		'value'=>$row['bg_category']));
	}else{
	$qu='select id from products_categories where parent="'.mysql_real_escape_string($row['id']).'"';
	$result=mysql_query($qu) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$qu);
	if(mysql_num_rows($result) < 1){
	array_push($return,
		array('label'=>$row['bg_category'],
		'value'=>sanitaze_category($row['bg_category'])));
			}
		}
    }
    echo(json_encode($return));
 exit; 
?>