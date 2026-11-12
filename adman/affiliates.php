<?php
//require '../translation/lang.php';
function getaffiliates($query){
global $lang;
$type='desc';
$msg='';
if(!empty($_GET['type'])){
if($_GET['type']=='asc') $type='desc';else $type='asc';
}else $_GET['type']='asc';
$so=$_GET['type'];
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$num_rows=mysql_num_rows($result);
	echo '<div class="table-responsive card-body border-0">
	<div style="margin:10px;padding: 5px;">Намерени са : '.$num_rows.' партньор/и</div><div class="ib w100 tar"><a class="btn btn-secondary" href="./index.php?page=affiliates&new=1">Добави нов</a></div>';
	echo '<table class="table table-bordered border-top mb-0" width="100%" border="0" cellspacing="0"><tbody><tr>
	<th width="40">#</th>
	<th width="200">Имена</th>
	<th width="150">Емейл</th>
	<th width="80">Телефон</th>
	<th width="80">Град</th>
	<th width="80">Website</th>
	<th width="115">Комисионна %</th>
	<th width="110">Активен</th>
	<th width="80">Код на афилиейта</th>
	<th width="80">Действия</th></tr>';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	echo '<tr>
	<td align="center">'.($i+1).'</td>
	<td><input readonly="readonly" data="'.$row[0].'" class="names invis" type="text" value="'.$row[1].'" name="firstname'.$row[0].'"/>
	<input readonly="readonly" data="'.$row[0].'" class="names invis" type="text" value="'.$row[2].'" name="last_name'.$row[0].'"/></td>
	<td><input readonly="readonly" type="text" data="'.$row[0].'" class="invis" value="'.$row[3].'" name="email'.$row[0].'"/></td>
	<td><input readonly="readonly" type="text" data="'.$row[0].'" class="invis" value="'.$row[4].'" name="phone'.$row[0].'"/></td>
	<td><input readonly="readonly" type="text" data="'.$row[0].'" class="invis" value="'.$row[5].'" name="city'.$row[0].'"/></td>
	<td align="center">'.$row[7].'</td>
	<td align="center"><input readonly="readonly" data="'.$row[0].'" class="invis" type="text" value="'.$row[8].'" name="commission'.$row[0].'"/></td>
	<td align="center">'.$row[9].'</td><td align="center">'.$row[10].'</td>
	<td align="center"><img data="'.$row[0].'" id="edit'.$row[0].'" class="editaff" title="Редактирай" src="../images/interface/update.png" /></td></tr>';	
	}
	echo '</tbody></table></div>';	
}



