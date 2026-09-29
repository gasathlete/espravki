<?php
require_once '../config.php';

mysql_connect($db_host,$db_user,$db_pass,$db_name);
mysql_select_db($db_name);
mysql_set_charset('utf8');


/*
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
*/


function check_sent($email){
$query = 'Select id from mail_campaigns where email="'.$email.'" and date(date) = CURDATE()';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0) return 0;
	else return 1;

}
function get_customer_mails(){
	$html='<div id="investors_checkboxes" class="form-group tac">
	<p class="ib w100 mtb10 tac cb">Изберете клиент от списъка<small> макс 10 наведнъж !</small></p>
	<hr/>
	<div class="form-group tal ncc">';
	
	$query = 'Select id, customer_names, customer_email from customers where active="1"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	if($num_rows > 0){

	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$check_sent = check_sent($row[2]);
		if($check_sent > 0){
		$html.='<div class="form-group ib w100 mtb5"><input class="inv mlr5" id="'.$row[2].'" type="checkbox" name="investor[]"  value="'.$row[2].'" /><label class="ib wa" for="'.$row[2].'">'.$row[1].' <small>'.$row[2].'</small></label></div>';
			}
		}
	}
	$html.='</div></div>';
	return $html;
}

function searchWords($string,$words){
    foreach($words as $word){
        if(stristr($string, $word)) //spaces either side to force a word
        {
            return true;
        }
    }
    return false;
}

function create_url($s = ''){
  $c = mb_strtolower((trim($s)), 'UTF-8');
  $c = preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll}\w]/u', '-', $c); 
   $c = str_replace('---', '-', $c);$c = str_replace('--', '-', $c);
  $c = htmlentities(strip_tags($c), ENT_QUOTES, 'UTF-8');
  return rtrim($c,'-');
}

function delete_dir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                delete_dir($src . '/' . $file); 
            } 
            else { 
                unlink($src . '/' . $file); 
            } 
        } 
    } 
    rmdir($src);
    closedir($dir); 
}

function translate2($str){
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

function translate($str){ 
    $tr = array( 
	"А"=>"a", "Б"=>"b", "В"=>"v", "Г"=>"g", "Д"=>"d", 
    "Е"=>"e", "Ё"=>"yo", "Ж"=>"zh", "З"=>"z", "И"=>"i",  
    "Й"=>"j", "К"=>"k", "Л"=>"l", "М"=>"m", "Н"=>"n",  
    "О"=>"o", "П"=>"p", "Р"=>"r", "С"=>"s", "Т"=>"t",  
    "У"=>"u", "Ф"=>"f", "Х"=>"kh", "Ц"=>"tz", "Ч"=>"ch",  
    "Ш"=>"sh", "Щ"=>"sch", "Ъ"=>"a", "Ы"=>"y", "Ь"=>"",  
    "Э"=>"e", "Ю"=>"yu", "Я"=>"ya", "а"=>"a", "б"=>"b",  
    "в"=>"v", "г"=>"g", "д"=>"d", "е"=>"e", "ё"=>"yo",  
    "ж"=>"zh", "з"=>"z", "и"=>"i", "й"=>"j", "к"=>"k",  
    "л"=>"l", "м"=>"m", "н"=>"n", "о"=>"o", "п"=>"p",  
    "р"=>"r", "с"=>"s", "т"=>"t", "у"=>"u", "ф"=>"f",  
    "х"=>"kh", "ц"=>"tz", "ч"=>"ch", "ш"=>"sh", "щ"=>"sch",  
    "ъ"=>"a", "ы"=>"y", "ь"=>"", "э"=>"e", "ю"=>"yu",  
    "я"=>"ya", "."=>"", ","=>"", "/"=>"", "\\"=>"", "'"=>"",
    ":"=>"", ";"=>"", "—"=>"", "_"=>"", "+"=>"", "|"=>"", "\""=>"",
	"%"=>"", "&"=>"", "#"=>"", "@"=>"", "("=>"", ")"=>"",
	"!"=>"", "$"=>"", "^"=>"", "amp"=>"", "*"=>"", "?"=>"",
	"<"=>"", ">"=>"", "№"=>"", "€"=>"", "§"=>"", "="=>"-", " "=>"-", "------"=>"-", "-----"=>"-", "----"=>"-", "---"=>"-", "--"=>"-", "--"=>"-"
    ); 
return strtr($str,$tr); 
}
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-zА-Яа-я0-9\-]/', '', $string); // Removes special chars.
}

function sanitize_post_get($str){
//blokirane na opiti za hak prez url-to
if(is_array($str)){
$str=array_map('trim',$str);
}else $str = trim($str);//echo '<br/>';
$urltocheck=$_SERVER['REQUEST_URI'];
    $str = str_replace(array('"','„','`','“','(',')'),"'", $str);
	
return $str; 
}

