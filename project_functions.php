<?php
$info = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
//echo '<br />';
//echo $info['city'].'aaaaaaaaaaa';
//echo '<br />';


//var_dump($info);

//if($info['country_code']=="VN" || $info['country_code']=="BR" || $info['country_code']=="CN" || $info['country_code']=="IN" || $info['country_code']=="RU" || $info['country_code']=="UA"){
//if($info['country_code']=="VN" || $info['country_code']=="BR" || $info['country_code']=="CN" || $info['country_code']=="RU" || $info['country_code']=="UA"){
/*
if((isset($_SESSION['country']) && !empty($_SESSION['country'])) && $_SESSION['country'] !='Bulgaria'){
if(!_bot_detected()){
echo '<h1>Not Found</h1><br />';
echo 'The requested URL was not found on this server.<br />';
echo 'Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.';
//header('Location: https://www.google.com/');
exit(0);
	}
}
*/


if(isset($_GET)){
foreach($_GET as $k => $v){
	if(is_array($v)){
		foreach($v as $kk=>$kv)
			$_GET[$k][$kk]=sanitize_post_get($kv);
		}else $_GET[$k] = sanitize_post_get($v);		
	}
}
if(isset($_POST)){
foreach($_POST as $k => $v) {
	if(is_array($v)){
		foreach($v as $kk=>$kv){
			if(is_array($_POST[$k][$kk])){
				foreach($_POST[$k][$kk] as $kkk=>$kkv) $_POST[$kk][$kkk]=sanitize_post_get($kkv);
				}else $_POST[$k][$kk]=sanitize_post_get($kv);
			}
		}else $_POST[$k] = sanitize_post_get($v);
	}
}


if(isset($_REQUEST)){
foreach($_REQUEST as $k => $v){
	if(is_array($v)){
		foreach($v as $kk=>$kv)
			$_REQUEST[$k][$kk]=sanitize_post_get($kv);
		}else $_REQUEST[$k] = sanitize_post_get($v);
	} 
}

if(isset($_COOKIE)){
foreach($_COOKIE as $k => $v) {
	if(is_array($v)){
		foreach($v as $kk=>$kv){
			if(is_array($_COOKIE[$k][$kk])){
				foreach($_COOKIE[$k][$kk] as $kkk=>$kkv) $_COOKIE[$kk][$kkk]=sanitize_post_get($kkv);
				}else $_COOKIE[$k][$kk]=sanitize_post_get($kv);
			}
		}else $_COOKIE[$k] = sanitize_post_get($v);
	}
}

function sanitize_post_get($str){
//blokirane na opiti za hak prez url-to
if(is_array($str)){
$str=array_map('trim',$str);
}else $str = trim($str);//echo '<br/>';
$urltocheck=$_SERVER['REQUEST_URI'];
    $str = str_replace(array('"','„','`','“'),"'", $str);
	
return $str; 
}

function checkRootDomain($url,$dom){
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }

    $domain = implode('.', array_slice(explode('.', parse_url($url, PHP_URL_HOST)), -2));
    if ($domain == $dom) {
        return True;
    } else {
        return False;
    }

}

function _bot_detected(){
  if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Googlebot|bot|crawl|robospider|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
    return TRUE;
  }else{
    return FALSE;
  }
}

function translate($str){
    $tr = array( 
    "А"=>"a", "Б"=>"b", "В"=>"v", "Г"=>"g", "Д"=>"d", 
    "Е"=>"e", "Ё"=>"yo", "Ж"=>"J", "З"=>"z", "И"=>"i",  
    "Й"=>"j", "К"=>"k", "Л"=>"l", "М"=>"m", "Н"=>"n",  
    "О"=>"o", "П"=>"p", "Р"=>"r", "С"=>"s", "Т"=>"t",  
    "У"=>"u", "Ф"=>"f", "Х"=>"H", "Ц"=>"ts", "Ч"=>"ch",  
    "Ш"=>"sh", "Щ"=>"sht", "Ъ"=>"a", "Ы"=>"y", "Ь"=>"",  
    "Э"=>"e", "Ю"=>"yu", "Я"=>"ya", "а"=>"a", "б"=>"b",  
    "в"=>"v", "г"=>"g", "д"=>"d", "е"=>"e", "ё"=>"yo",  
    "ж"=>"j", "з"=>"z", "и"=>"i", "й"=>"j", "к"=>"k",  
    "л"=>"l", "м"=>"m", "н"=>"n", "о"=>"o", "п"=>"p",  
    "р"=>"r", "с"=>"s", "т"=>"t", "у"=>"u", "ф"=>"f",  
    "х"=>"h", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"sht",  
    "ъ"=>"a", "ы"=>"y", "ь"=>"", "э"=>"e", "ю"=>"yu",  
    "я"=>"ya", "."=>" ", ","=>" ", "/"=>"-",   
    ":"=>"", ";"=>"","—"=>"", "_"=>"","+"=>"", "'"=>"","%"=>"", "&"=>"","#"=>"", "@"=>"","("=>"", ")"=>"","!"=>"", "$"=>"","^"=>"", "amp"=>"","*"=>""
    ); 
return strtr($str,$tr); 
}

function echo_product_options_new_basket($pid){
$html = '';
	$query='SELECT * FROM product_options WHERE product_id = "'.mysql_real_escape_string($pid).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$html = array();
	for($a=0;$a<$num_rows;$a++){
	$row=mysql_fetch_assoc($result);
	$optionnames = explode(";",$row['option_values']);
	$html[$row['option_name']] = array();
	foreach($optionnames as $name){
	if(!empty($name)) array_push($html[$row['option_name']],$name);
			}
		}
	}
	return $html;
}

function echo_product_options_new($pid){
$html = array();
	$query='SELECT * FROM product_options WHERE product_id = "'.mysql_real_escape_string($pid).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	for($a=0;$a<$num_rows;$a++){
	$row=mysql_fetch_assoc($result);
	$optionnames = explode(";",$row['option_values']);
	$html[$row['option_name']]=$row['option_values'];
	//foreach($optionnames as $optkey=>$option){
	//$html[1][]=$row['option_values'];
	//		}
		}
	}
	return $html;
}

function get_product_options($pid){
$html = array();
	$query='SELECT * FROM product_options WHERE product_id = "'.mysql_real_escape_string($pid).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	for($a=0;$a<$num_rows;$a++){
	$row=mysql_fetch_assoc($result);
	$optionnames = explode(";",$row['option_values']);
	$html[0][]=$row['option_name'];
	//foreach($optionnames as $optkey=>$option){
	$html[1][]=$row['option_values'];
	//		}
		}
	}
	return $html;
}



function echo_business_for_sale(){

global $lang;
$query='SELECT supplier_name, link_name, small_image, country, business_price, town, bg_category, url, area, company_phones, opening, closing, meta_description, a.id, a.selected_plan, pc.id as pcid, a.added_date as date    
FROM adverts a,products_categories pc, adverts_to_product_categories atpc 
 WHERE a.active="1" and a.sellbusiness = "1" and a.id=atpc.advert_id and atpc.category_id=pc.id group by a.id order by added_date DESC limit 6';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$result2=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$categories = array();
for($a=0;$a<$num_rows;$a++){
	$catrow=mysql_fetch_assoc($result);
	if(!in_array($catrow['url'],$categories)) $categories[$catrow['url']] = $catrow['bg_category'];
	}
	
//var_dump($categories);
echo '<section class="sptb">
<div class="container">
<div class="section-title center-block text-center">
	<h2>',$lang['bfs'],'</h2>
	<p>',$lang['bfs2'],' ',official_site_name,'</p>
	
	
</div>
<div>
	<div class="items-gallery">
		<div class="items-blog-tab text-center">
			<div class="items-blog-tab-heading ib w100 row mb-0">
				<div class="col-12">
					<ul class="nav catsnav items-blog-tab-menu">
					<li class="cattabsli"><a href="'.WebSite.'/business-for-sale/" class="active show">',$lang['view_all_pr'],'</a></li>';
					foreach($categories as $key=>$val){
					echo '<li class="cattabsli"><a target="_blank" href="'.WebSite.'/business-for-sale/'.$key.'/">'.$val.'</a></li>';
					}
				echo '</ul>
				</div>
			</div>
			<div class="tab-content"><div class="tab-pane active" id="tab-1">
				<div class="row">';
			
			for($i=0;$i<$num_rows;$i++){
			$row=mysql_fetch_row($result2);
			setlocale(LC_ALL, 'bg_BG.UTF-8');
				
			if(empty($row[2])){
			$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/no_product_image.jpg"/>';
			}else{
			$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/adverts/'.$row[2].'"/>';
			if(!file_exists(WebHome.'/images/adverts/'.$row[2].'')) $image = '<img class="cover-image" alt="'.$row[0].'" src="'.WebSite.'/images/no_product_image.jpg"/>';
			}
			
			if($row[4] == '0.00'){
			$price = '<i class="fa fa-money" aria-hidden="true"></i> '.$lang['neg'];
			}else $price = '<i class="fa fa-money" aria-hidden="true"></i> '.$row[4].' '.$lang['bgn'];
			
			//$price = '<div class="item-card7-overlaytext"> <a href="business.html" class="text-white"> Beauty</a> <h4 class="mb-0">18% Off</h4> </div>';
			echo '
			<div class="col-xl-4 col-lg-4 col-md-12 mt20">
				<div class="card mb-xl-0">
					<div class="item-card8-img br-tr-4 br-tl-4 h250p overflow-hidden tac">
					
					<a href="./business-for-sale/',$row[7],'/',$row[1],'">'.$image.'</a>
					</div>
					<div class="item-card7-overlaytext">
						<h6 class="mb-0"><a class="tw" target="_blank" href="./business-for-sale/',$row[7],'">',$row[6],'</a></h6>
					</div>
					<div class="card-body">
						<div class="item-card8-desc h200p overflow-hidden">
							<p class="text-muted mb-2 ttc"><i class="fa fa-calendar-check-o mr-3 text-secondary"></i> '.strftime("%a, %d %B %Y", strtotime($row[16])).' г.</p>
							<h4 class="font-weight-semibold h40p">
							<a target="_blank" href="./business-for-sale/',$row[7],'/',$row[1],'">',$row[0],'</a></h4>
							<div>
							<a href="mb-1"><p class="pb-0 pt-0 mb-2 mt-2"><i class="fa fa-map-marker mr-2"></i>',$row[5],', ',$row[8],'</p></a>
							<a href="tel:',$row[9],'"><p class="pb-0 pt-0 mb-2 mt-2"><i class="fa fa-phone mr-2"></i>',$row[9],'</p></a>
						</div>
							<p class="mb-0 desc">',$row[12],'</p>
						</div>
					</div>
					<div class="card-footer">
				<div class="item-card2-footer">
					<div class="item-card2-footer-u">
						<div class="item-card9-cost ffo ib mt-2 tac text-dark fs-18 w100 pt-2 bg-light border">
			 <label>'.$lang['prd_price'].'</label>: ';
			 setlocale(LC_MONETARY, 'bg_BG.UTF-8');
			
			echo money_format('%.2n',$row[4]).'</div>
					</div>
				</div>
			</div>
				</div>
			</div>
			
			';
				}
		
	echo '</div></div></div>
			</div>
		</div>
	</div>
</div>
</section>';
	}
}


function get_advert_max_offers($aid){
$max_offers = 0;
$query='SELECT max_offers FROM adverts WHERE id = "'.mysql_real_escape_string($aid).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row = mysql_fetch_assoc($result);
$max_offers = $row['max_offers'];
	}
	return $max_offers;
}

function get_adverts_offers($aid,$sup_name){
global $lang;
	$query='SELECT p.id, p.title,p.description,p.category_name, a.max_offers, p.price,p.image, a.link_name, p.category_url,a.supplier_name,product_url,category_url, p.active, p.sold  
	FROM products p, adverts a  
	WHERE p.deleted="0" and p.active="1" and p.sold = "0" and p.advert_id=a.id and a.id = "'.mysql_real_escape_string($aid).'" group by p.id ';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	echo '
	<div class="card border-0 mb-0 p-2 overflow-hidden mt-3">
		<div class="ib w100 mobw100">
			<h3 class="card-title pt-2 mb-2">',$lang['of_from'],' ',$sup_name,'</h3>
			</div>
		<div class="card-body p-2 ads-tabs">';
	if($num_rows > 0){
		for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_assoc($result);
		$active = '<i title="'.$lang['unactive'].'" class="fa fa-check-square-o text-danger ml-3" aria-hidden="true"></i>';
		if($row['active'] > 0) $active = '<i title="'.$lang['active'].'" class="fa fa-check-square-o text-success ml-3" aria-hidden="true"></i>';
		
	$image = 'no_product_image.jpg';
	if(!empty($row['image'])) $image = $row['image'];
		
		echo '
		<div id="pid_',$row['id'],'" class="card overflow-hidden mt-2 card-absolute prsq">
			
			<div class="d-md-flex">
				<div class="item-card9-img">
					<div class="item-card9-imgs">
						<img src="',WebSite,'/images/products/',$image,'" alt="',$row['title'],'" class="cover-image" />
					</div>
					
				</div>
				<div class="card border-0 mb-0">
					<div class="card-body py-4">
						<div class="item-card9">
							<div class="d-flex">
								<a target="_blank" href="',WebSite,'/products/',$row['category_url'],'/">',$row['category_name'],'</a>
							</div>
							<a target="_blank" href="',WebSite,'/products/',$row['category_url'],'/',$row['product_url'],'" class="text-dark"><h4 class="font-weight-semibold mt-0">',$row['title'],' ',$active,'</h4></a>
							<div class="item-card2-desc mt-3">
								<div class="item-card2-desc-cost">
									<p class="pr_desc">',strip_tags($row['description']),'</p>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer py-3">
						<div class="row">
							<div class="col mt-2">
								<b class="ttu">',$lang['prd_price'],'</b> ',$row['price'],' ',current_currency,'
							</div>
							<div class="col col-auto">
								<a target="_blank" href="',WebSite,'/products/',$row['category_url'],'/',$row['product_url'],'" class="btn btn-info">',$lang['view_pr'],'</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		';
		}
	
	}else echo '<div class="form-group mt-5 tac"><label class="form-label text-dark">',$lang['no_of_p'],'</label></div>';
	
	if($show_add_btn > 0){
	if($num_rows < $max_offers){
	if($paid > 0 && $adactive > 0) echo '<div class="col-xl-12 col-lg-12 col-md-12 p-1 tac"><a href="./redirector.php?add_product=',$aid,'" class="btn btn-secondary m-3"><i class="fa fa-plus mr-2" aria-hidden="true"></i> ',$lang['add_offer'],'</a></div>';
	else echo '<div class="col-xl-12 col-lg-12 col-md-12 p-1 tac"><button title="'.$lang['wait_pay_or'].'" disabled class="btn btn-secondary m-3"><i class="fa fa-plus mr-2" aria-hidden="true"></i> ',$lang['add_offer'],'</button></div>';
		}
	}
	echo '</div></div>';
}


function count_adv_products($aid){
$offers = 0;
$query='SELECT p.id	FROM products p, adverts a WHERE p.deleted="0" and p.advert_id=a.id and a.id = "'.mysql_real_escape_string($aid).'" group by p.id ';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0) $offers = $num_rows;
	
	return $offers;
}

function echo_adverts_products($aid,$paid,$adactive,$show_add_btn,$sup_name){
global $lang;
	$query='SELECT p.id, p.title,p.description,p.category_name, a.max_offers, p.price, p.promo_price, p.image, a.link_name, p.category_url,a.supplier_name,product_url,category_url, p.active, p.sold  
	FROM products p, adverts a  
	WHERE p.deleted="0" and p.advert_id=a.id and a.id = "'.mysql_real_escape_string($aid).'" group by p.id ';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	$max_offers=get_advert_max_offers($aid);
	echo '
	<div class="card mb-0 overflow-hidden mt-3">
	<div class="ib w100 text-orange tac"></div>
		<div class="card-header ib">
		<div class="ib w50 mobw100">
			<h3 class="card-title pt-2 mb-2">',$lang['ur_pr'],' ',$lang['con'],' ',$sup_name,'</h3>
			<small class="ib w100">',$lang['you_can'],' ',$max_offers,' ',$lang['of_pr'],'</small>
			</div>
			<div class="ib w48 tar mobw100">',$num_rows,' / ',$max_offers,' ',$lang['of_pr'],'</div>';
			
			if($show_add_btn > 0 && $num_rows > 3){
	if($num_rows < $max_offers){
	if($paid > 0 && $adactive > 0) echo '<div class="col-xl-12 col-lg-12 col-md-12 p-1 tac"><a href="'.WebSite.'/redirector.php?add_product=',$aid,'" class="btn btn-secondary m-3"><i class="fa fa-plus mr-2" aria-hidden="true"></i> ',$lang['add_offer'],'</a></div>';
	else echo '<div class="col-xl-12 col-lg-12 col-md-12 p-1 tac"><button title="'.$lang['wait_pay_or'].'" disabled class="btn btn-secondary m-3"><i class="fa fa-plus mr-2" aria-hidden="true"></i> ',$lang['add_offer'],'</button></div>';
		}
	}
		echo '</div><div class="card-body p-2 ads-tabs">';
	if($num_rows > 0){
		for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_assoc($result);
		$active = '<i title="'.$lang['unactive'].'" class="fa fa-check-square-o text-danger ml-3" aria-hidden="true"></i>';
		if($row['active'] > 0) $active = '<i title="'.$lang['active'].'" class="fa fa-check-square-o text-success ml-3" aria-hidden="true"></i>';
		
	$image = 'no_product_image.jpg';
	if(!empty($row['image'])) $image = $row['image'];
		
		echo '
		<div id="pid_',$row['id'],'" class="card overflow-hidden mt-2 card-absolute prsq">
			<div class="edit-buttons-fixed">
	<a class="btn btn-info btn-sm text-white" data-toggle="tooltip" data-original-title="',$lang['p_cals'],'"><i class="fa fa-volume-control-phone"></i> (',count_products_calls($row['id']),')</a>
				<a href="../product_redirect.php?pid=',$row['id'],'" class="btn btn-success btn-sm text-white" data-toggle="tooltip" data-original-title="',$lang['edit'],'"><i class="fa fa-pencil"></i></a>
				<a class="btn btn-danger btn-sm text-white del_product" data-id="',$row['id'],'" data-aid="',$aid,'" data-toggle="tooltip" data-original-title="',$lang['del_it'],'"><i class="fa fa-trash-o"></i></a>
			</div>
			<div class="d-md-flex">
				<div class="item-card9-img">
					<div class="item-card9-imgs">
						<!--<a target="_blank" href="../products/',$row['category_url'],'/',$row['product_url'],'"></a>-->
						<img src="../images/products/',$image,'" alt="',$row['title'],'" class="cover-image" />
					</div>
					
				</div>
				<div class="card border-0 mb-0">
					<div class="card-body py-4">
						<div class="item-card9">
							<div class="d-flex">
								<a target="_blank" href="../products/',$row['category_url'],'/">',$row['category_name'],'</a>
							</div>
							<a target="_blank" href="../products/',$row['category_url'],'/',$row['product_url'],'" class="text-dark"><h4 class="font-weight-semibold mt-0">',$row['title'],' ',$active,'</h4></a>
							<div class="item-card2-desc mt-3">
								<div class="item-card2-desc-cost">
									<p class="pr_desc">',strip_tags($row['description']),'</p>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer py-3">
						<div class="row">
							<div class="col">';
							if($row['promo_price'] > 0) $price = $row['promo_price'];
							else $price = $row['price'];
								echo '<b class="ttu">',$lang['prd_price'],'</b> ',$price,' ',current_currency;
							
							echo '</div>
							<div class="col col-auto">
								<a href="#" class="mt-1 mb-1 mr-2 text-black"><i class="fa fa-bell-o" aria-hidden="true"></i> ',count_product_orders($row['id']),' ',$lang['ords'],'</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		';
		}
	
	}else echo '<div class="form-group mt-5 tac"><label class="form-label text-dark">',$lang['no_pr'],'</label></div>';
	
	if($show_add_btn > 0){
	if($num_rows < $max_offers){
	if($paid > 0 && $adactive > 0) echo '<div class="col-xl-12 col-lg-12 col-md-12 p-1 tac"><a href="'.WebSite.'/redirector.php?add_product=',$aid,'" class="btn btn-secondary m-3"><i class="fa fa-plus mr-2" aria-hidden="true"></i> ',$lang['add_offer'],'</a></div>';
	else echo '<div class="col-xl-12 col-lg-12 col-md-12 p-1 tac"><button title="'.$lang['wait_pay_or'].'" disabled class="btn btn-secondary m-3"><i class="fa fa-plus mr-2" aria-hidden="true"></i> ',$lang['add_offer'],'</button></div>';
		}
	}
	echo '</div></div>';
}


