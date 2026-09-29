<?php
session_start();
require '../config.php';
require '../connect.php';
?>
<html>
<head>
<link type="text/css" rel="stylesheet" href="<?php echo WebSite;?>/css/linebanner.css" />
</head>
<body>
<?php
$query='SELECT p.title,p.description,p.price,p.image, a.link_name, pc.url,a.supplier_name FROM products p,adverts a, products_categories pc  
WHERE p.advert_id=a.id and a.active="1" and p.category_id=pc.id group by p.advert_id order by rand() limit 5';
$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
$num_rows=mysql_num_rows($result);
$htm='';
if($num_rows > 0){
$htm.= '<div class="eslideshowholder">';
	for($i=0;$i<$num_rows;$i++){
	$row=mysql_fetch_row($result);
		
	if(empty($row[3])){
	$image = 'no_product_image.jpg';
	}else $image = $row[3];
	$htm.= '<div><h3>'.$row[0].'</h3>
	<a title="Топ оферта от '.$row[6].' за '.$row[0].'" target="_blank" href="http://www.espravki.com/business-directory/'.$row[5].'/'.$row[4].'">
	<span class="imgspan"><img src="http://www.espravki.com/images/products/'.$image.'" alt="'.$row[0].'" /></span>
	<span class="bannershop">'.$row[0].'</span>
	<span class="newprice">Цена '.$row[2].'</span>
	</a></div>';
		}
	$htm.='</div>';
	}
	echo $htm;

?>
</body>
</html>