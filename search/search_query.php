<?php
/* Search query builder, moved unchanged from search/index.php so the classic page and the
   v3 search page return exactly the same results. Produces $query (first 12) and $allquery. */
if(empty($_SESSION['latitude'])) $_SESSION['latitude'] = '42.700000762939';
if(empty($_SESSION['longitude'])) $_SESSION['longitude'] = '23.333299636841';
$query='SELECT a.id as aid,supplier_name,link_name,meta_description,site,a.added_date,small_image,town,area,postcode,vip,longitude,latitude,company_address,company_phones, opening, closing, pc.url, bg_category, a.business_type, a.sellbusiness,   
(
   6371 *
   acos(cos(radians('.$_SESSION['latitude'].')) * 
   cos(radians(latitude)) * 
   cos(radians(longitude) - 
   radians('.$_SESSION['longitude'].')) + 
   sin(radians('.$_SESSION['latitude'].')) * 
   sin(radians(latitude )))
) AS distance 
FROM adverts a,products_categories pc, adverts_to_product_categories atpc 
WHERE a.selected_plan < 3 and a.id=atpc.advert_id and atpc.category_id=pc.id and a.active="1" ';

if(isset($_GET['category']) && !empty($_GET['category'])) $categorylink = $_GET['category'];
if(!empty($categorylink)){
$query.= ' and (pc.url="'.mysql_real_escape_string($categorylink).'") ';
}


