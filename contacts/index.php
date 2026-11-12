<?php
session_start();
require '../config.php';
require '../project_functions.php';

require '../translation/'.$_SESSION['lang'].'_lang.php';

/*
$inipath = php_ini_loaded_file();
if ($inipath) {
    echo 'Loaded php.ini: ' . $inipath;
} else {
    echo 'A php.ini file is not loaded';
}*/

//phpinfo();
function echo_meta(){
	global $lang;
	echo '<title>',$lang['meta_contact']['title'],'</title>';
	echo '<meta name="description" content="',$lang['meta_contact']['description'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_contact']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['meta_contact']['title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['meta_contact']['description'],'" />';

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

<link href="../assets/css/style.css" rel="stylesheet" />

</head>
<body>
<?php include '../modules/header.php';
?>
<section>
			<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/banner2.jpg">
				<div class="header-text mb-0">
					<div class="container">
						<div class="text-center text-white ">
							<h1 class=""><?php echo $lang['contact'],' ',official_mail_sender_name ;?></h1>
							<ol class="breadcrumb text-center">
								<li class="breadcrumb-item"><a href="../"><?php echo $lang['home'];?></a></li>
								<li class="breadcrumb-item"><?php echo $lang['contact_form'];?></li>
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
													<img src="../images/logo_1.png" alt="<?php echo official_mail_sender_name;?>"/>
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
										<li class=""><a href="#tab-5" class="active" data-toggle="tab"><?php echo $lang['contact_details'];?></a></li>
										<li><a href="#tab-6" data-toggle="tab" class=""><?php echo $lang['contact_form'];?></a></li>
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
												<div class="media-heading">
													<h3 class="card-title mb-3 font-weight-semibold"><?php echo $lang['contact_details'];?></h3>
												</div>
		<ul class="usertab-list mb-0">
			<li><a href="#" class="text-dark"><span class="font-weight-semibold"><i class="fa fa-address-book-o"></i> <?php echo $lang['title'];?>: </span> <?php echo $lang['c_n'];?></a></li>
			<li><a href="#" class="text-dark"><span class="font-weight-semibold"><i class="fa fa-map-marker"></i> <?php echo $lang['address'];?>: </span> <?php echo $lang['c_a'];?></a></li>
			<li><a href="#" class="text-dark"><span class="font-weight-semibold"><i class="fa fa-address-card-o"></i> <?php echo $lang['website'];?>: </span><?php echo official_site_name;?></a></li>
			<li><a href="#" class="text-dark"><span class="font-weight-semibold"><i class="fa fa-envelope"></i> <?php echo $lang['email'];?>: </span> <?php echo official_mail_sender;?></a></li>
			<li><a href="#" class="text-dark"><span class="font-weight-semibold"><i class="fa fa-phone-square"></i> <?php echo $lang['phone'];?>: </span> <?php echo site_phone;?> </a></li>
			<li><a href="#" class="text-dark ib tac"><span class="font-weight-semibold"><i class="fa fa-clock-o"></i> <?php echo $lang['worktime'];?>: </span> <?php echo $lang['worktime1'];?> </a></li>
		<li class="shm"><a href="#tab-6" data-toggle="tab" class="btn btn-secondary ad-post"><?php echo $lang['contact_form'];?></a></li>
		</ul>
												<div class="mt-5 profie-img">
													<div class="">
														<div class="media-heading">
															<h3 class="card-title mb-3 font-weight-semibold"><?php echo $lang['alw'];?></h3>
														</div>
														<p><?php echo $lang['alw2'];?></p>
														<p class="mb-0"><?php echo $lang['alw3'];?></p>
														<p class="mt20"><?php echo $lang['alw4'];?></p>
														<p class="mt20"><?php echo $lang['thank_msg'],' ',official_mail_sender_name;?></p>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab-6">
											<form disabled name="aform">
											<div id="cfholder" class="row">
											<?php
											//var_dump($_SESSION);
											$first_name = '';
											$second_name = '';
											$customer_email = '';
											$customer_phone = '';
											if(isset($_SESSION['customer_names'])){
											$names = preg_split("~\s+~",$_SESSION['customer_names']);
											$first_name = $names[0];
											$second_name = $names[1];
											}
											if(isset($_SESSION['customer_email'])) $customer_email = $_SESSION['customer_email'];
											if(isset($_SESSION['customer_phone'])) $customer_phone = $_SESSION['customer_phone'];
											//if(isset($_SESSION['customer_names'])) $customer_names = $_SESSION['customer_names'];
											?>
											<div class="msg ib w100 tac"></div>
												<div class="col-sm-6 col-md-6 mt-4">
													<div class="form-group">
														<label class="form-label"><?php echo $lang['name'];?></label>
														<input type="text" class="form-control" value="<?php echo $first_name;?>" id="name" placeholder="First Name"/>
													</div>
												</div>
												<div class="col-sm-6 col-md-6 mt-4">
													<div class="form-group">
														<label class="form-label"><?php echo $lang['fname'];?></label>
														<input type="text" class="form-control" value="<?php echo $second_name;?>" id="fname" placeholder="Last Name"/>
													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label"><?php echo $lang['email'];?></label>
														<input type="email" class="form-control" value="<?php echo $customer_email;?>" id="mail" placeholder="Email"/>
													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label"><?php echo $lang['phone'];?></label>
														<input onkeypress="return(numberFormat(event));" value="<?php echo $customer_phone;?>" id="phone" type="number" class="form-control" placeholder="Number"/>
													</div>
												</div>
												
												
										
												<div class="col-md-5">
													<div class="form-group">
														<label class="form-label"><?php echo $lang['subject'];?></label>
														<select id="subject" class="form-control select2-container--default w-100" data-placeholder="Select">
															<optgroup label="Categories">
																<option value="0"><?php echo $lang['please_select'];?></option>
																<option value="1"><?php echo $lang['mail_sbj']['1'];?></option>
																<option value="2"><?php echo $lang['mail_sbj']['2'];?></option>
																<option value="3"><?php echo $lang['mail_sbj']['3'];?></option>
																<option value="4"><?php echo $lang['mail_sbj']['4'];?></option>
																<option value="4"><?php echo $lang['mail_sbj']['5'];?></option>
															</optgroup>
														</select>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label class="form-label"><?php echo $lang['message'];?></label>
														<textarea id="message" rows="5" class="form-control" placeholder="<?php echo $lang['pls_ent'];?>"></textarea>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label class="form-label"><?php echo $lang['add_file'];?></label>
														<div class="custom-file">
															<input id="up_doc" accept="" type="file" class="custom-file-input" name="file"/>
															<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<a id="contactusbtn" class="btn btn-secondary"><?php echo $lang['send'];?></a>
												</div>
												
												
											</div>
											</form>
										</div>
									
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		

<?php include '../modules/footer.php';?>	

		<!-- Message Modal -->
		<div class="modal fade" id="contact" tabindex="-1" role="dialog"  aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Send Message</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" class="form-control" id="name1" placeholder="Your Name">
						</div>
						<div class="form-group">
							<input type="email" class="form-control" id="email" placeholder="Email Address">
						</div>
						<div class="form-group mb-0">
							<textarea class="form-control" name="example-textarea-input" rows="6" placeholder="Message"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-success">Send</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Back to top -->
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<script src="../../assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="../../assets/js/fullfunctions.js"></script>
<script src="../../assets/js/contacts.js"></script>
	</body>
</html>