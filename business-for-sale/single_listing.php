<?php
if(!defined('MyConst')) {
header('Location: '.WebSite);
exit(0);
}

/*echo '
<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/shop2.jpg">
	<div class="header-text1 mb-0">
		<div class="container">
			<div class="row">
				<div class="col-xl-8 col-lg-12 col-md-12 d-block mx-auto">
					<div class="text-center text-white "></div>
					
				</div>
			</div>
		</div>
	</div>
</div>
';*/
echo '<img src="../../assets/images/banners/shop2.jpg"/>';

?>
<section>
		<div class="bg-white border-bottom">
			<div class="container">
				<div class="page-header">
					<h4 class="page-title"><?php echo $lang['f_pr'];?></h4>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WebSite;?>"><?php echo $lang['home'];?></a></li>
						<?php
						if(!empty($categoryname)){
						echo '
						<li class="breadcrumb-item"><a href="../',$categorylink,'">',$categoryname,'</a></li>
						';
						}
						
						echo '
						<li class="breadcrumb-item">',$advertname,'</li>';
						?>
					</ol>
				</div>
			</div>
		</div>
</section>

<section class="sptb pb">
			<div class="container">
				<div class="row">
				<div class="col-xl-4 col-lg-4 col-md-12">
<?php
echo '
<div class="card overflow-hidden">';
//echo $categoryname;
if($categoryname != 'Детски градини' && $categoryname != 'ТД на НАП' && $categoryname != 'Посолства') echo '
<div class="card-header">
			<h3 class="card-title ib w100 tac">'.$advertrow['supplier_name'].'</h3>
		</div>
		<div class="ffo bg-secondary w100 db mt-2 tac text-white fs-18">
 <label class="mt-2 mb-2">Цена</label>: ';
 setlocale(LC_MONETARY, 'bg_BG.UTF-8');
			echo money_format('%.2n',$advertrow['business_price']);

	echo '</div>';
		echo '<div class="card-body item-user">
			<div class="profile-details pr tar">';
			$logo = '<img src="../../assets/images/other/logo.jpg" class="brround w-150 h-150" alt="'.$advertrow['supplier_name'].'"/>';
			if(!empty($advertrow['logo'])) $logo = '<img src="../../images/adverts/'.$advertrow['logo'].'" class="brround w-150 h-150" alt="'.$advertrow['supplier_name'].'"/>';
			if(empty($advertrow['logo']) && !empty($advertrow['small_image'])) $logo = '<img src="../../images/adverts/'.$advertrow['small_image'].'" class="brround w-150 h-150" alt="'.$advertrow['supplier_name'].'"/>';
			if(isset($_SESSION['customer']) && $_SESSION['customer'] == $advertrow['customer_id']) echo '
			<a href="',WebSite,'/redirector.php?edit_ad=',$advertrow['id'],'" class="btn btn-success btn-sm text-white" data-toggle="tooltip" data-original-title="Редакция">
			<i class="fa fa-pencil"></i></a>
			';
				
				echo '<div class="profile-pic mb-0 mx-5">
					',$logo,'
				</div>
			</div>
			<div class="text-center mt-2">
				<h4 class="mt-0 mb-0 font-weight-semibold">',$advertrow['supplier_name'],'</h4>
				<span class="text-muted ffo ib w100 mt-2">',$lang['pub'],' ',date('d-m-Y',strtotime($advertrow['added_date'])),' ',$lang['y'],'</span>
			</div>
		</div>';
		$workingdays = unserialize($advertrow['work_days']);
		
		$certificates = '';
		if(!empty($advertrow['certificates'])) $certificates = unserialize($advertrow['certificates']);
		echo '<div class="profile-user-tabs">
			<div class="tab-menu-heading border-0 p-0">
				<div class="tabs-menu1">
					<ul class="nav">
						<li class=""><a href="#tab-contact" class="active" data-toggle="tab">',$lang['cont'],'</a></li>';
						echo '<li><a href="#tab-timings" data-toggle="tab">',$lang['worktime'],'</a></li>';
					echo '</ul>
				</div>
			</div>
		</div>
		<div class="tab-content border-0 bg-white">
			<div class="tab-pane active" id="tab-contact">
				<div class="card-body item-user">
					<h4 class="mb-4">',$lang['contact_details'],'</h4>
					<div>
						<h6><span class="font-weight-semibold"><i class="fa fa-map mr-3 mb-2"></i></span><a href="#" class="text-body"> ',$advertrow['town'],', ',$advertrow['area'],', ',$advertrow['company_address'],'</a></h6>
						<h6><span class="font-weight-semibold"><i class="fa fa-envelope mr-3 mb-2"></i></span><a href="mailto:',$advertrow['mail'],'" class="text-body"> ',$advertrow['mail'],'</a></h6>';
						
						if(!empty($advertrow['company_phones'])){
							echo '<span class="font-weight-semibold"><i class="fa fa-phone mr-3 mb-2"></i></span>';
							$phones = explode(";", $advertrow['company_phones']);
							foreach($phones as $phone) echo '<h6 class="ib wa"><a class="phone_dialer" data-aid="',$advertrow['id'],'" data-pid="',$pid,'" href="tel:',$phone,'" class="text-secondary"> ',$phone,'</a></h6> | ';
							//var_dump($phones);
							}
					

						
						if(($advertrow['selected_plan'] > 1 || $advertrow['vip'] == "1") && !empty($advertrow['site'])) echo '<h6><span class="font-weight-semibold"><i class="fa fa-link mr-3 "></i></span><a target="_blank" data-aid="',$advertrow['id'],'" href="',$advertrow['site'],'" rel="nofollow" class="text-secondary link_clicker">',$advertrow['site'],'</a></h6>';
