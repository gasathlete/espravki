<?php
session_start();
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){

require '../config.php';

if($_SERVER['HTTP_HOST'] == full_domain_name){
require '../translation/'.$_SESSION['lang'].'_lang.php';
require '../project_functions.php';

$response = array();

if(!empty($_REQUEST['t']) && !empty($_REQUEST['v']) && !empty($_REQUEST['viewtype']) && $_REQUEST['t'] == 'articles'){
//loading results
function count_comments($aid){

$count = 0;
$query = 'select count(id) from comments_to_articles where article_id="'.$aid.'" and active="1"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
if(mysql_num_rows($result) > 0){
	$row=mysql_fetch_row($result);
	$count = $row[0];
	}
	return $count;
}

$start = ($_REQUEST['v']+1);
$query='SELECT unique_name, bg_meta_description, bg_article_title, small_image, category_id, added_date, id FROM articles WHERE active="1"';
if(isset($_REQUEST['cat']) && !empty($_REQUEST['cat'])) $query.= ' and category_id="'.mysql_real_escape_string($_REQUEST['cat']).'"';
else $query.= ' and category_id="2"';
if(!empty($_REQUEST['searchnews'])) $query.= ' and (
	bg_article_title LIKE "%'.mysql_real_escape_string($_REQUEST['searchnews']).'%" or 
	bg_meta_description LIKE "%'.mysql_real_escape_string($_REQUEST['searchnews']).'%" or 
	bg_tags LIKE "%'.mysql_real_escape_string($_REQUEST['searchnews']).'%" 
	)';
$query.=' order by added_date DESC limit '.$start.', 4;';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$result1=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);

	setlocale(LC_ALL, 'bg_BG.UTF-8');
	if($num_rows >0){

	//if($_REQUEST['viewtype'] == 'tab-12'){
	for($i=1;$i<=$num_rows;$i++){
	$row=mysql_fetch_row($result);
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];

	if(!file_exists(WebHome.'/images/articles/'.$image.'')) $image = 'no_product_image.jpg';
	
	
	$html12.='
	<div data-id="'.($i+$_REQUEST['v']).'" class="col-lg-6 col-md-6 col-xl-6 snglart">
		<div class="card">
			<div class="item7-card-img h250p oh">
				<a href="./'.$row[0].'">
				<img src="../images/articles/'.$image.'" alt="'.$row[2].'" class="cover-image"/>
				<div class="item7-card-text">
					<span class="badge badge-success">'.$lang['artcle_categories'][$row[4]].'</span>
				</div></a>
			</div>
			<div class="card-body">
				<div class="item7-card-desc d-flex mb-2">
					<a href="#"><i class="fa fa-calendar-o text-muted mr-2"></i>'.strftime("%a, %d %B %Y", strtotime($row[5])).' г.</a>
					<div class="ml-auto">
						<a class="mr-0" href="#"><i class="fa fa-comment-o text-muted mr-2"></i>'.count_comments($row[6]).' '.$lang['a_comments'].'</a>
					</div>
				</div>
				<a href="./'.$row[0].'" class="text-dark"><h4 class="font-weight-semibold h40p">'.$row[2].'</h4></a>
				<p class="desc">'.$row[1].'</p>
				<a href="./'.$row[0].'" class="btn btn-secondary btn-sm">'.$lang['read'].'</a>
			</div>
		</div>
	</div>';
		}
	//}	
	
	//if($_REQUEST['viewtype'] == 'tab-11'){
//echo '<div class="tab-pane active" id="tab-11">';
	for($a=1;$a<=$num_rows;$a++){
	$row=mysql_fetch_row($result1);
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];

	if(!file_exists(WebHome.'/images/articles/'.$image.'')) $image = 'no_product_image.jpg';
	
	$html11.='
	<div data-id="'.($a+$_REQUEST['v']).'" class="card overflow-hidden snglart">
	<div class="row no-gutters blog-list">
		<div class="col-xl-4 col-lg-12 col-md-12">
			<div class="item7-card-img">
				<a href="./'.$row[0].'"></a>
				<img src="../images/articles/'.$image.'" alt="'.$row[2].'" class="cover-image">
				<div class="item7-card-text">
					<span class="badge badge-success">'.$lang['artcle_categories'][$row[4]].'</span>
				</div>
			</div>
		</div>
		<div class="col-xl-8 col-lg-12 col-md-12">
			<div class="card-body">
				<a href="./'.$row[0].'" class="text-dark"><h4 class="font-weight-semibold mb-2">'.$row[2].'</h4></a>
				<div class="item7-card-desc d-flex mb-3">
					<a href="#"><i class="fa fa-calendar-o text-muted mr-2"></i>'.strftime("%a, %d %B %Y", strtotime($row[5])).' г.</a>
					<a href="#"><i class="fa fa-user text-muted mr-2"></i>'.$lang['admin'].'</a>
					<div class="ml-auto">
						<a class="mr-0" href="#"><i class="fa fa-comment-o text-muted mr-2"></i>'.count_comments($row[6]).' '.$lang['a_comments'].'</a>
					</div>
				</div>
				<p class="mb-1 leading-tight">'.$row[1].'</p>
				<a href="./'.$row[0].'" class="btn btn-secondary btn-sm mt-4">'.$lang['read'].'</a>
			</div>
		</div>
	</div>
</div>';
		}
	//}
