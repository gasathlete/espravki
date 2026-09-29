<!--Loader-->
		<!-- <div id="global-loader">
			<img src="../../assets/images/products/products/loader.png" class="loader-img floating" alt="Зареждам страницата" />
			<span><?php echo $lang['load'];?></span>
		</div> -->


		<!--Topbar-->
		<div class="header-main">
			<div class="top-bar p-3">
				<div class="container">
					<div class="row">
						<div class="col-xl-6 col-lg-6 col-sm-4 col-6">
							<div class="top-bar-left d-flex">
								<div class="clearfix">
									<ul class="socials d-lg-none">
										<li>
											<a class="social-icon text-dark" target="_blank" href="<?php echo facebook_page;?>"><i class="fa fa-facebook"></i></a>
										</li>
										<li>
											<a class="social-icon text-dark" href="<?php echo twitter_page;?>"><i class="fa fa-twitter"></i></a>
										</li>
										<li>
											<a class="social-icon text-dark" href="#"><i class="fa fa-linkedin"></i></a>
										</li>
									</ul>
									<div class="header-search-logo d-none d-lg-block logodiv">
										<a href="<?php echo WebSite;?>" class="d-flex logo-height logo-svg">
						<img id="logo" src="<?php echo WebSite;?>/images/logo_1.png" alt="<?php echo official_mail_sender_name;?>" />
						</a>
									</div>
								</div>
								
								
								<div class="clearfix">
									<ul class="contact border-left">
										<li class="mr-5 d-lg-none">
											<a href="#" class="callnumber text-dark"><span><i class="fa fa-phone mr-1"></i>: 0899 904 850</span></a>
										</li>
										
									</ul>
								</div>
							</div>
						</div>
	<div class="col-xl-6 col-lg-6 col-sm-8 col-6">
		<div class="top-bar-right">
			<ul class="custom mt-4">
<?php
if(isset($_SESSION['customer'])){
$fname = explode(' ',$_SESSION['customer_names']);
echo '<li class="hsm">
	<span>',$lang['hello'],' ',$fname[0],'</span>
</li>';
}

if(isset($_SESSION['affiliate'])){
echo '<li class="hsm">
	<span>',$lang['hello'],' ',$_SESSION['affiliate_fname'],'</span>
</li>';
}
?>	
		<li class="dropdown">
				<a href="#" class="text-dark" data-toggle="dropdown"><i class="fa fa-globe" aria-hidden="true"></i><span> <?php echo $lang['lang'];?> </span> </a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
<?php
foreach($lang['lang_array'] as $key=>$langs){
if($key != $_SESSION['lang']) echo '<a class="dropdown-item" href="',WebSite,'/lang/index.php?lang=',$key,'">',$langs,'</a>';
 }
?>
				</div>
			</li>
<?php
//var_dump($_SESSION);
if(!isset($_SESSION['customer']) && !isset($_SESSION['affiliate'])){
?>			
<li>
	<a href="<?php echo WebSite;?>/userlogins/index.php" class="text-dark"><i class="fa fa-user mr-1"></i> <span><?php echo $lang['login'];?></span></a>
</li>
<?php
}