function count_product_orders($pid){
$counter = 0;
$query="SELECT count(id) as counter FROM orders WHERE pid='".mysql_real_escape_string($pid)."' GROUP BY `pid`";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$row=mysql_fetch_assoc($result);
	if(!empty($row['counter'])) $counter = $row['counter'];
	return $counter;
}

function count_advert_orders($aid){
$counter = 0;
$query="SELECT count(id) as counter FROM orders WHERE aid='".mysql_real_escape_string($aid)."' GROUP BY `aid`";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$row=mysql_fetch_assoc($result);
	if(!empty($row['counter'])) $counter = $row['counter'];
	return $counter;
}


function truncate($string,$length=200,$append="&hellip;") {

  $string = trim(strip_tags($string));
//echo strlen($string);echo '<br/>';
  if( mb_strlen($string, 'UTF-8') > $length) {
    $string = wordwrap($string, $length);
    $string = explode("\n", $string, 2);
    $string = $string[0] . $append;
  }

  return $string;
}


function echo_products_everywhere($query){
global $lang;

$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);

$html = '';
$htmlreturn = array();
//select p.id, product_url, category_name, category_url, title, price, p.image,p.description,p.addeddate, supplier_name, link_name, pc.url 
if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_assoc($result);
	
	if(empty($row['image'])){
	$image = 'no_product_image.jpg';
	}else $image = $row['image'];
	
	$discount = '';
	if($row['promo_price'] > 0){
	$discount = round((($row['price'] - $row['promo_price'])*100) /$row['price']) ;//($row['price'] - $row['promo_price']);
	}
$your_date = strtotime($row['addeddate']);
$datediff = time() - $your_date;
$new = round($datediff / (60 * 60 * 24));


$desc = mb_strimwidth(strip_tags($row['description']),0,120,'..','utf-8');

$html.='<div id="p'.$row['id'].'" data-id="'.($i+1).'" class="col-xl-4 col-md-6 singleoffer">
	<div class="card overflow-hidden">
		<div class="">
			<div class="item-card9-img">
				<div class="item-card9-imgs item-card7-imgs h300p">';
				if($new <= 7) $html.= '<div class="arrow-ribbon bg-secondary badge-success">'.$lang['new'].'</div>';
					$html.= '<a href="'.WebSite.'/products/'.$row['category_url'].'/'.$row['product_url'].'"></a>
			<img src="'.WebSite.'/images/products/'.$image.'" alt="'.$row['title'].'" class="cover-image" />
				</div>
				<div class="item-card9-icons">';
				if(!empty($discount)) $html.='<a class="item-card2-icons-l text-white dis">-'.$discount.'%</a>';
				$activfav = '';
				$favtitle = $lang['add_tofav'];
				if(isset($_COOKIE['fav_prod'])){
				$cookie = unserialize( base64_decode($_COOKIE['fav_prod']));
				//var_dump($cookie);
				if(array_key_exists($row['id'],$cookie)){
				$activfav = 'active';
				$favtitle = $lang['added_tofav'];
					}
				}
				
				$html.= '<a title="'.$favtitle.'" data-id="'.$row['id'].'" class="item-card9-icons1 wishlist '.$activfav.'"> <i class="fa fa fa-heart-o"></i></a></div>
				<div class="item-cardreview-absolute bg-light z99">
				<a class="text-dark" target="_blank" href="'.WebSite.'/products/'.$row['category_url'].'">
				'.$row['category_name'].'</a></div>
			</div>
			<div class="card border-0 mb-0">
				<div class="card-body pt-2">
					<div class="item-card9">
						<a href="'.WebSite.'/products/'.$row['category_url'].'/'.$row['product_url'].'" class="orn">
						<h4 class="font-weight-semibold mt-1 mb-1 h40p">'.$row['title'].' </h4></a>
						
						<div class="mb-0 pdesc">'.$desc.' '.$desclength.'</div>
					</div>
				</div>
				<div class="card-footer pt-2 pb-2 pl-1 pr-1">
					<div class="item-card9-footer d-sm-flex">
						<div class="ib w48 tac pr">';
						if($row['promo_price'] > 0){
						$html.= '<h6 title="'.$lang['inpromo'].'" class="m-0 vam ib promo_p2">'.$lang['prd_price'].': '.$row['price'].' '.current_currency.'</h6>
							<h5 class="m-0 vam ib">'.$lang['prd_price'].': '.$row['promo_price'].' '.current_currency.'</h5>';
						}else $html.= '<h5 class="m-0 vam ib">'.$lang['prd_price'].': '.$row['price'].' '.current_currency.'</h5>';
						$html.= '</div>
						<div class="ib w48 tar">
								<a class="btn btn-secondary btn-sm br-tr-3 br-br-3" href="'.WebSite.'/products/'.$row['category_url'].'/'.$row['product_url'].'">'.$lang['view'].'</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';
 $htmlreturn[0]=$html;
 $htmlreturn[1]=1;
	}
}else{
 $html.= '<div class="col-lg-12 col-md-12 col-xl-12 mb-5 nomoreproducts"><div class="card mb-0"><div class="card-header pr panel-title"><h3 class="card-title">'.$lang['no_results'].'</h3></div></div></div>';
  $htmlreturn[0]=$html;
  $htmlreturn[1]=0;
 }

return $htmlreturn;
}

function breadcrumb($categorylink){
global $lang;
$html1='';
$html2='';
$html = '<ol class="breadcrumb">';
$query = 'select id, parent, bg_category, url from shop_products_categories where url = "'.mysql_real_escape_string($categorylink).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_assoc($result);
	if($row['parent'] > 0){
	$query1 = 'select id, parent, bg_category, url from shop_products_categories where id = "'.mysql_real_escape_string($row['parent']).'"';
	$result1=mysql_query($query1) or die(send_error($query1,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows1=mysql_num_rows($result1);
		if($num_rows1 > 0){
	$row1=mysql_fetch_assoc($result1);
	//echo $row1['bg_category'];
	$html2.='<li class="breadcrumb-item"><a href="'.WebSite.'/products/'.$row1['url'].'">'.$row1['bg_category'].'</a></li>';
		if($row1['parent'] > 0){
	$query2 = 'select id, parent, bg_category, url from shop_products_categories where id = "'.mysql_real_escape_string($row1['parent']).'"';
	$result2=mysql_query($query2) or die(send_error($query2,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows2=mysql_num_rows($result2);
		if($num_rows2 > 0){
	$row2=mysql_fetch_assoc($result2);
	$html1.='<li class="breadcrumb-item"><a href="'.WebSite.'/products/'.$row2['url'].'">'.$row2['bg_category'].'</a></li>';
	}
	
		}
	}
	
		}
	}
	$html.=$html1.$html2.'<li class="breadcrumb-item">'.$row['bg_category'].'</li>';
	$html.='</ol>';
	return $html;
}


function echo_products($query){
global $lang;

	
	$result2=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result2);
	if($num_rows > 0){
	echo '<div id="myCarousel2" class="owl-carousel owl-carousel-icons">';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result2);
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];
	
	echo '
	<div class="item">
	<div class="card">
		<div class="arrow-ribbon bg-success">',$row[2],' ',current_currency,'</div>
		<div class="item-card7-imgs h301p">
			<a href="',WebSite,'/products/',$row[8],'/',$row[7],'">
			<img src="'.WebSite.'/images/products/',$image,'" alt="',$row[0],'" class="cover-image"></a>
		</div>
		<div class="item-card7-overlaytext">
			<a target="_blank" href="',WebSite,'/business-directory/',$row[8],'/" class="text-white"> ',$row[9],' </a>
		</div>
		<div class="card-body">
			<div class="item-card7-desc">
				<div class="item-card7-text">
					<a href="',WebSite,'/products/',$row[8],'/',$row[7],'" class="text-dark"><h4 class="h40p">',strip_tags($row[0]),'</h4></a>
				</div>
				<p class="mb-0 pdesc">',strip_tags($row[1]),'</p>
			</div>
		</div>
		<div class="card-footer">
			<div class="footerimg d-flex mt-0 mb-0">
			<div class="ib w100 tac">
			<a class="btn btn-secondary btn-sm br-tr-3 br-br-3 pl-5 pr-5" href="',WebSite,'/products/',$row[8],'/',$row[7],'">',$lang['view_pr'],'</a>
			</div>	
			</div>
		</div>
	</div>
</div>';
	
	
		}
		
		
		echo '</div>';
	}
}


function echo_vert_products_banner($categoryname){
global $lang;
//echo $categoryname;
if(isset($_GET['search'])) $categoryname = $_GET['search'];
$html = '';
$query='SELECT p.title,p.description,p.price,p.image, a.link_name, category_url,a.supplier_name,product_url,category_url FROM products p,adverts a   
WHERE p.advert_id=a.id and a.active="1" and p.active="1" and p.deleted="0" ';
if(!empty($categoryname)) $query.=' and (
p.title like "%'.mysql_real_escape_string($categoryname).'%" or 
p.category_name like "%'.mysql_real_escape_string($categoryname).'%" or 
p.description like "%'.mysql_real_escape_string($categoryname).'%"

) order by addeddate DESC limit 12';
//$query.=' ';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows < 1){
$query='SELECT p.title,p.description,p.price,p.image, a.link_name, category_url,a.supplier_name,product_url,category_url FROM products p,adverts a   
WHERE p.advert_id=a.id and a.active="1" and p.active="1" and p.deleted="0" group by p.advert_id order by addeddate DESC limit 12';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
}
if($num_rows > 0){
$html.= '<div class="card">
		<div class="card-header pr panel-title">
			<h3 class="card-title">'.$lang['pofs'].'</h3>
<a class="chev accordion-toggle" aria-expanded="false" aria-controls="collapsethree" data-toggle="collapse" data-parent="#accordion" href="#collapsethree"></a>

		</div>
		<div id="collapsethree" class="card-body collapse show pb-3">
			<ul class="vertical-scroll">';
			for($i=0;$i<$num_rows;$i++){
			$row=mysql_fetch_assoc($result);
			
			if(empty($row['image'])){
	$image = 'no_product_image.jpg';
	}else $image = $row['image'];

	$html.= '<li class="item">
	<div class="item-det card-body">
	<div class="db w100 vat">
		<img src="'.WebSite.'/images/products/'.$image.'" alt="'.$row['title'].'" class="mr-2 vat"/>
	</div>

	<div class="ib w100 mb-0">
		<a target="_blank" href="'.WebSite.'/products/'.$row['category_url'].'/'.$row['product_url'].'" class="text-dark"><h4 class="mb-2 fs-16 font-weight-semibold">'.$row['title'].'</h4></a>
		<h5 class="orange">'.$lang['prd_price'].' '.$row['price'].''.current_currency.'</h5>
		<div class="icons mt-3 pb-0 ">
			<a target="_blank" href="'.WebSite.'/products/'.$row['category_url'].'/'.$row['product_url'].'" class="btn btn-light btn-sm icons"> '.$lang['view_pr'].'</a>
		</div>
		</div>
	</div>
</li>';
			}
		$html.= '</ul></div>
	</div>';

	}
	return $html;
}


function latest_products(){
global $lang;
$query='SELECT p.title,p.description,p.price,p.image, a.link_name, category_url,a.supplier_name,product_url,category_url FROM products p,adverts a   
WHERE p.advert_id=a.id and a.active="1" and p.active="1" group by p.advert_id order by addeddate DESC limit 12';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
echo '<h4 class="home">',$lang['latest_products'],'</h4><div class="all"><a href="',WebSite,'/products/">',$lang['view_all_pr'],'</a></div>';
	echo '<ul class="bxslider">';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];
	echo '<li><div class="bannerhome">
	<a href="',WebSite,'/products/',$row[8],'/',$row[7],'">
	<span class="image"><img src="',WebSite,'/images/products/',$image,'" alt="',$row[0],'" /></span>
	<h3>',$row[0],'</h3></a>
	<span class="inner">
	<p class="prd_price">',$lang['prd_price'],': ',$row[2],'</p>
	<p>',$lang['added_by'],' <a href="',WebSite,'/business-directory/',$row[5],'/',$row[4],'">',$row[6],'</a></p>
	<span class="bbutton"><span class="b0">',$lang['order'],'</span></span>
	</span>
	</div></li>';
	if($i%3==0){
	?>
<!--li><div class="gadvert">
<ins class="adsbygoogle" style="display:inline-block;width:150px;height:420px" data-ad-client="ca-pub-8616690735725377" data-ad-slot="6521826395"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div></li-->
<?php
			}
		}
	echo '</ul>';
	}
}

function menu_products_categories(){
global $lang;

$query='select distinct (category_name) as category_name, category_url from products group by category_url order by category_name ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
$html='';
if($num_rows){
	$html.= '<ul id="catmenu">';
	while($row = mysql_fetch_assoc($result)){
	
	$html.='<li class="main without"><a href="'.WebSite.'/products/'.$row['category_url'].'">'.$row['category_name'].'</a></li>';
	
	}
	$html.= '</ul>';

	}
	echo $html;
}


function check_for_new_categories(){
global $lang;
$html = '';
		$q='select id from adverts_to_product_categories where advert_id="'.mysql_real_escape_string($_SESSION['advert_id']).'" and category_id = "9999"';
		$r=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
		$num_rows=mysql_num_rows($r);
		if($num_rows > 0){
		$html = $lang['new_cat_added'];
		}
	return $html;
}


function count_products_visits($pid){
$count = 0;
$query='select count(id) from statistics_products_visits where product_id = "'.mysql_real_escape_string($pid).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_row($result);
$count = $row[0];
	}
	return $count;
}

function count_products_calls($pid){
$count = 0;
$query='select count(id) from statistics_adverts_calls where product_id = "'.mysql_real_escape_string($pid).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_row($result);
$count = $row[0];
	}
	return $count;
}

function count_adverts_calls($aid){
$count = 0;
$query='select count(id) from statistics_adverts_calls where advert_id = "'.mysql_real_escape_string($aid).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_row($result);
$count = $row[0];
	}
	return $count;
}

function count_adverts_site_visits($aid){
$count = 0;
$query='select count(id) from statistics_adverts_visits where advert_id = "'.mysql_real_escape_string($aid).'" and sitevisited = "1"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_row($result);
$count = $row[0];
	}
	return $count;
}

function count_adverts_visits($aid){
$count = 0;
$query='select count(id) from statistics_adverts_visits where advert_id = "'.mysql_real_escape_string($aid).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_row($result);
$count = $row[0];
	}
	return $count;
}

function check_for_published_offers(){
$count = 0;
$offers = 0;
$query='select count(id) from products where advert_id = "'.mysql_real_escape_string($_SESSION['advert_id']).'" and deleted = "0"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_row($result);
$offers = $row[0];
	}
$aquery='select max_offers from adverts where id = "'.mysql_real_escape_string($_SESSION['advert_id']).'"';
$aresult=mysql_query($aquery) or die(send_error($aquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$anum_rows=mysql_num_rows($aresult);
if($anum_rows > 0){
	$arow=mysql_fetch_row($aresult);
	//echo $arow[0];
	if($arow[0] <= $offers) $count = 1;
	$_SESSION['max_offers'][$_SESSION['advert_id']]=$row[0];
	}
return $count;
}


function check_for_not_approved(){
global $lang;
$active = 0;
$max_offers = 0;
$query='select id, max_offers from adverts where customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'" and active = "0"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
	$row=mysql_fetch_row($result);
	$active = $row[0];
	$_SESSION['advert_id']=$row[0];
	$_SESSION['max_offers'][$row[0]]=$row[1];
	}
	return $active;
}

function get_advert_images($aid){
$advert_images = array();
$query='select id, image, order_id from adverts_gallery where advert_id = "'.mysql_real_escape_string($aid).'" order by order_id ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
if($num_rows){
while($row = mysql_fetch_assoc($result)){
		$advert_images[$row['id']]['image'] = $row['image'];
		$advert_images[$row['id']]['ordering'] = $row['order_id'];
		}
	}
return $advert_images;
}

function randomcode() {
    $alphabet = "abcdefghijklmnopqrstuwxyz-ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i<=5;$i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function get_product_images($pid){
$product_images = array();
$query='select id, image, order_id from products_gallery where product_id = "'.mysql_real_escape_string($pid).'" order by order_id ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
if($num_rows){
while($row = mysql_fetch_assoc($result)){
		$product_images[$row['id']]['image'] = $row['image'];
		$product_images[$row['id']]['ordering'] = $row['order_id'];
		}
	}
return $product_images;
}

