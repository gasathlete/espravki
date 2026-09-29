<?php
if(isset($_SESSION['adman']) && $_SESSION['adman'] == "1"){
//var_dump($_POST['maillist']);

if (!empty($_POST['maillist'])) {
$_POST['maillist'] = str_replace(array("\r\n"),'',$_POST['maillist']);
$_POST['maillist'] = rtrim($_POST['maillist'], ", \t\n");
 $mails = explode(',', $_POST['maillist']);

    foreach ($mails as $mail) {
    if (isset($mail) && $mail != "") {// check for empty email
	$mail=trim($mail);
	if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
   // echo("$mail is a valid email address<br>");
   $query = 'select id from mails_import where email="'.mysql_real_escape_string($mail).'"';
   $result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($result) < 1){
	$q = 'insert into mails_import set email="'.mysql_real_escape_string($mail).'", sent="0",keyword="'.$_POST['keyword'].'"';
	$r=mysql_query($q) or die(send_error($q,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));		
				}else echo $mail.' is already in the base !<br/>';
			}else echo $mail.' is invalid !<br/>';
        }
    }
}
echo '<div style="width:500px;margin:0 auto;"><form name="mails" method="POST" action="">
<span style="width:100%;display: block;">Ключова дума за мейлите
<input type="text" name="keyword" value="',@$_POST['keyword'],'"/></span>
Моля копирайте мейлите в полето. мейлите да са разделени с ","
<textarea  style="width:100%;height:400px" name="maillist" value=""></textarea>
<input type="submit" value="Upload" style="display: block;margin: 20px auto;padding: 5px 10px;"/>
</form></div>';
}
?>