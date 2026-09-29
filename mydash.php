<?php
session_start();
require 'config.php';
require './translation/'.$_SESSION['lang'].'_lang.php';
require 'project_functions.php';

if(isset($_SESSION['customer']) && !empty($_SESSION['customer']) && intval($_SESSION['customer']) > 0){

if(isset($_GET['messages']) && isset($_GET['msg'])){
if(strlen($_GET['msg']) == 32){
$query='select * from messages where secret = "'.mysql_real_escape_string($_GET['msg']).'" and recipient_id ="'.mysql_real_escape_string($_SESSION['customer']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);
if($num_rows > 0){
$msgrow=mysql_fetch_assoc($result);
$updm = 'update messages set readed = "1" where id = "'.mysql_real_escape_string($msgrow['id']).'"';
$updmresult=mysql_query($updm) or die(send_error($updm,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		}else{
		header('Location: '.WebSite.'/mydash.php?messages=show');
		exit(0);
		}
	}else{
		header('Location: '.WebSite.'/mydash.php?messages=show');
		exit(0);
		}
}

function echo_meta(){
	global $lang;
	if(isset($_GET['my_orders'])){
	echo '<title>',$lang['my_orders'],'</title>';
	echo '<meta name="description" content="',$lang['my_orders'],'" />';
	echo '<meta name="keywords" content="',$lang['my_orders'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['my_orders'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['my_orders'],'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}
	elseif(isset($_GET['profile'])){
	echo '<title>',$lang['p_edit'],'</title>';
	echo '<meta name="description" content="',$lang['p_edit'],'" />';
	echo '<meta name="keywords" content="',$lang['p_edit'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['p_edit'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['p_edit'],'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}
	elseif(isset($_GET['my_listings'])){
	echo '<title>',$lang['my_b'],'</title>';
	echo '<meta name="description" content="',$lang['my_b'],'" />';
	echo '<meta name="keywords" content="',$lang['my_b'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['my_b'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['my_b'],'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}
	elseif(isset($_GET['my_products'])){
	echo '<title>',$lang['my_p'],'</title>';
	echo '<meta name="description" content="',$lang['my_p'],'" />';
	echo '<meta name="keywords" content="',$lang['my_p'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['my_p'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['my_p'],'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}
	elseif(isset($_GET['messages'])){
	echo '<title>',$lang['yr_msgs'],'</title>';
	echo '<meta name="description" content="',$lang['yr_msgs'],'" />';
	echo '<meta name="keywords" content="',$lang['yr_msgs'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['yr_msgs'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['yr_msgs'],'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';
	}
	else{
	echo '<title>',$lang['meta_home']['title'],'</title>';
	echo '<meta name="description" content="',$lang['meta_home']['description'],'" />';
	echo '<meta name="keywords" content="',$lang['meta_home']['keywords'],'" />';
	echo '<meta itemprop="image" content="',WebSite,'/images/site_screen.jpg">';
	echo '<meta property="og:title" content="',$lang['meta_home']['title'],'" />';
	echo '<meta property="og:type" content="article" />';
	echo '<meta property="og:url" content="',WebSite,'" />';
	echo '<meta property="og:image" content="',WebSite.'/images/site_screen.jpg" />';
	echo '<meta property="og:site_name" content="',ShortDomainName,'" />';
	echo '<meta property="og:description" content="',$lang['meta_home']['description'],'" />';
	echo '<meta name="robots" content="index, follow" />';
	echo '<meta name="revisit-after" content="2 days" /> ';	
	}
}

function echo_single_order(){
	global $lang;

$query='select o.id as oid, qty, o.price, options, delivery_address, DATE_FORMAT(order_date, "%d/%m/%Y %H:%i") as ord_date, order_status, order_secret, category_url, product_url, title, supplier_name, company_phones, p.cid as sup_id, c.customer_names, o.cid as cust_id, c.customer_phone, o.order_comments, cnames, cphone    
from orders o, products p, adverts a, customers c where o.order_secret = "'.mysql_real_escape_string($_GET['order']).'" and 
(o.cid=c.id or o.cid = 0) and o.pid=p.id and o.aid=a.id group by o.id order by order_date ASC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);
if($num_rows > 0){
$row=mysql_fetch_assoc($result);
if($row['sup_id'] == $_SESSION['customer'] || $row['cust_id'] == $_SESSION['customer']){
//echo $row['cust_id'],'tuk',$row['sup_id'];


if($row['order_status'] == 'not_active'){
$upd = 'update orders set order_status = "pending" where id = "'.mysql_real_escape_string($row['oid']).'"';
$updresult=mysql_query($upd) or die(send_error($upd,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$row['order_status'] = 'pending';
}


if($row['cust_id'] > 0){
$cnames = $row['customer_names'];
$cphone = $row['customer_phone'];
}
else{
$cnames = $row['cnames'];
$cphone = $row['cphone'];
}


$disabled = '';
if($row['order_status'] == 'finished' || $row['order_status'] == 'canceled') $disabled = 'disabled';

$option = '';
if(!empty($row['options'])){
$options = unserialize($row['options']);
if(is_array($options) && count($options) > 0){
foreach($options as $okey=>$oval){
$option.='<div class="db w100"><label>'.$okey.': </label>
<input type="text" value="'.$oval.'" readonly class="form-control" />
</div>';
		}
	}
}

echo '
<div class="card mb-0 overflow-hidden">
<div class="card-header">
	<h3 class="card-title">',$lang['ordern'],' ',$row['oid'],'</h3>
</div>
<div class="card-body">
<div class="ib w100 orange tac mt-3 mb-3"></div>

<div class="row">
<div class="col-sm-6 col-md-6">
	<div class="form-group">
		<label class="form-label">',$lang['cnames'],'</label>
		<input type="text" value="',$cnames,'" readonly class="form-control" />
	</div>
</div>

<div class="col-sm-6 col-md-6">
<div class="form-group">
	<label class="form-label">',$lang['phone'],'</label>
	<input type="text" value="',$cphone,'" readonly class="form-control" />
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="form-group">
	<label class="form-label">',$lang['product'],'</label>
	<input type="text" value="',$row['title'],'" readonly class="form-control" />
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6 col-md-6">
	<div class="form-group">
		<label class="form-label">',$lang['supplier_data'],'</label>
		<input type="text" value="',$row['supplier_name'],'" readonly class="form-control" />
	</div>
</div>

<div class="col-sm-6 col-md-6">
<div class="form-group">
	<label class="form-label">',$lang['phone'],'</label>
	<input type="text" value="',$row['company_phones'],'" readonly class="form-control" />
</div>
</div>
</div>

<div class="row">
<div class="col-sm-3 col-md-3">
	<div class="form-group">
		<label class="form-label">',$lang['qty'],'</label>
		<input type="text" value="',$row['qty'],'" readonly class="form-control" />
	</div>
</div>

<div class="col-sm-3 col-md-3">
<div class="form-group">
	<label class="form-label">',$lang['prd_price'],'</label>
	<input type="text" value="',$row['price'],' ',current_currency,'" readonly class="form-control" />
</div>
</div>

<div class="col-sm-3 col-md-3">
<div class="form-group">
	<label class="form-label">',$lang['selected_option'],'</label>
	',$option,'
</div>
</div>

<div class="col-sm-3 col-md-3">
<div class="form-group">
	<label class="form-label">',$lang['total_sh'],'</label>
	<input type="text" value="',number_format(($row['qty']*$row['price']),2),' ',current_currency,'" readonly class="form-control" />
</div>
</div>

</div>

<div class="row">
<div class="col-md-12">
<div class="form-group">
	<label class="form-label">',$lang['delivery_address'],'</label>
	<input type="text" value="',$row['delivery_address'],'" readonly class="form-control" />
</div>
</div>
</div>



<div class="row mt-3 mb-3">
<div class="col-md-12">
<div class="form-group">
<label class="form-label">',$lang['ord_com'],'</label>
<textarea readonly class="form-control" name="comments" id="comments" value="" rows="6">',$row['order_comments'],'</textarea>
</div>
</div>
</div>


<div class="row">
<div class="col-sm-6 col-md-6">
<div class="form-group">
	<label class="form-label">',$lang['order_status'],'</label>';
	
	echo '<select id="change_status" ',$disabled,' name="ord_status" class="form-control select2-no-search border-bottom-0 w-100 select2-hidden-accessible">';
	foreach($lang['statuses_act'] as $key=>$status){
	$selected = '';
	$disabled = '';
	if($row['cust_id'] == $_SESSION['customer']){
	if($key == 'not_active' || $key == 'pending' || $key == 'finished') $disabled = 'disabled';
	}else{
	if($key == 'not_active') $disabled = 'disabled';
	}
	if($key == $row['order_status']) $selected = 'selected="selected"';
	echo '<option ',$disabled,' ',$selected,' value="',$key,'">',$status,'</option>';
	}
	
echo '</select></div>
</div>

<div class="col-sm-6 col-md-6">
<div class="form-group">
	<label class="form-label">',$lang['ord_date'],'</label>
	<input type="text" value="',$row['ord_date'],'" readonly class="form-control" />
</div>
</div>
</div>
</div>
</div>';
		}

	}
}


function echo_single_message($msgrow){
global $lang;

//echo $msgrow['id'];

$about = $msgrow['subject'];
$link = '';
if($msgrow['about_pid'] > 0){
$pdata = get_product_name($msgrow['about_pid']);
//var_dump($pdata);
$link = '<a href="'.WebSite.'/products/'.$pdata['curl'].'/'.$pdata['purl'].'" target="_blank">'.$lang['view_pr'].'</a>';
$about = $pdata['name'];
}

if($msgrow['about_pid'] < 1 && $msgrow['about_aid'] > 0){
$adata = get_advert_details($msgrow['about_aid']);
//var_dump($adata);
$about = $adata['name'];
}

echo '
<div class="card mb-0 overflow-hidden">
<div class="card-header">
	<h3 class="card-title">',$lang['m'],' # ',$msgrow['id'],' / ',date('d-m-Y H:i',strtotime($msgrow['added_date'])),'</h3>
</div>
<div class="card-body">
<div class="ib w100 orange tac mt-3 mb-3"></div>

<div class="row">
<div class="col-sm-6 col-md-6">
	<div class="form-group">
		<label class="form-label">',$lang['cnames'],'</label>
		<input type="text" value="',$msgrow['customer_names'],'" readonly class="form-control" />
	</div>
</div>

<div class="col-sm-6 col-md-6">
<div class="form-group">
	<label class="form-label">',$lang['phone'],'</label>
	<input type="text" value="',$msgrow['cphone'],'" readonly class="form-control" />
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6 col-md-6">
	<div class="form-group">
		<label class="form-label">',$lang['email'],'</label>
		<input type="text" value="',$msgrow['cmail'],'" readonly class="form-control" />
	</div>
</div>

<div class="col-sm-6 col-md-6">
<div class="form-group">
	<label class="form-label">',$lang['date'],'</label>
	<input type="text" value="',date('d-m-Y H:i',strtotime($msgrow['added_date'])),'" readonly class="form-control" />
</div>
</div>
</div>

<div class="row">
<div class="col-sm-12 col-md-12">
	<div class="form-group">
		<label class="form-label">',$lang['subject'],' '.$link.'</label>
		<input type="text" value="',$about,'" readonly class="form-control" />
	</div>
</div>
</div>

<div class="row mt-3 mb-3">
<div class="col-md-12">
<div class="form-group">
<label class="form-label">',$lang['m_txt'],'</label><br/>
',htmlspecialchars_decode($msgrow['mail_text']),'
</div>
</div>
</div>

</div>
</div>
';
}


function echo_my_messages(){
global $lang;

$query='select subject, m.customer_names msgcustname, m.secret, readed, m.about_pid, m.added_date, supplier_name, company_phones, c.customer_names  
from messages m, adverts a, customers c where 
recipient_id = "'.mysql_real_escape_string($_SESSION['customer']).'" and about_aid = a.id and a.customer_id = recipient_id group by m.id order by readed ASC, m.added_date DESC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);

echo '
<div class="card mb-0 overflow-hidden">
<div class="card-header">
	<h3 class="card-title">',$lang['yr_msgs'],'</h3>
</div>
<div class="ads-tabs mt-4">
<div class="tabs-menus ml-4 mb-2">
	<ul class="nav panel-tabs">
		<li class=""><a id="all_ords" class="active all_a" data-toggle="tab">',$lang['all_ords'],' (<span>',$num_rows,'</span>)</a></li>
		<li><a id="pending" class="all_a badge-danger">',$lang['readed_arr'][0],' (<span></span>)</a></li>
		<li><a id="finished" class="all_a badge-success">',$lang['readed_arr'][1],' (<span></span>)</a></li>
	</ul>
</div>
<div class="card-body">
<div class="table-responsive border-top dragscroll">
<table class="table table-bordered table-hover text-nowrap">
<thead>
<tr>
<th>#</th>
<th class="maxw200 overflow-hidden">',$lang['subject'],'</th>
<th>',$lang['from'],'</th>
<th>',$lang['date'],'</th>
<th>',$lang['status'],'</th>
<th>',$lang['act'],'</th>
</tr>
</thead>
<tbody>';
if($num_rows > 0){
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);

$about = $row['subject'];
if($row['about_pid'] > 0){
$pdata = get_product_name($row['about_pid']);
//var_dump($pdata);
$about = '<a href="'.WebSite.'/products/'.$pdata['curl'].'/'.$pdata['purl'].'" target="_blank">'.$pdata['name'].'</a>';
}

if($row['readed'] < 1){
$status = '<span href="#" class="badge badge-danger">'.$lang['readed_arr'][$row['readed']].'</span>';
$class = 'pending';
}else{
$status = '<span href="#" class="badge badge-success">'.$lang['readed_arr'][$row['readed']].'</span>';
$class = 'finished';
}


echo '<tr class="',$class,' orders">
<td>',($i+1),'</td>
<td class="overflow-hidden">',$about,'</td>
<td class="tac">',$row['msgcustname'],'</td>
<td class="tac">',date('d-m-Y H:i',strtotime($row['added_date'])),'</td>
<td class="font-weight-semibold">',$status,'</td>
<td class="tac"><a class="btn btn-info" href="./mydash.php?messages=show&msg=',$row['secret'],'" target="_blank">',$lang['view'],'</a></td>
</tr>';
	}
}else echo '<tr><td class="tac" colspan="6">',$lang['no_msgs'],'</td></tr>';	
echo '</tbody>
	</table>
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
</div>';
}


function echo_fast_orders(){
global $lang;
$query='select o.id as oid, delivery_address, p.price, DATE_FORMAT(order_date, "%d/%m/%Y %H:%i"),cnames, cphone, order_status, order_secret, category_url, product_url, title, supplier_name, company_phones, p.cid   
from fast_orders o, products p, adverts a where 
o.pid=p.id and o.aid=a.id group by o.id order by order_date DESC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);

$return = array();
$html = '';
$total = 0;
if($num_rows > 0){
$html.='<tr><th colspan="8" class="tac bg-light">'.$lang['fo'].'</th></tr>';	

for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_assoc($result);

$total+= number_format((1 * $row['price']),2);

if($row['order_status'] == 'canceled'){
$status = '<span href="#" class="badge badge-danger">'.$lang['statuses'][$row['order_status']].'</span>';
$class = 'badge-danger';
}
if($row['order_status'] == 'not_active'){
$status = '<span href="#" class="badge badge-secondary">'.$lang['statuses'][$row['order_status']].'</span>';
$class = 'badge-secondary';
}
if($row['order_status'] == 'finished'){
$status = '<span href="#" class="badge badge-success">'.$lang['statuses'][$row['order_status']].'</span>';
$class = 'badge-success';
}
if($row['order_status'] == 'pending'){
$status = '<span href="#" class="badge badge-secondary">'.$lang['statuses'][$row['order_status']].'</span>';
$class = 'badge-secondary';
}

$html.= '<tr class="'.$row['order_status'].' orders fo">
<td class="text-secondary">'.$row['oid'].'</td>
<td class="maxw200 overflow-hidden"><a target="_blank" href="'.WebSite.'/products/'.$row['category_url'].'/'.$row['product_url'].'">'.$row['title'].'</a><span class="db w100">'.$row['cnames'].' - '.$row['cphone'].'</span></td>
<td class="tac">'.$lang['clar'].'</td>
<td class="tac">'.$row['price'].' '.current_currency.'</td>
<td>'.$lang['clar'].'</td>
<td class="font-weight-semibold"><span class="'.$row['order_status'].'_p all_p">'.number_format((1 * $row['price']),2).'</span> '.current_currency.'</td>
<td>'.$status.'</td>
<td><a class="btn '.$class.' small-btn" href="./mydash.php?my_orders=show&order='.$row['order_secret'].'">'.$lang['view'].'</a></td>

</tr>';

		}
$html.= '<tr><td colspan="5" class="tar">'.$lang['tfo'].'</td><td><span id="total">'.$total.'</span> '.current_currency.'</td><td></td><td></td></tr>';	
	}
	$return[0] = $html;
	$return[1] = $num_rows;
	//return $return;
}


function echo_my_orders(){
	global $lang;

$query='select o.id, qty, o.price, options, delivery_address, DATE_FORMAT(order_date, "%d/%m/%Y %H:%i"), order_status, order_secret, category_url, product_url, title, supplier_name, company_phones, p.cid, c.customer_names, o.cid, cnames, cphone   
from orders o, products p, adverts a, customers c where 
(o.cid=c.id or o.cid = 0) and o.pid=p.id and o.aid=a.id group by o.id order by order_date DESC';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows = mysql_num_rows($result);

echo '
<div class="card mb-0 overflow-hidden">
<div class="card-header">
	<h3 class="card-title">',$lang['my_orders'],'</h3>
</div>
<div class="ads-tabs mt-4">
<div class="tabs-menus ml-4 mb-2">
	<ul class="nav panel-tabs">
		<li class=""><a id="all_ords" class="active all_a" data-toggle="tab">',$lang['all_ords'],' (<span>',$num_rows,'</span>)</a></li>
		<li><a id="not_active" class="all_a">',$lang['statuses']['not_active'],' (<span></span>)</a></li>
		<li><a id="pending" class="all_a">',$lang['statuses']['pending'],' (<span></span>)</a></li>
		<li><a id="canceled" class="all_a badge-danger">',$lang['statuses']['canceled'],' (<span></span>)</a></li>
		<li><a id="finished" class="all_a badge-success">',$lang['statuses']['finished'],' (<span></span>)</a></li>
	</ul>
</div>
<div class="card-body">
<div class="table-responsive border-top dragscroll">
<table class="table table-bordered table-hover text-nowrap">
<thead>
<tr>
<th>#</th>
<th class="maxw200 overflow-hidden">',$lang['product'],'</th>
<th>',$lang['qty_short'],'</th>
<th>',$lang['prd_price'],'</th>
<th>',$lang['selected_option'],'</th>
<th>',$lang['total_sh'],'</th>
<th>',$lang['status'],'</th>
<th>',$lang['act'],'</th>
</tr>
</thead>
<tbody>';


if($num_rows > 0){

$total = 0;
for($i=0;$i<$num_rows;$i++){
$row=mysql_fetch_row($result);

$option = '';
if(!empty($row[3])){
$options = unserialize($row[3]);
if(is_array($options) && count($options) > 0){
foreach($options as $okey=>$oval){
$option.='<div class="db w100"><label>'.$okey.': </label>'.$oval.'</div>';
		}
	}
}

//select o.id, qty, o.price, options, delivery_address, DATE_FORMAT(order_date, "%d/%m/%Y %H:%i"), order_status, order_secret, category_url, product_url, title, supplier_name, company_phones, p.cid, c.customer_names, o.cid, cnames, cphone   

//echo $row[15];

$fo = 'fo bg-light';
$titlet = $lang['fast_order'].' - '.$lang['clar'];
if($row[15] > 0){
$fo = '';
$titlet = '';
if($row[13] == $_SESSION['customer']) $names = $row[14];
else $names = $row[11];
}else $names = $row[16];

$total+= number_format(($row[1] * $row[2]),2);
if($row[6] == 'canceled'){
$status = '<span href="#" class="badge badge-danger">'.$lang['statuses'][$row[6]].'</span>';
$class = 'badge-danger';
}
if($row[6] == 'not_active'){
$status = '<span href="#" class="badge badge-secondary">'.$lang['statuses'][$row[6]].'</span>';
$class = 'badge-secondary';
}
if($row[6] == 'finished'){
$status = '<span href="#" class="badge badge-success">'.$lang['statuses'][$row[6]].'</span>';
$class = 'badge-success';
}
if($row[6] == 'pending'){
$status = '<span href="#" class="badge badge-secondary">'.$lang['statuses'][$row[6]].'</span>';
$class = 'badge-secondary';
}

echo '<tr title="',$titlet,'" class="',$row[6],' orders ',$fo,'">
<td>',$row[0],'</td>
<td class="maxw200 overflow-hidden"><a target="_blank" href="',WebSite,'/products/',$row[8],'/',$row[9],'">',$row[10],'</a><span class="db w100">',$names,'</span></td>
<td class="tac">',$row[1],'</td>
<td class="tac">',$row[2],' ',current_currency,'</td>
<td>',$option,'</td>
<td class="font-weight-semibold"><span class="',$row[6],'_p all_p">',number_format(($row[1] * $row[2]),2),'</span> ',current_currency,'</td>
<td>',$status,'</td>
<td><a class="btn '.$class.' small-btn" href="./mydash.php?my_orders=show&order=',$row[7],'">',$lang['view'],'</a></td>
</tr>';
	}
	echo '<tr><td colspan="5" class="tar">',$lang['total_sh'],'</td><td><span id="total">',$total,'</span> ',current_currency,'</td><td></td><td></td></tr>';	
}else echo '<tr><td class="tac" colspan="8">',$lang['no_orders'],'</td></tr>';	
echo '</tbody>
	</table>
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
</div>';
}


function echo_my_listings(){
	global $lang;
	
$query='SELECT * FROM adverts WHERE customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'" and deleted = "0" order by added_date desc';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
echo '<div class="col-xl-9 col-lg-12 col-md-12">
<div class="card mb-0 overflow-hidden">
	<div class="card-header">
		<h3 class="card-title">',$lang['my_b'],'</h3>
	</div>
	<div class="card-body">';
if($num_rows > 0){
echo '<div class="ads-tabs">
		<div class="tabs-menus">
			<ul class="nav panel-tabs">
				<li class=""><a id="all_b" class="active" data-toggle="tab">',$lang['all_ads'],' (<span>',$num_rows,'</span>)</a></li>
				<li><a id="active" >',$lang['actives'],' (<span></span>)</a></li>
				<li><a id="pendings" >',$lang['active_status'][0],' (<span></span>)</a></li>
			</ul>
		</div>
		<div class="tab-content">';
	for($i=0;$i<$num_rows;$i++){
	$row = mysql_fetch_assoc($result);
	
	$status = 'pendings';

	$image = 'no_product_image.jpg';
	if(!empty($row['small_image'])) $image = $row['small_image'];
	if(!empty($row['logo'])) $image = $row['logo'];
	
	$active = '<i title="'.$lang['wait_pay_or'].'" class="fa fa-check-circle-o text-danger"></i>';
	if($row['active'] > 0){
	$active ='<i title="'.$lang['active'].'" class="fa fa-check-circle-o text-success"></i>';
	$status = 'active';
	}
	
	
	$categories = '';
	$adv_cats = get_advert_categories($row['id']);
	//var_dump($adv_cats);
	if(count($adv_cats) > 0){
	foreach($adv_cats as $category) $categories.='<div class="ib ml-2 mr-2 mb-2"><a href="#">'.$category.'</a></div> | ';
	}
	
	if(!empty($row['work_days'])){
	$work_days = unserialize($row['work_days']);
	//echo count($work_days);
	if(count($work_days) > 0){
	if(count($work_days) == 7) $workdays=$lang['ev_day'];
	else{
	foreach($work_days as $day) $workdays.=$lang['days_arr'][$day];
			}
		}
	}
	
	
	echo '<div id="aid_',$row['id'],'" data-status="',$status,'" class="card overflow-hidden card-absolute advert">
<div class="edit-buttons-fixed">';
	$count_site_visits = count_adverts_site_visits($row['id']);
	
	$additsv = '';
	if($count_site_visits > 0) $additsv = 'target="_blank" href="site_visits.php?aid='.$row['id'].'"';
	echo '
	<a ',$additsv,' class="btn btn-secondary btn-sm text-white pr" data-toggle="tooltip" data-original-title="',$lang['site_visits'],'"><i class="fa fa-link text-white"></i> (',$count_site_visits,')</a>';
	echo '<a target="_blank" href="profile_visits.php?aid='.$row['id'].'" class="btn btn-primary btn-sm text-white pr" data-toggle="tooltip" data-original-title="',$lang['profile_visits'],'"><i class="fa fa-eye text-white fixedeye"></i> (',count_adverts_visits($row['id']),')</a>
	<a target="_blank" href="call_statistics.php?aid='.$row['id'].'" class="btn btn-info btn-sm text-white" data-toggle="tooltip" data-original-title="',$lang['p_cals'],'"><i class="fa fa-volume-control-phone"></i> (',count_adverts_calls($row['id']),')</a>
	<a href="./redirector.php?edit_ad=',$row['id'],'" class="btn btn-success btn-sm text-white" data-toggle="tooltip" data-original-title="',$lang['edit'],'"><i class="fa fa-pencil"></i></a>
	<a class="btn btn-danger btn-sm text-white del_advert" data-id="',$row['id'],'" data-toggle="tooltip" data-original-title="',$lang['del_it'],'"><i class="fa fa-trash-o"></i></a>
</div>
<div class="d-md-flex">
	<div class="item-card9-img">
		<div class="item-card9-imgs">
			<img src="../images/adverts/',$image,'" alt="',$row['supplier_name'],'" class="cover-image" />
		</div>
	</div>
	<div class="card border-0 mb-0">
		<div class="card-body py-4">
			<div class="item-card9">
				',$categories,'
				<a href="business.html" class="text-dark"><h4 class="font-weight-semibold mt-0">',$row['supplier_name'],' ',$active,'</h4></a>
				<div class="item-card2-desc mt-1">
					<div class="item-card2-desc-cost">
						<h6 class="text-dark font-weight-normal mb-0 mt-0"><i class="fa fa-map-marker mr-1"></i> ',$row['town'],', ',$row['area'],', ',$row['company_address'],'</h6>
						<h6 class="text-dark font-weight-normal mb-0 mt-2"><i class="fa fa-phone mr-1"></i> <a href="#"> ',$row['company_phones'],'</a></h6>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer py-3">
			<div class="row">
				<div class="col">
					<a href="#" class="mt-1 mb-1 mr-1 text-black"><i class="fa fa-clock-o"></i> ',$workdays,' <b>',$row['opening'],'-',$row['closing'],'</b></a>
				</div>';
				if($row['active'] > 0) echo '
				<div class="col col-auto">
				<a href="mydash.php?my_orders=show&aid=',$row['id'],'" class="mt-1 mb-1 mr-2 text-black"><i class="fa fa-bell-o" aria-hidden="true"></i> ',count_advert_orders($row['id']),' ',$lang['ords'],'</a>
					<a target="_blank" href="#" class="mt-1 mb-1 mr-0">',$lang['view'],' <i class="fa fa-arrow-circle-right"></i></a>
				</div>';
			echo '</div>
		</div>
	</div>
</div>
</div>';
		}
		echo '</div></div>';
	}
	echo '
	<div class="db w100 tac mt-4 mb-4"><a class="btn btn-secondary ad-post" href="',WebSite,'/select-plan/"><i class="fa fa-plus text-white"></i> ',$lang['add'],'</a></div>
	</div></div></div>';
}

//var_dump($_POST);
$msg = '';


if(isset($_POST['save_profile'])){
$pattern  = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";
if(empty($_POST['first_name']) || !preg_match($pattern, $_POST['first_name']) == 1) $msg = $lang['wrong_aname'];
if(empty($_POST['second_name']) || !preg_match($pattern, $_POST['second_name']) == 1) $msg = $lang['wrong_aname'];
if(empty($_POST['customer_phone']) || !preg_match($pattern, $_POST['customer_phone']) == 1) $msg = $lang['wrong_phone'];

$_POST['customer_address'] = sanitaze_text(strip_tags($_POST['customer_address']));

//if(empty($_POST['customer_address']) || !preg_match("/^[a-zA-Z\p{Cyrillic}0-9. ,'!()\s\-]+$/u", $_POST['customer_address']) == 1) $msg = $lang['wrong_address'];
if(empty($_POST['city']) || !preg_match("/^[a-zA-Z\p{Cyrillic}\s\-]+$/u", $_POST['city']) == 1) $msg = $lang['wrong_city'];
if(empty($_POST['postcode']) || !preg_match($pattern, $_POST['postcode']) == 1) $msg = $lang['wrong_postcode'];

if (!empty($_POST['facebook']) && (!filter_var($_POST['facebook'], FILTER_VALIDATE_URL) || !checkRootDomain($_POST['facebook'],'facebook.com'))) $msg = $lang['wrong_facebook_link'];
if (!empty($_POST['linkedin']) && (!filter_var($_POST['linkedin'], FILTER_VALIDATE_URL) || !checkRootDomain($_POST['linkedin'],'linkedin.com'))) $msg = $lang['wrong_linkedin_link'];

if(isset($_FILES) && !empty($_FILES['profile_image']['name'])){
//var_dump($_FILES);
list($width, $height, $type, $attr) = getimagesize($_FILES['profile_image']['tmp_name']);
if (!isset($type) || !in_array($type, array(
    IMAGETYPE_PNG, IMAGETYPE_JPEG))){
 $msg = $lang['UNSUPPORTED_FILE_TYPE'].' '.$_FILES["profile_image"]["name"];
		}
	}
	
	if(isset($_POST['delete_picture'])){
	if(file_exists('./images/users/'.$_POST['delete_picture'])) unlink('./images/users/'.$_POST['delete_picture']);
	$imagesq = 'update customers set profile_image = "" where id = "'.mysql_real_escape_string($_SESSION['customer']).'"';
	$imagesqresult=mysql_query($imagesq) or die(send_error($imagesq,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	}
	
	
	if(empty($msg)){
	$update = 'update customers set 
	customer_names = "'.mysql_real_escape_string(trim($_POST['first_name']).' '.trim($_POST['second_name'])).'",
	customer_phone = "'.mysql_real_escape_string(trim(sanitaze_text(strip_tags($_POST['customer_phone'])))).'",
	customer_address = "'.mysql_real_escape_string(trim(sanitaze_text(strip_tags($_POST['customer_address'])))).'",
	city = "'.mysql_real_escape_string(trim($_POST['city'])).'",';
	if(!empty($_POST['password'])) $update.=' password = "'.md5(trim($_POST['password'])).'", realp = "'.mysql_real_escape_string(trim($_POST['password'])).'",';
	
	$update.=' postcode = "'.mysql_real_escape_string(trim($_POST['postcode'])).'",
	facebook = "'.mysql_real_escape_string(trim($_POST['facebook'])).'",
	linkedin = "'.mysql_real_escape_string(trim($_POST['linkedin'])).'",
	about_me = "'.mysql_real_escape_string(trim($_POST['about_me'])).'"';
	if(isset($_FILES) && !empty($_FILES['profile_image']['name'])){
	$imagename = create_url($_POST['first_name'].'-'.$_POST['second_name']).'-'.randomcode().'.jpg';

	$update.= ',profile_image = "'.mysql_real_escape_string($imagename).'"';
	
	copy_and_resize_image_new($_FILES["profile_image"]["tmp_name"],$_FILES["profile_image"]["type"],$imagename,1000,1000,'users');

	}
	
	$update.= ' where id = "'.mysql_real_escape_string($_SESSION['customer']).'"';
	$updateresult=mysql_query($update) or die(send_error($update,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	
	$_SESSION['customer_phone'] = $_POST['customer_phone'];
	$_SESSION['customer_address'] = $_POST['customer_address'];
	
	header('Location: '.WebSite.'/mydash.php?profile=show&msg=success');
	exit(0);
	}
}

$query = 'select * from customers where id = "'.mysql_real_escape_string($_SESSION['customer']).'"';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
if($num_rows > 0){

if(empty($msg) && !isset($_POST['save_profile'])){
$row=mysql_fetch_assoc($result);
$names = explode(' ',$row['customer_names']);
$_POST['first_name'] = $names[0];
$_POST['second_name'] = $names[1];
$_POST['customer_email'] = $row['customer_email'];
$_POST['customer_phone'] = $row['customer_phone'];
$_POST['customer_address'] = $row['customer_address'];
$_POST['city'] = $row['city'];
$_POST['postcode'] = $row['postcode'];
$_POST['profile_image'] = $row['profile_image'];

$_POST['about_me'] = $row['about_me'];
$_POST['facebook'] = $row['facebook'];
$_POST['linkedin'] = $row['linkedin'];

if(isset($_GET['msg'])) $msg = $lang['suc_save'];

}

?>
<!doctype html>
<html lang="en" dir="ltr">

	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<?php echo echo_meta();?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="author" content="Web-site-maker.eu"/>
		
		<link rel="icon" type="image/png" href="<?php echo WebSite;?>/favicon.ico" />

		<link href="./assets/css/style.css" rel="stylesheet" />
		<link href="./assets/iconfonts/typicons/typicons.css" rel="stylesheet" />

	</head>
	<body>
<?php
include './modules/header.php';
?>


		<!--Breadcrumb-->
<section>
	<div class="bannerimg cover-image bg-background3" data-image-src="../../assets/images/banners/banner2.jpg">
		<div class="header-text mb-0">
			<div class="container">
				<div class="text-center text-white">
					<h1 class=""><?php echo $lang['dashb'];?></h1>
					<ol class="breadcrumb text-center">
						<li class="breadcrumb-item"><a href="./index.php"><?php echo $lang['home'];?></a></li>
						<li class="breadcrumb-item active text-white" aria-current="page"><?php echo $lang['dashb'];?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
		<!--Breadcrumb-->

		<!--User Dashboard-->
		<section class="sptb">
			<div class="container">
				<div class="row">
					<div class="col-xl-3 col-lg-12 col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['dashb'];?></h3>
							</div>
							<div class="card-body text-center item-user border-bottom">
								<div class="profile-pic">
									<div class="profile-pic-img">
									<?php
									$profile_image = '25.jpg';
									if(!empty($row['profile_image'])) $profile_image = $row['profile_image'];
									?>
										<span class="bg-success dots" data-toggle="tooltip" data-placement="top" title="online"></span>
										<img src="./images/users/<?php echo $profile_image;?>" class="brround" alt="<?php echo $row['customer_names'];?>" />
									</div>
									<h4 class="mt-3 mb-0 font-weight-semibold"><?php echo $row['customer_names'];?></h4>
								</div>
							</div>
							<div class="item1-links mb-0">
								<a href="./mydash.php?profile=show" class="<?php if(empty($_GET) || $_GET['profile'] == 'show') echo 'active';?> d-flex border-bottom">
									<span class="icon1 mr-2"><i class="typcn typcn-edit fs-20"></i></span> <?php echo $lang['p_edit'];?>
								</a>
								<a href="./mydash.php?my_listings=show" class="<?php if(isset($_GET['my_listings']) || $_GET['my_listings'] == 'show') echo 'active';?> d-flex border-bottom">
									<span class="icon1 mr-2"><i class="typcn typcn-briefcase fs-20"></i></span> <?php echo $lang['my_b'];?>
								</a>
								
								<a href="./mydash.php?my_products=show" class="<?php if(isset($_GET['my_products']) || $_GET['my_products'] == 'show') echo 'active';?> d-flex border-bottom">
									<span class="icon1 mr-2"><i class="typcn typcn-folder fs-20"></i></span> <?php echo $lang['my_p'];?>
								</a>
								
								
								<a href="./mydash.php?messages=show" class="<?php if(isset($_GET['messages']) || $_GET['messages'] == 'show') echo 'active';?> d-flex border-bottom">
									<span class="icon1 mr-2"><i class="fa fa-envelope-o"></i></span> <?php echo $lang['msgs'],' ',$newmsg;?>
								</a>
								
								<a href="./cart/" class="d-flex border-bottom">
									<span class="icon1 mr-2"><i class="fa fa-shopping-basket"></i></span> <?php echo $lang['u_bas'];?>
								</a>
								<a href="./logout.php" class="d-flex">
									<span class="icon1 mr-2"><i class="typcn typcn-power-outline fs-20"></i></span> <?php echo $lang['logout'];?>
								</a>
							</div>
						</div>
						
						<div class="card mb-xl-0">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['suc_sales'];?></h3>
							</div>
							<div class="card-body p-0">
								<ul class="list-unstyled widget-spec  mb-0">
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> <?php echo $lang['reg_f'];?>
									</li>
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> <?php echo $lang['add_offer'];?>
									</li>
									<li class="">
										<i class="fa fa-check text-success" aria-hidden="true"></i> <?php echo $lang['sale_on'];?>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<?php
					if(empty($_GET) || $_GET['profile'] == 'show'){
					?>
					<div class="col-xl-9 col-lg-12 col-md-12">
						<form id="profileform" action="" name="productform" method="post" enctype="multipart/form-data" class="form-horizontal mb-0">
						<div class="card mb-0 overflow-hidden">
							<div class="card-header">
								<h3 class="card-title"><?php echo $lang['p_edit'];?></h3>
							</div>
							<div class="card-body">
							<div class="ib w100 orange tac mt-3 mb-3"><?php echo $msg;?></div>
								<div class="row">
									<div class="col-sm-6 col-md-6">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['name'];?></label>
											<input type="text" value="<?php echo $_POST['first_name'];?>" name="first_name" class="form-control" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									<div class="col-sm-6 col-md-6">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['fname'];?></label>
											<input type="text" value="<?php echo $_POST['second_name'];?>" name="second_name" class="form-control" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									<div class="col-sm-6 col-md-6">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['email'];?></label>
											<input type="email" readonly value="<?php echo $_POST['customer_email'];?>" name="customer_email" class="form-control" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									
									<div class="col-sm-6 col-md-6">
										<div class="form-group pr pass">
											<label class="form-label"><?php echo $lang['password'];?></label>
											<input autocomplete="off" id="login_password" class="form-control" value="" type="password" name="password"/>
											<i class="fa fa-eye" data-id="login_password" aria-hidden="true"></i>
											</div>
									</div>
									
									
									<div class="col-sm-6 col-md-6">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['phone'];?></label>
											<input onkeypress="return(numberFormat(event));" type="text" value="<?php echo $_POST['customer_phone'];?>" name="customer_phone" class="form-control <?php if(empty($_POST['customer_phone'])) echo 'wrong';?>" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									<div class="col-sm-6 col-md-6">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['delivery_address'];?></label>
											<input type="text" value="<?php echo $_POST['customer_address'];?>" name="customer_address" class="form-control <?php if(empty($_POST['customer_address'])) echo 'wrong';?>" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									<div class="col-sm-6 col-md-4">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['city'];?></label>
											<input type="text" value="<?php echo $_POST['city'];?>" name="city" class="form-control <?php if(empty($_POST['city'])) echo 'wrong';?>" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['postcode'];?></label>
											<input type="text" value="<?php echo $_POST['postcode'];?>" name="postcode" class="form-control <?php if(empty($_POST['postcode'])) echo 'wrong';?>" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['country'];?></label>
											<select disabled class="form-control select2-show-search border-bottom-0 w-100" data-placeholder="<?php echo $lang['please_select'];?>">
												<optgroup label="country">
													<option><?php echo $lang['please_select'];?></option>
													<option selected value="1">Bulgaria</option>
												</optgroup>
											</select>
										</div>
									</div>
									<div class="col-sm-6 col-md-6">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['facebook_link'];?></label>
											<input type="text" value="<?php echo $_POST['facebook'];?>" name="facebook" class="form-control" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									
									<div class="col-sm-6 col-md-6">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['linkedin_link'];?></label>
											<input type="text" value="<?php echo $_POST['linkedin'];?>" name="linkedin" class="form-control" placeholder="<?php echo $lang['enter'];?>" />
										</div>
									</div>
									
									<div class="col-md-12">
										<div class="form-group">
											<label class="form-label"><?php echo $lang['ab_me'];?></label>
											<textarea rows="5" value="<?php echo $_POST['about_me'];?>" name="about_me" class="form-control" placeholder="<?php echo $lang['enter'];?>"><?php echo $_POST['about_me'];?></textarea>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group mb-0">
											<label class="form-label"><?php echo $lang['upload_image'];?></label>
											<div class="custom-file">
												<input type="file" class="custom-file-input" name="profile_image" accept=".jpg, .png, image/jpeg, image/png" />
												<label class="custom-file-label"><?php echo $lang['sel_file'];?></label>
											</div>
										</div>
									</div>
<?php
if(!empty($_POST['profile_image'])){
?>
									<div class="col-lg-12">
									<div class="checkbox checkbox-info">
										<label class="custom-control mt-4 custom-checkbox">
											<input type="checkbox" id="delete_picture" name="delete_picture" value="<?php echo $_POST['profile_image'];?>" class="custom-control-input" />
											<span id="termslbl" class="custom-control-label text-dark pl-2"><?php echo $lang['delete_picture']?></span>
										</label>
									</div>
								</div>
<?php
}
?>
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" name="save_profile" class="btn btn-secondary"><?php echo $lang['save'];?></button>
							</div>
						</div>
						</form>
					</div>
					<?php
					}
					
					if(isset($_GET['my_listings']) || $_GET['my_listings'] == 'show'){
					echo_my_listings();
					
					echo '<div class="modal fade" data-backdrop="static" data-keyboard="false" id="deletediv" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
						<h5 class="modal-title ib w100">',$lang['pl_con'],'</h5><hr/>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
						<span class="ib w100 tac formsg" >',$lang['are_you_s2'],'</span>
					</div>
					<div class="modal-body tac">
						<button type="button" class="btn btn-danger" data-dismiss="modal">',$lang['canc'],'</button>
						<button type="button" id="confirm_del" data-id="" class="btn btn-success ml-3">',$lang['confirm'],'</button>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>';
					}
					
if(isset($_GET['my_products']) || $_GET['my_products'] == 'show'){
$aquery='SELECT id, supplier_name, paid, active FROM adverts WHERE customer_id = "'.mysql_real_escape_string($_SESSION['customer']).'" and deleted = "0" order by added_date desc';
$aresult=mysql_query($aquery) or die(send_error($aquery,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$a_num_rows=mysql_num_rows($aresult);

echo '<div id="advertForm" class="col-xl-9 col-lg-12 col-md-12">';
if($a_num_rows > 0){
for($i=0;$i<$a_num_rows;$i++){
		$arow=mysql_fetch_assoc($aresult);
		echo echo_adverts_products($arow['id'],$arow['paid'],$arow['active'],1,$arow['supplier_name']);
		}
		echo '<div class="modal fade" data-backdrop="static" data-keyboard="false" id="deletediv" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
						<h5 class="modal-title ib w100">',$lang['pl_con'],'</h5><hr/>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
						<span class="ib w100 tac formsg" >',$lang['are_you_s'],'</span>
					</div>
					<div class="modal-body tac">
						<button type="button" class="btn btn-danger" data-dismiss="modal">',$lang['canc'],'</button>
						<button type="button" id="confirm_del2" data-id="" class="btn btn-success ml-3">',$lang['confirm'],'</button>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>';
	}else echo '
<div class="card mb-0 overflow-hidden">
	<div class="card-header">
		<h3 class="card-title">',$lang['my_p'],'</h3>
	</div>
	<div class="card-body">',$lang['no_bu'],'
	<div class="db w100 tac mt-4 mb-4"><a class="btn btn-secondary ad-post" href="',WebSite,'/select-plan/"><i class="fa fa-plus text-white"></i> ',$lang['add'],'</a></div>
	</div>
	</div>
	';
	echo '</div>';
}

if(isset($_GET['my_orders']) || $_GET['my_orders'] == 'show'){
echo '<div class="col-xl-9 col-lg-12 col-md-12">';
if(!isset($_GET['order'])) echo_my_orders();
else{
if(strlen($_GET['order']) == 32){
 echo_single_order();
 echo '<div class="modal fade" data-backdrop="static" data-keyboard="false" id="statusdiv" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
 <form id="statusform" action="" name="productform" method="get" enctype="multipart/form-data" class="form-horizontal mb-0">
				<div class="modal-header">
						<h5 class="modal-title ib w100">',$lang['pl_con'],'</h5><hr/>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
						<span class="ib w100 tac formsg" >',$lang['are_you_status'],'</span>
					</div>
					<div class="modal-body tac">
						<button type="button" class="btn btn-danger" data-dismiss="modal">',$lang['canc'],'</button>
						<button type="button" id="change_status_btn" data-id="',$_GET['order'],'" data-status="" class="btn btn-success ml-3">',$lang['confirm'],'</button>
					</div>
					<div class="modal-footer">
					</div>
			</form>
				</div>
			</div>
		</div>';
 }
else{
echo '<div class="card mb-0 overflow-hidden"><div class="card-header"><h3 class="card-title">',$lang['no_results'],'</h3></div></div>';
	}
 }
echo '</div>';

}

if(isset($_GET['messages']) || $_GET['messages'] == 'show'){
echo '<div class="col-xl-9 col-lg-12 col-md-12">';
if(!isset($_GET['msg'])) echo_my_messages();
else echo_single_message($msgrow);
echo '</div>';
}
?>
				</div>
			</div>
		</section>

<?php include './modules/footer.php';?>		
<script src="./assets/js/<?php echo $_SESSION['lang'].'_lang';?>.js"></script>
<script src="./assets/js/fullfunctions.js"></script>
<script src="./assets/js/jquery-ui.js"></script>
<script src="./assets/js/profile.js"></script>

	</body>
</html>
<?php
}else{
header('Location: '.WebSite);
exit(0);
}
}else{
header('Location: '.WebSite);
exit(0);
}
?>