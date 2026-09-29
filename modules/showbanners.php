<?php
$allow=0;
switch($_SERVER['HTTP_ORIGIN']){
    case 'http://shoppingbulgaria.com':
	header('Access-Control-Allow-Origin: http://shoppingbulgaria.com');
	header('Access-Control-Allow-Methods: GET, PUT, POST,OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
	$allow=1;
	break;
	case 'http://www.shoppingbulgaria.com':
	header('Access-Control-Allow-Origin: http://www.shoppingbulgaria.com');
	header('Access-Control-Allow-Methods: GET, PUT, POST,OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
	$allow=1;
	break;
	case 'http://test.shoppingbulgaria.com':
	header('Access-Control-Allow-Origin: http://test.shoppingbulgaria.com');
	header('Access-Control-Allow-Methods: GET, PUT, POST,OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
	$allow=1;
	break;
}
session_start();
require '../config.php';
require '../connect.php';


function send_error($query,$url1,$url2,$error,$user_ip){
die(mysql_error());
$message="error in ".$_SERVER["REQUEST_URI"]."\r\n".$_SERVER["PHP_SELF"]."\r\n<br />".$query."\r\n<br />".$error."\r\n<br />User:".$user_ip;
mail(official_mail_sender,"error in ".WebSite."",$message);
}

$cid=0;
if($allow > 0){

$query='SELECT p.title,p.description,p.price,p.image, a.link_name, pc.url,a.supplier_name FROM products p,adverts a, products_categories pc  
WHERE p.advert_id=a.id and a.active="1" and p.category_id=pc.id group by p.advert_id order by addeddate DESC limit 15';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
$htm='';
if($num_rows > 0){
$htm.= '<span class="min" title="Скрий офертата"></span>
<span class="offercount"><b>'.$num_rows.'</b> оферти от <img src="http://shoppingbulgaria.com/images/newlogo1.png" alt="Фирмен каталог Espravki.com" /></span>
<div class="eslideshow">';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
		
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];
	$htm.= '<div><h3>'.$row[0].' - <span>'.$row[2].'</span></h3>
	<a title="Топ оферта от '.$row[6].' за '.$row[0].'" target="_blank" href="http://www.espravki.com/business-directory/'.$row[5].'/'.$row[4].'">
	<span class="imgspan"><img src="http://www.espravki.com/images/products/'.$image.'" alt="'.$row[0].'" /></span>
	<span class="bannershop">Топ оферта от '.$row[6].' за '.$row[0].'
	<span class="newprice">Цена '.$row[2].'</span>
	</a></div>';
		}
	$htm.= '</div>';
	$htm.='<img alt="предишна оферта" src="../images/interface/larrow.png" id="prev" /><img alt="следваща оферта" src="../images/interface/rarrow.png" id="next" />';
	}
	echo $htm;
}
?>
