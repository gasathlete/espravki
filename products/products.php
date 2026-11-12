<?php
if(defined('MyConst')) {
$url=$_SERVER['REQUEST_URI'];
//$url=htmlentities($_SERVER['REQUEST_URI'],ENT_QUOTES);

$parts = explode('/', rtrim($url, '/'));
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";

//if(!empty($parts[2])) $category = preg_match($pattern, urldecode($parts[2]));
//if(!empty($category)) $categorylink = urldecode($parts[2]);

if(count($parts) > 1 && count($parts) < 4){

//var_dump($product_categories);

echo '
<section class="sptb">
			<div id="accordion" class="container">
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-12 pl-1 pr-1">
						<div class="card mb-2">
							<div class="card-header pr panel-title">
								<h3 class="card-title">',$lang['cats'],'</h3>
								<a class="chev accordion-toggle" aria-expanded="false" aria-controls="collapseOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"></a>
							</div>';
				
				echo '<div id="collapseOne" class="card-body collapse show">
								<div class="" id="container">
									<div class="filter-product-checkboxs">
									<select name="category" id="category" class="form-control select2-show-search-prdcat border-bottom-0 mand" data-placeholder="',$lang['ccat'],'">
								<option value="0">',$lang['please_select'],'</option>';
						
						echo filter_product_category(0,@$level,0);			
						echo '</select></div>
						</div>
					</div>';
				/*
				if(count($product_categories) > 0){
				//setlocale(LC_ALL,$_SESSION['lang'].'_'.strtoupper($_SESSION['lang']).'.UTF-8');
				//asort($product_categories,SORT_LOCALE_STRING);
							echo '<div id="collapseOne" class="card-body collapse show">
								<div class="" id="container">
									<div class="filter-product-checkboxs">';
									
									foreach($product_categories as $pckey=>$categorynames){
									$catchecked = '';
									if($categorylink == $categorynames['url']) $catchecked = 'checked="checked"';
										echo '<label class="custom-control custom-radio mb-3">
											<input ',$catchecked,' type="radio" class="custom-control-input catradio" name="checkbox1" value="',$categorynames['url'],'">
											<span class="custom-control-label">
												',$categorynames['name'],'<span class="spspan label-primary float-right">',$categorynames['counter'],'</span>
											</span>
										</label>';
											}
										
									echo '</div>
								</div>
							</div>';
							}*/
							
							//var_dump($categories_array);echo count($categories_array),'aaa';
							//echo $category_id,'aaa';
							//var_dump($categories_array);
							if((!empty($categories_array) && is_array($categories_array) && count($categories_array) > 0) || !empty($category_id)){
							$marks = get_marks($categories_array,$category_id);
							//var_dump($marks).'aaaa';
							if(is_array($marks) && count($marks) > 0){
							if(count($marks['make']) > 0){
							echo '
							<div class="card-header pr panel-title border-top">
								<h3 class="card-title">',$lang['marka'],'</h3>
								<a class="chev accordion-toggle" aria-expanded="false" aria-controls="collapsethree" data-toggle="collapse" data-parent="#accordion" href="#collapsethree"></a>
							</div>
							
							<div id="collapsethree" class="card-body collapse show">
							<div class="card-body">
							<label class="custom-control custom-radio mb-3">
									<input '; if(!isset($_POST['make']) || $_POST['make'] == 0) echo 'checked';echo ' type="radio" class="custom-control-input marka" name="mark" value="0">
									<span class="custom-control-label">',$lang['all'],'</span>
								</label>';
							
							foreach($marks['make'] as $mark){
							$mchecked = '';
							if(isset($_POST['make']) && $_POST['make'] == $mark) $mchecked = 'checked';
							echo '<label class="custom-control custom-radio mb-3">
									<input ',$mchecked,' type="radio" class="custom-control-input marka" name="mark" value="',$mark,'"/>
									<span class="custom-control-label">',$mark,'</span>
								</label>';
							}
							echo '</div>
							</div>';
								}
								
							if(count($marks['model']) > 0){
							echo '
							<div class="card-header pr panel-title border-top">
								<h3 class="card-title">',$lang['model'],'</h3>
								<a class="chev accordion-toggle" aria-expanded="false" aria-controls="collapsefour" data-toggle="collapse" data-parent="#accordion" href="#collapsefour"></a>
							</div>
							
							<div id="collapsefour" class="card-body collapse show">
							<div class="card-body">
							<label class="custom-control custom-radio mb-3">
									<input '; if(!isset($_POST['model']) || $_POST['model'] == 0) echo 'checked';echo ' type="radio" class="custom-control-input model" name="model" value="0">
									<span class="custom-control-label">',$lang['all'],'</span>
								</label>';
							
							foreach($marks['model'] as $model){
							$mochecked = '';
							if(isset($_POST['model']) && $_POST['model'] == $model) $mochecked = 'checked';
							echo '<label class="custom-control custom-radio mb-3">
									<input ',$mochecked,' type="radio" class="custom-control-input model" name="model" value="',$model,'"/>
									<span class="custom-control-label">',$model,'</span>
								</label>';
							}
							echo '</div>
							</div>';
								}
								
								if(count($marks['modification']) > 0){
							echo '
							<div class="card-header pr panel-title border-top">
								<h3 class="card-title">',$lang['modification'],'</h3>
								<a class="chev accordion-toggle" aria-expanded="false" aria-controls="collapsefive" data-toggle="collapse" data-parent="#accordion" href="#collapsefive"></a>
							</div>
							
							<div id="collapsefive" class="card-body collapse show">
							<div class="card-body">
							<label class="custom-control custom-radio mb-3">
									<input '; if(!isset($_POST['modification']) || $_POST['modification'] == 0) echo 'checked';echo ' type="radio" class="custom-control-input modification" name="modification" value="0">
									<span class="custom-control-label">',$lang['all'],'</span>
								</label>';
							
							foreach($marks['modification'] as $modification){
							$modchecked = '';
							if(isset($_POST['modification']) && $_POST['modification'] == $modification) $modchecked = 'checked';
							echo '<label class="custom-control custom-radio mb-3">
									<input ',$modchecked,' type="radio" class="custom-control-input modification" name="modification" value="',$modification,'"/>
									<span class="custom-control-label">',$modification,'</span>
								</label>';
							}
							echo '</div>
							</div>';
									}
								}
							}
							echo '<div class="card-header pr panel-title border-top">
								<h3 class="card-title">',$lang['ot'],'</h3>
								<a class="chev accordion-toggle" aria-expanded="false" aria-controls="collapsetwo" data-toggle="collapse" data-parent="#accordion" href="#collapsetwo"></a>
							</div>
							
							<div id="collapsetwo" class="card-body collapse show">
							<div class="card-body">
							<label class="custom-control custom-radio mb-3">
									<input '; if(!isset($_POST['offer_type']) || $_POST['offer_type'] == 0) echo 'checked';echo ' type="radio" class="custom-control-input otradio" name="checkbox2" value="0">
									<span class="custom-control-label">',$lang['all'],'</span>
								</label>';
							foreach($lang['off_type_arr'] as $otkey=>$otname){
							$otchecked = '';
							if(isset($_POST['offer_type']) && $_POST['offer_type'] == $otkey) $otchecked = 'checked';
							echo '<label class="custom-control custom-radio mb-3">
									<input ',$otchecked,' type="radio" class="custom-control-input otradio" name="checkbox2" value="',$otkey,'"/>
									<span class="custom-control-label">',$otname,'</span>
								</label>';
							}
							
							echo '</div>
							</div>
							
							<div class="card-footer">
								<a id="filter_category" class="btn btn-info btn-block text-white">',$lang['update'],'</a>
							</div>
						</div>
					</div>';

$query='select p.id, product_url, category_name, category_url, title, price, promo_price, p.image, p.description, p.addeddate, supplier_name, link_name, pc.url 
from products p, adverts a, adverts_to_product_categories atpc, products_categories pc  
where p.active="1" and p.sold="0" and p.deleted="0" and p.checked_by_admin = "1" and p.advert_id = a.id and a.id = atpc.advert_id and atpc.category_id = pc.id';
if(!empty($_POST['search_text'])){
$_POST['search_text'] = trim(preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll}\w]/u', ' ', $_POST['search_text']));;
$_POST['search_text'] = str_replace(array('"',"'",')','('),' ',$_POST['search_text']);
if(!empty($_POST['search_text'])){
$search = $_POST['search_text'];
$a = '';
$search1 = '';
$a=array(' ',"\t","\r","\n",'\\','\'','"','<','>','?','!','@','#','$','%','^','&','*','(',')','_','-','+','|', '~','`',',','.','/','{','}','[',']',':',';');
	$searchterm=str_replace($a,'%',$search);
	
	$a=explode('%',$searchterm);
	$searchterm='%';
	//var_dump($a);
	
$str = $_POST['search_text']; 
$commonwords = 'a,an,and,I,it,is,do,does,for,from,go,how,the,this,are';// you dont want to search for these common words

$commonwords = explode(",", $commonwords);

$str = explode(" ", $str);

foreach($str as $value){
if(mb_strlen($value,'utf8')>=3){
  //if(!in_array($value, $commonwords)){ // remove the common words from search

    $words[] = $value;
  }
}   

$words = implode(" ", $words);// generate coma separated values 

$str2 =(explode(" ",strtolower($words)));// convert the values to an array
$newwords = implode('|',array_values($str2)).'';// generate values to be searched and make regex
//var_dump($str2);
//$query.= " and (`category_name` REGEXP '$newwords' or `title` REGEXP '$newwords' or `product_tags` REGEXP '$newwords' ) ";

foreach($str2 as $k=>$v){
$query1.= "  LOWER(`title`) REGEXP '$v' and ";
$query2.= "  LOWER(`product_tags`) REGEXP '$v' and ";
}

$query1 = rtrim($query1,'and ');
$query2 = rtrim($query2,'and ');

$query.= ' and (( '.rtrim($query1,'and').' ) or ( p.category_name LIKE "%'.$search.'%" ) or ( p.product_tags LIKE "%'.$search.'%" ))';

	/*if(count($a)>1){
		foreach($a as $k=>$v){
			if(mb_strlen($v,'utf8')>=3){
			
			if(mb_strpos('אמטוףÿ',mb_substr($v,-1,1,'utf8'))!==false){
					$searchterm=mb_substr($v,0,(mb_strlen($v,'utf8')-1),'utf8').'';
					
					$aconstruct.=' p.category_name LIKE "%'.$searchterm.'%" AND';
					$aconstruct1.=' p.title LIKE "%'.$searchterm.'%" AND';
					$aconstruct2.=' p.product_tags LIKE "%'.$searchterm.'%" AND';
				}
				else{
				$searchterm.=$v.' ';
				$aconstruct.=' p.category_name LIKE "'.$searchterm.'%" AND';
				$aconstruct1.=' p.title LIKE "'.$searchterm.'%" AND';
				$aconstruct2.=' p.product_tags LIKE "'.$searchterm.'%" AND';
				
				}
				
			//$searchterm.=$v.' ';
				//$aconstruct.=' p.category_name LIKE "'.$searchterm.'%" AND';
				//$aconstruct1.=' p.title LIKE "'.$searchterm.'%" OR';
				//$aconstruct2.=' p.product_tags LIKE "'.$searchterm.'%" AND';
			
			}
		
		}
		
		$query.= ' and ( ('.rtrim($aconstruct,'AND').') or ('.rtrim($aconstruct1,'AND').') or ('.rtrim($aconstruct2,'AND').') or 
	(p.title_latin LIKE "%'.$search.'%") or 
	(p.tags_latin LIKE "%'.$search.'%")
	)';
	}else {
$query.=' and ( p.category_name like "%'.mysql_real_escape_string($_POST['search_text']).'%" || 
p.title like "%'.mysql_real_escape_string($_POST['search_text']).'%" || p.product_tags like "%'.mysql_real_escape_string($_POST['search_text']).'%" || 
p.title_latin like "%'.mysql_real_escape_string($_POST['search_text']).'%" || p.tags_latin like "%'.mysql_real_escape_string($_POST['search_text']).'%")';
		}*/
	}
}


//$query.= ' '.$query1.' or ('.$query2.')';
//$query.= ' and ( ('.rtrim($query1,'AND').') or ('.rtrim($query2,'AND').') )';

//var_dump($categories_array);
if(isset($_POST['offer_type']) && (intval($_POST['offer_type']) > 0 && intval($_POST['offer_type']) < 3)) $query.=' and offer_type = "'.mysql_real_escape_string($_POST['offer_type']).'" ';
if(isset($categories_array) && is_array($categories_array) && count($categories_array) > 0){
$query.=' and p.category_id IN (' . implode(",", $categories_array) . ')';
}else{
if(!empty($categorylink)) $query.=' and p.category_url = "'.mysql_real_escape_string($categorylink).'"';
if(!empty($category_id)) $query.=' and p.category_id = "'.mysql_real_escape_string($category_id).'"';

}
if(isset($_POST['make']) && !empty($_POST['make'])){
$_POST['make'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['make'])));
$query.=' and make = "'.mysql_real_escape_string($_POST['make']).'" ';
}

