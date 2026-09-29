<?php
require_once '../config.php';
require_once '../connect.php';
require_once '../project_functions.php';

    $q = strtolower(sanitaze_text($_GET["term"]));
    $return = array();
	$row['category_name']='';
    $query = mysql_query("SELECT category_name FROM products WHERE (category_name LIKE '%".mysql_real_escape_string($q)."%' ) group by category_name order by category_name ASC LIMIT 10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
	array_push($return,
		array('label'=>$row['category_name'],
		'value'=>$row['category_name']));
	
    }
	//var_dump($return);
    echo(json_encode($return));
 exit; 
?>