function clear_product($str){ 
    $tr = array( 
	"\\"=>"", "'"=>"", ";"=>"", "_"=>"", "\""=>"", 
	"@"=>"", "("=>"", ")"=>"",	"!"=>"", "$"=>"", "^"=>"", "&amp"=>"", "*"=>"", "?"=>"",
	"<"=>"", ">"=>"", "§"=>"", "="=>"-", "  "=>" "
    ); 
return strtr($str,$tr); 
}	
function is_file_uploaded($f){
	global $lang;
	$msg[0]=0;
	$msg[1]=$lang['success'];
	$msg[2]=0;
	//echo $f['type'],'<br />';
	if($f['type']=='image/jpeg' || $f['type']=='image/pjpeg' || $f['type']=='image/jpg' || $f['type']=='image/JPEG' || $f['type']=='image/png' || $f['type']=='image/x-png'){
		if($f['error']>0){
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

function copy_and_resize_image_lang_big($f,$save_file_name,$aid=0,$langsign,$path){
	// Set a maximum height and width
	$big_width=700;
	$big_height=700;
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
	$image_p = imagecreatetruecolor($big_width, $big_height);
	switch($f['type']){
		case 'image/jpg': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/JPEG': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/jpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/png': $image = imagecreatefrompng($f['tmp_name']);break;
		case 'image/pjpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/x-png': $image = imagecreatefrompng($f['tmp_name']);break;
	}
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $big_width, $big_height, $width_orig, $height_orig);
	// Output
	imagejpeg($image_p, $path."/big/".$save_file_name);
	//echo servImagesDir."/products/big/".$save_file_name;
	imagedestroy($image_p);
	
	$image_p= imagecreatetruecolor($small_width, $small_height);
}
function copy_and_resize_advert_image($f,$save_file_name,$aid=0){
	// Set a maximum height and width
	$big_width=700;
	$big_height=700;	
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
	switch($f['type']){
		case 'image/jpg': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/JPEG': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/jpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/png': $image = imagecreatefrompng($f['tmp_name']);break;
		case 'image/pjpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/x-png': $image = imagecreatefrompng($f['tmp_name']);break;
	}
	$image_p = imagecreatetruecolor($big_width, $big_height);
	imagealphablending( $image_p, false );
	imagesavealpha( $image_p, true );
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $big_width, $big_height, $width_orig, $height_orig);
	// Output
		
			$langsign = 'bg';
			$path = servShopHome.ImagesSubDir."adverts";
			
			$filename2 = $path."/".create_url($save_file_name).'.png';
			imagepng($image_p, $path."/".$save_file_name, 9);

	//imagejpeg($image_p, servImagesDir."/adverts/".$save_file_name);
	imagedestroy($image_p);
}
//http://demo.templatic.com/listings/
function copy_and_resize_article_image_old($f,$save_file_name,$aid=0){
	// Set a maximum height and width
	$big_width=550;
	$big_height=550;
	$small_width = 250;
	$small_height = 250;
	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($f['tmp_name']);
	$ratio_orig = $width_orig/$height_orig;
	if ($big_width/$big_height > $ratio_orig) $big_width = $big_height*$ratio_orig;
	else $big_height = $big_width/$ratio_orig;
	if ($small_width/$small_height > $ratio_orig) $small_width = $small_height*$ratio_orig;
	else $small_height = $small_width/$ratio_orig;
	
	// Resample
	switch($f['type']){
		case 'image/jpg': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/JPEG': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/jpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/png': $image = imagecreatefrompng($f['tmp_name']);break;
		case 'image/pjpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/x-png': $image = imagecreatefrompng($f['tmp_name']);break;
	}

	$image_p = imagecreatetruecolor($big_width, $big_height);
	imagealphablending( $image_p, false );
	imagesavealpha( $image_p, true );
	
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $big_width, $big_height, $width_orig, $height_orig);
	// Output
			$langsign = 'bg';
			$path = servShopHome.ImagesSubDir."articles";
			
			$filename2 = $path."/".create_url(htmlspecialchars_decode($_POST['link_name'])).'.jpg';
			//imagepng($image_p, $path."/".$save_file_name, 9);
			imagejpeg($image_p, $path."/".$save_file_name,85);

			imagedestroy($image_p);
	
}


function copy_and_resize_article_image($f,$save_file_name,$aid=0){
	// Set a maximum height and width
	$big_width=1000;
	$big_height=1000;
	$small_width = 250;
	$small_height = 250;
	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($f['tmp_name']);
	$ratio_orig = $width_orig/$height_orig;
	if ($big_width/$big_height > $ratio_orig) $big_width = $big_height*$ratio_orig;
	else $big_height = $big_width/$ratio_orig;
	if ($small_width/$small_height > $ratio_orig) $small_width = $small_height*$ratio_orig;
	else $small_height = $small_width/$ratio_orig;
	
	// Resample
	switch($f['type']){
		case 'image/jpg': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/JPEG': $image = imagecreatefromjpg($f['tmp_name']);break;
		case 'image/jpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/png': $image = imagecreatefrompng($f['tmp_name']);break;
		case 'image/pjpeg': $image = imagecreatefromjpeg($f['tmp_name']);break;
		case 'image/x-png': $image = imagecreatefrompng($f['tmp_name']);break;
	}

	$image_p = imagecreatetruecolor($big_width, $big_height);
	imagealphablending( $image_p, false );
	imagesavealpha( $image_p, true );
	
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $big_width, $big_height, $width_orig, $height_orig);

			$path = '../'.ImagesSubDir."articles";
			
			//$filename2 = $path."/".create_url(htmlspecialchars_decode($_POST['en_alt'])).'.jpg';
			$filename2 = $path."/".create_url(htmlspecialchars_decode($_POST['link_name'])).'.jpg';
			//imagepng($image_p, $path."/".$save_file_name, 9);
			imagejpeg($image_p, $path."/".$save_file_name,85);

			imagedestroy($image_p);
	
}

function gallery_upload($gallery_upload,$aid,$imgid,$langsign,$postVal){
	if($langsign=="bg"){ $path = servShopHome.ImagesSubDir."products";
	}else{ $path = "/www/sxy.bg/".$langsign."/root/images/products";}
	if($imgid =="1"){
	copy_and_resize_image_lang($gallery_upload,create_url(rtrim($postVal,' ')).'-'.$aid.'-'.$imgid.'.jpg', intval($_POST['advert']),$langsign,$path);	
	}
	else{
	copy_and_resize_image_lang_big($gallery_upload,create_url(rtrim($postVal,' ')).'-'.$aid.'-'.$imgid.'.jpg', intval($_POST['advert']),$langsign,$path);	
	}
	
}


