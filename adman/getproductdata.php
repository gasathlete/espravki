<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once '../config.php';
require_once '../connect.php';
//require_once '../project_functions.php';
require_once '../translation/bg_lang.php';
include_once('../crawler/simple_html_dom.php');

function create_url($s = ''){
  $c = mb_strtolower((trim($s)), 'UTF-8');
  $c = preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll}\w]/u', '-', $c); 
   $c = str_replace('---', '-', $c);$c = str_replace('--', '-', $c);
  $c = htmlentities(strip_tags($c), ENT_QUOTES, 'UTF-8');
  return trim($c,'-');
}

function fileExists($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if( $httpCode == 200 ){return true;}
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function sanitaze_text2($str){
$str=strip_tags($str);
$str = trim($str);
$str = preg_replace(array('/\r\n\r\n/','/\s\s+/','/\r\n/'), '<br /> ', $str);
$str=str_replace(array('"','`','’','„','”'),'',$str);
//mahame paragraph white space ako se kopira text ot html
$str=str_replace('  ',' ',$str);
$str=str_replace(array('\\','[',']','{','}','^','%'),'',$str);
	return $str;
}
	
if((!empty($_SESSION["customer"]) && !empty($_SESSION["advert_id"])) || !empty($_SESSION["adman"])){

if(isset($_SESSION['temp_images'])) unset($_SESSION['temp_images']);
$error = '';
$data=array();

if(!empty($_REQUEST['advertid'])) $_SESSION["advert_id"] = $_REQUEST['advertid'];
$query='select advert_title_indentificator, advert_desc_indentificator, advert_price_indentificator, advert_img_indentificator, site, advert_category_indentificator, advert_promo_price_indentificator from adverts 
where id="'.mysql_real_escape_string($_SESSION["advert_id"]).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
	if($num_rows > 0){
	$row=mysql_fetch_row($result);
	
	$target_url = urldecode($_REQUEST['url']);
	$html = get_headers($target_url);
	
	if($html[0] == 'HTTP/1.1 200 OK'){

	$html = new simple_html_dom();
	$html->load_file($target_url);
	
	//var_dump($html);
	$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";
	//title
	if(!empty($row[0])){
	echo eval($row[0]);
	//echo $row[0];
	//$title = trim($title);
	//$title = preg_replace($pattern," ", htmlspecialchars_decode($title));
	$title = preg_replace ("/[^\p{L}\p{N} <>&\s\/\,:.!?-]/u", '', htmlspecialchars_decode($title));

	
	$data[0] = $title;
	}else{
	$error.= 'Не можах да взема заглавието на продукта<br/>';
	$data[0] = 'Няма въведен идентификатор за заглавието на продукта<br/>';
	}
		
	//description
	if(!empty($row[1])){
	echo eval($row[1]); 
	$description = str_replace("&nbsp;", " ", $description);
	$description = str_replace("&ndash;", "-", $description);
	$description = preg_replace ("/[^\p{L}\p{N} <>&\s\/\,:.!?-]/u", '', htmlspecialchars_decode($description));
	//array_push($data,$description);
	if(!empty($description)) $data[1] = $description;
	else $data[1] = 'Не можах да взема описанието на продукта';
	}else{
	$error.= 'Не можах да взема описанието на продукта<br/>';
	$data[1] = 'Няма въведен идентификатор за описанието на продукта<br/>';
	}
	
	//price
	if(!empty($row[2])){
	echo eval($row[2]);
	//var_dump($price);
	$price= strip_tags(trim($price));
	//$price=str_replace(array('Цена','цена','ЦЕНА'),'',$price);
	//$price=preg_replace('/[^,.0-9лвЛВ]/s', '', trim($price));
	$price=preg_replace("/[^0-9.]/", '', str_replace(array('лв.','ЛВ.','лв.','ЛВ.','Лв.'),'',$price));
	//array_push($data,$price);
	if(!empty($price)) $data[2] = number_format($price,2, '.', '');
	else $data[2]= 'Не можах да взема цената на продукта';
	}else{
	$error.= 'Не можах да взема цената на продукта<br/>';
	$data[2] = 'Няма въведен идентификатор за цената на продукта<br/>';
	}
		

	//snimka
	if(!empty($row[3])){
	echo eval($row[3]);
	//echo count($image);
	//foreach($html->find('div[class=blog-details-thumb] > img') as $img) {
	$imagecounter = 0;
	//echo count($image);
	if(is_array($image)){
	foreach($image as $img) {
	$imagesrc = $img->src;
	if(!preg_match("@^https?://@", $imagesrc)){
	//ako ne zapochwa s http dobawqme saita
	$cleanrelative = str_replace(array('../','./','../../','../../../'),'',$imagesrc);
	$imagesrc= $row[4].'/'.$cleanrelative;
	}
	if(fileExists($imagesrc)){
	if($imagecounter < 4) $data[3][] = $imagesrc;
	//$_SESSION['temp_images'][] = $imagesrc;
	$imagecounter++;
		}
	}
	}else{
	$imagesrc = $image->src;
	if(!preg_match("@^https?://@", $imagesrc)){
	//ako ne zapochwa s http dobawqme saita
	$cleanrelative = str_replace(array('../','./','../../','../../../'),'',$imagesrc);
	$imagesrc= $row[4].'/'.$cleanrelative;
	}
	if(fileExists($imagesrc)){
	if($imagecounter < 4) $data[3][] = $imagesrc;
	//$_SESSION['temp_images'][] = $imagesrc;
	$imagecounter++;
		}
	}
	if(!empty($data[3])) $_SESSION['temp_images']=$data[3];
	else $data[3]='';
}else{
$error.= 'Не можах да взема снимки на продукта<br/>';
$data[3]='Няма въведен идентификатор за снимки';
}
	
	//$categoryname = $html->find('div.holder > div', 0);
	if(!empty($row[5])){
	echo eval($row[5]);
	//echo count($categoryname);
	$categoryname = preg_replace ("/[^\p{L}\p{N} <>&\s\/\,:.!?-]/u", '', htmlspecialchars_decode($categoryname));
	$data[4] = $categoryname;
	}else{
	$error.= 'Не можах да взема категорията на продукта<br/>';
	$data[4] = 'Няма въведен идентификатор за категорията на продукта';
	}
	
	//promo price
	if(!empty($row[6])){
	echo eval($row[6]);
	//var_dump($promo_price);
	$promo_price= strip_tags(trim($price));
	//$promo_price=str_replace(array('Цена','цена','ЦЕНА'),'',$promo_price);
	//$promo_price=preg_replace('/[^,.0-9лвЛВ]/s', '', trim($promo_price));
	$promo_price=preg_replace("/[^0-9.]/", '', str_replace(array('лв.','ЛВ.','лв.','ЛВ.','Лв.'),'',$promo_price));
	//array_push($data,$promo_price);
	if(!empty($promo_price)) $data[6] = number_format($promo_price,2, '.', '');
	else $data[6]= 'Не можах да взема промо цената на продукта';
	}else{
	$error.= 'Не можах да взема промоцената на продукта<br/>';
	$data[6] = 'Няма въведен идентификатор за промо цената на продукта<br/>';
	}
	
	//if (!copy($image, $newfile)){
	//	array_push($data,$lang['image_fail']);
	//	}
	
		}else $error.='Отсрещният сървър не отговаря или блокира сканирането !!!<br/>';
	}else $error = 'Не можах да намеря данни на търговеца';
	if(empty($error)) $error = $lang['load_s'];
	
	$data[8] = $error;
	//var_dump($data);
	//echo count($data);
	
	echo json_encode($data);
	exit();
		}else echo 'page error';exit();	
?>