<?php
function getcustomers($query,$count,$search){
global $lang;
//echo $query;
$type='desc';
if(!empty($_GET['type'])){
if($_GET['type']=='asc') $type='desc';else $type='asc';
}else $_GET['type']='asc';
$so=$_GET['type'];
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	echo '<div style="margin:10px;padding: 5px;">',$lang['search_h1_before'],' : '.$count.' ',$lang['meta_search']['results'],' '.$search.'</div>';
	
	echo '
	<div class="table-responsive offset-top-32 mtb20 mb50 dragscroll">
	<table class="table table-striped text-left" width="100%" border="0" cellspacing="0"><tbody><tr>
	<th class="text-left success mw20 c1"><a data="',$type,'" onclick="setorder(this,a=\'added_date\');">#</a></th>
	<th class="text-center success c2"><a data="',$type,'" onclick="setorder(this,a=\'customer_names\');">',$lang['cnames'],'</a></th>
	<th class="text-center success c3">',$lang['email'],'</th>
	<th class="text-left success c4">',$lang['city'],'</th>
	<th class="text-left success c5">',$lang['phone'],'</th>
	<th class="text-center success c7">Дата регистрация</th>
	<th class="text-center success c8">',$lang['status'],'</th>
	<th class="text-center success c8">Бр. листинги</th>
	<th class="text-center success c8">Бр. продукти</th>
	<th class="text-center success c9">Действие</th>
	</tr>';
	if($num_rows > 0){
	
	$totalbalance = 0;
	$transactions = 0;
	$totaltransactions = 0;
	$registered='';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	$totalbalance+=$row[6];
	//$transactions = check_user_balance($row[0]);
	$totaltransactions+=$transactions;
	$active = $lang['active_status'][$row[5]];
	
	if($row[8]=="0000-00-00") $date=' - ';else $date=date("d-m-Y", strtotime($row[8]));
	echo '<tr>
	<td class="c1" align="center">'.($i+1).'</td>
	<td disabledrag class="c2">'.$row[1].'</td>
	<td disabledrag class="c3">'.$row[2].'</td>
	<td disabledrag class="c4">'.$row[4].'</td>
	<td disabledrag class="c5">'.$row[3].'</td>
	<td disabledrag class="c7" align="center">'.$row[7].'</td>
	<td disabledrag class="c8" align="center">'.$active.'</td>
	<td disabledrag class="c8" align="center">'.count_adverts($row[0]).'</td>
	<td disabledrag class="c8" align="center">'.count_products($row[0]).'</td>
	<td disabledrag class="c9" align="center">
	<a class="btn btn-primary btn-sm smbtn" href="./index.php?page=customerprofile&cid='.$row[0].'">',$lang['view'],'</a></td>
	</tr>';	
	}

	}else echo '<tr><td align="center" colspan="10">',$lang['no_results'],'</td></tr>';
	echo '</tbody></table></div>';
}

if(isset($_SESSION['adman'])){
$sc='';
if(!empty($_POST['city_search'])) $sc=$_POST['city_search'];
elseif(!empty($_GET['city_search'])) $sc=$_GET['city_search'];


if($_SESSION['admin_id'] == 1){
/*
$query = 'select * from group_members where member_mail!="" order by member_name asc';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);
	
	$query2 = 'select * from customers where email="'.$row['member_mail'].'"';
$result2=mysql_query($query2) or die(send_error($query2,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows2=mysql_num_rows($result2);
//if($num_rows2 < 1) echo $row['member_name'],' - няма регистрация<br/>';

}
}*/
//obnowqwaen na bazata
/*
$query = 'select * from transactions where member_id > 0 and `prihod` < 1 and `razhod` < 1 order by member_id asc';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);

if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);

$cid = get_cid_from_member($row['member_id']);
if($cid > 0){ 
//echo $row['member_id'],' - ',$row['member_mail'];echo '<br/>';

echo $gmrquery = 'SELECT * FROM `group_members_reservations` WHERE id="'.$row['reservation_id'].'"';echo '<br/>';
$gmrresult=mysql_query($gmrquery) or die(send_error($gmrquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$gmrnum_rows=mysql_num_rows($gmrresult);
if($gmrnum_rows > 0){
	$gmrrow=mysql_fetch_assoc($gmrresult);
	//echo $gmrrow['anulated'];echo '<br/>';
	if($gmrrow['anulated'] > 0){
	$prihod = $gmrrow['training_cost'];
	$razhod = '0.00';
	}else{
	$prihod = '0.00';
	$razhod = $gmrrow['training_cost'];
		}
	
	echo $update = 'update transactions set customer_id = "'.$cid.'", prihod = "'.$prihod.'",razhod = "'.$razhod.'" where reservation_id = "'.$row['reservation_id'].'"';echo '<br/>';
	$updateresult=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	}
}
	}
}*/
}

?>
<form name="alabala" id="alabala" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="GET">
<input type="hidden" name="page" value="customers" />
<input type="hidden" name="pn" value="1" />
<input type="hidden" name="city_search" id="city_search" value="<?php echo @$sc;?>" />
<input type="hidden" name="order" id="order" value="" />
<input type="hidden" name="customersearch" id="customersearch" value="" />
<input type="hidden" name="type" id="type" value="<?php echo @$so;?>" />

</form>
<?php
$products_on_page=100;

echo '<div class="control-group form-group">';
$query='select city from customers group by city';
$result=mysql_query($query) or die($query);
$num_rows=mysql_num_rows($result);
$city_search='<div class="form-group ib">
<label class="form-label text-dark">Град</label>
<select style="padding: 5px;" class="form-control" name="city_search" onchange="setAdvert(this);"><option value="0">От всички градове</option>';
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$selected='';
		if(isset($sc) && $sc==$row[0]) $selected=' selected="selected"';
		$city_search.='<option value="'.$row[0].'"'.$selected.'>'.$row[0].'</option>';
	}
	$city_search.='</select></div>';
	
	
