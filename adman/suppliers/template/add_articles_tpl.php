<?php
if(!empty($article)) $_POST['articles']=$article;
if(!empty($_POST['articles'])) $article=$_POST['articles'];

if(isset($_SESSION['adman'])){
?>
<script>
function check_form(){
var isFormValid = $(".mustfill").removeClass("highlight").filter(function() {
        return $.trim(this.value) == "";
    }).addClass("highlight").length == 0;
if (!isFormValid) {
	alert("<?php echo $lang['enter_fields'];?>");
return false;
}
	document.form1.submit();
    return isFormValid;
}
</script>
<?php
function articles_to_categories($article){
	$html='<option value="0">Моля изберете категория</option>';
	$q='select product_category_id from articles_to_products_categories where article_id="'.$article.'"';
	$res=mysql_query($q) or die(mysql_error());
	$rw=mysql_fetch_row($res);

	$query='SELECT pc.id,bg_category FROM products_categories pc,adverts_to_product_categories atpc WHERE visible="1" and
	atpc.category_id=pc.id group by pc.id';
	$result=mysql_query($query) or die(mysql_error());
	$num_rows=mysql_num_rows($result);
	for($j=0;$j<$num_rows;$j++){
	$selected='';
	$row=mysql_fetch_row($result);
	if($rw[0]==$row[0]) $selected='selected="selected"';
	$html.='<option '.$selected.' value="'.$row[0].'">'.$row[1].'</option>';
	}			
	return $html;
}
if(empty($_SESSION['back'])){
$_SESSION['back']=$_SERVER['HTTP_REFERER'];
}
//var_dump($_SESSION);
function getImages($aid){
	global $lang;
	if(!empty($aid)){
		$query='select small_image from articles where id="'.$aid.'"';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_rows=mysql_num_rows($result);
		$row=mysql_fetch_row($result);
	echo '<div>',$lang['picture'],'</div>';
	if(!empty($row[0])){ echo '<td align="center" colspan="2"><input type="checkbox" name="dimage" id="'.$aid.'" value="'.$aid.'"><label for="'.$aid.'">'.$lang['delete_picture'].'</label>
	<br /><img src="../images/articles/'.$row[0].'" height="110"></td>';
	}else{
		echo '<td colspan="2" align="center">
		<div>',$lang['picture1'],'</div><img src="'.WebSite.'/adman/suppliers/images/no_product_image.jpg" />
		<div><input type="file" size="14" name="gallery_upload1"><br>600x600px</div></td>';
		}
	}
	else{
	$query='select small_image from articles where id="'.$aid.'"';
	echo '<td colspan="2" align="center">
	<div>',$lang['picture1'],'</div><img src="'.WebSite.'/adman/suppliers/images/no_product_image.jpg" />
	<div><input type="file" size="14" name="gallery_upload1"><br>600x600px</div></td>';	
	}
}


if(!empty($_GET['article']) && $_GET['article']!="0") $article=$_GET['article'];

if(!empty($article)){
	$query='select * from articles where id="'.mysql_real_escape_string($article).'"';
	$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	$row=mysql_fetch_assoc($result);
	$_POST['article']=$row['id'];
	$_POST['unique_name']=$row['unique_name'];
	$_POST['bg_article_title']=$row['bg_article_title'];
	$_POST['category']=$row['category_id'];
	$_POST['bg_short_description']=$row['bg_short_description'];
	$_POST['bg_meta_description']=$row['bg_meta_description'];
	$_POST['bg_tags']=$row['bg_tags'];
	$_POST['added_date']=$row['added_date'];
	$_POST['top_article']=$row['top_article'];
}
elseif(!empty($_POST['article'])) $article=$_POST['article'];

if(!empty($_POST['top_article'])) $as_top="checked";else $as_top="";

if($message!='') echo '<center><div style="color:#ff0000;margin-top:10px;">'.$message.'</div></center>';
//var_dump($_POST);
?>

<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="POST" name="form1" enctype="multipart/form-data" class="p_form">
<input type="hidden" id="aid" name="article" value="<?php echo $_POST['article'];?>" />
<input type="hidden" name="unique_name" value="<?php echo translate(trim($_POST['bg_article_title'],$tr));?>" />
<input type="hidden" id="show_in_site" name="show_in_site" value="1" />

<div width="100%" border="0" cellspacing="0" cellpadding="2" class="p">
<div class="htabs" id="tabs">
<span id="ntab1"><a class="fragment" id="f1"><img title="Български" src="../images/flags/bg.png" /> Главна</a></span>
<span id="ntab2"><a class="fragment" id="f2">Продукти към статията</a></span>

</div>

<div id="fragment1" class="hideme">
<table class="tabtable">
<tbody>
<tr><td><span class="required">*</span><span class="label wa">Заглавие</span></td>
<td><input class="mustfill ib w100" type="text" name="bg_article_title" value="<?php echo @$_POST['bg_article_title']?>" />
</td></tr>
<tr><td><span class="required">*</span><span class="label wa">Дата добавяне</span></td>
<td><input class="mustfill ib w100" type="text" name="added_date" value="<?php echo @$_POST['added_date']?>" />
</td></tr>
<tr><td><span class="label wa">Да се показва КАТО НОВИНА на първа страница</span></td>
<td><input style="float:left;margin:5px;" type="checkbox" <?php echo $as_top;?> name="top_article" value="top_article" />
</td></tr>
<tr><td><span class="required">*</span><span class="label wa">Описание</span></td>
<td>
<textarea id="bg_short_description" name="bg_short_description" value="<?php echo @$_POST['bg_short_description'];?>" ><?php echo htmlspecialchars_decode(@$_POST['bg_short_description']);?></textarea></td></tr>
</td></tr>
<tr><td><span class="required">*</span><span class="label wa">Мета Описание</span></td>
<td><textarea class="meta ib w100" id="bg_meta_description" name="bg_meta_description" value="<?php echo htmlspecialchars_decode(@$_POST['bg_meta_description']);?>" ><?php echo @$_POST['bg_meta_description'];?></textarea></td></tr>
<tr><td colspan="2"><div class="separator"></div></td></tr>
<tr><td><span class="required">*</span><span class="label wa">Категория на статията</span></td>
<td>
<select class="form-control" name="category">
<option value="0">Моля изберете</option>
<?php
foreach($lang['artcle_categories'] as $key=>$value){
$selected = '';
if($key == $_POST['category']) $selected = 'selected="selected"';
echo '<option ',$selected,' value="',$key,'">',$value,'</option>';
}
?>
</select>
</td></tr>

<tr><td colspan="2"><div class="separator"></div></td></tr>
<tr><td>
<span class="label wa">Тагове, разделяй със запетая ,</span></td>
<td><textarea class="meta" name="bg_tags"><?php echo @$_POST['bg_tags'];?></textarea></td></tr>
<tr><?php getImages($article);?></tr>
<tr><td colspan="2"><div class="separator"></div></td></tr>
</tbody></table></div>

<div id="fragment2" class="hideme">
<h2 class="ntab"><span class="required">*</span>Продукти към статията</h2>
<div style="width: 600px;margin:10px auto;text-align:center;">
<select class="form-control" name="article_to_products" class="categories">
<?php echo articles_to_categories($article); ?>
</select>
</div>
</div>
</div>

<div align="center"><input type="hidden" name="btn" id="btn" value="save" />
<?php if(!empty($_SESSION['back'])){ ?>
<button type="button" class="cancel" onclick="window.location='<?php echo $_SESSION['back'];?>';">Затвори</button>
<?php }else{ ?>
<button class="cancel" type="button" onclick="window.location='./index.php?page=articles&amp;pn=<?php if(isset($_GET['pn'])) echo $_GET['pn']; else echo '0';?>';"><?php echo $lang['close'];?></button>
<?php } ?>
&nbsp;&nbsp;
<button class="save" type="BUTTON" onclick="return check_form();gE('btn').value='save';document.form1.submit();">Запази</button>
</div>
</form>
<?php
}else{
	header("Location:../../index.php");
	exit(0);
}
?>