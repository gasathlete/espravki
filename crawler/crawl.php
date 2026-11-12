<?php
require '../config.php';
require '../connect.php';

include_once('simple_html_dom.php');

function send_error($query,$url1,$url2,$error,$user_ip){
$message="error in ".$_SERVER["REQUEST_URI"]."\r\n".$_SERVER["PHP_SELF"]."\r\n<br />".$query."\r\n<br />".$error."\r\n<br />User:".$user_ip;
mail(official_mail_sender,"error in ".WebSite."",$message);
}

function create_url($s = ''){
  $c = mb_strtolower((trim($s)), 'UTF-8');
  $c = preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll}\w]/u', '-', $c);
  $c = str_replace(array('---','--'),'-',$c);
  $c = htmlentities(strip_tags($c), ENT_QUOTES, 'UTF-8');
  return rtrim($c,'-');
}

$category = 123;
$advert = 54;
$title = '';
$price = '';

$image = '';

/*$target_url = "http://www.localsearch.com.au/Categories/List_Y";
$html = get_headers($target_url);
var_dump($html[0]);
//print_r(get_headers($target_url));
if ($html[0] == 'HTTP/1.1 200 OK'){

    $opts = array(
      'http'=>array(
       'method'=>"GET",
       'header'=>"Accept-language: en\r\n" .
       "User-Agent:  TheCore.com (Windows; U; Windows NT 6.0; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6\r\n".
       "Cookie: foo=bar\r\n"
      )
     );
$context = stream_context_create($opts);
//stream_context_set_params($context, array('user_agent' => 'UserAgent/1.9'));
	 
$html = new simple_html_dom();
$html->load_file($target_url, 0, $context);

$html = new simple_html_dom();
$html->load_file($target_url);

foreach($html->find('div.row > ul.list-unstyled > li > a') as $link){
echo $links = $link->plaintext;echo '<br>';

$query='insert into products_categories set url="'.create_url($links).'",bg_category="'.mysql_real_escape_string($links).'",title="'.mysql_real_escape_string($links).'",meta_title="'.mysql_real_escape_string($links).'",keywords="'.mysql_real_escape_string($links).'",parent="0",visible="1"';echo '<br>';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

$newdirectory='../business-directory/'.create_url($links);echo '<br>';
mkdir($newdirectory, 0755);
copy('../business-directory/index-to-copy.php', $newdirectory.'/index.php');

//echo $link;echo '<br>';
//$newtarget_url = $link->href;echo '<br>';

}
*/


/*

$html = new simple_html_dom();
$html->load_file($target_url);
if ($html->get_headers == 200){
foreach($html->find('span.provider > a.r') as $link){
echo memory_get_usage()."->";
echo $newtarget_url = $link->href;echo '<br>';
$query='select id from products where product_url="'.$newtarget_url.'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);
if($num_rows < 1){

$newhtml = new simple_html_dom();
$newhtml->load_file($newtarget_url);

foreach($newhtml->find('div.ppricewraper') as $price){
$price=preg_replace("/[^0-9.]/", "",$price);
	}
if($price > 0){
foreach($newhtml->find('div.prohead > h1') as $title){
$title= $title->innertext;
	}
$desc = array();
foreach($newhtml->find('div.shortd > div > p') as $description){
$desc[]=$description;
	}

$description=htmlspecialchars(implode(" ",$desc));
$uniquename = create_url(trim($title));
echo $insert='insert into products set unique_name="'.$uniquename.'", bg_product_name="'.trim(htmlspecialchars($title)).'", bg_short_description="'.$description.'",product_price="'.$price.'",
available_qty="100",product_url="'.$newtarget_url.'"';
$insertresult=mysql_query($insert) or die(send_error($insert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$last_id=mysql_insert_id();echo '-aa<br>';

echo $q1='update products set unique_name="'.$last_id.'-'.$uniquename.'" where id="'.$last_id.'"';
$res=mysql_query($q1) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q1);

$counter = 0;
foreach($newhtml->find('div#imgw > img') as $image){
 $counter++;

$count = count($newhtml->find('div#imgw > img'));
if($count > 0){ 
$image=$image->src;
$newimagename=$last_id.'-'.$uniquename.'-'.$counter.'.jpg';


	if($counter == 1){
	$imagequery='insert into products_gallery set product_id="'.$last_id.'", image="'.$newimagename.'", type="small", lang="BG"';
	$imageresult=mysql_query($imagequery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$imagequery);
	
	$newfile = servImagesDir.'products/small/'.$newimagename;
	if (!copy($image, $newfile)){echo "failed to copy";}
	}
	$imagequery='insert into products_gallery set product_id="'.$last_id.'", image="'.$newimagename.'", type="big", lang="BG"';
	$imageresult=mysql_query($imagequery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$imagequery);
	
	$newfile = servImagesDir.'products/big/'.$newimagename;
	if (!copy($image, $newfile)){echo "failed to copy";}
		}
	}
	$advertquery='insert into products_to_adverts set product_id="'.$last_id.'", advert_id="'.$advert.'"';echo '<br>';
	$advertresult=mysql_query($advertquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$advertquery);
	
	$categoryquery='insert into products_to_categories set product_id="'.mysql_real_escape_string($last_id).'", category_id="'.$category.'"';echo '<br>';
	$categoryresult=mysql_query($categoryquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$categoryquery);
	
		}
	}
	}
}
$html->clear();
unset($html);
*/
}else echo 'ttttttaaaaaaaaaammmmmmmmmmmmm';



/*
$site='http://www.weddingburg.com/wedding/';
$target_url = "http://www.weddingburg.com/wedding/index.php?offers=27";
$html = new simple_html_dom();
$html->load_file($target_url);


foreach($html->find('div.page-menu > span > a') as $link){

$newtarget_url = $link->href;echo '<br>';
echo $newtarget_url = $site.$newtarget_url;

$html1 = new simple_html_dom();
$html1->load_file($target_url);


foreach($html1->find('a.img') as $link1){
//$link->href."<br />";
echo $newtarget_url1 = $link1->href;echo '<br>';

//tuk zamestwame relativno URL s absolutno
$newtarget_url=str_replace('./',$site,$newtarget_url);

$newhtml = new simple_html_dom();
$newhtml->load_file($newtarget_url);


foreach($newhtml->find('div.prohead > h1') as $newlink){
//echo $newlink."<br />";
}
foreach($newhtml->find('div.shortd > p') as $newlink){
//echo $newlink."<br />";
}
foreach($newhtml->find('span.new_price') as $newlink){
//echo $newlink."<br />";

}
foreach($newhtml->find('div#imgw > img') as $newlink){
//echo $newlink->src."<br />";
}

}

}
*/
?>