function save_advert($aid=0){
	global $lang;

	//var_dump($_POST);
	//return messages and errors
	$msg[0]=0;
	$msg[1]=$lang['success'];
	$msg[2]=0;//if $msg[0]==0 then here we have product id;
		
	if(empty($_POST['supplier_name']) || empty($_POST['bg_short_description'])){
		$msg[0]=1;
		$msg[1]=$lang['enter_fields'];
		return $msg;
	}
	if(!empty($aid)){
		return update_advert($aid);
	}
	
	if(empty($_POST['link_name'])) $_POST['link_name']= create_url($_POST['supplier_name']);
	//var_dump($_POST['link_name']);
	//exit();
	if(empty($aid)){
	$query='select id from adverts where link_name="'.create_url($_POST['link_name']).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	if(mysql_num_rows($result)>0){
		$msg[0]=1;
		$msg[1]=$lang['wrong_uname'];
		return $msg;
		}
		elseif(!isset($_POST['categories'])){
		$msg[0]=1;
		$msg[1]='Моля добавете категория !';
		return $msg;
		}
		else{
		if(!empty($_POST['active'])) $active="1";else $active="0";
		if(!empty($_POST['vip'])) $vip="1";else $vip="0";
		if(!empty($_POST['affiliate'])) $affiliate="1";else $affiliate="0";
		
		$linkname=create_url($_POST['link_name']);
		$query='insert into adverts set 
		supplier_name="'.mysql_real_escape_string (htmlspecialchars($_POST['supplier_name'])).'",
		description="'.mysql_real_escape_string (htmlspecialchars($_POST['bg_short_description'])).'",
		meta_title="'.mysql_real_escape_string (htmlspecialchars($_POST['meta_title'])).'",
		meta_keywords="'.mysql_real_escape_string(htmlspecialchars($_POST['meta_keywords'])).'",
		meta_description="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_meta_description'])).'",
		link_name="'.mysql_real_escape_string($linkname).'",
		postcode="'.mysql_real_escape_string($_POST['postcode']).'",
		latitude="'.mysql_real_escape_string($_POST['latitude']).'",
		longitude="'.mysql_real_escape_string($_POST['longitude']).'",
		affiliate="'.mysql_real_escape_string($affiliate).'",
		commission="'.mysql_real_escape_string($_POST['commission']).'",
		town="'.mysql_real_escape_string($_POST['town']).'",
		area="'.mysql_real_escape_string($_POST['area']).'",
		company_address="'.mysql_real_escape_string($_POST['address']).'",
		country="Bulgaria",country_code="BG",
		company_phones="'.mysql_real_escape_string($_POST['phones']).'",
		mail="'.mysql_real_escape_string($_POST['mail']).'",
		site="'.mysql_real_escape_string($_POST['site']).'",
		expired_date="'.mysql_real_escape_string($_POST['expired_date']).'",
		active="'.mysql_real_escape_string($active).'",
		facebook="'.mysql_real_escape_string($_POST['facebook']).'",
		twitter="'.mysql_real_escape_string($_POST['twitter']).'",
		google="'.mysql_real_escape_string($_POST['google']).'",
		pinterest="'.mysql_real_escape_string($_POST['pinterest']).'",
		advert_title_indentificator="'.mysql_real_escape_string($_POST['intitle']).'",
		advert_desc_indentificator="'.mysql_real_escape_string($_POST['indesc']).'",
		advert_price_indentificator="'.mysql_real_escape_string($_POST['inprice']).'",
		advert_img_indentificator="'.mysql_real_escape_string($_POST['inimg']).'",
		advert_category_indentificator="'.mysql_real_escape_string($_POST['incatname']).'",
		max_offers="'.$_POST['max_offers'].'",
		vip="'.mysql_real_escape_string($vip).'"';
		if(!empty($_POST['pass'])){
		$query.=', pass="'.md5($_POST['pass']).'"';
		}	
		
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$last_id=mysql_insert_id();
		
		if(isset($_POST['categories'])){
		foreach($_POST['categories'] as $check) {
		$query='insert into adverts_to_product_categories set advert_id="'.$last_id.'", category_id="'.$check.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}
	}
		if(isset($_POST['articletoadvert'])){
		$query="select id from articles_to_adverts where advert_id='".$aid."' and article_id='".intval($_POST['articletoadvert'])."'";
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		if($num_rows < 1){
		$query="delete from articles_to_adverts where advert_id='".$aid."'";
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		if(!empty($_POST['articletoadvert'])){
		$query='insert into articles_to_adverts set advert_id="'.$aid.'", article_id="'.intval($_POST['articletoadvert']).'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
			}
		}
	}	

		$msg[2]=$last_id;
		}
	}
	return $msg;
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
		$c=$content[3];
	}
	
	return $c;
}

function select_get_countries(){
	$c=''; $html='<select name="country">';
	if(!empty($_POST['country'])) $c=addslashes(strtoupper(strip_tags(trim($_POST['country']))));
	if(!empty($_POST['country'])){
		$query='select country_code from countries where (id="'.$_POST['country'].'" or country_name="'.$_POST['country'].'")';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		if($num_rows > 0){
		$row=mysql_fetch_row($result);
		$c=$_POST['country']=$row[0];
			}
		}
	
	if(empty($c)){
		//$c=detect_country_fast($_SERVER['REMOTE_ADDR']);
		if($c=='-') $c='';
	}
	$selected='';
	if(empty($c)) $selected='selected="selected"';
	$html.='<option '.$selected.' value="0">please select</option>';
	
	$query='select id, country_code,country_name from countries';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$selected='';
		if($c==$row[1]) $selected='selected="selected"';
		$html.='<option '.$selected.' value="'.$row[0].'">'.$row[2].'</option>';
	}
	$html.='</select>';
	return $html;
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
</div>
	<div align="center" style="background:#555;color:#ffffff;margin:0;padding:5px;height:30px;line-height:30px;text-align:center;">©Copyright '.date('Y').'"&nbsp;&nbsp;<a href="'.WebSite.'" target="_blank" style="color:#fff;text-decoration:none;">'.official_site_name.'</a> - All Rights Reserved</div>
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

