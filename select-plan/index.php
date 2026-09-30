<?php
session_start();
header("Content-Type: text/html; charset=utf-8");

require '../config.php';
require '../project_functions.php';

require '../translation/'.$_SESSION['lang'].'_lang.php';


function echo_meta(){
	global $lang;
	echo '<title>',$lang['meta_news_add']['title'],'</title>';
	echo '<meta name="description" content="',$lang['meta_news_add']['description'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_news_add']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['meta_news_add']['title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'/select-plan/" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',official_site_name,'" />';
	echo '<meta property="og:description" content="',$lang['meta_news_add']['description'],'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--html xmlns="http://www.w3.org/1999/xhtml"-->
<html itemscope itemtype="http://schema.org/LocalBusiness" lang="BG" dir="ltr">

<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<?php echo echo_meta();?>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="author" content="Web-site-maker.eu">
<link rel="icon" type="image/png" href="<?php echo WebSite;?>/favicon.ico" />

<!--
<link href="../../assets/plugins/bootstrap-4.4.1-dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="../../assets/css/style-small.css" rel="stylesheet" />
<link href="../../assets/css/icons.css" rel="stylesheet"/>
<link href="../../assets/plugins/horizontal/horizontal-menu/horizontal.css" rel="stylesheet" />
<link href="../../assets/plugins/select2/select2.min.css" rel="stylesheet" />
<link href="../../assets/plugins/cookie/cookie.css" rel="stylesheet"/>
<link href="../../assets/plugins/autocomplete/jquery.autocomplete.css" rel="stylesheet"/>
<link href="../../assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet" />
<link id="theme" href="../../assets/css/feather.css"  rel="stylesheet"/>
<link id="theme" href="../../assets/color-skins/color1.css"  rel="stylesheet"/>
-->

<link href="../../assets/plugins/bootstrap-4.4.1-dist/css/bootstrap.min.css" rel="stylesheet" />

		<!-- Dashboard Css -->
		<link href="../../assets/css/style-small.css" rel="stylesheet" />

		<!-- Font-awesome  Css -->
		<link href="../../assets/css/icons.css" rel="stylesheet"/>
		<link href="../../assets/css/feather.css" rel="stylesheet"/>
		<link href="../../assets/css/components.css" rel="stylesheet"/>

		<!--Horizontal Menu-->
		<link href="../../assets/plugins/horizontal/horizontal-menu/horizontal.css" rel="stylesheet" />

		<!--Select2 Plugin -->
		<link href="../../assets/plugins/select2/select2.min.css" rel="stylesheet" />

		<!-- Cookie css -->
		<link href="../../assets/plugins/cookie/cookie.css" rel="stylesheet">

		<!-- Auto Complete css -->
		<link href="../../assets/plugins/autocomplete/jquery.autocomplete.css" rel="stylesheet">

		<!-- Custom scroll bar css-->
		<link href="../../assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet" />

		<!-- Color-Skins -->
		<link id="theme" href="../../assets/color-skins/color1.css"  rel="stylesheet"/>
<link href="../../assets/css/espravki-pricing-refresh.css" rel="stylesheet" />

</head>
<body class="pricing-refresh">
<div class="horizontalMenucontainer">
<?php
include '../modules/header.php';
?>
<section>
	<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/banner2.jpg">
		<div class="header-text mb-0">
			<div class="container">
				<div class="text-center text-white">
					<h1 class=""><?php echo $lang['meta_news_add']['title'];?></h1>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="bg-white border-bottom">
<div class="container">
<div class="page-header">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="../"><?php echo $lang['home'];?></a></li>
<?php

echo '
<li class="breadcrumb-item">'.$lang['sel_plan'].'</li>';
?>				
</ol>
</div>
</div>
</div>

<section class="sptb bg-white">
			<div class="container">
			<div class="center-block text-center">
			<h2 class="text-success"><?php echo $lang['shb'];?></h2><br/>
			<hr/>
			<h2><?php echo $lang['txtst0'];?></h2>
					<div class="section-title text-left"><?php echo $lang['txtst'];?></div>

<video width="700" height="400" class="mt-4 mb-5" poster="../images/files/video_screen.png" id="video" controls autoplay>
  <source src="../images/files/espravki.com-ready.mp4" type="video/mp4">
Your browser does not support the video tag.
</video>	
	<!--
	<iframe width="560" height="500" src="https://www.youtube-nocookie.com/embed/JcQca326C-Q&autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	-->
				</div>
				
				
				
				<section id="select_plan" class="pricingtables combination-pricing sptb">
			<div class="container">
				<div class="section-title center-block text-center">
					<h2><?php echo $lang['sel_plan'];?></h2>
					<p><?php echo $lang['plan_h1'];?></p>
				</div>
				<div class="row no-gutters">
					<div class="col-md-8 col-lg-3 margin-top-30 left-price sitelock">
						<div class="panel panel-info">
							<div class="panel-heading"></div>
							<div class="panel-body text-center"></div>
							<ul class="text-left">
								<li><?php echo $lang['dura'];?></li>
								<li><?php echo $lang['company_logo2'];?></li>
								<li><?php echo $lang['company_contacts'];?></li>
								<li><?php echo $lang['website'];?></li>
								<li><?php echo $lang['business_description'];?></li>
								<li><?php echo $lang['user_may_contact'];?></li>
								<li><?php echo $lang['profile_visits_statistics'];?></li>
								<li><?php echo $lang['site_visits_statistics'];?></li>
								<li><?php echo $lang['google_map'];?></li>
								<li><?php echo $lang['before_free'];?></li>
								<li><?php echo $lang['website_link'];?></li>
								<li><?php echo $lang['company_images'];?></li>
								<li><?php echo $lang['1video'];?></li>
								<li><?php echo $lang['u_docs2'];?></li>
								<li class="hasinfo"><?php echo $lang['cats_nums'];?> <i class="fa fa-info-circle" aria-hidden="true"></i> <span class="info"><?php echo $lang['cats_nums_txt'];?></span></li>
								<li><?php echo $lang['business_can_publish'];?></li>
								<li><?php echo $lang['social_link'];?></li>
								<li class="hasinfo"><?php echo $lang['pr_article'];?> <i class="fa fa-info-circle" aria-hidden="true"></i> <span class="info"><?php echo $lang['pr_article_info'];?></span></li>
								<li><?php echo $lang['sell_business_listing'];?></li>
								<li><?php echo $lang['al_reviews'];?></li>
								<li style="white-space: normal;height: 95px;"><?php echo $lang['new1'];?></li>
							</ul>
							<div class="panel-footer"></div>
						</div>
					</div>
					
					<?php
					/*
					<div class="col-md-4 col-lg margin-top-30 most-popular">
						<div class="panel panel-info">
							<div class="panel-heading text-center py-4">
								<h3 class="mb-0"><?php echo $lang['plans_array'][1];?></h3>
							</div>
							<div class="px-4 py-4 mt-6 text-center bg-success text-white">
								<div class="pricer-value">
									<h4 class="mb-0 text-white"><span><?php echo $lang['plans_prices'][1],' ',current_currency;?></span> / <?php echo $lang['year'];?></h4>
								</div>
							</div>
							<ul class="text-center">
							
								<li><?php echo $lang['year'];?> <div class="d-block d-md-none"> <?php echo $lang['dura'];?></div></li>
								<li><i class="fe fe-check text-success"></i> <div class="d-block d-md-none"> <?php echo $lang['company_logo2'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['company_contacts'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['website'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['business_description'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['user_may_contact'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['profile_visits_statistics'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['site_visits_statistics'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['google_map'];?></div></li>
								<li><?php echo $lang['af1'];?><div class="d-block d-md-none"> <?php echo $lang['before_free'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['website_link'];?></div></li>
								<li><?php echo $lang['images_num'][1];?><div class="d-block d-md-none"> <?php echo $lang['company_images'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['1video'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['u_docs2'];?></div></li>
								<li class="hasinfo">1 <?php echo $lang['n'];?><div class="d-block d-md-none"> <?php echo $lang['cats_nums'];?><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="info"><?php echo $lang['cats_nums_txt'];?></span></div></li>
								<li><?php echo $lang['offers_num'][1],' ',$lang['n'];?><div class="d-block d-md-none"> <?php echo $lang['business_can_publish'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['social_link'];?></div></li>
								<li class="hasinfo"><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['pr_article'];?> <i class="fa fa-info-circle" aria-hidden="true"></i> <span class="info"><?php echo $lang['pr_article_info'];?></span></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['sell_business_listing'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['al_reviews'];?></div></li>
							</ul>
							<div class="p-4 text-center border-top">
								<a class="btn btn-pill btn-success" href="<?php echo WebSite;?>/redirector.php?selected_plan=1"><?php echo $lang['select_plan'];?></a>
							</div>
						</div>
					</div>
					<?php
					*/
					?>
					<div class="col-md-4 col-lg margin-top-30 most-popular">
						<div class="panel panel-info">
							<div class="panel-heading text-center py-4">
								<h3 class="mb-0"><?php echo $lang['plans_array'][2];?></h3>
							</div>
							<div class="px-4 py-6 text-center bg-secondary text-white">
								<div class="pricer-value">
									<h4 class="mb-0 text-white"><span>€ <?php echo $lang['plans_prices'][2];?></span><br/><?php echo $lang['1time'];?></h4>
								</div>
							</div>
							<ul class="text-center">
								<li><?php echo $lang['forever'];?> <div class="d-block d-md-none"> <?php echo $lang['dura'];?></div></li>
								<li><i class="fe fe-check text-success"></i> <div class="d-block d-md-none"> <?php echo $lang['company_logo2'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['company_contacts'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['website'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['business_description'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['user_may_contact'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['profile_visits_statistics'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['site_visits_statistics'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['google_map'];?></div></li>
								<li><?php echo $lang['bef1'];?><div class="d-block d-md-none"> <?php echo $lang['before_free'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['website_link'];?></div></li>
								<li><?php echo $lang['images_num'][2];?><div class="d-block d-md-none"> <?php echo $lang['company_images'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['1video'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['u_docs2'];?></div></li>
								<li class="hasinfo">2 <?php echo $lang['n'];?><div class="d-block d-md-none"> <?php echo $lang['cats_nums'];?><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="info"><?php echo $lang['cats_nums_txt'];?></span></div></li>
								<li><?php echo $lang['offers_num'][2],' ',$lang['n'];?><div class="d-block d-md-none"> <?php echo $lang['business_can_publish'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['social_link'];?></div></li>
								<li class="hasinfo"><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['pr_article'];?><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="info"><?php echo $lang['pr_article_info'];?></span></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['sell_business_listing'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['al_reviews'];?></div></li>
								<li style="height: 100px;"><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['new1'];?></div></li>
							</ul>
							<div class="p-4 text-center border-top">
								<a class="btn btn-pill btn-success" href="<?php echo WebSite;?>/redirector.php?selected_plan=2"><?php echo $lang['select_plan'];?></a>
							</div>
						</div>
					</div>
					<?php
					?>
					<div class="col-md-4 col-lg margin-top-30 most-popular border-left">
						<div class="panel panel-info">
							<div class="panel-heading text-center py-4">
								<h3 class="mb-0"><?php echo $lang['plans_array'][3];?></h3>
							</div>
							<div class="px-4 py-4 mt-6 text-center bg-secondary text-white">
								<div class="pricer-value">
									<h4 class="mb-0 text-white"><span>€ <?php echo $lang['plans_prices'][3];?></span><br/><?php echo $lang['1time'];?></h4>
								</div>
							</div>
							<ul class="text-center">
								<li>1 <?php echo $lang['year'];?> <div class="d-block d-md-none"><?php echo $lang['dura'];?></div></li>
								<li><i class="fe fe-check text-success"></i> <div class="d-block d-md-none"> <?php echo $lang['company_logo2'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['company_contacts'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['website'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['business_description'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['user_may_contact'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['profile_visits_statistics'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['site_visits_statistics'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['google_map'];?></div></li>
								<li><?php echo $lang['af1'];?><div class="d-block d-md-none"> <?php echo $lang['before_free'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['website_link'];?></div></li>
								<li><?php echo $lang['images_num'][3];?><div class="d-block d-md-none"> <?php echo $lang['company_images'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['1video'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['u_docs2'];?></div></li>
								<li class="hasinfo">1 <?php echo $lang['n'];?><div class="d-block d-md-none"> <?php echo $lang['cats_nums'];?><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="info"><?php echo $lang['cats_nums_txt'];?></span></div></li>
								<li><?php echo $lang['offers_num'][3],' ',$lang['n'];?><div class="d-block d-md-none"> <?php echo $lang['business_can_publish'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['social_link'];?></div></li>
								<li class="hasinfo"><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['pr_article'];?> <i class="fa fa-info-circle" aria-hidden="true"></i> <span class="info"><?php echo $lang['pr_article_info'];?></div></li>
								<li><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['sell_business_listing'];?></div></li>
								<li><i class="fe fe-x text-danger"></i><div class="d-block d-md-none"> <?php echo $lang['al_reviews'];?></div></li>
								<li style="height: 100px;"><i class="fe fe-check text-success"></i><div class="d-block d-md-none"> <?php echo $lang['new1'];?></div></li>
							</ul>
							<div class="p-4 text-center border-top">
								<a class="btn btn-pill btn-secondary" href="<?php echo WebSite;?>/redirector.php?selected_plan=3"><?php echo $lang['select_plan'];?></a>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</section>
				
				
				
				<div class="row mt-3 mb-5">
				<div class="col-xl-4 col-lg-4 col-md-12 mt-4">
				<a target="_blank" href="../news/evtina-reklama"><h3><?php echo $lang['er'];?></h3>
				<img src="../images/articles/bezplatna-reklama.jpg" alt=""/></a>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-12 mt-4">
				<a target="_blank" href="../news/lesna-otkrivaemost"><h3><?php echo $lang['lo'];?></h3>
				<img src="../images/articles/lesna-otkrivaemost.jpg" alt="Лесна откриваемост"/></a>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-12 mt-4">
				<a target="_blank" href="../news/poveche-prodazhbi"><h3><?php echo $lang['ms'];?></h3>
				<img src="../images/articles/poveche-prodazhbi.jpg" alt="Повече продажби"/></a>
				</div>
				</div>
				
				<div class="section-title center-block text-center pt-5">
				<h2><?php echo $lang['wmu'];?></h2>
				<hr/>
				<div class="section-title text-left">
				<?php echo $lang['lte'];?>
				</div>
				</div>
				
				<div class="section-title center-block text-center pt-5">
					<h2><?php echo $lang['how'];?></h2>
					<p><?php echo $lang['how1'];?></p>
				</div>
                <div class="card overflow-hidden">
					<div class="row no-gutters row-deck">
						<div class="col-md-4">
							<div class="bg-light p-0 box-shadow2">
								<div class="card-body text-center">
									<div class="bg-white icon-bg  icon-service text-purple mb-4 service-card-svg">
										<!--User Icon Svg --->
										<svg height="618pt" viewBox="-28 -19 618 618.66574" width="618pt" xmlns="http://www.w3.org/2000/svg"><path d="m211.265625 264.132812c72.9375 0 132.066406-59.125 132.066406-132.066406 0-72.9375-59.128906-132.066406-132.066406-132.066406s-132.066406 59.128906-132.066406 132.066406c.070312 72.914063 59.15625 131.996094 132.066406 132.066406zm0-239.144531c59.140625 0 107.078125 47.945313 107.078125 107.078125 0 59.140625-47.9375 107.078125-107.078125 107.078125-59.136719 0-107.074219-47.9375-107.074219-107.078125.066406-59.109375 47.964844-107.007812 107.074219-107.078125zm0 0"/><path d="m554.492188 337.976562-107.703126-57.347656c-3.710937-2.023437-8.203124-1.976562-11.871093.121094l-98.582031 53.976562c-36.109376-26.863281-79.34375-40.855468-125.316407-40.855468-57.226562.125-110.328125 22.367187-149.6875 62.847656-39.859375 40.855469-61.726562 96.707031-61.597656 157.304688.019531 6.894531 5.601563 12.476562 12.492187 12.496093l347.597657-.625c16.339843 20.289063 37.347656 36.320313 61.222656 46.730469l14.496094 6.371094c1.574219.703125 3.28125 1.042968 5 1 1.710937-.03125 3.40625-.367188 4.996093-1l16.367188-7.121094c60.136719-25.230469 99.25-84.09375 99.207031-149.3125v-73.46875c.003907-4.640625-2.539062-8.910156-6.621093-11.117188zm-529.394532 163.429688c2.621094-49.105469 21.738282-93.957031 54.226563-127.320312 34.605469-35.609376 81.460937-55.226563 131.816406-55.226563h.375c41.359375 0 80.09375 12.996094 112.203125 37.609375v67.59375c.019531 26.835938 6.714844 53.242188 19.488281 76.839844zm511.027344-78.84375c.023438 55.21875-33.140625 105.039062-84.089844 126.324219h-.125l-11.367187 4.871093-9.5-4.125c-49.96875-21.832031-82.28125-71.160156-82.335938-125.691406v-67.597656l92.332031-50.601562 95.085938 50.726562zm0 0"/><path d="m406.804688 415.941406c-4.484376-5.242187-12.371094-5.855468-17.617188-1.371094-5.242188 4.484376-5.855469 12.371094-1.371094 17.613282l28.359375 33.109375c2.382813 2.769531 5.847657 4.363281 9.496094 4.375 2.863281.023437 5.644531-.945313 7.871094-2.75l65.972656-53.597657c5.347656-4.382812 6.128906-12.269531 1.746094-17.621093-4.378907-5.347657-12.265625-6.128907-17.613281-1.746094l-56.476563 45.980469zm0 0"/></svg>
										<!-- User Icon Svg --->
									</div>
									<h4 class="mb-4 fs-20"><?php echo $lang['how2'];?></h4>
									<p class="mh90"><?php echo $lang['how3'];?></p>
									<a href="#select_plan" class="btn btn-info text-white px-6"><?php echo $lang['how4'];?></a>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="bg-white p-0 mt-5 mt-md-0 box-shadow2">
								<div class="card-body text-center">
									<div class="bg-light icon-bg  icon-service text-purple mb-4 service-card-svg">
										<!--User Icon Svg --->
										<svg height="482pt" viewBox="-15 0 482 482.60407" width="482pt" xmlns="http://www.w3.org/2000/svg"><path d="m268.941406 173.949219h25.609375c3.3125 0 6-2.6875 6-6 0-3.316407-2.6875-6-6-6h-25.609375c-3.316406 0-6 2.683593-6 6 0 3.3125 2.683594 6 6 6zm0 0"/><path d="m156.160156 173.949219h89.820313c3.316406 0 6-2.6875 6-6 0-3.316407-2.683594-6-6-6h-89.820313c-3.3125 0-6 2.683593-6 6 0 3.3125 2.6875 6 6 6zm0 0"/><path d="m94.542969 173.949219h38.199219c3.3125 0 6-2.6875 6-6 0-3.316407-2.6875-6-6-6h-38.199219c-3.316407 0-6 2.683593-6 6 0 3.3125 2.683593 6 6 6zm0 0"/><path d="m231.722656 214.8125h-106.386718c-3.316407 0-6 2.6875-6 6s2.683593 6 6 6h106.386718c3.3125 0 6-2.6875 6-6s-2.6875-6-6-6zm0 0"/><path d="m94.542969 226.8125h11.136719c3.3125 0 6-2.6875 6-6s-2.6875-6-6-6h-11.136719c-3.316407 0-6 2.6875-6 6s2.683593 6 6 6zm0 0"/><path d="m94.542969 282.972656h71.257812c3.316407 0 6-2.6875 6-6s-2.683593-6-6-6h-71.257812c-3.316407 0-6 2.6875-6 6s2.683593 6 6 6zm0 0"/><path d="m123.347656 322.875h-28.804687c-3.316407 0-6 2.6875-6 6 0 3.316406 2.683593 6 6 6h28.804687c3.3125 0 6-2.683594 6-6 0-3.3125-2.6875-6-6-6zm0 0"/><path d="m442.261719 132.910156c-7.777344-11.804687-23.644531-15.089844-35.464844-7.339844l-6.515625 4.28125h-.015625l-11.476563 7.550782v-88.261719c-.015624-14.136719-11.472656-25.59375-25.609374-25.609375h-131.363282c-6.847656-14.375-21.347656-23.53125-37.269531-23.53125-15.925781 0-30.425781 9.15625-37.273437 23.53125h-131.363282c-14.136718.015625-25.59375 11.472656-25.609375 25.609375v407.863281c.023438 14.132813 11.476563 25.582032 25.609375 25.601563h337.269532c14.132812-.015625 25.585937-11.46875 25.609374-25.601563v-245.601562l51.964844-34.144532c11.804688-7.777343 15.089844-23.640624 7.339844-35.464843zm-222.734375 175.34375-7.09375-10.800781 203.097656-133.449219 7.097656 10.800782zm111.785156 52.011719h-31.566406c-14.136719.015625-25.59375 11.46875-25.609375 25.605469v30.734375h-212.554688c-2.097656-.003907-3.796875-1.707031-3.800781-3.804688v-319.460937c.003906-2.101563 1.703125-3.800782 3.800781-3.808594h26.960938v16.472656c0 3.3125 2.683593 6 6 6h200.007812c3.3125 0 6-2.6875 6-6v-16.472656h26.957031c2.097657.003906 3.800782 1.707031 3.804688 3.808594v81.828125l-144.167969 94.726562c-.09375.0625-.179687.128907-.269531.199219-.054688.039062-.109375.078125-.164062.121094-.183594.144531-.359376.300781-.527344.464844l-.007813.007812c-.160156.164062-.3125.339844-.453125.523438-.035156.042968-.066406.085937-.101562.128906-.113282.152344-.21875.3125-.316406.476562-.019532.027344-.039063.050782-.058594.082032l-27.257813 46.972656-8.101562 5.320312c-2.769531 1.820313-3.539063 5.539063-1.71875 8.308594 1.820312 2.769531 5.539062 3.539062 8.308593 1.71875l8.101563-5.320312 53.929687-6.375c.03125 0 .0625-.011719.09375-.015626.097657-.015624.191407-.035156.289063-.054687.175781-.03125.347656-.066406.515625-.113281.101562-.027344.203125-.0625.300781-.101563.164063-.054687.324219-.113281.480469-.179687.097656-.042969.199219-.089844.292969-.136719.152343-.078125.300781-.160156.445312-.25.058594-.035156.117188-.058594.175781-.101563l110.210938-72.40625zm-8.242188 12-36.933593 36.933594v-23.328125c.007812-7.511719 6.097656-13.597656 13.609375-13.605469zm-115.609374-60.523437-33.777344 3.996093 17.074218-29.421875 8.351563 12.714844zm-1.617188-24.316407-7.09375-10.800781 187.386719-123.121094.011719-.007812 15.699218-10.316406 7.097656 10.800781zm157.335938-251.894531c7.511718.007812 13.597656 6.09375 13.609374 13.609375v96.144531l-33.476562 22v-73.945312c-.011719-8.726563-7.082031-15.796875-15.804688-15.808594h-26.957031v-18.585938c0-3.316406-2.6875-6-6-6h-58.710937v-11.648437c-.003906-1.929687-.140625-3.855469-.410156-5.765625zm-168.632813-23.527344c16.171875.015625 29.28125 13.125 29.296875 29.300782v17.640624c0 3.3125 2.6875 6 6 6h58.710938v35.058594h-188.011719v-35.058594h58.707031c3.3125 0 6-2.6875 6-6v-17.648437c.019531-16.171875 13.125-29.273437 29.296875-29.292969zm182.242187 445c-.015624 7.507813-6.101562 13.59375-13.609374 13.601563h-337.269532c-7.507812-.011719-13.59375-6.09375-13.609375-13.601563v-407.863281c.007813-7.511719 6.097657-13.601563 13.609375-13.609375h127.75c-.269531 1.910156-.40625 3.835938-.410156 5.765625v11.648437h-58.707031c-3.316407 0-6 2.683594-6 6v18.585938h-26.960938c-8.722656.015625-15.789062 7.082031-15.800781 15.808594v319.464844c.011719 8.71875 7.078125 15.789062 15.800781 15.800781h214.757813c.796875-.007813 1.589844-.066407 2.375-.175781.464844.113281.941406.171874 1.421875.175781 1.734375 0 3.382812-.753907 4.515625-2.058594 1.566406-.832031 2.996094-1.890625 4.25-3.144531l49.207031-49.199219c3.339844-3.324219 5.210937-7.847656 5.199219-12.5625v-120.363281l33.476562-22zm57.375-289.773437-1.507812.988281-20.777344-31.625 1.507813-.988281c6.28125-4.128907 14.71875-2.382813 18.84375 3.898437l5.832031 8.878906c4.117188 6.28125 2.371094 14.710938-3.898438 18.84375zm0 0"/></svg>
										<!--User Icon Svg --->
									</div>
									<h4 class="mb-4 fs-20"><?php echo $lang['how5'];?></h4>
									<p class="mh90"><?php echo $lang['how6'];?></p>
									<a href="#select_plan" class="btn btn-secondary text-white px-6"><?php echo $lang['selPl'];?></a>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="bg-white p-0 mt-5 mt-md-0 box-shadow2">
								<div class="card-body text-center">
									<div class="bg-light icon-bg  icon-service text-purple mb-4 service-card-svg">
										<span><img src="../../assets/images/svgs/business/sale-tag-hand-drawn-symbol.svg" class="location-gps" alt="<?php echo $lang['how7'];?>"></span>
									</div>
									<h4 class="mb-4 fs-20"><?php echo $lang['how7'];?></h4>
									<p class="mh90"><?php echo $lang['how8'];?></p>
									<a href="#select_plan" class="btn btn-success text-white px-6"><?php echo $lang['add_offer'];?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
<?php
include '../modules/footer.php';
?>	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<script src="../../assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="../../assets/js/fullfunctions.js"></script>
<script src="../../assets/js/contacts.js"></script>
</div>
	</body>
</html>