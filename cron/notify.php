<?php
require '/home/shoppingbulg/public_html/espravki.com/config.php';
require '/home/shoppingbulg/public_html/espravki.com/connect.php';

exit();
function send_mail($p){
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
<title>=?utf-8?b?'.$p['subject'].'?= </title>
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

function send_error($query,$url1,$url2,$error,$user_ip){
die(mysql_error());
$message="error in ".$_SERVER["REQUEST_URI"]."\r\n".$_SERVER["PHP_SELF"]."\r\n<br />".$query."\r\n<br />".$error."\r\n<br />User:".$user_ip;
mail(official_mail_sender,"error in ".WebSite."",$message);
}

if(isset($_GET['activate'])){
	
	//премахваме държавните институции и други по желание
	$query = 'select a.id, a.supplier_name, a.link_name, a.mail, pc.url, pc.bg_category, last_visits_conter  
	from adverts a, adverts_to_product_categories atpc, products_categories pc
	where a.active = "1" and a.id=atpc.advert_id and atpc.category_id NOT IN ( "10041", "10042","10044","10122", "10252", "10253", ) and 
	atpc.category_id=pc.id and (statistic_mail_date + INTERVAL 20 DAY <= NOW() || statistic_mail_date = "0000-00-00")  
	group by a.id order by id ASC limit 50';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);//echo '<br/>';
	if($num_rows > 0){
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	
	
	$m = date('n');
	$lastmonth_start = date('Y-m-d',mktime(1,1,1,$m-1,1,date('Y')));
	$lastmonth_end = date('Y-m-d',mktime(1,1,1,$m,0,date('Y')));
	//$lastmonth_start = '2017-01-01 00:00:00';
	//$lastmonth_end = '2017-01-31 00:00:00';
	
	$monthcountq='select count(id) from statistics_adverts_visits where advert_id="'.mysql_real_escape_string(intval($row[0])).'" and 
	 (addeddate BETWEEN "'.$lastmonth_start.' 00:00:00" AND "'.$lastmonth_end.' 00:00:00")';
	 
	$monthcountr=mysql_query($monthcountq) or die(send_error($monthcountq,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$monthcountnum_rows=mysql_num_rows($monthcountr);
	if($monthcountnum_rows > 0){
	$monthcountrow=mysql_fetch_row($monthcountr);
	$monthvisitscount=$monthcountrow[0];//echo ' - ',$row[1],'<br/>';
		}else $monthvisitscount = 0;
	
	$countq='select count(id) from statistics_adverts_visits where advert_id="'.mysql_real_escape_string(intval($row[0])).'"';
	 
	$countr=mysql_query($countq) or die(send_error($countq,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$countnum_rows=mysql_num_rows($countr);
	if($countnum_rows > 0){
	$countrow=mysql_fetch_row($countr);
	$visitscount=$countrow[0];//echo ' - ',$row[1],'<br/>';
		}else $visitscount = 0;
		
	$uquery = 'update adverts set last_visits_conter = "'.$monthvisitscount.'", statistic_mail_date = CURDATE() where id="'.mysql_real_escape_string(intval($row[0])).'"';
	mysql_query($uquery) or die(send_error($uquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

	if($row[6] <= $monthvisitscount) $label = '<b style="color:green;">Ръст в посещенията спрямо предния месец</b>: '.($monthvisitscount - $row[6]);
	else $label = '<b style="color:red;">Спад в посещенията спрямо предния месец</b>: '.($monthvisitscount - $row[6]);
	
	$mail_text='<div style="text-align: justify; line-height: 24px; font-family: sans-serif; color: #666; font-size: 13px;">
	Уважаеми '.$row[1].',<br/>
	ето как изглежда статистиката на посещенията на профила ви в Espravki.com през изминалият месец:<br/>
	<b>От Дата</b>:'.$lastmonth_start.'<br/>
	<b>До Дата</b>:'.$lastmonth_end.'<br/>
	<b>Брой посещения за месеца</b>:'.$monthvisitscount.'<br/>
	<b>Брой посещения за предходния месец</b>:'.$row[6].'<br/>'.$label.'<br/>
	<b>Общо посещения за профила</b>:'.$visitscount.'<br/><br/>
	
	<a style="color:#333;" href="'.WebSite.'/business-directory/'.$row[4].'/'.$row[2].'" target="_blank">Кликнете тук за да разгледате профила си</a><br/>
	
	С най-добри пожелания,<br/>
	Екипът на Espravki.com
	</div>';
	$params=array(
	"content_type"=>"text/html",
	"replay_to"=>official_mail_sender,
	"from_name"=>official_mail_sender_name,
	"from_email"=>official_mail_sender,
	"bcc"=>'',
	"to"=>$row[3],
	//"to"=>'info@weddingburg.com',
	"subject"=>'Статистика за посещенията на фирменият ви профил',
	"body"=>$mail_text
	);
	//var_dump($params);
	 //echo $params["body"];echo '<br/><br/>';
		send_mail($params);
		}
	}
}
?>