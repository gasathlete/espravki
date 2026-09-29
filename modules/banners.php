<?php

function echo_advert_to_article($articlelink,$aid){
global $lang;
$query='select supplier_name,link_name,small_image,country,town,bg_category,country_code,url from 
articles_to_adverts aa, adverts a, products_categories pc,adverts_to_product_categories atpc 
where aa.article_id="'.mysql_real_escape_string($aid).'" and aa.advert_id=a.id and a.id=atpc.advert_id and atpc.category_id=pc.id and a.active="1" group by a.id';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows >0){
echo '<div class="banner">';
$row=mysql_fetch_row($result);
if(empty($row[2])){
	$image = '<img alt="'.$row[0].'" width="50" src="'.WebSite.'/images/no_product_image.jpg"/>';
	}else $image = '<img alt="'.$row[0].'" width="50" src="'.WebSite.'/images/adverts/small/'.$row[2].'"/>';
	echo '<div class="card"><a href="',WebSite,'/business-directory/',$row[7],'/',$row[1],'"><span>',$image,'<p class="as"><h4>',$row[0],'</h4></p>
	<div class="ib w100 tal pl-2"><p class="ib">',$lang['country'],': <img src="'.WebSite.'/images/flags/',strtolower($row[6]),'.png"/></p><p class="ib ml-2">',$row[3],'</p>,<p class="ib ml-2">',$row[4],'</p></div>
	<p class="ib w100 tal pl-2">',$lang['category'],': ',$row[5],'</p>
	</span></a></div>';
	echo '</div>';
	}
}

function echo_article_to_advert($advert_id){
global $lang;
$query='select unique_name,bg_meta_description,small_image,bg_article_title from 
articles_to_adverts aa, articles a
where aa.advert_id="'.mysql_real_escape_string($advert_id).'" and aa.article_id=a.id group by a.id';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows >0){
echo '<div class="banner">';

$row=mysql_fetch_row($result);
if(empty($row[2])){
	$image = '<img alt="'.$row[3].'" width="25%" src="'.WebSite.'/images/no_product_image.jpg"/>';
	}else $image = '<img alt="'.$row[3].'" width="25%" src="'.WebSite.'/images/articles/'.$row[2].'"/>';
	echo '<div class="listingrow2"><a href="',WebSite,'/news/',$row[0],'"><span>',$image,'<p class="as">',$row[3],'</p>
	<p class="l1">',$row[1],'</p>
	</span></a></div>';
	echo '</div>';

	}

}
//$_SESSION['article_id']
//echo $articlelink;
if(!empty($articlelink) && !empty($aid)){
echo_advert_to_article($articlelink,$aid);
}
if(!empty($aid)){
echo_article_to_advert($aid);
}

if(!isset($categoryname)) $categoryname = '';
echo echo_vert_products_banner($categoryname);

/*echo '<div class="banner card">
<a target="_blank" title="Рекламирай в Espravki.com" href="'.WebSite.'/contacts/">
<img alt="Рекламирай в Espravki.com" width="100%" src="'.WebSite.'/images/banners/baner_adv.jpg"/>
</a>
</div>';*/
?>