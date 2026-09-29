<?php
session_start();
header("Content-Type: text/html; charset=utf-8");

require '../config.php';
require '../project_functions.php';

require '../translation/'.$_SESSION['lang'].'_lang.php';


//var_dump($_POST);

function count_comments($aid){

$count = 0;
$query = 'select count(id) from comments_to_articles where article_id="'.$aid.'" and active="1"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
if(mysql_num_rows($result) > 0){
	$row=mysql_fetch_row($result);
	$count = $row[0];
	}
	return $count;
}


function get_comments($aid,$count){
global $lang;
$html = '';
$query = 'select * from comments_to_articles where article_id="'.$aid.'" and active="1"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);
if($num_rows > 0){
$html.='<div class="card">
<div class="card-header">
	<h3 class="card-title">'.$count.' '.$lang['a_comments'].'</h3>
</div>
<div class="card-body">';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_assoc($result);
	$html.='
	<div class="d-sm-flex mt-0 p-5 sub-review-section border br-bl-0 br-br-0">
		<div class="d-flex mr-3">
			<a href="#"><img class="media-object brround avatar-md" alt="64x64" src="../../assets/images/faces/male/1.jpg"> </a>
		</div>
		<div class="media-body">
			<h5 class="mt-0 mb-1 font-weight-semibold">'.$row['cnames'].'
				<span class="fs-14 ml-0" data-toggle="tooltip" data-placement="top" title="verified"><i class="fa fa-check-circle-o text-success"></i></span>
				<span class="fs-14 ml-2"> ';
		for($a=0;$a<$row['rating'];$a++) $html.='<i class="fa fa-star text-yellow"></i>';
				
			$html.='</span></h5>
			<small class="text-muted"><i class="fa fa-calendar"></i> '.strftime("%a, %d %B %Y", strtotime($row['added_date'])).'  <i class=" ml-3 fa fa-clock-o"></i> '.date('H:m',strtotime($row['added_date'])).'  <i class=" ml-3 fa fa-map-marker"></i> '.$row['city'].'</small>
			<p class="font-13  mb-2 mt-2">
			   '.$row['comment'].'
			</p>';
			if($row['rating'] > 3) $html.='<span class="badge badge-secondary">Helpful</span>';
			$html.='<a href="#commentarea" class="mr-2 mt-1" data-target="#commentarea"><span class="badge badge-light">'.$lang['com'].'</span></a>
			<a href="'.WebSite.'/contacts/" target="_blank" class="mr-2 mt-1"><span  class="badge badge-light">'.$lang['report'].'</span></a>
			<div class="btn-group btn-group-sm mb-1 ml-auto float-sm-right mt-1">
				<button class="btn btn-light" type="button"><i class="fa fa-thumbs-up"></i></button>
				<button class="btn btn-light" type="button"><i class="fa fa-thumbs-down"></i></button>
			</div>
		</div>
	</div>
	
	';
	
		}
	$html.='</div></div>';
	}
	return $html;
}

$articlelink='';

$url=htmlentities($_SERVER['REQUEST_URI'],ENT_QUOTES);
if(isset($_GET['fbclid'])) $url = substr($url, 0, strpos($url, "?fbclid"));

$parts = explode('/', rtrim($url, '/'));
//var_dump($parts);
$pattern = '/^[a-zA-Z\p{Cyrillic}\d\s\-]+$/u';

if(!empty($parts[2])){
//if(!preg_match('/^[A-Za-z\p{Cyrillic}\0-9-]+$/', $parts[2])){
if(!preg_match($pattern, $parts[2])){
header('Location: ../index.php');
exit(0);
	}else $articlelink=$parts[2]; 
}

if(!empty($articlelink)){
	$query='select * from articles where unique_name="'.mysql_real_escape_string($articlelink).'"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($result) > 0){
	$row=mysql_fetch_assoc($result);

}else{
header('Location: ../news/');
exit(0);
	}
}

