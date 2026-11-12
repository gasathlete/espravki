<?php
if(isset($_SESSION['adman'])){
require '../translation/bg_lang.php';
//var_dump($_POST);
//echo $message;
//echo $advert.'-aaa<br>';
if(!empty($advert)) $_POST['advert']=$advert;
if(!empty($_POST['advert'])) $advert=$_POST['advert'];

if(!empty($_POST['record_action']) && $_POST['record_action']=='delete'){


}


function send_mail($p){
$headers = "From: =?utf-8?b?".base64_encode($p['from_name'])."?= <".$p['from_email'].">\r\n";
$headers .= "Reply-To: ". strip_tags($p['from_email']) . "\r\n";
//$headers .= "CC: ". strip_tags($p['from_email']) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$random_hash = md5(date('r'));
//$p['body']=str_replace(array('<br>','<br />'),"\r\n",$p['body']);
	$body='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>'.$p['subject'].'</title>
</head>
<body bgcolor="White">
<center>
<div style="font-size:12px;width:620px;padding:5px;margin:0;color:#555;background:#fff;">
<a style="display:block;text-decoration:none;margin:0;padding:0;" href="'.WebSite.'" target="_blank">
	<img style="padding:0;margin:0;border:none;width:100%;" border="0" src="'.WebSite.'/images/mailheader.jpg" alt="'.official_mail_sender_name.'" />
	<div style="background:#06294F;color:#fff;font-size:11pt;font-weight:700;font-style:italic;margin:0;padding:5px 0;" align="center">'.official_mail_sender_name.'</div>
</a>
<div style="padding:5px;text-align:left;font-size:13px;font-family:verdana;"><br>'.$p['body'].'<br></div>
<div style="background:#06294F;color:#fff;margin:0;padding:5px 0;text-align:center;">©Copyright '.date('Y').'"&nbsp;&nbsp;<a href="'.WebSite.'" target="_blank" style="color:#fff;text-decoration:none;">'.official_site_name.'</a> - All Rights Reserved</div>
</div>
</center>
</body>
</html>';
		//$body.="\r\n\r\n--PHP-alt-$random_hash--";
	
	//echo $body;
	$mail_sent = mail($p['to'], '=?utf-8?B?'.base64_encode(strip_tags($p['subject'])).'?=', $body, $headers);
	return $mail_sent ? true : false;
}

function listCategory($id,$level=0,$parent) {
    $query = "SELECT bg_category, id, visible FROM products_categories where parent='".$id."' order by id ASC";
    $res = mysql_query($query) or die($query);
    if(mysql_num_rows($res) == 0) return;
   
    while (list ($bg_category, $id,$visible) = mysql_fetch_row($res)){
$que = 'select id from products_categories where parent="'.$id.'"';
		$resu = mysql_query($que) or die($que);
		$ro=mysql_fetch_row($resu);
        if ($level==0){
		
		if($ro[0]>0) {
		
		 echo '<tr><td colspan="2"><a style="color:#555;font-weight:bold;" href="index.php?page=categories&cid=',$id,'&edit=1">'.$bg_category.'</a></td><td>няма</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/icons/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/icons/desactivate.png" />'; $v2=0;}
        echo '<td><a href="index.php?page=categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td></tr>';
		
		}else{		
           echo '<tr>
		   <td colspan="2"><input type="radio" name="category_id" value="',$id,'" /><a style="color:#555;font-weight:bold;" href="index.php?page=categories&cid=',$id,'&edit=1">'.$bg_category.'</a></td><td>няма</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/icons/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/icons/desactivate.png" />'; $v2=0;}
        echo '<td><a href="index.php?page=categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td></tr>';
		}
		
		}
        else{
		if($ro[0]>0){
           echo '<tr>
		   <td colspan="2"><span style="margin-left:',($level*2),'0px;"><input type="radio" level="',$level,'" name="category_id" value="',$id,'" /><a style="color:#555;" href="index.php?page=categories&cid=',$id,'&edit=1">',$bg_category,'</a></span></td><td>',$parent,'</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/icons/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/icons/desactivate.png" />'; $v2=0;}
        echo '<td><a href="index.php?page=categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td></tr>';
			}
		else{
		 echo '<tr>
		   <td colspan="2"><span style="margin-left:',($level*2),'0px;"><input type="radio" level="',$level,'" name="category_id" value="',$id,'" /><a style="color:#555;" href="index.php?page=categories&cid=',$id,'&edit=1">',$bg_category,'</a></span></td><td>',$parent,'</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/icons/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/icons/desactivate.png" />'; $v2=0;}
        echo '<td><a href="index.php?page=categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td></tr>';
			}
		}
        listCategory($id,$level+1,$bg_category);
    } 
}

