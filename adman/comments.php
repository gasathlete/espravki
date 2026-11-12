<?php
if(isset($_SESSION['adman'])){

//var_dump($_POST);

$msg='';
if(isset($_POST['editcomment'])){
$query='update statistics_adverts_visits set username="'.mysql_real_escape_string($_POST['author']).'",
comment="'.mysql_real_escape_string($_POST['comment']).'",voted="'.mysql_real_escape_string($_POST['voted']).'",approved="1" where id="'.mysql_real_escape_string($_POST['commentid']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$msg='Comment was edited !';
}

if(isset($_POST['delcomment'])){
$query='delete from statistics_adverts_visits where id="'.mysql_real_escape_string($_POST['commentid']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$msg='Comment was deleted !';
}

if(!empty($_GET['new'])){
$query='select username,comment,voted,supplier_name,statistics_adverts_visits.id from statistics_adverts_visits, adverts where approved="0" and comment!="" and adverts.id=advert_id order by addeddate ASC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if(!empty($msg)) echo '<div class="msg">',$msg,'</div>';
echo '<table width="1000" class="p"><tbody><tr><th>#</th><th>Author</th><th>Comment</th><th>Voted value</th><th>About advert</th><th>Actions</th></tr>';

if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_row($result);
echo '<form name="comments" action="" method="POST"><input type="hidden" name="commentid" value="',$row[4],'"/>
<tr><td>',($i+1),'</td>
<td><input type="text" name="author" value="',$row[0],'"/></td>
<td><input type="text" name="comment" value="',$row[1],'"/></td>
<td><input type="text" name="voted" value="',$row[2],'"/></td>
<td>',$row[3],'</td>
<td>
<input type="submit" name="delcomment" value="Изтрий"/>
<input type="submit" name="editcomment" value="Одобри"/>
</td></tr></form>';
	}
	}else echo '<tr><td align="center" colspan="5">Няма намерени резултати</td></tr>';
		echo '</tbody><table>';

}else{
$query='select * from statistics_adverts_visits where approved="1" and comment!="" order by addeddate ASC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if(!empty($msg)) echo '<div class="msg">',$msg,'</div>';

echo '<table width="1000" class="p"><tbody><tr><th>#</th><th>Author</th><th>Comment</th><th>Voted value</th><th>Actions</th></tr>';

if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);
echo '<form name="comments" action="" method="POST"><input type="hidden" name="commentid" value="',$row['id'],'"/>
<tr><td>',($i+1),'</td>
<td><input type="text" name="author" value="',$row['username'],'"/></td>
<td><input type="text" name="comment" value="',$row['comment'],'"/></td>
<td><input type="text" name="voted" value="',$row['voted'],'"/></td>
<td>
<input type="submit" name="delcomment" value="Изтрий"/>
<input type="submit" name="editcomment" value="Одобри"/>
</td></tr></form>';
	}
	}else echo '<tr><td align="center" colspan="5">Няма намерени резултати</td></tr>';

	echo '</tbody><table>';


	}
}else{
	@header("Location:../index.php");
	exit(0);
}