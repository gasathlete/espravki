<?php


session_start();
require 'config.php';
require './translation/'.$_SESSION['lang'].'_lang.php';
require 'project_functions.php';

//var_dump($_SESSION);echo '<br/><br/>';

//echo $_SESSION['city'];

$cities = echo_cities();
//var_dump($cities);
//print geoip_database_info(GEOIP_COUNTRY_EDITION);
//phpinfo();


function echo_meta(){
	global $lang;
	echo '<title>',$lang['meta_home']['title'],'</title>';
	echo '<meta name="description" content="',$lang['meta_home']['description'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_home']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['meta_home']['title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['meta_home']['description'],'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';	
}

function total_listings(){
$total = 0;
	$query='SELECT count(id) as number from adverts';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_assoc($result);
	$total = $row['number'];
	}
	return $total;
}

function total_categories(){
$total = 0;
	$query='SELECT count(id) as number from products_categories';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_assoc($result);
	$total = $row['number'];
	}
	return $total;
}

function total_products(){
$total = 0;
	$query='SELECT count(id) as number from products';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_assoc($result);
	$total = $row['number'];
	}
	return $total;
}

function total_listings_for_sale(){
$total = 0;
	$query='SELECT count(id) as number from adverts where sellbusiness = "1"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_assoc($result);
	$total = $row['number'];
	}
	return $total;
}


function echo_products_home(){

global $lang;

	//$query='SELECT p.title,p.description,p.price,p.image, a.link_name, category_url,a.supplier_name,product_url,category_url, category_name FROM products p,adverts a  
	//WHERE p.advert_id=a.id and a.active="1" and p.active="1" group by p.id, a.id ORDER BY addeddate DESC LIMIT 8';
	
	$query='SELECT p.title,p.description,p.price,p.image, a.link_name, category_url,a.supplier_name,product_url,category_url, category_name, url, p.id   
FROM products p,adverts a, adverts_to_product_categories atpc, products_categories pc   
	WHERE p.advert_id=a.id and atpc.advert_id = a.id and pc.id = atpc.category_id and a.active="1" and p.active="1" and p.deleted="0" and p.checked_by_admin = "1" group by p.id, a.id ORDER BY addeddate DESC LIMIT 8';

	$result2=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result2);
	if($num_rows > 0){
	echo '
	<section class="sptb bg-patterns bg-white">
	<div class="container">
		<div class="section-title tac">
			<h2>',$lang['latest_products'],' - <a href="./products/">',$lang['view_all_pr'],'</a></h2>
			<p>',$lang['lp1'],'</p>
		</div>
		<div id="myCarousel2" class="owl-carousel owl-carousel-icons2">';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result2);
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];
	
	/*if($i%3==0){
	?>
<div class="gadvert">
<ins class="adsbygoogle" style="display:inline-block;width:150px;height:420px" data-ad-client="ca-pub-8616690735725377" data-ad-slot="6521826395"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<?php
			}*/
		//echo $i;	
	if($i==3){
	?>
<div class="item">
<img class="advert_img" alt="<?php echo $lang['a_here'];?>" src="./images/banners/baner_adv.jpg" width="100%" />
</div>
<?php
			}else{
	echo '
	<div class="item">
	<div class="card mb-0 overflow-hidden">
		<div class="arrow-ribbon bg-success">€ ',$row[2],'</div>
		<div class="item-card9-icons">';
		
		$activfav = '';
				$favtitle = $lang['add_tofav'];
				if(isset($_COOKIE['fav_prod'])){
				$cookie = unserialize( base64_decode($_COOKIE['fav_prod']));
				//var_dump($cookie);
				if(array_key_exists($row[11],$cookie)){
				$activfav = 'active';
				$favtitle = $lang['added_tofav'];
					}
				}
					echo '<a title="',$favtitle,'" data-id="',$row[11],'" class="item-card9-icons1 wishlist ',$activfav,'"> <i class="fa fa fa-heart-o"></i></a>';
					
		echo '</div>';
		
		echo '<div class="item-card7-imgs h250p">
			<a href="./products/',$row[8],'/',$row[7],'">
			<img src="./images/products/',$image,'" alt="',$row[0],'" class="cover-image"></a>
		</div>
		<div class="item-card7-overlaytext">
			<a target="_blank" href="',WebSite,'/products/',$row[8],'/" class="text-white"> ',$row[9],' </a>
		</div>
		<div class="card-body">
			<div class="item-card7-desc">
				<div class="item-card7-text">
					<a href="',WebSite,'/products/',$row[8],'/',$row[7],'" class="text-dark"><h4 class="h40p">',strip_tags($row[0]),'</h4></a>
				</div>
				<ul class="ib w100 mb-0">
					<li><span class="lhldr"><i class="icon fa fa-user mr-1 text-secondary"></i>
					<a class="orn" title="',$lang['view_profile'],'" href="',WebSite,'/business-directory/',$row[10],'/',$row[4],'">',$row[6],'</a>
					</span></li>
					
				</ul>
				<p class="mb-0 pdesc">',strip_tags($row[1]),'</p>
			</div>
		</div>
		<div class="card-footer">
			<div class="footerimg d-flex mt-0 mb-0">
			<div class="ib w100 tac">
			<a class="btn btn-secondary btn-sm br-tr-3 br-br-3 pl-5 pr-5" href="./products/',$row[8],'/',$row[7],'">',$lang['view_pr'],'</a>
			
			</div>	
			</div>
		</div>
	</div>
</div>';
	}
	
		}
		
		
		echo '</div>
		<div class="ib w100 tac mt-3 mb-3">
			<a class="btn btn-info btn-sm br-tr-3 br-br-3 pl-5 pr-5" href="./products/">',$lang['ent_shop'],'</a>
			</div>
			</div>
		</section>';
	}
}

function echo_home_news(){
	global $lang;
	$query='SELECT unique_name, bg_meta_description, bg_article_title, small_image FROM articles WHERE top_article="1" and active="1" and added_date >= DATE(NOW()) - INTERVAL 30 DAY order by added_date DESC limit 3';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	if($num_rows >0){
	
	
	echo '
	<section class="sptb">
			<div class="container">
				<div class="section-title center-block text-center">
					<h2>',$lang['latest_news'],'</h2>
					<p>',$lang['n1'],'</p>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="myCarousel" class="owl-carousel testimonial-owl-carousel">';

	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];

	if(!file_exists(WebHome.'/images/articles/'.$image.'')) $image = 'no_product_image.jpg';
	
		
	echo '<div class="item card mb-0">
			<div class="row no-gutters">
				<div class="col-lg-6 col-md-4">
					<div class="h-100">
						<img src="./images/articles/'.$image.'" alt="',$row[2],'" class="w-100 h-100"/>
					</div>
				</div>
				<div class="col-lg-6 col-md-8">
					<div class="card-body p-7">
						<h2 class="h95">',$row[2],'</h2>
						<p class="fs-16 mt-4 h200">',$row[1],'</p>
						<div class="ib w100 tar mtac">
						<a class="btn btn-lg btn-secondary px-6" href="./news/',$row[0],'">',$lang['read'],'</a>
						</div>
					</div>
				</div>
			</div>
		</div>';
		}
	echo '</div>
					</div>
				</div>
			</div>
		</section>';
	}
}