if(isset($_SESSION['adman'])){
//dobawqne na now affiliate
if(!empty($_GET['new']) && empty($_POST['affid'])){
//var_dump($_POST);
if(isset($_POST['savenew'])){
if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['affiliate_code']) || empty($_POST['commission']) || empty($_POST['city'])){
$msg = 'Някои от задължителните полета са Празни ! Моля корегирайте !';
}else{

$_SESSION['pcommission']=$_POST['commission'];

if(empty($_POST['aff_pass'])) $_POST['aff_pass'] = mt_srand(6);
$query='insert into affiliates set 
firstname="'.mysql_real_escape_string($_POST['firstname']).'",
lastname="'.mysql_real_escape_string($_POST['lastname']).'",
email="'.mysql_real_escape_string($_POST['email']).'",
aff_pass = "'.md5($_POST['aff_pass']).'",
aff_real_pass = "'.mysql_real_escape_string($_POST['aff_pass']).'",
telephone="'.mysql_real_escape_string($_POST['telephone']).'",
company="'.mysql_real_escape_string($_POST['company']).'",
website="'.mysql_real_escape_string($_POST['website']).'",
city="'.mysql_real_escape_string($_POST['city']).'",
postcode="'.mysql_real_escape_string($_POST['postcode']).'",
address="'.mysql_real_escape_string($_POST['address']).'",
affiliate_code="'.mysql_real_escape_string($_POST['affiliate_code']).'",
commission="'.mysql_real_escape_string($_POST['commission']).'",
bank_name="'.mysql_real_escape_string($_POST['bank_name']).'",
bank_swift_code="'.mysql_real_escape_string($_POST['bank_swift_code']).'",
bank_account_number="'.mysql_real_escape_string($_POST['bank_account_number']).'",
bank_account_name="'.mysql_real_escape_string($_POST['bank_account_name']).'",
active="'.mysql_real_escape_string($_POST['status']).'" ';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$last_id=mysql_insert_id();
if(isset($_POST['alert_affiliate'])){
	//izprashtame dannite za login na affiliate-a
	$subject = $lang['affiliate_registration'].' '.hostname.'';
	$body = $lang['dear'].' '.$_POST['firstname'].' '.$_POST['lastname'].',<br />'.
	$lang['details'].' '.$_POST['commission'].' '.$lang['detailsa'].'.<b><a href="'.WebSite.'/myaffiliates/'.$_POST['affiliate_code'].'">'.WebSite.'/myaffiliates/'.$_POST['affiliate_code'].'</a></b><br />'.
	$lang['details2'].'<b><a href="'.WebSite.'/affiliates/">'.WebSite.'/myaffiliates/</a></b><br />'.
	$lang['details3'].''.$lang['thank_msg'].'<br />'.official_mail_sender_name;
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
	//var_dump($params);
		}
	}
}
echo '
<div class="msg">',$msg,'</div>
<form name="affiliateform" id="affiliateform" action="" method="POST">
<input type="hidden" name="affid" value="'.$last_id.'" />
<table class="affiliates" width="100%" border="0" cellspacing="0"><tbody>
<tr><td class="label" colspan="2">','Добавяне на Афилейт</td></tr>
<tr><td><span class="required">*</span>Име</td><td><input type="text" value="',$_POST['firstname'],'" name="firstname"/></td></tr>
<tr><td><span class="required">*</span>Фамилия</td><td><input type="text" value="',$_POST['lastname'],'" name="lastname"/></td></tr>
<tr><td><span class="required">*</span>E-Mail:</td><td><input type="text" value="',$_POST['email'],'" name="email"></td></tr>
<tr><td><span class="required">*</span>Парола:</td><td><input type="text" value="',$_POST['aff_pass'],'" name="aff_pass"/></td></tr>
<tr><td>Телефон</td><td><input type="text" value="',$_POST['telephone'],'" name="telephone"/></td></tr>
<tr><td>Име на фирмата</td><td><input type="text" value="',$_POST['company'],'" name="company"/></td></tr>
<tr><td>Website</td><td><input type="text" value="',$_POST['website'],'" name="website"></td></tr>
<tr><td><span class="required">*</span>Град</td><td><input type="text" value="',$_POST['city'],'" name="city"/></td></tr>
<tr><td>Пощенски код</td><td><input type="text" value="',$_POST['postcode'],'" name="postcode"></td></tr>
<tr><td>Адрес</td><td><input type="text" value="',$_POST['address'],'" name="address"></td></tr>
<tr><td><span class="required">*</span>Код на афилиейта<br />Позволени са: A-Za-z0-9-_@&</td><td><input type="text" value="',$_POST['affiliate_code'],'" name="affiliate_code"></td></tr>
<tr><td><span class="required">*</span>Комисионна %</td><td><input maxlength="5" type="text" value="',$_POST['commission'],'" name="commission"></td></tr>
<tr><td colspan="2" class="label">Данни за плащене на комисионните</td></tr>
<tr><td>Име на банката</td><td><input type="text" value="',$_POST['bank_name'],'" name="bank_name"></td></tr>
<tr><td>BIC</td><td><input type="text" value="',$_POST['bank_swift_code'],'" name="bank_swift_code"></td></tr>
<tr><td>IBAN</td><td><input type="text" value="',$_POST['bank_account_number'],'" name="bank_account_number"></td></tr>
<tr><td>Титуляр</td><td><input type="text" value="',$_POST['bank_account_name'],'" name="bank_account_name"></td></tr>
';
?>
<tr><td>Статус</td><td><select name="status"><option <?php if($_POST['status']=="0") echo 'selected="selected"'?> value="0">Неактивен</option><option <?php if($_POST['status']=="1") echo 'selected="selected"'?> value="1">Активен</option></select></td></tr>
<tr><td>Link на Афилиейта:</td><td><?php echo WebSite;?>/myaffiliates/<?php echo $_POST['affiliate_code'];?></td></tr>
<tr><td>Изпрати мейл с данните на Афилиейта:</td><td><input type="checkbox" name="alert_affiliate"/></td></tr>