function articles_to_listings($aid){
	$html='<select name="articletoadvert"><option value="0">Моля изберете статия</option>';
	$q='SELECT article_id FROM articles_to_adverts where advert_id="'.$aid.'"';
	$res=mysql_query($q) or die($q);
	$rw=mysql_fetch_row($res);

	$query='select id, bg_article_title from articles';
	$result=mysql_query($query) or die($query);
	$num_rows=mysql_num_rows($result);
	for($j=0;$j<$num_rows;$j++){
	$selected='';
	$row=mysql_fetch_row($result);
	if($rw[0]==$row[0]) $selected='selected="selected"';
	$html.='<option '.$selected.' value="'.$row[0].'">'.$row[1].'</option>';
	}
	$html.='</select>';	
	echo $html;
}

function getproductimage($prid){
	$ihtml='';
	if(!empty($prid)){
		$query='select image,id from products where id="'.$prid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		$row=mysql_fetch_row($result);

		if(!empty($row[0])){
			if(file_exists(servImagesDir."/products/".$row[0]))	$image='../../images/products/'.$row[0];
			//else $image='../../images/products/'.$row[0];
			$ihtml.='<div class="pimg"><input type="checkbox" name="delete_image[]" value="'.$row[1].'" />
			<label>Изтрий снимката</label><img src="'.$image.'" /></div>';
			
	
		}else{
		$ihtml.='<div class="pimagediv"><span>Избери снимка
		<input id="p_gallery_upload" class="upload toclear" type="file" name="p_gallery_upload['.$row[1].']" /></span></div>';
		}
}else{
	$ihtml.='<div class="pimagediv"><span>Избери снимка
	<input id="p_gallery_upload" class="upload toclear" type="file" name="p_gallery_upload['.$row[1].']" /></span></div>';
	}
	return $ihtml;
}

function products_to_listings($aid){
	$html='
	<tr><th>#</th><th>Снимка</th><th>Продукт</th><th>Описание</th><th>Цена</th><th>Категория</th><th>Урл</th><th>Действие</th></tr>';
	$query='SELECT p.id,p.title,p.description,p.price,p.redirect_url,p.category_name,category_name FROM products p where p.advert_id="'.$aid.'" group by p.id';
	$result=mysql_query($query) or die($query);

	$num_rows=mysql_num_rows($result);
	for($j=0;$j<$num_rows;$j++){
	$row=mysql_fetch_row($result);
	$html.='<tr><td><input id="productid" class="toclear" type="hidden" value="'.$row[0].'" name="prid[]" />'.($j+1).'</td>
	<td width="10%">'.getproductimage($row[0]).'</td>
	<td width="25%"><input maxlength="70" class="toclear" type="text" id="prd_title" name="prd_title[]" value="'.$row[1].'" /></td>
	<td width="25%"><textarea maxlength="150" class="toclear" id="prd_description" name="prd_description[]" value="'.$row[2].'">'.$row[2].'</textarea></td>
	<td width="10%"><input class="toclear" type="text" id="prd_price" name="prd_price[]" value="'.$row[3].'" /></td>
	<td width="10%" align="center">'.$row[6].'</td>
	<td><input class="toclear" type="text" id="prd_link" name="prd_link[]" value="'.$row[4].'" /></td>
	<td><input class="toclear" type="checkbox" name="del_prd[]" value="'.$row[0].'" /><label>Изтрий продукта</label></td>
	</tr>';
	}
	$html.='';	
	echo $html;
}

