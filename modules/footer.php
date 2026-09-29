<!--Footer Section-->
		<section>
			<footer class="bg-dark-purple text-white">
				<div class="footer-main border-bottom">
					<div class="container">
						<div class="row">
							<div class="col-xl-3 col-lg-6 col-md-12">
								<?php echo todays_searches();?>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-12">
								<h6><?php echo $lang['pop'];?></h6>
								<ul class="list-unstyled mb-0">
								<li><i class="fa fa-angle-double-right mr-2 text-secondary"></i><a href="<?php echo WebSite;?>/business-directory/тд-на-нап/"><?php echo $lang['6'];?></a></li>
								<li><i class="fa fa-angle-double-right mr-2 text-secondary"></i><a href="<?php echo WebSite;?>/business-directory/посолства/"><?php echo $lang['8'];?></a></li>
								<li><i class="fa fa-angle-double-right mr-2 text-secondary"></i><a href="<?php echo WebSite;?>/business-directory/счетоводни-услуги/"><?php echo $lang['5'];?></a></li>
								<li><i class="fa fa-angle-double-right mr-2 text-secondary"></i><a href="<?php echo WebSite;?>/business-directory/транспортни-услуги-логистика/"><?php echo $lang['7'];?></a></li>
								<li><i class="fa fa-angle-double-right mr-2 text-secondary"></i><a href="<?php echo WebSite;?>/business-directory/a-z/"><?php echo $lang['view_all'];?></a></li>
									
								</ul>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-12">
								<h6 class="mt-6 mt-xl-0"><?php echo $lang['contact_details'];?></h6>
								<ul class="list-unstyled mb-0">
									<li>
										<i class="fa fa-list-alt mr-3 text-secondary"></i><?php echo $lang['c_n'],', ',$lang['bulstat'];?>: 206957326
									</li>
									
									<li>
										<i class="fa fa-home mr-3 text-secondary"></i> <?php echo $lang['c_a'];?>
									</li>
									<?php
									if(empty($aid)){
									?>
									<li>
										<i class="fa fa-envelope mr-3 fs-12 text-secondary"></i> eespravki@gmail.com
									</li>
									<?php
									}
									if(site_phone) echo '
									<li>
										<a href="#"><i class="fa fa-phone mr-3 text-secondary"></i>',site_phone,'</a>
									</li>';
									?>
									
								</ul>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-12">
								<h6 class="mb-2 mt-6 mt-xl-0"><?php echo $lang['subscribe'];?></h6>
								<div class="input-group">
									<input id="email2" value="" type="text" class="form-control br-tl-3  br-bl-3" placeholder="<?php echo $lang['email'];?>"/>
									<div class="input-group-append ">
										<button  id="subscribe2" type="button" class="btn btn-secondary br-tr-3 br-br-3">
											<?php echo $lang['subscribe_btn'];?>
										</button>
									</div>
								</div>
								<h6 class="mb-2 mt-5">Payments</h6>
								<ul class="payments mb-0">
									<li>
										<a href="#" class="payments-icon"><i class="fa fa-cc-amex" aria-hidden="true"></i></a>
									</li>
									<li>
										<a href="#" class="payments-icon"><i class="fa fa-cc-visa" aria-hidden="true"></i></a>
									</li>
									<li>
										<a href="#" class="payments-icon"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></a>
									</li>
									<li>
										<a href="#" class="payments-icon"><i class="fa fa-cc-mastercard" aria-hidden="true"></i></a>
									</li>
									<li>
										<a href="#" class="payments-icon"><i class="fa fa-cc-paypal" aria-hidden="true"></i></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="bg-dark-purple text-white p-0 border-bottom">
					<div class="container">
						<div class="p-2 text-center footer-links">
						<?php
						echo '
						<a class="btn btn-link" href="'.WebSite.'/news/">',$lang['news'],'</a>
						<a class="btn btn-link" href="'.WebSite.'/products/">MarketPlace</a>
						<a class="btn btn-link" href="'.WebSite.'/select-plan/">',$lang['pss'],'</a>';
						if(empty($aid)) echo '<a class="btn btn-link" href="',WebSite,'/contacts">',$lang['contact_us'],'</a>';
						echo '<a class="btn btn-link" href="'.WebSite.'/news/terms-and-conditions">',$lang['terms_and'],'</a>
						<a class="btn btn-link" href="'.WebSite.'/news/terms-for-traders">',$lang['terms_tr'],'</a>';
						?>
							
						</div>
					</div>
				</div>
				<div class="bg-dark-purple text-white-50 p-3">
					<div class="container">
						<div class="row d-flex">
							<div class="col-lg-12 col-sm-12  mt-2 mb-2 text-center ">
							<div class="made" title="Изработка на фирмени сайтове и онлайн магазини www.Web-Site-Maker.eu">Copyright © <?php echo date('Y');?> <font color="#28C9C7">Web-</font><font color="#EC2D5F">Site-</font><font color="#E746D2">Maker</font>.eu  All rights reserved.</div>
							</div>
							<div class="col-lg-12 col-sm-12 text-center mb-2 mt-2">
								<ul class="social-icons mb-0">
									<li>
										<a class="social-icon" href="<?php echo facebook_page;?>"><i class="fa fa-facebook"></i></a>
									</li>
									<li>
										<a class="social-icon" href="<?php echo instagram;?>"><i class="fa fa-instagram"></i></a>
									</li>
									<li>
										<a class="social-icon" href="<?php echo tiktok;?>"><svg style="height: 20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M544.5 273.9C500.5 274 457.5 260.3 421.7 234.7L421.7 413.4C421.7 446.5 411.6 478.8 392.7 506C373.8 533.2 347.1 554 316.1 565.6C285.1 577.2 251.3 579.1 219.2 570.9C187.1 562.7 158.3 545 136.5 520.1C114.7 495.2 101.2 464.1 97.5 431.2C93.8 398.3 100.4 365.1 116.1 336C131.8 306.9 156.1 283.3 185.7 268.3C215.3 253.3 248.6 247.8 281.4 252.3L281.4 342.2C266.4 337.5 250.3 337.6 235.4 342.6C220.5 347.6 207.5 357.2 198.4 369.9C189.3 382.6 184.4 398 184.5 413.8C184.6 429.6 189.7 444.8 199 457.5C208.3 470.2 221.4 479.6 236.4 484.4C251.4 489.2 267.5 489.2 282.4 484.3C297.3 479.4 310.4 469.9 319.6 457.2C328.8 444.5 333.8 429.1 333.8 413.4L333.8 64L421.8 64C421.7 71.4 422.4 78.9 423.7 86.2C426.8 102.5 433.1 118.1 442.4 131.9C451.7 145.7 463.7 157.5 477.6 166.5C497.5 179.6 520.8 186.6 544.6 186.6L544.6 274z" fill="rgba(255,255,255,0.6)"/></svg></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</section>
		<!--Footer Section-->

		<!-- Back to top -->
		<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>