$valid_search='';
if(isset($_POST['s']) && preg_match($pattern, $_POST['s'])) $valid_search=trim($_POST['s']);
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<?php
if(!empty($articlelink)){
	if(!empty($row['small_image'])){
	$mainimage = '<img src="'.WebSite.'/images/articles/'.$row['small_image'].'" alt="'.$row['bg_article_title'].'" />';
	echo '<meta itemprop="image" content="'.WebSite.'/images/articles/'.$row['small_image'].'"/>
	<meta property="og:image" content="'.WebSite.'/images/articles/'.$row['small_image'].'" />';
	}
	else{
	echo '<meta itemprop="image" content="'.WebSite.'/images/site_screen.jpg"/>
	<meta property="og:image" content="'.WebSite.'/images/site_screen.jpg" />';
	}
	
	echo '<title>',$row['bg_article_title'],'</title>';
	echo '<meta name="description" content="',$row['bg_meta_description'],'" />';
	echo '<meta name="keywords" content="',$row['keywords'],'" />';
	echo $image;
	echo '<meta property="og:title" content="',$row['bg_article_title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'/news/'.$articlelink.'" />';
	echo $image2;
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$row['bg_meta_description'],'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	
		}else{
	echo '<title>',$lang['meta_news']['title'],'</title>';
	echo '<meta name="description" content="',$lang['meta_news']['description'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_news']['keywords'],'" />';
	echo '<meta itemprop="image" content="'.WebSite.'/images/site_screen.jpg"/>
	<meta property="og:image" content="'.WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:title" content="',$lang['meta_news']['title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'/news/" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['meta_news']['description'],'" />';

	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	
	}
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="author" content="Web-site-maker.eu">
<link rel="icon" type="image/png" href="<?php echo WebSite;?>/favicon.ico" />
<link href="../assets/css/style.css" rel="stylesheet" />
<link href="../assets/css/buttons.css" rel="stylesheet" />

</head>
<body>
<?php
include '../modules/header.php';
?>
<section>
	<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/banner2.jpg">
		<div class="header-text mb-0">
			<div class="container">
				<div class="text-center text-white">
					<h1 class=""><?php echo $lang['news'];?></h1>
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
if(!empty($articlelink)){
$aid = $row['id'];
echo '
<li class="breadcrumb-item"><a href="../news/">'.$lang['news'].'</a></li>
<li class="breadcrumb-item active">',$row['bg_article_title'],'</li>';
}else echo '<li class="breadcrumb-item">'.$lang['news'].'</li>';
?>
						
						
					</ol>
				</div>
			</div>
		</div>

		<!--Add listing-->
		<section class="sptb">
			<div class="container">
				<div class="row">

<?php
setlocale(LC_ALL, 'bg_BG.UTF-8');
if(empty($articlelink)){
?>
<div class="col-xl-9 col-lg-9 col-md-12">
<div class=" mb-lg-0">
	<div class="">
	<div class="item2-gl business-list-01">
		
<?php
$query='SELECT unique_name, bg_meta_description, bg_article_title, small_image, category_id, added_date, id FROM articles WHERE active="1"';
if(isset($_POST['cat']) && !empty($_POST['cat'])) $query.= ' and category_id="'.mysql_real_escape_string($_POST['cat']).'"';
else $query.= ' and category_id="2"';
if(!empty($valid_search)) $query.= ' and (
	bg_article_title LIKE "%'.mysql_real_escape_string($valid_search).'%" or 
	bg_meta_description LIKE "%'.mysql_real_escape_string($valid_search).'%" or 
	bg_tags LIKE "%'.mysql_real_escape_string($valid_search).'%" 
	)';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows2=mysql_num_rows($result);
$query.=' order by added_date DESC limit 8';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$result1=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	$num_rows=mysql_num_rows($result);
	echo '<div class="">
	<div class="bg-white p-5 mp-1 item2-gl-nav d-flex">
	<h6 class="mb-0 mt-3">',$lang['search_h1_before'],' ',$num_rows2,' ',$lang['news'],'</h6>
	<ul class="nav item2-gl-menu ml-auto mt-1">
		<li><a href="#tab-11" class="active show" data-toggle="tab" title="List style"><i class="fa fa-list"></i></a></li>
		<li><a href="#tab-12" data-toggle="tab" class="" title="Grid"><i class="fa fa-th"></i></a></li>
	</ul>
</div>
</div>';
	if($num_rows >0){
echo '
<div class="tab-content newstab">
<div class="tab-pane row" id="tab-12">
<div class="row">';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];

	if(!file_exists(WebHome.'/images/articles/'.$image.'')) $image = 'no_product_image.jpg';
	
	echo '
	<div data-id="'.($i+1).'" class="col-lg-6 col-md-6 col-xl-6 snglart">
		<div class="card">
			<div class="item7-card-img h250p oh">
				<a href="./',$row[0],'">
				<img src="../images/articles/'.$image.'" alt="',$row[2],'" class="cover-image"/>
				<div class="item7-card-text">
					<span class="badge badge-success">'.$lang['artcle_categories'][$row[4]].'</span>
				</div></a>
			</div>
			<div class="card-body">
				<div class="item7-card-desc d-flex mb-2">
					<a href="#"><i class="fa fa-calendar-o text-muted mr-2"></i>'.strftime("%a, %d %B %Y", strtotime($row[5])).' г.</a>
					<div class="ml-auto">
						<a class="mr-0" href="#"><i class="fa fa-comment-o text-muted mr-2"></i>',count_comments($row[6]),' ',$lang['a_comments'],'</a>
					</div>
				</div>
				<a href="./',$row[0],'" class="text-dark"><h4 class="font-weight-semibold h40p">',$row[2],'</h4></a>
				<p class="desc">',$row[1],'</p>
				<a href="./',$row[0],'" class="btn btn-secondary btn-sm">',$lang['read'],'</a>
			</div>
		</div>
	</div>';
		}
		echo '</div></div>';
		
echo '<div class="tab-pane active" id="tab-11">';
	for($a=0;$a<$num_rows;$a++){
	$row=mysql_fetch_row($result1);
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];

	if(!file_exists(WebHome.'/images/articles/'.$image.'')) $image = 'no_product_image.jpg';
	
	
	echo '
	<div data-id="'.($a+1).'" class="card overflow-hidden snglart">
	<div class="row no-gutters blog-list">
		<div class="col-xl-4 col-lg-12 col-md-12">
			<div class="item7-card-img">
				<a href="./',$row[0],'"></a>
				<img src="../images/articles/'.$image.'" alt="',$row[2],'" class="cover-image">
				<div class="item7-card-text">
					<span class="badge badge-success">'.$lang['artcle_categories'][$row[4]].'</span>
				</div>
			</div>
		</div>
		<div class="col-xl-8 col-lg-12 col-md-12">
			<div class="card-body">
				<a href="./',$row[0],'" class="text-dark"><h4 class="font-weight-semibold mb-2">',$row[2],'</h4></a>
				<div class="item7-card-desc d-flex mb-3">
					<a href="#"><i class="fa fa-calendar-o text-muted mr-2"></i>'.strftime("%a, %d %B %Y", strtotime($row[5])).' г.</a>
					<a href="#"><i class="fa fa-user text-muted mr-2"></i>',$lang['admin'],'</a>
					<div class="ml-auto">
						<a class="mr-0" href="#"><i class="fa fa-comment-o text-muted mr-2"></i>',count_comments($row[6]),' ',$lang['a_comments'],'</a>
					</div>
				</div>
				<p class="mb-1 leading-tight">',$row[1],'</p>
				<a href="./',$row[0],'" class="btn btn-secondary btn-sm mt-4">',$lang['read'],'</a>
			</div>
		</div>
	</div>
</div>';
	}
echo '</div>';		

echo '</div>';
}
?>
</div>
</div>
</div>
</div>	
<?php
}else{
//single article
$countcomments = count_comments($row['id']);
echo '
<div class="col-xl-8 col-lg-8 col-md-12">
	<div class="card">
		<div class="card-body">
			<a href="#" class="text-dark"><h3 class="font-weight-semibold">',$row['bg_article_title'],'</h3></a>
			<div class="">'.$mainimage.'
			<input type="hidden" id="aid" value="',$row['id'],'"/>
			<input type="hidden" id="atitle" value="',$row['bg_article_title'],'"/></div>';
			
			
			echo '<div class="item7-card-desc d-flex mb-2 mt-3">
				<p class="mr-1 ib"><i class="fa fa-calendar-o text-muted mr-2"></i>'.strftime("%a, %d %B %Y", strtotime($row['added_date'])).' г.</p>
				<p class="ml-5 ib"><a href="#"><i class="fa fa-list-alt mr-1" aria-hidden="true"></i>',$lang['artcle_categories'][$row['category_id']],'</a></p>';
				if($row['category_id'] == 2) echo '
				<div class="ml-auto ib">
					<a href="#" class="mr-0"><i class="fa fa-comment-o text-muted mr-2"></i>'.$countcomments.' ',$lang['a_comments'],'</a>
				</div>';
			echo '</div>

			<p>',htmlspecialchars_decode($row['bg_short_description']),'</p>';
			
			if(!empty($row['image1'])){
			echo '<h4 class="mt-6 mb-3">',$lang['images'],'</h4>
			<div class="row">
				<div class="col-lg-3 col-sm-6">
					<img src="../../images/articles/',$row['image1'],'" alt="',$row['bg_article_title'],'" class="mt-3"/>
				</div>';
			if(!empty($row['image2'])) echo '
			<div class="col-lg-3 col-sm-6">
					<img src="../../images/articles/',$row['image2'],'" alt="',$row['bg_article_title'],'" class="mt-3"/>
				</div>';
			if(!empty($row['image3'])) echo '
			<div class="col-lg-3 col-sm-6">
					<img src="../../images/articles/',$row['image3'],'" alt="',$row['bg_article_title'],'" class="mt-3"/>
				</div>';
			if(!empty($row['image2'])) echo '
			<div class="col-lg-3 col-sm-6">
					<img src="../../images/articles/',$row['image4'],'" alt="',$row['bg_article_title'],'" class="mt-3"/>
				</div>';
			echo '</div>';
			}
		echo '</div>
	</div>';

if($row['category_id'] == 2){

echo get_comments($row['id'],$countcomments);
	echo '
	<div id="commentarea" class="card mb-lg-0">
	<div class="msg tac"></div>
		<div class="card-header">
			<h3 class="card-title">',$lang['your_comment'],'</h3>
		</div>
		<div class="card-body">
			<div class="mt-2">
				<div class="form-group">
					<input type="text" class="form-control" id="names" placeholder="',$lang['names'],'">
				</div>
				<div class="form-group">
					<input type="email" class="form-control" id="email" placeholder="',$lang['email'],'">
				</div>
				<div class="form-group">
					<textarea class="form-control" id="review" name="example-textarea-input" rows="6" placeholder="',$lang['your_comment'],'"></textarea>
				</div>
				
				<div id="votes" class="form-group">
					<i data-id="6" class="likes fa fa-thumbs-o-up mr-2 ib">',$lang['ilike'],'</i> <i data-id="2" class="likes fa fa-thumbs-o-down ml-2 ib">',$lang['dontlike'],'</i>
					<input type="hidden" id="like" value=""/>
				</div>
				
				<a href="#" id="savecomment" class="btn btn-secondary">',$lang['savecomment'],'</a>
			</div>
		</div>
	</div>';
	}
echo '</div>	
';
}
?>
					<!--/Add lists-->

					<!--Right Side Content-->
					<div class="col-xl-3 col-lg-3 col-md-12">
						<div class="card">
						<form id="searchform" class="rd-search offset-top-32" method="post" action="./">
							<div class="card-body">
								<div class="input-group">
									<input type="text" class="form-control br-tl-3  br-bl-3" name="s" value="<?php echo @$valid_search;?>" id="searchnews" placeholder="<?php echo $lang['search'];?>">
									<input type="hidden" name="cat" value="<?php echo $_POST['cat'];?>" id="newscats"/>
									<div class="input-group-append ">
										<button type="submit" name="sernew" class="btn btn-secondary br-tr-3  br-br-3">
											<?php echo $lang['search'];?>
										</button>
									</div>
								</div>
							</div>
							</form>
						</div>
						<div class="card">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['cats'];?></h3>
							</div>
							<div class="card-body p-0">
								<div class="list-catergory">
									<div class="item-list">
										<ul class="list-group mb-0">
										
											<li class="list-group-item">
												<a onclick="set_cat(1);" href="#" class="text-dark ">
													<i class="fa fa-briefcase bg-secondary text-secondary"></i> <?php echo $lang['artcle_categories'][1];?>
													<span class="badgetext badge badge-pill badge-light mb-0 mt-1 mt-1"><?php echo $lang['view'];?></span>
												</a>
											</li>
											
											<li class="list-group-item">
												<a onclick="set_cat(2);" href="#" class="text-dark ">
													<i class="fa fa-newspaper-o bg-secondary text-secondary"></i> <?php echo $lang['artcle_categories'][2];?>
													<span class="badgetext badge badge-pill badge-light mb-0 mt-1 mt-1"><?php echo $lang['view'];?></span>
												</a>
											</li>
											
											<li class="list-group-item">
												<a onclick="set_cat(3);" href="#" class="text-dark ">
													<i class="fa fa-archive bg-secondary text-secondary"></i> <?php echo $lang['artcle_categories'][3];?>
													<span class="badgetext badge badge-pill badge-light mb-0 mt-1 mt-1"><?php echo $lang['view'];?></span>
												</a>
											</li>
											
										</ul>
									</div>
								</div>
							</div>
						</div>
						<?php
						if(!empty($row['bg_tags'])){
						?>
						<div class="card">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['a_tags'];?></h3>
							</div>
							<div class="card-body">
								<div class="product-tags clearfix">
									<ul class="list-unstyled mb-0">
<?php
$article_tags = rtrim($row['bg_tags'],';');
$tags = explode(';',$article_tags);
foreach($tags as $tag){
echo '<li><a data-tag="',trim($tag),'" class="btn-tag tagsclicker" href="#">',trim($tag),'</a></li>';
}
?>
									</ul>
								</div>
							</div>
						</div>
						<?php
						}
						
						include '../modules/banners.php';
						?>
						
						
						
						
						
						
						
					</div>
					<!--/Right Side Content-->
				</div>
			</div>
		</section>
		<!--Add listing-->



		<!-- Home Video Modal -->
		<div class="modal fade" id="homeVideo" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="pauseVid()"><i class="fa fa-times" aria-hidden="true"></i></button>
					<div class="embed-responsive embed-responsive-16by9">
						<video id="gossVideo" class="embed-responsive-item" controls="controls">
							<source src="../../assets/video/300052515.mp4" type="video/mp4">
						</video>
					</div>
				</div>
			</div>
		</div>

<?php include '../modules/footer.php';?>	
<a href="#top" id="back-to-top" ><i class="fa fa-rocket"></i></a>
<script src="../../assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="../../assets/js/fullfunctions.js"></script>
<script src="../../assets/js/contacts.js"></script>
</body>
</html>