if(isset($_POST['model']) && !empty($_POST['model'])){
$_POST['model'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['model'])));
$query.=' and model = "'.mysql_real_escape_string($_POST['model']).'" ';
}

if(isset($_POST['modification']) && !empty($_POST['modification'])){
$_POST['modification'] = preg_replace('/[^ \w & | "\'\/,.\p{Cyrillic}]+/u', '',strip_tags(str_replace('"',"'",$_POST['modification'])));
$query.=' and modification = "'.mysql_real_escape_string($_POST['modification']).'" ';
}

$query.=' group by p.id ';
if(!isset($_POST['sort'])) $query.= ' order by a.vip DESC, p.addeddate DESC';
else{
if($_POST['sort'] == 'cheap') $query.=' order by p.price ASC';
if($_POST['sort'] == 'newest') $query.=' order by p.addeddate DESC';
if($_POST['sort'] == 'vip' || $_POST['sort'] == 'all') $query.=' order by a.vip DESC, p.price ASC';
}

$allquery = $query;

$query.= ' limit 12';
//var_dump($_POST);
//echo $query;
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));	
$num_rows=mysql_num_rows($result);

$allresult=mysql_query($allquery) or die(send_error($allquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$allnum_rows=mysql_num_rows($allresult);
					
					//<!--Right Side Content-->
					echo '<div class="col-xl-9 col-lg-9 col-md-12 pl-1 pr-1">
						<div class=" mb-lg-0">
							<div class="">
								<div class="item2-gl business-list-01">
									<div class="">
										<div class="bg-white p-5 item2-gl-nav ">';
										if(!empty($categoryname)) echo '<h1 class="ib w100 tal">',$categoryname,'</h1>';
											echo '<h6 class="mb-0 ib w29 mobw100 tal mt-3">',$lang['showing'],' <span id="cr">',$num_rows,'</span> / ',$allnum_rows,' ',$lang['meta_search']['results'],'</h6>
											<div class="ib w70 mobw100">
											<ul class="nav item2-gl-menu ib ml-auto mt-1">
												<li><a href="#tab-12" data-toggle="tab" class="active show" title="Grid"><i class="fa fa-th"></i></a></li>
											</ul>
											<div class="ib mobw100">
												<label class="mr-2 mt-2 mb-sm-1 sort-label">',$lang['sort'],':</label>
												<div class="selectgroup ffol">
													<label class="selectgroup-item mb-md-0">
														<input type="radio" ';if(isset($_POST['sort']) && $_POST['sort'] == 'all') echo 'checked'; echo ' name="sort" value="all" class="selectgroup-input sort_ch" >
														<span class="selectgroup-button d-md-flex">',$lang['all'],' <i class="fa fa-sort ml-2 mt-1"></i></span>
													</label>
													<label class="selectgroup-item mb-md-0">
														<input type="radio" ';if(isset($_POST['sort']) && $_POST['sort'] == 'cheap') echo 'checked'; echo ' name="sort" value="cheap" class="selectgroup-input sort_ch">
														<span class="selectgroup-button">',$lang['cheap'],'</span>
													</label>
													<label class="selectgroup-item mb-md-0">
														<input type="radio" ';if(isset($_POST['sort']) && $_POST['sort'] == 'newest') echo 'checked'; echo ' name="sort" value="newest" class="selectgroup-input sort_ch">
														<span class="selectgroup-button">',$lang['newest'],'</span>
													</label>
													<label class="selectgroup-item mb-0">
														<input type="radio" ';if(!isset($_POST['sort']) || $_POST['sort'] == 'vip') echo 'checked'; echo ' name="sort" value="vip" class="selectgroup-input sort_ch">
														<span class="selectgroup-button">',$lang['reccom'],'</span>
													</label>
												</div>
											</div>
											</div>
										</div>
									</div>
									<div class="tab-content">
										<div class="tab-pane active products_tab" id="tab-12">
											<div class="row">';
											$htmllistings = echo_products_everywhere($query);
											//var_dump($htmllistings);
											echo $htmllistings[0];
											
												
									echo '</div>
										</div>
									</div>
								</div>
								<div class="row tac">
<ul class="pagination mt-3">
<li class="page-item page-prev disabled">
	<a class="page-link" href="#" tabindex="-1">Prev</a>
</li>
<li class="page-item active"><a class="page-link" href="#">1</a></li>
<li class="page-item page-next disabled">
	<a class="page-link" href="#">Next</a>
</li>
</ul>
</div>
							</div>
						</div>
						<!--/Add lists-->
					</div>
					<!--/Right Side Content-->
				</div>
			</div>
		</section>
';
	}
}
?>