<?php

//var_dump($_SESSION);

if(isset($_COOKIE['partner_id'])) {
if(empty($_SESSION['affiliate_id'])) $_SESSION['affiliate_id']=$_COOKIE['partner_id'];
}

$affiliate_id = 0;
if(!empty($_SESSION['affiliate_id'])) $affiliate_id = $_SESSION['affiliate_id'];


$error='NE';
if(!empty($msg)) $error=$msg;

$ref='';
//echo $visitorcity.'aaaa';
if(empty($_POST['adverts'])){
$advert_id=0;
}else $advert_id=$_POST['adverts'];

if(isset($_SERVER['HTTP_REFERER'])) $ref=$_SERVER['HTTP_REFERER'];
elseif(isset($_ENV['HTTP_REFERER'])) $ref=$_ENV['HTTP_REFERER'];

if(!isset($_SERVER['HTTP_USER_AGENT'])) $_SERVER['HTTP_USER_AGENT'] = 'unknown HTTP_USER_AGENT';

$user_type = 'guest';
if(isset($_SESSION['advert_name'])){
$user_type = 'advert';
$advert_id = $_SESSION['advert_id'];
}


$search = '';
if(isset($_POST['search']) && !empty($_POST['search'])) $search = $_POST['search'];
if(isset($_POST['search_text']) && !empty($_POST['search_text'])) $search = $_POST['search_text'];
if(isset($_GET['search']) && !empty($_GET['search'])) $search = $_GET['search'];

//var_dump($_POST);
//$affiliate_id 
if(!_bot_detected()){
	$query='insert into statistics set user_type="'.mysql_real_escape_string($user_type).'", 
	ip="'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'",
	city="'.mysql_real_escape_string(@$_SESSION['city']).'",country="'.mysql_real_escape_string(@$_SESSION['country']).'",
	url="'.mysql_real_escape_string($_SERVER['REQUEST_URI']).'", timest=now(), sid="'.mysql_real_escape_string(session_id()).'", 
	user_agent="'.mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']).'",
	referer="'.mysql_real_escape_string($ref).'", 
	post="'.mysql_real_escape_string(var_export($_POST,true)).'", 
	advert_id="'.mysql_real_escape_string($advert_id).'",on_site="1",
	user_latitude="'.mysql_real_escape_string(@$_SESSION['latitude']).'", user_longitude="'.mysql_real_escape_string(@$_SESSION['longitude']).'",errorsession="'.mysql_real_escape_string($error).'",
	from_affiliate = "'.mysql_real_escape_string($affiliate_id).'"';
	if(!empty($search)) $query.=',searched_term="'.mysql_real_escape_string($search).'"';
	mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	}

/*	
	function detect_proxy_fast($ip){
	$c='-';
	if(!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost') return $c;
	$useragent='Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.18) Gecko/20110628 Ubuntu/10.10 (maverick) Firefox/3.6.18';
	$url='http://'.$ip;
	$ch=curl_init();
	$curl_opt=array(
		CURLOPT_FOLLOWLOCATION => false,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERAGENT => $useragent,
		CURLOPT_URL => $url,
		CURLOPT_TIMEOUT => 5,
		CURLOPT_REFERER => '',
	);
	curl_setopt_array($ch, $curl_opt);
	$content=curl_exec($ch);
	curl_close($ch);
	if(strlen($content)) return true;
	
	return false;
}
*/
//$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
//echo $_SERVER['REMOTE_ADDR'].'<br />';
//echo $hostname,'<br />';
//if($hostname!=$_SERVER['REMOTE_ADDR']) $_SESSION['proxy']=1; //die('Proxy detected.');
if(empty($_SESSION['user_checked_proxy'])){
//if(detect_proxy_fast($_SERVER['REMOTE_ADDR'])) $_SESSION['proxy']=2;//die('Proxy detected.');
//$_SESSION['user_checked_proxy']=1;
}

if(!isset($_SESSION['real_user'])) $_SESSION['real_user'] = 1;
echo analytics;
/*
?>
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '412987582593973');
  fbq('track', 'PageView');
</script>
<noscript>
  <img height="1" width="1" style="display:none" 
       src="https://www.facebook.com/tr?id={your-pixel-id-goes-here}&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
*/