function latest_listings_old_carosel_working(){
global $lang;
$query='SELECT supplier_name, link_name, small_image, country, country_code, town, bg_category, url, area, company_phones, opening, closing, meta_description, a.id, a.selected_plan, pc.id, a.sellbusiness   
FROM adverts a,products_categories pc, adverts_to_product_categories atpc 
 WHERE a.active="1" and a.sellbusiness="0" and a.selected_plan !="3" and a.id=atpc.advert_id and atpc.category_id=pc.id group by pc.id order by added_date DESC limit 10';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	if(empty($row[2])){
	$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/no_product_image.jpg"/>';
	}else{
	$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/adverts/'.$row[2].'"/>';
	if(!file_exists(WebHome.'/images/adverts/'.$row[2].'')) $image = '<img class="cover-image" alt="'.$row[0].'" src="'.WebSite.'/images/no_product_image.jpg"/>';
	}
	
	if($row[10] != '00:00' || $row[11] != '00:00'){
	$now = time();//echo 'now<br/>';
	$begin = strtotime($row[10]);//echo 'beg<br/>';
	$end = strtotime($row[11]);//echo 'end<br/>';
	$opened = '<span class="badge badge-danger ml-2 fs-13">'.$lang['closed'].'</span>';
	$tdlt = 'tdlt';
	if (($begin < $end && $now >= $begin && $now <= $end) || ( $begin > $end && ( $now >= $begin || $now <= $end))) {
   $opened = '<span class="badge badge-success ml-2 fs-13">'.$lang['op'].'</span>';
   $tdlt = '';
	}
}
if($row[10] == '00:00' && $row[11] == '00:00') $opened = '<span class="badge badge-success ml-2 fs-13">'.$lang['op'].'</span>';

$rating = echo_advert_rating($row[13]);
//var_dump($rating);
$reviews = $rating[1];

$forsell = '';
if($row[16] > 0) $forsell = '<div class="arrow-ribbon bg-success">'.$lang['fs'].'</div>';
	echo '
	<div class="item lp">
		<div class="card mb-0 overflow-hidden">';
			if($row[16] > 0){
			echo '<div class="arrow-ribbon bg-success bg-success-orng"><i class="fa fa-trophy" aria-hidden="true"></i> '.$lang['fs'].'</div>';
			}else{
			if($row[14] > 1) echo '<div class="power-ribbon power-ribbon-top-left text-warning"><span class="bg-warning"><i class="fa fa-bolt"></i></span></div>';			
			}
			echo '<div class="item-card2-img h300p">
				<a href="./business-directory/',$row[7],'/',$row[1],'">
				'.$image.'</a>
				<div class="item-card2-icons">';
				//ikonkite za otdelnite kategorii
				if($row[15] == '10229') echo '<a href="./business-directory/',$row[7],'/',$row[1],'" class="item-card2-icons-l"><i class="fa fa-cutlery"></i></a>';
				echo '<a href="#" class="item-card2-icons-r"><i class="fa fa fa-heart-o"></i></a>
				</div>
				<div class="blog--category"><a target="_blank" href="./business-directory/',$row[7],'">',$row[6],'</a></div>
			</div>
			<div class="card-body pb-0">
				<div class="item-card2">
					<div class="item-card2-desc">
						<div class="item-card2-text">
							<a href="./business-directory/',$row[7],'/',$row[1],'" class="text-dark"><h4 class="mb-0">',$row[0],'</h4></a>
						</div>
						<div class="pt-3">
							<a href="mb-1"><p class="pb-0 pt-0 mb-2 mt-2"><i class="fa fa-map-marker mr-2"></i>',$row[5],', ',$row[8],'</p></a>';
							if(!empty($row[9])){
							echo '<i class="fa fa-phone mr-2"></i>';
							$phones = explode(";", $row[9]);
							foreach($phones as $phone) echo '<a href="tel:',$phone,'"><p class="pb-0 pt-0 mb-2 mt-2 ib wa">',$phone,'</p></a> | ';
							//var_dump($phones);
							}
							echo '
							<p class="pb-0 pt-0 mb-2 mt-2"><i class="fa fa-clock-o mr-2"></i>',$row[10],' - ',$row[11],'<a> '.$opened.'</a></p>
						</div>
						<p class="desc">',strip_tags(htmlspecialchars_decode($row[12])),'</p>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="item-card2-footer">
					<div class="item-card2-footer-u">
						<div class="d-flex">
							<div class="rating-stars d-inline-flex">
								<input type="number" readonly="readonly" class="rating-value star" name="rating-stars-value" value="5">
								<div class="rating-stars-container">
								'.$rating[2].'
								</div> &nbsp;( '.$reviews.' '.$lang['mn'].')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	
		}
	}
}