else{
if(!empty($advertrow['site'])) echo '<h6><span class="font-weight-semibold"><i class="fa fa-link mr-3 "></i></span>',$advertrow['site'],'</h6>';
}
					echo '</div>
				</div>
			</div>';
			
			//var_dump($workingdays);
			echo '<div class="tab-pane" id="tab-timings">
				<div class="table-responsive card-body">
					<table class="table table-bordered border-top mb-0">
						<tbody>';
						if(!empty($workingdays) && count($workingdays) > 0){
						foreach($workingdays as $day){
						echo '<tr>
								<td>',$lang['days_arr'][$day],'</td>
								<td class="font-weight-semibold">',$advertrow['opening'],' - ',$advertrow['closing'],'</td>
							</tr>';
							}
						}else echo '<tr><td colspan="2" class="tac">',$lang['no_info'],'</td></tr>';
						echo '</tbody>
					</table>
				</div>
			</div>
<div class="card-footer bg-white tac pl-1 pr-1">
<div class="icons">';
if(!empty($advertrow['company_phones'])){
$phones = explode(";", $advertrow['company_phones']);
echo '<a data-aid="',$advertrow['id'],'" data-pid="',$pid,'" href="tel:',$phones[0],'" class="btn btn-info text-white icons phone_dialer mr-1 mt-2 mb-2"><i class="icon fa fa-phone-square mr-1"></i> ',$lang['call'],'</a>';
}

echo '<a id="socialmedia" class="btn btn-danger icons mt-2 mb-2"><i class="icon icon-share mr-1"></i> ',$lang['share'],'</a>';
echo '
<a href="../../../contacts/" class="btn btn-light icons mt-2 mb-2"><i class="icon icon-exclamation mr-1"></i> ',$lang['abuse'],' </a>
</div>
<div id="loadhere"></div>
<div class="ffo bg-light w100 db mt-2 tac fs-18">
 <label class="mt-2 mb-2">Цена</label>: ';
 setlocale(LC_MONETARY, 'bg_BG.UTF-8');
echo money_format('%.2n',$advertrow['business_price']);
echo '</div>
</div>
		</div>
	</div>';
	if($advertrow['business_type'] != 2){
	echo '<div class="card" id="location">
		<div class="card-header">
			<h3 class="card-title">',$lang['trad_adr'],'</h3>
		</div>
		<div class="card-body tac">
			<div class="map-header">
				<div class="map-header-layer" id="map2">
				<a target="_blank" href="https://www.google.com/maps/place/',$advertrow['latitude'],',',$advertrow['longitude'],'/">
				<img id="mapsmpl" title="',$lang['listing_on_map'],'" src="',WebSite,'/images/mapsample.jpg"/>
				</a></div>
			</div>
			<div class="item-user mt-5">
				<div>
					<h6><span class="font-weight-semibold"><i class="fa fa-map mr-3 mb-0"></i></span><a href="#" class="text-body"> ',$advertrow['town'],', ',$advertrow['area'],', ',$advertrow['company_address'],'</a></h6>
				</div>
			</div>
		</div>
		<div class="card-footer tac">
			<a class="btn btn-secondary" target="_blank" href="https://www.google.com/maps/place/',$advertrow['latitude'],',',$advertrow['longitude'],'/">',$lang['show_loc'],'</a>
		</div>
	</div>';
	}