function getProductCategories($aid=0){
$html='';
	$checked_values=array();
	if($aid!=0){
		$query="SELECT category_id FROM adverts_to_product_categories WHERE advert_id='".addslashes($aid)."'";
		$result=mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result)){
			while($row=mysql_fetch_row($result)){
				$checked_values[$row[0]]=1;
			}
		}
	}
	$query='select id,bg_category from products_categories where parent="0"';
	$result=mysql_query($query) or hjdie();
	$num_rows=mysql_num_rows($result);
	for($i=0;$i<$num_rows;$i++){
		$row=mysql_fetch_row($result);
		$query='select id,bg_category from products_categories where parent="'.$row[0].'"';
		$result1=mysql_query($query) or hjdie();
		$num_rows1=mysql_num_rows($result1);
		if($num_rows1>0){
			$html.='<div><input type="checkbox" disabled="true" />'.$row[1].'</div>';
			for($j=0;$j<$num_rows1;$j++){
				$row1=mysql_fetch_row($result1);
				
				    $querys='select id,bg_category from products_categories where parent="'.$row1[0].'"';
					$result1s=mysql_query($querys) or hjdie();
					$num_rows1s=mysql_num_rows($result1s);
					$checked='';
					//
					if($num_rows1s>0){
					$html.='<div style="padding-left:20px;"><input type="checkbox" disabled="true" />'.$row1[1].'</div>';
					for($k=0;$k<$num_rows1s;$k++){
					$row1s=mysql_fetch_row($result1s);
					$checked='';

					if(array_key_exists($row1s[0],$checked_values)) $checked=' checked="true"';
					$html.='<div style="padding-left:40px;"><input type="checkbox" name="categories[]" id="c'.$row1s[0].'" value="'.$row1s[0].'"'.$checked.'><label for="c'.$row1s[0].'">'.$row1s[1].'</label></div>';
						}
					}else{
				$checked='';
				if(array_key_exists($row1[0],$checked_values)) $checked=' checked="true"';
				$html.='<div style="padding-left:20px;"><input type="checkbox" name="categories[]" id="c'.$row1[0].'" value="'.$row1[0].'"'.$checked.'><label for="c'.$row1[0].'">'.$row1[1].'</label></div>';
				}
			}
		}
		else{
			$checked='';
			if(array_key_exists($row[0],$checked_values)) $checked=' checked="true"';
			$html.='<div><input type="checkbox" name="categories[]" id="c'.$row[0].'" value="'.$row[0].'"'.$checked.'><label for="c'.$row[0].'">'.$row[1].'</label></div>';
		}
	}
	echo $html;
}

function getImages($aid){
	global $lang;
	$numimages=4;
	if(!empty($aid)){
		$query='select image,id,order_id from adverts_gallery where advert_id="'.$aid.'" order by order_id ASC';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);

		echo '<div class="row"><p>You can add max 4 images!</p>
		
		<p><strong>Направи скрийншот на сайта от тук</strong><br/>https://snapito.com/ </p>';
		$firstimage='';
		$highidarray=array();
		if($num_rows > 0){
		for($i=1;$i<=$num_rows;$i++){
			$row=mysql_fetch_row($result);
			$imageid=$row[2];
			$highidarray[]=$imageid;
			if($imageid==1) $firstimage=$row[1];
			$class='';$label='<span class="firstimg"></span>';
			if($row[1]==$firstimage){ $class='1';$label='<span class="firstimg">Главна снимка</span>';}
			echo '<div class="imgupl',$class,'">',$label,'<input type="checkbox" name="dimage[]" id="i'.$row[1].'" value="'.$row[1].'" />
			<label for="i'.$row[1].'">'.$lang['delete_picture'].'</label><br />
			<img src="../../images/adverts/'.$row[0].'.jpg" height="110" /></div>';
			
			$arr[substr($row[0],-5,-4)][0]=$row[0];
			$arr[substr($row[0],-5,-4)][1]=$row[1];
			}
		}
		//$highid=max($highidarray);
	echo '</div>';

	
		//var_dump($highidarray);
	for($j=1;$j<=4;$j++){
		if(!in_array($j, $highidarray)){
		//echo $j.' is not in array';
		
	echo '<div data="'.$j.'" id="input'.$j.'" class="imagediv">';
	//echo $j.'-aaa';
	if($j==1) echo '<font color="Red" size="1">Main profile image<br /></font>';
	echo 'Image '.$j.'<span>Custom Upload
	<input id="gallery_upload_'.$j.'" data="'.$j.'" class="upload" type="file" name="gallery_upload_'.$j.'" /></span></div>';
		}
	}
	
}else{
	echo '<font color="Red" size="1">'.$lang['img1'].'</font><br />
	<div class="imagediv"><img src="../images/no_product_image.jpg" /></div>
	<div class="imagediv"><img src="../images/no_product_image.jpg" /></div>
	<div class="imagediv"><img src="../images/no_product_image.jpg" /></div>
	<div class="imagediv"><img src="../images/no_product_image.jpg" /></div>';
	}
}