function get_advert_categories_link($aid){
$categories = array();
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc where 
pc.id=atpc.category_id and atpc.advert_id = "'.mysql_real_escape_string($aid).'" and pc.bg_category != "Временна категория" group by pc.id order by pc.bg_category ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
if($num_rows){
while($row = mysql_fetch_assoc($result)){
		$categories[] = $row['url'];
		}
	}
return $categories;
}

function get_advert_categories($aid){
$categories = array();
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc where 
pc.id=atpc.category_id and atpc.advert_id = "'.mysql_real_escape_string($aid).'" and pc.bg_category != "Временна категория" group by pc.id order by pc.bg_category ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
if($num_rows){
while($row = mysql_fetch_assoc($result)){
		$categories[] = $row['bg_category'];
		}
	}
return $categories;
}

function add_product_category2(){
$query = 'select category_name from products group by category_name';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
while($row = mysql_fetch_assoc($result)){
$selected = '';
//echo $row['category_name'];
	if(isset($_POST['category_name']) && (mb_strtolower($row['category_name'], 'UTF-8')==mb_strtolower($_POST['category_name'], 'UTF-8'))) $selected = 'selected="selected"';
//echo $row['category_name'];
	echo '<option '.$selected.' value="'.$row['category_name'].'">'.$row['category_name'].'</option>';
		}
	}
}


function add_listing_categories($plan){
global $lang;
$categories = array();
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc,adverts a where 
pc.id=atpc.category_id and atpc.advert_id=a.id and a.active="1" and pc.bg_category != "Временна категория" group by pc.id order by pc.bg_category ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);

$multiple = '';
if($plan == 2) $multiple = 'multiple="multiple"';
if($num_rows){
$newcat = check_for_new_categories();
echo '<select ',$multiple,' name="category[]" id="add_listing_categories" class="form-control select2-show-search border-bottom-0" data-placeholder="',$lang['ccat'],'">
<option value="0">'.$lang['ccat'].'</option>
<optgroup label="Категории">';
	while($row = mysql_fetch_assoc($result)){
	$selected = '';
	if( isset($_POST['category']) && in_array($row['bg_category'],$_POST['category'])) $selected = 'selected="selected"';
	echo '<option '.$selected.' value="'.$row['bg_category'].'">'.$row['bg_category'].'</option>';
		}
		echo '</optgroup></select>',$newcat;
	}
}

