<?php
//phpinfo();
require 'config.php';

function getExpiredAdverts(){
	
	$str='SELECT id FROM adverts WHERE ( (active="0") or (expired_date!="0000-00-00" and expired_date < now()) )';
	$result=mysql_query($str) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	$str='0';
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		if($i==0) $str=$row[0];
		else $str.=','.$row[0];
	}
	return $str;
}

mysql_connect($db_host,$db_user,$db_pass,$db_name);
mysql_select_db($db_name);
mysql_set_charset('utf8');

$fp=fopen('./sitemap.xml','w');
if($fp===false) die('Cannot open file sitemap.xml');
fwrite($fp,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
fwrite($fp,"<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");

fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/home.php</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");


//news
$query='SELECT id, unique_name FROM articles WHERE active="1" and top_article="1" ORDER BY added_date DESC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
if($num_rows>0){
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/news/{$row[1]}</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");
	}
}


//product categories
$query='select pc.url from products_categories pc, adverts_to_product_categories atpc, adverts a 
where pc.visible="1" and pc.id=atpc.category_id and atpc.advert_id=a.id and a.active="1" group by pc.id';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/business-directory/{$row[0]}/</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");
}

//adverts
$query='SELECT a.link_name,pc.url, a.selected_plan FROM adverts a,adverts_to_product_categories atpc,products_categories pc 
WHERE a.id not in ('.getExpiredAdverts().') and a.id=atpc.advert_id and atpc.category_id=pc.id group by a.id';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	if($row[2] == 3) fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/business-for-sale/{$row[1]}/{$row[0]}</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");
	else fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/business-directory/{$row[1]}/{$row[0]}</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");
}

//adverts tag search

$query='SELECT GROUP_CONCAT(DISTINCT meta_keywords SEPARATOR ", ") FROM adverts WHERE meta_keywords !="" GROUP BY meta_keywords'; 
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query); 
$num_rows=mysql_num_rows($result); 
if($num_rows){ 
$colors = array(); 
while ($row = mysql_fetch_array($result, MYSQL_NUM)) { 
    $array = explode(',', $row[0]);  // your row is just a string, explode it to get an array 
    $colors = array_merge($colors, $array); // merge that into the colors array 
} 
array_map('trim', $colors); // in case there was any whitespace in your color strings 
$colors = array_unique($colors);
$temp = array_filter($colors);
foreach($temp as $key => $value) {
 if($value == "" || $value == " " || is_null($value)) {
 unset($temp[$key]);
 }
 }
 $new_array = array_values(array_unique($temp));
for($j = 0; $j < count($new_array); $j++) {
$tag = trim($new_array[$j]);
fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/search/index.php?search={$tag}</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");
	}
}

	fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/products/</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");

$products = 'select * from products where active="1" and deleted="0" and sold="0" and checked_by_admin="1"';
$productsresult=mysql_query($products) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$products);
$productsnum_rows=mysql_num_rows($productsresult);
if($productsnum_rows > 0){
for($i=0;$i<$productsnum_rows;$i++){
	$productsrow=mysql_fetch_assoc($productsresult);
	fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/products/{$productsrow['category_url']}/{$productsrow['product_url']}</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");
	}
}

/*
//products urls with makes only
$query='SELECT category_id, make FROM makesandmodels group by make';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/shop/index.php?offers={$row[0]}&amp;makes={$row[1]}</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");
}
//products urls with make and models
$query='SELECT category_id, make, model FROM makesandmodels';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	fwrite($fp,"\t<url>\n\t\t<loc>".WebSite."/shop/index.php?offers={$row[0]}&amp;makes={$row[1]}&amp;models={$row[2]}</loc>\n\t\t<changefreq>daily</changefreq>\n\t</url>\n");
}
*/
fwrite($fp,'</urlset>');
fclose($fp);
echo 'READY ! ! !';
?>