$checkedgoogleplusprogram='';
$checkedsellbusiness='';
$affiliate='';
if(!empty($advert)){
	$query='select * from adverts where id="'.$advert.'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$row=mysql_fetch_assoc($result);
	$_POST['supplier_name']=htmlspecialchars($row['supplier_name']);
	$_POST['bg_short_description']=htmlspecialchars($row['description']);
	$_POST['meta_title']=htmlspecialchars($row['meta_title']);
	$_POST['bg_meta_description']=htmlspecialchars($row['meta_description']);
	$_POST['meta_keywords']=htmlspecialchars($row['meta_keywords']);

	$_POST['small_image']=htmlspecialchars($row['small_image']);
	$_POST['link_name']=htmlspecialchars($row['link_name']);
	$_POST['expired_date']=$row['expired_date'];
	if(isset($_POST['expired_date']) && $_POST['expired_date']!='' && $_POST['expired_date']!='0000-00-00'){
	$_POST['expired_date'] = date("d-m-Y", strtotime($_POST['expired_date']));
	} else $_POST['expired_date']='';
	
	$_POST['address']=htmlspecialchars($row['company_address']);
	$_POST['town']=htmlspecialchars($row['town']);
	$_POST['area']=htmlspecialchars($row['area']);
	$_POST['postcode']=htmlspecialchars($row['postcode']);
	$_POST['phones']=htmlspecialchars($row['company_phones']);
	$_POST['mail']=htmlspecialchars($row['mail']);
	$_POST['pass']=htmlspecialchars($row['pass']);
	$_POST['active']=htmlspecialchars($row['active']);
	$_POST['vip']=htmlspecialchars($row['vip']);
	$_POST['affiliate']=$row['affiliate'];
	$_POST['commission']=$row['commission'];
	$_POST['site']=$row['site'];
	$_POST['facebook']=htmlspecialchars($row['facebook']);
	$_POST['twitter']=htmlspecialchars($row['twitter']);
	$_POST['google']=htmlspecialchars($row['google']);
	$_POST['pinterest']=htmlspecialchars($row['pinterest']);
	
	$_POST['business_type']=$row['business_type'];
	$_POST['googleplusprogram']=$row['googleplusprogram'];
	$_POST['sellbusiness']=$row['sellbusiness'];
	
	if(!empty($_POST['googleplusprogram'])) $checkedgoogleplusprogram='checked';
	if(!empty($_POST['sellbusiness'])) $checkedsellbusiness='checked';
	
	$_POST['business_price']=$row['business_price'];
	$_POST['sell_description']=$row['sell_description'];
	
	$_POST['latitude']=htmlspecialchars($row['latitude']);
	$_POST['longitude']=htmlspecialchars($row['longitude']);
	
	$_POST['max_offers']=$row['max_offers'];
	$_POST['intitle']=$row['advert_title_indentificator'];
	$_POST['indesc']=$row['advert_desc_indentificator'];
	$_POST['inprice']=$row['advert_price_indentificator'];
	$_POST['promo_price']=$row['advert_promo_price_indentificator'];
	$_POST['inimg']=$row['advert_img_indentificator'];
	$_POST['incatname']=$row['advert_category_indentificator'];
	
}
elseif(!empty($_POST['advert'])) $advert=$_POST['advert'];

	//var_dump($_POST['sendmail']);
	if(isset($_POST['sendmail'])){
	echo 'мейла е изпратен !';
	$subject = 'Вашият бизнес профил в Espravki.com е одобрен';
	$body= $lang['dear'].' '.$_POST['supplier_name'].',<br />
	Вашият профил в Espravki.com бе редактиран и одобрен от администратор. Моля имайте в предвид, че администратора има право да променя напълно или частично профила ако информацията която сте въвели е неясна, неточна, недостатъчна или объркваща. В някои от случаите администратор може дори да изтрие профила Ви, особенно тогава, когато информацията противоречи на закона, морала или е изцяло подвеждаща !<br />
	Можете да разгледате профила си като го потърсите в съответната категория в която сте го добавили или по някоя от ключовите думи за бизнеса ви. <br />
	ВАЖНО е да знаете че ако сте избрали платен вариянт можете да добавите до 30 продукта които да продавате онлайн без да заплащате комисионна върху продажбите през Espravki.com и ShoppingBulgaria.com<br />
	Също така можете да добавите и снимков материал с който най-добре да презентирате бизнеса си.<br />
	Ако сте избрали платеният вариянт имате право да добавите и 1 PR статия с право на външен линк към уебсайта ви. Това ще ви изстреля напред в класирането на Гугъл тъй като ще предаде огромна тежест на фирменият ви уеб сайт.<br />
	Ако все пак бизнесът ви е нов и не може да си позволи 8 лв/ месец за да продава чрез Espravki.com и ShoppingBulgaria.com моля не се разочаровайте ако ефекта който сте търсили не бъде постигнат. Така или инъче сме се постарали да осигурим максимална видимост и на безплатно регистриралите се фирми.<br /><br />
	Искренно се надяваме да постигнете повече посещения и продажби независимо кой вариянт за участие сте избрали.<br /><br />И още нещо което е ВАЖНО: Не забравяйте да посетите профила си в Espravki.com и да го споделите във Фейсбук! Бихте могли да помолите приятели или клиенти да споделят мнение за вашата фирма в профила ви в Espravki.com. Положителните коментари са 60% реализирана продажба !<br /><br /> 
	Благодарим Ви , че се регистрирахте и дадохте шанс на бизнесът си да се развива !<br /><br />
	Espravki.com дава възможност на всеки бизнес да печели допълнителни доходи от своят фирмен уеб сайт чрез нашата партньорска програма. Ако поставите наша реклама във вашият уебсайт и някой кликне върху нея и се регистрира в Espravki.com независимо като Премиум потребител или безплатна регистрация вие ще спечелите определена сума. Сумата договаряме индивидуално с всеки собственик на бизнес затова в случай на интерес от тази възможност, моля не се колебайте да ни пишете на info@espravki.com <br />
	Ако не се интересувате от предложението за допълнителни доходи, бихме оценили ако просто публикувате линк към нашият уебсайт<br /><br /><br />
	За да заплатите годишна такса в размер на 96лв моля изпратете ни фирмените си данни за да ви издадем фактура на емейл: info@espravki.com<br /><br />
	'.$lang['thank_msg'].'<br />'.official_mail_sender_name;
	$params=array(
	"content_type"=>"text/html",
	"replay_to"=>official_mail_sender,
	"from_name"=>official_mail_sender_name,
	"from_email"=>official_mail_sender,
	"to"=>$_POST['mail'],
	"subject"=>$subject,
	"body"=>$body
	);
	send_mail($params);
	//var_dump($params);
	}

