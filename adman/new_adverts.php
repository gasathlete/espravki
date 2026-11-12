<?php
session_start();
if(isset($_SESSION['adman'])){

mysql_set_charset('utf8');

//var_dump($_POST);

if(isset($_POST['record_action']) && $_POST['record_action']=="delete"){
	//echo $msg=delete_advert(addslashes($_POST['advert']));
}

function getProductsBy_AdvertsId(){
	global $lang;
	$query='select a.supplier_name,a.mail,a.meta_description,a.expired_date,a.country,bg_category,a.active, a.id, added_date, affiliate_id, referral, ip   
	from adverts a,products_categories pc,adverts_to_product_categories atpc where 
	a.id=atpc.advert_id and atpc.category_id = pc.id and (a.active="0" or (a.expired_date!="0000-00-00" and a.expired_date < now())) group by a.id order by added_date desc';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	
	if($num_rows > 0){
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		if($i%2) $bg=' class="rbg"';
		else $bg='';
		
		if($row[6]==0){$v1='<img src="../images/interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="../images/interface/desactivate.png" />'; $v2=0;}
	
		echo '<tr'.$bg.'>
		<td align="left">',$i+1,'</td>
		<td align="left">',$row[0],'</td>
		<td align="left">',$row[1],'</td>
		<td align="left">',$row[2],'</td>
		<td align="center">',$row[8],'</td>
		<td align="center">',$row[3],'</td>
		<td align="center">',$row[4],'</td>
		<td align="center">',$row[5],'</td>
		<td align="center">',$row[9],'</td>
		<td align="center">',$row[10],'</td>
		<td align="center">',$row[11],'</td>
		<td align="center">
		<span class="laction"><a href="index.php?page=new_adverts&aid='.$row[7].'&visible='.$v2.'">',$v1,'</a></span>
		<span class="laction">
		<form method="post" action="../adman/index.php?page=adverts&advert='.$row[7].'" >
		<input type="hidden" value="',$row[7],'" name="advert" />
		<input type="hidden" value="update" name="record_action" />
		<a href="index.php?page=edit_advert&aid='.$row[7].'"><img src="../images/interface/update.png" /></a></form></span>
		
		<span class="laction">
		<form method="post" name="delform',$row[7],'" id="delform',$row[7],'">
		<input type="hidden" value="',$row[7],'" name="advert" />
		<input type="hidden" value="delete" name="record_action" />
		<img onclick="return delete_ask2(\'',$row[7],'\');" src="../images/interface/delete.png" title="delete" /></form></span>
		</td></tr>';
		$flag=true;
	}
	}else echo '<tr><td colspan="12" align="center">Няма нови листинги</td></tr>';
	
}

if(isset($_GET['visible']) && !empty($_GET['aid'])){
	$query='update adverts set active="'.addslashes(htmlspecialchars($_GET['visible'])).'" where id="'.addslashes(htmlspecialchars($_GET['aid'])).'"';
	mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
}

?>
<h2>Нови листинги</h2>
<div class="table-responsive">
<table width="100%" class="p"><tbody>
	<tr>
		<th>#</th>
		<th>Заглавие</th>
		<th>Емейл</th>
		<th>Кратко описание</th>
		<th>Дата на добавяне</th>
		<th>Дата на изтичане</th>
		<th>Страна</th>
		<th>Категория</th>
		<th>Афилиейт</th>
		<th>Референция</th>
		<th>IP адрес</th>
		<th>Действия<span style="float:right;">
		<form method="post" action="index.php?page=adverts&advert=0&pn=1">
		<input type="hidden" value="0" name="advert">
		<input type="hidden" value="add" name="record_action">
		<input type="image" src="../images/interface/add.png" value="add" title="Add New" name="record_submit">
		</form></span>
		</th>
	</tr>
	<?php echo getProductsBy_AdvertsId();?>
</tbody></table></div>
<?php
}
?>