if(!empty($_SESSION['customer'])){
$messages = check_new_msgs();
$newmsg = '';
if($messages > 0) $newmsg = '<span title="'.$lang['new_msgs'].'" class="main-badge1 badge badge-success ml-2">('.$messages.')</span>';
echo '
<li class="dropdown">
	<a href="#" class="text-dark" data-toggle="dropdown"><i class="dropdown-icon icon fa fa-user"></i><span> ',$lang['menu'],'</span></a>
	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
		<a href="',WebSite,'/mydash.php?profile=show" class="dropdown-item" >
			<i class="dropdown-icon icon fa fa-user"></i> ',$lang['my_profile'],'
		</a>
		
		<a href="',WebSite,'/mydash.php?my_listings=show" class="dropdown-item" >
			<i class="dropdown-icon fa fa-briefcase"></i> ',$lang['my_b'],'
		</a>
		
		<a href="',WebSite,'/mydash.php?my_products=show" class="dropdown-item" >
			<i class="dropdown-icon fa fa-tasks"></i> ',$lang['my_p'],'
		</a>
		
		
		<a href="',WebSite,'/mydash.php?messages=show" class="dropdown-item" >
			<i class="dropdown-icon icon fa fa fa-envelope-o"></i> ',$lang['msgs'],' ',$newmsg,'
		</a>
		<a class="dropdown-item" href="',WebSite,'/logout.php">
			<i class="dropdown-icon icon fa fa-sign-out"></i> ',$lang['logout'],'
		</a>
	</div>
</li>';

if($messages > 0) echo '
<li class="pr">
	<a href="',WebSite,'/mydash.php?messages=show" class="header-icons-link1 text-dark"><i class="icon fa fa-envelope-o mr-1"></i>
	<span title="',$lang['new_msgs'],'" id="msg_counter" class="main-badge1 badge badge-success badge-pill"> ',$messages,' </span>
	<span>',$lang['msgs'],'</span></a>
</li>
';
}
/*if(!empty($_SESSION['advert_id'])){
echo '
<li class="dropdown">
	<a href="#" class="text-dark" data-toggle="dropdown"><i class="fa fa-home mr-1"></i><span> ',$lang['menu'],'</span></a>
	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
		<a href="',WebSite,'/addbusiness/edit/" class="dropdown-item" >
			<i class="dropdown-icon icon fa fa-user"></i> ',$lang['my_profile'],'
		</a>
		
		
		<a href="',WebSite,'/addbusiness/orders/index.php?orders=show" class="dropdown-item" >
			<i class="dropdown-icon icon icon-speech"></i> ',$lang['my_orders'],'
		</a>
		<a class="dropdown-item" href="',WebSite,'/logout.php">
			<i class="dropdown-icon icon fa fa-sign-out"></i> ',$lang['logout'],'
		</a>
	</div>
</li>';
}*/

if(!isset($_SESSION['affiliate'])){
}else{
echo '<li class="hsm">
	<a class="header-icons-link1 text-dark" href="',WebSite,'/logout.php">
		<span><i class="dropdown-icon icon fa fa-sign-out"></i> ',$lang['logout'],'</span>
		</a>
</li>';
}
?>
									
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Mobile Header -->
			<div class="sticky">
				<div class="horizontal-header clearfix ">
					<div class="container">
						<a id="horizontal-navtoggle" class="animated-arrow"><span></span></a>
						<span class="smllogo"><a href="<?php echo WebSite;?>" class="d-flex logo-height logo-svg">
						<img id="logo" src="<?php echo WebSite;?>/images/logo_1.png" alt="<?php echo official_mail_sender_name;?>" />
						</a>
						</span>
						<a href="<?php echo WebSite.'/select-plan/';?>" class="callusbtn"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
			<!-- /Mobile Header -->

			<div class="horizontal-main bg-dark-transparent clearfix">
				<div class=" header-style horizontal-mainwrapper container clearfix">
					<!--Nav-->
					<nav class="horizontalMenu clearfix d-md-flex">
						<ul class="horizontalMenu-list">
							<li><a href="<?php echo WebSite;?>"> <?php echo $lang['home'];?> <span class="wsarrow"></span></a></li>
							<?php
							//if(empty($aid)){
							?>
							<li><a href="<?php echo WebSite;?>/contacts/"><?php echo $lang['a_us'];?></a></li>
							<?
							//}
							?>
							<li><a href="<?php echo WebSite;?>/news/"><?php echo $lang['news'];?></a></li>
							<li><a href="<?php echo WebSite;?>/business-for-sale/"><?php echo $lang['buy_b'];?></a></li>
							<?php
							//if(empty($aid)){
							?>
							<li><a href="<?php echo WebSite;?>/products/"> <?php echo $lang['shop'];?> <span class="wsarrow"></span></a></li>
							<?
							//}
							?>
							<li class="d-lg-none mt-5 pb-5 mt-lg-0">
								<span><a class="btn btn-orange" href="<?php echo WebSite.'/select-plan/';?>"><?php echo $lang['add'];?></a></span>
							</li>
						</ul>
						<ul class="mb-0 mt-1">
							<li class="mt-2 d-none d-lg-flex mt2 w150p">
								<span><a class="btn btn-secondary new-firm" href="<?php echo WebSite.'/select-plan/';?>"><i class="fa fa-plus text-white"></i> <?php echo $lang['add'];?></a></span>
							</li>
						</ul>
					</nav>
					<!--Nav-->
				</div>
				<div class="body-progress-container">
					<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="myBar"></div>
				</div>
			</div>
		</div>