<tr><td colspan="2" align="center"><a href="./index.php?page=affiliates" class="btn btn-secondary cancel" >Затвори</a>
<input type="submit" name="savenew" class="btn btn-secondary save" value="Запази"/></td></tr>
<?php
echo '</tbody></table></form>';
}
elseif(!empty($_GET['edit']) || !empty($_POST['affid'])){
//var_dump($_POST);
$msg='';
if(isset($_POST['save']) || isset($_POST['savenew'])){

if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['commission']) || empty($_POST['affiliate_code'])){
$msg.='Празни полета - Моля корегирайте';
	}else{
	$aff_pass = '';
	if(empty($_POST['aff_real_pass'])) echo 'aaaaaaaaaaa',$aff_pass = substr(uniqid('', true), -5);
	else $aff_pass = $_POST['aff_real_pass'];
	//var_dump($_POST);

$updquery='update affiliates set 
firstname="'.mysql_real_escape_string($_POST['firstname']).'",
lastname="'.mysql_real_escape_string($_POST['lastname']).'",
email="'.mysql_real_escape_string($_POST['email']).'",
aff_pass = "'.md5($aff_pass).'",
aff_real_pass = "'.mysql_real_escape_string($aff_pass).'",
telephone="'.mysql_real_escape_string($_POST['telephone']).'",
company="'.mysql_real_escape_string($_POST['company']).'",
website="'.mysql_real_escape_string($_POST['website']).'",
city="'.mysql_real_escape_string($_POST['city']).'",
postcode="'.mysql_real_escape_string($_POST['postcode']).'",
address="'.mysql_real_escape_string($_POST['address']).'",
affiliate_code="'.mysql_real_escape_string($_POST['affiliate_code']).'",
commission="'.mysql_real_escape_string($_POST['commission']).'",
bank_name="'.mysql_real_escape_string($_POST['bank_name']).'",
bank_swift_code="'.mysql_real_escape_string($_POST['bank_swift_code']).'",
bank_account_number="'.mysql_real_escape_string($_POST['bank_account_number']).'",
bank_account_name="'.mysql_real_escape_string($_POST['bank_account_name']).'",
active="'.mysql_real_escape_string($_POST['status']).'" 
where id="'.mysql_real_escape_string($_POST['affid']).'"';
//exit();
$result=mysql_query($updquery) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$updquery);
	$msg.='Промените са запазени !';
	if(isset($_POST['alert_affiliate'])){
	//izprashtame dannite za login na affiliate-a
	$subject = $lang['affiliate_registration'].' '.hostname.'';
	$body = $lang['dear'].' '.$_POST['firstname'].' '.$_POST['lastname'].',<br />'.
	$lang['details'].' '.$_POST['commission'].' '.$lang['detailsa'].'.<b><a href="'.WebSite.'/myaffiliates/'.$_POST['affiliate_code'].'">'.WebSite.'/myaffiliates/'.$_POST['affiliate_code'].'</a></b><br />'.
	$lang['details2'].'<b><a href="'.WebSite.'/affiliates/">'.WebSite.'/affiliates/</a></b><br />'.' и следните логин данни:<br />
	Имейл: '.$_POST['email'].'<br />
	Парола: '.$aff_pass.'<br />'.
	$lang['details3'].''.$lang['thank_msg'].'<br />'.official_mail_sender_name;
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
	//var_dump($params);
		}
	}

}
//redakciq na affiliate
if(!empty($_POST['affid'])) $_GET['edit']=$_POST['affid'];
$query='select * from affiliates where id="'.intval($_GET['edit']).'"';
$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
$num_rows=mysql_num_rows($result);