function latest_listings(){
global $lang;
$query='SELECT supplier_name, link_name, small_image, country, country_code, town, bg_category, url, area, company_phones, opening, closing, meta_description, a.id, a.selected_plan, pc.id, a.sellbusiness, a.added_date, a.logo     
FROM adverts a,products_categories pc, adverts_to_product_categories atpc 
 WHERE a.active="1" and a.sellbusiness="0" and a.selected_plan !="3" and a.id=atpc.advert_id and atpc.category_id=pc.id and pc.id not in(
 "10261", "10260", "10253", "10252"
 )
 group by a.id order by added_date DESC limit 12';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	
	if(empty($row[2])){
	if(!empty($row[18])){
	$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/adverts/'.$row[18].'"/>';
	}else $image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/no_product_image.jpg"/>';
	}else{
	$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/adverts/'.$row[2].'"/>';
	if(!file_exists(WebHome.'/images/adverts/'.$row[2].'')){
	$image = '<img class="cover-image" alt="'.$row[0].'" src="'.WebSite.'/images/no_product_image.jpg"/>';
	if(!empty($row[18])){
	$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/adverts/'.$row[18].'"/>';
			}
		}
	}
	
	if($row[10] != '00:00' || $row[11] != '00:00'){
	$now = time();//echo 'now<br/>';
	$begin = strtotime($row[10]);//echo 'beg<br/>';
	$end = strtotime($row[11]);//echo 'end<br/>';
	$opened = '<span class="badge badge-danger ml-2 fs-13">'.$lang['closed'].'</span>';
	$tdlt = 'tdlt';
	if (($begin < $end && $now >= $begin && $now <= $end) || ( $begin > $end && ( $now >= $begin || $now <= $end))) {
   $opened = '<span class="badge badge-success ml-2 fs-13">'.$lang['op'].'</span>';
   $tdlt = '';
	}
}
if($row[10] == '00:00' && $row[11] == '00:00') $opened = '<span class="badge badge-success ml-2 fs-13">'.$lang['op'].'</span>';

$rating = echo_advert_rating($row[13]);
//var_dump($rating);
$reviews = $rating[1];

$forsell = '';
if($row[16] > 0) $forsell = '<div class="arrow-ribbon bg-success">'.$lang['fs'].'</div>';
	echo '
	<div class="col-xl-4 col-md-6 lp mt-3 mb-3">
		<div class="card mb-0 tac overflow-hidden">';
			if($row[16] > 0){
			echo '<div class="arrow-ribbon bg-success bg-success-orng"><i class="fa fa-trophy" aria-hidden="true"></i> '.$lang['fs'].'</div>';
			}else{
			if($row[14] > 1) echo '<div class="power-ribbon power-ribbon-top-left text-warning"><span class="bg-warning"><i class="fa fa-bolt"></i></span></div>';			
			}
			echo '<div class="item-card2-img h300p">
				<a href="./business-directory/',$row[7],'/',$row[1],'">
				'.$image.'</a>
				<div class="item-card2-icons">';
				//ikonkite za otdelnite kategorii
				if($row[15] == '10229') echo '<a href="./business-directory/',$row[7],'/',$row[1],'" class="item-card2-icons-l"><i class="fa fa-cutlery"></i></a>';
				echo '<a href="#" class="item-card2-icons-r"><i class="fa fa fa-heart-o"></i></a>
				</div>
				<div class="blog--category"><a target="_blank" href="./business-directory/',$row[7],'">',$row[6],'</a></div>
			</div>
			<div class="card-body pb-0">
				<div class="item-card2">
					<div class="item-card2-desc">
						<div class="item-card2-text">
							<a href="./business-directory/',$row[7],'/',$row[1],'" class="text-dark"><h4 class="mb-0">',$row[0],'</h4></a>
						</div>
						<div class="pt-3">
							<a href="mb-1"><p class="pb-0 pt-0 mb-2 mt-2"><i class="fa fa-map-marker mr-2"></i>',$row[5],', ',$row[8],'</p></a>';
							if(!empty($row[9])){
							echo '<i class="fa fa-phone mr-2"></i>';
							$phones = explode(";", $row[9]);
							foreach($phones as $phone) echo '<a href="tel:',$phone,'"><p class="pb-0 pt-0 mb-2 mt-2 ib wa">',$phone,'</p></a> | ';
							//var_dump($phones);
							}
							echo '
							<p class="pb-0 pt-0 mb-2 mt-2"><i class="fa fa-clock-o mr-2"></i>',$row[10],' - ',$row[11],'<a> '.$opened.'</a></p>
						</div>
						<p class="desc">',strip_tags(htmlspecialchars_decode($row[12])),'</p>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="item-card2-footer">
					<div class="item-card2-footer-u">
						<div class="d-flex">
							<div class="rating-stars d-inline-flex">
								<input type="number" readonly="readonly" class="rating-value star" name="rating-stars-value" value="5">
								<div class="rating-stars-container">
								'.$rating[2].'
								</div> &nbsp;( '.$reviews.' '.$lang['mn'].')
							</div>';
						$count_ad_prds = count_advert_products($row[13]);
						
						if($count_ad_prds > 0) echo '<div class="rating-stars ml-5 d-inline-flex">',$lang['of_pr'],' ('.$count_ad_prds.')</div>';
						echo '</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	
		}
	}
}


function latest_listings_for_sale(){
global $lang;
$query='SELECT supplier_name, link_name, small_image, country, country_code, town, bg_category, url, area, company_phones, business_price, closing, meta_description, a.id, a.selected_plan, pc.id, a.sellbusiness   
FROM adverts a,products_categories pc, adverts_to_product_categories atpc 
 WHERE a.active="1" and a.sellbusiness="1" and a.id=atpc.advert_id and atpc.category_id=pc.id group by pc.id order by added_date DESC limit 10';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	if(empty($row[2])){
	$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/no_product_image.jpg"/>';
	}else{
	$image = '<img alt="'.$row[0].'" src="'.WebSite.'/images/adverts/'.$row[2].'"/>';
	if(!file_exists(WebHome.'/images/adverts/'.$row[2].'')) $image = '<img class="cover-image" alt="'.$row[0].'" src="'.WebSite.'/images/no_product_image.jpg"/>';
	}
	
	

