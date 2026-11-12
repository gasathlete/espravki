<?php
//session_start();
//require '../config.php';
//require '../project_functions.php';

//require '../translation/'.$_SESSION['lang'].'_lang.php';


if(isset($_SESSION['adman']) && isset($_GET['aid'])){
$ch = 'select * from adverts where id = "'.mysql_real_escape_string($_GET['aid']).'" ';
$chresult=mysql_query($ch) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$ch);
$check_num_rows=mysql_num_rows($chresult);
//exit();

if($check_num_rows < 1){
header("location: ../index.php");
exit(0);
}else{
$advertrow = mysql_fetch_assoc($chresult);

}

$msg = '';


if(isset($_GET['deldoc']) && !isset($_POST['save_advert'])){
$d = 'delete from docs where id="'.$_GET['deldoc'].'"';
$dresult=mysql_query($d) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$d);
if(file_exists('../images/files/'.$_GET['doc'])) unlink('../images/files/'.$_GET['doc']);
$msg = 'Файла беше изтрит успешно !';
}

$help_business_type='<div id="help_business_type" class="bar" style="top:35%;"><span></span>'.$lang['help_business_type'].'</div>';
$help_supplier_name='<div id="help_supplier_name" class="bar"><span></span>'.$lang['help_supplier_name'].'</div>';
$help_email='<div id="help_email" class="bar"><span></span>'.$lang['used_for'].'</div>';
$help_password='<div id="help_password" class="bar"><span></span>'.$lang['used_for2'].'</div>';
$help_website='<div id="help_website" class="bar"><span></span>'.$lang['website_txt'].'</div>';
$help_city='<div id="help_city" class="bar"><span></span>'.$lang['city1'].'</div>';
$help_area='<div id="help_area" class="bar"><span></span>'.$lang['help_area'].'</div>';
$help_address='<div id="help_address" class="bar"><span></span>'.$lang['city1'].'</div>';
$help_phone='<div id="help_phone" class="bar"><span></span>'.$lang['help_phone'].'</div>';
$help_fsearch='<div id="help_fsearch" class="bar"><span></span>'.$lang['category_txt'].'</div>';
$help_keywords='<div id="help_keywords" class="bar"><span></span>'.$lang['keywords_txt'].'</div>';

$help_lat='<div id="help_lat" class="bar"><span></span>'.$lang['help_lat'].'</div>';
$help_lng='<div id="help_lng" class="bar"><span></span>'.$lang['help_lng'].'</div>';

$cities = echo_cities();


