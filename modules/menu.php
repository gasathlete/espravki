<?php
session_start();
require '../config.php';
require '../connect.php';
require '../project_functions.php';
require '../translation/lang.php';

global $lang;

$categories = array();
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc,adverts a where 
pc.id=atpc.category_id and atpc.advert_id=a.id and a.active="1" group by a.id order by pc.id ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
$html='';

if($num_rows){
$html.= '<ul id="catmenu">';
while($row = mysql_fetch_assoc($result)){
    $categories[$row['parent']][$row['id']] = $row['id'];
}
//if(!empty($_SESSION['advert_id'])) var_dump($categories);
$countcats=0;
foreach($categories as $key => $category){
	//echo $row[0].'-a<br/>';
    //echo $key.' <br/>';
	if($key > 0){
		$subhtml='<ul class="subul">';
    foreach($category as $item){
    //echo $item.'- tuk<br/>';
	$subquery='SELECT pc.id, pc.bg_category, pc.parent, pc.url FROM products_categories pc WHERE pc.id="'.$item.'" group by pc.id ORDER BY pc.bg_category asc';	
	$subresult=mysql_query($subquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$subquery);
	$subnum_rows=mysql_num_rows($subresult);
	$subrow=mysql_fetch_row($subresult);
	
	$subhtml.='<li><a href="'.WebSite.'/business-directory/'.$subrow[3].'">'.$subrow[1].'</a></li>';
	}
	$subhtml.='</ul>';
	//taking their mother category
	$q='select id, bg_category, url from products_categories where id="'.$key.'"';
	$r=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
	$num_r=mysql_num_rows($r);
	if($num_r > 0){
	$countcats++;
	//if($countcats < 12){
	$rws = mysql_fetch_row($r);
	$html.='<li class="main with">+ '.$rws[1].''.$subhtml.'</li>';
			//}
		}
	}else{
	foreach($category as  $key => $item){
	$q='select id, bg_category, url from products_categories where id="'.$key.'"';
	$r=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
	$rws = mysql_fetch_row($r);

	$html.='<li class="main without"><a href="'.WebSite.'/business-directory/'.$rws[2].'">'.$rws[1].'</a></li>';
		}
	}	
}

	$html.= '<li class="last"><a href="'.WebSite.'/business-directory/a-z/">'.$lang['view_all'].'</a></li></ul>';
	
	 echo $html;
	 
/*for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	//echo $row[0];echo '<br>';
	if($row[2] > 0){
	$subhtml='<ul class="subul">';
	$subquery='SELECT pc.id, pc.bg_category, pc.parent, pc.url FROM products_categories pc WHERE pc.id="'.$row[2].'" group by pc.id ORDER BY pc.bg_category asc';	
	$subresult=mysql_query($subquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$subquery);
	$subnum_rows=mysql_num_rows($subresult);
	$subrow=mysql_fetch_row($subresult);
	
	$subhtml.='<li><a href="'.WebSite.'/business-directory/'.$row[3].'">'.$row[1].'</a></li>';
	$subhtml.='</ul>';
	//if($row[2]==$subrow[0]) echo $row[2];
	$html.='<li class="main with">'.$subrow[1].''.$subhtml.'</li>';
	
		}else{
		$html.='<li class="main"><a href="'.WebSite.'/business-directory/'.$row[3].'">'.$row[1].'</a></li>';
		}
	}*/
	
	//$html.=$subhtml;

	}
?>