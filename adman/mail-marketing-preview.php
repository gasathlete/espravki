<?php
//var_dump($_POST);
//var_dump($_SESSION);
if(isset($_SESSION['adman'])){

$msg='';
$d=time();
//$online=getOnline();
if(empty($_POST['y']) or empty($_POST['m']) or empty($_POST['d'])){
	$_POST['y']=date('Y');
	$_POST['m']=date('m');
	$_POST['d']=date('d');
}
elseif(checkdate($_POST['m'],$_POST['d'],$_POST['y'])){
	$d=mktime(10, 10, 10, (int)$_POST['m'], (int)$_POST['d'], (int)$_POST['y']);
	//if($d>time()) $msg='Въвели сте невалидна дата!';
}
else $msg='Въвели сте невалидна дата!';

if($_SESSION['adman']=="1"){
	$q1='select admin_name from admins';
	$res=mysql_query($q1) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q1);
	$num_rows=mysql_num_rows($res);
	$selected='';
	echo '<div class="form-group mtb10 tal ib vat w50"><select onchange="setactive(this);" name="active_admins">
	<option value="0">Всички администратори</option>';
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($res);
		$selected='';
		if($_POST['admins']==$row[0]) $selected='selected="selected"';
		echo '<option ',$selected,' value="',$row[0],'">',$row[0],'</option>';

}

	?>
	<option <?php if($_POST['admins']=="automated") echo 'selected="selected"';?> value="automated">Автоматизиран мейл</option>
	</select></div><div class="table  ib vat w50 text-right"><a class="btn btn-primary btn-sm" href="./index.php?page=mail-marketing">Назад</a></div>
	<?php
}
?>
<form name="form1" action="../adman/index.php?page=mail-marketing-preview" id="form1" method="post">
<input type="hidden" id="admins" name="admins" value=""/>
<div class="srcdiv">
<table border="0" cellspacing="1" class="p" style="margin:0 0 0 auto;">
	<tr>
		<td>&nbsp;</td>
		<td align="center">YYYY</td>
		<td align="center">-</td>
		<td align="center">MM</td>
		<td align="center">-</td>
		<td align="center">DD</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><a href="javascript:prevDate();" style="color:#000;">&lt;&lt;&nbsp;</a></td>
		<td><input style="width:50px;" type="text" name="y" maxlength="4" style="width:40px;" id="dy" onkeypress="return(dateFormat(event));" value="<?php echo @$_POST['y']?>" /></td>
		<td>-</td>
		<td><input style="width:50px;" type="text" name="m" maxlength="2" id="dm" onkeypress="return(dateFormat(event));" value="<?php echo @$_POST['m']?>" /></td>
		<td>-</td>
		<td><input style="width:50px;" type="text" name="d" maxlength="2" id="dd" onkeypress="return(dateFormat(event));" value="<?php echo @$_POST['d']?>" /></td>
		<td><a href="javascript:nextDate();" style="color:#000;">&nbsp;&gt;&gt;</a></td>
	</tr>
</table>
</div>
</form>

<?php if($msg!='') echo '<div style="color:#ff0000;margin-top:10px;">'.$msg.'</div>';?>

<?php
if($d==0) $d=time();
$query='select * from mail_campaigns where email !="" ';

if($_SESSION['adman']=="1"){
if(!empty($_POST['admins'])){
$query.='and admin="'.mysql_real_escape_string($_POST['admins']).'" ';
}else $query.='and admin !="" ';
}else $query.='and admin="'.mysql_real_escape_string($_SESSION['admin_name']).'" ';
if(!empty($_POST['y'])) $query.=' and date > "'.date('Y-m-d 00:00:00',$d).'" and date < "'.date('Y-m-d 23:59:59',$d).'"';

$query.= ' order by date DESC';
//echo $query;
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);

echo '<table width="100%" class="table table-striped text-left"><tbody>
<tr>
<th class="text-left success mw20">#</th>
<th class="text-left success">Email</th>
<th class="text-left success">Код на мейла</th>
<th class="text-left success">Дата</th>
<th class="text-left success">Отворен</th>
<th class="text-left success">Посетил сайта</th>
<th class="text-left success">IP</th>
<th class="text-left success">Държава</th>
<th class="text-left success">Град</th>
<th class="text-left success">Администратор</th>
<th class="text-left success">Ключова дума</th>
</tr>
';
$countopened='';
$countvisited='';
if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);
$countopened+=$row['opened'];
$countvisited+=$row['clicked'];
echo '<tr>
<td>',($i+1),'</td>
<td>',$row['email'],'</td>
<td>',$row['code'],'</td>
<td align="center">',date('M j Y ', strtotime($row['date'])),'</td>
<td align="center">',$row['opened'],' път/и</td>
<td align="center">',$row['clicked'],' път/и</td>
<td align="center">',$row['ip'],'</td>
<td align="center">',$row['country'],'</td>
<td align="center">',$row['city'],'</td>
<td align="center">',$row['admin'],'</td>
<td align="center">',$row['keyword'],'</td>
</tr>';		
	}
echo '<div>Изпратени мейли: ',$num_rows,'<br />Отворени мейли: ',$countopened,'<br />Посетили сайта: ',$countvisited,'</div>';
}
echo '</tbody></table>';

}else{
header("Location: ../index.php");
}
?>
<script language="JavaScript" type="text/javascript">
function dateFormat(e){
var c=0;
if(e.keyCode) c=e.keyCode; else c=e.which;
switch(c){case 48:case 45:case 46:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:case 116:return true;break;case 13:document.form1.submit();break;default:return false;break;}
}
function format(p){
	if(p<10) return ('0'+p);
	return p;
}
function prevDate(){
	var dy=gE('dy').value;
	var dm=gE('dm').value;
	var dd=gE('dd').value;
	var d=new Date(dy,(dm-1),dd,0,0,0,0);
	d=new Date(Number(d.getTime())-86400000);
	gE('dy').value=d.getFullYear();
	gE('dm').value=format(Number(d.getMonth())+1);
	gE('dd').value=format(d.getDate());
	document.form1.submit();
}
function nextDate(){
	var dy=gE('dy').value;
	var dm=gE('dm').value;
	var dd=gE('dd').value;
	var d=new Date(dy,(dm-1),dd,0,0,0,0);
	d=new Date((Number(d.getTime())+86400000));
	gE('dy').value=d.getFullYear();
	gE('dm').value=format(Number(d.getMonth())+1);
	gE('dd').value=format(d.getDate());
	document.form1.submit();
}

function setactive(o){
	document.getElementById('admins').value=o.value;
	document.getElementById('form1').submit();
}
</script>