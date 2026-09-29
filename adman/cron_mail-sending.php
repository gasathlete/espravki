<?php
session_start();
//echo 'success';echo '<br/>';
//echo $dir = dirname(__FILE__);exit();
global $lang;
	require '../config.php';
	require '../connect.php';

$msg='Моля попълнете полетата по-долу';
function generateRandomString($length = 20) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}
/*
function send_mail($p,$code){
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
<a style="display:block;text-decoration:none;margin:0;padding:0;" href="'.WebSite.'/mails/'.$code.'" target="_blank">
	<img style="padding:0;margin:0;border:none;width:100%;" border="0" src="'.WebSite.'/images/mailheader.jpg" alt="'.official_mail_sender_name.'" />
	<div style="background:#06294F;color:#fff;font-size:11pt;font-weight:700;font-style:italic;margin:0;padding:5px 0;" align="center">'.official_mail_sender_name.'</div>
</a>
<div style="padding: 5px; text-align: left; font-size: 13px; font-family: verdana;"><br>'.$p['body'].'<br></div>
<div style="background:#06294F;color:#fff;margin:0;padding:5px 0;text-align:center;">©Copyright '.date('Y').'"&nbsp;&nbsp;<a href="'.WebSite.'/mails/'.$code.'" target="_blank" style="color:#fff;text-decoration:none;">'.official_site_name.'</a> - All Rights Reserved</div>
<img alt="." src="'.WebSite.'/mails/index.php?code='.$code.'"></div>
</center>
</body>
</html>'; 
		$body.="\r\n\r\n--PHP-alt-$random_hash--";
	}
	//echo $body;
	$mail_sent = @mail( $p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers );
	return $mail_sent ? true : false;
}

	$subject = 'Your business profile in espravki.com';
	*/

	$query = 'select email,keyword from mails_import where sent="0" ORDER BY RAND() limit 2';
	$res=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_r=mysql_num_rows($res);
	if($num_r > 0){

	for($i=0;$i<$num_r;$i++){
	$row=mysql_fetch_row($res);
	$email = $row[0];
	$keyword = $row[1];

	//prowerqwame dali na maila e prashtano prez poslednite 10 dni
	$q='select id from mail_campaigns where email="'.mysql_real_escape_string($email).'" and DATE_SUB(CURDATE(),INTERVAL 10 DAY) <= date';
	$result=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
	$num_row=mysql_num_rows($result);
	//ako na maila ne sa prashtani maili w poslenite 10 dni
	if($num_row < 1){
	if(!empty($email)){
	$code='Marian_'.generateRandomString();
	
	$mail_description = 'Dear Colleagues,<br />
	I hope this email finds you well! First of all please excuse me for this direct approach - I can assure you that I am aware of how precious is your time and that\'s why this will be my first and last email if the matter below is out of your interests !<br />
	My name is Marian and I am representative of espravki.com. This is a business directory which provides <b>free</b> or premium business listing with several very innovative techniques that will move forward your company\'s website in the search engines results. 
	Nowadays there aren\'t many free options for advertising our businesses and this is the purpose for contacting you. Please take a minute to register your business and take advantage of being promoted world wide for free.
	Just prepare a unique text that best describes your business, enter your contact details, website and business keywords and you are done..<br />
	If you decide to become a Premium business user, than you will be able to use our Google+ review exchange programme. This will quickly move up your website position in the search engine results for main targeted keyword. Premium users have also many other extras available<br />
	Click <a href="'.WebSite.'/mails/'.$code.'">here</a> to compare free and premium business listings.<br />
	Remember: <b>All tiny choices we make today impact the shape of the future</b><br /><br />
	Wishing you successful business,<br />Marian<br />espravki.com';

	//izprashtame mailite
	/*$subject = $subject;
	$body = $mail_description;
	$params=array(
	"content_type"=>"text/html",
	"replay_to"=>official_mail_sender,
	"from_name"=>official_mail_sender_name,
	"from_email"=>official_mail_sender,
	"to"=>$email,
	"subject"=>$subject,
	"body"=>$body
	);
	send_mail($params,$code);*/
	//var_dump($params);echo '<br /><br /><br />';
	//exit();
	
	$url = "http://www.oditor.eu/mailer.php";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);

	$data = array(
	'mail' => $email,
	'code' => $code
	);


	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	$contents = curl_exec($ch);
	curl_close($ch);
	
	echo $contents;


	$query='insert into mail_campaigns set email="'.mysql_real_escape_string($email).'",code="'.mysql_real_escape_string($code).'",
	admin="automated", redirect_url="'.WebSite.'/addbusiness/free/",keyword="'.mysql_real_escape_string($keyword).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	
	$query = 'update mails_import set sent="1" where email="'.mysql_real_escape_string($email).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	
	//echo $msg='Мейла до '.$email.' беше изпратен';
				}
			}
		}
	}
	//$msg='Мейлите бяха изпратени';
?>