//$response[0] = $html;
$response[11] = $html11;
$response[12] = $html12;
$response[1] = 1;
}else{
$html = $lang['no_results'];
$response[0] = $html;
$response[1] = 0;
}

if($num_rows > 0 && $num_rows < 4){
//$html.= '<div data-id="'.($a+$_REQUEST['v']).'" class="card overflow-hidden tac">'.$lang['no_news'].'</div>';
$response[11] = $html11.'<div data-id="'.($a+$_REQUEST['v']).'" class="card overflow-hidden tac">'.$lang['no_news'].'</div>';
$response[12] = $html12.'<div data-id="'.($a+$_REQUEST['v']).'" class="card overflow-hidden tac">'.$lang['no_news'].'</div>';
//$response[0] = $html;
$response[1] = 0;
	}
}

elseif(!empty($_REQUEST['t']) && !empty($_REQUEST['v']) && !empty($_REQUEST['viewtype']) && $_REQUEST['t'] == 'adverts'){
$start = ($_REQUEST['v']+1);

if(empty($_SESSION['latitude'])) $_SESSION['latitude'] = '42.700000762939';
if(empty($_SESSION['longitude'])) $_SESSION['longitude'] = '23.333299636841';
$query='SELECT a.id as aid,supplier_name,link_name,meta_description,site,a.added_date,small_image,town,area,postcode,vip,longitude,latitude,company_address,business_price, 
company_phones, opening, closing, pc.url, bg_category, a.business_type, a.sellbusiness,  
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
WHERE ';
if($_REQUEST['for_sale'] < 1) $query.='a.selected_plan < 3 ';
else $query.='a.selected_plan > 2 ';

$query.=' and a.id=atpc.advert_id and atpc.category_id=pc.id and a.active="1" ';


if((!isset($_REQUEST['search']) && !empty($categorylink)) || (empty($_REQUEST['search']) && !empty($_REQUEST['cat']))){
$query.= ' and pc.url="'.mysql_real_escape_string($_REQUEST['cat']).'" ';
}

$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";
$_REQUEST['search'] = trim(preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll}\w]/u', ' ', $_REQUEST['search']));;
$_REQUEST['search'] = str_replace(array('"',"'",')','('),' ',$_REQUEST['search']);

if(!empty($_REQUEST['search']) && preg_match($pattern, $_REQUEST['search'])){
$search = $_REQUEST['search'];
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

	}else{
	$query.= ' and a.supplier_name LIKE "%'.mysql_real_escape_string($search).'%" ';

	}

}