function add_listing_category($id,$level=0,$parent){
global $lang;
	$query = "SELECT bg_category, id, visible, parent FROM products_categories where parent='".mysql_real_escape_string($id)."' order by bg_category ASC";
	$res=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
 	
   if(mysql_num_rows($res) > 0){
    while (list ($bg_category, $id,$visible,$parent) = mysql_fetch_row($res)){
	$selected = '';
	$que = 'select bg_category, id, visible,parent from products_categories where parent="'.$id.'"';
	$resu=mysql_query($que) or die(send_error($que,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$ro=mysql_fetch_row($resu);
		if( isset($_POST['category']) && in_array($row['bg_category'],$_POST['category'])) $selected = 'selected="selected"';

        if ($level==0){
			if($ro[2]>0) {
			echo '<option class="m',$level,' aaa" disabled '.$selected.' value="'.mysql_real_escape_string($bg_category).'">'.$bg_category.'</option>';
			}else{
			echo '<option class="m',$level,' bbb" '.$selected.' value="'.$bg_category.'">'.$bg_category.'</option>';		
			}
		}
        else{
			if($ro[0]>0){
			echo '<option class="m',$level,' ccc" disabled '.$selected.' value="'.$bg_category.'">'.$bg_category.'</option>';	
			}
		else{
			echo '<option class="m',$level,' ddd" '.$selected.' value="'.$bg_category.'">'.$bg_category.'</option>';	
			}
		}
        add_listing_category($id,$level+1,$parent);
		}
	}else{
	if ($level==0) echo '<option '.$selected.' value="">Все още няма добавени категории</option>';	
	}
}



function add_product_category_new($id,$level=0,$parent){
global $lang;
	$query = "SELECT bg_category, id, visible,top_category FROM shop_products_categories where parent='".mysql_real_escape_string($id)."' order by id=38, bg_category ASC";
	$res=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
 	
   if(mysql_num_rows($res) > 0){
    while (list ($bg_category, $id,$visible,$top_category) = mysql_fetch_row($res)){
	$selected = '';
	$que = 'select id from shop_products_categories where parent="'.$id.'"';
	$resu=mysql_query($que) or die(send_error($que,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$ro=mysql_fetch_row($resu);
		if(isset($_POST['category_id']) && ($_POST['category_id'] == $id)) $selected = 'selected="selected"';

        if ($level==0){
			if($ro[0]>0) {
			echo '<option class="m',$level,' aaa" disabled '.$selected.' value="'.mysql_real_escape_string($id).'">'.$bg_category.'</option>';
			}else{
			echo '<option class="m',$level,' bbb" '.$selected.' value="'.$id.'">'.$bg_category.'</option>';		
			}
		}
        else{
			if($ro[0]>0){
			echo '<option class="m',$level,' ccc" disabled '.$selected.' value="'.$id.'">'.$bg_category.'</option>';	
			}
		else{
			echo '<option class="m',$level,' ddd" '.$selected.' value="'.$id.'">'.$bg_category.'</option>';	
			}
		}
        add_product_category_new($id,$level+1,$bg_category);
		}
	}else{
	if ($level==0) echo '<option '.$selected.' value="">Все още няма добавени категории</option>';	
	}
}

function filter_product_category($id,$level=0,$parent){
global $lang;

$categories = 'select category_id from products where active="1" and checked_by_admin="1"';
$categoriesresult=mysql_query($categories) or die(send_error($categories,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$categories_num_rows=mysql_num_rows($categoriesresult);
if($categories_num_rows){
	while($row = mysql_fetch_assoc($categoriesresult)){
		$categories_list[] = $row['category_id'];
		}
	$query = 'SELECT bg_category, id, visible,top_category, url FROM shop_products_categories where parent="'.mysql_real_escape_string($id).'" and id!=38 order by bg_category ASC';
	$res=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
 	
   if(mysql_num_rows($res) > 0){
    while (list ($bg_category, $id,$visible,$top_category,$url) = mysql_fetch_row($res)){
	$selected = '';
	$que = 'select id from shop_products_categories where parent="'.mysql_real_escape_string($id).'"';
	$resu=mysql_query($que) or die(send_error($que,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$ro=mysql_fetch_row($resu);
		if(isset($_POST['category']) && ($_POST['category'] == $id)) $selected = 'selected="selected"';

        if ($level==0){
			if($ro[0]>0) {
			echo '<option data-url="',$url,'" class="m',$level,' aaa" disabled '.$selected.' value="'.$id.'">'.$bg_category.'</option>';
			}else{
			echo '<option data-url="',$url,'" class="m',$level,' bbb" '.$selected.' value="'.$id.'">'.$bg_category.'</option>';		
			}
		}
        else{
			if($ro[0]>0){
			echo '<option data-url="',$url,'" class="m',$level,' ccc" disabled '.$selected.' value="'.$id.'">'.$bg_category.'</option>';	
			}
		else{
			if(in_array($id,$categories_list)) echo '<option data-url="',$url,'" class="m',$level,' ddd" '.$selected.' value="'.$id.'">'.$bg_category.'</option>';	
			}
		}
        filter_product_category($id,$level+1,$bg_category);
			}
		}else{
		if ($level==0) echo '<option '.$selected.' value="">Все още няма добавени категории</option>';	
		}
	}
}


function get_subcategories($category_id){
$categories = '';

//"'.mysql_real_escape_string($category_id).'"
$query = 'SELECT id, bg_category, visible,top_category FROM shop_products_categories where parent="'.mysql_real_escape_string($category_id).'" order by bg_category ASC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$categories = array();
	for($i=0;$i<$num_rows;$i++){
			$row=mysql_fetch_row($result);
			$categories[] = $row[0];
			
			$sub = 'SELECT id, bg_category, visible,top_category FROM shop_products_categories where parent="'.$row[0].'" order by bg_category ASC';
			$subresult=mysql_query($sub) or die(send_error($sub,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			$sub_num_rows=mysql_num_rows($subresult);
			if($sub_num_rows > 0){
			for($j=0;$j<$sub_num_rows;$j++){
			$subrow=mysql_fetch_row($subresult);
			$categories[] = $subrow[0];
				}
			}
		}
	}
	return $categories;
}


function get_subcategories_by_url($categorylink){
$categories = '';

//"'.mysql_real_escape_string($category_id).'"
$query = 'SELECT id FROM shop_products_categories where url="'.mysql_real_escape_string($categorylink).'" order by bg_category ASC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row = mysql_fetch_assoc($result);
$categories = get_subcategories($row['id']);
	}
	//var_dump($categories);
	return $categories;
}

function add_product_category(){
global $lang;
$categories = array();
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc,adverts a where 
pc.id=atpc.category_id and atpc.advert_id=a.id and a.active="1" and pc.bg_category != "Временна категория" group by pc.id order by pc.bg_category ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
if($num_rows){
echo '
<select name="category_name" id="category_name" class="form-control select2-show-search border-bottom-0 mand" data-placeholder="',$lang['ccat'],'">
<option value="0">'.$lang['ccat'].'</option>
<optgroup label="',$lang['ccat'],'">';
	while($row = mysql_fetch_assoc($result)){
	$selected = '';
	if(isset($_POST['category_name']) && (mb_strtolower($row['bg_category'], 'UTF-8') == mb_strtolower($_POST['category_name'], 'UTF-8'))) $selected = 'selected="selected"';
	echo '<option '.$selected.' value="'.$row['bg_category'].'">'.$row['bg_category'].'</option>';
		}
	add_product_category2();
		echo '</optgroup></select>';

	}

}

function mb_ucfirst($string, $encoding){
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}



function echo_categories_in_search_for_sale($categoryname=''){
global $lang;
$categories = array();
$citylink = '';
$url=urldecode($_SERVER['REQUEST_URI']);
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";

$parts = explode('/', rtrim($url, '/'));

if(!empty($parts[2]) && $parts[1]=='regions'){
if(preg_match($pattern, urldecode($parts[2]))){
$citylink = urldecode($parts[2]);
	}
if(!empty($_POST['search_city'])) $citylink = $_POST['search_city'];	
}
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc,adverts a where 
pc.id=atpc.category_id and atpc.advert_id=a.id and a.sellbusiness ="1" and a.active="1" and pc.bg_category != "Временна категория" ';
if(!empty($citylink)) $query.= ' and a.town = "'.mysql_real_escape_string($citylink).'"';
$query.=' group by pc.id order by pc.bg_category ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
echo '<select name="category" id="category" class="form-control select2-show-search border-bottom-0" data-placeholder="',$lang['ccat'],'">
<optgroup label="',$lang['cats'],'"><option value="0">',$lang['view_all'],'</option>';
if($num_rows){

	while($row = mysql_fetch_assoc($result)){
	$selected = '';
	if($row['url'] == $_POST['category'] || (mb_strtolower($row['bg_category'], 'UTF-8') == mb_strtolower($categoryname, 'UTF-8'))) $selected = 'selected="selected"';
	echo '<option '.$selected.' value="'.$row['url'].'">'.$row['bg_category'].'</option>';
		}
	}
echo '</optgroup></select>';
}

function echo_categories_in_search($categoryname=''){
global $lang;
$categories = array();
$citylink = '';
$url=urldecode($_SERVER['REQUEST_URI']);
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";

$parts = explode('/', rtrim($url, '/'));

if(!empty($parts[2]) && $parts[1]=='regions'){
if(preg_match($pattern, urldecode($parts[2]))){
$citylink = urldecode($parts[2]);
	}
if(!empty($_POST['search_city'])) $citylink = $_POST['search_city'];	
}
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc,adverts a where 
pc.id=atpc.category_id and atpc.advert_id=a.id and a.active="1" and pc.bg_category != "Временна категория" ';
if(!empty($citylink)) $query.= ' and a.town = "'.mysql_real_escape_string($citylink).'"';
$query.=' group by pc.id order by pc.bg_category ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);

if($num_rows){
echo '<select name="category" id="category" class="form-control select2-show-search border-bottom-0" data-placeholder="',$lang['ccat'],'">
<optgroup label="',$lang['cats'],'"><option value="0">',$lang['view_all'],'</option>';
	while($row = mysql_fetch_assoc($result)){
	$selected = '';
	if($row['url'] == $_POST['category'] || (mb_strtolower($row['bg_category'], 'UTF-8') == mb_strtolower($categoryname, 'UTF-8'))) $selected = 'selected="selected"';
	echo '<option '.$selected.' value="'.$row['url'].'">'.str_replace('###','',$row['bg_category']).'</option>';
		}
		echo '</optgroup></select>';
	}
}

function echo_categories(){
global $lang;
$categories = array();
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc,adverts a where 
pc.id=atpc.category_id and atpc.advert_id=a.id and a.active="1" and pc.bg_category != "Временна категория" group by pc.id order by pc.bg_category ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);

if($num_rows){
echo '<select name="category" id="category" class="form-control select2-show-search  border-bottom-0" data-placeholder="',$lang['ccat'],'">
<optgroup label="',$lang['cats'],'"><option>',$lang['ccat'],'</option><option value="0">',$lang['view_all'],'</option>';
	while($row = mysql_fetch_assoc($result)){
	$selected = '';
	if($row['bg_category'] == $_POST['category'] || $row['bg_category'] == $categoryname) $selected = 'selected="selected"';
	echo '<option '.$selected.' value="'.str_replace('###','',$row['bg_category']).'">'.str_replace('###','',$row['bg_category']).'</option>';
		}
		echo '</optgroup></select>';
	}
}



function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
}

function echo_product_categories(){
global $lang;
$categories = array();
$query='select id, category_name, category_url from products where active="1" and sold="0" and deleted="0" group by category_name order by category_name ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);

if($num_rows){
echo '<select name="products_category" id="products_category" class="form-control select2-show-search  border-bottom-0" data-placeholder="',$lang['ccat'],'">
<optgroup label="',$lang['cats'],'"><option>',$lang['ccat'],'</option>';
	while($row = mysql_fetch_assoc($result)){
	$selected = '';
	if($row['category_name'] == $_POST['products_category']) $selected = 'selected="selected"';
	echo '<option '.$selected.' value="'.$row['category_url'].'">'.$row['category_name'].'</option>';
		}
		echo '</optgroup></select>';
	}
}

function return_product_categories(){
global $lang;
$categories = array();
$query='select count(id) as counter, id, category_name, category_url from products where active="1" and sold="0" and deleted="0" group by category_name order by category_name ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);

if($num_rows){
	while($row = mysql_fetch_assoc($result)){
	$categories[$row['id']]['name'] = $row['category_name'];
	$categories[$row['id']]['url'] = $row['category_url'];
	$categories[$row['id']]['counter'] = $row['counter'];
	//$categories['count'] = ;
		}
	}
	return $categories;
}

function echo_cities(){
	global $lang;
	
	$cities = array();
	$query='select id, name, en_name from cities order by name asc';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	if($num_rows){
		while($row = mysql_fetch_assoc($result)){
		//$cities['label'][] = $row['name'];
		//$cities['id'][] = $row['id'];
		$cities[] = $row['name'];
		}
	}
	return $cities;
}

function echo_makes(){
$makes = array();
	$query = 'select make from products where make !="" group by make order by make asc';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	if($num_rows){
		while($row = mysql_fetch_assoc($result)){
		$makes[] = $row['make'];
		}
	}
	return $makes;
}

function echo_models(){
$models = array();
	$query = 'select model from products where model !="" group by model order by model asc';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	if($num_rows){
		while($row = mysql_fetch_assoc($result)){
		$models[] = $row['model'];
		}
	}
	return $models;
}

function echo_modifications(){
$modifications = array();
	$query = 'select modification from products where modification !="" group by modification order by modification asc';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	if($num_rows){
		while($row = mysql_fetch_assoc($result)){
		$modifications[] = $row['modification'];
		}
	}
	return $modifications;
}

function get_marks($categories_array,$category_id){
	$makes['make'] = array();
	$makes['model'] = array();
	$makes['modification'] = array();
	//var_dump($categories_array);
	$query = 'select make, model, modification from products where active="1" and sold="0" and deleted="0" and make !="" and model !="" and modification !="" and checked_by_admin = "1" ';
	
	if(is_array($categories_array) && count($categories_array) > 0) $query.= ' and category_id IN (' . implode(",", $categories_array) . ') ';
	else{
	if($category_id > 0) $query.= ' and category_id = "'.mysql_real_escape_string($category_id).'"';
	}
/*if(isset($_POST['make']) && !empty($_POST['make'])){
$_POST['make'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['make'])));
$query.=' and make = "'.mysql_real_escape_string($_POST['make']).'" ';
}

if(isset($_POST['model']) && !empty($_POST['model'])){
$_POST['model'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['model'])));
$query.=' and model = "'.mysql_real_escape_string($_POST['model']).'" ';
}*/
	$query.=' GROUP BY make,model,modification order by make asc,model asc,modification asc ';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	if($num_rows){
		while($row = mysql_fetch_assoc($result)){
		//echo is_array($makes);
		if(!in_array($row['make'],$makes['make'])) $makes['make'][] = $row['make'];
		if(!in_array($row['model'],$makes['model'])) $makes['model'][] = $row['model'];
		if(!in_array($row['modification'],$makes['modification'])) $makes['modification'][] = $row['modification'];
		}
	}
	return $makes;
}


function new_menu(){
global $lang;

$categories = array();
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc,adverts a where 
pc.id=atpc.category_id and atpc.advert_id=a.id and a.active="1" and pc.bg_category != "Временна категория" group by a.id order by pc.id ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
$html='';

if($num_rows){
$html.= '<ul id="catmenu"><li class="main without f"><a class="btn" href="'.WebSite.'/news/select-plan/">'.$lang['add'].' <i class="fa fa-plus-square" aria-hidden="true"></i></a></li>';
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
	$html.='<li class="main with"><span>+ '.$rws[1].'</span>'.$subhtml.'</li>';
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
	 }

}


function send_error($query,$url1,$url2,$error,$user_ip){
$message="error in ".$_SERVER["REQUEST_URI"]."\r\n".$_SERVER["PHP_SELF"]."\r\n<br />".$query."\r\n<br />".$error."\r\n<br />User:".$user_ip;
mail(official_mail_sender,"error in ".WebSite."",$message);
die(mysql_error());
}

function sanitaze_text($str){
$str = preg_replace(array('/\r\n\r\n/','/\s\s+/','/\r\n/'), '<br /> ', $str);
//$str=strip_tags(htmlspecialchars_decode($str),"<p>");
$str = str_replace("'","", html_entity_decode($str, ENT_QUOTES));
//$str = strip_tags(trim($str),"<p>");
//$str = preg_replace(array('/\r\n\r\n/','/\s\s+/','/\r\n/'), '<br /> ', $str);
$str=str_replace(array('"','`','’','„','”'),'\'',$str);
//mahame paragraph white space ako se kopira text ot html
$str=str_replace('  ',' ',$str);
$str=str_replace(array('\\','[',']','{','}','^','%'),'',$str);
	return $str;
}

function sanitaze_text2($str){
$str=strip_tags($str);
$str = trim($str);
$str = preg_replace(array('/\r\n\r\n/','/\s\s+/','/\r\n/'), '<br /> ', $str);
$str=str_replace(array('"','`','’','„','”'),'\'',$str);
//mahame paragraph white space ako se kopira text ot html
$str=str_replace('  ',' ',$str);
$str=str_replace(array('\\','[',']','{','}','^','%'),'',$str);
	return $str;
}

function sanitaze_phone($str){
$str = trim($str);
$str = preg_replace(array('/\r\n\r\n/','/\s\s+/','/\r\n/'), '<br /> ', $str);
$str=strip_tags($str,'<b><br><br /><p>');
$str=str_replace(array('"','`','’','„','”'),'\'',$str);
//mahame paragraph white space ako se kopira text ot html
$str=str_replace('  ',' ',$str);
$str=str_replace(array('\\','^','%'),'',$str);
	return $str;
}

function sanitaze_category($str){
$str = trim($str);
$str = preg_replace(array('/\r\n\r\n/','/\s\s+/','/\r\n/'), ' ', $str);
$str=strip_tags($str,'');
$str=str_replace(array('"','`','’','„','”'),'\'',$str);
//mahame paragraph white space ako se kopira text ot html
$str=str_replace('  ',' ',$str);
$str=str_replace(array('\\','[',']','{','}','^',';','%'),'',$str);
	return $str;
}

function select_countries($country){
	global $lang;
	$country=preg_replace("/[^a-zA-Z0-9 -\/']+/", "", $country);
	$html='<select id="f0" class="transform" name="country">';
	
	$selected='';
	if(empty($country)) $selected='selected="selected"';
	$html.='<option '.$selected.' value="0">'.$lang['please_select'].'</option>';
	
	$query='select id, country_code,country_name from countries';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$selected='';
		//echo $row[2];
		if($country==$row[2] || $country==$row[0]) $selected='selected="selected"';
		$html.='<option '.$selected.' value="'.$row[0].'">'.$row[2].'</option>';
	}
	$html.='</select>';
	return $html;
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}




function select_cities($city){
	global $lang;
	$html='<select id="f0" class="transform" name="city">';
	
	$selected='';
	if(empty($city)) $selected='selected="selected"';
	$html.='<option '.$selected.' value="0">'.$lang['please_select'].'</option>';
	
	$query='select id, name, en_name from cities order by ordering ASC,id ASC';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$selected='';
		//echo $city.'-aaa';
		if(!empty($city) && ($city==$row[1] || $city==$row[2])) $selected='selected="selected"';

		$html.='<option '.$selected.' value="'.$row[1].'">'.$row[1].'</option>';
	}
	$html.='</select>';
	return $html;
}


function echo_product_options($pid){
global $lang;
//var_dump($_POST);
$query='select product_options from products where id="'.mysql_real_escape_string($pid).'"';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
$row=mysql_fetch_row($result);
$html='';
if(!empty($row[0])){
		$optionsStr = rtrim($row[0], ";");
		$options = explode(":", $optionsStr);
		$label=$options[0];
		$options = explode(";", $options[1]);
			//var_dump($options);

	$html.='<select data="'.$label.'" class="transform product_options" onchange="refresh_select();" name="product_options">';
	$html.= '<option value="0">'.$label.'</option>';
	foreach ($options as $value) {
	$selected='';
	if((isset($_POST['product_options']) && $_POST['product_options']==trim($value))) $selected='selected="selected"';
		$html.= '<option '.$selected.' value="'.trim($value).'">'.trim($value).'</option>';
			}
	
	$html.='</select>';
	}
	return $html;
}

function select_business_type($type){
	global $lang;
	$html='<select id="business_type" name="business_type" class="form-control select2" data-placeholder="'.$lang['select_type'][0].'">';

	$selected='selected="selected"';
	if($type == '0') $html.='<option '.$selected.' value="0">'.$lang['select_type']['0'].'</option>';else $html.='<option value="0">'.$lang['select_type']['0'].'</option>';
	if($type == '1') $html.='<option '.$selected.' value="1">'.$lang['select_type']['1'].'</option>';else $html.='<option value="1">'.$lang['select_type']['1'].'</option>';
	if($type == '2') $html.='<option '.$selected.' value="2">'.$lang['select_type']['2'].'</option>';else $html.='<option value="2">'.$lang['select_type']['2'].'</option>';
	if($type == '3') $html.='<option '.$selected.' value="3">'.$lang['select_type']['3'].'</option>';else $html.='<option value="3">'.$lang['select_type']['3'].'</option>';
	if($type == '4') $html.='<option '.$selected.' value="4">'.$lang['select_type']['4'].'</option>';else $html.='<option value="4">'.$lang['select_type']['4'].'</option>';
	
	$html.='</select>';
	return $html;
}
function detect_country_fast($ip){
	$c='-';
	if(!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost') return $c;
	$useragent='Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.18) Gecko/20110628 Ubuntu/10.10 (maverick) Firefox/3.6.18';
	$url='http://api.ipinfodb.com/v3/ip-country/?key=2fc53ebd63c943fc0a469e288d376fbc5ee8e3748415e602abe7c8d8bc04985d&ip='.urlencode($ip);
	$ch=curl_init();
	$curl_opt=array(
		CURLOPT_FOLLOWLOCATION => false,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERAGENT => $useragent,
		CURLOPT_URL => $url,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_REFERER => 'http://'.$_SERVER['HTTP_HOST'],
	);
	curl_setopt_array($ch, $curl_opt);
	$content=curl_exec($ch);
	curl_close($ch);
	if(strlen($content)){
		$content=explode(';',$content);
		$c=preg_replace("/[^A-Za-z0-9 ]/", '', $content[4]);
	}
	
	return $c;
}

function detect_city_fast($ip){
	$c='-';
	if(!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost') return $c;
	$useragent='Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.18) Gecko/20110628 Ubuntu/10.10 (maverick) Firefox/3.6.18';
	$url='http://api.ipinfodb.com/v3/ip-city/?key=2fc53ebd63c943fc0a469e288d376fbc5ee8e3748415e602abe7c8d8bc04985d&ip='.urlencode($ip);
	$ch=curl_init();
	$curl_opt=array(
		CURLOPT_FOLLOWLOCATION => false,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERAGENT => $useragent,
		CURLOPT_URL => $url,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_REFERER => 'http://'.$_SERVER['HTTP_HOST'],
	);
	curl_setopt_array($ch, $curl_opt);
	$content=curl_exec($ch);
	curl_close($ch);
	if(strlen($content)){
		$content=explode(';',$content);
		$content[5]= preg_replace("/[^A-Za-z0-9 ]/", '', $content[5]);
		$c=$content[5];
		if(!empty($content[6])) $c=preg_replace("/[^A-Za-z0-9 ]/", '', $content[6]);
	}
	
	return $c;
}
function is_mail($value){
	if($value!=""){
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
		return 0;
		}else return 1;
	}else return 0;
}


function is_phone($value){
	if($value!=""){
	if(is_numeric($value) && strlen($value) >= 8) return 1;
		else return 0;
	}
	else return 1;
}

function send_mail_no_header($p){
/*$headers = "From: =?utf-8?b?".base64_encode($p['from_name'])."?= <".$p['from_email'].">\r\n";
if(!empty($p['replay_to'])) $headers .= "Reply-To: ". strip_tags($p['replay_to']) . "\r\n";
else $headers .= "Reply-To: ". strip_tags($p['from_email']) . "\r\n";
if(!empty($p['cc'])) $headers .= "CC: ". strip_tags($p['cc']) . "\r\n";
if(!empty($p['bcc'])) $headers .= "Bcc: ". strip_tags($p['bcc']) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$random_hash = md5(date('r'));*/
$headers ='';
//if(isset($p['from_name'])) $headers .= "From: =?utf-8?b?".base64_encode($p['from_name'])."?= <".$p['from_email'].">\r\n";
if(isset($p['from_name'])) $headers .= "From: ".mail_name." <".$p['from_email'].">\r\n";
if(isset($p['from_email'])) $headers .= "Reply-To: ". strip_tags($p['from_email']) . "\r\n";
$headers .= "Return-Path: ". official_mail_sender . "\r\n";
if(!empty($p['cc'])) $headers .= "CC: ". strip_tags($p['cc']) . "\r\n";
if(!empty($p['bcc'])) $headers .= "BCC: ". strip_tags($p['bcc']) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$random_hash = md5(date('r'));
//$p['body']=str_replace(array('<br>','<br />'),"\r\n",$p['body']);
		//$body="This is a multi-part message in MIME format.\r\n\r\n--PHP-alt-$random_hash\r\nContent-Type: text/html;\r\n charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".strip_tags(str_replace(array('<br>','<br />'),"\n",$p['body']))."\r\n--PHP-alt-$random_hash\r\nContent-Type: text/html;\r\n charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n";
		$body='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>'.$p['subject'].'</title>
</head>
<body bgcolor="White">
<center>
<div style="text-align: left;font-family:verdana,sans-serif;font-size:12px;width:100%;padding:0;margin:0;color:#555;background:#fff;">
	<div style="line-height: 20px;padding: 40px 5px; text-align: left; margin: 15px 0px; border-top: 3px double #ddd; border-bottom: 3px double #ddd;">
	<br>'.$p['body'].'<br></div>
	<div align="center" style="background:#555;color:#ffffff;margin:0;padding:5px;text-align:center;">©Copyright '.date('Y').'"&nbsp;&nbsp;<a href="'.WebSite.'" target="_blank" style="color:#fff;text-decoration:none;">'.official_site_name.'</a> - All Rights Reserved</div>
</div>
</center>
</body>
</html>';
		//$body.="\r\n\r\n--PHP-alt-$random_hash--";
		//$body=strip_tags(str_replace(array('<br>','<br />'),"\r\n",$body));
	
	
$mail_sent = mail($p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers, "-f ".official_mail_sender."");
//mail($to, $subject, $message, $headers);
	//echo $body;
	//$mail_sent = @mail( $p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers );
	return $mail_sent ? true : false;
}

function send_mail($p){
/*$headers = "From: =?utf-8?b?".base64_encode($p['from_name'])."?= <".$p['from_email'].">\r\n";
if(!empty($p['replay_to'])) $headers .= "Reply-To: ". strip_tags($p['replay_to']) . "\r\n";
else $headers .= "Reply-To: ". strip_tags($p['from_email']) . "\r\n";
if(!empty($p['cc'])) $headers .= "CC: ". strip_tags($p['cc']) . "\r\n";
if(!empty($p['bcc'])) $headers .= "Bcc: ". strip_tags($p['bcc']) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$random_hash = md5(date('r'));*/
$headers ='';
//if(isset($p['from_name'])) $headers .= "From: =?utf-8?b?".base64_encode($p['from_name'])."?= <".$p['from_email'].">\r\n";
if(isset($p['from_name'])) $headers .= "From: ".mail_name." <".$p['from_email'].">\r\n";
if(isset($p['from_email'])) $headers .= "Reply-To: ". strip_tags($p['from_email']) . "\r\n";
$headers .= "Return-Path: ". official_mail_sender . "\r\n";
if(!empty($p['cc'])) $headers .= "CC: ". strip_tags($p['cc']) . "\r\n";
if(!empty($p['bcc'])) $headers .= "BCC: ". strip_tags($p['bcc']) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$random_hash = md5(date('r'));
//$p['body']=str_replace(array('<br>','<br />'),"\r\n",$p['body']);
		//$body="This is a multi-part message in MIME format.\r\n\r\n--PHP-alt-$random_hash\r\nContent-Type: text/html;\r\n charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".strip_tags(str_replace(array('<br>','<br />'),"\n",$p['body']))."\r\n--PHP-alt-$random_hash\r\nContent-Type: text/html;\r\n charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n";
		$body='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>'.$p['subject'].'</title>
</head>
<body bgcolor="White">
<center>
<div style="text-align: left;font-family:verdana,sans-serif;font-size:12px;width:100%;padding:0;margin:0;color:#555;background:#fff;">
	<a style="display:block;text-decoration:none;color:#E56C6C;" href="'.WebSite.'" target="_blank">
		<img style="padding:0;margin:0;border:none;" border="0" src="'.WebSite.'/images/mailheader.jpg" alt="'.official_site_name.'" />
	</a>
	<div style="line-height: 20px;padding: 40px 5px; text-align: left; margin: 15px 0px; border-top: 3px double #ddd; border-bottom: 3px double #ddd;">
	<br>'.$p['body'].'<br></div>
<div align="center">
<table border="0" width="100%" height="300">
	<tr><td align="center"><p align="center"><a href="https://www.shoppingbulgaria.com" target="_blank">
	<img width="80%" border="0" src="'.WebSite.'/cron/banner.jpg" alt="Онлайн MarketPlace"/></a></p>
	</td></tr>
</table>
</div>
	<div align="center" style="background:#555;color:#ffffff;margin:0;padding:5px;text-align:center;">©Copyright '.date('Y').'"&nbsp;&nbsp;<a href="'.WebSite.'" target="_blank" style="color:#fff;text-decoration:none;">'.official_site_name.'</a> - All Rights Reserved</div>
</div>
</center>
</body>
</html>';
		//$body.="\r\n\r\n--PHP-alt-$random_hash--";
		//$body=strip_tags(str_replace(array('<br>','<br />'),"\r\n",$body));
	
	
$mail_sent = mail($p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers, "-f ".official_mail_sender."");
//mail($to, $subject, $message, $headers);
	//echo $body;
	//$mail_sent = @mail( $p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers );
	return $mail_sent ? true : false;
}

function send_mail_olds($p){
$headers = "From: =?utf-8?b?".base64_encode($p['from_name'])."?= <".$p['from_email'].">\r\n";
$headers .= "Reply-To: ". strip_tags($p['from_email']) . "\r\n";
//$headers .= "CC: ". strip_tags($p['from_email']) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$random_hash = md5(date('r'));
//$p['body']=str_replace(array('<br>','<br />'),"\r\n",$p['body']);
	$body='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>'.$p['subject'].'</title>
</head>
<body bgcolor="White">
<center>
<div style="font-size:12px;width:620px;padding:5px;margin:0;color:#555;background:#fff;">
<a style="display:block;text-decoration:none;margin:0;padding:0;" href="'.WebSite.'" target="_blank">
	<img style="padding:0;margin:0;border:none;width:100%;" border="0" src="'.WebSite.'/images/mailheader.jpg" alt="'.official_mail_sender_name.'" />
	<div style="background:#06294F;color:#fff;font-size:11pt;font-weight:700;font-style:italic;margin:0;padding:5px 0;" align="center">'.official_mail_sender_name.'</div>
</a>
<div style="padding:5px;text-align:left;font-size:13px;font-family:verdana;"><br>'.$p['body'].'<br></div>
<div style="background:#06294F;color:#fff;margin:0;padding:5px 0;text-align:center;">©Copyright '.date('Y').'"&nbsp;&nbsp;<a href="'.WebSite.'" target="_blank" style="color:#fff;text-decoration:none;">'.official_site_name.'</a> - All Rights Reserved</div>
</div>
</center>
</body>
</html>';
		//$body.="\r\n\r\n--PHP-alt-$random_hash--";
	
	//echo $body;
	$mail_sent = mail($p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers);
	return $mail_sent ? true : false;
}


function send_mail_with_attachement($p,$file){
global $lang;

$headers = '';
if(isset($p['from_name'])) $headers .= "From: =?utf-8?b?".base64_encode($p['from_name'])."?= <".$p['from_email'].">\r\n";
if(isset($p['from_email'])) $headers .= "Reply-To: ". strip_tags($p['from_email']) . "\r\n";
if(!empty($p['cc'])) $headers .= "CC: ". strip_tags($p['cc']) . "\r\n";
if(!empty($p['bcc'])) $headers .= "BCC: ". strip_tags($p['bcc']) . "\r\n";

// boundary 
$semi_rand = md5(time()); 
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
$headers .= "MIME-Version: 1.0\r\n"."Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\"";
 
//$message .= "If you can see this MIME than your client doesn't accept MIME types!\r\n"."--1a2a3a\r\n";
 
$messagetext= "<center>
<div style=\"font-size:12px;width:620px;padding:5px;margin:0;color:#555;background:#fff;\">
<a style=\"display:block;text-decoration:none;margin:0;padding:0;\" href=\"".WebSite."\" target=\"_blank\">
	<img style=\"padding:0;margin:0;border:none;width:100%;\" border=\"0\" src=\"".WebSite."/images/mailheader.jpg\" alt=\"".official_mail_sender_name."\" />
</a>
<div style=\"background:#344767;color:#fff;font-size:14px;font-weight:700;font-style:italic;margin:0;padding-bottom:5px;height:30px;line-height:30px;\" align=\"center\">".official_mail_sender_name."</div>
<div style=\"padding:5px;text-align:left;font-size:13px;font-family:verdana;\"><br>".$p['body']."<br></div>
<div style=\"background:#344767;color:#fff;margin:0;text-align:center;height:30px;line-height:30px;\">©Copyright ".date('Y')."\"&nbsp;&nbsp;<a href=\"".WebSite."\" target=\"_blank\" style=\"color:#fff;text-decoration:none;\">".official_mail_sender_name."</a> - All Rights Reserved</div>
</div>
</center>\r\n"
  ."--{$mime_boundary}\r\n";
  
  
    // multipart boundary 
    $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $messagetext . "\n\n"; 
    $message .= "--{$mime_boundary}\n";
	//var_dump($files);
   //for($x=0;$x<count($files);$x++){

	$imageFile = $file['filetoup']['tmp_name'];
	$data = base64_encode(file_get_contents($file['tmp_name']));

	$message .= "Content-Type: application/pdf; name=\"".$file['name']."\"\r\n".
  "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
  $message .="Content-disposition: attachment; file=\"".$file['tmp_name']."\"\r\n";
  $message .= "--{$mime_boundary}\n";
//}


	$mail_sent = mail($p['to'],'=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $message, $headers);
	return $mail_sent ? true : false;
}

function send_mail_old($p){
	if(!array_key_exists("replay_to",$p)) $p['replay_to']=$p['from_email'];
	if(isset($p['content_type']) && $p['content_type']=="text/plain"){
		$headers="MIME-Version: 1.0\r\n";
		$headers.="Content-type: text/plain; charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n";
		$headers.='To: '.$p['to']."\r\n";
		
		if(empty($p['from_name'])) $headers.='From: '.$p['from_email']."\r\n";
		else $headers.='From: '.$p['from_name'].' <'.$p['from_email'].'>'."\r\n";
		$headers.='Reply-To: '.$p['replay_to']."\r\n";
		if(!empty($p['cc'])) $headers.='Cc: '.$p['cc']."\r\n";
		if(!empty($p['bcc'])) $headers.='Bcc: '.$p['bcc']."\r\n";
		$body=strip_tags(str_replace(array('<br>','<br />'),"\n",$p['body']));
	}
	else{
		$random_hash = md5(date('r'));
		$headers='To: '.$p['to']."\r\n";
		
		if(empty($p['from_name'])) $headers.='From: '.$p['from_email']."\r\n";
		else $headers.='From: =?utf-8?B?'.base64_encode($p['from_name']).'?= <'.$p['from_email'].'>'."\r\n";
		
		$headers.='Reply-To: '.$p['replay_to']."\r\n";
		if(!empty($p['cc'])) $headers.='Cc: '.$p['cc']."\r\n";
		if(!empty($p['bcc'])) $headers.='Bcc: '.$p['bcc']."\r\n";
		$headers.='MIME-Version: 1.0'."\r\n";
		$headers.= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
		
		$body="This is a multi-part message in MIME format.\r\n\r\n--PHP-alt-$random_hash\r\nContent-Type: text/plain;\r\n charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".strip_tags(str_replace(array('<br>','<br />'),"\n",$p['body']))."\r\n--PHP-alt-$random_hash\r\nContent-Type: text/html;\r\n charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n";
		$body.='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>'.$p['subject'].'</title>
</head>
<body bgcolor="White">
<center>
<div style="font-size:12px;width:620px;padding:5px;margin:0;color:#555;background:#fff;">
<a style="display:block;text-decoration:none;margin:0;padding:0;" href="'.WebSite.'" target="_blank">
	<img style="padding:0;margin:0;border:none;width:100%;" border="0" src="'.WebSite.'/images/mailheader.jpg" alt="'.official_mail_sender_name.'" />
	<div style="background:#06294F;color:#fff;font-size:11pt;font-weight:700;font-style:italic;margin:0;padding:5px 0;" align="center">'.official_mail_sender_name.'</div>
</a>
<div style="padding:5px;text-align:left;font-size:13px;font-family:verdana;"><br>'.$p['body'].'<br></div>
<div style="background:#06294F;color:#fff;margin:0;padding:5px 0;text-align:center;">©Copyright '.date('Y').'"&nbsp;&nbsp;<a href="'.WebSite.'" target="_blank" style="color:#fff;text-decoration:none;">'.official_site_name.'</a> - All Rights Reserved</div>
</div>
</center>
</body>
</html>';
		$body.="\r\n\r\n--PHP-alt-$random_hash--";
	}
	//echo $body;
	$mail_sent = @mail( $p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers );
	return $mail_sent ? true : false;
}
function remote_file_exists($file){
$url=getimagesize($file);
if(is_array($url)){
 return true;
}
else {
 return false;
	}
}

function getproductimage($prid){
	global $lang;
	if(!empty($prid)){
		$query='select image,id from products where id="'.$prid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		$row=mysql_fetch_row($result);

		if(!empty($row[0])){
			if(file_exists(servImagesDir."/products/".$row[0]))	$image='../../images/products/'.$row[0];
			//else $image='../../images/products/'.$row[0];
			echo '<div class="pimg"><label class="ib w100"><input type="checkbox" name="dimage" value="'.$row[1].'" />
			'.$lang['delete_picture'].'</label><img src="'.$image.'" /></div>';
			
	
		}else{
		echo '<div class="pimagediv"><span>',$lang['upload_image'],'
		<input id="gallery_upload" class="upload toclear" type="file" name="gallery_upload" /></span></div>';
		}
}else{
	echo '<div class="pimagediv"><span>',$lang['upload_image'],'
	<input id="gallery_upload" class="upload toclear" type="file" name="gallery_upload" /></span></div>';
	
	//echo '<font color="Red" size="1">'.$lang['img1'].'</font><br />
	//<div><img class="pimg" alt="',$lang['no_product_image'],'" src="',WebSite,'/images/no_product_image.jpg" /></div>';
	}
}
function wordwrap2($str, $len, $br){
	$arr=explode(' ',$str);
	$str='';
	foreach($arr as $k=>$v) $str.=' '.wordwrap($v,$len,$br,true);
	return $str;
}
function getImages($aid){
	global $lang;
	$numimages=4;
	if(!empty($aid)){
		$query='select image,id,order_id from adverts_gallery where advert_id="'.$aid.'" order by order_id ASC';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);

		echo '<div class="rws mimage"><p>',$lang['max_4_images'],'</p><span class="sell_desc_msg"></span>';
		$firstimage='';
		$highidarray=array();
		if($num_rows > 0){
		for($i=1;$i<=$num_rows;$i++){
			$row=mysql_fetch_row($result);
			$imageid=$row[2];
			$highidarray[]=$imageid;
			if($imageid==1) $firstimage=$row[1];
			$class='';$label='<span class="firstimg"></span>';
			if($row[1]==$firstimage){ $class='1';$label='<span class="firstimg">'.$lang['main_image'].'</span>';}
			echo '<div class="imgupl',$class,'">',$label,'<input type="checkbox" name="dimage[]" id="i'.$row[1].'" value="'.$row[1].'" />
			<label for="i'.$row[1].'">'.$lang['delete_picture'].'</label><br />
			<img src="../../images/adverts/'.$row[0].'.jpg" /></div>';
			
			$arr[substr($row[0],-5,-4)][0]=$row[0];
			$arr[substr($row[0],-5,-4)][1]=$row[1];
			}
		}
		//$highid=max($highidarray);
	echo '</div>';

	
		//var_dump($highidarray);
	for($j=1;$j<=4;$j++){
		if(!in_array($j, $highidarray)){
		//echo $j.' is not in array';
		
	echo '<div data="'.$j.'" id="input'.$j.'" class="imagediv">';
	//echo $j.'-aaa';
	if($j==1) echo '<font color="Red" size="3">',$lang['main_image'],'<br /></font>';
	echo $lang['image'].' '.$j.'<span>',$lang['upload_image'],'
	<input id="gallery_upload_'.$j.'" data="'.$j.'" class="upload" type="file" name="gallery_upload_'.$j.'" /></span></div>';
		}
	}
	
}else{
	echo '<font color="Red" size="3">'.$lang['img1'].'</font><br />
	<div class="imagediv"><img alt="',$lang['no_product_image'],'" src="../images/no_product_image.jpg" /></div>
	<div class="imagediv"><img alt="',$lang['no_product_image'],'" src="../images/no_product_image.jpg" /></div>
	<div class="imagediv"><img alt="',$lang['no_product_image'],'" src="../images/no_product_image.jpg" /></div>
	<div class="imagediv"><img alt="',$lang['no_product_image'],'" src="../images/no_product_image.jpg" /></div>';
	}
}
function create_url($s = ''){
  $c = mb_strtolower((trim($s)), 'UTF-8');
  $c = preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll}\w]/u', '-', $c); 
   $c = str_replace('---', '-', $c);$c = str_replace('--', '-', $c);
  $c = htmlentities(strip_tags($c), ENT_QUOTES, 'UTF-8');
  return trim($c,'-');
}

function get_regions_categories($parts){
global $lang;
$html='';
$selected='';
//var_dump($parts);
if(!isset($_POST['category'])) $_POST['category']='';
if(!empty($parts[2])){
$query = 'select pc.id,bg_category from adverts a, adverts_to_product_categories atpc, products_categories pc where 
a.town="'.mysql_real_escape_string($parts[2]).'" and a.id=atpc.advert_id and atpc.category_id=pc.id group by pc.id';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
	$html.= '<span id="cityfilter"><form action="" method="POST">
	<select class="transform" onchange="this.form.submit()" name="category"><option value="0">'.$lang['category_filter'].'</option>';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	$selected='';
	if($_POST['category'] == $row[1]) $selected = 'selected="selected"';
	$html.= '<option '.$selected.' value="'.$row[1].'">'.$row[1].'</option>';
	}
	$html.= '</select></form></span>';
		}
	}
	return $html;
}

function gettypes($query, $queryallcities){
global $lang;
$html='';
$selected='';
$cities='';

//var_dump($_POST);
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	$cityarray[]=$row[6];
}
	$cities= array_filter(array_unique($cityarray));
	if(is_array($cities)){
	$numbercities=sizeof($cities);
	if($numbercities > 1){
$html.= '<span id="cityfilter"><form action="" method="POST">
<select class="transform" onchange="this.form.submit()" name="city"><option '.$selected.' value="0">'.$lang['city_filter'].'</option>';
foreach($cities as $city) {
$selected='';
if(@$_POST['city'] == $city) $selected='selected="selected"';
$html.= '<option '.$selected.' value="'.$city.'">'.$city.'</option>';
}
$html.= '</select></form></span>';
		}else{
$result2=mysql_query($queryallcities) or die(send_error($queryallcities,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows2=mysql_num_rows($result2);		
if($num_rows2 > 0){
	for($k=0;$k<$num_rows2;$k++){
	$row2=mysql_fetch_row($result2);
	$cityarray[]=$row2[6];
		}
	$cities= array_filter(array_unique($cityarray));
	$html.= '<span id="cityfilter"><form action="" method="POST">
<select class="transform" onchange="this.form.submit()" name="city"><option '.$selected.' value="0">'.$lang['city_filter'].'</option>';
foreach($cities as $city) {
$selected='';
if(@$_POST['city'] == $city) $selected='selected="selected"';
$html.= '<option '.$selected.' value="'.$city.'">'.$city.'</option>';
}
$html.= '</select></form></span>';
				}
		
		
		}
	}
}
return $html;
}

function echo_listings_in_regions($query,$searchterm,$categoryname,$parts){
global $lang;
$origquery=$query;

if(!empty($_POST['city'])){
$origquery.=' and town="'.mysql_real_escape_string($_POST['city']).'" group by a.id order by vip DESC, town asc';
//$origquery.=' and town="'.mysql_real_escape_string($_POST['city']).'" group by a.id order by vip DESC, town asc';
}else{
$origquery.=' group by a.id order by vip DESC';
$query=$origquery;
}

//if($_SERVER['REMOTE_ADDR'] == '85.187.42.179') echo $origquery;
//var_dump($searchterm);
$searchadd=$searchterm;
$splitsearch = explode(',',$searchterm);
$citysearch='';
if(!empty($_POST['city'])) $citysearch=$_POST['city'];

//var_dump($splitsearch);
if(!empty($splitsearch[1])){
if(!empty($_POST['category']))  $searchadd= $splitsearch[0].' '.$splitsearch[1];
else{
if(trim($splitsearch[0]) != trim($splitsearch[1])) $searchadd= $lang['meta_country']['firmi'].' '.$splitsearch[0].' '.$splitsearch[1];
else $searchadd= $lang['meta_country']['firmi'].' '.$splitsearch[0];
	}
 }
if(!empty($splitsearch[2])) $searchadd.= ' '.$splitsearch[2];
if(!empty($splitsearch[3])) $searchadd.= ', '.$splitsearch[3];
if(!empty($splitsearch[4])) $searchadd.= ' '.$splitsearch[4];
if(empty($searchterm)) $searchadd = $categoryname;
$result=mysql_query($origquery) or die(send_error($origquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
$location='';
	if($num_rows > 0){
	if(!empty($_POST['category'])) echo '<h1>',$lang['search_h1_before'],'',$num_rows,' ',$lang['search_h1_after'],' ',$_POST['category'],' ',$searchadd,' ',$citysearch,'</h1>';
	else echo '<h1>',$lang['search_h1_before'],'',$num_rows,' ',$lang['search_h1_after'],'',$searchadd,' ',$citysearch,'</h1>';
	echo '<button id="showMe">',$lang['show_me'],'</button><span id="cityspan"></span>';
	if(!empty($_POST['category'])) echo '<div id="googlemap"></div>';
	//the message !
	echo '<div id="overlay"></div><div class="steps"><img id="closebtn" src="',WebSite,'/images/interface/delete.png" />
	<h2 id="bh2">',$lang['allow'],'</h2>
	<span id="bimage"></span>
	</div>';
	$numbercities=0;
	for($i=0;$i<$num_rows;$i++){
	$class='';
	$vip='';
	$image=WebSite.'/images/no_advert_image.jpg';
		$row=mysql_fetch_row($result);
		$cityarray[]=$row[6];

		$category=$row[11];
		$city=str_replace(' ','-',$row[6]);
		$area=str_replace(' ','-',$row[7]);
		$postcode=str_replace(' ','-',$row[8]);
		$name = $row[1];
		$long = $row[13];
		$latt= $row[14];
		$location[]= $row[1].', '.$row[14].', '.$row[13].','.($i+1).','.$row[0].','.$row[15].','.$row[16].','.$row[6].','.$row[5];
		if($row[12]=='1'){ $class='class="vip"';$vip='vip';}
		if(!empty($row[4])){ $image=WebSite.'/images/adverts/small/'.$row[4];
		//echo WebHome.webImagesDir.'adverts/small/'.$row[4];
		if(!file_exists(WebHome.webImagesDir.'adverts/small/'.$row[4])) $image=WebSite.'/images/no_advert_image.jpg';
		}
		echo '<div id="',$row[0],'" class="row ',$vip,'">
		<div class="image"><a href="'.WebSite.'/business-directory/',$row[11],'/',$row[2],'"><img src="',$image,'" alt="',$row[9],' - ',$row[1],'"/></a></div>
		<div class="descr"><i><img src="'.WebSite.'/images/interface/c5.png" alt="',$lang['category'],'" /><a title="',$lang['show_all_from_cat'],' ',$row[9],'" href="'.WebSite.'/business-directory/',$row[11],'">',$row[9],'</a></i>
		<h2 ',$class,'><a href="'.WebSite.'/business-directory/',$row[11],'/',$row[2],'">',$row[1],'</a></h2>
		<p>',$row[3],'</p></div><div class="advrating">';
		echo product_rating($row[0],"html");
		if(!empty($row[6])) echo '<span class="cd"><img src="'.WebSite.'/images/interface/map.png" alt="',$lang['category'],'" /><a href="'.WebSite.'/regions/'.$category.'/'.$city.'" title="',$lang['find'],' ',$row[9],' ',$lang['in'],' ',$row[6],'">',$row[9],' ',$lang['in'],' ',$row[6],'</a></span>';
		if(!empty($row[7])) echo '<span class="cd"><a href="'.WebSite.'/regions/'.$category.'/'.$city.'/'.$area.'" title="',$lang['find'],' ',$row[9],' ',$lang['in'],' ',$row[7],'">',$row[9],' ',$lang['in'],' ',$row[7],',',$row[6],'</a></span>';
		echo '<span class="more"><span><a href="'.WebSite.'/business-directory/',$row[11],'/',$row[2],'">',$lang['view_profile'],'</a></span></span></div>
		</div>';
		}
		//if($_SESSION['advert_id']=='82') var_dump($location);
		echo $cityselect = get_regions_categories($parts);
		if(!empty($_POST['category'])){
	?>
<script type="text/javascript">
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("<?php echo $lang['u_denied'];?>");
            break;
        case error.POSITION_UNAVAILABLE:
		alert("<?php echo $lang['l_unavailable'];?>");
		break;
        case error.TIMEOUT:
            alert("<?php echo $lang['time_out'];?>"); 
            break;
        case error.UNKNOWN_ERROR:
            alert("<?php echo $lang['unknown'];?>");
            break;
    }
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
	if(typeof window.orientation !== 'undefined'){
	alert('<?php echo $lang['mobile_user'];?>');
	}else alert('<?php echo $lang['sorry'];?>');
}
function bindInfoWindow(marker, map, infowindow, content) {
        // Allow each marker to have an info window
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(content);
            infowindow.open(map, marker);
        });
    }
function initialize(){
//userbrowser detect start

var userbrowser = navigator.sayswho= (function(){
var ua= navigator.userAgent, tem,
M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
if(/trident/i.test(M[1])){
	tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
	return 'IE '+(tem[1] || '');
}
if(M[1]=== 'Chrome'){
	tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
	if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
}
M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
return M.join(' ');
})();
//userbrowser detect end

userbrowser = userbrowser.replace(/\d/g,'');

if(userbrowser.indexOf("Firefox") > -1 || userbrowser.indexOf("Chrome") > -1 || userbrowser.indexOf("IE") > -1){
$("#showMe").show();
}else $("#showMe").hide();


document.getElementById("showMe").addEventListener('click', function (){

if(typeof window.orientation !== 'undefined'){
	tmout = "5000";
	}else tmout = "5000";

//var url = <?php echo json_encode(WebSite);?>;
//var bimage = url + '/images/' + userbrowser.replace(/\s/g,'') +'.jpg';
//$('#bimage').html('<img src="' + bimage + '"/>');
    if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(function(position) {
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
      var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);

      marker = new google.maps.InfoWindow({
        map: map,
        position: pos,
		zoom:5,
        content: <?php echo json_encode($lang['you_are_here']); ?>,
		
      });
    bounds.extend(pos);
	map.fitBounds(bounds);
        
    }, showError,{timeout: tmout}
	);
  } else {

    // Browser doesn't support Geolocation
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
	alert('<?php echo $lang['sorry'];?>');
    handleNoGeolocation(false);
  }
});
var map;
var locations = <?php echo json_encode($location); ?>;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
// Display a map on the page
    map = new google.maps.Map(document.getElementById("googlemap"), mapOptions);

    // Multiple Markers
	var markers = locations;        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow();
    
    // Loop through our array of markers & place each one on the map 
    for( i = 0; i < markers.length; i++ ){
	loc_array = markers[i].split(",");
        var position = new google.maps.LatLng(loc_array[1], loc_array[2]);
		//alert(position+' - '+loc_array[0]);

        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
			draggable: false,
			raiseOnDrag: true,
            title: loc_array[0]
        });
		// Info Window Content
       	content=loc_array[0] + " - <a class='ac' onclick='move("+ loc_array[4] +");' href='#'>" + <?php echo json_encode($lang['view_profile']);?> + "</a><span class='p'>" + <?php echo json_encode($lang['phone']);?> + ": " + loc_array[6] + " </span><span class='p'>" + <?php echo json_encode($lang['address']);?> + ": " + loc_array[5] + ", " + loc_array[7] + ", " + loc_array[8] + "</span>";
		bindInfoWindow(marker, map, infoWindow, content);

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }
	bounds.extend(marker.position); 
    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
       map.fitBounds(bounds);
        google.maps.event.removeListener(boundsListener);
    });

}
initialize();
</script>
<?php
}
?>
<!--
//working but loading API each requestand causes query limits !!!
<script type="text/javascript">
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("<!--?php echo $lang['u_denied'];?>");
            break;
        case error.POSITION_UNAVAILABLE:
		alert("<!--?php echo $lang['l_unavailable'];?>");
		break;
        case error.TIMEOUT:
            alert("<!--?php echo $lang['time_out'];?>"); 
            break;
        case error.UNKNOWN_ERROR:
            alert("<!--?php echo $lang['unknown'];?>");
            break;
    }
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
	if(typeof window.orientation !== 'undefined'){
	alert('<!--?php echo $lang['mobile_user'];?>');
	}else alert('<!--?php echo $lang['sorry'];?>');
}
function bindInfoWindow(marker, map, infowindow, content) {
        // Allow each marker to have an info window
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(content);
            infowindow.open(map, marker);
        });
    }