echo '</div>';
?>

<!--Right Side Content-->
<div class="col-xl-8 col-lg-8 col-md-12">
<?php

echo '
<div class="card overflow-hidden">';
if(!empty($advertrow['vip'])) echo '<div class="ribbon ribbon-top-right text-danger"><span class="bg-danger">VIP</span></div>';
echo '<div class="card-body">
	<div class="item-det mb-4">
		<h1 class="pt">',$advertrow['supplier_name'],'</h1>
		<div class=" d-flex">
		<input type="hidden" id="aid" value="'.$advertrow['id'].'"/>
			<ul class="d-flex mb-0">
				<li class="mr-5"><a href="../',$categorylink,'" class="icons"><i class="icon icon-briefcase text-muted mr-1"></i> ',$categoryname,'</a></li>';
				if($advertrow['business_type'] != 2) echo '<li class="mr-5"><i class="icon icon-location-pin text-muted mr-1"></i> ',$advertrow['town'],', ',$advertrow['area'],', ',$advertrow['company_address'],'</li>';
			echo '</ul>
		</div>
	</div>
	
<div class="wideget-user-tab wideget-user-tab3">
	<div class="tab-menu-heading">
		<div class="tabs-menu1">
			<ul class="nav">
				<li class=""><a href="#tab-5" class="active" data-toggle="tab">',$lang['description'],'</a></li>';
				if($categoryname != 'ТД на НАП') echo '
				<li><a href="#tab-8" data-toggle="tab" class="">',$lang['contact_form'],'</a></li>';
			echo '</ul>
		</div>
	</div>
</div>	

<div class="br-4 border br-tl-0 bg-white">
	<div class="card-body">
		<div class="border-0">
			<div class="tab-content">
			<div class="tab-pane active" id="tab-5">
			<div class="profie-img mt-5">
			<div class="mb-3">',htmlspecialchars_decode($advertrow['description']),'</div>';
			$aimages = get_advert_images($aid);
	//var_dump($aimages);
	if(count($aimages) > 0){
	echo '<div class="product-slider tac">
		<div id="carousel" class="carousel slide" data-ride="carousel">';

$your_date = strtotime($advertrow['added_date']);
$datediff = time() - $your_date;
$new = round($datediff / (60 * 60 * 24));


			if($new <= 7) echo '<div class="arrow-ribbon bg-secondary">',$lang['new'],'</div>';
			echo '<div class="carousel-inner">';
			$imgcount = 0;
			foreach($aimages as $imgkey=>$singleimg){
			$imgactive = '';
			$imgcount++;
			if($imgcount == 1) $imgactive = 'active';
			echo '<div class="carousel-item '.$imgactive.'"><img src="',WebSite,'/images/adverts/',$singleimg['image'],'" alt="',$advertrow['supplier_name'],'"/> </div>';
			}
				
			echo '</div>
			<a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
				<i class="fa fa-angle-left" aria-hidden="true"></i>
			</a>
			<a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</a>
		</div>
		<div class="clearfix">
			<div id="thumbcarousel" class="carousel slide" data-interval="false">
				<div class="carousel-inner">
					<div class="carousel-item active">';
			$imgcount = 0;
			foreach($aimages as $imgkey=>$singleimg){

			echo '<div data-target="#carousel" data-slide-to="',$imgcount,'" class="thumb"><img src="',WebSite,'/images/adverts/',$singleimg['image'],'" alt="',$advertrow['supplier_name'],'"/> </div>';
			$imgcount++;
			}
						
					echo '</div>
					<div class="carousel-item ">';
					$imgcount = 0;
			foreach($aimages as $imgkey=>$singleimg){

			echo '<div data-target="#carousel" data-slide-to="',$imgcount,'" class="thumb"><img src="',WebSite,'/images/adverts/',$singleimg['image'],'" alt="',$advertrow['supplier_name'],'"/> </div>';
			$imgcount++;
			}
				echo '</div>
				</div>
				<a class="carousel-control-prev" href="#thumbcarousel" role="button" data-slide="prev">
					<i class="fa fa-angle-left" aria-hidden="true"></i>
				</a>
				<a class="carousel-control-next" href="#thumbcarousel" role="button" data-slide="next">
					<i class="fa fa-angle-right" aria-hidden="true"></i>
				</a>
			</div>
		</div>
	</div>';
	}else echo '<div class="product-slider tac">
	<img src="',WebSite,'/images/adverts/no_product_image.jpg" alt="',$advertrow['supplier_name'],'"/>
	</div>';

	echo '</div>
			</div>';
			
		
			
			