if(isset($search) && !empty($search)){

if(empty($_GET['exact_match'])){
$a = '';
$search1 = '';
$a=array(' ',"\t","\r","\n",'\\','\'','"','<','>','?','!','@','#','$','%','^','&','*','(',')','_','-','+','|', '~','`',',','.','/','{','}','[',']',':',';');
	$searchterm=str_replace($a,'%',$search);
	
	$a=explode('%',$searchterm);
	//var_dump($a);
	$searchterm='%';
	if(count($a)>1){
		foreach($a as $k=>$v){
		//echo $v,'<br/>';
			if(mb_strlen($v,'utf8')>=3){
				if(mb_strpos('аоиеуя',mb_substr($v,-1,1,'utf8'))!==false){
					$searchterm=mb_substr($v,0,(mb_strlen($v,'utf8')-1),'utf8').'';
					
					$aconstruct.=' meta_keywords LIKE "%'.$searchterm.'%" AND';
					$aconstruct1.=' supplier_name LIKE "%'.$searchterm.'%" AND';
					$aconstruct2.=' bg_category LIKE "%'.$searchterm.'%" AND';
				}
				else{
				$searchterm.=$v.' ';
				$aconstruct.=' meta_keywords LIKE "'.$searchterm.'%" AND';
				$aconstruct1.=' supplier_name LIKE "'.$searchterm.'%" AND';
				$aconstruct2.=' bg_category LIKE "'.$searchterm.'%" AND';
				
				}
				
				
			}else{
			$aconstruct.=' meta_keywords LIKE "%'.$search.'%" AND';
			$aconstruct1.=' supplier_name LIKE "%'.$search.'%" AND';
			$aconstruct2.=' bg_category LIKE "%'.$search.'%" AND';
			}
		}
		
	$query.= ' and ( ('.rtrim($aconstruct,'AND').') or ('.rtrim($aconstruct1,'AND').') or ('.rtrim($aconstruct2,'AND').') or 
	(bg_description LIKE "%'.$search.'%") or 
	(keywords LIKE "%'.$search.'%")
	)';
		/*$search1='%';
		$a=explode('%',$searchterm);
		$a=array_reverse($a);
		foreach($a as $k=>$v) $search1.=$v.'%';*/
	}
	elseif(preg_match('/^[a-zA-Z\p{Cyrillic}\d\s\-]+$/u', $a[0])){
	
	if(mb_strpos('аоиеуя',mb_substr($a[0],-1,1,'utf8'))!==false){
		$searchterm.=mb_substr($a[0],0,(mb_strlen($a[0],'utf8')-1),'utf8').'%';
		$query.= ' and ( REPLACE(supplier_name,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" 
or REPLACE(bg_category,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" or 
REPLACE(bg_description,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" or
meta_keywords LIKE "'.mysql_real_escape_string($searchterm).'"  or 
	(bg_description LIKE "%'.$search.'%") or 
	(keywords LIKE "%'.$search.'%")
)';
	//exit();
		}else{
		$searchterm.=$a[0].'%';
		$query.= ' and ( REPLACE(supplier_name,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" 
or REPLACE(bg_category,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" or 
REPLACE(bg_description,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" or
meta_keywords LIKE "'.mysql_real_escape_string($searchterm).'" or 
	(bg_description LIKE "%'.$search.'%") or 
	(keywords LIKE "%'.$search.'%")
)';
		}
	}
	else{
	$searchterm.=$a[0].'%';
	$query.= ' and ( REPLACE(supplier_name,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" 
or REPLACE(bg_category,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'%" or 
REPLACE(bg_description,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" or
meta_keywords LIKE "'.mysql_real_escape_string($searchterm).'%" or 
	(bg_description LIKE "%'.$search.'%") or 
	(keywords LIKE "%'.$search.'%")
)';
	}

//$searchterm = preg_replace('/[^0-9 \w_]+/u', '', $search);

$search_exploded = explode ( " ", $search );
//var_dump($search_exploded);

/*
 $x = 0;
 $construct = "";
 $construct1= "";
 $construct2= "";
 if(count($search_exploded) > 1){
 foreach( $search_exploded as $search_each ) {
  $x++;

  if(mb_strlen($search_each, 'UTF-8') > 2){
  $construct .=' meta_keywords LIKE "%'.$search_each.'%" AND';
  $construct1.=' supplier_name LIKE "%'.$search_each.'%" AND';
  $construct2.=' bg_category LIKE "%'.$search_each.'%" AND';
  
  }


 }
// $query.= ' and ( ('.rtrim($construct1,'AND').') or ('.rtrim($construct1,'AND').') or ('.rtrim($construct2,'AND').') )';

}else{
$query.= ' and ( REPLACE(supplier_name,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" 
or REPLACE(bg_category,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'%" or 
REPLACE(bg_description,"\'","") LIKE "'.mysql_real_escape_string($searchterm).'" or
meta_keywords LIKE "'.mysql_real_escape_string($searchterm).'%"  
)';
}
*/

	}else{
	//$query.= ' and (a.supplier_name LIKE "%'.mysql_real_escape_string($_GET['search']).'%" || meta_keywords LIKE "%'.$search.'%") ';
	$query.= ' and (MATCH(a.supplier_name) AGAINST ("'.mysql_real_escape_string($_GET['search']).'" IN BOOLEAN MODE) || meta_keywords LIKE "%'.$search.'%") ';

	}

}

/*if(isset($_GET['search_city'])){
if(!empty($_GET['search_city']) && preg_match("/^[a-zA-Z\p{Cyrillic} \s\-]+$/u", $_GET['search_city'])){
$query.= ' and town = "'.mysql_real_escape_string($_GET['search_city']).'"';
	}else $_GET['search_city'] = '';
}*/


if(!empty($_GET['city_match'])){
if(!empty($_GET['search_city']) && preg_match("/^[a-zA-Z\p{Cyrillic} \s\-]+$/u", $_GET['search_city'])){
$query.= ' and town = "'.mysql_real_escape_string($_GET['search_city']).'"';
	}
}

$query.=' group by a.id ';



if(!isset($_GET['sort']) || empty($_GET['sort'])){
$query.= ' order by a.vip DESC, distance ASC';
}else{
	if($_GET['sort'] == 'distance') $query.=' order by distance ASC,vip DESC';
	if($_GET['sort'] == 'newest') $query.=' order by a.added_date DESC';
	if($_GET['sort'] == 'vip' || $_GET['sort'] == 'all'){
	 
	$query.=' order by a.vip DESC, distance ASC';
	}
}
$allquery = $query;
$query.=' limit 12';
