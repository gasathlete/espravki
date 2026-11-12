<?php
session_start();
require './config.php';
require './project_functions.php';

require './translation/'.$_SESSION['lang'].'_lang.php';

if(isset($_SESSION['customer']) && !empty($_SESSION['customer']) && intval($_SESSION['customer']) > 0){
if(empty($_GET['aid']) || intval($_GET['aid']) < 1){
header('Location: '.WebSite.'/mydash.php?my_listings=show');
exit(0);
}

$cquery='select site from adverts where id = "'.mysql_real_escape_string($_GET['aid']).'" and active="1" and customer_id = "'.$_SESSION['customer'].'"';
$cresult=mysql_query($cquery) or die(send_error($cquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$cnum_rows = mysql_num_rows($cresult);
if($cnum_rows < 1){
header('Location: '.WebSite.'/mydash.php?my_listings=show');
exit(0);
}

function echo_meta(){
	global $lang;
	echo '<title>',$lang['profile_visits_statistics'],'</title>';
	echo '<meta name="description" content="',$lang['site_visits_statistics'],'" />';
	echo '<meta name="keywords" content="',$lang['site_visits_statistics'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['profile_visits_statistics'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['site_visits_statistics'],'" />';

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
<meta name="author" content="Web-site-maker.eu">

<link rel="icon" type="image/png" href="<?php echo WebSite;?>/favicon.ico" />

<link href="./assets/css/style.css" rel="stylesheet" />
<link href="./assets/plugins/charts-c3/c3-chart.css" rel="stylesheet" />
<link href="./assets/plugins/morris/morris.css" rel="stylesheet" />
</head>
<body>
<?php include './modules/header.php';
?>
<section>
			<div class="bannerimg cover-image bg-background3" data-image-src="./assets/images/banners/banner2.jpg">
				<div class="header-text mb-0">
					<div class="container">
						<div class="text-center text-white ">
							<h1 class=""><?php echo $lang['site_visits_statistics'];?></h1>
							<ol class="breadcrumb text-center">
								<li class="breadcrumb-item"><a href="./"><?php echo $lang['home'];?></a></li>
								<li class="breadcrumb-item"><?php echo $lang['site_visits_statistics'];?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</section>
		

		<!--User Profile-->
		<section class="sptb">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<div class="wideget-user">
									<div class="row">
										<div class="col-lg-12 col-md-12">
											<div class="wideget-user-desc text-center">
												<div>
													<img src="./images/logo_1.png" alt="<?php echo official_mail_sender_name;?>"/>
												</div>
												<div class="user-wrap wideget-user-info">
													<a href="<?php echo WebSite;?>" class="text-white"><h4 class="font-weight-semibold text-black"><?php echo $lang['find_us'];?></h4></a>
													
												</div>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 text-center">
											<div class="wideget-user-info ">
												<div class="wideget-user-icons mt-2">
													<a target="_blank" href="<?php echo facebook_page;?>" class="facebook-bg mt-0"><i class="fa fa-facebook"></i></a>
													<a target="_blank" href="<?php echo twitter_page;?>" class="twitter-bg"><i class="fa fa-twitter"></i></a>
													<a target="_blank" href="<?php echo linkedin;?>" class="social-icon"><i class="fa fa-linkedin"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="wideget-user-tab wideget-user-tab3">
					
							<div class="tab-menu-heading">
								<div class="tabs-menu1">
									<ul class="nav">
										<li class=""><a href="#tab-5" class="active to_scroll" data-toggle="tab"><?php echo $lang['site_visits'];?></a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="br-4 mb-0 overflow-hidden border br-tl-0 bg-white">
							<div class="card-body">
								<div class="border-0">
									<div class="tab-content">
										<div class="tab-pane active" id="tab-5">
											<div class="profile-log-switch">
<?php


$chart = '';
$chart_data = '';
$yearcarray = array();

$total=0;
$table= '												
<div class="table-responsive card-body border-0 dragscroll">
<table class="table ffo table-bordered border-top mb-0 mt-1">
<thead>
<tr>
<th class="bg-secondary tac text-white">#</th>
<th class="bg-secondary tac text-white">'.$lang['my'].'</th>
<th class="bg-secondary tac text-white">'.$lang['visits'].'</th>
</tr>
</thead>
<tbody>';
$query='select count(sitevisited) as counter, MONTHNAME(addeddate) as monthname, MONTH(addeddate) as month, YEAR(addeddate) as year from statistics_adverts_visits where sitevisited="1" and advert_id = "'.mysql_real_escape_string($_GET['aid']).'" ';
if(isset($_GET['year']) && $_GET['year'] > 0) $query.=' and YEAR(addeddate) = "'.mysql_real_escape_string($_GET['year']).'"';
$query.=' GROUP BY YEAR(addeddate) ASC, MONTH(addeddate) ASC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));

$query2='select count(sitevisited) as counter, MONTHNAME(addeddate) as monthname, MONTH(addeddate) as month, YEAR(addeddate) as year from statistics_adverts_visits where advert_id = "'.mysql_real_escape_string($_GET['aid']).'" GROUP BY YEAR(addeddate) ASC, MONTH(addeddate) ASC';
$result2=mysql_query($query2) or die(send_error($query2,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);

$num_rows2 = mysql_num_rows($result2);



if($num_rows > 0){

for($a=0;$a<$num_rows2;$a++){
$row2=mysql_fetch_assoc($result2);
if(!In_array($row2["year"],$yearcarray)) $yearcarray[]=$row2["year"];
}

$chart.='
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card mb-5">
			<div class="card-header">
				<h3 class="card-title">'.$lang['gr'].'</h3>
			</div>
			<div class="card-body">
				<div id="chart" class="chartsh"></div>
			</div>
		</div>
	</div>
</div>
';
echo $chart;
for($i=0;$i<$num_rows;$i++){

$row=mysql_fetch_assoc($result);
$total+=$row["counter"];
$chart_data .= "{ year:'".$row["year"]."', month:".$row["month"].", visits:".$row["counter"].", fperiod:'".$lang['months'][$row["month"]]." ".$row["year"]."'}, ";
$table.='<tr>
<td>'.($i+1).'</td>
<td>'.$lang['months'][$row["month"]].' '.$row['year'].'</td>
<td>'.$row['counter'].'</td>
</tr>';

	}
$chart_data = substr($chart_data, 0, -2);
$table.='<tr><th colspan="2" class="bg-light tar">'.$lang['total_sh'].'</th><th class="bg-light">'.$total.'</th></tr>';
}else $table.='<tr><td colspan="3" class="tac">'.$lang['no_results'].'</td></tr>';
$table.='</tbody>
</table>
</div>';

//var_dump($yearcarray);


echo '<div class="col-lg-12 col-md-12 tac mt-4">
<div class="w48 ib">
<form name="yearform" method="get" action="">
<input type="hidden" name="aid" value="',$_GET['aid'],'"/>
<select onchange="this.form.submit();" class="mt-5 form-control maxw200" name="year"><option value="0">'.$lang['ay'].'</option>';
foreach($yearcarray as $year){
$selected = '';
if(isset($_GET['year']) && $_GET['year'] == $year) $selected = 'selected="selected"'; 
echo '<option ',$selected,' value="',$year,'">',$year,'</option>';
}
echo '</select></form></div>';

echo '<div class="w48 ib tar vab"><a target="_blank" class="btn btn-success" href="profile_visits.php?aid='.$_GET['aid'].'">'.$lang['profile_visits'].' <i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>';

echo '</div>';
echo $table;
?>											</div>
							</div>
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>

<?php
//var_dump($chart_data);
include './modules/footer.php';?>	

<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<script src="./assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="./assets/js/fullfunctions.js"></script>
<script src="./assets/js/contacts.js"></script>
<script src="./assets/plugins/morris/morris.js"></script>

<!-- Input Mask Plugin -->
<script src="./assets/plugins/input-mask/jquery.mask.min.js"></script>
<script src="./assets/plugins/morris/raphael-min.js"></script>



<script>
const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];// $chart_data .= "{ year:'".$row["year"]."', month:".$row["month"].", monthname:".$row["monthname"].", visits:".$row["counter"]."}, ";
//console.log(<?php echo $chart_data; ?>);
Morris.Bar({
 element : 'chart',
 data:[<?php echo $chart_data; ?>],
 xkey:'fperiod',
 ykeys:['visits'],
 parseTime: false,
 labels:[messages.v],
 gridTextColor: "#333",
 stacked: false,
 resize: true,
barColors: ["#0f5b0a", "#fff", "#1AB244", "#B29215"],
hideHover: 'auto',
pointFillColors:['#ffffff'],
      pointStrokeColors: ['black'],
      lineColors:['gray','red'],
      behaveLikeLine: true
});

/*
Morris.Bar({
  element: 'chart',
  data: [
    { y: '2006', a: 100, b: 90 },
    { y: '2006', a: 75,  b: 65 },
    { y: '2006', a: 50,  b: 40 },
    { y: '2009', a: 75,  b: 65 },
    { y: '2010', a: 50,  b: 40 },
    { y: '2011', a: 75,  b: 65 },
    { y: '2012', a: 100, b: 90 }
  ],
  xkey: 'y',
  ykeys: ['a', 'b'],
  labels: ['Series A', 'Series B']
});
*/
</script>




	</body>
</html>
<?php
}else{
header('Location: '.WebSite);
exit(0);
}
?>