function initialize(){
//userbrowser detect start

var userbrowser = navigator.sayswho= (function(){
var ua= navigator.userAgent, tem,
M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
if(/trident/i.test(M[1])){
	tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
	return 'IE '+(tem[1] || '');
}
if(M[1]=== 'Chrome'){
	tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
	if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
}
M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
return M.join(' ');
})();
//userbrowser detect end

userbrowser = userbrowser.replace(/\d/g,'');

if(userbrowser.indexOf("Firefox") > -1 || userbrowser.indexOf("Chrome") > -1 || userbrowser.indexOf("IE") > -1){
$("#showMe").show();
}else $("#showMe").hide();


document.getElementById("showMe").addEventListener('click', function (){

if(typeof window.orientation !== 'undefined'){
	tmout = "5000";
	}else tmout = "5000";

//var url = <!--?php echo json_encode(WebSite);?>;
//var bimage = url + '/images/' + userbrowser.replace(/\s/g,'') +'.jpg';
//$('#bimage').html('<img src="' + bimage + '"/>');
    if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(function(position) {
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
      var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);

      marker = new google.maps.InfoWindow({
        map: map,
        position: pos,
		zoom:5,
        content: <!--?php echo json_encode($lang['you_are_here']); ?>,
		
      });
    bounds.extend(pos);
	map.fitBounds(bounds);
        
    }, showError,{timeout: tmout}
	);
  } else {

    // Browser doesn't support Geolocation
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
	alert('<!--?php echo $lang['sorry'];?>');
    handleNoGeolocation(false);
  }
});


