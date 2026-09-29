<?php
session_start();
require '../config.php';
if(isset($_SESSION['affiliate']) && intval($_SESSION['affiliate']) > 0){

require '../project_functions.php';
require '../translation/'.$_SESSION['lang'].'_lang.php';


function echo_meta(){
	global $lang;
	echo '<title>',$lang['my_orders'],'</title>';
	echo '<meta name="description" content="',$lang['my_orders'],'" />';
	echo '<meta name="keywords" content="',$lang['my_orders'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['my_orders'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['my_orders'],'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
}
?>
<!doctype html>
<html lang="en" dir="ltr">

	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<?php echo echo_meta();?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="author" content="Web-site-maker.eu"/>
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
	<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/banner2.jpg">
		<div class="header-text mb-0">
			<div class="container">
				<div class="text-center text-white">
					<h1 class=""><?php echo $lang['dashb'];?></h1>
					<ol class="breadcrumb text-center">
						<li class="breadcrumb-item"><a href="./index.php"><?php echo $lang['home'];?></a></li>
						<li class="breadcrumb-item active text-white" aria-current="page"><?php echo $lang['aff'];?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
		<!--Breadcrumb-->
<section class="sptb">
<div class="container">
	<div class="row">
		<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card">
		<div class="card-header">
			<h1 class="card-title"><?php echo $lang['ur_aff'];?></h1>
		</div>
<?php
echo '<div class="card-body">
<div class="table-responsive border-top dragscroll">
<table class="table table-bordered table-hover text-nowrap">
<thead>
<tr>
<th>#</th>
<th class="maxw200 overflow-hidden">',$lang['supplier_name'],'</th>
<th>',$lang['reg_date'],'</th>
<th>',$lang['till_date'],'</th>
<th>',$lang['listing_type'],'</th>
<th>',$lang['p_date'],'</th>
<th>',$lang['sum'],'</th>
<th>',$lang['commission'],'</th>
<th>',$lang['status'],'</th>
</tr>
</thead>
<tbody>';

$query = 'select * from adverts where affiliate_id = "'.mysql_real_escape_string($_SESSION['affiliate']).'" order by added_date DESC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);
if($num_rows > 0){
$total = 0;
$totalcommission = 0;
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);

$total+=$row['amount'];
$coname = $row['company_name'];
if(empty($row['company_name'])) $coname = $row['supplier_name'];

$exp_date = $row['expired_date'];
if($row['expired_date'] == '0000-00-00') $exp_date = '-';

$paid_date = $row['paid_date'];
if($row['paid_date'] == '0000-00-00') $paid_date = '-';

$commission = number_format(((($row['amount']/1.2)*$_SESSION['commission'])/100),2);
$totalcommission+=$commission;
echo '<tr>
<td>',($i+1),'</td>
<td class="maxw300p overflow-hidden">',$coname,'</td>
<td class="tac">',date('d-m-Y',strtotime($row['added_date'])),'</td>
<td class="tac">',$exp_date,'</td>
<td>',$lang['plans_array'][$row['selected_plan']],'</td>
<td>',$paid_date,'</td>
<td>',number_format($row['amount'],2),' ',$lang['bgn'],'</td>
<td class="tac">',$commission,' ',$lang['bgn'],'</td>
<td>',$lang['p_active_status'][$row['active']],'</td>
</tr>';
	}
	echo '<tr><td class="tar fwb" colspan="6">',$lang['total_sh'],'</td><td class="tac">',number_format($total,2),' ',$lang['bgn'],'</td><td class="tac">',number_format($totalcommission,2),' ',$lang['bgn'],'</td><td></td></tr>';
}else echo '<tr><td class="tac" colspan="9">',$lang['no_affiliates'],'</td></tr>';

echo '
</tbody>
</table>';
?>				
					</div>	
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
<script src="../assets/js/profile.js"></script>

	</body>
</html>
<?php
}else{
header("location: ./login.php");
exit(0);
}
?>