if(isset($_REQUEST['ex_city']) && !empty($_REQUEST['ex_city'])){
if(!empty($_REQUEST['ex_city']) && preg_match("/^[a-zA-Z\p{Cyrillic} \s\-]+$/u", $_REQUEST['ex_city'])){
$query.= ' and town = "'.mysql_real_escape_string($_REQUEST['ex_city']).'"';
	}else $_REQUEST['ex_city'] = '';
}

$query.=' group by a.id ';

if(!isset($_REQUEST['sort'])) $query.= ' order by a.vip DESC, a.town = "'.mysql_real_escape_string($_SESSION['city']).'"';
else{
	if($_REQUEST['sort'] == 'distance') $query.=' order by distance ASC,vip DESC';
	if($_REQUEST['sort'] == 'newest') $query.=' order by a.added_date DESC';
	if($_REQUEST['sort'] == 'vip' || $_REQUEST['sort'] == 'all') $query.='  order by a.vip DESC, distance ASC';
	}
$query.=' limit '.$_REQUEST['v'].', 4';

//echo $query;

$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);

$markers = '';
$listings = '';
$head = '';
$cities = array();
$advertcities = array();
$distance = array();
if($num_rows > 0){
	for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);

if(!in_array($row['town'],$advertcities)) array_push($advertcities,$row['town']);
	if(empty($row['small_image'])){
	$image = '/products/no_product_image.jpg';
	}else $image = '/adverts/'.$row['small_image'];
	
$your_date = strtotime($row['added_date']);
$datediff = time() - $your_date;
$new = round($datediff / (60 * 60 * 24));

$worktime = $lang['no_info'];
 if($row['opening'] != '00:00' || $row['closing'] != '00:00'){
 $worktime =$row['opening'].'-'.$row['closing'];
	$now = time();//echo 'now<br/>';
	$begin = strtotime($row['opening']);//echo 'beg<br/>';
	$end = strtotime($row['closing']);//echo 'end<br/>';
	$opened = '<span class="badge badge-danger ml-2 fs-13">'.$lang['closed'].'</span>';
	$tdlt = 'tdlt';
	if (($begin < $end && $now >= $begin && $now <= $end) || ( $begin > $end && ( $now >= $begin || $now <= $end))) {
   $opened = '<span class="badge badge-success ml-2 fs-13">'.$lang['op'].'</span>';
   $tdlt = '';
	}
 }
if($row['opening'] == '00:00' && $row['closing'] == '00:00') $opened = '<span class="badge badge-success ml-2 fs-13">'.$lang['op'].'</span>';

$rating = echo_advert_rating($row['aid']);