//var_dump($_POST);

if(!empty($_POST['vip'])) $vip="checked";
if(!empty($_POST['active'])) $active="checked";

if(!empty($_POST['affiliate'])) $affiliate="checked";

if(@$message!='') echo '<div class="msg" style="margin-top:10px;">'.$message.'</div>';
?>

<form action="" method="POST" name="form1" enctype="multipart/form-data" class="p_form">
<input type="hidden" name="advert" value="<?php echo $advert;?>" id="advertid"/>
<div width="100%" border="0" cellspacing="0" cellpadding="2" class="p">
<div class="htabs" id="tabs">
<span id="ntab1"><a class="fragment" id="f1"><img title="Български" src="../images/flags/bg.png" /> Главна</a></span>
<span id="ntab6"><a class="fragment" id="f6">Категории</a></span>
<span id="ntab5"><a class="fragment" id="f7">Статии към листинга</a></span>
<span id="ntab7"><a class="fragment" id="f8">Продукти към листинга</a></span>
<span id="ntab9"><a class="fragment" id="f9">Настройка за сканиране</a></span>
</div>

<table class="tabtable">
<tbody>
</tbody>
</table>
<div id="fragment1" class="hideme">
<table class="tabtable">
<tbody>
<tr><td width="345" align="right"><span class="required">*</span><span class="label">Вид бизнес</span></td>
<td width="500"><?php echo select_business_type($_POST['business_type']);?></td></tr>
<tr><td width="345" align="right"><span class="required">*</span><span class="label">Заглавие</span></td>
<td width="500"><input style="width:565px;" type="text" name="supplier_name" maxlength="110" value="<?php echo @$_POST['supplier_name']?>" /></td></tr>
<tr><td width="345" align="right"><span class="required">*</span><span class="label">LINK url name</span></td>
<td width="500"><input style="width:150px;" type="text" name="link_name" maxlength="110" value="<?php echo @$_POST['link_name']?>" /></td></tr>
<tr><td width="345" align="right"><span class="label">Пощенски код</span></td>
<td width="500"><input style="width:365px;" type="text" name="postcode" maxlength="110" value="<?php echo @$_POST['postcode']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Адрес</span></td>
<td width="500"><input style="width:365px;" type="text" name="address" maxlength="110" value="<?php echo @$_POST['address']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Area</span></td>
<td width="500"><input style="width:365px;" type="text" name="area" maxlength="110" value="<?php echo @$_POST['area']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Град</span></td>
<td width="500"><input style="width:365px;" type="text" name="town" maxlength="110" value="<?php echo @$_POST['town']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Latitude</span> -> http://www.mapcoordinates.net/en</td>
<td width="500"><input style="width:365px;" type="text" id="latitude" name="latitude" maxlength="110" value="<?php echo @$_POST['latitude']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">longitude</span> -> http://www.mapcoordinates.net/en</td>
<td width="500"><input style="width:365px;" type="text" id="longitude" name="longitude" maxlength="110" value="<?php echo @$_POST['longitude']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Телефони</span></td>
<td width="500"><input style="width:365px;" type="text" name="phones" maxlength="110" value="<?php echo @$_POST['phones']?>" /></td></tr>
<tr><td width="345" align="right"><span class="label">WebSite</span></td>
<td width="500"><input style="width:365px;" type="text" name="site" maxlength="110" value="<?php echo @$_POST['site'];?>" /></td></tr>
<tr><td width="345" align="right"><span class="label">Email / ползва се за логин</span></td>
<td width="500"><input style="width:365px;" type="text" name="mail" maxlength="110" value="<?php echo @$_POST['mail'];?>" /></td></tr>
<tr><td width="345" align="right"><span class="label">Парола за достъп до администрацията</span></td>
<td width="500"><input style="width:70px;" type="text" name="pass" maxlength="20" value="" /></td></tr>