function update_advert($aid){
	global $lang;
	//var_dump($_POST);
	//return messages and errors
	$msg[0]=0;
	$msg[1]=$lang['success'];
	$msg[2]=0;//if $msg[0]==0 then here we have product id;
	
	if(isset($_POST['del_prd'])){
	foreach($_POST['del_prd'] as $product){
	$delquery='delete from products where id="'.$product.'"';//echo '<br>';
	mysql_query($delquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$delquery);
		}	
	}
	
	if(isset($_POST['delete_image'])){
	foreach($_POST['delete_image'] as $product){
	$q='select image from products where id="'.$product.'"';
	$cresult=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
	$row=mysql_fetch_row($cresult);
	if(!empty($row[0])){
	$path = servShopHome.ImagesSubDir."products";
	$file=$path."/".$row[0];
	if(file_exists($file)){
	unlink($file);
	}else echo $file.'can not be deleted !';
	
	$delpiquery='update products set image="" where id="'.$product.'"';//echo '<br>';
	mysql_query($delpiquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$delpiquery);
			}
		}		
	}

if(!empty($_POST['dimage'])){
	foreach($_POST['dimage'] as $i){
	//echo $i;//=substr($i, -9, 9);
		$query='select image,order_id from adverts_gallery where id="'.mysql_real_escape_string($i).'" and advert_id="'.$aid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		for($j=0;$j<$num_rows;$j++){
			$row=mysql_fetch_row($result);
			$path = servShopHome.ImagesSubDir."adverts";
			$file=$path."/".$row[0].".jpg";
			if(file_exists($file)){
			unlink($file);
			}else echo $file.'can not be deleted !';
		$query='delete from adverts_gallery where id="'.$i.'" and advert_id="'.$aid.'"';
		mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}
	}
}
	
	
	if(!empty($_POST['link_name'])){
		$query='select id from adverts where link_name="'.$_POST['link_name'].'" and id!="'.$aid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		if(mysql_num_rows($result)>0){
			$msg[0]=2;
			$msg[1]=$lang['wrong_uname'];
			return $msg;
		}
	}
	else{
		$msg[0]=2;
		$msg[1]=$lang['no_uname'];
		return $msg;
	}
	
	$imagegid='';
	if(isset($_FILES)){
		foreach($_FILES as $imags => $imags){
		$imagegid=substr($imags, -1);
		//exit();
		if(isset($_FILES['gallery_upload'.$imagegid.'']) && !empty($_FILES['gallery_upload'.$imagegid.'']['name'])){
			$msg=is_file_uploaded($_FILES['gallery_upload'.$imagegid.'']);
			if($msg[0]>0) return $msg;
			}
		}
	}
		
	if(!empty($_POST['active'])) $active="1";else $active="0";
	if(!empty($_POST['affiliate'])) $affiliate="1";else $affiliate="0";
	if(!empty($_POST['vip'])) $vip="1";else $vip="0";
	if(!empty($_POST['googleplusprogram'])) $googleplusprogram="1";else $googleplusprogram="0";
	if(!empty($_POST['sellbusiness'])) $sellbusiness="1";else $sellbusiness="0";
	$linkname=create_url($_POST['link_name']);
	$query='update adverts set 
	supplier_name="'.mysql_real_escape_string(htmlspecialchars($_POST['supplier_name'])).'",
	description="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_short_description'])).'",
	meta_title="'.mysql_real_escape_string(htmlspecialchars($_POST['meta_title'])).'",
	meta_keywords="'.mysql_real_escape_string(htmlspecialchars($_POST['meta_keywords'])).'",
	meta_description="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_meta_description'])).'",
	link_name="'.mysql_real_escape_string($linkname).'",
	postcode="'.mysql_real_escape_string($_POST['postcode']).'",
	latitude="'.mysql_real_escape_string($_POST['latitude']).'",
	longitude="'.mysql_real_escape_string($_POST['longitude']).'",
	affiliate="'.mysql_real_escape_string($affiliate).'",
	commission="'.mysql_real_escape_string($_POST['commission']).'",
	town="'.mysql_real_escape_string($_POST['town']).'",
	area="'.mysql_real_escape_string($_POST['area']).'",
	company_address="'.mysql_real_escape_string($_POST['address']).'",
	company_phones="'.mysql_real_escape_string($_POST['phones']).'",
	mail="'.mysql_real_escape_string($_POST['mail']).'",
	site="'.mysql_real_escape_string($_POST['site']).'",
	expired_date="'.mysql_real_escape_string($_POST['expired_date']).'",
	active="'.mysql_real_escape_string($active).'",
	facebook="'.mysql_real_escape_string($_POST['facebook']).'",
	twitter="'.mysql_real_escape_string($_POST['twitter']).'",
	google="'.mysql_real_escape_string($_POST['google']).'",
	pinterest="'.mysql_real_escape_string($_POST['pinterest']).'",
	business_type="'.mysql_real_escape_string($_POST['business_type']).'",
	googleplusprogram="'.mysql_real_escape_string($googleplusprogram).'",
	sellbusiness="'.mysql_real_escape_string($sellbusiness).'",
	business_price="'.mysql_real_escape_string($_POST['business_price']).'",
	sell_description="'.mysql_real_escape_string($_POST['sell_description']).'",
	advert_title_indentificator="'.mysql_real_escape_string($_POST['intitle']).'",
	advert_desc_indentificator="'.mysql_real_escape_string($_POST['indesc']).'",
	advert_price_indentificator="'.mysql_real_escape_string($_POST['inprice']).'",
	advert_promo_price_indentificator="'.mysql_real_escape_string($_POST['promo_price']).'",
	advert_img_indentificator="'.mysql_real_escape_string($_POST['inimg']).'",
	advert_category_indentificator="'.mysql_real_escape_string($_POST['incatname']).'",
	max_offers="'.$_POST['max_offers'].'",
	vip="'.mysql_real_escape_string($vip).'"';
		if(!empty($_POST['pass'])){
		$query.=', pass="'.md5($_POST['pass']).'"';
		}
		$query.=' where id="'.mysql_real_escape_string($_POST['advert']).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error($query).'<br /> Query:'.$query);

	if(isset($_POST['categories'])){
	$query="delete from adverts_to_product_categories where advert_id='".$aid."'";
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	foreach($_POST['categories'] as $check) {
	$query='insert into adverts_to_product_categories set advert_id="'.$aid.'", category_id="'.$check.'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	}
}

if(isset($_POST['articletoadvert'])){
	$query="select id from articles_to_adverts where advert_id='".$aid."' and article_id='".intval($_POST['articletoadvert'])."'";
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	if($num_rows < 1){
	$query="delete from articles_to_adverts where advert_id='".$aid."'";
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	if(!empty($_POST['articletoadvert'])){
	$query='insert into articles_to_adverts set advert_id="'.$aid.'", article_id="'.intval($_POST['articletoadvert']).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}
	}
}

//gallery
$imagegid='';
	if(!empty($_FILES)){

	foreach($_FILES as $imags => $imags){
		$file = explode('_', $imags);
		$imagegid = $file[2];

	if(isset($_FILES['gallery_upload_'.$imagegid.'']) && !empty($_FILES['gallery_upload_'.$imagegid.'']['name'])){
		$query='select image,order_id,id from adverts_gallery where advert_id="'.$aid.'" and order_id="'.$imagegid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		for($i=0;$i<$num_rows;$i++){
			$row=mysql_fetch_row($result);
			@ unlink(servImagesDir."adverts/".$row[0]);
			$query='delete from adverts_gallery where id="'.$row[2].'"';
			mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}

		copy_and_resize_image_lang($_FILES['gallery_upload_'.$imagegid.''],$_POST['link_name'].'-'.$imagegid.'.jpg',$imagegid);	

	$query='insert into adverts_gallery set advert_id="'.$aid.'", image="'.$_POST['link_name'].'-'.$imagegid.'", order_id="'.$imagegid.'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);

	$query2='update adverts set small_image="'.$_POST['link_name'].'-'.$imagegid.'.jpg" where id="'.$aid.'"';
	$result2=mysql_query($query2) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query2);
		}
		
	if(isset($_FILES['p_gallery_upload']) && !empty($_FILES['p_gallery_upload']['name'])){
	foreach($_FILES['p_gallery_upload']['name'] as $keys=>$image){
		$imgquery='update products set image ="'.$image.'" where id="'.$keys.'" and advert_id="'.$aid.'"';
		mysql_query($imgquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$imgquery);
		copy_and_resize_image_product($_FILES['p_gallery_upload'],$image,$keys);
			}
		}
	}
}

	if(!empty($_POST['prid'])){
	foreach($_POST['prid'] as $key=>$prid){
	//echo $key.'<br>';
	$updprquery='update products set title="'.$_POST['prd_title'][$key].'",description="'.$_POST['prd_description'][$key].'",price="'.$_POST['prd_price'][$key].'",
	redirect_url="'.$_POST['prd_link'][$key].'" where id="'.$prid.'"';//echo '<br>';
	mysql_query($updprquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$updprquery);
		}	
	}
	
	$msg[2]=$aid;
	return $msg;
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