//var map;
var locations = <!--?php echo json_encode($location); ?>;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };

var map = new google.maps.Map(document.getElementById('googlemap'), {
      zoom: 10,
      center: new google.maps.LatLng(42.6073975,-25.4856617),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();
    var geocoder = new google.maps.Geocoder();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
      geocodeAddress(locations[i]);
    }

function geocodeAddress(location) {
loc_array = location.split(",");

var latlng = new google.maps.LatLng(loc_array[1], loc_array[2]);
//console.log(location);

  geocoder.geocode( { 'latLng': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {

     // console.log(results[0].geometry.location);
      map.setCenter(results[0].geometry.location);
	  
	 
		
     createMarker(results[0].geometry.location,location[0]+"<br>"+location[1], location);
    }
    else{
      console.log("some problem in geocode " +' '+status+' '+ loc_array[0]+''+loc_array[1] +''+loc_array[2]);
    }
  }); 
}

function createMarker(latlng,html,location){
var loc_array = location.split(",");

bounds.extend(latlng);
  var marker = new google.maps.Marker({
    position: latlng,
	draggable: false,
	raiseOnDrag: true,
	center: latlng,
	animation: google.maps.Animation.DROP,
	title: loc_array[0],
    map: map
  }); 


infoWindow(marker, map, loc_array[0], loc_array);	

map.fitBounds(bounds);		
}	

function infoWindow(marker, map, title, loc_array) {
        google.maps.event.addListener(marker, 'click', function() {
           // var html = "<div><h3>" + title + "</h3><p>" + address + "<br></div><a href='" + url + "'>View location</a></p></div>";
   content=loc_array[0] + " - <a class='ac' onclick='move("+ loc_array[4] +");' href='#'>" + <!--?php echo json_encode($lang['view_profile']);?> + "</a><span class='p'>" + <!--?php echo json_encode($lang['phone']);?> + ": " + loc_array[6] + " </span><span class='p'>" + <!--?php echo json_encode($lang['address']);?> + ": " + loc_array[5] + ", " + loc_array[7] + ", " + loc_array[8] + "</span>";
           iw = new google.maps.InfoWindow({ content : content, maxWidth : 350});
            iw.open(map,marker);
        });
    }
}
initialize();
</script>
-->