<tr><td width="345" align="right"><span class="label">Линк към Facebook страница</span></td>
<td width="500" height="30"><input style="width:365px;" type="text" name="facebook" value="<?php echo @$_POST['facebook']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Линк към Twitter страница</span></td>
<td width="500" height="30"><input style="width:365px;" type="text" name="twitter" value="<?php echo @$_POST['twitter']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Линк към Google+ страница</span></td>
<td width="500" height="30"><input style="width:365px;" type="text" name="google" value="<?php echo @$_POST['google']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Линк към Pinterest страница</span></td>
<td width="500" height="30"><input style="width:365px;" type="text" name="pinterest" value="<?php echo @$_POST['pinterest']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Участва в Гугъл програмата</span></td>
<td width="500" height="30"><input style="float:left;margin:0px 5px;" <?php echo $checkedgoogleplusprogram;?> id="googleplusprogram" type="checkbox" name="googleplusprogram" /></td></tr>

<tr><td width="345" align="right"><span class="label">Бизнеса се продава</span></td>
<td width="500" height="30"><input style="float:left;margin:0px 5px;" <?php echo $checkedsellbusiness;?> id="sellbusiness" type="checkbox" name="sellbusiness" /></td></tr>

<tr><td width="345" align="right"><span class="label">Продажна цена</span></td>
<td width="500" height="30"><input id="f16" type="text" name="business_price" value="<?php echo @$_POST['business_price']?>" /></td></tr>

<tr><td width="345" align="right"><span class="label">Описание какво се продава</span></td>
<td width="500" height="30"><textarea style="width:300px;height:100px;" name="sell_description" value="<?php echo @$_POST['sell_description']?>"><?php echo @$_POST['sell_description']?></textarea></td></tr>

<tr><td width="345" align="right"><span class="label">Активен до дата</span></td>
<td width="500">
<input name="expired_date" type="text" maxlength="10" id="datepicker"  value="<?php echo $_POST['expired_date'];?>" /></td></tr>
<tr><td width="345" align="right"><span class="label">Активен</span></td>
<td width="500"><input style="float:left;margin:0px 5px;" type="checkbox" name="active" <?php echo @$active;?> value="active"/>активен</td></tr>
<tr><td width="345" align="right"><span class="label">Vip</span></td>
<td width="500"><input style="float:left;margin:0px 5px;" type="checkbox" name="vip" <?php echo @$vip;?> value="vip"/>vip</td></tr>

<tr><td width="345" align="right"><span class="label">Affiliate</span></td>
<td width="500"><input style="float:left;margin:0px 5px;" type="checkbox" name="affiliate" <?php echo @$affiliate;?> value="active"/>Бизнеса продава на комисионна</td></tr>

<tr><td width="345" align="right"><span class="label">Комисионна</span></td>
<td width="500" height="30"><input size="5" id="commission" type="text" name="commission" value="<?php echo @$_POST['commission'];?>" /> %</td></tr>

<tr><td width="345" align="right"><span class="label">Брой позволени оферти</span></td>
<td width="500" height="30"><input size="5" id="max_offers" type="text" name="max_offers" value="<?php echo @$_POST['max_offers'];?>" /> бр</td></tr>

<tr><td width="345" align="right"><span class="required">*</span><span class="label">Описание</span></td>
<td width="500">
<textarea id="bg_short_description" name="bg_short_description" value='<?php echo @$_POST['bg_short_description'];?>' ><?php echo htmlspecialchars_decode(@$_POST['bg_short_description']);?></textarea></td></tr>
<tr><td width="345" align="right"><span class="label">Мета title</span></td>
<td width="500" height="30"><input style="width:365px;" type="text" name="meta_title" value="<?php echo @$_POST['meta_title']?>" /></td></tr>
<tr><td width="345" align="right"><span class="label">Мета keywords</span></td>
<td width="500" height="30"><input style="width:565px;" type="text" name="meta_keywords" value="<?php echo @$_POST['meta_keywords']?>" /></td></tr>

