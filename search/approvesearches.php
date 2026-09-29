<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require '../config.php';
require '../connect.php';
require '../project_functions.php';
require '../translation/blockedwords.php';

//echo $today = date("Y-m-d");

//var_dump($_POST);

if(!empty($_POST['approve'])){

foreach($_POST['approve'] as $key => $apprword){
	if(!in_array($apprword,$blockedwords)){
	$q = 'update statistics set searched_term_approved="1" where id="'.$key.'"';echo '<br />';
		$r=mysql_query($q) or die(send_error($q,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		
		}
	}
}else{
	if(empty($_GET['approve'])){
	$email_subject = 'одобри вчерашните търсения';
	$mail_text = 'здр. Мариянски, това са вчерашните търсения:<br />
	<a href="https://www.espravki.com/search/approvesearches.php?approve=show">Klikni tuk</a><br /><br />Поздрави';
	$params=array(
	"content_type"=>"text/html",
	"replay_to"=>official_mail_sender,
	"from_name"=>mail_name,
	"from_email"=>official_mail_sender,
	"bcc"=>"",
	"to"=>official_mail_sender,
	"subject"=>$email_subject,
	"body"=>$mail_text
	);
	send_mail($params);
	}
}

if(!empty($_GET['approve'])){
//днешните търсения
//$query = 'select id, searched_term, searched_term_approved from statistics where searched_term!="" and DATE(`timest`) = CURDATE() group by searched_term order by searched_term asc';
//вчера търсения
$query = 'select id, searched_term, searched_term_approved from statistics where searched_term!="" and timest between subdate(CURDATE(), 1) and CURDATE() group by searched_term order by searched_term asc';

$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
echo $num_rows=mysql_num_rows($result);
if($num_rows > 0 ){
echo '<form name="approved" method="post" action="../search/approvesearches.php?approved=yes">';
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$checked='';
		if($row[2] == "1") $checked="checked='checked'";
	if(!empty($_POST['approve'])){
	foreach($_POST['approve'] as $apprword){
		if($row[1] ==  $apprword) $checked="checked='checked'";
		}
	}
	echo '<p><label><input type="checkbox" ',$checked,' name="approve[',$row[0],']" value="',$row[1],'" />',$row[1],'</label></p>';
	}
	echo '<p><input type="submit" value="Одобри избраните" /></p>
	</form>';
	}
}
?>