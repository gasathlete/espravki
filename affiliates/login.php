<?php
session_start();
require '../config.php';
require '../project_functions.php';
require '../translation/'.$_SESSION['lang'].'_lang.php';

//var_dump($_SESSION);
if(!isset($_SESSION['customer']) && !isset($_SESSION['affiliate'])){

function echo_meta(){
	global $lang;
	echo '<title>',official_site_name,' Login</title>';
	echo '<meta name="description" content="',official_site_name,' Login" />';
	echo '<meta name="keywords" content="',official_site_name,' Login" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',official_site_name,' Login" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'/userlogins/index.php" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',official_site_name,'" />';
	echo '<meta property="og:description" content="',official_site_name,' Login" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';	
}


//var_dump($_POST);
$msg = '';
if(isset($_POST['afflogin'])){
if(empty($_POST['mail']) || !email_valid($_POST['mail'])) $msg = $lang['subscribe_plchldr'];
if(empty($_POST['password'])) $msg = $lang['wrong_login'];

if(empty($msg)){


$query='select id, firstname, lastname, commission from affiliates where email = "'.mysql_real_escape_string($_POST['mail']).'" and aff_pass = "'.md5($_POST['password']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_row($result);
$_SESSION['affiliate'] = $row[0];
$_SESSION['affiliate_fname'] = $row[1];
$_SESSION['affiliate_lname'] = $row[2];
$_SESSION['commission'] = $row[3];

header("location: ./index.php");
exit(0);
		}
	}
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
<link href="../assets/css/buttons.css" rel="stylesheet" />

</head>
<body>
<?php include '../modules/header.php';
?>
<section>
			<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/banner2.jpg">
				<div class="header-text mb-0">
					<div class="container">
						<div class="text-center text-white">
							<h1 class=""><?php echo $lang['login_s'];?></h1>
							<ol class="breadcrumb text-center">
								<li class="breadcrumb-item"><a href="../"><?php echo $lang['home'];?></a></li>
								<li class="breadcrumb-item active text-white" aria-current="page"><?php echo $lang['login'];?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!--Login-Section-->
		<section class="sptb">
			<div class="container customerpage">
				<div class="row">
					<div class="col-lg-5 col-xl-4 col-md-6 d-block mx-auto">
						<div class="row">
							<div class="col-xl-12 col-md-12 col-md-12 register-right">
								<ul class="nav nav-tabs nav-justified p-1 border" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active m-1" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $lang['login'];?></a>
									</li>
								</ul>
								<div class="tab-content" id="myTabContent">
								<div class="msg tac"><?php echo $msg;?></div>
									<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
										<div class="single-page  w-100  p-0">
											<div class="wrapper wrapper2">
												<div class="card-body">
													<h3 class="scroller"><?php echo $lang['login'];?></h3>
													<div class="text-center">
														<div class="btn-list d-flex">
															<a href="#" class="btn btn-google btn-block"><i class="fa fa-google fa-1x mr-2"></i> <?php echo $lang['log_goo'];?></a>
															<a href="#" class="btn btn-twitter"><i class="fa fa-twitter fa-1x"></i></a>
															<a href="#" class="btn btn-facebook"><i class="fa fa-facebook fa-1x"></i></a>
														</div>
													</div>
												</div>
												<hr class="divider">
												<form id="login" name="loginform" method="post" action="" class="card-body" tabindex="500">
													<div class="mail">
														<input id="login_mail" value="" type="email" name="mail"/>
														<label><?php echo $lang['email'];?></label>
													</div>
													<div class="passwd pr">
														<input id="login_password" value="" type="password" name="password"/>
														<i class="fa fa-eye" data-id="login_password" aria-hidden="true"></i>
														<label><?php echo $lang['password'];?></label>
													</div>
													<div class="submit">
														<button type="submit" id="afflogin" name="afflogin" value="login" class="btn btn-secondary btn-block"><?php echo $lang['login'];?></button>
													</div>
													<p class="mb-2"><a class="text-secondary" href="../contacts/"><?php echo $lang['p_f'];?></a></p>
												</form>
											</div>
										</div>
									</div>
									
									
									<div class="tab-pane fade show" id="pass_forg" role="tabpanel" aria-labelledby="pass_forgotten">
										<div class="single-page w-100  p-0">
											<div class="wrapper wrapper2">
												<div class="card-body">
													<h3><?php echo $lang['p_f'];?></h3>
													
												</div>
												<hr class="divider">
												<form id="pas_for" class="card-body" tabindex="500">
													
													<div class="mail">
														<input id="forg_mail" type="email" value="" name="mail"/>
														<label><?php echo $lang['email'];?></label>
													</div>
													
													<div class="submit">
														<a id="n_p" class="btn btn-secondary btn-block" href="#"><?php echo $lang['n_p'];?></a>
													</div>
													<p class="text-dark mb-0"><?php echo $lang['h_a'];?><a id="llink" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false" class="text-secondary ml-1"><?php echo $lang['login'];?></a></p>
												</form>
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--/Login-Section-->

<?php include '../modules/footer.php';?>	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<script src="../../assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="../../assets/js/fullfunctions.js"></script>
<script src="../../assets/js/contacts.js"></script>
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="validationdiv" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
						<h5 class="modal-title ib w100"><?php echo $lang['pl_con'];?></h5><hr/>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
						<span class="ib w100 tac formsg" ><?php echo $lang['ent_code'];?></span>
					</div>
					<div class="modal-body">
						<div class="form-group">
						<label><?php echo $lang['code'];?></label>
							<input id="code" type="text" class="form-control" name="code" placeholder="<?php echo $lang['code'];?>" />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $lang['canc'];?></button>
						<button type="button" id="confirmcode" class="btn btn-success"><?php echo $lang['confirm'];?></button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
}else{
header("location: ../index.php");
exit(0);
}
?>