echo $city_search;

?>
<div class="form-group ib ml-3">
<label class="form-label text-dark">Име на клиента</label>
<input placeholder="търсене на клиент по име, фамилия или email.." id="customers" size="100" type="text" class="form-control autosuggest" value="" />
</div>
<div class="form-group ib">
<img id="csearch" align="right" src="<?php WebSite?>/images/interface/search.png" />
</div></div>
<?php
		$search='';
		$query='SELECT c.id, customer_names, c.customer_email, c.customer_phone, c.city, c.active, c.customer_address, c.registered_date FROM customers c where customer_names !=""';
		if(!empty($_GET['customersearch'])){
		$search=' при търсене на '.$_GET['customersearch'];
		$csearch = explode(" ",$_GET['customersearch']);
		if(sizeof($csearch) > 1){
		$query.=" and (customer_names LIKE '%".mysql_real_escape_string($csearch[0])."%')";
		}else{
		if(is_numeric($csearch[0])){
		$query.=" and phone LIKE '%".mysql_real_escape_string($csearch[0])."%'";
		}else $query.=" and (customer_names LIKE '%".mysql_real_escape_string($csearch[0])."%' or customer_email LIKE '%".mysql_real_escape_string($csearch[0])."%')";
			}
		}
		if(isset($_GET['city_search']) && !empty($_GET['city_search'])) $query.=' and city="'.$sc.'"';
		if(!empty($_GET['id'])) $query.=' and id="'.$_GET['id'].'"';
		if(!empty($_GET['order'])){
		if($_GET['type'] == 'asc'){
		$query.= ' group by c.id ORDER BY '.$_GET['order'].' asc ';
		}else {
		$query.= ' group by c.id ORDER BY '.$_GET['order'].' desc ';
			}
		}
		else{
		$query.=' group by c.id ORDER BY registered_date DESC ';
		}
	//echo $query;
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$count=mysql_num_rows($result);
		if($count>0){
		$pages=intval(ceil($count/$products_on_page));
		$pn=1;
		if(isset($_GET['pn'])){
			$pn=intval($_GET['pn']);
			if($pn>$pages) $pn=$pages;
		}
		$limit=($pn-1)*$products_on_page;
		
		if(!isset($_GET['customersearch'])) $query.=' LIMIT '.$limit.','.$products_on_page;
		//pri serach otpada order by
		}
		//echo $query;
		getcustomers($query,$count,$search);
		
		if($count>0){
		echo '<div class="ib w100 tac">';
		if($pages>1){
			echo '<div class="center-block ib wa text-center"><ul class="pagination mb-0 mt-1">';
			if(isset($_GET['city_search'])) $select_c='&city_search='.$_GET['city_search'];
			$url='./index.php?page=customers'.$select_c.$select_f;
			if(isset($_GET['city'])) $url.='&city='.$_GET['city'];
			$disabled='disabled';
			if($pn>1){
			$disabled='';
			}
			echo '<li class="page-item page-prev ',$disabled,'"><a class="page-link" href="',$url,'&pn=1">First</a></li>';
			echo '<li class="page-item ',$disabled,'"><a class="page-link" href="',$url,'&pn=',($pn-1),'">&lt;&lt;</a></li>';
			for($i = max(1, $pn - 5); $i <= min($pn + 5, $pages); $i++){
				if($pn==$i) echo '<li class="page-item active"><a class="page-link" href="',$url,'&pn=',$i,'">',$i,'</a></li>';
				else echo '<li class="page-item"><a class="page-link" href="',$url,'&pn=',$i,'">',$i,'</a></li>';
			
			}
			$disabled2='disabled';
			if($pn<$pages){
			$disabled2='';
			}
			echo '<li class="page-item ',$disabled2,'"><a class="page-link" href="',$url,'&pn=',($pn+1),'">&gt;&gt;</a></li>';
			echo '<li class="page-item ',$disabled2,'"><a class="page-link last" href="',$url,'&pn=',($pages),'">Last</a></li>';
		echo '</ul></div>';
		}
		echo '</div>';
	
	}
}else{
	@header("Location:index.php");
	exit(0);
}
?>
<script>
function setAdvert(o){
	document.getElementById('city_search').value=o.value;
	document.getElementById('alabala').submit();
}

function setorder(o,a){
var type = $(o).attr('data');
var order = $(o).attr('class');
	document.getElementById('type').value=type;
	document.getElementById('order').value=a;
	document.getElementById('city_search').value="<?php echo @$_GET['city_search'];?>";
	document.getElementById('alabala').submit();
}
</script>