<tr><td  align="right"><span class="label">Meta description</span></td>
<td>
<textarea class="meta" id="bg_meta_description" name="bg_meta_description" value='<?php echo htmlspecialchars(@$_POST['bg_meta_description']);?>' ><?php echo htmlspecialchars_decode(@$_POST['bg_meta_description']);?></textarea></td></tr>
<tr><td colspan="2"><div class="separator"></div></td></tr>
</tbody></table>
<table align="center" class="images"><tbody><tr><td width="100%" align="center"><?php getImages($advert);?></td></tr></tbody></table>
</div>
<div id="fragment6" class="hideme">
<table class="tabtable"><tbody>
	<h2 style="margin:0;font-size:14px;text-align:center;width:100%"><span class="required">*</span>Категории</h2>
	<div style="margin: 0px auto; width: 700px;">
	<ul id="categories">
		<?php
			if(isset($_POST['category'])){ $category=$_POST['category'];
			}else{ $category=0;}
			getProductCategories($advert);
		?>
	</ul>
	</div>
</tbody></table>
</div>

<div id="fragment7" class="hideme">
<table class="tabtable"><tbody>
	<h2 style="margin:0;font-size:14px;text-align:center;width:100%"><span class="required">*</span>Статии</h2>
	<div style="margin: 0px auto; width: 700px;text-align: center;">
		<?php
		articles_to_listings($advert);
		?>
	</div>
</tbody></table>
</div>

<div id="fragment8" class="hideme">
<table class="tabtable"><tbody>
	<tr><th colspan="7"><h2 style="margin:0;font-size:14px;text-align:center;width:100%"><span class="required">*</span>Продукти</h2></th></tr>
	<?php
	products_to_listings($advert);
	?>
</tbody></table>
</div>

<div id="fragment9" class="hideme">
	<div style="margin: 80px auto; width: 100%;text-align: center;">
<h2 style="font-size:14px;text-align:center;width:100%">Настройки на crawler-a</h2>
<p>Примери<br />
$title = $html->find('div.prohead > h1',0)->outertext();<br />
$description = $html->find('div.shortd',0)->outertext();<br />
$price = $html->find('div.pricewraper',0)->plaintext;<br />
$image = $html->find('div#imgw > img');<br />
$categoryname = $html->find('div.holder > div');
</p>
<p>https://simplehtmldom.sourceforge.io/manual.htm</p>
<p>http://nimishprabhu.com/top-10-best-usage-examples-php-simple-html-dom-parser.html</p>
<span id="msg" ></span>
<h1>Ако променяш идентификаторите 1во трябва да запазиш и след това да ТЕСТВАШ !!!</h1>
<table class="table tabtable2"><tbody>
<tr><th width="200">Заглавие</th><td width="500"><input type="text" id="intitle" name="intitle" value="<?php echo @$_POST['intitle'];?>"/></td><td class=""><div id="intitle1"></div></td></tr>
<tr><th width="200">Описание</th><td width="500"><input type="text" id="indesc" name="indesc" value="<?php echo @$_POST['indesc'];?>"/></td><td class=""><div id="indesc1"></div></td></tr>
<tr><th width="200">Цена</th><td width="500"><input type="text" id="inprice" name="inprice" value="<?php echo @$_POST['inprice'];?>"/></td><td class=""><div id="inprice1"></div></td></tr>
<tr><th width="200">Промо Цена</th><td width="500"><input type="text" id="promo_price" name="promo_price" value="<?php echo @$_POST['promo_price'];?>"/></td><td class=""><div id="promo_price1"></div></td></tr>
<tr><th width="200">Снимка - За снимката се подава обграждащ div или елемент !!!</th><td width="500"><input type="text" id="inimg" name="inimg" value="<?php echo @$_POST['inimg'];?>"/></td><td class=""><div id="inimg1"></div></td></tr>
<tr><th width="200">Категория</th><td width="500"><input type="text" id="incatname" name="incatname" value="<?php echo @$_POST['incatname'];?>"/></td><td class=""><div id="incatname1"></div></td></tr>


<tr><td align="center" colspan="5">Моля въведете урл към сайт който желаете да тествате<br /><input type="text" id="product_link" name="product_link" value=""/></td></tr>

<tr><td align="center" colspan="5"><p class="test">Тествай</p></td></tr>
	
</tbody></table>
	</div>
</div>

