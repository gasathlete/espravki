<?php
if(isset($_SESSION['adman'])){
global $lang;


$msg='Моля попълнете полетата по-долу';
function generateRandomString($length = 20) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

function send_mail2($p,$code){
$headers ='';
if(isset($p['from_name'])) $headers .= "From: =?utf-8?b?".base64_encode($p['from_name'])."?= <".$p['from_email'].">\r\n";
if(isset($p['from_email'])) $headers .= "Reply-To: ". strip_tags($p['from_email']) . "\r\n";
if(!empty($p['cc'])) $headers .= "CC: ". strip_tags($p['cc']) . "\r\n";
if(!empty($p['bcc'])) $headers .= "BCC: ". strip_tags($p['bcc']) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$random_hash = md5(date('r'));
//$p['body']=str_replace(array('<br>','<br />'),"\r\n",$p['body']);
		//$body="This is a multi-part message in MIME format.\r\n\r\n--PHP-alt-$random_hash\r\nContent-Type: text/html;\r\n charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".strip_tags(str_replace(array('<br>','<br />'),"\n",$p['body']))."\r\n--PHP-alt-$random_hash\r\nContent-Type: text/html;\r\n charset=\"utf-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n";
		$body='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>'.$p['subject'].'</title>
</head>
<body bgcolor="White">
<center>
<div style="text-align: left;font-family:verdana,sans-serif;font-size:12px;width:100%;padding:0;margin:0;color:#555;background:#fff;">
<a style="display:block;text-decoration:none;margin:0;padding:0;" href="'.WebSite.'/mails/index.php?code='.$code.'&act=click" target="_blank">
	<img style="padding:0;margin:0;border:none;width:100%;" border="0" src="'.WebSite.'/images/mailheader.jpg" alt="'.official_mail_sender_name.'" />
</a>
<div style="line-height: 20px;padding: 40px 5px; text-align: left; margin: 15px 0px; border-top: 3px double #ddd; border-bottom: 3px double #ddd;">
	<br>'.$p['body'].'<br></div>
<div style="background:#555;color:#ffffff;margin:0;padding:5px;height:30px;line-height:30px;text-align:center;">©Copyright '.date('Y').'"&nbsp;&nbsp;<a href="'.WebSite.'/mails/index.php?code='.$code.'&act=click" target="_blank" style="color:#fff;text-decoration:none;">'.official_site_name.'</a> - All Rights Reserved</div>
<img src="'.WebSite.'/mails/index.php?code='.$code.'" />
</div>
</center>
</body>
</html>';

	//if( $_SERVER['REMOTE_ADDR'] == '85.187.42.50') echo $headers;echo '<br/>';
	$mail_sent = mail($p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers, "-f ".official_mail_sender."");
	//echo $p['to'];
	return $mail_sent ? true : false;
}

$subject = '';
$redirect = '';
if(isset($_POST['btn']) && $_POST['btn']=='add'){
$redirect = $_POST['redirect'];
$subject = addslashes($_POST['subject']);
$mail_description =$_POST['mail_description'];
$receiver = $_POST["receiver"];

$_POST['receiver'] = str_replace(';',',',$_POST['receiver']);
$mailarray=explode(',',$_POST['receiver']);
$html='';


	foreach($mailarray as $email){
	//prowerqwame dali na maila e prashtano prez poslednite 10 dni
	$q='select id from mail_campaigns where email="'.mysql_real_escape_string($email).'" and DATE_SUB(CURDATE(),INTERVAL 1 DAY) <= date';
	$result=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
	$num_row=mysql_num_rows($result);
	//ako na maila ne sa prashtani maili w poslenite 10 dni
	if($num_row < 1){
	//echo 'tuk';
	$code=$_SESSION['admin_name'].'_'.generateRandomString();
	//izprashtame mailite
	$subject = $subject;
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
	send_mail2($params,$code);
	//var_dump($params);exit();

	$query='insert into mail_campaigns set email="'.mysql_real_escape_string($email).'",code="'.mysql_real_escape_string($code).'",admin="'.mysql_real_escape_string($_SESSION['admin_name']).'", redirect_url="'.mysql_real_escape_string($_POST['redirect']).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}
	}
	$msg='Мейлите бяха изпратени';
	//exit();
}
?>  
<?php
//var_dump($_POST);
?>
<section class="section-80 section-md-110">
<div class="shell">
<div class="table text-right"><a class="btn btn-primary btn-sm" href="./index.php"><?php echo $lang['back'];?></a></div>
<div class="cell-md-8">	
<form action="" name="form1" id="form1" method="POST" class="products_form" onsubmit="return validateForm()">
<div class="msg"><?php echo $msg;?></div>

<div class="table text-right"><a href="./index.php?page=mail-marketing-preview" class="btn btn-primary btn-sm"><?php echo $lang['view_sent_mails'];?></a></div>
<div class="form-group mtb10">
<label for="subject" class="form-label form-label-outside ib tal w100 mh rd-input-label"><?php echo $lang['subject_mails'];?></label>
<input autocomplete="off" id="subject" type="text" name="subject" data-constraints="@Required" value="<?php echo $subject;?>" class="form-control form-control-gray" />
</div>


<div class="form-group mtb10">
<label for="mail_description" class="form-label form-label-outside ib tal w100 mh rd-input-label"><?php echo $lang['mail_txt'];?></label>
<textarea id="mail_description" class="form-control form-control-gray" name="mail_description" value="<?php echo htmlspecialchars(@$_POST['mail_description']);?>" ><?php echo htmlspecialchars(@$_POST['mail_description']);?></textarea>
</div>

<div class="form-group mtb10 ib w100">
<label for="receiver" class="ib tal w100 mh rd-input-label mtb10"><?php echo $lang['mail_receiver'];?><a href="#" data-toggle="modal" data-target="#list"> Може да избереш от списък с клиенти тук</a></label>
<input autocomplete="off" id="receiver" type="text" name="receiver" data-constraints="@Required" value="<?php echo @$_POST['receiver'];?>" class="form-control form-control-gray ib w100" />
</div>

<div class="form-group mtb10">
<label for="redirect" class="form-label form-label-outside ib tal w100 mh rd-input-label"><span class="in"><?php echo $lang['mail_redirect'];?></span></label>
<input autocomplete="off" id="redirect" type="text" name="redirect" data-constraints="@Required" value="<?php echo $redirect;?>" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10">
<input type="hidden" name="btn" id="btn" value="add" />
	<button type="BUTTON" class="btn btn-primary btn-sm mtb10 cancel ib" onclick="window.location='index.php'"><?php echo $lang['close'];?></button>&nbsp;&nbsp;
	<button type="submit"  onClick="return confirm('Сигурни ли сте , че желаете да изпратите мейла?');" class="btn btn-secondary mtb10 ib save">Изпрати</button>
</div>



	<div class="modal fade" id="list" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="examplecontactLongTitle">Избери от списъка</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php echo get_customer_mails();?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Затвори</button>
				</div>
			</div>
		</div>
	</div>

<?php //echo set_navigation_promotions();
?>
</form>
<script language="JavaScript">
function validateForm(){
 var y=document.forms["form1"]["subject"].value;
 var x=document.forms["form1"]["receiver"].value;
 
 var body = tinymce.get("mail_description").getBody();
var content = tinymce.trim(body.innerText || body.textContent);
content = content.replace(/\s+/g, ' ');
	
if(isEmpty(content)){
content = 0;
}else content = content.split(' ').length;

if(content < 50){
tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fdcaca';
}else tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fff';


 if (x==null || x==""){
   alert("Не сте въвели получатели !!! Не повече от 5-6 !");
   return false;
   }
   if (y==null || y==""){
   alert("Не сте въвели Тема на мейла !!! Не повече от 5-6 думи !");
   return false;
   }
 }
</script>
</div></div>
</section>
<?php
}else{
	header("Location:../../index.php");
	exit(0);
}
?>