$listings.='
<div id="a'.$row['aid'].'" class="card overflow-hidden snglad" data-id="'.(($i+1)+$_REQUEST['v']).'">';
if($new <= 7){
$listings.= '<div class="ribbon ribbon-top-left text-danger"><span class="bg-danger">'.$lang['new'].'</span></div>';
}else{
if($row['vip'] > 0) $listings.='<div class="power-ribbon power-ribbon-top-left text-warning"><span class="bg-warning"><i title="'.$lang['reccom'].'" class="fa fa-bolt"></i></span></div>';
}
$listings.='
<div class="d-md-flex">
	<div class="item-card9-img">';
	if($_REQUEST['for_sale'] < 1){
		$listings.='<div class="item-card9-imgs h200p">
			<a href="'.WebSite.'/business-directory/'.$row['url'].'/'.$row['link_name'].'"></a>
			<img src="'.WebSite.'/images/'.$image.'" alt="'.$row['supplier_name'].'" class="cover-image"/>
		</div>
		<div class="item-cardreview-absolute bg-secondary z99"><a class="text-white" target="_blank" href="'.WebSite.'/business-directory/'.$row['url'].'/">'.$row['bg_category'].'</a></div>';
	}else{
	$listings.='<div class="item-card9-imgs pt-3">
			<a href="'.WebSite.'/business-for-sale/'.$row['url'].'/'.$row['link_name'].'"></a>
			<img src="'.WebSite.'/images/'.$image.'" alt="'.$row['supplier_name'].'" class="cover-image"/>
		</div>
		
		<div class="item-cardreview-absolute bg-light border z99">
		<div class="item-card9-cost ffo ib mt-2 text-dark fs-18">
			 <label>'.$lang['prd_price'].'</label>: ';
			 setlocale(LC_MONETARY, 'bg_BG.UTF-8');
			 $listings.=money_format('%.2n',$row['business_price']);
			$listings.='</div>
		</div>';
	}
	
	$listings.='</div>
	<div class="card border-0 mb-0">
		<div class="card-body h-100">
			<div class="item-card9">
				<a target="_blank" href="'.WebSite.'/business-directory/'.$row['url'].'/'.$row['link_name'].'" class="text-dark">
				<h4 class="font-weight-semibold mt-1 mb-1 ib w48">'.$row['supplier_name'].' </h4></a>
				<div class="ib w48 dist pt-2 tar"><i class="fa fa-compass" aria-hidden="true"></i> 
				'.number_format($row['distance'],2).' km '.$lang['dist2'].'
				<div class="text-dark font-weight-normal mb-0 mt-0 item-card2-desc">
					<a target="_blank" href="https://www.google.com/maps/place/'.$row['latitude'].','.$row['longitude'].'/"><i class="fa fa-map-signs"></i>  '.$lang['map_dir'].'</a>
					</div>
				</div>
				<div class="rating-stars d-flex mr-5">
					<input type="number" readonly="readonly" class="rating-value star" name="rating-stars-value" value="3">
					<div class="rating-stars-container mr-2">'.$rating[2].'</div>
					<a class="fs-13 leading-tight mt-1" href="#">'.$rating[1].' '.$lang['otz'].'</a>
				</div>
				<div class="mt-2 mb-2">
				<p class="desc">'.strip_tags(htmlspecialchars_decode($row['meta_description'])).'</p>
				</div>
				<div class="mt-2 mb-2">
					<i class="fa fa-map-marker mr-1"></i> '.$row['town'].' '.$row['postcode'].' '.$row['company_address'].'
				</div>
				<p class="mb-0 leading-tight"><span class="font-weight-semibold text-dark"> '.$lang['worktime'].' : </span>  '.$worktime.' '.$opened.'</p> 
			</div>
		</div>
		<div class="card-footer pt-2 pb-2">
			<div class="item-card9-footer d-sm-flex">
				<div class="item-card9-cost ib w48">
					<div class="text-dark font-weight-normal mb-0 mt-0 item-card2-desc">
						<a target="_blank" href="'.WebSite.'/business-directory/'.$row['url'].'/'.$row['link_name'].'" ><i class="fa fa-envelope"></i></a>
						<a class="phone_dialer" data-aid="'.$row['aid'].'" href="tel:'.$row['company_phones'].'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$row['company_phones'].'"><i class="fa fa-phone"></i></a>';
						if($row['vip'] < 1) $listings.='<a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$row['site'].'"><i class="fa fa-globe"></i></a>';
						else $listings.='<a target="_blank" href="'.$row['site'].'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$row['site'].'"><i class="fa fa-globe"></i></a>';
					$listings.='</div>
				</div>
				<div class="ml-auto ib w48 tar mt-3 mt-sm-0">
				<div class="text-dark ib mb-0 mt-0 ml-3 item-card2-desc">
				<a target="_blank" href="'.WebSite.'/business-directory/'.$row['url'].'/'.$row['link_name'].'" class="btn btn-sm btn-info">'.$lang['view_profile'].'</a>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>';



	}
$response[0] = $listings;
//$response[2] = $query;
$response[1] = 1;
//$response[2] = $query;
}else{
$response[0] = '<div data-id="'.(1+$_REQUEST['v']).'" class="card overflow-hidden tac">'.$lang['no_more_results'].'</div>';
$response[1] = 0;
}

if($num_rows > 0 && $num_rows < 4){
$response[0] = $listings.='<div data-id="'.(1+$_REQUEST['v']).'" class="card overflow-hidden tac">'.$lang['no_more_results'].'</div>';
$response[1] = 0;
	}
}

