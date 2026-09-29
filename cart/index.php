<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require '../config.php';
require '../project_functions.php';
require '../translation/'.$_SESSION['lang'].'_lang.php';

if(isset($_SESSION['customer']) && intval($_SESSION['customer']) > 0){
function echo_meta(){
	global $lang;
	echo '<title>',$lang['u_bas'],'</title>';
	echo '<meta name="description" content="',sprintf($lang['your_b'], count($_SESSION['products'])),'" />';
	echo '<meta name="keywords" content="',$lang['u_bas'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['u_bas'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'/cart/" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="'.sprintf($lang['your_b'], count($_SESSION['products'])).'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';	
}


$query = 'select * from customers where id = "'.mysql_real_escape_string($_SESSION['customer']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){

$row=mysql_fetch_assoc($result);
$names = explode(' ',$row['customer_names']);
$_POST['first_name'] = $names[0];
$_POST['second_name'] = $names[1];
$_POST['customer_email'] = $row['customer_email'];
$_POST['customer_phone'] = $row['customer_phone'];
$_POST['customer_address'] = $row['customer_address'];
$_POST['city'] = $row['city'];
$_POST['postcode'] = $row['postcode'];
$_POST['profile_image'] = $row['profile_image'];

$_POST['about_me'] = $row['about_me'];
$_POST['facebook'] = $row['facebook'];
$_POST['linkedin'] = $row['linkedin'];

if(isset($_GET['msg'])) $msg = $lang['suc_save'];

}
?>
<!doctype html>
<html lang="en" dir="ltr">

	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<?php echo echo_meta();?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="author" content="Web-site-maker.eu">
		
		<link rel="icon" type="image/png" href="<?php echo WebSite;?>/favicon.ico" />

		<link href="../assets/css/style.css" rel="stylesheet" />
		<link href="../assets/iconfonts/typicons/typicons.css" rel="stylesheet" />

	</head>
	<body>
<?php
include '../modules/header.php';
?>


		<!--Breadcrumb-->
<section>
	<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/shop2.jpg">
		<div class="header-text mb-0">
			<div class="container">
				<div class="text-center text-white">
					<h1 class=""><?php echo $lang['u_bas'];?></h1>
					<ol class="breadcrumb text-center">
						<li class="breadcrumb-item"><a href="../products/"><?php echo $lang['home'];?></a></li>
						<li class="breadcrumb-item active text-white" aria-current="page"><?php echo $lang['u_bas'];?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
		<!--Breadcrumb-->

		<!--User Dashboard-->
		<section class="sptb">
			<div class="container">
				<div class="row">
					<div class="col-xl-3 col-lg-12 col-md-12">
						<form id="profileform" action="" name="productform" method="post" enctype="multipart/form-data" class="form-horizontal mb-0">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['dashb'];?></h3>
							</div>
							<div class="card-body text-center item-user border-bottom">
								<div class="profile-pic">
									<div class="profile-pic-img">
									<?php
									$profile_image = '25.jpg';
									if(!empty($row['profile_image'])) $profile_image = $row['profile_image'];
									?>
										<span class="bg-success dots" data-toggle="tooltip" data-placement="top" title="online"></span>
										<img src="../images/users/<?php echo $profile_image;?>" class="brround" alt="<?php echo $row['customer_names'];?>" />
									</div>
									<a href="userprofile.html" class="text-dark"><h4 class="mt-3 mb-0 font-weight-semibold"><?php echo $row['customer_names'];?></h4></a>
								</div>
							</div>
							<div class="item1-links mb-0">
								<a href="../mydash.php?profile=show" class="d-flex border-bottom">
									<span class="icon1 mr-2"><i class="typcn typcn-edit fs-20"></i></span> <?php echo $lang['p_edit'];?>
								</a>
								<a href="../mydash.php?my_listings=show" class="d-flex border-bottom">
									<span class="icon1 mr-2"><i class="typcn typcn-briefcase fs-20"></i></span> <?php echo $lang['my_b'];?>
								</a>
								
								<a href="../mydash.php?my_products=show" class="d-flex border-bottom">
									<span class="icon1 mr-2"><i class="typcn typcn-folder fs-20"></i></span> <?php echo $lang['my_p'];?>
								</a>
								
								<a href="../mydash.php?my_orders=show" class="d-flex border-bottom">
									<span class="icon1 mr-2"><i class="typcn typcn-shopping-cart fs-20"></i></span> <?php echo $lang['my_orders'];?>
								</a>
								
								<a class=" active d-flex border-bottom">
									<span class="icon1 mr-2"><i class="fa fa-shopping-basket fs-20"></i></span> <?php echo $lang['u_bas'];?>
								</a>
								
								<a href="../logout.php" class="d-flex">
									<span class="icon1 mr-2"><i class="typcn typcn-power-outline fs-20"></i></span> <?php echo $lang['logout'];?>
								</a>
							</div>
						</div>
						
						<?php
						if(isset($_SESSION['products']) && count($_SESSION['products']) > 0){
						echo '<div class="card mb-xl-0">
							<div class="card-header">
								<h3 class="card-title">',$lang['suc_buys'],'</h3>
							</div>
							<div class="card-body p-0">
								<ul class="list-unstyled widget-spec  mb-0">
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> ',$lang['ch1'],'
									</li>
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> ',$lang['ch2'],'
									</li>
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> ',$lang['ch3'],'
									</li>
								</ul>
							</div>
						</div>';
						}else{
						echo '<div class="card mb-xl-0">
							<div class="card-header">
								<h3 class="card-title">',$lang['suc_sales'],'</h3>
							</div>
							<div class="card-body p-0">
								<ul class="list-unstyled widget-spec  mb-0">
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> ',$lang['reg_f'],'
									</li>
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> ',$lang['add_offer'],'
									</li>
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> ',$lang['sale_on'],'
									</li>
								</ul>
							</div>
						</div>';
						}
						?>
			</form>
					</div>
					
					<div class="col-xl-9 col-lg-12 col-md-12">
						<div class="card mb-0 overflow-hidden">
							<div class="card-header">
								<h3 class="card-title scroller"><?php echo sprintf($lang['your_b'], count($_SESSION['products']));?></h3>
							</div>
							<div class="card-body">
							<div class="ib w100 orange tac mt-3 mb-0"><?php echo $msg;?></div>
								<div class="row">
									<?php
//var_dump($_SESSION['products']);
//var_dump($_SESSION);

$delivery_address = '';
if(!empty($_SESSION['customer_address'])) $delivery_address = $_SESSION['customer_address'];

$phone = '';
if(!empty($_SESSION['customer_phone'])) $phone = $_SESSION['customer_phone'];

echo '<div class="table-responsive border-top dragscroll">
<table class="table table-bordered table-hover text-nowrap cart">
<thead>
<tr>
<th>#</th>
<th class="maxw200 overflow-hidden">',$lang['product'],'</th>
<th>',$lang['qty_short'],'</th>
<th>',$lang['prd_price'],'</th>
<th>',$lang['options'],'</th>
<th>',$lang['total_sh'],'</th>
<th>',$lang['act'],'</th>
</tr>
</thead>
<tbody>';
if(isset($_SESSION['products']) && count($_SESSION['products']) > 0){
$count = 0;
$total = 0;

$adverts_array = array();
foreach($_SESSION['products'] as $pkey=>$pval){

$queryp = 'select p.advert_id, p.title, p.price,p.promo_price, p.product_url, p.category_url, a.supplier_name from products p, adverts a where 
p.id = "'.mysql_real_escape_string($pkey).'" and p.advert_id = a.id group by p.id order by p.advert_id ASC, p.id asc';
$presult=mysql_query($queryp) or die(send_error($queryp,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($presult);


if($num_rows > 0){
$prow=mysql_fetch_assoc($presult);

if(!array_key_exists($prow['advert_id'],$adverts_array)){
$adverts_array[$prow['advert_id']][] = $prow['advert_id'];
		}


$product_options = echo_product_options_new_basket($pkey);
//var_dump($product_options);echo $pkey,' ima slednite opt<br/><br/>';
$count++;

$optionhtml = '';
if(!empty($product_options)){
foreach($product_options as $optionkey=>$optionval){
$optionhtml.='<div class="db w100 mt-1 mb-1"><select name="options['.$pkey.'][]" data-id="'.$pkey.'" data-name="'.$optionkey.'" id="'.$optionkey.''.$count.'" class="form-control select_option" data-placeholder="">
<option value="0">'.$lang['select'].' '.$optionkey.'</option>';
foreach($optionval as $optionvalname){
$selected = '';
if(isset($_SESSION['product_options'][$pkey]) && in_array($optionvalname,$_SESSION['product_options'][$pkey])) $selected = 'selected="selected"';
$optionhtml.='<option '.$selected.' value="'.$optionvalname.'">'.$optionvalname.'</option>';

		}
$optionhtml.='</select></div>';
	}
}

if($prow['promo_price'] > 0){
$total+= ($pval * $prow['promo_price']);
$price = $prow['promo_price'];
}
else{
$total+= ($pval * $prow['price']);
$price = $prow['price'];
}
echo '<tr id="pr_',$pkey,'" class="product">
<td>',$count,'</td>
<td class="w200p overflow-hidden"><a href="',WebSite,'/products/',$prow['category_url'],'/',$prow['product_url'],'" target="_blank">',$prow['title'],'</a><br/>/<small>',$prow['supplier_name'],'</small>/</td>
<td class="tac"><input disabledrag min="1" max="100" type="number" data-id="'.$pkey.'" class="form-control qty w60p" name="qty['.$pkey.']" value="',$pval,'"/></td>
<td><div><div class="ib wa"><input readonly type="text" class="form-control pr_price w100p" name="price['.$pkey.']" value="',$price,'"/></div> ',current_currency,'</div></td>
<td>',$optionhtml,'</td>
<td><div class="ib w100p tac"><div class="ib wa subttl">',number_format(($pval * $price), 2,".", "," ),'</div> ',current_currency,'</div></td>
<td class="tac"><a class="btn btn-danger btn-sm text-white rem_from_bask" data-id="',$pkey,'" data-toggle="tooltip" data-original-title="',$lang['del_prd_bask'],'"><i class="fa fa-trash-o"></i></a></td>
</tr>';
		}
	}
		echo '<tr><td class="tal" colspan="7"><label><strong>',$lang['del_cost_txt'],'</strong></label></td></tr>';

	echo '<tr><td class="tar" colspan="5"><label><strong>',$lang['total'],'</strong></label></td><td><div class="ib w100 tac"><div id="total" class="ib wa fwb">',number_format($total, 2,".", "," ),'</div> ',current_currency,'</div></td><td></td></tr>';


echo '<tr><td colspan="4">
	<label><strong>',$lang['ur_ph'],'</strong></label>
	<input disabledrag type="text" class="form-control" onkeypress="return(numberFormat(event));" name="phone" id="phone" value="',$phone,'" placeholder="',$lang['enter'],'" />
	</td><td colspan="3"></td></tr>';
	
	echo '<tr><td colspan="4">
	<label><strong>',$lang['delivery_address'],'</strong></label>
	<input class="form-control" disabledrag type="text" id="delivery_address" placeholder="',$lang['enter'],'" name="delivery_address" value="',$delivery_address,'" />

	</td><td colspan="3"></td></tr>';
	
	//echo count($adverts_array);
	if(count($adverts_array) < 2){
	echo '<tr><td colspan="4">
	<label><strong>',$lang['ord_com'],'</strong></label>
	<textarea disabledrag class="form-control" name="comments" id="comments" value="" rows="6"></textarea>
	</td><td colspan="3"></td></tr>';
	}

		echo '<tr><td disabledrag class="tal" colspan="7"><div class="omsg db orange w100">
		<label class="custom-control custom-checkbox mb-3">
		<input type="checkbox" class="custom-control-input terms" name="terms" value="1"/>
		<span class="custom-control-label">',$lang['ac_ord_terms'],'</span>
		</label>
		</div></td></tr>';

	echo '<tr><td class="tal" colspan="7"><div class="omsg db orange w100"></div><button disabledrag type="submit" name="send_order" id="send_order" class="btn btn-secondary">',$lang['send_ord'],'</button></td></tr>';
}else echo '<tr><td class="tal" colspan="7"><label>',$lang['u_bas_empty'],'</label></td></tr>';

echo '</tbody></table></div>';
?>
								</div>
							</div>
							<div class="card-footer">
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</section>

<?php include '../modules/footer.php';?>		
<script src="../assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="../assets/js/fullfunctions.js"></script>
<script src="../assets/js/jquery-ui.js"></script>
<script src="../assets/js/shop.js?t=<?php echo time();?>"></script>

	</body>
</html>
<?php
}else{
header('Location: '.WebSite.'/userlogins/');
exit(0);
}
?>