$forsell = '';
if($row[16] > 0) $forsell = '<div class="arrow-ribbon bg-success">'.$lang['fs'].'</div>';
	echo '
	<div class="item lp">
		<div class="card mb-0 overflow-hidden">';
			if($row[16] > 0){
			echo '<div class="arrow-ribbon bg-success bg-success-orng"><i class="fa fa-trophy" aria-hidden="true"></i> '.$lang['fs'].'</div>';
			}else{
			if($row[14] > 1) echo '<div class="power-ribbon power-ribbon-top-left text-warning"><span class="bg-warning"><i class="fa fa-bolt"></i></span></div>';			
			}
			echo '<div class="item-card2-img h250p tac">
				<a href="./business-for-sale/',$row[7],'/',$row[1],'">
				'.$image.'</a>
				<div class="item-card2-icons">';
				//ikonkite za otdelnite kategorii
				if($row[15] == '10229') echo '<a href="./business-for-sale/',$row[7],'/',$row[1],'" class="item-card2-icons-l"><i class="fa fa-cutlery"></i></a>';
				echo '<a href="#" class="item-card2-icons-r"><i class="fa fa fa-heart-o"></i></a>
				</div>
				<div class="blog--category"><a target="_blank" href="./business-for-sale/',$row[7],'">',$row[6],'</a></div>
			</div>
			<div class="card-body pb-0">
				<div class="item-card2">
					<div class="item-card2-desc">
						<div class="item-card2-text">
							<a href="./business-for-sale/',$row[7],'/',$row[1],'" class="text-dark"><h4 class="mb-0">',$row[0],'</h4></a>
						</div>
						<div class="pt-3">
							<a href="mb-1"><p class="pb-0 pt-0 mb-2 mt-2"><i class="fa fa-map-marker mr-2"></i>',$row[5],', ',$row[8],'</p></a>';
							if(!empty($row[9])){
							echo '<i class="fa fa-phone mr-2"></i>';
							$phones = explode(";", $row[9]);
							foreach($phones as $phone) echo '<a href="tel:',$phone,'"><p class="pb-0 pt-0 mb-2 mt-2 ib wa">',$phone,'</p></a> | ';
							//var_dump($phones);
							}
							echo '
						</div>
						<p class="desc">',strip_tags(htmlspecialchars_decode($row[12])),'</p>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="item-card2-footer">
					<div class="item-card2-footer-u">
						<div class="item-card9-cost ffo ib mt-2 text-dark fs-18 w100 pt-2 bg-light border">
			 <label>'.$lang['prd_price'].'</label>: ';
			 setlocale(LC_MONETARY, 'bg_BG.UTF-8');
			
			echo money_format('%.2n',$row[10]).'</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	
		}
	}
}
?>
<!doctype html>
<html lang="en" dir="ltr">

	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta name="verify-paysera" content="575ca41c780aa866ad404a67fec2370c">
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<?php echo echo_meta();?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<!--	<meta http-equiv="X-UA-Compatible" content="IE=11" />
<script data-ad-client="ca-pub-8616690735725377" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>		-->
			<meta name="author" content="Web-site-maker.eu"/>
	<link rel="icon" type="image/png" href="<?php echo WebSite;?>/favicon.ico" />

		<link href="./assets/css/style.css" rel="stylesheet" />

	</head>
	<body>
<?php include './modules/header.php';	
$city = '';
//if(isset($_SESSION['city']) && !empty($_SESSION['city'])) $city = $_SESSION['city'];
//<!--Sliders Section-->
echo '<section>
	<div class="banner-1 cover-image sptb-2 sptb-tab bg-background2" data-image-src="../../assets/images/banners/banner6.jpg">
		<div class="header-text mb-0">
			<div class="container">
				<div class="text-center text-white mb-7">
					<h1 class="mb-1">'.$lang['h_h1'].'</h1>
					<p>'.$lang['h_h1_txt'].'</p>
				</div>
				<div class="row">
					<div class="col-xl-10 col-lg-12 col-md-12 d-block mx-auto">
						<div class="search-background py-3 px-4 bg-white-transparent">
							<form name="search_advert" id="search_advert_form" action="./search/" method="get">
							<div class="form row row-sm homesearch">
								<div class="form-group col-xl-4 col-lg-3 col-md-12 mb-0 pr">
								<input id="srctxt" class="form-control input-lg" type="text" name="search" placeholder="',$lang['w_s'],'" value=""/>
								<input id="exact_match" type="hidden" name="exact_match" value="0" />
								<span><i class="fa fa-search" aria-hidden="true"></i><i class="fa fa-times" aria-hidden="true"></i></span>
								<div id="srctxthldr" class="srctxthldr"></div>
								</div>
								<div class="form-group col-xl-3 col-lg-3 col-md-12 mb-0">
									<input type="text" class="form-control input-lg location-input" name="search_city" id="search_city" value="'.$city.'" placeholder=" '.$lang['where_s'].'" />
									<span><img onclick="getLocation();" src="../../assets/images/svgs/gps.svg" class="location-gps" alt="map"></span>
								</div>
								<div class="form-group col-xl-3 col-lg-3 col-md-12 select2-lg mb-0">
									',echo_categories_in_search(),'
								</div>
								<div class="col-xl-2 col-lg-3 col-md-12 mb-0">
									<button type="submit" name="search_a" id="search_a" value="1" class="btn btn-lg btn-block btn-secondary">'.$lang['search'].'</button>
								</div>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>';