elseif(!empty($_REQUEST['t']) && !empty($_REQUEST['v']) && !empty($_REQUEST['viewtype']) && $_REQUEST['t'] == 'products'){
$start = ($_REQUEST['v']+1);

$query='select p.id, product_url, category_name, category_url, title, price, promo_price, p.image, p.description, p.addeddate, supplier_name, link_name, pc.url 
from products p, adverts a, adverts_to_product_categories atpc, products_categories pc  
where p.active="1" and p.sold="0" and p.deleted="0" and p.checked_by_admin = "1" and p.advert_id = a.id and a.id = atpc.advert_id and atpc.category_id = pc.id';
if(!empty($_REQUEST['search'])){
$pattern  = '/[^\w_ ]+/u';
//filter_var ( $_REQUEST['search'], FILTER_SANITIZE_STRING);
$_REQUEST['search'] = preg_replace($pattern, '', strip_tags($_REQUEST['search']));
$query.=' and ( p.category_name like "%'.mysql_real_escape_string($_REQUEST['search']).'%" || 
p.title like "%'.mysql_real_escape_string($_REQUEST['search']).'%" || p.product_tags like "%'.mysql_real_escape_string($_REQUEST['search']).'%" || 
p.title_latin like "%'.mysql_real_escape_string($_REQUEST['search']).'%" || p.tags_latin like "%'.mysql_real_escape_string($_REQUEST['search']).'%")';

}

if(isset($_REQUEST['offer_type']) && (intval($_REQUEST['offer_type']) > 0 && intval($_REQUEST['offer_type']) < 3)) $query.=' and offer_type = "'.mysql_real_escape_string($_REQUEST['offer_type']).'" ';
if(isset($_SESSION['catlist']) && !empty($_SESSION['catlist'])){
//var_dump($_SESSION['catlist']);
$catlist = $_SESSION['catlist'];
$query.=' and p.category_id IN (' . implode(",", $catlist) . ')';
}else{
 if(!empty($_REQUEST['cat']) && preg_match("/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u", $_REQUEST['cat'])) $query.=' and p.category_url = "'.mysql_real_escape_string($_REQUEST['cat']).'"';
}

if(isset($_REQUEST['make']) && !empty($_REQUEST['make'])){
$_REQUEST['make'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_REQUEST['make'])));
$query.=' and make = "'.mysql_real_escape_string($_REQUEST['make']).'" ';
}

if(isset($_REQUEST['model']) && !empty($_REQUEST['model'])){
$_REQUEST['model'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_REQUEST['model'])));
$query.=' and model = "'.mysql_real_escape_string($_REQUEST['model']).'" ';
}

if(isset($_REQUEST['modification']) && !empty($_REQUEST['modification'])){
$_REQUEST['modification'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_REQUEST['modification'])));
$query.=' and modification = "'.mysql_real_escape_string($_REQUEST['modification']).'" ';
}

$query.=' group by p.id ';
if(!isset($_REQUEST['sort'])) $query.= ' order by a.vip DESC, p.price ASC';
else{
if($_REQUEST['sort'] == 'cheap') $query.=' order by p.price ASC';
if($_REQUEST['sort'] == 'newest') $query.=' order by p.addeddate DESC';
if($_REQUEST['sort'] == 'vip' || $_REQUEST['sort'] == 'all') $query.=' order by a.vip DESC, p.price ASC';
}

$query.=' limit '.$_REQUEST['v'].', 12';

$listings = echo_products_everywhere($query);
//echo strlen($listings);
if($listings[1] < 1) $listings[0]= '<div class="col-lg-12 col-md-12 col-xl-12 mb-5 nomoreproducts"><div class="card mb-0"><div class="card-header pr panel-title"><h3 class="card-title">'.$lang['no_more_prs'].'</h3></div></div></div>';
$response[0] = $listings[0];
$response[1] = 1;
$response[2] = $listings[1];
}
else{
$response[0] = 'error';
$response[1] = 0;
}
//echo $response;
echo(json_encode($response));

	}
}
?>