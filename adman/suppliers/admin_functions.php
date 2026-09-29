<?php
function _bot_detected(){
  if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Googlebot|bot|crawl|robospider|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
    return TRUE;
  }else{
    return FALSE;
  }
}
function send_error($query,$url1,$url2,$error,$user_ip){
die(mysql_error());
$message="error in ".$_SERVER["REQUEST_URI"]."\r\n".$_SERVER["PHP_SELF"]."\r\n<br />".$query."\r\n<br />".$error."\r\n<br />User:".$user_ip;
mail(official_mail_sender,"error in ".WebSite."",$message);
}
function add_query_limits($current_page){
	if(isset($current_page)) $limit=($current_page-1) * default_tables_rows_count;
	else $limit=0;
	return ' LIMIT '.$limit.','.default_tables_rows_count;
}


function get_pages_count($query){
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$count=mysql_num_rows($result);
	if($count){
		$pages_count=intval($count/default_tables_rows_count);
		if(($count%default_tables_rows_count)!=0) $pages_count++;
		return $pages_count;
	}
	else return 1;
}
function get_base_link(){
	$link='index.php';
	if(count($_GET)){
		$current_val=each($_GET);
		$first=1;
		while($current_val){
			if($current_val[0]!='pn'){
				if($first){
					$link.='?';
					$first=0;
				}
				else $link.='&';
				$link.=$current_val[0].'='.$current_val[1];
			}
			$current_val=each($_GET);
		}
	}
	reset($_GET);
	return $link;
}
function set_navigation($lang){ 
	$my_array=array();
	if(isset($_GET['subarticles'])){
		$query="SELECT `id`,`bg_article_title` title,`category_id` FROM `articles` WHERE `id`='".addslashes($_GET['subarticles'])."'";
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		if(mysql_num_rows($result)){
			$row=mysql_fetch_assoc($result);
			$my_array['url'][0]=$row['category_id'];
			$my_array['labels'][0]=$row['title'];
			$category=$row['category_id'];
			$i=0;
			while($category!='0'){
				$i++;
				$query="SELECT id, category_id, bg_article_title title FROM articles WHERE id='".addslashes($category)."'";
				$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
				$row=mysql_fetch_assoc($result);
				$category=$row['category_id'];
				$my_array['url'][$i]=$row['category_id'];
				$my_array['labels'][$i]=$row['title'];
			}
		}
	}
	$html="<span style=\"\">";
	$count=count(@$my_array['url']);
	for($i=$count-1;$i>-1;$i--){
		if($my_array['url'][$i]!='0')
			$html.="<a href=\"index.php?subarticles=".$my_array['url'][$i]."&pn=".$_GET['pn']."\">".$my_array['labels'][$i]."</a>&nbsp;|&nbsp;";
		else
			$html.="<a href=\"index.php?page=articles&pn=".$_GET['pn']."\">".$my_array['labels'][$i]."</a>&nbsp;|&nbsp;";
	}
	$html.="</span>";
	return $html;
}
function is_mail($value){
	if($value!=""){
		if (( preg_match("/^[A-Za-z0-9]{1,100}@[A-Za-z0-9]{1,100}[A-Za-z0-9_\.-]{0,100}[A-Za-z0-9]{1,100}\.[A-Za-z]{2,4}$/",$value , $matches ) == false ) && (
		preg_match ( "/^[A-Za-z0-9]{1,100}[A-Za-z0-9_\.-]{0,100}[A-Za-z0-9]{1,100}@[A-Za-z0-9]{1,100}[A-Za-z0-9_\.-]{0,100}[A-Za-z0-9]{1,100}\.[A-Za-z]{2,4}$/",
		$value, $matches ) == false )) return 0;
		else return 1;
	}
	else return 0;
}

function check_login_action($field,$table){
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

	if((empty($_POST['admin_mail'])) or (empty($_POST['admin_password'])) or (empty($_POST['captcha']))){
		$my_result['changes']['message_div']['content']=$lang['emty_fields'];
	}
	else{
		if(is_mail($_POST[$mail_field])){
			$query='SELECT id, user_group, admin_name FROM admins WHERE 
			email="'.mysql_real_escape_string($_POST['admin_mail']).'" and 
			password="'.mysql_real_escape_string(md5($_POST['admin_password'])).'" and 
			code="'.mysql_real_escape_string(md5($_POST['captcha'])).'" and active="1"';//echo '<br>';
			$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			if(mysql_num_rows($result)){
			$row=mysql_fetch_row($result);
				$my_result['result']=$row[1];
				
				$_SESSION['adman']=1;
				$_SESSION['admin_id']=$row[0];
				$_SESSION['admin_level']=$row[1];
				$_SESSION['admin_name']=$row[2];
			}
			else{
				$my_result['changes']['message_div']['content']=$lang['invalid_user_name'];
			}
		}
		else{
			$my_result['changes']['message_div']['content']=$lang['invalid_user_name'];
		}
	}
	return $my_result;
}



function echo_cat_path($ncid=0,$npid=0,$adverthome=''){
global $lang;
//echo $ncid;
echo '<div class="holder">';
//echo '<div>',$lang['you_are_here'],'</div>';
echo '<div class="links"><a class="fi" title="Отиди на главна страница" href="',WebSite,'/home.php">',$lang['home'],'</a> » </div>';
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
?>