?>		
		
		<section class="categories">
			<div class="container">
				<div id="small-categories" class="owl-carousel owl-carousel-icons2">
					
					
					<div class="item">
						<div class="card mb-0 overflow-hidden">
							<div class="card-body">
								<div class="cat-item text-center">
									<a href="./business-directory/правни-услуги"></a>
									<div class="cat-img category-svg popular_searches">
										<span class="service"></span>	
									</div>
									<div class="cat-desc">
										<h5 class="mb-0"><?php echo $lang['4'];?></h5>
									</div>
								</div>
							</div>
							<div class="card-footer p-0">
								<div class="d-flex">
									<div class="border-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">1</span> <?php echo $lang['city'];?>
										</div>
									</div>
									<div class="float-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">3+</span> <?php echo $lang['firms'];?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				<div class="item">
						<div class="card mb-0 overflow-hidden">
							<div class="card-body">
								<div class="cat-item text-center">
									<a href="./business-directory/счетоводни-услуги"></a>
									<div class="cat-img category-svg popular_searches">
										<span class="accounting"></span>	
									</div>
									<div class="cat-desc">
										<h5 class="mb-0"><?php echo $lang['5'];?></h5>
									</div>
								</div>
							</div>
							<div class="card-footer p-0">
								<div class="d-flex">
									<div class="border-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">5</span> <?php echo $lang['cs'];?>
										</div>
									</div>
									<div class="float-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">14+</span> <?php echo $lang['firms'];?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="item">
						<div class="card mb-0 overflow-hidden">
							<div class="card-body">
								<div class="cat-item text-center">
									<a href="./business-directory/тд-на-нап"></a>
									<div class="cat-img category-svg popular_searches">
										<span class="goverments"></span>	
									</div>
									<div class="cat-desc">
										<h5 class="mb-0"><?php echo $lang['6'];?></h5>
									</div>
								</div>
							</div>
							<div class="card-footer p-0">
								<div class="d-flex">
									<div class="border-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">36</span> <?php echo $lang['cs'];?>
										</div>
									</div>
									<div class="float-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">63+</span> <?php echo $lang['6'];?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="item">
						<div class="card mb-0 overflow-hidden">
							<div class="card-body">
								<div class="cat-item text-center">
									<a href="./business-directory/строителни-фирми"></a>
									<div class="cat-img category-svg popular_searches">
										<span class="building"></span>	
									</div>
									<div class="cat-desc">
										<h5 class="mb-0"><?php echo $lang['9'];?></h5>
									</div>
								</div>
							</div>
							<div class="card-footer p-0">
								<div class="d-flex">
									<div class="border-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">3</span> <?php echo $lang['cs'];?>
										</div>
									</div>
									<div class="float-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">4+</span> <?php echo $lang['firms'];?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="item">
						<div class="card mb-0 overflow-hidden">
							<div class="card-body">
								<div class="cat-item text-center">
									<a href="./business-directory/транспортни-услуги-логистика"></a>
									<div class="cat-img category-svg popular_searches">
										<span class="transportation"></span>	
									</div>
									<div class="cat-desc">
										<h5 class="mb-0"><?php echo $lang['7'];?></h5>
									</div>
								</div>
							</div>
							<div class="card-footer p-0">
								<div class="d-flex">
									<div class="border-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">2</span> <?php echo $lang['cs'];?>
										</div>
									</div>
									<div class="float-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">20+</span> <?php echo $lang['firms'];?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="item">
						<div class="card mb-0 overflow-hidden">
							<div class="card-body">
								<div class="cat-item text-center">
									<a href="./search/index.php?search=онлайн+магазин&exact_match=0&city=999"></a>
									<div class="cat-img category-svg popular_searches">
										<span class="online-shopping"></span>	
									</div>
									<div class="cat-desc">
										<h5 class="mb-0"><?php echo $lang['3'];?></h5>
									</div>
								</div>
							</div>
							<div class="card-footer p-0">
								<div class="d-flex">
									<div class="border-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">8</span> <?php echo $lang['cs'];?>
										</div>
									</div>
									<div class="float-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">20+</span> <?php echo $lang['3'];?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="item">
						<div class="card mb-0 overflow-hidden">
							<div class="card-body">
								<div class="cat-item text-center">
									<a href="./business-directory/посолства/"></a>
									<div class="cat-img category-svg popular_searches">
										<span class="goverments"></span>	
									</div>
									<div class="cat-desc">
										<h5 class="mb-0"><?php echo $lang['8'];?></h5>
									</div>
								</div>
							</div>
							<div class="card-footer p-0">
								<div class="d-flex">
									<div class="border-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">4</span> <?php echo $lang['cs'];?>
										</div>
									</div>
									<div class="float-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">70+</span> <?php echo $lang['8'];?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="item">
						<div class="card mb-0 overflow-hidden">
							<div class="card-body">
								<div class="cat-item text-center">
									<a href="./business-directory/Агенции-за-недвижими-имоти/"></a>
									<div class="cat-img category-svg popular_searches">
										<span class="real-estate"></span>	
									</div>
									<div class="cat-desc">
										<h5 class="mb-0"><?php echo $lang['10'];?></h5>
									</div>
								</div>
							</div>
							<div class="card-footer p-0">
								<div class="d-flex">
									<div class="border-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">1</span> <?php echo $lang['city'];?>
										</div>
									</div>
									<div class="float-right w-150">
										<div class="p-3 text-center">
											<span class="font-weight-bold fs-16">1+</span> <?php echo $lang['ags'];?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</section>
	
	<?php echo_home_news();?>	
	
		<section class="sptb bg-white">
			<div class="container">
				<div class="section-title center-block text-center">
					<h2><?php echo $lang['la'];?></h2>
					<p><?php echo $lang['la1'];?> <?php echo official_site_name;?></p>
				</div>
				<div class="row">
				<?php latest_listings();?>
				</div>
			</div>
		</section>
		
		<section class="mb-4">
<div class="container">
<!--
<a target="_blank" href="https://www.shoppingbulgaria.com/select-plan/">
<img src="/images/banners/banner_trade.jpg" title="Оферта за онлайн търговци" alt="Онлайн Маркетплейс Shoppingbulgaria.com" width="100%" /></a>-->
</div>
</section>



<?php echo_business_for_sale();?>
		