<?php
	}else{
	echo '<h1>',$lang['we_found_this'],' ',$lang['for'],' ',$lang['meta_country']['keywords2'],' ',$searchterm,' ',$citysearch,'</h1>';
	?>
<script>
  (function() {
    var cx = 'partner-pub-8616690735725377:8351055997';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only>
	<?
	}
}



function echo_cat_path($ncid=0,$npid=0,$adverthome=''){
global $lang;
//echo $ncid;
echo '<div class="holder">';
//echo '<div>',$lang['you_are_here'],'</div>';
echo '<div class="links"><a class="fi" title="',$lang['go_home'],'" href="',WebSite,'/home.php">',$lang['home'],'</a> » </div>';
if(isset($_GET['page']) && $_GET['page']=='myorders'){
echo '<div>',$lang['my_orders'],'</div>';
}
elseif(isset($_GET['page']) && $_GET['page']=='login_form'){
echo '<div>',$lang['login'],'</div>';
}
elseif(isset($_GET['page']) && $_GET['page']=='profile'){
echo '<div>',$lang['profile'],'</div>';
}
elseif(isset($_GET['page']) && $_GET['page']=='contacts'){
echo '<div>Връзка с нас</div>';
}
elseif(isset($_GET['article'])){
echo '<div>',$lang['news'],'</div>';
}
if($_SERVER['REQUEST_URI']=='/register.php' || !empty($_GET['facebook'])){
echo '<div>',$lang['new_registration'],'</div>';
}
if(!empty($ncid) || !empty($npid)) $category = intval($ncid);
$query='SELECT bg_category, parent, id,url FROM products_categories WHERE id="'.mysql_real_escape_string($category).'" ';	
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$row=mysql_fetch_row($result);
if(!empty($ncid) || !empty($npid)){
if($row[1]>0){

$query1='SELECT bg_category, parent, id, url FROM products_categories WHERE id="'.$row[1].'" and visible="1"';	
$result1=mysql_query($query1) or die(send_error($query1,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$row1=mysql_fetch_row($result1);
if($row1[1]>0){
$querys='SELECT bg_category, parent, id, url FROM products_categories WHERE id="'.$row1[1].'" and visible="1"';	
$results=mysql_query($querys) or die(send_error($querys,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$rows=mysql_fetch_row($results);
echo '<div class="links"><a href="',WebSite,'/products/',$rows[3],'/" title="Отиди на ',$rows[0],'">',$rows[0],'</a> » </div>';
}
echo '<div class="links"><a href="',WebSite,'/products/',$row1[3],'/" title="Отиди на ',$row1[0],'">',$row1[0],'</a> » </div>';
}
if(!empty($npid)){
echo '<div class="links"><a href="',WebSite,'/products/',$row[3],'/" title="Отиди на ',$row[0],'">',$row[0],'</a></div>';
}else{
echo '<div>',$row[0],'</div>';
}
	}
	echo '</div>';
}

function product_rating($advert_id,$variant){
	global $lang;
	$html="";
	$my_rating=0;
	$first=0;
	$second=0;
	$voted=0;
	$comment=0;
	$visited='';
	$rating_array=array();

	$query="SELECT count(`advert_id`) visits,sum(`voted`) votes, COUNT( NULLIF( comment, '' ) ) as comments, count(NULLIF( approved, 1 )) as numcomments FROM `statistics_adverts_visits` WHERE `advert_id`='".addslashes($advert_id)."' GROUP BY `advert_id`";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$row=mysql_fetch_assoc($result);

	$voted_query="SELECT * FROM `statistics_adverts_visits` WHERE `advert_id`='".addslashes($advert_id)."' AND `ip`='".addslashes($_SERVER['REMOTE_ADDR'])."' AND `voted`<>'0'";
	$voted_result=mysql_query($voted_query) or die(send_error($voted_query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

	$rating_query="SELECT count(*) votes_count FROM `statistics_adverts_visits` WHERE `advert_id`='".addslashes($advert_id)."' AND `voted`<>'0'";
	$rating_result=mysql_query($rating_query) or die(send_error($rating_query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$devider=1;$voted=0;
	//echo mysql_num_rows($voted_result).'-';
	if(mysql_num_rows($rating_result)){
		$rating_row=mysql_fetch_assoc($rating_result);
		if($rating_row['votes_count']>0)
		$devider=$rating_row['votes_count'];
		$voted=$rating_row['votes_count'];
	}

	if($row['visits']!='0'){
		$my_rating=$row['votes']/$devider;
		if($row['numcomments']!='') $comment=$row['numcomments'];
		if($comment == '1') $review=$lang['review'];else $review=$lang['reviews'];
	}
	if($my_rating<10){
		$first=intval($my_rating);
		$second=0;
		if($my_rating!=0){
			$second=$my_rating-intval($my_rating);
		}
		$last=9-$first;
	}
	else{
		$first=10;
		$second=-1;
		$last=0;
	}
	$html.='<span id="starshome">
	<span class="starshome">'.$lang['rating'].': 
	<span>'.round($my_rating,2).'</span> / 10<div class="rat"><span>'.$my_rating.'</span><span>'.$voted.'</span>
	</div></span>
	<span class="starshome">';
	$yellow=$first;
	if(!mysql_num_rows($voted_result)){
		for($i=0;$i<$first;$i++)
		$html.="<img src=\"".WebSite."/images/interface/star1.png\" alt=\"Listing rating\">";
		$half=0;
		$gray=0;
		if($second>=0){
			if($second>0.4){
				$html.="<img src=\"".WebSite."/images/interface/star2.png\" alt=\"Listing rating\" />";
				$half++;
			}
			else{
				$gray++;
				$html.="<img src=\"".WebSite."/images/interface/star3.png\" alt=\"Listing rating\" />";
			}
		}
		$gray+=$last;
		for($j=0;$j<$last;$j++)
			$html.="<img src=\"".WebSite."/images/interface/star3.png\" alt=\"Listing rating\" />";
	}
	else{
		for($i=0;$i<$first;$i++)
		$html.="<img src=\"".WebSite."/images/interface/star1.png\" alt=\"Listing rating\" />";
		$half=0;
		$gray=0;
		if($second>=0){
			if($second>0.4){
			$html.="<img src=\"".WebSite."/images/interface/star2.png\" alt=\"Listing rating\" />";
			$half++;
			}
			else{
				$gray++;
				$html.="<img src=\"".WebSite."/images/interface/star3.png\" alt=\"Listing rating\" />";
			}
		}
		
		$gray+=$last;
		for($j=0;$j<$last;$j++)
		$html.="<img src=\"".WebSite."/images/interface/star3.png\" alt=\"Listing rating\" />";
	}
	$html.="</span></span>";
	$rating_array=array("yellow"=>$yellow,"half"=>$half,"gray"=>$gray,"visits"=>$visited,"votes"=>$voted);
	return '<span class="statistics">'.$html.'</span><span class="count_reviews">'.$comment.' '.$review.'</span>';	
}

function insert_action($tablename,$fields){
	$v="";
	$i=0;
	$count=count($fields);
	$query="INSERT into `".addslashes($tablename)."` (";
	foreach($fields as $key=>$value){
		$i++;
		if($i<$count) $post=", ";
		else $post="";
		$query.="`".addslashes($key)."`".$post;
		$v.="'".addslashes($value)."'".$post;
	}
	$query.=") VALUES (".$v.")";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		
	if(!$result) return 0;
	else return mysql_insert_id();
}
function statistics_product_visits($aid){
	$query="SELECT * FROM `statistics_adverts_visits` WHERE `advert_id`='".addslashes($aid)."' AND `ip`='".addslashes($_SERVER['REMOTE_ADDR'])."'";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	//mahame unikalnite poseshteniq i zapiswame wsqko poseshtenie
	//if(!mysql_num_rows($result)){
		$fields=array(
				"id"=>"''",
				"advert_id"=>$aid,
				"ip"=>$_SERVER['REMOTE_ADDR'],
				"voted"=>0
				);
		$inserted_record=insert_action("statistics_adverts_visits",$fields);
	//}
}

function replace_with_stars($str) {
    $len = strlen($str);
    return mb_substr($str, 0, 1, 'UTF-8').str_repeat('*', $len - 2).mb_substr($str, $len - 1, 1, 'UTF-8');
}

function check_new_msgs(){
$counter = 0;
$query="SELECT count(id) as counter FROM messages WHERE recipient_id='".mysql_real_escape_string($_SESSION['customer'])."' and readed='0' GROUP BY `recipient_id`";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$row=mysql_fetch_assoc($result);
	if(!empty($row['counter'])) $counter = $row['counter'];
	return $counter;
}


function send_order($cid,$customer_names,$customer_phone){
	global $lang;
	$msg='';
	//proverka za neaktivni porachki poradi nepotwarden account
	$q='select id, aid, pid, qty, price, options, delivery_address, DATE_FORMAT(date, "%d/%m/%Y %H:%i") from orders where cid="'.mysql_real_escape_string($cid).'" and order_status="not_active"';
	$qresult=mysql_query($q) or die(send_error($q,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows = mysql_num_rows($qresult);
	if($num_rows > 0){
	for($i=0;$i<$num_rows;$i++){
	$qrow=mysql_fetch_row($qresult);
	$updateorders='update orders set order_status="pending" where cid="'.mysql_real_escape_string($cid).'"';
	$updateordersresult=mysql_query($updateorders) or die(send_error($updateorders,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	
	$info = 'select supplier_name, a.mail, p.product_url, p.category_url, p.title from adverts a, products p where a.id=p.advert_id and p.id="'.$qrow[2].'" and a.id="'.$qrow[1].'" group by p.id';
	$inforesult=mysql_query($info) or die(send_error($info,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($inforesult) > 0){
	$inforow=mysql_fetch_row($inforesult);
	//izprashtame mail na wseki dostawchik
	$email_subject=$lang['new_order'].' '.mail_name;
	$mail_text= $lang['dear'].' '.$inforow[0].', <br />'.$lang['on'].' '.$qrow[7].' '.$lang['received_order'].'<br /><br />
	<b>'.$lang['cnames'].'</b>: '.$customer_names.'<br />
	<b>'.$lang['phone'].'</b>: '.$customer_phone.'<br />
	<b>'.$lang['product'].'</b>: <a href="'.WebSite.'/products/'.$inforow[3].'/'.$inforow[2].'">'.$inforow[4].'</a><br />
	<b>'.$lang['qty'].'</b>: '.$qrow[3].'<br />
	<b>'.$lang['prd_price'].'</b>: '.$qrow[4].'<br />';
	if(!empty($qrow[5])) $mail_text.= '<b>'.$lang['selected_option'].'</b>: '.$qrow[5].'<br />';
	$mail_text.= '<b>'.$lang['total'].'</b>: '.number_format(($qrow[3] * $qrow[4]),2).' '.current_currency.'<br />
	<b>'.$lang['delivery_address'].'</b>: '.$qrow[6].'<br /><br />
	'.$lang['thank_msg'].' '.mail_name;
	
	$params=array(
	"content_type"=>"text/html",
	"replay_to"=>official_mail_sender,
	"from_name"=>mail_name,
	"from_email"=>official_mail_sender,
	"bcc"=>official_mail_sender,
	"to"=>$inforow[1],
	"subject"=>$email_subject,
	"body"=>$mail_text
	);
	send_mail($params);
				}
			}
			$msg=$lang['order_success'];
		}
	return $msg;
}

function echo_comments($advert_id){
global $lang;
$html='';
$query='select voted, username, comment, UNIX_TIMESTAMP(addeddate),ip from statistics_adverts_visits where approved="1" and comment!="" and advert_id="'.$advert_id.'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
$html.='<div class="comdiv"><h2 class="orange">'.$lang['comments'].'</h2>';

if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_row($result);
$stars='';
if($row[0] >0){
for($j=0;$j<$row[0];$j++) $stars.='<img alt="'.$lang['voted_with'].' '.$row[0].'" src="../../images/interface/star1.png" />';
}else for($j=0;$j<5;$j++) $stars.='<img alt="'.$lang['voted_with'].' '.$row[0].'" src="../../images/interface/star3.png" />';
$html.='<span class="singlecom">
<span class="name"><span class="ib"><label class="ib wa tal">'.$row[1].' '.$lang['voted_with'].'</label><span class="ib wa ml-3">'.$stars.'</span></span><span class="date fr wa tar">'.$lang['date'].': '.date('j /m /Y g:i',$row[3]).'</span></span>
<p>'.$row[2].'</p>
</span>';

		}
	}
	$html.='</div>';
	return $html;
}

function get_product_name($pid){
$product = array();
$query='select product_url, category_url, title from products where id="'.$pid.'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_assoc($result);

$product['purl'] = $row['product_url'];
$product['curl'] = $row['category_url'];
$product['name'] = $row['title'];
	}
	return $product;
}

function get_advert_details($aid){
$advert = array();
$query='select * from adverts where id="'.$aid.'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_assoc($result);

$advert['name'] = $row['supplier_name'];
	}
	return $advert;
}


function echo_advert_rating($advert_id){
	global $lang;
	$count = array();
	$count[0] = 0;
	$count[1] = 0;
	$count[3] = 0;
	$stars = '';
	$query="SELECT count(`advert_id`) visits,sum(`voted`) votes, COUNT( NULLIF( comment, '' ) ) as comments, count(NULLIF( approved, 1 )) as numcomments FROM `statistics_adverts_visits` WHERE `advert_id`='".mysql_real_escape_string($advert_id)."' GROUP BY `advert_id`";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	$row=mysql_fetch_assoc($result);	
	
	
	if(!empty($row['numcomments'])){
	//echo $row['numcomments'],'aaaaaa';
	$r = round(($row['votes'] / $row['numcomments']));
	$count[1] = $row['numcomments'];
	$count[3] = $r;
	for($i=0;$i<$r;$i++) $stars.='<div class="rating-star sm is--active"><i class="fa fa-star"></i></div>&nbsp;';
	}else{
	for($i=0;$i<5;$i++) $stars.='<div class="rating-star sm is--active"><i class="fa fa-star"></i></div>&nbsp;';
	//$count[1] = 5;
	$count[3]= 5;
	}
	
	$count[2] = $stars;
	
	return $count;
}


function check_nearest_ads($lat,$lng){
global $lang;
	//echo $lat;echo '<br/>';
	//echo $lng;echo '<br/>';
	$ads = 'nqmaaa';
	$query = '
	SELECT d.*, dp.*, (
   6371 *
   acos(cos(radians('.$lat.')) * 
   cos(radians(lat)) * 
   cos(radians(lng) - 
   radians('.$lng.')) + 
   sin(radians('.$lat.')) * 
   sin(radians(lat )))
) AS distance 
 from drivers d JOIN driver_positions dp ON dp.driver_id = d.id JOIN (SELECT driver_id, MAX(added_date) ad FROM driver_positions GROUP by driver_id) max_dp ON dp.driver_id = max_dp.driver_id AND dp.added_date = max_dp.ad and DATE(dp.added_date) = CURDATE() order by distance ASC limit 3
	';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0){
	$ads = array();
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_assoc($result);
	$ads[$i]=array("driver_id"=>$row['driver_id'],"names"=>$row['names'], "distance"=>number_format($row['distance'],2) );
		}
	}
	return $ads;
}

function html_product_rating($advert_id,$variant,$advert){
	global $lang;
	$html="";
	$voted_query="SELECT * FROM `statistics_adverts_visits` WHERE `advert_id`='".addslashes($advert_id)."' AND `ip`='".addslashes($_SERVER['REMOTE_ADDR'])."' AND `voted`<>'0'";
	$voted_result=mysql_query($voted_query) or die(send_error($voted_query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

	
	$first=5;
	if(mysql_num_rows($voted_result)) $html.='<div class="msg tac voted">'.$lang['alredy_voted'].'</div>';
	$html.=echo_comments($advert_id);
	$html.='<h4 class="ib w100">'.$lang['your_comment'].'</h4><span class="gr"><small>'.$lang['share_comment'].' <b>'.$advert.'</b> '.$lang['share_comment2'].'</small></span>';
	$html.='<span class="msg ib w100 tac mt-3 mb-3"></span>';
	if(!mysql_num_rows($voted_result)){
	$html.='<div class="mt-2"><span id="stars">
	<form id="subcom" name="subcom" action="" method="post">
	<input id="selectedstars" type="hidden" name="selectedstars" value=""/>';
	$names= '';
	if(isset($_SESSION['customer'])) $names= $_SESSION['customer_names'];
	$html.='<span id="voted_stars" data="'.round(@$my_rating,2).'" class="stars">';
	$html.="<div class='form-group'><label>".$lang['names']."</label><input id='anames' class='form-control' type='text' name='authorname' value='".$names."' maxlength='100'/></div>
	<div class='form-group'><label>".$lang['your_comment']."</label><textarea id='authorcomments' class='form-control' name='authorcomments' maxlength='500' value=''></textarea></div>";
	$html.='<div class="mt-2"><span class="stars ib w100"><span>'.$lang['please_vote'].' </span><span> 1 / 5</span></span><span class="starshold ib w100 mt-3 mb-4">';
		for($i=1;$i<=$first;$i++){
		$html.="<img onClick=\"change_stars(".($i).");\" class=\"is ml-2\" id=\"star_".$i."\" src=\"".WebSite."/images/interface/star3.png\" onMouseOver=\"change_stars(".$i.");\" alt=\"".$lang['vote']."\" />";
		}
	//onClick=\"product_vote('".$advert_id."',".($i+1+$j).");return false;\";
	//onMouseOut=\"restore_stars(".round($my_rating,2).");\" ;
	$html.="</span><p class='tac'><input data-id='".$advert_id."' class='btn btn-secondary' id='savecomment' name='savecomment' value='".$lang['savecomment']."'/></p></span></form></span></div></div>";

	}
	
	return $html;
}
function valid_local_part($local_part) {
	if(preg_match("/[^a-zA-Z0-9-_@.!#$%&'*\/+=?^`{\|}~]/", $local_part)) return false;
	return true;
}
function valid_domain_part($domain_part) {
	if(preg_match("/[^a-zA-Z0-9-_@#\[\].]/", $domain_part)) return false;
	elseif(preg_match("/[@]/", $domain_part) && preg_match("/[#]/", $domain_part)) return false;
	elseif(preg_match("/[\[]/", $domain_part) || preg_match("/[\]]/", $domain_part)){
		$dot_pos = strrpos($domain_part, ".");
		if(($dot_pos<strrpos($domain_part,"]"))||(strrpos($domain_part,"]")<strrpos($domain_part,"["))) return true;
		elseif(preg_match("/[^0-9.]/", $domain_part)) return false;
		else return false;
	}
	return true;
}
function valid_dot_pos($email){
	$str_len = strlen($email);
	for($i=0; $i<$str_len; $i++) {
		$current_element = $email[$i];
		if($current_element == "." && ($email[$i+1] == ".")){
			return false;
			break;
		}
	}
	return true;
}

function email_valid($temp_email){
	$str_trimmed=trim($temp_email);
	$at_pos=strrpos($str_trimmed,'@');
	$dot_pos=strrpos($str_trimmed,'.');
	$local_part=substr($str_trimmed,0,$at_pos);
	$domain_part=substr($str_trimmed, $at_pos);
	if(!isset($str_trimmed)||is_null($str_trimmed)||empty($str_trimmed)||$str_trimmed=="") return false;
	elseif(!valid_local_part($local_part)) return false;
	elseif(!valid_domain_part($domain_part)) return false;
	elseif($at_pos > $dot_pos) return false;
	elseif(!valid_local_part($local_part)) return false;
	elseif(($str_trimmed[$at_pos+1])==".") return false;
	elseif(!preg_match("/[(@)]/", $str_trimmed) || !preg_match("/[(.)]/", $str_trimmed)) return false;
	return true;
}

function check_form($vip){
global $lang;

$pattern = '~(*UTF8)[a-z\p{Cyrillic}]+~i';
$msg[0]=0;
$msg[1]=$lang['success'];
$msg[2]=0;
$_POST['supplier_name']=sanitaze_text($_POST['supplier_name']);
$_POST['city']=sanitaze_text($_POST['city']);
$_POST['area']=sanitaze_text($_POST['area']);
$_POST['postcode']=sanitaze_text($_POST['postcode']);
$_POST['address']=sanitaze_text($_POST['address']);
$_POST['phone']=sanitaze_text($_POST['phone']);
$_POST['description']=sanitaze_text($_POST['description']);
$_POST['fsearch']=sanitaze_text($_POST['fsearch']);
$_POST['keywords']=sanitaze_text($_POST['keywords']);
$_POST['business_type']=intval($_POST['business_type']);

//$msg=var_dump($_POST);
if(empty($_POST['business_type']) || (!empty($_POST['business_type']) && $_POST['business_type'] < 1)){
	$msg[0]=2;
	$msg[1]=$lang['wrong_business_type'];
}
if(empty($_POST['supplier_name']) or (!empty($_POST['supplier_name']) && (count(preg_split('/\s+/', $_POST['supplier_name'])) > 70))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_supplier_name'];
}
if(!empty($_POST['password']) && !preg_match('/^[A-Za-z0-9\-#!_()]+$/', $_POST['password'])){
	$msg[0]=2;
	$msg[1]=$lang['wrong_password'];
}
if(!empty($_POST['website']) && preg_match('#^https?://#i', $_POST['website']) === 0){
	$msg[0]=2;
	$msg[1]=$lang['wrong_website'];
}

if(empty($_POST['city']) || (!empty($_POST['city']) && (count(preg_split('/\s+/', $_POST['city'])) > 20))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_city'];
}
if((!empty($_POST['area']) && (count(preg_split('/\s+/', $_POST['area'])) > 20))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_area'];
}
if(empty($_POST['postcode']) || (!is_numeric($_POST['postcode']))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_postcode'];
}
if(empty($_POST['address']) || (!empty($_POST['address']) && !preg_match ("/^[a-zA-Zа-пр-яА-Я0-9`~!@#%^&*()_+=№\[\]\{\}\|\\\;:'\"\<\>,.\/\? -]+$/u", $_POST['address']))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_address'];
}
if(!empty($_POST['phone']) && !preg_match('/^[+0-9 #&№(),;\'\/-]+$/', $_POST['phone'])){
	$msg[0]=2;
	$msg[1]=$lang['wrong_phone'];
}

//tuk prowerqwame za towa dali usera ne copy/pastwa edin i sasht text za da mine napred
$words = explode(" ", $_POST['description']);
$result = array_combine($words, array_fill(0, count($words), 0));
foreach($words as $word) {
    $result[$word]++;
}
$toreturntocorrect='';
$i = 0;
foreach($result as $word => $count) {
$strlen=strlen($word);
if($strlen > 5){
if($count > 10){
 $i++;
$toreturntocorrect.="There are $count instances of $word.\n<br />";
		}
	}
}
$numberofrepeatedwords=$i;
$wordcount= count(preg_split('/\s+/', $_POST['description']));
if(empty($_POST['description']) || (!empty($_POST['description']) && $wordcount < 200) || (!empty($_POST['description']) && $numberofrepeatedwords > 5 && !empty($toreturntocorrect))){
	if(!empty($_POST['description']) && $wordcount < 200){
	$msg[0]=2;
	$msg[1]=$lang['short_description'];
	}
	elseif(!empty($toreturntocorrect)){
	$msg[0]=2;
	$msg[1]=$lang['similar_description'].''.$toreturntocorrect;
	}
	/*elseif(!empty($_POST['description']) && !preg_match('/^[\r\n\A-Za-z0-9 \?!()\$*№@:#&.,=_+\'\/-]+$/', $_POST['description'])){
	$msg[0]=2;
	$msg[1]=$lang['desc_chars_notallowed'];
	}*/
	elseif(empty($_POST['description'])){
	$msg[0]=2;
	$msg[1]=$lang['wrong_description'];
	}
}

if((empty($_POST['fsearch'])) || (!empty($_POST['fsearch']) && !preg_match($pattern, $_POST['fsearch'])) || ($_POST['fsearch'] === $lang['category_example'])) {
	$msg[0]=2;
	$msg[1]=$lang['wrong_fsearch'];
}
if(empty($_POST['keywords']) || (!empty($_POST['keywords']) && !preg_match($pattern, $_POST['keywords']))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_keywords'];
}
//tuk proverqvame dopalnitelnite poleta ako e VIP
if($vip > 0){
if(!empty($_POST['facebook_link']) && (!filter_var($_POST['facebook_link'], FILTER_VALIDATE_URL) || false === strpos($_POST['facebook_link'], 'https://'))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_facebook_link'];

}

if(!empty($_POST['googleplus_link']) && (!filter_var($_POST['googleplus_link'], FILTER_VALIDATE_URL) || false === strpos($_POST['googleplus_link'], 'https://'))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_google_link'];
}

if(!empty($_POST['pinterest_link']) && (!filter_var($_POST['pinterest_link'], FILTER_VALIDATE_URL) || false === strpos($_POST['pinterest_link'], 'https://'))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_pinterest_link'];
}

if(!empty($_POST['twitter_link']) && (!filter_var($_POST['twitter_link'], FILTER_VALIDATE_URL) || false === strpos($_POST['twitter_link'], 'https://'))){
	$msg[0]=2;
	$msg[1]=$lang['wrong_twitter_link'];
}
if(isset($_POST['sellbusiness'])){
	if(!empty($_POST['business_price']) && !preg_match('/^[0-9 .+,$-]+$/', $_POST['business_price'])){
	$msg[0]=2;	
	$msg[1]=$lang['wrong_price'];
	}
	if(empty($_POST['sell_description'])){
	$msg[0]=2;	
	$msg[1]=$lang['sell_description_missing'];
		}
	}
	
		if(isset($_FILES)){
		//var_dump($_FILES);
		if(sizeof($_FILES) > 5){
		$msg[0]=2;	
		$msg[1]=$lang['many_images'];
		return $msg;
		}
			foreach($_FILES as $imags => $imags){
			$imagegid=substr($imags, -1);//echo '<br/>';
			//var_dump($imags);echo '<br/>';
			//exit();
			if(isset($_FILES['gallery_upload_'.$imagegid.'']) && !empty($_FILES['gallery_upload_'.$imagegid.'']['name'])){
				//echo 'pp<br />';
				$msg=is_file_uploaded($_FILES['gallery_upload_'.$imagegid.'']);
				//var_dump($msg);exit();
				if($msg[0]>0) return $msg;
			}
		}
	}
}
return $msg;
}

function is_file_uploaded($f){
	global $lang;
	$msg[0]=0;
	$msg[1]=$lang['success'];
	$msg[2]=0;
	//var_dump($f);
	//echo $f['tmp_name'];
	if($f['type']=='image/jpeg' || $f['type']=='image/pjpeg' || $f['type']=='image/jpg' || $f['type']=='image/JPEG' || $f['type']=='image/png' || $f['type']=='image/x-png'){
		//echo $f['tmp_name'];
		if(exif_imagetype($f['tmp_name'])===FALSE){
		//exit();
		$msg[0]=2;
		$msg[1]=$f['name'].': '.$lang['UNSUPPORTED_FILE_TYPE'];
		}
		elseif($f['error']>0){
			switch($f['error']){
				case 1:{
					$msg[0]=2;
					$msg[1]=$f['name'].': '.$lang['UPLOAD_ERR_INI_SIZE'];
					break;
				}
				case 2:{
					$msg[0]=2;
					$msg[1]=$f['name'].': '.$lang['UPLOAD_ERR_FROM_SIZE'];
					break;
				}
				case 3:{
					$msg[0]=2;
					$msg[1]=$f['name'].': '.$lang['UPLOAD_ERR_PARTIAL'];
					break;
				}
				case 4:{
					$msg[0]=2;
					$msg[1]=$f['name'].': '.$lang['UPLOAD_ERR_NO_FILE'];
					break;
				}
				case 6:{
					$msg[0]=2;
					$msg[1]=$f['name'].': '.$lang['UPLOAD_ERR_NO_TMP_DIR'];
					break;
				}
				case 7:{
					$msg[0]=2;
					$msg[1]=$f['name'].': '.$lang['UPLOAD_ERR_CANT_WRITE'];
					break;
				}
				case 8:{
					$msg[0]=2;
					$msg[1]=$f['name'].': '.$lang['UPLOAD_ERR_EXTENSION'];
					break;
				}
			}
		}	
	}
	else{
		$msg[0]=2;
		$msg[1]=$f['name'].': '.$lang['UNSUPPORTED_FILE_TYPE'];
	}
	return $msg;
}

function update_free_advert($advert){
	global $lang;
	$_POST['supplier_name']=sanitaze_text($_POST['supplier_name']);
	$_POST['city']=sanitaze_text($_POST['city']);
	$_POST['area']=sanitaze_text($_POST['area']);
	$_POST['postcode']=sanitaze_text($_POST['postcode']);
	$_POST['address']=sanitaze_text($_POST['address']);
	$_POST['phone']=sanitaze_text($_POST['phone']);
	$_POST['description']=sanitaze_text($_POST['description']);
	$_POST['fsearch']=sanitaze_text($_POST['fsearch']);
	$_POST['keywords']=sanitaze_text($_POST['keywords']);
	$_POST['business_type']=intval($_POST['business_type']);
	
	
	if(isset($_POST['fsearch'])){
	$category='';
	$q='select id,bg_category from products_categories where bg_category="'.mysql_real_escape_string ($_POST['fsearch']).'"';
	$res=mysql_query($q) or die(send_error($q,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($res) > 0){
	$r=mysql_fetch_row($res);
	$category=$r[0];
	}
	
	/*$query='select id from adverts_to_product_categories where category_id="'.mysql_real_escape_string($category).'" and advert_id="'.$advert.'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($result) < 1){
	$query="delete from adverts_to_product_categories where advert_id='".$advert."'";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	
	$query='insert into adverts_to_product_categories set advert_id="'.mysql_real_escape_string(intval($advert)).'", category_id="'.mysql_real_escape_string(intval($category)).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));		
	}*/
}	

	$logoname = create_url($_POST['supplier_name']).'-logo.jpg';
	$query='update adverts set 
	supplier_name="'.mysql_real_escape_string (htmlspecialchars($_POST['supplier_name'])).'",
	description="'.mysql_real_escape_string (htmlspecialchars($_POST['description'])).'",
	meta_title="'.mysql_real_escape_string (htmlspecialchars($_POST['supplier_name'])).'",
	meta_keywords="'.mysql_real_escape_string(htmlspecialchars($_POST['keywords'])).'",
	postcode="'.mysql_real_escape_string($_POST['postcode']).'",
	town="'.mysql_real_escape_string($_POST['city']).'",
	area="'.mysql_real_escape_string($_POST['area']).'",
	company_address="'.mysql_real_escape_string($_POST['address']).'",
	company_phones="'.mysql_real_escape_string($_POST['phone']).'",
	site="'.mysql_real_escape_string($_POST['website']).'",
	expired_date="0000-00-00",
	active="0",business_type="'.mysql_real_escape_string($_POST['business_type']).'",
	vip="0"';
	
	if(!empty($_FILES['logo_upload']['name'])) $query.=', small_image="'.mysql_real_escape_string($logoname).'"';
	
	if(!empty($_POST['password'])){
	$query.=', pass="'.md5($_POST['password']).'"';
	}
	$query.=' where id="'.mysql_real_escape_string (intval($advert)).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$msg[0]=0;
	$msg[1]=$lang['profile_updated'];
	
	if(!empty($_FILES['logo_upload']['name'])){
	$msg[0]=0;
	$msg[1]=copy_and_resize_logo($_FILES['logo_upload'],$logoname).'<br />'.$msg[1];
	}
	//echo '<br><br>'.$query;

	return $msg;
}

function save_product($prid,$title,$description,$price,$url,$categoryid,$aid,$categoryname,$categoryurl,$options){
	global $lang;
	$pmsg = array();
	if($prid > 0){
	update_product($prid,$title,$description,$price,$url,$categoryid,$aid,$categoryname,$categoryurl,$options);
	exit();
	}
	$image = create_url($title);
	$insquery = 'insert into products set product_url="'.mysql_real_escape_string(create_url($title)).'",
	advert_id="'.mysql_real_escape_string($aid).'",
	category_id="'.mysql_real_escape_string($categoryid).'",title="'.mysql_real_escape_string($title).'",
	description="'.mysql_real_escape_string($description).'",
	category_name="'.mysql_real_escape_string($categoryname).'",
	category_url="'.mysql_real_escape_string($categoryurl).'",
	addeddate ="'.(date("Y-m-d H:i:s", time())).'",';
	if(isset($_FILES['gallery_upload']) && !empty($_FILES['gallery_upload']['name'])){
	$insquery.='image="'.mysql_real_escape_string($image).'.jpg",';

	}
	if(!empty($options)) $insquery.=' product_options="'.mysql_real_escape_string($options).'",';
	$insquery.='price="'.mysql_real_escape_string(str_replace(',','.',$price)).'",redirect_url="'.mysql_real_escape_string($url).'"';
	$insresult=mysql_query($insquery) or die(send_error($insquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$prid = mysql_insert_id();
	$pmsg[]= array_push($pmsg,$lang['prd_recorded']);
	
	//gallery
	if(!empty($_FILES)){
	if(isset($_FILES['gallery_upload']) && !empty($_FILES['gallery_upload']['name'])){
		copy_and_resize_image_product($_FILES['gallery_upload'],$image.'.jpg');
		}
	}
	$pmsg[]= array_push($pmsg,$prid);//array($pmsg, $prid);

	return $pmsg;
}


function update_product($prid,$title,$description,$price,$url,$categoryid,$aid,$categoryname,$categoryurl,$options){
	global $lang;
	$pmsg = array();
	$image = create_url($title);

	$updquery = 'update products set product_url="'.mysql_real_escape_string(create_url($title)).'",
	title="'.mysql_real_escape_string($title).'", 
	description="'.mysql_real_escape_string($description).'",
	category_name="'.mysql_real_escape_string($categoryname).'",
	category_url="'.mysql_real_escape_string($categoryurl).'",
	';
	
	if(isset($_FILES['gallery_upload']) && !empty($_FILES['gallery_upload']['name'])){
	$updquery.='image="'.mysql_real_escape_string($image).'.jpg",';

	}
	
	if(!empty($options)) $updquery.=' product_options="'.mysql_real_escape_string($options).'",';

	$updquery.='price="'.mysql_real_escape_string(str_replace(',','.',$price)).'",
	category_id="'.mysql_real_escape_string($categoryid).'" , 
	redirect_url="'.mysql_real_escape_string($url).'",
	addeddate ="'.(date("Y-m-d H:i:s", time())).'" where 
	advert_id="'.mysql_real_escape_string($aid).'" and id="'.mysql_real_escape_string($prid).'"';
	$updresult=mysql_query($updquery) or die(send_error($updquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	//$pmsg = $lang['prd_updated'];
	//echo $updquery;
	$pmsg[]= array_push($pmsg,$lang['prd_updated']);
	
	if(!empty($_POST['dimage'])){
	//echo $i;//=substr($i, -9, 9);
		$query='select image from products where id="'.mysql_real_escape_string($prid).'" and advert_id="'.$aid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
			$row=mysql_fetch_row($result);
			$path = servShopHome.ImagesSubDir."products";
			$file=$path."/".$row[0];
			if(file_exists($file)){
			unlink($file);
			}else echo $file.'can not be deleted !';
		$query='update products set image="" where id="'.mysql_real_escape_string($prid).'" and advert_id="'.$aid.'"';
		mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		unset($_FILES);
	}
	
	//gallery
	if(!empty($_FILES)){
	if(isset($_FILES['gallery_upload']) && !empty($_FILES['gallery_upload']['name'])){
		copy_and_resize_image_product($_FILES['gallery_upload'],$image.'.jpg');
		}
	}
	
	return $pmsg;
}

function update_premium_advert($advert){
global $lang;
$_POST['supplier_name']=sanitaze_text($_POST['supplier_name']);
$_POST['city']=sanitaze_text($_POST['city']);
$_POST['area']=sanitaze_text($_POST['area']);
$_POST['postcode']=sanitaze_text($_POST['postcode']);
$_POST['address']=sanitaze_text($_POST['address']);
$_POST['phone']=sanitaze_text($_POST['phone']);
$_POST['description']=sanitaze_text($_POST['description']);
$_POST['fsearch']=sanitaze_text($_POST['fsearch']);
$_POST['keywords']=sanitaze_text($_POST['keywords']);

$_POST['sell_description']=sanitaze_text($_POST['sell_description']);

		
		if(isset($_POST['fsearch'])){
		$category='';
		$q='select id,bg_category from products_categories where bg_category="'.mysql_real_escape_string ($_POST['fsearch']).'"';
		$res=mysql_query($q) or die(send_error($q,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		if(mysql_num_rows($res) > 0){
		$r=mysql_fetch_row($res);
		$category=$r[0];
		}
		
		/*$query='select id from adverts_to_product_categories where category_id="'.mysql_real_escape_string ($category).'" and advert_id="'.$advert.'"';
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		if(mysql_num_rows($result) < 1){
		$query="delete from adverts_to_product_categories where advert_id='".$advert."'";
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		
		$query='insert into adverts_to_product_categories set advert_id="'.mysql_real_escape_string(intval($advert)).'", category_id="'.mysql_real_escape_string(intval($category)).'"';
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));		
		}*/
	}
	
	
	if(!empty($_POST['dimage'])){
	foreach($_POST['dimage'] as $i){
	//echo $i;//=substr($i, -9, 9);
		$query='select image,order_id from adverts_gallery where id="'.mysql_real_escape_string($i).'" and advert_id="'.$advert.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		for($j=0;$j<$num_rows;$j++){
			$row=mysql_fetch_row($result);
			$path = servShopHome.ImagesSubDir."adverts";
			$file=$path."/".$row[0].".jpg";
			if(file_exists($file)){
			unlink($file);
			}else echo $file.'can not be deleted !';
		$query='delete from adverts_gallery where id="'.$i.'" and advert_id="'.$advert.'"';
		mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}
	}
}

	$googleplusprogram=0;
	$sellbusiness=0;
	$logoname = create_url($_POST['supplier_name']).'-logo.jpg';
	
	if(isset($_POST['googleplusprogram'])) $googleplusprogram=1;
	if(isset($_POST['sellbusiness'])) $sellbusiness=1;
	$query='update adverts set 
	supplier_name="'.mysql_real_escape_string (htmlspecialchars($_POST['supplier_name'])).'",
	description="'.mysql_real_escape_string (htmlspecialchars($_POST['description'])).'",
	meta_title="'.mysql_real_escape_string (htmlspecialchars($_POST['supplier_name'])).'",
	meta_keywords="'.mysql_real_escape_string(htmlspecialchars($_POST['keywords'])).'",
	postcode="'.mysql_real_escape_string($_POST['postcode']).'",
	town="'.mysql_real_escape_string($_POST['city']).'",
	area="'.mysql_real_escape_string($_POST['area']).'",
	company_address="'.mysql_real_escape_string($_POST['address']).'",
	company_phones="'.mysql_real_escape_string($_POST['phone']).'",
	site="'.mysql_real_escape_string($_POST['website']).'",business_type="'.mysql_real_escape_string($_POST['business_type']).'",
	active="0"';
	
	if(!empty($_FILES['logo_upload']['name'])) $query.=', small_image="'.mysql_real_escape_string($logoname).'"';
	
	if(!empty($_POST['password'])){
	$query.=', pass="'.md5($_POST['password']).'"';
	}
	$query.=', facebook="'.mysql_real_escape_string($_POST['facebook_link']).'",
	twitter="'.mysql_real_escape_string($_POST['twitter_link']).'",
	google="'.mysql_real_escape_string($_POST['googleplus_link']).'",
	pinterest="'.mysql_real_escape_string($_POST['pinterest_link']).'",
	googleplusprogram="'.mysql_real_escape_string($googleplusprogram).'",
	sellbusiness="'.mysql_real_escape_string($sellbusiness).'",
	business_price="'.mysql_real_escape_string($_POST['business_price']).'",
	sell_description="'.mysql_real_escape_string($_POST['sell_description']).'"';
	$query.=' where id="'.mysql_real_escape_string (intval($advert)).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$msg[0]=0;
	$msg[1]=$lang['profile_updated'];
	//echo '<br><br>'.$query;
	
	//gallery
	$imagegid='';
	if(!empty($_FILES)){
	//var_dump($_FILES);
	foreach($_FILES as $imags => $imags){
		 $file = explode('_', $imags);
		$imagegid = $file[2];
			
		//echo $imagegid=substr($imags, -1).'<br />';
		//exit();
		
	$imagename = create_url($_POST['supplier_name']).'-'.$imagegid;
	if(isset($_FILES['gallery_upload_'.$imagegid.'']) && !empty($_FILES['gallery_upload_'.$imagegid.'']['name'])){
		$query='select image,order_id,id from adverts_gallery where advert_id="'.$advert.'" and order_id="'.$imagegid.'"';//echo '<br>';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		for($i=0;$i<$num_rows;$i++){
			$row=mysql_fetch_row($result);
			if(file_exists(servImagesDir."adverts/".$row[0]).'.jpg') @unlink(servImagesDir."adverts/".$row[0].'.jpg');
			$query='delete from adverts_gallery where id="'.$row[2].'"';
			mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}

		copy_and_resize_image_lang($_FILES['gallery_upload_'.$imagegid.''],$imagename.'.jpg',$imagegid);	

	$query='insert into adverts_gallery set advert_id="'.$advert.'", image="'.$imagename.'", order_id="'.$imagegid.'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);	
		}
	}
	if(!empty($_FILES['logo_upload']['name'])){
	$msg[0]=0;
	$msg[1]=copy_and_resize_logo($_FILES['logo_upload'],$logoname).'<br />'.$msg[1];
	}
}

	return $msg;
}


function copy_and_resize_image_new($file,$filetype,$save_file_name,$width,$height,$savelocation){
	// Set a maximum height and width
	//var_dump($f);exit();

	// Get new dimensions
	list($small_width, $small_height) = getimagesize($file);
	$ratio_orig = $small_width/$small_height;
	
	if($small_height > $height){
	if ($width/$height > $ratio_orig) $width = $height*$ratio_orig;
	else $height = $width/$ratio_orig;
	}else{
	$width = $small_width;
	$height = $small_height;
	}
	
	
	// Resample
	$image_p_small = imagecreatetruecolor($width, $height);
	
	switch($filetype){
		case 'image/jpg': $image = imagecreatefromjpg($file);break;
		case 'image/JPEG': $image = imagecreatefromjpg($file);break;
		case 'image/jpeg': $image = imagecreatefromjpeg($file);break;
		case 'image/png': $image = imagecreatefrompng($file);break;
		case 'image/pjpeg': $image = imagecreatefromjpeg($file);break;
		case 'image/x-png': $image = imagecreatefrompng($file);break;
	}
	
		
	imagecopyresampled($image_p_small, $image, 0, 0, 0, 0, $width, $height, $small_width, $small_height);
	
	// Output
	
	imagejpeg($image_p_small, servImagesDir."/".$savelocation."/".$save_file_name);

	imagedestroy($image_p_small);
}

function copy_and_resize_image_product($f,$save_file_name){
	// Set a maximum height and width
	$big_width=1000;
	$big_height=1000;
	$small_width = 300;
	$small_height = 300;
	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($f['tmp_name']);
	$ratio_orig = $width_orig/$height_orig;
	
	if ($small_width/$small_height > $ratio_orig) $small_width = $small_height*$ratio_orig;
	else $small_height = $small_width/$ratio_orig;
	
	// Resample
	$image_p_small = imagecreatetruecolor($small_width, $small_height);
	
	switch($f['type']){
		case 'image/jpg': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/JPEG': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/jpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/png': $image = imagecreatefrompng($f['tmp_name']);break;
		case 'image/pjpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/x-png': $image = imagecreatefrompng($f['tmp_name']);break;
	}
	imagecopyresampled($image_p_small, $image, 0, 0, 0, 0, $small_width, $small_height, $width_orig, $height_orig);
	
	// Output
	
	imagejpeg($image_p_small, servImagesDir."/products/".$save_file_name);
	
	//exit(0);

	imagedestroy($image_p_small);
}

function copy_and_resize_image_lang($f,$save_file_name,$imgid){
	// Set a maximum height and width
	$big_width=1000;
	$big_height=1000;
	$small_width = 350;
	$small_height = 350;
	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($f['tmp_name']);
	$ratio_orig = $width_orig/$height_orig;
	if ($big_width/$big_height > $ratio_orig) $big_width = $big_height*$ratio_orig;
	else $big_height = $big_width/$ratio_orig;
	if ($small_width/$small_height > $ratio_orig) $small_width = $small_height*$ratio_orig;
	else $small_height = $small_width/$ratio_orig;
	
	// Resample
	$image_p_big = imagecreatetruecolor($big_width, $big_height);
	$image_p_small = imagecreatetruecolor($small_width, $small_height);
	
	switch($f['type']){
		case 'image/jpg': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/JPEG': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/jpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/png': $image = imagecreatefrompng($f['tmp_name']);break;
		case 'image/pjpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/x-png': $image = imagecreatefrompng($f['tmp_name']);break;
	}
	imagecopyresampled($image_p_big, $image, 0, 0, 0, 0, $big_width, $big_height, $width_orig, $height_orig);
	imagecopyresampled($image_p_small, $image, 0, 0, 0, 0, $small_width, $small_height, $width_orig, $height_orig);
	
	// Output
	
	imagejpeg($image_p_big, servImagesDir."/adverts/".$save_file_name);
	
	//exit(0);

	imagedestroy($image_p_big);
	imagedestroy($image_p_small);
}

function copy_and_resize_logo($f,$save_file_name){
	global $lang;
	// Set a maximum height and width
	$big_width=1000;
	$big_height=1000;
	$small_width = 350;
	$small_height = 350;
	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($f['tmp_name']);
	if( $width_orig >= $big_width){
	$ratio_orig = $width_orig/$height_orig;
	if ($big_width/$big_height > $ratio_orig) $big_width = $big_height*$ratio_orig;
	else $big_height = $big_width/$ratio_orig;
	
	
	if ($small_width/$small_height > $ratio_orig) $small_width = $small_height*$ratio_orig;
	else $small_height = $small_width/$ratio_orig;
	
	// Resample
	$image_p_big = imagecreatetruecolor($big_width, $big_height);
	$image_p_small = imagecreatetruecolor($small_width, $small_height);
	
	switch($f['type']){
		case 'image/jpg': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/JPEG': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/jpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/png': $image = imagecreatefrompng($f['tmp_name']);break;
		case 'image/pjpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/x-png': $image = imagecreatefrompng($f['tmp_name']);break;
	}
	imagecopyresampled($image_p_big, $image, 0, 0, 0, 0, $big_width, $big_height, $width_orig, $height_orig);
	imagecopyresampled($image_p_small, $image, 0, 0, 0, 0, $small_width, $small_height, $width_orig, $height_orig);
	
	// Output
	
	imagejpeg($image_p_big, servImagesDir."/adverts/".$save_file_name);
	imagejpeg($image_p_small, servImagesDir."/adverts/small/".$save_file_name);
	//exit(0);

	imagedestroy($image_p_big);
	imagedestroy($image_p_small);
	}else return $lang['small_image'];
}
function todays_searches(){
global $lang;
//samo za dneshnite searchowe
//$squery='select distinct(searched_term) from statistics where searched_term!="" and DATE(`timest`) = CURDATE() order by id asc';
$squery='select searched_term from statistics where searched_term!="" and searched_term_approved="1" and on_site="1" group by searched_term order by count(searched_term) desc limit 8';
$sresult=mysql_query($squery) or die(send_error($squery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$snum_rows=mysql_num_rows($sresult);
$html='';
if($snum_rows > 0){
$html.= '<h6>'.$lang['todays_searches'].'</h6><ul class="list-unstyled mb-0"><li>';
for($k=0;$k<$snum_rows;$k++){
	$srow=mysql_fetch_row($sresult);
	$html.= '<a href="'.WebSite.'/search/index.php?search='.str_replace(' ','-',$srow[0]).'" class="btn footer-btn-outline btn-sm btn-pill mb-1">'.$srow[0].'</a>';
	}
	$html.= '</li></ul>';
}
return $html;
}



function check_login_action_customer(){
global $lang;
	$my_result=array(
				"changes"=>array(),
				"result"=>0,
				"user"=>"",
				"permissions_group"=>"",
				"gender"=>""
			);
	$mail_field="admin_mail";
	$password_field="admin_password";

	if((empty($_POST[$mail_field])) or (empty($_POST[$password_field]))){
		$my_result['changes']['message_div']['content']=$lang['wrong_fields'];
	}
	else{
		if(is_mail($_POST[$mail_field])){
			$query='SELECT id,customer_names,customer_phone,customer_address,customer_email FROM customers WHERE 
			customer_email="'.mysql_real_escape_string($_POST[$mail_field]).'" and 
			password="'.mysql_real_escape_string(md5($_POST[$password_field])).'" and active="1"';echo '<br>';
			$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			if(mysql_num_rows($result)){
			$row=mysql_fetch_row($result);
				$my_result['result']=$row[0];
				$_SESSION['customer_names']=$row[1];
				$_SESSION['customer_phone']=$row[2];
				$_SESSION['customer_address']=$row[3];
				$_SESSION['customer_email']=$row[4];
			}
			else{
				$my_result['changes']['message_div']['content']=$lang['wrong_login'];
			}
		}
		else{
			$my_result['changes']['message_div']['content']=$lang['wrong_login'];
		}
	}
	return $my_result;
}

function get_advert_docs($aid){
$advert_docs = array();
$query='select id, doc_name, doc_url, added_date from docs where advert_id = "'.mysql_real_escape_string($aid).'" order by id ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
if($num_rows){
$c = 0;
while($row = mysql_fetch_assoc($result)){
$c++;
		$advert_docs[$c]['doc_name'] = $row['doc_name'];
		$advert_docs[$c]['doc_url'] = $row['doc_url'];
		$advert_docs[$c]['added_date'] = $row['added_date'];
		$advert_docs[$c]['id'] = $row['id'];
		}
	}
return $advert_docs;
}
function toPennies($value){
    return (int) (string) ((float) preg_replace("/[^0-9.]/", "", $value) * 100);
}

function count_advert_products($aid){
$count = 0;

$query='select count(id) from products where advert_id = "'.mysql_real_escape_string($aid).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$row=mysql_fetch_row($result);
$count = $row[0];

return $count;
}
?>