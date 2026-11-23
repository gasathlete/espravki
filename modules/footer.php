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

// visit statistics + affiliate cookie (shared with the v3 layout)
include dirname(__FILE__).'/footer_tracking.php';
// Google Analytics 4: set define("ga4_id","G-XXXXXXX"); in config.php. The old UA code (shut down by Google in 2023) is no longer printed.
if(defined('ga4_id') && preg_match('/^G-[A-Z0-9]+$/', ga4_id)){
	echo "<script async src='https://www.googletagmanager.com/gtag/js?id=".ga4_id."'></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '".ga4_id."');</script>";
}
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