if($num_rows > 0){
$row=mysql_fetch_assoc($result);
$_POST['status']=$row['active'];
echo '
<div class="msg">',$msg,'</div>
<form name="affiliateform" id="affiliateform" action="" method="POST">
<input type="hidden" name="affid" value="'.$row['id'].'" />
<table class="affiliates" width="100%" border="0" cellspacing="0"><tbody>
<tr><td class="label" colspan="2">','Редакция на Афилейт '.$row['firstname'].' '.$row['lastname'],'</td></tr>
<tr><td><span class="required">*</span>Име</td><td><input type="text" value="',$row['firstname'],'" name="firstname"></td></tr>
<tr><td><span class="required">*</span>Фамилия</td><td><input type="text" value="',$row['lastname'],'" name="lastname"></td></tr>
<tr><td><span class="required">*</span>E-Mail:</td><td><input type="text" value="',$row['email'],'" name="email"></td></tr>
<tr><td><span class="required">*</span>Парола:</td><td><input type="text" value="',$row['aff_real_pass'],'" name="aff_real_pass"></td></tr>
<tr><td>Телефон</td><td><input type="text" value="',$row['telephone'],'" name="telephone"></td></tr>
<tr><td>Име на фирмата</td><td><input type="text" value="',$row['company'],'" name="company"></td></tr>
<tr><td>Website</td><td><input type="text" value="',$row['website'],'" name="website"></td></tr>
<tr><td>Град</td><td><input type="text" value="',$row['city'],'" name="city"></td></tr>
<tr><td>Пощенски код</td><td><input type="text" value="',$row['postcode'],'" name="postcode"></td></tr>
<tr><td>Адрес</td><td><input type="text" value="',$row['address'],'" name="address"></td></tr>
<tr><td><span class="required">*</span>Код на афилиейта</td><td><input type="text" value="',$row['affiliate_code'],'" name="affiliate_code"></td></tr>
<tr><td><span class="required">*</span>Комисионна %</td><td><input maxlength="5" type="text" value="',$row['commission'],'" name="commission"></td></tr>
<tr><td colspan="2" class="label">Данни за плащане на комисионните</td></tr>
<tr><td>Име на банката</td><td><input type="text" value="',$row['bank_name'],'" name="bank_name"></td></tr>
<tr><td>BIC</td><td><input type="text" value="',$row['bank_swift_code'],'" name="bank_swift_code"></td></tr>
<tr><td>IBAN</td><td><input type="text" value="',$row['bank_account_number'],'" name="bank_account_number"></td></tr>
<tr><td>Титуляр</td><td><input type="text" value="',$row['bank_account_name'],'" name="bank_account_name"></td></tr>
<tr><td>Добавен на дата</td><td><input readonly type="text" value="',date("d-m-Y", strtotime($row['added_date'])),'" name="added_date"></td></tr>';
?>
<tr><td>Статус</td><td><select name="status"><option <?php if($_POST['status']=="0") echo 'selected="selected"'?> value="0">Неактивен</option><option <?php if($_POST['status']=="1") echo 'selected="selected"'?> value="1">Активен</option></select></td></tr>
<tr><td>Link на Афилиейта:</td><td><?php echo WebSite;?>/partners/<?php echo $row['affiliate_code'];?></td></tr>
<tr><td>Изпрати мейл с данните на Афилиейта:</td><td><input type="checkbox" name="alert_affiliate"/></td></tr>

<tr><td colspan="2" align="center"><a href="./index.php?page=affiliates" class="btn btn-secondary cancel" >Затвори</a>
<input type="submit" name="save" class="btn btn-secondary save" value="Запази"/></td></tr>
<?php
echo '</tbody></table></form>';

//принтираме поръчките на дадения афилиейт
?>
<div id="her_advertisment" style="margin:0 0 0 4px;">
	<div style="text-align:center;margin-top:20px;">
		<h1>Поръчки с комисионни от <?php echo $row['firstname'].' '.$row['lastname']?></h1>
	<table id="orders" width="100%" class="p"><tbody>
<?php
	echo '<tr><th>#</th>
	<th>',$lang['title'],'</th>
	<th>',$lang['website'],'</th>
	<th>',$lang['status'],'</th>
	<th>',$lang['listing_type'],'</th>
	<th>',$lang['added_date'],'</th>
	<th>',$lang['country'],'</th></tr>';

$q='select * from adverts where affiliate_id="'.$_GET['edit'].'" order by id DESC';
$r=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
$numrows=mysql_num_rows($r);
if($numrows > 0){
$grandtotal='';
$commissionpercent=0;
for($k=0;$k<$numrows;$k++){
	$rw=mysql_fetch_assoc($r);

	$vip=$lang['free_plan'];
	$active=$lang['unactive'];
	if($rw['vip']=="1") $vip=$lang['paid_plan'];
	if($rw['active']=="1") $active=$lang['active'];
	echo '<tr><td align="center">',($k+1),'</td>
	<td align="left">',$rw['supplier_name'],'</td>
	<td align="center">',$rw['site'],'</td>
	<td align="center">',$active,'</td>
	<td align="center">',$vip,'</td>
	<td align="center">',date('M j Y ', strtotime($rw['added_date'])),'</td>
	<td align="center">',$rw['country'],'</td>
	
	</tr>';	
	}
	
	}else{
	echo '<tr class="ends"><td align="center" colspan="7">Афилиейта все още няма направени поръчки !</td></tr>';
	}		
			?>
			
		</tbody></table>
	</div>
</div>
<?php
//поръчките до тук	
	
	}
}else{
$sc='';
if(!empty($_POST['select_city'])) $sc=$_POST['select_city'];
elseif(!empty($_GET['select_city'])) $sc=$_GET['select_city'];


$sf='';
if(!empty($_POST['active_affiliate'])) $sf=$_POST['active_affiliate'];
elseif(!empty($_GET['active_affiliate'])) $sf=$_GET['active_affiliate'];
?>
<form name="alabala" id="alabala" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="GET">
<input type="hidden" name="page" value="affiliates" />
<input type="hidden" name="pn" value="1" />
<input type="hidden" name="select_city" id="select_city" value="<?php echo $sc;?>" />
<input type="hidden" name="active_affiliate" id="active_affiliate" value="<?php echo $sf;?>" />
<input type="hidden" name="order" id="order" value="" />
<input type="hidden" name="type" id="type" value="<?php echo $so;?>" />

</form>
<?php
$products_on_page=30;

$query='select city from affiliates group by city';
$result=mysql_query($query) or die($query);
$num_rows=mysql_num_rows($result);
$select_city='<select style="margin:30px 10px;padding: 5px;" name="select_city" onchange="setAdvert(this);"><option value="0">От всички градове</option>';
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$selected='';
		if(!empty($sc) && $sc==$row[0]) $selected=' selected="selected"';
		$select_city.='<option value="'.$row[0].'"'.$selected.'>'.$row[0].'</option>';
	}
	$select_city.='</select>';

