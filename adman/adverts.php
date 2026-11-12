<?php
if(isset($_SESSION['adman'])){

//require_once './suppliers/lang/English.php';
require_once './suppliers/functions2.php';

if(isset($_POST['record_action']) && $_POST['record_action']=="delete"){
	echo $msg=delete_advert(addslashes($_POST['advert']));
}
if(isset($_GET['visible']) && !empty($_GET['aid'])){
	$query='update adverts set active="'.addslashes(htmlspecialchars($_GET['visible'])).'" where id="'.addslashes(htmlspecialchars($_GET['aid'])).'"';
	mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
}


$products_on_page = 30;
$sa='';
if(!empty($_POST['select_advert'])) $sa=intval($_POST['select_advert']);
elseif(!empty($_GET['select_advert'])) $sa=intval($_GET['select_advert']);

$sc='';
if(!empty($_POST['select_category'])) $sc=intval($_POST['select_category']);
elseif(!empty($_GET['select_category'])) $sc=intval($_GET['select_category']);
?>
<script language="JavaScript">
function setAdvert(o){
	document.getElementById('select_advert').value=o.value;
	document.getElementById('alabala').submit();
}
function setCategory(o){
	document.getElementById('select_category').value=o.value;
	document.getElementById('alabala').submit();
}

function search(o){
document.getElementById('search_advert').value=document.getElementById('adver_name').value;
//alert(document.getElementById('search_advert').value);
document.getElementById('alabala').submit();

}
</script>
<form name="alabala" id="alabala" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="GET">
<input type="hidden" name="page" value="adverts" />
<input type="hidden" name="select_advert" id="select_advert" value="<?php echo $sa;?>" />
<input type="hidden" name="select_category" id="select_category" value="<?php echo $sc;?>" />
<input type="hidden" name="search_advert" id="search_advert" value="<?php echo $_GET['search_advert'];?>" />

</form>
<?php
	$changes=array();
	if(isset($_GET['advert']) || isset($_POST['btn'])){

		$message='';
		if(isset($_GET['advert']) && $_GET['advert']>0) $advert=$_GET['advert'];
		elseif(isset($_POST['advert'])) $advert=$_POST['advert'];
		else $advert=0;
		

		switch(@$_POST['btn']){
			case 'save':{
				$msg=save_advert($advert);
				//var_dump($msg);
				$message=$msg[1];
				if($msg[0]==0) $advert=$msg[2];
				require_once './suppliers/template/add_adverts_tpl.php';
				break;
			}
			default: require_once './suppliers/template/add_adverts_tpl.php'; break;
		}
		
	}
	else{
		$_SESSION['back']=$_SERVER['REQUEST_URI'];
		
		$_POST['pager_base_link']=get_base_link();
		
		$query='select id,supplier_name from adverts';
		$result=mysql_query($query) or die(mysql_error());
		$num_rows=mysql_num_rows($result);
		
		$select_adverts='<select name="select_advert" onchange="setAdvert(this);"><option value="0">Всички листинги</option>';
		for($i=0;$i<$num_rows;$i++){
			$row=mysql_fetch_row($result);
			$selected='';
			if(!empty($sa) && $sa==$row[0]) $selected=' selected="selected"';
			$select_adverts.='<option value="'.$row[0].'"'.$selected.'>'.$row[1].'</option>';
		}
		$select_adverts.='</select>';
		
		$query='select distinct pc.id,bg_category from products_categories pc,adverts_to_product_categories atpc where atpc.category_id=pc.id';
		if(!empty($_GET['select_advert'])){
		$query.=' and atpc.advert_id="'.$_GET['select_advert'].'"';
		}
		
		
		
		//echo $query;
		
		$result=mysql_query($query) or die(mysql_error());
		$num_rows=mysql_num_rows($result);
		
		$select_category='<select name="select_category" onchange="setCategory(this);"><option value="0">Всички категории</option>';
		for($i=0;$i<$num_rows;$i++){
			$row=mysql_fetch_row($result);
			$selected='';
			if(!empty($sc) && $sc==$row[0]) $selected=' selected="selected"';
			$select_category.='<option value="'.$row[0].'"'.$selected.'>'.$row[1].'</option>';
		}
		$select_category.='</select>';
		
		if(!isset($sa) || !isset($sc) || !isset($_GET['asc']) || !isset($_GET['desc'])){
		$query='select a.id,active, supplier_name, a.description,small_image,added_date FROM 
		adverts a';
		}
		
		$query='select a.id, supplier_name,mail,a.meta_description,small_image,added_date,selected_plan,bg_category,active,affiliate_id,referral FROM 
		adverts a,adverts_to_product_categories atpc,products_categories pc where a.id=atpc.advert_id and atpc.category_id=pc.id ';
		
		if(!empty($_GET['select_advert']) && is_numeric($_GET['select_advert'])){
		$query.=' and a.id="'.$_GET['select_advert'].'"';
		if(!empty($_GET['select_category'])){
		$query.=' and atpc.category_id="'.$_GET['select_category'].'" ';
			}
		}
		if(empty($_GET['select_advert']) && !empty($_GET['select_category'])){
		$query.=' and atpc.category_id="'.$_GET['select_category'].'" ';
		}

		if(!empty($_GET['search_advert'])){
		$query.=' and supplier_name LIKE "%'.$_GET['search_advert'].'%"';
		}
	
		$link='';
		//if(!empty($sa) && is_numeric($sa)){
			//$query.=' and a.id="'.intval($sa).'"';
			//$link.='&select_advert='.intval($sa);
		//}
		
		if(!empty($sc) && is_numeric($sc)){
			$query.=' and category_id="'.intval($sc).'"';
			$link.='&select_category='.intval($sc);
		}
		
		$_POST['pages_count']=get_pages_count($query);
		
		if(isset($_GET['asc'])){
			$changes[$_GET['asc']]['sort']['link']="index.php?page=adverts&desc=".$_GET['asc'].$link;
			$changes[$_GET['asc']]['sort']['icon']="asc.png";
			$sort_field=$_GET['asc'];
			$method="ASC";
		}
		elseif(isset($_GET['desc'])){
			$changes[$_GET['desc']]['sort']['link']="index.php?page=adverts&asc=".$_GET['desc'].$link;
			$changes[$_GET['desc']]['sort']['icon']="desc.png";
			$sort_field=$_GET['desc'];
			$method="DESC";
		}
		else{
			$changes['category']['sort']['link']="index.php?page=adverts&desc=bg_category".$link;
			$changes['category']['sort']['icon']="asc.png";
			$sort_field='bg_category';
			$method="ASC";
		}
		//include($prev_path.'main/pager_form.php');
		$query.=' group by a.id order by id desc';
		//echo $query;
		if(isset($_GET['asc']) or isset($_GET['desc']))
		$query.=' ORDER BY `'.addslashes($sort_field).'` '.$method;
		//$query.=add_query_limits($_GET['pn']);
		//echo $query;
		
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$count=mysql_num_rows($result);
	
	echo '<div class="table-responsive card-body">
	<input disabledrag class="input_txt" type="text" id="adver_name" name="adver_name" value="'.@$_GET['search_advert'].'" placeholder="Име на фирмата"/>';
		echo $select_adverts,'&nbsp;&nbsp;',$select_category;
	echo '<input disabledrag onclick="search();" class="btn btn-secondary ad-post" type="submit" id="search_supplier" name="submit" value="Търси" />
	</div>';
		
			$pages=intval(ceil($count/$products_on_page));
			$pn=1;
			if(!empty($_POST['pn'])){
			if(is_numeric($_POST['pn'])){
				$pn=intval($_POST['pn']);
				if($pn>$pages) $pn=$pages;
				}
			}
			$limit=($pn-1)*$products_on_page;
		
		$query.=' limit '.$limit.','.$products_on_page;
		//echo $query;
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$num_rows=mysql_num_rows($result);
		echo '<div class="table-responsive card-body border-0 dragscroll"><table class="table table-bordered border-top mb-0"><tbody>
		<tr>
		<th>#</th>
		<th>Заглавие</th>
		<th>Емейл</th>
		<th>Дата на добавяне</th>
		<th>Избран план</th>
		<th>Категория</th>
		<th>От Афилиейт</th>
		<th>Референция</th>
		<th width="120">Действия<span style="float:right;"><form method="post" action="index.php?page=adverts&advert=0&pn=1">
		<input type="hidden" value="0" name="advert">
		<input type="hidden" value="add" name="record_action"/>
		</form></span></th>
		</tr>';
		if($num_rows > 0){
		
		for($i = 0; $i < $num_rows; $i++) {
		$row=mysql_fetch_row($result);
		if ($i % 2 == 0) {
		  $class="class='even'";
		}else $class="class='odd'";
		if($row[8]==0){$v1='<img src="../images/interface/activate.png" />'; $v2=1;}
		else{
		$v1='<img src="../images/interface/desactivate.png" />'; $v2=0;
		}
		
		echo '<tr ',$class,'>
		<td>',$i+1,'</td>
		<td>',$row[1],'</td>
		<td>',$row[2],'</td>
		<td>',$row[5],'</td>
		<td>',$lang['plans_array'][$row[6]],'</td>
		<td>',$row[7],'</td>
		<td align="center">',$row[9],'</td>
		<td align="center">',$row[10],'</td>
		<td align="center">
		<span class="laction"><a href="index.php?page=edit_advert&aid='.$row[0].'&visible='.$v2.'">',$v1,'</a></span>
		
		<span class="laction">
		<form method="post" action="" >
		<input type="hidden" value="',$row[0],'" name="advert" />
		<input type="hidden" value="update" name="record_action" />
		<a href="index.php?page=edit_advert&aid='.$row[0].'">
		<img src="../images/interface/update.png" title="update" /></a></form></span>
		<span class="laction">
		<form method="post" action="" name="delform',$row[0],'" id="delform',$row[0],'">
		<input type="hidden" value="',$row[0],'" name="advert" />';
		if($_SESSION['adman']=="1"){
		echo '<input type="hidden" value="delete" name="record_action" />';
		echo '<img onclick="return delete_ask2(\'',$row[0],'\');" src="../images/interface/delete.png" title="delete" />';
		}
		echo '</form></span>
		
		</td>		
		</tr>';
		
			}		
		}
		echo '</tbody></table></div>';
		//echo table_creator($query,'tbl_adverts',$actions,$changes,$language,1);
		
		if($pages>1){
		echo '
		<form name="advertform" id="advertform" method="post" action="" >
		<input type="hidden" id="pn" value="',$pn,'" name="pn" />
		</form>
		<div class="pages"><div class="rows">';
		if($pn>1){
		echo '<a class="pagen first" data="1"><span class="first">Първа</span></a>';
		echo '<a class="pagen" data="',($pn-1),'"><span>&lt;&lt;</span></a>';
		}
		for($i = max(1, $pn - 5); $i <= min($pn + 5, $pages); $i++){
			if($pn==$i) echo '<a class="pagen activpn" data="',$i,'"><span class="activpn">',$i,'</span></a>';
			else echo '<a class="pagen" data="',$i,'"><span>',$i,'</span></a>';
		
		}
		if($pn<$pages){
		echo '&nbsp;&nbsp;<a class="pagen" data="',($pn+1),'"><span>&gt;&gt;</span></a>';
		echo '&nbsp;&nbsp;<a class="pagen last" data="',($pages),'"><span class="last">Последна</span></a>';
		}
		echo '</div></div>';
		}
				
		echo set_navigation('bg');
	}

}else{
	@header("Location:index.php");
	exit(0);
}
?>