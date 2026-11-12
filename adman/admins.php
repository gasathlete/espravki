<?php
//var_dump($_SESSION);
if(isset($_SESSION['adman']) && $_SESSION['adman']== '1'){
//require '../translation/lang.php';

if(!empty($_POST['submit'])){
$query='update admins set 
admin_name="'.$_POST['admin_name'].'",
user_group="'.$_POST['user_group'].'",
email="'.$_POST['email'].'",
active="'.$_POST['active'].'"';
if(!empty($_POST['code'])) $query.=',code="'.md5($_POST['code']).'"';
if(!empty($_POST['password'])) $query.=',password="'.md5($_POST['password']).'"';
$query.=' where id="'.$_POST['id'].'"';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
 
}
echo '<table class="table table-bordered border-top mb-0" width="100%"><tbody><tr>
	<th>#</th>
	<th>Admin name</th>
	<th>Admin level</th>
	<th>Email</th>
	<th>Password</th>
	<th>Active</th>
	<th>Administrator code</th>
	<th>Actions</th>
	</tr>';
$query='select * from admins';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);
for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_assoc($result);
	echo '<tr>
	<form name="admin" method="POST" action=""><input type="hidden" name="id" value="',$row['id'],'" />
	<td>',$i+1,'</td>
	<td><input type="text" name="admin_name" value="',$row['admin_name'],'" /></td>
	<td><input type="text" name="user_group" value="',$row['user_group'],'" /></td>
	<td><input type="text" name="email" value="',$row['email'],'" /></td>
	<td><input type="text" name="password" value="" /></td>
	<td><input type="text" name="active" value="',$row['active'],'" /></td>
	<td><input type="text" name="code" value="" /></td>
	<td align="center"><input class="btn btn-secondary ad-post" type="submit" name="submit" value="Update" /></td>
	</form></tr>';
	}

echo '</tbody></table>';

}
?>