function mb_ucfirst($string, $encoding){
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
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

function add_product_category_new($id,$level=0,$parent){
global $lang;
   $query = "SELECT bg_category, id, visible,top_category FROM shop_products_categories where parent='".$id."' order by id=38, bg_category ASC";
    $res = mysql_query($query) or die($query);
 	
   if(mysql_num_rows($res) > 0){
    while (list ($bg_category, $id,$visible,$top_category) = mysql_fetch_row($res)){
	$selected = '';
	$que = 'select id from shop_products_categories where parent="'.$id.'"';
		$resu = mysql_query($que) or die($que);
		$ro=mysql_fetch_row($resu);
		if(isset($_POST['category_id']) && ($_POST['category_id'] == $id)) $selected = 'selected="selected"';

        if ($level==0){
		
		if($ro[0]>0) {
		echo '<option class="m',$level,' aaa" disabled '.$selected.' value="'.$id.'">'.$bg_category.'</option>';
		/* echo '<tr><td colspan="2"><a href="index.php?page=shop_categories&cid=',$id,'&edit=1">'.$bg_category.'</a></td><td>няма</td>';
		 if($visible==0){$v1='<img src="'.webImagesDir.'interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/desactivate.png" />'; $v2=0;}
		
		if($top_category==0){
		$top='Не';
		$top1='1';
		}else{
		$top='Да';
		$top1='0';
		}
        echo '<td><a href="index.php?page=shop_categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td>
		<td align="center"><a href="index.php?page=shop_categories&cid=',$id,'&top_category='.$top.'">',$top,'</td></tr>';*/
		
		}else{
		echo '<option class="m',$level,' bbb" '.$selected.' value="'.$id.'">'.$bg_category.'</option>';		
          /* echo '<tr>
		   <td colspan="2"><input type="checkbox" name="category_id[]" value="',$id,'" /><a href="index.php?page=shop_categories&cid=',$id,'&edit=1">'.$bg_category.'</a></td><td>няма</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/desactivate.png" />'; $v2=0;}
		if($top_category==0){
		$top='Не';
		$top1='1';
		}else{
		$top='Да';
		$top1='0';
		}
        echo '<td><a href="index.php?page=shop_categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td>
		<td align="center"><a href="index.php?page=shop_categories&cid=',$id,'&top_category='.$top1.'">',$top,'</td></tr>';*/
		}
		
		}
        else{
		if($ro[0]>0){
		echo '<option class="m',$level,' ccc" disabled '.$selected.' value="'.$id.'">'.$bg_category.'</option>';	
         /*  echo '<tr>
		   <td colspan="2"><span class="subcat" style="margin-left:',($level*2),'0px;"><input type="checkbox" level="',$level,'" name="category_id[]" value="',$id,'" /><a style="color:#555;" href="index.php?page=shop_categories&cid=',$id,'&edit=1">',$bg_category,'</a></span></td><td>',$parent,'</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/desactivate.png" />'; $v2=0;}
		if($top_category==0){
		$top='Не';
		$top1='1';
		}else{
		$top='Да';
		$top1='0';
		}
        echo '<td><a href="index.php?page=shop_categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td>
		<td align="center"><a href="index.php?page=shop_categories&cid=',$id,'&top_category='.$top1.'">',$top,'</td></tr>';*/
			}
		else{
		echo '<option class="m',$level,' ddd" '.$selected.' value="'.$id.'">'.$bg_category.'</option>';	
		/* echo '<tr>
		   <td colspan="2"><span class="subcat" style="margin-left:',($level*2),'0px;"><input type="checkbox" level="',$level,'" name="category_id[]" value="',$id,'" /><a style="color:#555;" href="index.php?page=shop_categories&cid=',$id,'&edit=1">',$bg_category,'</a></span></td><td>',$parent,'</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/desactivate.png" />'; $v2=0;}
		if($top_category==0){
		$top='Не';
		$top1='1';
		}else{
		$top='Да';
		$top1='0';
		}
        echo '<td><a href="index.php?page=shop_categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td>
		<td align="center"><a href="index.php?page=shop_categories&cid=',$id,'&top_category='.$top1.'">',$top,'</td></tr>';*/
			}
		}
        add_product_category_new($id,$level+1,$bg_category);
		}
	}else{
	if ($level==0) echo '<option '.$selected.' value="">Все още няма добавени категории</option>';	
	//echo '<tr><td style="text-align:center;" colspan="4">Все още няма добавени категории</td></tr>';
	}
	

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

function count_product_orders($pid){
$counter = 0;
$query="SELECT count(id) as counter FROM orders WHERE pid='".mysql_real_escape_string($pid)."' GROUP BY `pid`";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$row=mysql_fetch_assoc($result);
	if(!empty($row['counter'])) $counter = $row['counter'];
	return $counter;
}

function count_adverts($cid){
$counter = 0;
$query="SELECT count(id) as counter FROM adverts WHERE customer_id='".mysql_real_escape_string($cid)."'";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$row=mysql_fetch_assoc($result);
	if(!empty($row['counter'])) $counter = $row['counter'];
	return $counter;
}


function count_products($cid){
$counter = 0;
$query="SELECT count(id) as counter FROM products WHERE cid='".mysql_real_escape_string($cid)."'";
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$row=mysql_fetch_assoc($result);
	if(!empty($row['counter'])) $counter = $row['counter'];
	return $counter;
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
			<h3 class="card-title pt-2 mb-2">Оферти от ',$sup_name,'</h3>
			<small class="ib w100">Търговеца има право на <input type="text" class="short" name="max_offers" value="',$max_offers,'"/> ',$lang['of_pr'],'</small>
			</div>
			<div class="ib w48 tar mobw100">',$num_rows,' / ',$max_offers,' ',$lang['of_pr'],'</div>';
			
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
				<a target="_blank" href="index.php?page=edit_product&pid=',$row['id'],'" class="btn btn-success btn-sm text-white" data-toggle="tooltip" data-original-title="',$lang['edit'],'"><i class="fa fa-pencil"></i></a>
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
	
	}else echo '<div class="form-group mt-5 tac"><label class="form-label text-dark">Няма въведени продукти</label></div>';
	
	
	echo '</div></div>';
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

function select_business_type($type){
	global $lang;
	$html='<select id="f50" class="form-control" name="business_type">';

	$selected='selected="selected"';
	if($type == '0') $html.='<option '.$selected.' value="0">'.$lang['select_type']['0'].'</option>';else $html.='<option value="0">'.$lang['select_type']['0'].'</option>';
	if($type == '1') $html.='<option '.$selected.' value="1">'.$lang['select_type']['1'].'</option>';else $html.='<option value="1">'.$lang['select_type']['1'].'</option>';
	if($type == '2') $html.='<option '.$selected.' value="2">'.$lang['select_type']['2'].'</option>';else $html.='<option value="2">'.$lang['select_type']['2'].'</option>';
	if($type == '3') $html.='<option '.$selected.' value="3">'.$lang['select_type']['3'].'</option>';else $html.='<option value="3">'.$lang['select_type']['3'].'</option>';
	if($type == '4') $html.='<option '.$selected.' value="4">'.$lang['select_type']['4'].'</option>';else $html.='<option value="4">'.$lang['select_type']['4'].'</option>';
	
	$html.='</select>';
	return $html;
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

function filter_advert_categories($id,$level=0,$parent,$aid){
global $lang;
//$categories_list = array();
$categories = 'select atpc.category_id from adverts_to_product_categories atpc where advert_id="'.$aid.'" group by atpc.category_id';
$categoriesresult=mysql_query($categories) or die(send_error($categories,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$categories_num_rows=mysql_num_rows($categoriesresult);
if($categories_num_rows){
	while($row = mysql_fetch_assoc($categoriesresult)){
		$categories_list[] = $row['category_id'];
		}
	}
	
	$query = "SELECT bg_category, id, visible,top_category FROM products_categories where parent='".mysql_real_escape_string($id)."' order by bg_category ASC";
	$res=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
 	
   if(mysql_num_rows($res) > 0){
    while (list ($bg_category, $id,$visible,$top_category) = mysql_fetch_row($res)){
	$selected = '';
	$que = 'select id from products_categories where parent="'.$id.'"';
	$resu=mysql_query($que) or die(send_error($que,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$ro=mysql_fetch_row($resu);
		if(in_array($id,$categories_list)) $selected = 'selected="selected"';


        if ($level==0){
			if($ro[0]>0) {
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
        filter_advert_categories($id,$level+1,$bg_category,$aid);
		}
	}else{
	if ($level==0) echo '<option '.$selected.' value="">Все още няма добавени категории</option>';	
	}
}

/*
function filter_advert_categories($id,$level,$parent,$aid){
global $lang;
//$categories_list = array();
$categories = 'select atpc.category_id from adverts_to_product_categories atpc where advert_id="'.$aid.'" group by atpc.category_id';
$categoriesresult=mysql_query($categories) or die(send_error($categories,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$categories_num_rows=mysql_num_rows($categoriesresult);
if($categories_num_rows){
	while($row = mysql_fetch_assoc($categoriesresult)){
		$categories_list[] = $row['category_id'];
		}
	}
	//var_dump($categories_list).'aaaaaaaaaaa';
	$query = 'SELECT bg_category, id, visible,top_category FROM products_categories where parent="'.mysql_real_escape_string($id).'" order by bg_category ASC';
	$res=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
 	//exit();
   if(mysql_num_rows($res) > 0){
    while (list ($bg_category, $id,$visible,$top_category) = mysql_fetch_row($res)){
	$selected = '';
	$que = 'select id from products_categories where parent="'.mysql_real_escape_string($id).'"';
	$resu=mysql_query($que) or die(send_error($que,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$ro=mysql_fetch_row($resu);
		//if(isset($_POST['category']) && ($_POST['category'] == $id)) $selected = 'selected="selected"';
		if(in_array($id,$categories_list)) $selected = 'selected="selected"';

        if ($level==0){
			if($ro[0]>0) {
			echo '<option class="m',$level,' aaa" disabled '.$selected.' value="'.$id.'">'.$bg_category.'</option>';
			}else{
			echo '<option class="m',$level,' bbb" '.$selected.' value="'.$id.'">'.$bg_category.'</option>';		
			}
		}
        else{
			if($ro[0]>0){
			echo '<option class="m',$level,' ccc" disabled '.$selected.' value="'.$id.'">'.$bg_category.'</option>';	
			}
		else{
			if(in_array($id,$categories_list)) echo '<option class="m',$level,' ddd" '.$selected.' value="'.$id.'">'.$bg_category.'</option>';	
			}
		}
        filter_advert_categories($id,$level+1,$bg_category,$aid);
			}
		}else{
		if ($level==0) echo '<option '.$selected.' value="">Все още няма добавени категории</option>';	
		}
	
}
*/

function add_listing_categories($plan){
global $lang;
$categories = array();
$categories = get_advert_categories($_GET['aid']);
//var_dump($categories);
$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc where pc.bg_category != "Временна категория" group by pc.id order by pc.bg_category ASC';
//$query='select pc.id, pc.bg_category, pc.parent, pc.url from products_categories pc,adverts_to_product_categories atpc,adverts a where 
//pc.id=atpc.category_id and atpc.advert_id=a.id and pc.bg_category != "Временна категория" group by pc.id order by pc.bg_category ASC';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);


if($num_rows){
$newcat = check_for_new_categories();
echo '<select multiple="multiple" name="category[]" id="add_listing_categories" class="form-control border-bottom-0" data-placeholder="',$lang['ccat'],'">
<option value="0">'.$lang['ccat'].'</option>
<optgroup label="Категории">';
	while($row = mysql_fetch_assoc($result)){
	$selected = '';
	$disabled = '';
	if($row['bg_category'])
	if( !empty($categories) && in_array($row['bg_category'],$categories)) $selected = 'selected="selected"';
	echo '<option '.$selected.' value="'.$row['bg_category'].'">'.$row['bg_category'].'</option>';
		}
		echo '</optgroup></select>',$newcat;
	}
}

function delete_advert($aid){
$query="delete from adverts_to_product_categories where advert_id='".$aid."'";
mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$query="delete from adverts where id='".$aid."'";
mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

$query='delete from adverts_gallery where advert_id="'.$aid.'"';
mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$msg='<div class="msg">Листинга беше изтрит успешно !</div>';
return $msg;
}

function delete_articles($aid){
$query="delete from articles where id='".$aid."'";
mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$query="delete from articles_to_adverts where article_id='".$aid."'";
mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$query="delete from articles_to_products_categories where article_id='".$aid."'";
mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$msg='<div class="msg">Статията беше изтрита успешно !</div>';
return $msg;
}
function save_article($aid=0){
	global $lang;
	//var_dump($_POST);
	//exit(0);
	//return messages and errors
	$msg[0]=0;
	$msg[1]=$lang['art_success'];
	$msg[2]=0;//if $msg[0]==0 then here we have product id;
	
	if(empty($_POST['bg_article_title']) || empty($_POST['bg_short_description'])){
		$msg[0]=1;
		$msg[1]=$lang['enter_fields'];
		return $msg;
	}
	if(empty($_POST['category'])){
	$msg[0]=1;
		$msg[1]='Моля изберете категория за статията';
		return $msg;
	}
	if(!empty($aid)){
		return update_article($aid);
	
	}
	$_POST['link_name']= translate(trim($_POST['bg_article_title'],$tr));
	$_POST['link_name'] = create_url($_POST['link_name']);

	if(!empty($_POST['link_name'])){
	$query='select id from articles where unique_name="'.mysql_real_escape_string($_POST['link_name']).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	if(mysql_num_rows($result)>0){
		$row=mysql_fetch_row($result);
		return update_article($row[0]);
		}
	}
	
	$query='select id from articles where bg_article_title="'.mysql_real_escape_string($_POST['bg_article_title']).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	if(mysql_num_rows($result)>0){
		$row=mysql_fetch_row($result);
		return update_article($row[0]);
	}
	else{
		if(isset($_FILES['gallery_upload1']) && !empty($_FILES['gallery_upload1']['name'])){
			$msg=is_file_uploaded($_FILES['gallery_upload1']);
			if($msg[0]>0) return $msg;
		}
		
		
		
		if(!empty($_POST['top_article'])) $as_top="1";else $as_top="0";
		$query='insert into articles set 
		category_id="'.mysql_real_escape_string($_POST['category']).'",
		show_in_site="'.mysql_real_escape_string($_POST['show_in_site']).'",
		unique_name="'.mysql_real_escape_string($_POST['link_name']).'",
		top_article="'.$as_top.'",active="1",
		bg_article_title="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_article_title'])).'",
		bg_short_description="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_short_description'])).'",
		bg_meta_description="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_meta_description'])).'",
		bg_tags="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_tags'])).'"';
		
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$last_id=mysql_insert_id();
		
		if(isset($_FILES['gallery_upload1']) && !empty($_FILES['gallery_upload1']['name'])){
			copy_and_resize_article_image($_FILES['gallery_upload1'],create_url($_POST['link_name']).'.jpg',intval($_POST['article']));
			$query='update articles set small_image="'.create_url($_POST['link_name']).'.jpg" where id="'.$last_id.'"';
			$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}
	if(!empty($_POST['article_to_products'])){
	$query="delete from articles_to_products_categories where article_id='".$aid."'";
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$query='insert into articles_to_products_categories set article_id="'.$aid.'", product_category_id="'.mysql_real_escape_string($_POST['article_to_products']).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	}else{
	$query="delete from articles_to_products_categories where article_id='".$aid."'";
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	}
	$msg[2]=$last_id;
	}
	//echo 'cc<br />';
	return $msg;
}

function update_article($aid){
	global $lang;
	//var_dump($_POST);
	//return messages and errors
	$msg[0]=0;
	$msg[1]=$lang['art_success'];
	$msg[2]=0;//if $msg[0]==0 then here we have product id;
	
	if(!empty($_POST['dimage'])){
			//echo $i;
			$query='select small_image from articles where id="'.$aid.'"';
			$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
			$row=mysql_fetch_row($result);
			foreach($_POST as $postKey => $postVal){
			if(preg_match("/^(.*)_article_title$/i",$postKey)) {
			if(!empty($postVal)){	
			$langsign = substr($postKey,0,2);
			$path = servShopHome.ImagesSubDir."articles";
			$file=$path."/".$row[0];
			if(file_exists($file)){
			@unlink($file);
					}
				}
			}
		}
		$query='update articles set small_image="" where id="'.$aid.'"';
		mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);	
	}
	
	if(isset($_FILES['gallery_upload1']) && !empty($_FILES['gallery_upload1']['name'])){
		//echo 'pp<br />';
		$msg=is_file_uploaded($_FILES['gallery_upload1']);
		if($msg[0]>0) return $msg;
	}
	$_POST['link_name']= translate(trim($_POST['bg_article_title'],$tr));
	if(!empty($_POST['link_name'])){
		$query='select id from articles where unique_name="'.$_POST['link_name'].'" and id!="'.$aid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		if(mysql_num_rows($result)>0){
			$msg[0]=2;
			$msg[1]=$lang['wrong_uname'];
			return $msg;
		}
	}
	else{
		$msg[0]=2;
		$msg[1]=$lang['no_uname'];
		return $msg;
	}
	
	if(empty($_POST['category'])){
	$msg[0]=1;
		$msg[1]='Моля изберете категория за статията';
		return $msg;
	}
	
	if(!empty($_POST['top_article'])) $as_top="1";else $as_top="0";
	
	$query='update articles set 
		category_id="'.mysql_real_escape_string($_POST['category']).'",
		show_in_site="'.mysql_real_escape_string($_POST['show_in_site']).'",
		added_date="'.mysql_real_escape_string($_POST['added_date']).'",
		bg_article_title="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_article_title'])).'",
		bg_short_description="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_short_description'])).'",
		bg_meta_description="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_meta_description'])).'",
		top_article="'.$as_top.'",
		bg_tags="'.mysql_real_escape_string(htmlspecialchars($_POST['bg_tags'])).'" where id="'.mysql_real_escape_string ($_POST['article']).'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);

	
	if(isset($_FILES['gallery_upload1']) && !empty($_FILES['gallery_upload1']['name'])){
		$query='select small_image from articles where id="'.$aid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$row=mysql_fetch_row($result);
		@ unlink(servImagesDir."articles/".$row[0]);
		
		copy_and_resize_article_image($_FILES['gallery_upload1'],create_url($_POST['link_name']).'.jpg', intval($_POST['article']));
		$query='update articles set small_image="'.create_url($_POST['link_name']).'.jpg" where id="'.$aid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	}
	if(!empty($_POST['article_to_products'])){
	$query="delete from articles_to_products_categories where article_id='".$aid."'";
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$query='insert into articles_to_products_categories set article_id="'.$aid.'", product_category_id="'.mysql_real_escape_string($_POST['article_to_products']).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	}else{
	$query="delete from articles_to_products_categories where article_id='".$aid."'";
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	}
	$msg[2]=$aid;
	return $msg;
}
function add_query_limits2($current_page){
	if(isset($current_page)) $limit=($current_page-1) * default_tables_rows_count;
	else $limit=0;
	$query_end=" LIMIT ".$limit.",".default_tables_rows_count;
	return $query_end;
}

?>