$names = '';
$customer_email = '';
$customer_phone = '';
if(isset($_SESSION['customer_names'])) $names = $_SESSION['customer_names'];
if(isset($_SESSION['customer_email'])) $customer_email = $_SESSION['customer_email'];
if(isset($_SESSION['customer_phone'])) $customer_phone = $_SESSION['customer_phone'];
			
			echo '<div class="tab-pane" id="tab-8">
			<div class="mb-lg-0">
							<div class="card-header mb-4">
								<h3 class="card-title">',$lang['have_q'],'</h3>
							</div>
							<div class="">							
								<h4>',sprintf($lang['c_a2'],$advertrow['supplier_name']),'</h4>
								<div class="msg tac msgaaa"></div>
								<div class="mt-4">
									<div class="form-group">
									<label class="form-label">',$lang['names'],'</label>
										<input type="text" class="form-control" id="names" value="',$names,'" placeholder="Your Name"/>
									</div>
									<div class="form-group">
									<label class="form-label">',$lang['email'],'</label>
										<input type="email" class="form-control" id="email" value="',$customer_email,'" placeholder="Email Address"/>
									</div>
									<div class="form-group">
									<label class="form-label">',$lang['phone'],'</label>
										<input onkeypress="return(numberFormat(event));" type="text" class="form-control" id="phone" value="',$customer_phone,'" placeholder="Phone"/>
									</div>
									<div class="form-group">
									<label class="form-label">',$lang['message'],'</label>
										<textarea class="form-control" name="message" id="message" value="" rows="6" placeholder="',$lang['pls_ent'],'"></textarea>
									</div>
									<a id="contact_the_trader" class="btn btn-secondary">',$lang['send'],'</a>
								</div>
							</div>
						</div>
			</div>
			
		</div>
	</div>
</div>';


if(!empty($certificates)){
//var_dump($certificates);
echo '<div class="card-header mb-4">
	<h3 class="card-title">',$lang['certs'],'</h3>
</div>
<div class="card-body">
<div class="product-tags clearfix">
	<ul class="list-unstyled mb-0">';
foreach($certificates as $cert){
echo '<li><a>',$lang['certs_arr'][$cert],'</a></li>';
	}
	echo '</ul>
	</div>
</div>
';
}


if(!empty($advertrow['meta_keywords'])){
echo '
<div class="card-header mb-4">
	<h3 class="card-title">',$lang['a_tags'],'</h3>
</div>
<div class="card-body">
<div class="product-tags clearfix">
	<ul class="list-unstyled mb-0">';
	$atags = explode(",",rtrim($advertrow['meta_keywords'],','));
foreach($atags as $tag){
echo '<li><a target="_blank" href="../../business-for-sale/search.php?search=',$tag,'&exact_match=0&search_city=&category=0&search_a=1" class="tags">',$tag,'</a></li>';
	}
	echo '</ul>
	</div>
</div>';
}

$advert_docs = get_advert_docs($advertrow['id']);
//var_dump($advert_docs);
if(!empty($advert_docs)){
echo '<div class="card-header mb-4">
	<h3 class="card-title">',$lang['u_docs'],'</h3>
</div>';
$c = 0;
foreach($advert_docs as $imgkey=>$image){
$c++;
//var_dump($image);
echo '

<div class="card-body">
<label class="form-label text-dark"><h4 class="card-title mb-2">'.$image['doc_name'].'</h4>
<a target="_blank" href="'.WebSite.'/images/files/'.$image['doc_url'].'">'.$image['doc_url'].'</a></label>'.$lang['added_date'].' '.$image['added_date'].' год.

</div>';

}
}

echo '</div>	
</div>
</div>';
?>
					</div>
				</div>
		</div>
</section>
				
<?php
?>