$query='select active from affiliates group by active';
$result=mysql_query($query) or die($query);
$num_rows=mysql_num_rows($result);
$active_affiliate='<select style="margin:30px 10px;padding: 5px;" name="active_affiliate" onchange="setactive(this);"><option value="0">Всички</option>';
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$selected='';
		$label='';
		if(($row[0]+1)=="2") $label='Активни';else $label='Неактивни';
		if(!empty($sf) && $sf==($row[0]+1)) $selected=' selected="selected"';
		$active_affiliate.='<option value="'.($row[0]+1).'"'.$selected.'>'.$label.'</option>';
	}
	$active_affiliate.='</select>';	
	
	
echo $select_city,'&nbsp;&nbsp;',$active_affiliate;

$query='SELECT id,firstname, lastname, email,telephone,city, company,website, commission, active, affiliate_code FROM affiliates where active!=""';
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
			//pri serach otpada order by
			
			if(!empty($_GET['select_city'])){
						$query.=' and city="'.$sc.'"';
			}
			if(!empty($sf)){
				$query.=' and active="'.($sf-1).'"';
			}
			if(!empty($_GET['order'])){
			if($_GET['type'] == 'asc'){
			$query.= ' ORDER BY '.$_GET['order'].' asc LIMIT '.$limit.','.$products_on_page;
			}else {
			$query.= ' ORDER BY '.$_GET['order'].' desc LIMIT '.$limit.','.$products_on_page;
				}
			}
			else{
			$query.=' ORDER BY added_date DESC LIMIT '.$limit.','.$products_on_page;
			}
			
			//echo $query;
			getaffiliates($query);
			
			if(empty($sc) && empty($sf)){
			if($pages>1){
				echo '<div class="pages" align="center"><div class="row">';
				$url='./index.php?page=affiliates';
				if(isset($_GET['city'])) $url.='&city='.$_GET['city'];
				if($pn>1) echo '<span><a href="',$url,'&pn=',($pn-1),'">&lt;&lt;</a></span>';
				for($i=1;$i<=$pages;$i++){
					if($pn==$i) echo '<span class="activpn"><a class="activpn" href="',$url,'&pn=',$i,'">',$i,'</a></span>';
					else echo '<span><a href="',$url,'&pn=',$i,'">',$i,'</a></span>';
				if($i%14==0) echo '</div><div class="row">';
				}
				//echo '';
				if($pn<$pages) echo '&nbsp;&nbsp;<span><a href="',$url,'&pn=',($pn+1),'">&gt;&gt;</a></span>';
			echo '</div></div>';
				}
			}
		}else{
		echo '<div class="msg">Все още нямате въведени партнъори !<br />За да добавите партньор моля кликнете <a href="index.php?page=affiliates&new=1"> тук </a> и създайте партньор !</div>';
		}
	}
}else{
	@header("Location:index.php");
	exit(0);
	}
?>
<script>
function setAdvert(o){
	document.getElementById('select_city').value=o.value;
	document.getElementById('active_affiliate').value="<?php echo $_GET['active_affiliate'];?>";
	document.getElementById('alabala').submit();
}
function setactive(o){
	document.getElementById('active_affiliate').value=o.value;
	document.getElementById('alabala').submit();
}
function setorder(o,a){
var type = $(o).attr('data');
var order = $(o).attr('class');
	document.getElementById('type').value=type;
	document.getElementById('order').value=a;
	document.getElementById('select_city').value="<?php echo $_GET['select_city'];?>";
	document.getElementById('alabala').submit();
}


</script>