<?php
if(isset($_SESSION['adman'])){


$query='SELECT p.id as pid, p.title, p.price, p.image as pimage, p.promo_price, p.category_name,p.addeddate,p.active,p.deleted,p.sold,p.checked_by_admin, p.category_id, a.id as aid, a.supplier_name, shpc.id, shpc.bg_category  
FROM products p, adverts a, shop_products_categories shpc where 
p.advert_id = a.id and p.category_id = shpc.id';
if(isset($_GET['category_id']) && $_GET['category_id'] > 0) $query.= ' and p.category_id = "'.mysql_real_escape_string($_GET['category_id']).'"';
if(isset($_GET['product_name']) && !empty($_GET['product_name'])) $query.= ' and title LIKE "%'.$_GET['product_name'].'%"';
if(isset($_GET['trader']) && !empty($_GET['trader'])) $query.= ' and supplier_name LIKE "%'.$_GET['trader'].'%"';

$query.= ' group by p.id order by p.addeddate DESC limit 300';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);
echo '<h1>Продукти в магазина</h1>';

echo '<div class="table-responsive card-body">
<form name="products" id="products" action="',$_SERVER['REQUEST_URI'],'" method="GET">
<input type="hidden" name="page" value="products" />
<input disabledrag class="form-control ib w30" type="text" id="product_name" name="product_name" value="'.@$_GET['product_name'].'" placeholder="Име на продукт"/>
<input disabledrag class="form-control ib w30" type="text" id="trader" name="trader" value="'.@$_GET['trader'].'" placeholder="Име на търговец"/>';

$catquery = 'select shpc.id, shpc.bg_category 
from products p, shop_products_categories shpc where p.category_id = shpc.id group by shpc.id order by shpc.bg_category asc';
$catresult=mysql_query($catquery) or die(send_error($catquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$cat_num_rows=mysql_num_rows($catresult);
echo '<select class="form-control ib w30" name="category_id"><option value="0">Всички категории</option>';
if($cat_num_rows > 0){
	for($i=0;$i<$cat_num_rows;$i++){
	$catrow=mysql_fetch_row($catresult);
	$selected='';
	if(!empty($_GET['category_id']) && $_GET['category_id']==$catrow[0]) $selected=' selected="selected"';
			
	echo '<option value="'.$catrow[0].'"'.$selected.'>'.$catrow[1].'</option>';
	}
}
echo '</select>';

	echo '<input disabledrag class="btn btn-secondary ad-post" type="submit" id="search_p" name="submit" value="Търси" />
	</form>
	</div>';
	
echo '<div class="table-responsive card-body border-0 dragscroll">
<h3>Намерени са ',$num_rows,' продукта</h3>
<table class="table table-bordered border-top mb-0"><tbody>
		<tr>
		<th>#</th>
		<th>Снимка</th>
		<th>Заглавие</th>
		<th>Търговец</th>
		<th>Категория</th>
		<th>Цена / Промо цена</th>
		<th>Дата на добавяне</th>
		<th>Статус</th>
		<th>Действия</th>
		</tr>';
if($num_rows > 0){
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_assoc($result);
	
	$status = '<span class="badge-danger p-1">Не е Активен</span>';
	if($row['active'] > 0 && $row['deleted'] < 1 && $row['sold'] < 1 && $row['checked_by_admin'] > 0) $status = '<span class="badge-success p-1">Активен</span>';
	echo '
	<tr>
	<td>',($i+1),'</td>
	<td><img width="150" src="../images/products/',$row['pimage'],'"/></td>
	<td disabledrag>',$row['title'],'</td>
	<td disabledrag class=""><a target="_blank" href="index.php?page=edit_advert&aid='.$row['aid'].'">',$row['supplier_name'],'</a></td>
	<td disabledrag><div id="cat_',$row['pid'],'" class="ib w100">',$row['category_name'],'</div>
	<div class="ib w100"><input type="number" class="form-control set_cat" value="',$row['category_id'],'" id="',$row['pid'],'"/></div></td>
	<td>',$row['price'],' / ',$row['promo_price'],'</td>
	<td>',date('d-m-Y H:i:s',strtotime($row['addeddate'])),'</td>
	<td class="wsnw tac">',$status,'</td>
	<td disabledrag align="center"><a target="_blank" class="btn btn-secondary" href="index.php?page=edit_product&pid='.$row['pid'].'">Виж</a></td>
	</tr>
	';
		}
	}
	echo '</table></div>';
}
?>