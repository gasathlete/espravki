<?php
session_start();
if(isset($_SESSION['adman'])){
require '../config.php';
require './suppliers/lang/English.php';
require_once('./searchkeys.class.php');
mysql_connect($db_host,$db_user,$db_pass,$db_name);
mysql_select_db($db_name);
mysql_set_charset('utf8');

function getStatistics($d=0){
	if($d==0) $d=time();
	$flag=false;
	$query='select id,user_type,user,ip,url,referer,timest,sid,user_agent,post,city,country,errorsession from statistics where timest > "'.date('Y-m-d 00:00:00',$d).'" and timest < "'.date('Y-m-d 23:59:59',$d).'" order by timest desc';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		if($i%2) $bg=' class="rbg"';
		else $bg='';
		
		$keys = new search_keywords($row[5]);
		$keys = $keys->get_keys();
		
		echo '<tr'.$bg.'>
		<td>',$i+1,'</td>
		
		<td>',$row[3],'</td>
		<td>',$row[10],'</td>
		<td>',$row[11],'</td>
		<td><div class="shd">',$row[4],'</div></td>
		<td><div class="shd">',$row[5],'</div></td><td>',$keys[2],'</td>
		<td>',$keys[1],'</td><td>',$row[9],'</td>
		<td align="center"><a class="l" href="#" onclick="alert(\''.$row[8].'\');">виж</a></td>
		<td>',$row[12],'</td>
		<td>',$row[6],'</td></tr>';
		$flag=true;
	}
	return $flag;
}

function getOnline(){
	$query='select user_type,sid from (select user_type,sid from statistics where timest > "'.date('Y-m-d H:i:s',(time()-300)).'" order by timest desc) as t group by sid ';
	
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$ret[0]=0; //online admans
	$ret[1]=0; //online custimers
	$ret[2]=0; //online guests
	$ret[3]=mysql_num_rows($result); //total online
	for($i=0;$i<$ret[3];$i++){
		$row=mysql_fetch_row($result);
		if($row[0]=='adman') $ret[0]++;
		if($row[0]=='customer') $ret[1]++;
		if($row[0]=='guest') $ret[2]++;
	}
	return $ret;
}

function getUniqueIP($d=0){
	if($d==0) $d=time();
	$query='select distinct ip from statistics where timest > "'.date('Y-m-d 00:00:00',$d).'" and timest < "'.date('Y-m-d 23:59:59',$d).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	
	return mysql_num_rows($result);
}
function getUniqueSID($d=0){
	if($d==0) $d=time();
	$query='select distinct sid from statistics where timest > "'.date('Y-m-d 00:00:00',$d).'" and timest < "'.date('Y-m-d 23:59:59',$d).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}

$msg='';
$d=time();
$online=getOnline();
//echo $date('Y-m-d H:i:s');
if(empty($_GET['y']) or empty($_GET['m']) or empty($_GET['d'])){
	$_GET['y']=date('Y');
	$_GET['m']=date('m');
	$_GET['d']=date('d');
}
elseif(checkdate($_GET['m'],$_GET['d'],$_GET['y'])){
	$d=mktime(10, 10, 10, (int)$_GET['m'], (int)$_GET['d'], (int)$_GET['y']);
	if($d>time()) $msg='Въвели сте невалидна дата!';
}
else $msg='Въвели сте невалидна дата!';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Online users</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="../admin.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../adman/suppliers/css/style.css">
	<script src="./suppliers/js/suppliers.js" language="JavaScript" type="text/javascript"></script>
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
</script>
</head>
<body>
<center>
<div id="menu_top">
<?php
include("./menu_top.php");
?>
<table style="width: 70%; float: left; margin:20px;" border="0" cellspacing="1" class="p">
	<tr>
		<th colspan="8">В момента има online:</th>
		<th colspan="4">Справки за посочената дата:</th>
	</tr>
	<tr>
		<td align="right">Guests:</td>
		<td><?php echo $online[2];?></td>
		<td align="right">Customers:</td>
		<td><?php echo $online[1];?></td>
		<td align="right">Admans:</td>
		<td><?php echo $online[0];?></td>
		<td align="right">Total:</td>
		<td><?php echo $online[3];?></td>
		<td align="right">Unique IP:</td>
		<td><?php echo getUniqueIP($d);?></td>
		<td align="right">Unique SID:</td>
		<td><?php echo getUniqueSID($d);?></td>
	</tr>
</table>
<?php if($msg!='') echo '<div style="color:#ff0000;margin-top:10px;">'.$msg.'</div>';?>
<form name="form1" action="online.php" method="get">
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
		<td><input style="width:50px;" type="text" name="y" maxlength="4" style="width:40px;" id="dy" onkeypress="return(dateFormat(event));" value="<?php echo @$_GET['y']?>" /></td>
		<td>-</td>
		<td><input style="width:50px;" type="text" name="m" maxlength="2" id="dm" onkeypress="return(dateFormat(event));" value="<?php echo @$_GET['m']?>" /></td>
		<td>-</td>
		<td><input style="width:50px;" type="text" name="d" maxlength="2" id="dd" onkeypress="return(dateFormat(event));" value="<?php echo @$_GET['d']?>" /></td>
		<td><a href="javascript:nextDate();" style="color:#000;">&nbsp;&gt;&gt;</a></td>
	</tr>
</table>
</div>
</form>
<table width="100%" cellspacing="1" class="p">
	<tr>
		<th>#</th>
		<th>IP address</th>
		<th>City</th>
		<th>Country</th>
		<th>URL</th>
		<th>Referer URL</th>
		<th>Search engine</th>
		<th>Keywords</th>
		<th width="300">POST</th>
		<th>User agent</th>
		<th>Errors</th>
		<th>Date</th>
	</tr>
	<?php if(!getStatistics($d)) echo '<tr><td colspan="10" align="center">Няма статистика за тази дата!</td></tr>';?>
</table>
</div>
</center>
</body>
</html>
<?php
}
else{
header("Location: index.php");
}
?>