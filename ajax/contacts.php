<?php
@session_start();

$_SESSION['is_human'] = 1;
function code_generator(){
	$symbols=array('1','2','3','4','5','6','7','8','9','0');
	$p='';
	for($i=0;$i<6;$i++){
		$s=rand(0,9);
		$p.=$symbols[$s];
	}
	return $p;
}

if(isset($_SESSION['customer'])){
$customer_info_query="SELECT email, phone FROM customers WHERE id='".addslashes($_SESSION['customer'])."'";
$customer_info_result=mysql_query($customer_info_query) or die();
$customer_info_row=mysql_fetch_assoc($customer_info_result);
$_POST['first_name']=$_SESSION['names'];
$_POST['telephone']=$customer_info_row['phone'];
$_POST['email']=$customer_info_row['email'];
}

//var_dump($_POST);

if(isset($_POST['contact']) && isset($_SESSION['is_human'])){
// validation expected data exists    
if(isset($_POST['first_name']) || isset($_POST['email']) || isset($_POST['telephone']) || isset($_POST['comments'])){	
		$first_name = $_POST['first_name']; 
		$email_from = $_POST['email']; 
		$telephone = $_POST['telephone']; 
		$comments = $_POST['comments']; 
		$error_message = "";    
		$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';  
		if(empty($_POST['email']) || !email_valid($_POST['email'])){   
		$error_message= ''.$lang['wrong_email'].'';  
		}
		$string_exp = "/^[\p{Cyrillic}\d\s\-A-Za-z]+$/u";	
		if(!preg_match($string_exp,$first_name)) {    
		$error_message= ''.$lang['wrong_names'].'';
		}
		$phone_exp = "/^[0-9]+$/i";
		if(strlen($telephone) < 5 || !preg_match($phone_exp,$telephone)) {
		$error_message= ''.$lang['wrong_phone'].'';
		} 		
		if(strlen($comments) < 10) {    
		$error_message= ''.$lang['wrong_message'].'';		
		}
		if(empty($_POST['captcha']) || $_POST['captcha']!=$_SESSION['code']){
		$error_message= ''.$lang['wrong_captcha'].''; 
		}		
		if(strlen($error_message) > 0) {
		//echo $error_message;
		?>
<form name="contactform" method="post" action="">
<div class="msg"><?php echo $error_message;?></div>
<table class="contact_us">
<tr> <th valign="top" colspan="2"><div align="center">
<?php echo $lang['site_contacts'];?><h1><?php echo $lang['contact_us_h1'];?></h1></div></th></tr>
<tr> <td valign="top"><label for="first_name"><?php echo $lang['names'];?> *</label> </td> 
<td valign="top"><input id="your_names" class="tocheck <?php if(!preg_match($string_exp,$first_name)) echo 'red';?>" type="text" name="first_name" maxlength="50" size="30" data="your names" value="<?php echo $_POST['first_name'];?>"></td>
</tr>
<tr> <td valign="top"><label for="email"><?php echo $lang['email'];?> *</label> </td> 
<td valign="top"><input id="your_email" class="tocheck <?php if(empty($_POST['email']) || !email_valid($_POST['email'])) echo 'red';?>" type="text" name="email" maxlength="80" size="30" data="Email" value="<?php echo $_POST['email'];?>"> </td></tr>
<tr> <td valign="top"><label for="telephone"><?php echo $lang['phone'];?> *</label> </td> 
<td valign="top">
<input id="phone" type="text" name="telephone" data="phone" maxlength="12" class="tocheck <?php if(strlen($telephone) < 5 || !preg_match($phone_exp,$telephone)) echo 'red';?>" value="<?php echo $_POST['telephone'];?>" /><span id="errmsg1"></span></td></tr>
<tr> <td valign="top"><label for="comments"><?php echo $lang['message'];?> *</label> </td> 
<td valign="top"><textarea id="your_message" data="message" name="comments" maxlength="1000" cols="25" rows="6" class="tocheck <?php if(strlen($comments) < 20) echo 'red';?>"><?php echo $_POST['comments'];?></textarea> </td></tr>
<tr><td colspan="1" style="text-align:center;"><img alt="captcha" src="captcha_img.php?t=<?php echo time();?>"></td>
<td colspan="1" style="text-align:left;">
<div><?php echo $lang['captcha_lbl'];?><font color="red">*</font>:</div>
<div><input type="text" name="captcha" value="" id="antispam" data="number from picture" class="tocheck <?php if(empty($_POST['captcha']) || $_POST['captcha']!=$_SESSION['code']) echo 'red';?>" style="width:100px;" maxlength="6" />
<span id="errmsg"></span></div>
</td></tr>
<tr> <td colspan="2" style="text-align:center"><input id="contactsubmit" class="save" type="submit" value="<?php echo $lang['send'];?>" name="contact"></td></tr>
</table></form>
<?php	
		//die();  
	}else{
	// create email headers
		$email_subject = $_POST['first_name']." ви изпрати съобщение от ".official_site_name;              
		$mail_text=
		$email_message = "Names: ".$first_name."<br />\n".
		$email_message1 = "Email: ".$email_from."<br />\n".  
		$email_message2 = "Phone: ".$telephone."<br />\n".   
		$email_message3 = "Message: ".strip_tags($comments)."<br />\n".
		$email_message3 = "Country: ".$_SESSION['country']."<br />\n".
		$email_message3 = "City: ".$_SESSION['city']."<br />\n".
		$email_message3 = "IP: ".$_SERVER['REMOTE_ADDR']."<br />\n";   
		$params=array(
		"content_type"=>"text/html",
		"replay_to"=>$_POST['email'],
		"from_name"=>$_POST['first_name'],
		"from_email"=>official_mail_sender,
		"bcc"=>"",
		"to"=>official_mail_sender,
		"subject"=>$email_subject,
		"body"=>$mail_text
		);
		
		if(isset($_SESSION['country']) && ($_SESSION['country'] != 'Russian Federation' && $_SESSION['country'] != 'Ukraine' && $_SERVER['HTTP_HOST'] == 'www.espravki.com')){
		if(isset($_SESSION['real_user'])) send_mail($params);
		
		}
		
		
		?> 
		<!-- include your own success html here --> 
		<div align="center" style="color:orange;font-size:14px;">Благодарим Ви , че ни писахте ! Скоро ще се свържем с Вас !<br />
		<div>Препращам към главна страница ..</div>
		</div> 
		<?php
		unset($_POST['first_name']);
		unset($_POST['contact']);
		unset($_POST['email']);
		unset($_POST['telephone']);
		unset($_POST['comments']);
		unset($_POST['captcha']);
		//var_dump($_POST);
		?>
		<script>
		window.setTimeout(function() {
		window.location.href = '<?php echo WebSite;?>';
	}, 3000);
		</script>
		<?php
		}
	}	
}else $_SESSION['code']=code_generator();
if(!isset($_POST['contact'])){
?>
<form name="contactform" method="post" action="">
<div class="msg"></div>
<table class="contact_us">
<tr> <th valign="top" colspan="2"><div align="center">
<?php echo $lang['site_contacts'];?>
<h1><?php echo $lang['contact_us_h1'];?></h1></div></th></tr>
<tr> <td valign="top"><label for="first_name"><?php echo $lang['names'];?> *</label> </td> 
<td valign="top"><input id="your_names" class="tocheck" type="text" data="your names" name="first_name" maxlength="50" size="30" value="<?php echo @$_POST['first_name'];?>"> </td>
</tr>
<tr> <td valign="top"> <label for="email"><?php echo $lang['email'];?> *</label> </td> 
<td valign="top"><input id="your_email" data="email" class="tocheck" type="text" name="email" maxlength="80" size="30" value="<?php echo @$_POST['email'];?>"> </td></tr>
<tr> <td valign="top"><label for="telephone"><?php echo $lang['phone'];?> *</label> </td> 
<td valign="top"><input id="phone" data="phone" class="tocheck" type="text" name="telephone" style="width:100px;" maxlength="30" size="30" value="<?php echo @$_POST['telephone'];?>" /> <span id="errmsg1"></span></td></tr>
<tr> <td valign="top"><label for="comments"><?php echo $lang['message'];?> *</label> </td> 
<td valign="top"><textarea id="your_message" data="message" class="tocheck" name="comments" maxlength="1000" cols="25" rows="6"><?php echo @$_POST['comments'];?></textarea> </td></tr>
<tr><td colspan="1" style="text-align:center;"><img alt="captcha" src="captcha_img.php?t=<?php echo time();?>"></td>
<td colspan="1" style="text-align:left;">
<div><?php echo $lang['captcha_lbl'];?> *</div>
<div><input type="text" id="antispam" data="number from picture" name="captcha" value="" class="tocheck" style="width:100px;" maxlength="12" />
<span id="errmsg"></span></div>
</td></tr>
<tr> <td colspan="2" style="text-align:center"><input id="contactsubmit" class="save" type="submit" value="<?php echo $lang['send'];?>" name="contact"></td></tr>
</table></form>
<?php
}
?>