<div align="center"><input type="checkbox" name="sendmail"/> Изпрати мейл че профила е одобрен</div>
<div align="center"><input type="hidden" name="btn" id="btn" value="save" />
<?php if(!empty($_SESSION['back'])){ ?>
<button type="button" class="cancel" onclick="window.location='<?php echo $_SESSION['back'];?>';">Затвори</button>
<?php }else{ ?>
<button class="cancel" type="button" onclick="window.location='./index.php?page=adverts&amp;pn=<?php if(isset($_GET['pn'])) echo $_GET['pn']; else echo '0';?>';"><?php echo $lang['close'];?></button>
<?php } ?>
&nbsp;&nbsp;
<button class="save" type="BUTTON" onclick="return check();">Запази</button>
</div>
</div>
</form>
<script>
/*
function isEmpty(value){return (value == null || value.length === 0);}
window.onload = function(){
var roxyFileman = '../adman/ckeditor/fileman/index.html';

$(function(){
   CKEDITOR.replace( 'bg_short_description',{filebrowserBrowseUrl:roxyFileman,
	filebrowserImageBrowseUrl:roxyFileman+'?type=image',
	removeDialogTabs: 'link:Upload;image:Upload'});	
});
}*/
</script>
<script>
/*
$(document).ready(function(){
	$(".hideme").hide();
	$("#fragment1").show();
    $(".fragment").click(function() {
	var id = $(this).attr("id");
	var sectionId = id.replace("f", "#fragment");
	$(".hideme").hide();
	$(sectionId).show();
    });
	
	$('.test').click(function(){
	var url = $.trim($('#product_link').val());
	var advertid = $.trim($('#advertid').val());
	var intitle = $.trim($('#intitle').val());
	var indesc = $.trim($('#indesc').val());
	var inprice = $.trim($('#inprice').val());
	var inimg = $.trim($('#inimg').val());
	var incatname = $.trim($('#incatname').val());
	var error = 0;
	
	/*if(url.length < 30 ){
	$('#product_link').css('background',"#ffeaee");
	//alert(error+url.length);

	error = 1;
	}
	if(intitle.length < 10 ){
	$('#intitle').css('background',"#ffeaee");
	error = 1;
	}
	if(indesc.length < 10 ){
	$('#indesc').css('background',"#ffeaee");
	error = 1;
	}
	if(inprice.length < 10 ){
	$('#inprice').css('background',"#ffeaee");
	error = 1;
	}
	if(inimg.length < 10 ){
	$('#inimg').css('background',"#ffeaee");
	error = 1;
	}*/
/*
	if(error > 0){
	
		return false;
	}else{
	$.ajax({
	type: 'post',
    url: "./getproductdata.php",
    async: false,
	dataType: "json",
    data: { "url": encodeURIComponent(url),"advertid": advertid },
	async: true,
	success: function(data){
	//alert(data[2]);

	//if(!isEmpty(data[0]) && !isEmpty(data[1]) && !isEmpty(data[2]) && !isEmpty(data[3]) && !isEmpty(data[4])){
	if(data[0].length) $('#intitle1').html(data[0]);
	if(data[1].length) $('#indesc1').html(data[1]);
	if(data[2].length) $('#inprice1').html(data[2]);
	if(data[3].length){
	//var images = JSON.parse(data[3]);
	$.each(data[3], function(index, el) { 
        $('#inimg1').prepend('<span style="margin:5px;"><img width="150" src="'+data[3][index]+'"/></span>');
    });
	
	}
	if(data[4].length) $('#incatname1').html(data[4]);
	$('#msg').html(data[8]);
	if(data[8].length){
	$('#msg').html(data[8]);
	return false;
	}
	$('#addform').submit();
	$('#spinner').hide();
    return false;
	//}
}}
);
}	
	});
});

function check(){
//var data = $( 'textarea#bg_short_description' ).val();
//datas = data.replace(/<p>(\s*&nbsp;\s*)+<\/p>/ig,'<p>&nbsp;</p>');
//alert(datas);
//alert($("#longitude").val());
if(($("#longitude").val() === '') || ($("#latitude").val() === '')){
alert('Моля добавете координати !');
gE('btn').value='save';document.form1.submit();
return false;
}else{
gE('btn').value='save';document.form1.submit();
} 
}
$(function() {
$('.imagediv').hide();
$('.imagediv:first').show();
     $("input:file").change(function (){
       var nextf = $(this).attr('data');
	   $('#input'+(nextf)+'').next().show();
     });
  });
//$.noConflict();
$(function(){
var dateToday = new Date();   
$('#datepicker').datepicker({
	dateFormat: 'yy-mm-dd',
	minDate: dateToday
    });
}); */
</script>
<?php
}else{
	header("Location:../../index.php");
	exit(0);
}
?>