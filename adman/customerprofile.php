<?php

if(!empty($_SESSION['adman'])){
$cid = intval($_GET['cid']);
if(!empty($cid)){

//var_dump($_SESSION);
//var_dump($_POST);
$msg='';
if(isset($_POST['customersave'])){
if(empty($_POST['customer_names'])) $msg=$lang['enter_names'];
if(empty($_POST['email']) || !is_mail($_POST['email'])) $msg=$lang['enter_mail'];
if(empty($msg)){

if(!empty($_POST['password'])) $_POST['realp'] = $_POST['password'];
$active = 0;
if(isset($_POST['active'])) $active = 1;
$query='update customers set 
customer_names="'.mysql_real_escape_string($_POST['customer_names']).'",
customer_email="'.mysql_real_escape_string($_POST['email']).'",
customer_phone="'.mysql_real_escape_string($_POST['phone']).'",
city="'.mysql_real_escape_string($_POST['city']).'",
customer_address="'.mysql_real_escape_string($_POST['customer_address']).'",
postcode="'.mysql_real_escape_string($_POST['postcode']).'",
about_me="'.mysql_real_escape_string($_POST['about_me']).'",
facebook="'.mysql_real_escape_string($_POST['facebook']).'",
linkedin="'.mysql_real_escape_string($_POST['linkedin']).'",
reg_code="'.mysql_real_escape_string($_POST['reg_code']).'",
from_affiliate="'.mysql_real_escape_string($_POST['from_affiliate']).'",
facebook="'.mysql_real_escape_string($_POST['facebook']).'",
active="'.mysql_real_escape_string($active).'"';

if(!empty($_POST['password'])){
$query.= ' , password = "'.md5($_POST['password']).'", realp="'.$_POST['password'].'"';
}

$query.= ' where id="'.mysql_real_escape_string($cid).'"';
mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$msg = $lang['cust_saved'];

//var_dump($_POST);
if(isset($_POST['send_login'])){

$advert = 'select a.supplier_name, a.link_name, a.sellbusiness, pc.url from adverts a, adverts_to_product_categories atpc, products_categories pc  
where a.customer_id = "'.mysql_real_escape_string($cid).'" and a.id = atpc.advert_id and atpc.category_id = pc.id group by a.id';
$advertresult=mysql_query($advert) or die(send_error($advert,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$advertnum_rows=mysql_num_rows($advertresult);

$html = '';

if($advertnum_rows > 0){
$html.='Вашите обяви може да видите тук:<br/>';
for($i=0;$i<$advertnum_rows;$i++){
$advertrow=mysql_fetch_assoc($advertresult);
$html.=$advertrow['supplier_name'].'<br/>';
if($advertrow['sellbusiness'] == "1") $html.= '<a href="'.WebSite.'/business-for-sale/'.$advertrow['url'].'/'.$advertrow['link_name'].'">'.WebSite.'/business-for-sale/'.$advertrow['url'].'/'.$advertrow['link_name'].'</a><br/>';
else $html.= '<a href="'.WebSite.'/business-directory/'.$advertrow['url'].'/'.$advertrow['link_name'].'">'.WebSite.'/business-directory/'.$advertrow['url'].'/'.$advertrow['link_name'].'</a><br/>';
	}
	
}

$subject = 'Данни за вход в '.mail_name;
$body= 'Уважаеми '.$_POST['customer_names'].',<br/>
Това са вашите данни за управление на профила ви в '.mail_name.':<br/>
<a target="_blank" href="'.WebSite.'/userlogins/">'.WebSite.'/userlogins/</a><br/>
Потребител: '.$_POST['email'].'<br/>
Парола: '.$_POST['realp'].'<br/>

Чрез тези данни ще можете да управлявате вашите обяви, поръчки и т.н<br/>
'.$html.'<br/>
С Уважение,<br/>Екипът на '.mail_name;
$params=array(
	"content_type"=>"text/html",
	"replay_to"=>official_mail_sender,
	"from_name"=>official_mail_sender_name,
	"from_email"=>official_mail_sender,
	"to"=>$_POST['email'],
	"subject"=>$subject,
	"body"=>$body
	);
	send_mail($params);
		}
	}
}



$query = 'select * from customers where id="'.mysql_real_escape_string($cid).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
if(mysql_num_rows($result) > 0){
$row=mysql_fetch_array($result);
$_POST['id']=$row['id'];
$_POST['customer_names']=$row['customer_names'];
$_POST['email']=$row['customer_email'];
$_POST['phone']=$row['customer_phone'];
$_POST['city']=$row['city'];
$_POST['customer_address']=$row['customer_address'];
$_POST['postcode']=$row['postcode'];
$_POST['about_me']=$row['about_me'];
$_POST['facebook']=$row['facebook'];
$_POST['linkedin']=$row['linkedin'];
$_POST['reg_code']=$row['reg_code'];
$_POST['from_affiliate']=$row['from_affiliate'];
$_POST['ip']=$row['ip'];
$_POST['profile_image']=$row['profile_image'];
$_POST['realp']=$row['realp'];

$_POST['added_date']=$row['registered_date'];
$_POST['active']=$row['active'];


echo '
<section class="section-80 section-md-110 col-sm-10 m-auto">
    <div class="shell">
	<div class="table text-right"><a class="btn btn-primary btn-sm" href="./index.php?page=customers">Назад</a></div>
  <div class="offset-top-22 offset-md-top-42">
	<div class="range range-xs-center p-5">
	  <div class="cell-md-8">
<div class="msg tac">',$msg,'</div>
<div class="form-group mtb10 ib w100"><h2>',$lang['profile_of'],' ',$_POST['customer_names'],'</h2></div>
<form method="POST" action="" class="tac" id="cform" name="cform">';

$image = '<img src="../images/no_advert_image.jpg"/>';
if(!empty($_POST['profile_image'])) $image = '<img src="../images/users/'.$_POST['profile_image'].'"/>';
echo '<div class="form-group mtb10 ib col-sm-10">
<label for="customer_names" class="form-label form-label-outside ib tal w100 mh rd-input-label">Снимка</label>
',$image,'
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="customer_names" class="form-label form-label-outside ib tal w100 mh rd-input-label">',$lang['cnames'],'</label>
<input autocomplete="off" id="customer_names" type="text" name="customer_names" data-constraints="@Required" value="',$_POST['customer_names'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="email" class="form-label form-label-outside ib tal w100 mh rd-input-label">',$lang['email'],'</label>
<input autocomplete="off" id="email" type="text" name="email" data-constraints="@Required" value="',$_POST['email'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="phone" class="form-label form-label-outside ib tal w100 mh rd-input-label">',$lang['phone'],'</label>
<input autocomplete="off" id="phone" type="text" name="phone" data-constraints="@Required" value="',$_POST['phone'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="city" class="form-label form-label-outside ib tal w100 mh rd-input-label">',$lang['city'],'</label>
<input autocomplete="off" id="city" type="text" name="city" data-constraints="@Required" value="',$_POST['city'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="postcode" class="form-label form-label-outside ib tal w100 mh rd-input-label">',$lang['postcode'],'</label>
<input id="postcode" type="text" name="postcode" data-constraints="@Required" value="',$_POST['postcode'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="customer_address" class="form-label form-label-outside ib tal w100 mh rd-input-label">Адрес</label>
<input autocomplete="off" id="customer_address" type="text" name="customer_address" data-constraints="@Required" value="',$_POST['customer_address'],'" class="form-control form-control-gray" />
</div>



<div class="form-group mtb10 ib col-sm-5">
<label for="facebook" class="form-label form-label-outside ib tal w100 mh rd-input-label">Facebook</label>
<input id="facebook" type="text" name="facebook" data-constraints="@Required" value="',$_POST['facebook'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="linkedin" class="form-label form-label-outside ib tal w100 mh rd-input-label">Linkedin</label>
<input id="linkedin" type="text" name="linkedin" data-constraints="@Required" value="',$_POST['linkedin'],'" class="form-control form-control-gray" />
</div>


<div class="form-group mtb10 ib col-sm-5">
<label for="reg_code" class="form-label form-label-outside ib tal w100 mh rd-input-label">Код регистрация</label>
<input id="reg_code" type="text" name="reg_code" data-constraints="@Required" value="',$_POST['reg_code'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="from_affiliate" class="form-label form-label-outside ib tal w100 mh rd-input-label">Афилиейт <a class="text-orange" target="_blank" href="index.php?page=affiliates&edit=">ID</a></label>
<input id="from_affiliate" type="text" name="from_affiliate" data-constraints="@Required" value="',$_POST['from_affiliate'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="realp" class="form-label form-label-outside ib tal w100 mh rd-input-label">Парола</label>
<input autocomplete="off" id="realp" type="text" name="realp" data-constraints="@Required" value="',$_POST['realp'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="password" class="form-label form-label-outside ib tal w100 mh rd-input-label">Смени паролата</label>
<input autocomplete="off" id="password" type="text" name="password" data-constraints="@Required" value="" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="ip" class="form-label form-label-outside ib tal w100 mh rd-input-label">IP регистрация</label>
<input autocomplete="off" readonly id="ip" type="text" name="ip" data-constraints="@Required" value="',$_POST['ip'],'" class="form-control form-control-gray" />
</div>

<div class="form-group mtb10 ib col-sm-5">
<label for="added_date" class="form-label form-label-outside ib tal w100 mh rd-input-label">',$lang['reg_date'],'</label>
<input autocomplete="off" readonly id="added_date" type="text" name="added_date" data-constraints="@Required" value="',date('d-m-Y H:i:s', strtotime($_POST['added_date'])),'" class="form-control form-control-gray" />
</div>



<div class="form-group mtb10 ib col-sm-10">
<label for="about_me" class="form-label form-label-outside ib tal w100 mh rd-input-label">За мен</label>
<textarea id="about_me" name="about_me" value="',$_POST['about_me'],'" class="form-control form-control-gray">',$_POST['about_me'],'</textarea>
</div>
';


	$checkedslider = '';
	if($_POST['active'] == 1) $checkedslider = 'checked';
	echo '<div class="form-group bg-white mtb10 ib col-sm-10">
	<div class="checkbox checkbox-info ">
			<label class="custom-control mt-4 custom-checkbox">
				<input id="',$_POST['id'],'" ',$checkedslider,' name="active" value="" type="checkbox" class="custom-control-input">
				<span class="custom-control-label text-dark pl-2">',$lang['activate_status'][$_POST['active']],'</span>
			</label>
		</div>
	</div>
	';
?>

<div class="form-group mtb10 bg-white ib col-sm-10 tal">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input name="send_login" id="send_login"  <?php if(isset($_POST['send_login'])) echo 'checked';?> value="" type="checkbox" class="custom-control-input" />
				<span class="custom-control-label text-dark pl-2">Изпрати логин данни на клиента</span>
			</label>
		</div>
	</div>
<?php
echo '
<div class="form-group mtb10 ib col-sm-10">
<input type="submit" value="',$lang['save'],'" class="btn btn-block btn-secondary m-auto wa" name="customersave" />
</div>


</form>
</div>
</div>

</div></div>
</section>
';


}else echo 'Няма намерени записи';

}else echo 'Няма намерени записи';
}
?>