if(isset($_POST['save_advert'])){
if(!empty($_FILES['docs']['name'][0])){

//var_dump($_FILES["docs"]);echo '<br/><br/>';
//var_dump($_POST);
$_POST['doc_name'] = array_values(array_filter($_POST['doc_name']));
//var_dump($_POST);
foreach($_FILES["docs"]["tmp_name"] as $key=>$tmp_name){
if(!empty($tmp_name)){
//echo $key,' - ',$_POST['doc_name'][$key];echo '<br/>';
	if(!empty($_POST['doc_name'][$key])){
	$ext = pathinfo($_FILES["docs"]["name"][$key], PATHINFO_EXTENSION);
	$filename = create_url($_POST['doc_name'][$key]).'.'.$ext;
	
	$cheimg = 'select id from docs where advert_id = "'.mysql_real_escape_string($_GET['aid']).'" and doc_url = "'.mysql_real_escape_string($filename).'"';
	$cheimgresult=mysql_query($cheimg) or die(send_error($cheimg,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
	$cheimg_num_rows=mysql_num_rows($cheimgresult);
	if($cheimg_num_rows < 1){
	copy($_FILES['docs']['tmp_name'][$key],'../images/files/'.$filename);

	$insdoc = 'insert into docs set advert_id = "'.mysql_real_escape_string($_GET['aid']).'",
	doc_url = "'.mysql_real_escape_string($filename).'",doc_name = "'.mysql_real_escape_string($_POST['doc_name'][$key]).'"';
	$insdocresult=mysql_query($insdoc) or die(send_error($insdoc,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
			}
		}
	}
}
}
//exit();

$mand_fields = array("supplier_name", "business_type", "category", "mail", "company_name", "bulstat", "inv_city", "inv_address", "mol", "town", "company_address", "latitude", "longitude", "company_phones", "description", "bulstat", "selected_pay_way");

foreach($mand_fields as $fild){
if(!isset($_POST[$fild]) || empty($_POST[$fild])) $msg = $lang['enter'].' '.$lang[$fild].' -'.$fild;
$_POST[$fild] = str_replace('"',"'",$_POST[$fild]);

	}

if(isset($_POST['sellbusiness'])){
if(empty($_POST['business_price'])) $msg = $lang['enter'].' '.$lang['business_price'];
}	


if(!empty($_FILES['images']['name'][0])){
//var_dump($_FILES["images"]);
foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
if(!empty($tmp_name)){
list($width, $height, $type, $attr) = getimagesize($_FILES["images"]["tmp_name"][$key]);
if (!isset($type) || !in_array($type, array(
    IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
 $msg = $lang['UNSUPPORTED_FILE_TYPE'].' '.$_FILES["images"]["name"][$key];
}

if($_FILES["images"]['type'][$key]=='image/jpeg' || $_FILES["images"]['type'][$key]=='image/pjpeg' || $_FILES["images"]['type'][$key]=='image/jpg' || $_FILES["images"]['type'][$key]=='image/JPEG' || $_FILES["images"]['type'][$key]=='image/png'){
			if(exif_imagetype($_FILES["images"]["tmp_name"][$key])===FALSE){
			$msg=$_FILES["images"]['name'][$key].': '.$lang['UNSUPPORTED_FILE_TYPE'];
			}//else echo exif_imagetype($_FILES["images"]["tmp_name"][$key]);
			}else $msg=$_FILES["images"]['name'][$key].': '.$lang['UNSUPPORTED_FILE_TYPE'];
		}
	}
}


if(!empty($_FILES['logo']['name'])){
if(!empty($_FILES["logo"]["tmp_name"])){
list($width, $height, $type, $attr) = getimagesize($_FILES["logo"]["tmp_name"]);
if (!isset($type) || !in_array($type, array(
    IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
 $msg = $lang['UNSUPPORTED_FILE_TYPE'].' '.$_FILES["logo"]["name"];
}
if($_FILES["logo"]['type']=='image/jpeg' || $_FILES["logo"]['type']=='image/pjpeg' || $_FILES["logo"]['type']=='image/jpg' || $_FILES["logo"]['type']=='image/JPEG' || $_FILES["logo"]['type']=='image/png'){
			if(exif_imagetype($_FILES["logo"]["tmp_name"])===FALSE){
			$msg=$_FILES["logo"]['name'].': '.$lang['UNSUPPORTED_FILE_TYPE'];
			}//else echo exif_imagetype($_FILES["logo"]["tmp_name"]);
			}else $msg=$_FILES["logo"]['name'].': '.$lang['UNSUPPORTED_FILE_TYPE'];
		}

}

if(isset($_POST['del_logo'])){
//if(file_exists('../images/adverts/'.$_POST['del_logo'])) unlink('../images/adverts/'.$_POST['del_logo']);
$logoname = '';
echo $updatelogo = 'update adverts set logo = "" where id="'.mysql_real_escape_string($_GET['aid']).'" ';
$updatelogoresult=mysql_query($updatelogo) or die(send_error($updatelogo,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
}

if(isset($_GET['act']) && $_GET['act'] == 'copy_listing'){
$oldid = $_GET['aid'];
$chnewrow = 'select id from adverts where link_name="'.mysql_real_escape_string($_POST['link_name']).'" or 
latitude="'.mysql_real_escape_string($_POST['latitude']).'" or 
longitude="'.mysql_real_escape_string($_POST['longitude']).'"';
$chnewrowresult=mysql_query($chnewrow) or die(send_error($chnewrow,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$chnewrow_num_rows=mysql_num_rows($chnewrowresult);
if($chnewrow_num_rows < 1){
$maxq = 'insert into adverts set link_name="'.mysql_real_escape_string($_POST['link_name']).'", customer_id = "'.mysql_real_escape_string($advertrow['customer_id']).'",
logo = "'.mysql_real_escape_string($advertrow['logo']).'",small_image = "'.mysql_real_escape_string($advertrow['small_image']).'"';
$maxqresult=mysql_query($maxq) or die(send_error($maxq,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$last_id = mysql_insert_id();
$_GET['aid'] = $last_id;
$images = 'SELECT * FROM adverts_gallery where advert_id = "'.mysql_real_escape_string($oldid).'"';
$imagesresult=mysql_query($images) or die(send_error($images,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$images_num_rows=mysql_num_rows($imagesresult);
if($images_num_rows > 0){
for($a=0;$a<$images_num_rows;$a++){
$imagesrow=mysql_fetch_assoc($imagesresult);

$insertimages = 'insert into adverts_gallery set image = "'.mysql_real_escape_string($imagesrow['image']).'", advert_id = "'.mysql_real_escape_string($_GET['aid']).'", order_id = "'.mysql_real_escape_string($imagesrow['order_id']).'"';
$insertimagesresult=mysql_query($insertimages) or die(send_error($insertimages,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	
	}
}


	}else $msg = 'Трябва първо да промениш Име на фирмата / Бизнеса, LINK / URL, Геогр. ширина и Геогр. дължина !!! ';
}
		
if(empty($msg)){
$work_days = serialize($_POST['work_days']);
$pay_methods = serialize($_POST['pay_methods']);
$certificates = serialize($_POST['certificates']);




if(!empty($_FILES['logo']['name'])){
$logoname = create_url('logo_'.$_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode']).'-'.$_GET['aid'].'.jpg';
copy_and_resize_image_new($_FILES["logo"]["tmp_name"],$_FILES["logo"]["type"],$logoname,400,400,'adverts');
$updatelogo2 = 'update adverts set logo="'.mysql_real_escape_string(strip_tags($logoname)).'" where id="'.mysql_real_escape_string($_GET['aid']).'" ';
$updatelogo2result=mysql_query($updatelogo2) or die(send_error($updatelogo2,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
}


if(isset($_POST['active'])) $active="1";else $active="0";
if(isset($_POST['affiliate'])) $affiliate="1";else $affiliate="0";
if(isset($_POST['vip'])) $vip="1";else $vip="0";
if(isset($_POST['sellbusiness'])) $sellbusiness="1";else $sellbusiness="0";
if(isset($_POST['paid'])) $paid="1";else $paid="0";
//var_dump($_POST);echo '<br/>';




$update = 'update adverts set 
supplier_name="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['supplier_name']))).'",
meta_description="'.mysql_real_escape_string(htmlspecialchars($_POST['meta_description'])).'",
link_name="'.mysql_real_escape_string($_POST['link_name']).'",
meta_title="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['meta_title']))).'",
meta_keywords="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['meta_keywords'],'UTF-8'))).'",

description="'.mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['description'],'<p>'))).'",
latitude="'.mysql_real_escape_string(strip_tags($_POST['latitude'])).'",
longitude="'.mysql_real_escape_string(strip_tags($_POST['longitude'])).'",
town="'.mysql_real_escape_string(strip_tags($_POST['town'])).'",
area="'.mysql_real_escape_string(strip_tags($_POST['area'])).'",
postcode="'.mysql_real_escape_string(strip_tags($_POST['postcode'])).'",
company_address="'.mysql_real_escape_string(strip_tags($_POST['company_address'])).'",
country="Bulgaria",
country_code="BG",
company_phones="'.mysql_real_escape_string(strip_tags($_POST['company_phones'])).'",
mail="'.mysql_real_escape_string(strip_tags($_POST['mail'])).'",
site="'.mysql_real_escape_string(strip_tags($_POST['site'])).'",
expired_date="'.mysql_real_escape_string($_POST['expired_date']).'", 
business_type="'.mysql_real_escape_string(strip_tags($_POST['business_type'])).'",
max_offers = "'.mysql_real_escape_string(strip_tags($_POST['max_offers'])).'",
selected_plan = "'.mysql_real_escape_string(strip_tags($_POST['selected_plan'])).'",
selected_pay_way = "'.mysql_real_escape_string(strip_tags($_POST['selected_pay_way'])).'",
work_days = "'.mysql_real_escape_string($work_days).'",
pay_methods = "'.mysql_real_escape_string($pay_methods).'",
certificates = "'.mysql_real_escape_string($certificates).'",
opening="'.mysql_real_escape_string(strip_tags($_POST['opening'])).'",
closing="'.mysql_real_escape_string(strip_tags($_POST['closing'])).'",
company_name = "'.mysql_real_escape_string(strip_tags($_POST['company_name'])).'",
bulstat = "'.mysql_real_escape_string(strip_tags($_POST['bulstat'])).'",
dds = "'.mysql_real_escape_string(strip_tags($_POST['dds'])).'",
inv_city = "'.mysql_real_escape_string(strip_tags($_POST['inv_city'])).'",
inv_address = "'.mysql_real_escape_string(strip_tags($_POST['inv_address'])).'",
mol = "'.mysql_real_escape_string(strip_tags($_POST['mol'])).'",
active = "'.$active.'",facebook = "'.mysql_real_escape_string(strip_tags($_POST['facebook'])).'",
twitter = "'.mysql_real_escape_string(strip_tags($_POST['twitter'])).'",
linkedin = "'.mysql_real_escape_string(strip_tags($_POST['linkedin'])).'",
instagram = "'.mysql_real_escape_string(strip_tags($_POST['instagram'])).'",
vip = "'.$vip.'",paid = "'.mysql_real_escape_string(strip_tags($paid)).'",
paid_date = "'.mysql_real_escape_string(strip_tags($_POST['paid_date'])).'",
amount = "'.mysql_real_escape_string(strip_tags($_POST['amount'])).'",
advert_title_indentificator="'.mysql_real_escape_string($_POST['advert_title_indentificator']).'",
advert_desc_indentificator="'.mysql_real_escape_string($_POST['advert_desc_indentificator']).'",
advert_price_indentificator="'.mysql_real_escape_string($_POST['advert_price_indentificator']).'",
advert_promo_price_indentificator="'.mysql_real_escape_string($_POST['advert_promo_price_indentificator']).'",
advert_img_indentificator="'.mysql_real_escape_string($_POST['advert_img_indentificator']).'",
advert_category_indentificator="'.mysql_real_escape_string($_POST['advert_category_indentificator']).'",
sellbusiness = "'.$sellbusiness.'",
business_price = "'.mysql_real_escape_string(strip_tags($_POST['business_price'])).'" 
where id="'.mysql_real_escape_string($_GET['aid']).'"';
//echo $update;
//exit();
$result=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	

if(!empty($_POST['category'])){
//parwo iztriwame

$detcats = 'delete from adverts_to_product_categories where advert_id="'.mysql_real_escape_string($_GET['aid']).'"';
$detcatsresult=mysql_query($detcats) or die(send_error($detcats,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

foreach($_POST['category'] as $category){
		$q='select id, bg_category,url from products_categories where bg_category="'.mysql_real_escape_string($category).'"';
		$r=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
		$num_rows=mysql_num_rows($r);
		if($num_rows > 0){
		$row=mysql_fetch_row($r);
		$query='insert into adverts_to_product_categories set advert_id="'.mysql_real_escape_string($_GET['aid']).'", category_id="'.$row[0].'"';
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		}else{
		//dobawqme nowa kategoriq
		$inscat = 'insert into products_categories set bg_category = "'.mysql_real_escape_string($category).'", url = "'.mysql_real_escape_string(create_url($category)).'", parent="0"';
		$inscatresult=mysql_query($inscat) or die(send_error($inscat,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$last_cat_id=mysql_insert_id();
		
		$query='insert into adverts_to_product_categories set advert_id="'.mysql_real_escape_string($_GET['aid']).'", category_id="'.mysql_real_escape_string($last_cat_id).'"';
		$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
				}
			}
		}
		
		$imagecounter = 1;
		if(!empty($_FILES['images']['name'][0])){
		$maxnum = 0;
		$max = 'SELECT MAX(order_id) FROM adverts_gallery where advert_id = "'.mysql_real_escape_string($_GET['aid']).'" ';
		$maxresult=mysql_query($max) or die(send_error($max,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$maxrow = mysql_fetch_row($maxresult);
		$maxnum = $maxrow[0];
		
		foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
		if(!empty($tmp_name)){
		if($imagecounter <= $lang['images_num'][$_POST['selected_plan']]){
		$imagecounter++;
		$maxnum++;
		$save_file_name = create_url($_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode'].'-'.($maxnum)).'-'.$_GET['aid'].'.jpg';
		$smallfilename = create_url($_POST['supplier_name'].'-'.$_POST['city'].'-'.$_POST['area'].'-'.$_POST['postcode']).'-small-'.$_GET['aid'].'.jpg';

		//if(($key+1) == 1){
		if($maxnum == 1){
		$update = 'update adverts set small_image = "'.$smallfilename.'" where id = "'.mysql_real_escape_string($_GET['aid']).'"';
		$updateresult=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$smallfilename,400,400,'adverts');
		}
		
		$insertimages = 'insert into adverts_gallery set image = "'.mysql_real_escape_string($save_file_name).'", advert_id = "'.mysql_real_escape_string($_GET['aid']).'", order_id = "'.mysql_real_escape_string($maxnum).'"';
		$insertimagesresult=mysql_query($insertimages) or die(send_error($insertimages,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		copy_and_resize_image_new($_FILES["images"]["tmp_name"][$key],$_FILES["images"]["type"][$key],$save_file_name,1920,1200,'adverts');		
					}
				}
			}
		}else{
		if(isset($_GET['act']) && $_GET['act'] == 'copy_listing'){
		
		
		}
		
		}
		
		if(isset($_POST['sendmail'])){
	$msg.= 'мейла е изпратен !';
	$subject = 'Вашият бизнес профил в Espravki.com е одобрен';
	$body= $lang['dear'].' '.$_POST['supplier_name'].',<br />
	Вашият профил в Espravki.com бе редактиран и одобрен от администратор. Моля имайте в предвид, че администратора има право да променя напълно или частично профила ако информацията която сте въвели е неясна, неточна, недостатъчна или объркваща. В някои от случаите администратор може дори да изтрие профила Ви, особенно тогава, когато информацията противоречи на закона, морала или е изцяло подвеждаща !<br />
	Можете да разгледате профила си като го потърсите в съответната категория в която сте го добавили или по някоя от ключовите думи за бизнеса ви. <br />
	ВАЖНО е да знаете че ако сте избрали Премиум план можете да добавите 10 продукта които да продавате онлайн без да заплащате комисионна върху продажбите през Espravki.com<br />
	Също така можете да добавите и снимков материал с който най-добре да презентирате бизнеса си.<br />
	Ако сте избрали Премиум план имате право да добавите и 1 PR статия с право на външен линк към уебсайта ви. Това ще ви изстреля напред в класирането на Гугъл тъй като ще предаде огромна тежест на фирменият ви уеб сайт.<br />
	Ако все пак бизнесът ви е нов и не може да си позволи Премиум план за да ползва всички функционалностти които Espravki.com предлага, моля не се разочаровайте ако ефекта който сте търсили не бъде постигнат. Така или инъче сме се постарали да осигурим максимална видимост и за регистриралите се фирми с Базов план.<br /><br />
	Искренно се надяваме да постигнете повече посещения и продажби независимо кой вариянт за участие сте избрали.<br /><br />И още нещо което е ВАЖНО: Не забравяйте да посетите профила си в Espravki.com и да го споделите във Фейсбук! Бихте могли да помолите приятели или клиенти да споделят мнение за вашата фирма в профила ви в Espravki.com. Положителните коментари са 60% реализирана продажба !<br /><br /> 
	Благодарим Ви , че се регистрирахте и дадохте шанс на бизнесът си да се развива !<br /><br />
	Espravki.com дава възможност на всеки бизнес да печели допълнителни доходи от своят фирмен уеб сайт чрез нашата партньорска програма. Ако поставите наша реклама във вашият уебсайт и някой кликне върху нея и се регистрира в Espravki.com независимо като Премиум потребител или с Базов план вие ще спечелите определена сума. Сумата договаряме индивидуално с всеки собственик на бизнес затова в случай на интерес от тази възможност, моля не се колебайте да ни пишете на info@espravki.com <br />
	Ако не се интересувате от предложението за допълнителни доходи, бихме оценили ако просто публикувате линк към нашият уебсайт :)<br /><br /><br />
	<br />
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
	//exit();
	}
	
		$msg.= $lang['suc_save'];
		echo '<script>window.location.href = "../adman/index.php?page=edit_advert&aid='.$_GET['aid'].'&msg=1";</script>';
		//header("location: ../adman/index.php?page=edit_advert&aid=".$_GET['aid']."&msg=1");
		exit(0);
	}	
}


if(empty($msg) || $msg == $lang['suc_save'] || isset($_GET['deldoc'])){
$check = 'select * from adverts where customer_id = "'.mysql_real_escape_string($advertrow['customer_id']).'" and id = "'.mysql_real_escape_string($_GET['aid']).'" ';
$checkresult=mysql_query($check) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$check);

while($checkrow = mysql_fetch_assoc($checkresult)){
foreach($checkrow as $checkkey => $checkvalue) {
$lang['images_num'][$_POST['selected_plan']] = 100;

         //echo "$checkkey = $checkvalue <br />";
		 if($checkkey == 'selected_plan') $_POST['selected_plan'] = $checkvalue;
		 elseif($checkkey == 'work_days') $_POST['work_days'] = unserialize($checkvalue);
		 elseif($checkkey == 'pay_methods') $_POST['pay_methods'] = unserialize($checkvalue);
		 elseif($checkkey == 'certificates') $_POST['certificates'] = unserialize($checkvalue);
		 else $_POST[$checkkey] = $checkvalue;
		 
		 //echo $_POST[$checkkey] = $checkvalue;echo '<br/>';
     }
}
$categories = get_advert_categories($_GET['aid']);
//var_dump($categories);
//echo $_POST['dds'],'aaa';
$advert_images = get_advert_images($_GET['aid']);
//var_dump($advert_images);

$status = '<i class="fa fa-times text-danger ml-2 mr-2"></i>'.$lang['active_status'][$_POST['active']];
if($_POST['active'] > 0) $status = '<i class="fa fa-check text-success ml-2 mr-2"></i>'.$lang['active_status'][$_POST['active']];

$statuspaid = '<i class="fa fa-times text-danger ml-2 mr-2"></i>'.$lang['pay_status'][$_POST['paid']];
if($_POST['paid'] > 0) $statuspaid = '<i class="fa fa-check text-success ml-2 mr-2"></i>'.$lang['pay_status'][$_POST['paid']];

}
$advert_images = get_advert_images($_GET['aid']);

$advert_docs = get_advert_docs($_GET['aid']);

//echo $_POST['inv_city'],'aaaaaa';
if(!isset($_POST['inv_city']) || empty($_POST['inv_city'])) $city = $_SESSION['city'];
else $city = $_POST['inv_city'];

if(!isset($_POST['town']) || empty($_POST['town'])) $town = $_SESSION['city'];
else $town = $_POST['town'];


if(!isset($_POST['mol']) || empty($_POST['mol'])) $mol = $_SESSION['customer_names'];
else $mol = $_POST['mol'];

if(isset($_GET['msg'])){
if($_GET['msg'] == 'max_num_reached') $msg = $lang['max_num_reached'];
else $msg = $lang['suc_save'];
}
?>

<section class="sptb">
			<div class="container">
				<div class="row">
					<div class="col-xl-12">
						<div class="card mb-xl-0">
						<div class="cmsg"><?php echo $msg;?></div>

							<div class="card-header">
							<div class="ib w70 tal">
								<h3 class="card-title"><?php echo $lang['e_lst'],' - ',$lang['plans_array'][$_POST['selected_plan']],' | ',$advertrow['amount'],' ',current_currency,' ',$lang['inc_vat'],'  <span class="ml-4">',$lang['status'],': ',$status;?></span></h3>
							</div>
							<div class="ib w30 tar">
							<h4 class="text-right">
							<label class="ib wa"><a id="copy_listing" data-url="index.php?page=edit_advert&aid=<?php echo $_GET['aid'];?>&act=copy_listing" class="btn btn-primary mb-0">
							<input type="checkbox" name="aa"/>
							Копирай листинга</a></label></h4>
							</div>
							</div>

							<div class="card-body p-2">
								<form id="advertForm" action="./index.php?page=edit_advert&aid=<?php echo $_GET['aid'];?>" name="advertForm" method="post" enctype="multipart/form-data" class="form-horizontal mb-0">
								<input type="hidden" id="aid" value="<?php echo $_GET['aid'];?>"/>

									<div id="rootwizard" class="border pt-0">
										<ul class="nav nav-tabs nav-justified bbd">
											<li class="nav-item"><a id="firsttab" href="#first" data-toggle="tab" class="nav-link font-bold active"><?php echo $lang['base_info'];?></a></li>
											<li class="nav-item"><a id="secondtab" href="#second" data-toggle="tab" class="nav-link font-bold"><?php echo $lang['ab_com'];?></a></li>
											<li class="nav-item"><a id="thirdtab" href="#third" data-toggle="tab" class="nav-link font-bold"><?php echo $lang['images'];?></a></li>
											<li class="nav-item"><a id="fourthtab" href="#fourth" data-toggle="tab" class="nav-link font-bold"><?php echo $lang['of_pr'];?></a></li>
											<li class="nav-item"><a id="sixthtab" href="#sixth" data-toggle="tab" class="nav-link font-bold">Настройки за сканиране</a></li>
											<li class="nav-item"><a id="seventhtab" href="#seventh" data-toggle="tab" class="nav-link font-bold">Полезни документи</a></li>
											<li class="nav-item"><a id="fifthtab" href="#fifth" data-toggle="tab" class="nav-link font-bold "><?php echo $lang['paymnt'];?></a></li>
										</ul>
										<hr class="mb-1"/>
										<div class="tab-content mb-0 b-0 tal">
											<div class="tab-pane fade card-body active show" id="first">
											
<div class="form-group mb-5">
<label class="form-label text-dark"><?php echo $lang['company_logo'];?></label>
<?php
if(!empty($_POST['logo'])){
echo '
<div class="logo_preview pr">
<img src="../images/adverts/',$_POST['logo'],'" alt="',$lang['company_logo'],' ',$_POST['supplier_name'],'"/>
<div class="checkbox checkbox-info">
<label class="custom-control custom-checkbox">
<input type="checkbox" name="del_logo" value="',$_POST['logo'],'"/>
<span class="custom-control-label text-dark pl-2">',$lang['delete_picture'],'</span>
</label>
</div>
</div>
';
}else{
?>
<div class="custom-file">
<input data-num="1" id="advert_logo" type="file" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input" name="logo"/>
<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>
</div>
<?php
}
?>
</div>

		<div class="control-group form-group">
			<div class="form-group rws">
				<label class="form-label text-dark"><?php echo $lang['title'];?></label>
				<input data-tab="firsttab" onClick="show_help('#help_supplier_name');" type="text" maxlength="100" class="form-control mand" id="supplier_name" name="supplier_name" value="<?php echo @$_POST['supplier_name'];?>" placeholder="<?php echo $lang['enter'];?>" />
			<?php echo $help_supplier_name;?>
			</div>
		</div>
		
		<div class="control-group form-group">
			<div class="form-group rws">
				<label class="form-label text-dark">Мета title</label>
				<input data-tab="firsttab" type="text" maxlength="150" class="form-control" id="meta_title" name="meta_title" value="<?php echo @$_POST['meta_title'];?>" placeholder="<?php echo $lang['enter'];?>" />
			</div>
		</div>
		
		<div class="control-group form-group">
			<div class="form-group rws">
				<label class="form-label text-dark">LINK / URL</label>
				<input data-tab="firsttab" type="text" class="form-control" id="link_name" name="link_name" value="<?php echo @$_POST['link_name'];?>" placeholder="<?php echo $lang['enter'];?>" />
			</div>
		</div>
		
		<div class="form-group mt-4 ib">
			<label class="form-label text-dark"><?php echo $lang['business_type'];?></label>
			<?php echo select_business_type(@$_POST['business_type']);?>
		</div>
		
<div class="form-group mt-4 ib ml-5">
<label class="form-label text-dark"><?php echo $lang['selected_plan'];?></label>
<select name="selected_plan" id="selected_plan" class="form-control">
<?php
if(!isset($_POST['selected_plan']) or empty($_POST['selected_plan'])) echo '<option value="0" Selected>',$lang['please_select'],'</option>';
foreach($lang['plans_array'] as $plankey=>$plan){
$selected='';
if($_POST['selected_plan'] == $plankey) $selected='selected="selected"';
echo '<option ',$selected,' value="',$plankey,'">',$plan,'</option>';
}
?>
</select>
</div>


<div class="form-group mt-4">
	<div class="form-group">
		<label class="form-label text-dark"><?php echo $lang['category'];?></label>
		<?php
		echo '<select multiple name="category[]" id="category" class="form-control select2-show-search-prdcat border-bottom-0 mand" data-placeholder="',$lang['ccat'],'">
		<option value="0">',$lang['please_select'],'</option>';
		echo filter_advert_categories(0,@$level,0,$_GET['aid']);			
		echo '</select>';
		//add_listing_categories($_POST['category']);?>
		
	</div>
</div>

		
		
		<div class="control-group form-group rws">
			<div class="form-group">
				<label class="form-label text-dark"><?php echo $lang['website'];?></label>
				<input onClick="show_help('#help_website');" type="text" class="form-control " id="site" name="site" value="<?php echo @$_POST['site'];?>" placeholder="<?php echo $lang['enter'],' ',$lang['website'];?>" />
			<?php echo $help_website;?>
			</div>
		</div>
		
		<div class="control-group form-group rws">
			<div class="form-group">
				<label class="form-label text-dark"><?php echo $lang['mail_orders'];?></label>
				<input data-tab="firsttab" onClick="show_help('#help_email');" type="email" class="form-control mand" id="mail" name="mail" value="<?php echo $_POST['mail'];?>" placeholder="<?php echo $lang['enter'];?>" />
			<?php echo $help_email;?>
			</div>
		</div>
		
		<div class="tac ib w100 vat mt20 pln">
<span class="label bbd tac ib w100"><h4><?php echo $lang['inv_details'];?>:</h4></span>
<div class="control-group form-group tal">
<label class="form-label tal"><?php echo $lang['supplier_name'];?></label>
<input data-tab="firsttab" class="form-control mt10 w98 mand" type="text" id="company_name" name="company_name" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['company_name'];?>"/>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['bulstat'];?></label>
<input data-tab="firsttab" class="form-control mt10 w90 mand" type="text" id="bulstat" name="bulstat" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['bulstat'];?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['vat'];?></label>
<input data-tab="firsttab" class="form-control mt10 w100" type="text" id="dds" name="dds" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['dds'];?>"/>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['city'];?></label>
<input data-tab="firsttab" class="form-control mt10 w90 mand" type="text" id="inv_city" name="inv_city" placeholder="<?php echo $lang['enter'];?>" value="<?php echo $city;?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['address'];?></label>
<input data-tab="firsttab" class="form-control mt10 w100 mand" type="text" id="inv_address" name="inv_address" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['inv_address'];?>"/>
</div>
</div>
</div>

<div class="tac ib w100 vat">
<div class="control-group form-group"><label class="form-label tal"><?php echo $lang['mol'];?></label>
<input data-tab="firsttab" class="form-control mt10 w100 mand" type="text" id="mol" name="mol" placeholder="<?php echo $lang['enter'];?>" value="<?php echo $mol;?>"/>
</div>
</div>
</div>

<ul class="list-inline wizard mb-0 mt-4 px-4 py-4 bg-light border-top">
<li class="previous list-inline-item"></li>
<li class="next list-inline-item float-right"><a class="btn btn-secondary btnNext mb-0"><?php echo $lang['next'];?></a></li>
</ul>
</div>
<div class="tab-pane fade card-body border-top-0" id="second">
<div class="row mb-3 addmsg"><?php echo $lang['addmsg'];?></div>

<div class="row mb-3">
<div class="col-lg-4 col-md-12 mt-3 rws">
	<label class="form-label text-dark"><?php echo $lang['city'];?></label>
	<input data-tab="secondtab" onClick="show_help('#help_city');" id="town" name="town" value="<?php echo $town;?>" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['city'];?>" />
	<?php echo $help_city;?>
</div>

<div class="col-lg-4 col-md-12 mt-3 rws">
	<label class="form-label text-dark"><?php echo $lang['area'];?></label>
	<input data-tab="secondtab" onClick="show_help('#help_area');" id="area" name="area" value="<?php echo @$_POST['area'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'],' ',$lang['area'];?>" />
	<?php echo $help_area;?>
</div>

<div class="col-lg-4 col-md-12 mt-3 rws">
	<label class="form-label text-dark"><?php echo $lang['postcode'];?></label>
	<input id="postcode" name="postcode" value="<?php echo @$_POST['postcode'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'],' ',$lang['postcode'];?>" />
</div>

</div>


<div class="row">
<div class="col-lg-10 col-md-12 mt-3 rws">
	<label class="form-label text-dark"><?php echo $lang['address'];?></label>
	<input data-tab="secondtab" onClick="show_help('#help_address');" id="company_address" name="company_address" value="<?php echo @$_POST['company_address'];?>" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['address'];?>" />
	<?php echo $help_address;?>
</div>

<div class="col-lg-2 col-md-12 mt-3 pr-0 pl-0">
<a onclick="codeAddress();" class="btn btn-primary mt-4 mb-0"><?php echo $lang['get_coo'];?></a>
</div>
</div>
												
<div class="row mt-4">
<div class="col-sm-6">
<div class="form-group rws"><label class="form-label tal"><?php echo $lang['latitude'];?></label>
<input data-tab="secondtab" onClick="show_help('#help_lat');" class="form-control mt10 w90 mand" type="text" id="latitude" name="latitude" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['latitude'];?>"/>
<?php echo $help_lat;?>
</div>
</div>

<div class="col-sm-6">
<div class="form-group rws"><label class="form-label tal"><?php echo $lang['longitude'];?></label>
<input data-tab="secondtab" onClick="show_help('#help_lng');" class="form-control mt10 w100 mand" type="text" id="longitude" name="longitude" placeholder="<?php echo $lang['enter'];?>" value="<?php echo @$_POST['longitude'];?>"/>
<?php echo $help_lng;?>
</div>
</div>
</div>

<div class="w100 mt-4 mapholder">
<label class="form-label ib w100"><?php echo $lang['google_map'];?></label>
<div id="map" class="form-group rws"></div>
</div>

<div class="form-group rws mt-4">
<label class="form-label text-dark"><?php echo $lang['phone']; echo ' ( <small>',$lang['help_phone'],'</small> ) '?></label>
<input data-tab="secondtab" id="company_phones" name="company_phones" value="<?php echo @$_POST['company_phones'];?>" type="text" class="form-control mand" placeholder="<?php echo $lang['enter'],' ',$lang['phone'];?>"/>
</div>
<div class="form-group">
<label class="form-label text-dark"><?php echo $lang['workdays'];?></label>
<select name="work_days[]" id="work_days" class="form-control select2" data-placeholder="<?php echo $lang['ch_days'];?>" multiple>
<?php
if(!isset($_POST['work_days']) or empty($_POST['work_days'])) echo '<option value="ev_day" Selected>',$lang['ev_day'],'</option>';
foreach($lang['days_arr'] as $daykey=>$day){
$selected='';
if(isset($_POST['work_days']) && !empty($_POST['work_days']) && in_array($daykey,$_POST['work_days'])) $selected='selected="selected"';
if(isset($_POST['work_days']) && !empty($_POST['work_days']) && in_array('ev_day',$_POST['work_days'])) $selected='selected="selected"';
echo '<option ',$selected,' value="',$daykey,'">',$day,'</option>';
}
?>
</select>
</div>
<div class="row">
<div class="col-sm-6">
<div class="form-group">
<label class="form-label text-dark"><?php echo $lang['opening'];?></label>

<?php
$start = "00:00";
$end = "23:30";

$tStart = strtotime($start);
$tEnd = strtotime($end);
$tNow = $tStart;
echo '<select name="opening" id="opening" class="form-control select2" data-placeholder="',$lang['worktime'],'">';

while($tNow <= $tEnd){
$selected='';
if(isset($_POST['opening']) && $_POST['opening'] == date("H:i",$tNow)) $selected='selected="selected"';

echo '<option '.$selected.' value="'.date("H:i",$tNow).'">'.date("H:i",$tNow).'</option>';
$tNow = strtotime('+30 minutes',$tNow);
}
echo '</select>';
?>
</div>
</div>
<div class="col-sm-6">
<div class="form-group">
<label class="form-label text-dark"><?php echo $lang['closing'];?></label>
<?php
//$start = "08:00";
//$end = "07:30";

$tStart = strtotime($start);
$tEnd = strtotime($end);
$tNow = $tStart;
echo '<select name="closing" id="closing" class="form-control select2" data-placeholder="',$lang['worktime'],'">';
while($tNow <= $tEnd){
$selected='';
if(isset($_POST['closing']) && $_POST['closing'] == date("H:i",$tNow)) $selected='selected="selected"';
//}else{
//if(date("H:i",$tNow)=='18:00') $selected='selected="selected"';
//}
echo '<option '.$selected.' value="'.date("H:i",$tNow).'">'.date("H:i",$tNow).'</option>';
$tNow = strtotime('+30 minutes',$tNow);
}
echo '</select>';
?>	
</div>
</div>
</div>

<?php
$readonly = 'readonly';
$disabled = 'disabled';
$hlp = '- '.$lang['hlp'];
if($_POST['selected_plan'] > 1){
$readonly = '';
$disabled = '';
$hlp = '';
}
?>

<div class="form-group rws mt-4">
<label class="form-label text-dark"><?php echo $lang['description'];?></label>
<span class="label">
<label class="short ib wa"><?php echo $lang['total_words'];?></label><span id="display_count">0</span><label class="short ib wa"><?php echo $lang['words_left'];?></label>
<span id="word_left">150</span>
<span class="desc_msg"></span></span>
<textarea id="f9" class="form-control" name="description" rows="6" value="<?php echo @$_POST['description'];?>"><?php echo @$_POST['description'];?></textarea>
</div>

<div class="form-group rws">
<label class="form-label text-dark"><?php echo $lang['keywords'];?></label>
<input onClick="show_help('#help_keywords');" id="meta_keywords" name="meta_keywords" value="<?php echo @$_POST['meta_keywords'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'],' ',$lang['keywords'];?>" />
<?php echo $help_keywords;?>
</div>

<div class="form-group">
<label class="form-label text-dark">Мета описание</label>
<textarea id="meta_description" name="meta_description" value="<?php echo @$_POST['meta_description'];?>" class="form-control" placeholder="<?php echo $lang['enter'];?>"><?php echo @$_POST['meta_description'];?></textarea>
</div>		

<div class="form-group mt-4">
<label class="form-label text-dark"><?php echo $lang['p_meth'],' ( <small>',$lang['p_meth_a'],'</small> ) ';?></label>
<input type="hidden" id="fnum" value="<?php echo $lang['images_num'][$_POST['selected_plan']];?>"/>
<select id="pay_methods" name="pay_methods[]" class="form-control select2" data-placeholder="Choose Payment" multiple>
<?php
foreach($lang['pay_methods'] as $paykey=>$payway){
$selected='';
if(isset($_POST['pay_methods']) && !empty($_POST['pay_methods']) && in_array($paykey,$_POST['pay_methods'])) $selected='selected="selected"';
echo '<option ',$selected,' value="',$paykey,'">',$payway,'</option>';
}
?>
</select>
</div>

<div class="form-group mt-4">
<label class="form-label text-dark"><?php echo $lang['certs'];?></label>
<select id="certificates" name="certificates[]" class="form-control select_with_new" data-placeholder="Choose Certification" multiple>
<?php
foreach($lang['certs_arr'] as $certkey=>$cert){
$selected='';
if(isset($_POST['certificates']) && !empty($_POST['certificates']) && in_array($certkey,$_POST['certificates'])) $selected='selected="selected"';
echo '<option ',$selected,' value="',$certkey,'">',$cert,'</option>';
}
?>
</select>
</div>
<?php

$hlp = '- '.$lang['hlp'];
if($_POST['selected_plan'] == 2){
$hlp = '';
}
?>		

<div class="form-group row clearfix mt-5 mb-5">
	<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input <?php echo $disabled;?> name="sellbusiness" id="sellbusiness"  <?php if($_POST['sellbusiness'] > 0) echo 'checked';?> value="" type="checkbox" class="custom-control-input" />
				<span class="custom-control-label text-dark pl-2"><?php echo $lang['sellbusiness'],' ',$hlp;?></span>
			</label>
		</div>
	</div>
	
	<div class="form-group rws">
	<label class="form-label text-dark"><?php echo $lang['business_price']; echo ' ( <small>',$lang['business_price_sm'],'</small> ) '?></label>
	<input readonly id="business_price" name="business_price" value="<?php echo @$_POST['business_price'];?>" type="text" class="form-control" placeholder="<?php echo $lang['enter'],' ',$lang['business_price'];?>"/>
</div>
</div>

<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input name="vip" id="vip"  <?php if($_POST['vip'] > 0) echo 'checked';?> value="" type="checkbox" class="custom-control-input" />
				<span class="custom-control-label text-dark pl-2">vip</span>
			</label>
		</div>
	</div>

<div class="col-lg-12">
	<div class="checkbox checkbox-info">
		<label class="custom-control mt-4 custom-checkbox">
			<input name="active" id="active"  <?php if($_POST['active'] > 0) echo 'checked';?> value="" type="checkbox" class="custom-control-input" />
			<span class="custom-control-label text-dark pl-2">Активен</span>
		</label>
	</div>
</div>

<div class="col-lg-12">
<label class="form-label text-dark">Активен до дата</label>
<input id="datepicker" name="expired_date" value="<?php echo @$_POST['expired_date'];?>" type="text" class="form-control date" placeholder="<?php echo $lang['enter'];?>" />
</div>
	
<h4 class="mt-5 mb-4"><?php echo $lang['social'],' ',$hlp;?></h4>
		<div class="input-group mb-4">
			<div class="input-group-prepend">
				<div class="input-group-text w-7">
					<i class="fa fa-facebook tx-16 lh-0 op-6 text-center mx-auto"></i>
				</div>
			</div><!-- input-group-prepend -->
			<input <?php echo $readonly;?> name="facebook" id="facebook" value="<?php echo @$_POST['facebook'];?>" class="form-control" placeholder="Facebook URL" type="text"/>
		</div>
		<div class="input-group mb-4">
			<div class="input-group-prepend">
				<div class="input-group-text w-7">
					<i class="fa fa-twitter tx-16 lh-0 op-6 text-center mx-auto"></i>
				</div>
			</div><!-- input-group-prepend -->
			<input <?php echo $readonly;?> name="twitter" id="twitter" value="<?php echo @$_POST['twitter'];?>" class="form-control" placeholder="Twitter URL" type="text"/>
		</div>
		<div class="input-group mb-4">
			<div class="input-group-prepend">
				<div class="input-group-text w-7">
					<i class="fa fa-linkedin tx-16 lh-0 op-6 text-center mx-auto"></i>
				</div>
			</div>
			<input <?php echo $readonly;?> name="linkedin" id="linkedin" value="<?php echo @$_POST['linkedin'];?>" class="form-control" placeholder="Linkedin URL" type="text"/>
		</div>
		
		<div class="input-group mb-4">
			<div class="input-group-prepend">
				<div class="input-group-text w-7">
					<i class="fa fa-instagram tx-16 lh-0 op-6 text-center mx-auto"></i>
				</div>
			</div>
			<input <?php echo $readonly;?> name="instagram" id="instagram" value="<?php echo @$_POST['instagram'];?>" class="form-control" placeholder="Instagram URL" type="text"/>
		</div>
<?php
//}
?>
							
							<ul class="list-inline wizard mb-0 mt-4 px-4 py-4 bg-light border-top">
							<li class="previous list-inline-item"><a class="btn btn-primary mb-0 btnPrevious"><?php echo $lang['go_back'];?></a></li>
							<li class="next list-inline-item float-right"><a class="btn btn-secondary btnNext mb-0"><?php echo $lang['next'];?></a></li>
						</ul>
						</div>
						
						
<div class="tab-pane fade card-body border-top-0" id="third">

<div class="form-group mb-5">
<div class="msg imgmsg mt-3 mb-3"></div>
<label class="form-label text-dark"><?php echo $lang['company_images'],' - ',$lang['you_can'],' ',$lang['images_num'][$_POST['selected_plan']],' ',$lang['images'];?></label>
<small class="ib w100"><?php echo $lang['hold_ctrl'];?></small>

<?php
if(count($advert_images) < $lang['images_num'][$_POST['selected_plan']]){
?>
<div class="custom-file"><input multiple onchange="previewImages(this,<?php echo $lang['images_num'][$_POST['selected_plan']];?>);" data-num="<?php echo $lang['images_num'][$_POST['selected_plan']];?>" id="advert_images" type="file" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input" name="images[]"/>
<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>
</div>
<?php
}
?>

<div id="img_preview" class="mb-5">
<?php
if(!empty($advert_images)){
foreach($advert_images as $imgkey=>$image){
$mainimg = '';
if($image['ordering'] == 1) $mainimg = '<div title="'.$lang['main_image_txt'].'" class="arrow-ribbon bg-success">'.$lang['main_image'].'</div>';
echo '<div class="imgdiv">'.$mainimg.'<img class="thumbnail" alt="'.$_POST['supplier_name'].'" src="../images/adverts/',$image['image'],'"/>
<span class="fileinfo">
<label class="form-label text-dark">',$lang['ordering'],'</label>
<input min="1" type="number" class="form-control ordering" data-id="'.$imgkey.'" value="',$image['ordering'],'"/></span>
<button class="remove_file" value="'.$imgkey.'" type="button" title="',$lang['delete_picture'],'"></button></div>';
}
}
?>

</div>
</div>

<!--
<div class="form-group mb-3 mt-5">
<label class="form-label text-dark"><!--?php echo $lang['company_images'],' - ',$lang['you_can'],' ',$lang['images_num'][$_POST['selected_plan']],' ',$lang['images'];?></label>
<input data-num="<!--?php echo $lang['images_num'][$_POST['selected_plan']];?>" id="adv_images" type="file" name="files" accept=".jpg, .png, image/jpeg, image/png" multiple />
</div>-->

<ul class="list-inline wizard mb-0 mt-5 px-4 py-4 bg-light border-top">
<li class="previous list-inline-item"><a class="btn btn-primary mb-0 btnPrevious"><?php echo $lang['go_back'];?></a></li>
<li class="next list-inline-item float-right"><a class="btn btn-secondary btnNext mb-0"><?php echo $lang['next'];?></a></li>
</ul>
</div>


<div class="tab-pane fade card-body border-top-0 p-1" id="fourth">
<?php
echo '<div class="col-xl-12 col-lg-12 col-md-12 p-1">';
echo_adverts_products($_GET['aid'],$_POST['paid'],$_POST['active'],1,$_POST['supplier_name']);
echo '</div>';

?>

<ul class="list-inline wizard mb-0 mt-4 px-4 py-4 bg-light border-top">
<li class="previous list-inline-item"><a class="btn btn-primary mb-0 btnPrevious"><?php echo $lang['go_back'];?></a></li>
<li class="next list-inline-item float-right"><a class="btn btn-secondary btnNext mb-0"><?php echo $lang['next'];?></a></li>
</ul>
</div>

<div class="tab-pane fade card-body border-top-0" id="sixth">
<div style="margin: 80px auto; width: 100%;text-align: center;">
<h2 style="font-size:14px;text-align:center;width:100%">Настройки на crawler-a</h2>
<p>Примери<br />
$title = $html->find('div.prohead > h1',0)->outertext();<br />
$description = $html->find('div.shortd',0)->outertext();<br />
$price = $html->find('div.pricewraper',0)->plaintext;<br />
$strip_tags = $html->find('div.pricewraper',0)->plaintext;<br />
$image = $html->find('div#imgw > img');<br />
$categoryname = $html->find('div.holder > div');
</p>
<p>https://simplehtmldom.sourceforge.io/manual.htm</p>
<p>https://enb.iisd.org/_inc/simple_html_dom/manual/manual.htm</p>

<p>http://nimishprabhu.com/top-10-best-usage-examples-php-simple-html-dom-parser.html</p>
<span id="msg" ></span>
<h1>Ако променяш идентификаторите 1во трябва да запазиш и след това да ТЕСТВАШ !!!</h1>
<table class="table tabtable2"><tbody>
<tr><td align="left" width="500"><label>Заглавие</label><input type="text" id="intitle" name="advert_title_indentificator" value="<?php echo @$_POST['advert_title_indentificator'];?>"/></td><td class=""><div id="intitle1"></div></td></tr>
<tr><td align="left" width="500"><label>Описание</label><input type="text" id="indesc" name="advert_desc_indentificator" value="<?php echo @$_POST['advert_desc_indentificator'];?>"/></td><td class=""><div id="indesc1"></div></td></tr>
<tr><td align="left" width="500"><label>Цена</label><input type="text" id="inprice" name="advert_price_indentificator" value="<?php echo @$_POST['advert_price_indentificator'];?>"/></td><td class=""><div id="inprice1"></div></td></tr>
<tr><td align="left" width="500"><label>Промо Цена</label><input type="text" id="promo_price" name="advert_promo_price_indentificator" value="<?php echo @$_POST['advert_promo_price_indentificator'];?>"/></td><td class=""><div id="promo_price1"></div></td></tr>
<tr><td align="left" width="500"><label>Снимка - За снимката се подава обграждащ div или елемент !!!</label><input type="text" id="inimg" name="advert_img_indentificator" value="<?php echo @$_POST['advert_img_indentificator'];?>"/></td><td class=""><div id="inimg1"></div></td></tr>
<tr><td align="left" width="500"><label>Категория</label><input type="text" id="incatname" name="advert_category_indentificator" value="<?php echo @$_POST['advert_category_indentificator'];?>"/></td><td class=""><div id="incatname1"></div></td></tr>
<tr><td align="center" colspan="2"><label>Моля въведете урл към сайт който желаете да тествате</label><input type="text" id="product_link" name="product_link" value=""/></td></tr>
<tr><td align="center" colspan="2"><p class="test btn btn-secondary">Тествай</p></td></tr>
</tbody></table>
</div>
<ul class="list-inline wizard mb-0 mt-4 px-4 py-4 bg-light border-top">
<li class="previous list-inline-item"></li>
<li class="next list-inline-item float-right"><a class="btn btn-secondary btnNext mb-0"><?php echo $lang['next'];?></a></li>
</ul>
</div>

<div class="tab-pane fade card-body border-top-0" id="seventh">
<div class="ib w100">
<label class="custom-label">Добавяне на полезни документи към този листинг</label>
<a class="btn btn-secondary add_doc"><i class="fa fa-plus text-white"></i> Добави документ</a>
</div>

<?php
if(!empty($advert_docs)){
$c = 0;
foreach($advert_docs as $imgkey=>$image){
$c++;
//var_dump($image);
echo '<div class="ib w100 pr mt-4">
<label class="form-label text-dark">Файл '.$c.' - '.$image['doc_name'].' - <a target="_blank" href="'.WebSite.'/images/files/'.$image['doc_url'].'">'.$image['doc_url'].'</a> | Добавен на '.$image['added_date'].' год.</label>
<span class="fileinfo">
<a class="remove_file" href="'.WebSite.'/adman/index.php?page=edit_advert&aid='.$_GET['aid'].'&deldoc='.$image['id'].'&doc='.$image['doc_url'].'" title="',$lang['delete_picture'],'"></a>
</span>
</div>';

}
}
?>

<div id="mainclone" class="custom_docs_file mt-2 dn">
<div class="input-group mb-0">
<input class="form-control dname" placeholder="За какво е документа?" type="text" name="doc_name[]" value=""/>
</div>
<div class="custom-file mt-3">
<input class="custom-file-input docs" name="docs[]" type="file" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf, image/*" />
<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>
</div>
</div>
</div>
<div class="tab-pane fade card-body border-top-0" id="fifth">

<?php
//if($advertrow['paid'] < 1){
?>
<div class="panel panel-secondary">
<div class=" tab-menu-heading border-0 pl-0 pr-0 pt-0">
<h4 class="mt-2 mb-4"><?php echo $lang['how_pay'];?></h4>
<div class="tabs-menu1 ">
<!-- Tabs -->
<ul class="nav panel-tabs">
<li><a href="#tab5" onclick="set_pay('<?php echo $lang['c_bank'];?>','c_bank');" <?php if($_POST['selected_pay_way'] =='c_bank') echo 'class="active"';?> data-toggle="tab"><?php echo $lang['pay_methods']['bank'];?></a></li>
<li><a href="#tab6" onclick="set_pay('<?php echo $lang['c_easy'];?>','c_easy');" <?php if($_POST['selected_pay_way'] =='c_easy') echo 'class="active"';?> data-toggle="tab"><?php echo $lang['pay_methods']['easypay'];?></a></li>
<li><a href="#tab7" onclick="set_pay('<?php echo $lang['c_card'];?>','c_card');" <?php if($_POST['selected_pay_way'] =='c_card') echo 'class="active"';?> data-toggle="tab"><?php echo $lang['pay_methods']['card'];?></a></li>
</ul>
</div>
</div>
<div class="panel-body tabs-menu-body pl-0 pr-0 border-0">
<div class="tab-content">
<div class="tab-pane active " id="tab5">
<h4 class="mt-3 mb-4"><?php echo $lang['bank_txt'];?></h4>
<div class="form-group">
<label class="form-label" ><?php echo $lang['supplier_name'];?></label>
<input disabled="" class="form-control" value="ОПМ БГ ООД"/>
</div>

<div class="form-group">
<label class="form-label" ><?php echo $lang['prib'];?></label>
<input disabled="" class="form-control" value="Първа инвестиционна банка АД"/>
</div>
											
<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal">IBAN</label>
<input disabled="" class="form-control mt10 w90" type="text" value="BG25FINV91501016539791"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal">BIC</label>
<input disabled="" class="form-control mt10 w100" type="text" value="FINVBGSF"/>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['osn'];?></label>
<input disabled="" class="form-control mt10 w90" type="text" value="<?php echo $lang['reg_txt'],' - ',$lang['plans_array'][$_POST['selected_plan']],' | ',date('Y'),' ',$lang['y'];?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['sum'];?></label>
<input disabled="" class="form-control mt10 w100" type="text" value="<?php echo $advertrow['amount'],' ',current_currency;?>"/>
</div>
</div>
</div>
<p class="mb-0"><?php echo $lang['easypay_txt2'];?></p>
</div>
										
<div class="tab-pane " id="tab6">
	<h6 class="font-weight-semibold"><?php echo $lang['easypay_txt'];?></h6>
<div class="form-group">
<label class="form-label" ><?php echo $lang['supplier_name'];?></label>
<input disabled="" class="form-control" value="ОПМ БГ ООД"/>
</div>

<div class="form-group">
<label class="form-label" ><?php echo $lang['prib'];?></label>
<input disabled="" class="form-control" value="Първа инвестиционна банка АД"/>
</div>
											
<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal">IBAN</label>
<input disabled="" class="form-control mt10 w90" type="text" value="BG25FINV91501016539791"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal">BIC</label>
<input disabled="" class="form-control mt10 w100" type="text" value="FINVBGSF"/>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['osn'];?></label>
<input disabled="" class="form-control mt10 w90" type="text" value="<?php echo $lang['reg_txt'],' - ',$lang['plans_array'][$_POST['selected_plan']],' | ',date('Y'),' ',$lang['y'];?>"/>
</div>
</div>

<div class="col-sm-6">
<div class="form-group"><label class="form-label tal"><?php echo $lang['sum'];?></label>
<input disabled="" class="form-control mt10 w100" type="text" value="<?php echo $advertrow['amount'],' ',current_currency;?>"/>
</div>
</div>
</div>
<p class="mb-0"><?php echo $lang['easypay_txt2'];?></p>
</div>

<div class="tab-pane " id="tab7">
	<div class="control-group form-group">
		<div class="form-group">
			<label class="form-label text-dark"><?php echo $lang['paypal_txt'];?></label>
			
		</div>
	</div>
</div>
										
		</div>
	</div>
</div>

<div id="paymentdiv" class="form-group row clearfix">
<div class="col-lg-12">
	<div class="checkbox checkbox-info">
		<label class="custom-control mt-4 custom-checkbox">
			<input id="payment" name="selected_pay_way" <?php if(isset($_POST['selected_pay_way'])) echo 'checked';?> type="checkbox" value="c_bank" class="custom-control-input" />
			<span id="payment_txt" class="custom-control-label text-dark pl-2"><?php echo $lang[$_POST['selected_pay_way']];?></span>
		</label>
	</div>
</div>
</div>
<?php
//}

if($advertrow['paid'] > 0) echo '<div class="col-lg-12"><h3 class="card-title">',$lang['paid_to'],': ',date("d-m-Y", strtotime(date("d-m-Y", strtotime($advertrow['paid_date'])) . " + 1 year")),'</h3></div>';
?>

<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input name="paid" id="paid" value="" <?php if($_POST['paid'] > 0) echo 'checked';?> type="checkbox" class="custom-control-input" />
				<span class="custom-control-label text-dark pl-2">Листинга е Платен</span>
			</label>
		</div>
	</div>

<div class="col-lg-12 mt-3 mb-3">
<label class="form-label text-dark">Дата плащане</label>
<input name="paid_date" value="<?php echo @$_POST['paid_date'];?>" type="text" class="form-control date" placeholder="<?php echo $lang['enter'];?>" />
</div>

<div class="col-lg-12">
<div class="form-group"><label class="form-label tal"><?php echo $lang['sum'];?></label>
<input name="amount" class="form-control short" type="text" value="<?php echo $_POST['amount'];?>"/><?php echo current_currency;?>
</div>
</div>
	
<div class="col-lg-12">
		<div class="checkbox checkbox-info">
			<label class="custom-control mt-4 custom-checkbox">
				<input name="sendmail" id="sendmail" value="" type="checkbox" class="custom-control-input" />
				<span class="custom-control-label text-dark pl-2">Изпрати мейл че профила е одобрен</span>
			</label>
		</div>
	</div>							
							

							<ul class="list-inline wizard mb-0 mt-4 px-4 py-4 bg-light border-top">
							<li id="lastback" class="previous list-inline-item"><a  class="btn btn-primary mb-0 btnPrevious"><?php echo $lang['go_back'];?></a></li>
							<li id="saveadvert" class="next list-inline-item float-right"><button onclick="return aa();" type="submit" id="save_advert" class="btn btn-secondary mb-0" name="save_advert" value="save"><?php echo $lang['edit_prd'];?></button></li>
						</ul>
						</div>
						
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
					
				</div> <!-- end row -->
			</div>
		</section>
	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="deletediv" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
						<h5 class="modal-title ib w100"><?php echo $lang['pl_con'];?></h5><hr/>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
						<span class="ib w100 tac formsg" ><?php echo $lang['are_you_s'];?></span>
					</div>
					<div class="modal-body tac">
						<button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $lang['canc'];?></button>
						<button type="button" id="confirm_del" data-id="" class="btn btn-success ml-3"><?php echo $lang['confirm'];?></button>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>
<?php
}else{
//header("location: ../index.php");
echo '<script>window.location.href = "../index.php";</script>';
exit(0);
}
?>