<?php
echo_products_home();
?>
<!--Statistics-->
		<section>
			<div class="about-1 cover-image sptb bg-background-color" data-image-src="../../assets/images/banners/banner5.jpg">
				<div class="content-text mb-0 text-white info">
					<div class="container">
						<div class="row text-center">
							<div class="col-lg-3 col-md-6">
								<div class="counter-status status md-lg-0">
									<div class="counter-icon text-secondary">
										<i class="icon icon-docs"></i>
									</div>
									<h5><?php echo $lang['regs'];?></h5>
									<h2 class="counter mb-0"><?php echo total_listings();?></h2>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="counter-status status-1 md-lg-0">
									<div class="counter-icon text-warning">
										<i class="icon icon-rocket"></i>
									</div>
									<h5><?php echo $lang['bizs'];?></h5>
									<h2 class="counter mb-0"><?php echo total_listings_for_sale();?></h2>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="counter-status mb-md-0">
									<div class="counter-icon">
										<i class="icon icon-people"></i>
									</div>
									<h5> <?php echo $lang['pofs'];?> </h5>
									<h2 class="counter mb-0"><?php echo total_products();?></h2>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="counter-status status mb-0">
									<div class="counter-icon text-success">
										<i class="icon icon-folder"></i>
									</div>
									<h5><?php echo $lang['bcats'];?></h5>
									<h2 class="counter mb-0"><?php echo total_categories();?></h2>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--/Statistics-->


		<!--Section-->
		<section class="sptb bg-white">
			<div class="container">
				<div class="section-title center-block text-center">
					<h2><?php echo $lang['how'];?></h2>
					<p><?php echo $lang['how1'];?></p>
				</div>
                <div class="card overflow-hidden">
					<div class="row no-gutters row-deck">
						<div class="col-md-4">
							<div class="bg-light p-0 box-shadow2">
								<div class="card-body text-center">
									<div class="bg-white icon-bg  icon-service text-purple mb-4 service-card-svg">
										<!--User Icon Svg --->
										<svg height="618pt" viewBox="-28 -19 618 618.66574" width="618pt" xmlns="http://www.w3.org/2000/svg"><path d="m211.265625 264.132812c72.9375 0 132.066406-59.125 132.066406-132.066406 0-72.9375-59.128906-132.066406-132.066406-132.066406s-132.066406 59.128906-132.066406 132.066406c.070312 72.914063 59.15625 131.996094 132.066406 132.066406zm0-239.144531c59.140625 0 107.078125 47.945313 107.078125 107.078125 0 59.140625-47.9375 107.078125-107.078125 107.078125-59.136719 0-107.074219-47.9375-107.074219-107.078125.066406-59.109375 47.964844-107.007812 107.074219-107.078125zm0 0"/><path d="m554.492188 337.976562-107.703126-57.347656c-3.710937-2.023437-8.203124-1.976562-11.871093.121094l-98.582031 53.976562c-36.109376-26.863281-79.34375-40.855468-125.316407-40.855468-57.226562.125-110.328125 22.367187-149.6875 62.847656-39.859375 40.855469-61.726562 96.707031-61.597656 157.304688.019531 6.894531 5.601563 12.476562 12.492187 12.496093l347.597657-.625c16.339843 20.289063 37.347656 36.320313 61.222656 46.730469l14.496094 6.371094c1.574219.703125 3.28125 1.042968 5 1 1.710937-.03125 3.40625-.367188 4.996093-1l16.367188-7.121094c60.136719-25.230469 99.25-84.09375 99.207031-149.3125v-73.46875c.003907-4.640625-2.539062-8.910156-6.621093-11.117188zm-529.394532 163.429688c2.621094-49.105469 21.738282-93.957031 54.226563-127.320312 34.605469-35.609376 81.460937-55.226563 131.816406-55.226563h.375c41.359375 0 80.09375 12.996094 112.203125 37.609375v67.59375c.019531 26.835938 6.714844 53.242188 19.488281 76.839844zm511.027344-78.84375c.023438 55.21875-33.140625 105.039062-84.089844 126.324219h-.125l-11.367187 4.871093-9.5-4.125c-49.96875-21.832031-82.28125-71.160156-82.335938-125.691406v-67.597656l92.332031-50.601562 95.085938 50.726562zm0 0"/><path d="m406.804688 415.941406c-4.484376-5.242187-12.371094-5.855468-17.617188-1.371094-5.242188 4.484376-5.855469 12.371094-1.371094 17.613282l28.359375 33.109375c2.382813 2.769531 5.847657 4.363281 9.496094 4.375 2.863281.023437 5.644531-.945313 7.871094-2.75l65.972656-53.597657c5.347656-4.382812 6.128906-12.269531 1.746094-17.621093-4.378907-5.347657-12.265625-6.128907-17.613281-1.746094l-56.476563 45.980469zm0 0"/></svg>
										<!-- User Icon Svg --->
									</div>
									<h4 class="mb-4 fs-20"><?php echo $lang['how2'];?></h4>
									<p class="mh90"><?php echo $lang['how3'];?></p>
									<a href="./select-plan/" class="btn btn-info text-white px-6"><?php echo $lang['how4'];?></a>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="bg-white p-0 mt-5 mt-md-0 box-shadow2">
								<div class="card-body text-center">
									<div class="bg-light icon-bg  icon-service text-purple mb-4 service-card-svg">
										<!--User Icon Svg --->
										<svg height="482pt" viewBox="-15 0 482 482.60407" width="482pt" xmlns="http://www.w3.org/2000/svg"><path d="m268.941406 173.949219h25.609375c3.3125 0 6-2.6875 6-6 0-3.316407-2.6875-6-6-6h-25.609375c-3.316406 0-6 2.683593-6 6 0 3.3125 2.683594 6 6 6zm0 0"/><path d="m156.160156 173.949219h89.820313c3.316406 0 6-2.6875 6-6 0-3.316407-2.683594-6-6-6h-89.820313c-3.3125 0-6 2.683593-6 6 0 3.3125 2.6875 6 6 6zm0 0"/><path d="m94.542969 173.949219h38.199219c3.3125 0 6-2.6875 6-6 0-3.316407-2.6875-6-6-6h-38.199219c-3.316407 0-6 2.683593-6 6 0 3.3125 2.683593 6 6 6zm0 0"/><path d="m231.722656 214.8125h-106.386718c-3.316407 0-6 2.6875-6 6s2.683593 6 6 6h106.386718c3.3125 0 6-2.6875 6-6s-2.6875-6-6-6zm0 0"/><path d="m94.542969 226.8125h11.136719c3.3125 0 6-2.6875 6-6s-2.6875-6-6-6h-11.136719c-3.316407 0-6 2.6875-6 6s2.683593 6 6 6zm0 0"/><path d="m94.542969 282.972656h71.257812c3.316407 0 6-2.6875 6-6s-2.683593-6-6-6h-71.257812c-3.316407 0-6 2.6875-6 6s2.683593 6 6 6zm0 0"/><path d="m123.347656 322.875h-28.804687c-3.316407 0-6 2.6875-6 6 0 3.316406 2.683593 6 6 6h28.804687c3.3125 0 6-2.683594 6-6 0-3.3125-2.6875-6-6-6zm0 0"/><path d="m442.261719 132.910156c-7.777344-11.804687-23.644531-15.089844-35.464844-7.339844l-6.515625 4.28125h-.015625l-11.476563 7.550782v-88.261719c-.015624-14.136719-11.472656-25.59375-25.609374-25.609375h-131.363282c-6.847656-14.375-21.347656-23.53125-37.269531-23.53125-15.925781 0-30.425781 9.15625-37.273437 23.53125h-131.363282c-14.136718.015625-25.59375 11.472656-25.609375 25.609375v407.863281c.023438 14.132813 11.476563 25.582032 25.609375 25.601563h337.269532c14.132812-.015625 25.585937-11.46875 25.609374-25.601563v-245.601562l51.964844-34.144532c11.804688-7.777343 15.089844-23.640624 7.339844-35.464843zm-222.734375 175.34375-7.09375-10.800781 203.097656-133.449219 7.097656 10.800782zm111.785156 52.011719h-31.566406c-14.136719.015625-25.59375 11.46875-25.609375 25.605469v30.734375h-212.554688c-2.097656-.003907-3.796875-1.707031-3.800781-3.804688v-319.460937c.003906-2.101563 1.703125-3.800782 3.800781-3.808594h26.960938v16.472656c0 3.3125 2.683593 6 6 6h200.007812c3.3125 0 6-2.6875 6-6v-16.472656h26.957031c2.097657.003906 3.800782 1.707031 3.804688 3.808594v81.828125l-144.167969 94.726562c-.09375.0625-.179687.128907-.269531.199219-.054688.039062-.109375.078125-.164062.121094-.183594.144531-.359376.300781-.527344.464844l-.007813.007812c-.160156.164062-.3125.339844-.453125.523438-.035156.042968-.066406.085937-.101562.128906-.113282.152344-.21875.3125-.316406.476562-.019532.027344-.039063.050782-.058594.082032l-27.257813 46.972656-8.101562 5.320312c-2.769531 1.820313-3.539063 5.539063-1.71875 8.308594 1.820312 2.769531 5.539062 3.539062 8.308593 1.71875l8.101563-5.320312 53.929687-6.375c.03125 0 .0625-.011719.09375-.015626.097657-.015624.191407-.035156.289063-.054687.175781-.03125.347656-.066406.515625-.113281.101562-.027344.203125-.0625.300781-.101563.164063-.054687.324219-.113281.480469-.179687.097656-.042969.199219-.089844.292969-.136719.152343-.078125.300781-.160156.445312-.25.058594-.035156.117188-.058594.175781-.101563l110.210938-72.40625zm-8.242188 12-36.933593 36.933594v-23.328125c.007812-7.511719 6.097656-13.597656 13.609375-13.605469zm-115.609374-60.523437-33.777344 3.996093 17.074218-29.421875 8.351563 12.714844zm-1.617188-24.316407-7.09375-10.800781 187.386719-123.121094.011719-.007812 15.699218-10.316406 7.097656 10.800781zm157.335938-251.894531c7.511718.007812 13.597656 6.09375 13.609374 13.609375v96.144531l-33.476562 22v-73.945312c-.011719-8.726563-7.082031-15.796875-15.804688-15.808594h-26.957031v-18.585938c0-3.316406-2.6875-6-6-6h-58.710937v-11.648437c-.003906-1.929687-.140625-3.855469-.410156-5.765625zm-168.632813-23.527344c16.171875.015625 29.28125 13.125 29.296875 29.300782v17.640624c0 3.3125 2.6875 6 6 6h58.710938v35.058594h-188.011719v-35.058594h58.707031c3.3125 0 6-2.6875 6-6v-17.648437c.019531-16.171875 13.125-29.273437 29.296875-29.292969zm182.242187 445c-.015624 7.507813-6.101562 13.59375-13.609374 13.601563h-337.269532c-7.507812-.011719-13.59375-6.09375-13.609375-13.601563v-407.863281c.007813-7.511719 6.097657-13.601563 13.609375-13.609375h127.75c-.269531 1.910156-.40625 3.835938-.410156 5.765625v11.648437h-58.707031c-3.316407 0-6 2.683594-6 6v18.585938h-26.960938c-8.722656.015625-15.789062 7.082031-15.800781 15.808594v319.464844c.011719 8.71875 7.078125 15.789062 15.800781 15.800781h214.757813c.796875-.007813 1.589844-.066407 2.375-.175781.464844.113281.941406.171874 1.421875.175781 1.734375 0 3.382812-.753907 4.515625-2.058594 1.566406-.832031 2.996094-1.890625 4.25-3.144531l49.207031-49.199219c3.339844-3.324219 5.210937-7.847656 5.199219-12.5625v-120.363281l33.476562-22zm57.375-289.773437-1.507812.988281-20.777344-31.625 1.507813-.988281c6.28125-4.128907 14.71875-2.382813 18.84375 3.898437l5.832031 8.878906c4.117188 6.28125 2.371094 14.710938-3.898438 18.84375zm0 0"/></svg>
										<!--User Icon Svg --->
									</div>
									<h4 class="mb-4 fs-20"><?php echo $lang['how5'];?></h4>
									<p class="mh90"><?php echo $lang['how6'];?></p>
									<a href="./select-plan/" class="btn btn-secondary text-white px-6"><?php echo $lang['selPl'];?></a>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="bg-white p-0 mt-5 mt-md-0 box-shadow2">
								<div class="card-body text-center">
									<div class="bg-light icon-bg  icon-service text-purple mb-4 service-card-svg">
										<span><img src="../../assets/images/svgs/business/sale-tag-hand-drawn-symbol.svg" class="location-gps" alt="<?php echo $lang['how7'];?>"></span>
									</div>
									<h4 class="mb-4 fs-20"><?php echo $lang['how7'];?></h4>
									<p class="mh90"><?php echo $lang['how8'];?></p>
									<a href="./select-plan/" class="btn btn-success text-white px-6"><?php echo $lang['add_offer'];?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<!--news -->
		<!--Sale business-->
		

		<!--Our Customers Says-->
		<section class="sptb position-relative pattern">
			<div class="container">
				<div class="section-title center-block text-center">
					<h1 class="text-white position-relative"><?php echo $lang['cop'];?></h1>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="myCarousl1" class="owl-carousel testimonial-owl-carousel">
							<div class="item text-center">
								<div class="row">
									<div class="col-xl-8 col-md-12 d-block mx-auto">
										<div class="testimonia">
										    <p class="text-white-80">
												<i class="fa fa-quote-left text-white-80"></i> <?php echo $lang['cop1'];?> <i class="fa fa-quote-right text-white-80"></i>
											</p>
											<h3 class="title"><?php echo $lang['cop2'];?></h3>
											<span class="post"><?php echo $lang['cop3'];?></span>
											<div class="rating-stars">
												<input type="number" readonly="readonly" class="rating-value star" name="rating-stars-value" value="5">
												<div class="rating-stars-container">
													<div class="rating-star sm is--active">
														<i class="fa fa-star"></i>
													</div>
													<div class="rating-star sm is--active">
														<i class="fa fa-star"></i>
													</div>
													<div class="rating-star sm is--active">
														<i class="fa fa-star"></i>
													</div>
													<div class="rating-star sm">
														<i class="fa fa-star"></i>
													</div>
													<div class="rating-star sm">
														<i class="fa fa-star"></i>
													</div>
												</div>
											</div>
											<div class="owl-controls clickable">
												<div class="owl-pagination">
													<div class="owl-page active">
														<span class=""></span>
													</div>
													<div class="owl-page ">
														<span class=""></span>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="item text-center">
								<div class="row">
									<div class="col-xl-8 col-md-12 d-block mx-auto">
										<div class="testimonia">
											<p class="text-white-80"><i class="fa fa-quote-left"></i> <?php echo $lang['cop4'];?> <i class="fa fa-quote-right"></i> </p>
											<div class="testimonia-data">
												<h3 class="title"><?php echo $lang['cop5'];?></h3>
												<span class="post"><?php echo $lang['cop6'];?></span>
												<div class="rating-stars">
													<input type="number" readonly="readonly" class="rating-value star" name="rating-stars-value"  value="5">
													<div class="rating-stars-container">
														<div class="rating-star sm">
															<i class="fa fa-star"></i>
														</div>
														<div class="rating-star sm">
															<i class="fa fa-star"></i>
														</div>
														<div class="rating-star sm">
															<i class="fa fa-star"></i>
														</div>
														<div class="rating-star sm">
															<i class="fa fa-star"></i>
														</div>
														<div class="rating-star sm">
															<i class="fa fa-star"></i>
														</div>
													</div>
													<div class="owl-controls clickable">
														<div class="owl-pagination">
															<div class="owl-page ">
																<span class=""></span>
															</div>
															<div class="owl-page active">
																<span class=""></span>
															</div>
															
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</section>

		
			<!--Blogs-->
		<section class="sptb bg-white">
			<div class="container">
				<div class="section-title center-block text-center">
					<h2><?php echo $lang['popcitse'];?></h2>
				</div>
                <div class="row">
					<div class="col-sm-12 col-lg-3 col-md-6">
						<div class="item-card overflow-hidden">
							<div class="item-card-desc">
								<a target="_blank" href="<?php echo WebSite.'/regions/София';?>"></a>
								<div class="card text-center overflow-hidden mb-lg-0">
									<div class="card-img">
										<img src="./images/icons/sofia.jpg" alt="Фирми в София" class="cover-image">
									</div>
									<div class="item-card-text text-left">
										<div class="rating-stars">
											<div class="rating-stars-container text-left">
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
											</div>
										</div>
										<h4 class="mb-2">София</h4>
										<small class="text-white"><i class="fa fa-moon-o"></i> 40+ <?php echo $lang['cats'];?>  <i class="ml-3 fa fa-map-o"></i> 270+ <?php echo $lang['firms'];?> </small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-lg-3 col-md-6">
						<div class="item-card overflow-hidden">
							<div class="item-card-desc">
								<a target="_blank" href="<?php echo WebSite.'/regions/Пловдив';?>"></a>
								<div class="card text-center overflow-hidden mb-lg-0">
									<div class="card-img">
										<img src="./images/icons/plovdiv.jpg" alt="Фирми в Пловдив" class="cover-image">
									</div>
									<div class="item-card-text text-left">
										<div class="rating-stars">
											<div class="rating-stars-container text-left">
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
											</div>
										</div>
										<h4 class="mb-2">Пловдив</h4>
										<small class="text-white"><i class="fa fa-moon-o"></i> 40+ <?php echo $lang['cats'];?>  <i class="ml-3 fa fa-map-o"></i> 80+ <?php echo $lang['firms'];?> </small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-lg-3 col-md-6">
						<div class="item-card overflow-hidden">
							<div class="item-card-desc">
								<a target="_blank" href="<?php echo WebSite.'/regions/Варна';?>"></a>
								<div class="card text-center overflow-hidden mb-md-0">
									<div class="card-img">
										<img src="./images/icons/varna.jpg" alt="Фирми във Варна" class="cover-image">
									</div>
									<div class="item-card-text text-left">
										<div class="rating-stars">
											<div class="rating-stars-container text-left">
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
											</div>
										</div>
										<h4 class="mb-2">Варна</h4>
										<small class="text-white"><i class="fa fa-moon-o"></i> 40+ <?php echo $lang['cats'];?>  <i class="ml-3 fa fa-map-o"></i> 80+ <?php echo $lang['firms'];?> </small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-lg-3 col-md-6">
						<div class="item-card overflow-hidden">
							<div class="item-card-desc">
								<a target="_blank" href="<?php echo WebSite.'/regions/Бургас';?>"></a>
								<div class="card text-center overflow-hidden mb-0">
									<div class="card-img">
										<img src="./images/icons/burgas.jpg" alt="Фирми в Бургас" class="cover-image">
									</div>
									<div class="item-card-text text-left">
										<div class="rating-stars">
											<div class="rating-stars-container text-left">
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
												<div class="rating-star sm is--active fs-12">
													<i class="fa fa-star"></i>
												</div>
											</div>
										</div>
										<h4 class="mb-2">Бургас</h4>
										<small class="text-white"><i class="fa fa-moon-o"></i> 40+ <?php echo $lang['cats'];?>  <i class="ml-3 fa fa-map-o"></i> 50+ <?php echo $lang['firms'];?> </small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--Blogs-->
		<!--post section-->
		<?php
		if(!isset($_COOKIE['subscribed'])) {
		?>
		<section>
			<div class="sptb">
				<div class="content-text mb-0">
					<div class="container">
						<div class="text-center">
							<h2 class="mb-2"><?php echo $lang['subscribe'];?></h2>
							<p class="fs-16 mb-0"><?php echo $lang['subscribe_txt'];?></p>
							<div class="msg"></div>
							<div id="sform" class="row">
								<div class="col-lg-8 mx-auto d-block">
									<div class="mt-5">
										<div class="input-group sub-input mt-1">
											<input id="email" value="" type="text" class="form-control input-lg " placeholder="<?php echo $lang['subscribe_plchldr'];?>">
											<div class="input-group-append ">
												<button id="subscribe" type="button" class="btn btn-secondary br-tr-3 br-br-3 pl-5 pr-5">
													<?php echo $lang['subscribe_btn'];?>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
		}
		?>

	

		<!--Section-->
		<!--
		<section class="sptb cover-image patter-image" data-image-src="../../assets/images/pngs/7.png">
			<div class="container">
				<div class="section-title center-block text-center">
					<h2>Download App</h2>
					<p>Mauris ut cursus nunc. Morbi eleifend, ligula at consectetur vehicula</p>
				</div>
                <div class="row">
					<div class="col-md-12">
						<div class="text-center text-wrap">
							<div class="btn-list">
								<a href="#" class="btn btn-secondary btn-lg mb-sm-0"><i class="fa fa-apple fa-1x mr-2"></i> App Store</a>
								<a href="#" class="btn btn-primary btn-lg mb-sm-0"><i class="fa fa-android fa-1x mr-2"></i> Google Play</a>
								<a href="#" class="btn btn-info btn-lg mb-0"><i class="fa fa-windows fa-1x mr-2"></i> Windows</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>-->
		<!--/Section-->

		<?php include './modules/footer.php';?>		
<script>
var keywords = <?php echo json_encode($cities);?>;
//alert(keywords);
</script>
<script type="text/javascript" src="./assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script type="text/javascript" src="<?php echo WebSite;?>/assets/js/fullfunctions.js"></script>
<script type="text/javascript" src="./assets/js/jquery-ui.js"></script>
<script>
//console.log(keywords);
$('#search_city').autocomplete({
//source: [keywords],
	source: keywords,
	minLength: 1,
	select: function(event, ui) {
	event.preventDefault();
	//console.log(ui.item.label);
	$("#search_city").val(ui.item.label);
  }
});
</script>		
	</body>
</html>