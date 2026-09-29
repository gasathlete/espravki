<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){
require_once '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require_once '../project_functions.php';
//echo $_SESSION['city'];
    $q = strtolower(sanitaze_text($_REQUEST["term"]));
	$q = preg_replace('/[^0-9 \w_]+/u', '', $q);
	if(isset($_REQUEST["cat"]) && !empty($_REQUEST["cat"])) $cat = preg_replace('/[^0-9 \-\w_]+/u', '', sanitaze_text($_REQUEST["cat"]));
    $return = array();
	$example=array();
    $query = "SELECT a.id,a.meta_keywords,a.meta_title, a.town, pc.url FROM adverts a, products_categories pc, adverts_to_product_categories atpc WHERE 
	atpc.advert_id = a.id and atpc.category_id = pc.id ";
if(!empty($cat)) $query.= " and pc.url = '".mysql_real_escape_string($cat)."' ";	
	$query.= " and (a.meta_keywords LIKE '%".mysql_real_escape_string($q)."%' or a.meta_title LIKE '%".mysql_real_escape_string($q)."%' or 
	REPLACE(a.supplier_name,'\'','') LIKE '%".mysql_real_escape_string($q)."%' 
	) 
	AND a.active='1' group by a.id order by a.meta_keywords ASC LIMIT 10";
	//echo $query;
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

	$categoryquery = "SELECT pc.id, pc.bg_category, pc.parent, pc.url FROM products_categories pc, adverts_to_product_categories atpc WHERE 
	pc.id = atpc.category_id and (bg_category LIKE '%".mysql_real_escape_string($q)."%' or bg_description LIKE '%".mysql_real_escape_string($q)."%') 
	AND visible='1' group by pc.id order by pc.bg_category ASC LIMIT 10";
	$categoryresult=mysql_query($categoryquery) or die(send_error($categoryquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	
	while ($categoryrow = mysql_fetch_array($categoryresult)){
	//echo $categoryrow['parent'],' - ',$categoryrow['bg_category'];echo '<br/>';
	//if($categoryrow['parent'] == "0"){
	//echo $categoryquery = "SELECT id, bg_category, parent FROM products_categories WHERE id = '".$categoryrow['parent']."'
	// group by id order by bg_category ASC LIMIT 10";
	//}
	//array_push($return,$categoryrow['bg_category']);
	//array_push($return,
	//	array('categoryurl'=>$categoryrow['url']));
	array_push($return,
		array('label'=>str_replace('###','',$categoryrow['bg_category']).' - '.$lang['pro'],
		'cat'=>$categoryrow['url'],
		'value'=>trim(str_replace('###','',$categoryrow['bg_category'])),
		'town'=>$row['town']));
	}
	
	while ($row = mysql_fetch_array($result)){
	$example = explode(',',$row['meta_keywords'].','.$row['meta_title'].','.$row['town']);
	array_push($return,
		array('label'=>$row['meta_title'].' - '.$row['town'],
		'cat'=>$row['url'],
		'value'=>$row['meta_title'],
		'town'=>$row['town']));
    }
	//var_dump($example);
	
	$example = array_unique(array_filter($example));
	$matches = array_filter($example, function($var) use ($q) { return preg_match("/$q(.*)/ui", $var); });

   if(!empty($matches)){
   foreach($matches as $match){
   if(!empty($match)){
	/*array_push($return,
		array('label'=>$match,
		'value'=>$match,
		'town'=>$match));*/
			}
		}
	}
    echo (json_encode($return));
	exit;
	}
}
?>