<?php
require_once './suppliers/functions2.php';

function get_new(){
$query='select distinct id from adverts where active="0"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}

function get_new_products(){
$query='select distinct id from products where checked_by_admin="0"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}

function get_new_comments(){
$query='select distinct id from statistics_adverts_visits where approved="0" and comment!=""';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}
function get_online(){
$query='select distinct sid from statistics where timest > "'.date('Y-m-d H:i:s', strtotime("-2 min")).'"';
	//$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	return mysql_num_rows($result);
}
//var_dump($_SESSION);
if(isset($_SESSION['adman'])){
	
?>
<div align="center">
<table border="0" class="tpmenu">
<tr>
<td>
<a href="../adman/" class="d-flex logo-height logo-svg">
<img id="logo" src="../images/logo_1.png" alt="Espravki.com - намери каквото търсиш">
</a>
</td>
<td>
	<ul class="list2"><li>Здравейте <?php echo $_SESSION['admin_name'];?></li></ul>
	</td>
<td>
		<ul class="list2"><li><a href="./index.php">Начало</a></li></ul>
	</td>

	<td>
		<ul class="list2"><li><a href="./index.php?page=new_adverts">Нови листинги <font color="orange">(<?php echo get_new();?>)</font></a></li></ul>
	</td>
	<td>
		<ul class="list2"><li><a href="./index.php?page=new_products">Нови Продукти <font color="orange">(<?php echo get_new_products();?>)</font></a></li></ul>
	</td>
		<td>
		<ul class="list2"><li>
		<?php
		if(get_new_comments() > 0){
		?>
		<a href="./index.php?page=comments&new=show">Нови коментари <font color="orange">(<?php echo get_new_comments();?>)</font></a>
		<?php
		}else{
		?>
		<a href="./index.php?page=comments">Коментари</a>
		
		<?php
		}?>
		</li></ul>
	</td>

	

	
	<td>
	<ul class="horizontalMenu-list">
		<li class="dropdown show">
		<a href="#" class="text-dark dropdown-toggle" role="button" aria-expanded="true" id="dropdownMenuLink" data-toggle="dropdown"><span> Менюта</span></a>
		<div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow" aria-labelledby="dropdownMenuLink">
			<ul class="sub-menu">
			<li><a href="./index.php?page=affiliates">Афилиейти</a></li>
			<?php
			if($_SESSION['adman']== '1'){
			?>
			<li><a href="./index.php?page=admins">Администратори</a></li>
			<?php
			}
			?>
			<li><a href="./index.php?page=adverts">Доставчици</a></li>
			<li><a href="./index.php?page=categories">Категории Търг-ци</a></li>
			<li><a href="./index.php?page=shop_categories">Категории Магазин</a></li>
			<li><a href="./index.php?page=customers">Клиенти</a></li>
			<li><a href="./index.php?page=mail-marketing">Мейл маркетинг</a></li>
			<li><a href="./index.php?page=mail_import">Мейл импорт</a></li>
			<li><a href="./index.php?page=products">Продукти</a></li>
			<li><a href="./index.php?page=articles">Статии</a></li>
			</ul>
			</div>
		</li>
	</ul>
	</td>
	<td>
		<ul class="list2"><li><a href="logout.php">Изход</a></li></ul>
	</td>
</tr>
</table>
<?php
}
else{
?>
<a href="../index.php">Home</a>
<?php
}
?>
</div>