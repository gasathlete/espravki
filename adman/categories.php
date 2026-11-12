<?php
require_once './suppliers/functions2.php';

function deleteDirectory($dirPath) {
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object !="..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
    reset($objects);
    rmdir($dirPath);
    }
}

$display='none';

if(isset($_GET['cid'])) $_POST['cid']=$_GET['cid'];
if(isset($_GET['edit'])) $_POST['edit']=$_GET['edit'];
if(isset($_GET['visible'])) $_POST['visible']=$_GET['visible'];
if(isset($_GET['top_category'])) $_POST['top_category']=$_GET['top_category'];

/*
izpolzwai tazi funkcionalnost ako po nqkakwa prichina se iztriqt index.php failowete w individualnite direktorii. Taka shte gi kopira otnowo waw wsichki
$dir    = '../business-directory';
$files1 = scandir($dir);
foreach($files1 as $newdirectory){
if(is_dir($dir.'/'.$newdirectory) && (strlen($newdirectory) > 2)){
echo $newdirectory.'<br>';
copy('../business-directory/index-to-copy.php', $dir.'/'.$newdirectory.'/index.php');
	}
}
*/

//var_dump($_POST);
function upload_file($f,$fn){

	$saveto = "../images/pc/".$fn.".jpg";
  move_uploaded_file($f['tmp_name'], $saveto);
  $typeok = TRUE;
  switch($f['type']){
  case "image/gif": $src = imagecreatefromgif($saveto); break;
  case "image/jpeg": // Both regular and progressive jpegs
  case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
  case "image/png": $src = imagecreatefrompng($saveto); break;
  default: $typeok = FALSE; break;
  }
  if ($typeok){
  list($w, $h) = getimagesize($saveto);
  $max = 400; 
  // you can change this to desired product for height and width.
  $tw = $w;
  $th = $h;
  if ($w > $h && $max < $w){      
  $th = $max / $w * $h;
  $tw = $max;
  }
  elseif ($h > $w && $max < $h){
  $tw = $max / $h * $w;
  $th = $max;
  }
  elseif ($max < $w){
  $tw = $th = $max;
  }
  $tmp = imagecreatetruecolor($tw, $th);      
  imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
  imageconvolution($tmp, array( // Sharpen image
  array(-1, -1, -1),
  array(-1, 16, -1),
  array(-1, -1, -1)
  ), 8, 0);
  imagejpeg($tmp, $saveto);
  imagedestroy($tmp);
  imagedestroy($src);
  }
}


function listCategory($id,$level=0,$parent,$bg_description){
   $query = "SELECT bg_category, id, visible,top_category, bg_description FROM products_categories where parent='".$id."' order by bg_category ASC";
    $res = mysql_query($query) or die($query);
    if(mysql_num_rows($res) > 0){
   
    while (list ($bg_category, $id,$visible,$top_category,$bg_description) = mysql_fetch_row($res)){
	$que = 'select id from products_categories where parent="'.$id.'"';
		$resu = mysql_query($que) or die($que);
		$ro=mysql_fetch_row($resu);
        if ($level==0){
		
		if($ro[0]>0) {
		
		 echo '<tr><td colspan="2"><a href="index.php?page=categories&cid=',$id,'&edit=1">'.$bg_category.'</a></td>
		 <td>',$bg_description,'</td><td>няма</td>';
		 if($visible==0){$v1='<img src="'.webImagesDir.'interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/desactivate.png" />'; $v2=0;}
		
		if($top_category==0){
		$top='Не';
		$top1='1';
		}else{
		$top='Да';
		$top1='0';
		}
        echo '
		<td><a href="index.php?page=categories&cid=',$id,'&visible='.$v2.'">',$v1,'</a></td>
		<td align="center"><a href="index.php?page=categories&cid=',$id,'&top_category='.$top.'">',$top,'</a></td></tr>';
		
		}else{		
           echo '<tr>
		   <td colspan="2"><input type="checkbox" name="category_id[]" value="',$id,'" /><a href="index.php?page=categories&cid=',$id,'&edit=1">'.$bg_category.'</a></td> <td>',$bg_description,'</td><td>няма</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/desactivate.png" />'; $v2=0;}
		if($top_category==0){
		$top='Не';
		$top1='1';
		}else{
		$top='Да';
		$top1='0';
		}
        echo '<td><a href="index.php?page=categories&cid=',$id,'&visible='.$v2.'">',$v1,'</a></td>
		<td align="center"><a href="index.php?page=categories&cid=',$id,'&top_category='.$top1.'">',$top,'</a></td></tr>';
		}
		
		}
        else{
		if($ro[0]>0){
           echo '<tr>
		   <td colspan="2"><span class="subcat" style="margin-left:',($level*2),'0px;"><input type="checkbox" level="',$level,'" name="category_id[]" value="',$id,'" /><a style="color:#555;" href="index.php?page=categories&cid=',$id,'&edit=1">',$bg_category,'</a></span></td> <td>',$bg_description,'</td><td>',$parent,'</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/desactivate.png" />'; $v2=0;}
		if($top_category==0){
		$top='Не';
		$top1='1';
		}else{
		$top='Да';
		$top1='0';
		}
        echo '<td><a href="index.php?page=categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td>
		<td align="center"><a href="index.php?page=categories&cid=',$id,'&top_category='.$top1.'">',$top,'</td></tr>';
			}
		else{
		 echo '<tr>
		   <td colspan="2"><span class="subcat" style="margin-left:',($level*2),'0px;"><input type="checkbox" level="',$level,'" name="category_id[]" value="',$id,'" /><a style="color:#555;" href="index.php?page=categories&cid=',$id,'&edit=1">',$bg_category,'</a></span></td> <td>',$bg_description,'</td><td>',$parent,'</td>';
		 if($visible==0){$v1='<img src="'.WebSite.'/images/interface/activate.png" />'; $v2=1;}
		else{$v1='<img src="'.WebSite.'/images/interface/desactivate.png" />'; $v2=0;}
		if($top_category==0){
		$top='Не';
		$top1='1';
		}else{
		$top='Да';
		$top1='0';
		}
        echo '<td><a href="index.php?page=categories&cid=',$id,'&visible='.$v2.'">',$v1,'</td>
		<td align="center"><a href="index.php?page=categories&cid=',$id,'&top_category='.$top1.'">',$top,'</td></tr>';
			}
		}
        listCategory($id,$level+1,$bg_category,$bg_description);
		}
	}else{
	if ($level==0) echo '<tr><td style="text-align:center;" colspan="4">Все още няма добавени категории</td></tr>';
	}
}

function check_master_categories($cid,$mc){

	$q='select id from products_categories where parent="'.$_POST['cid'].'"';
	$res=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
	$num_r=mysql_num_rows($res);
	$msg='';
	for($i=0;$i<$num_r;$i++){
	$row=mysql_fetch_row($res);
	$qu='select id from products_categories where parent="'.$row[0].'"';
	
	$resu=mysql_query($qu) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$qu);
	$num_ro=mysql_num_rows($resu);
	for($j=0;$j<$num_ro;$j++){
	$ro=mysql_fetch_row($resu);
	if($ro[0]==$_POST['mc']){
	$message ='eeerror';
		}
	}
	if($row[0]==$_POST['mc']){
	$message ='eeerror1';
		}
	}
	//exit();
	return $message;
	
}
	

function getMasterCategories($c=0,$cc=0){
	if(empty($cc)){
	$cc=9999999999;
	echo '<option value="0" selected="true">Няма</option>';
	}
	$que='select id, bg_category,parent from products_categories where id="'.$cc.'"';
	$res=mysql_query($que) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$que);
	$num_rows=mysql_num_rows($res);
	$rw=mysql_fetch_row($res);
	
		if($rw[0]>0){
		$querys='select id,bg_category,parent from products_categories where parent="'.$cc.'" order by bg_category';
		$results=mysql_query($querys) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$querys);
		$num_rs=mysql_num_rows($results);
		$roww=mysql_fetch_row($results);
		if($num_rs=="0"){
		$query='select id,bg_category,parent from products_categories where id!="'.$cc.'" order by bg_category';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		$num_r=mysql_num_rows($result);
		echo '<option value="0" selected="true">Няма</option>';
		for($i=0;$i<$num_r;$i++){
		$row=mysql_fetch_row($result);
		$q='select id,bg_category,parent from products_categories where parent="'.$cc.'" and id="'.$row[0].'" order by bg_category';
		$r=mysql_query($q) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$q);
		$ro=mysql_fetch_row($r);
		if(empty($ro[0])){
			if($c==$row[0]) echo '<option value="',$row[0],'" selected="true">',$row[1],'</option>';
			else echo '<option value="',$row[0],'">',$row[1],'</option>';
				}
			}
		}else{
		$ques='select id, bg_category from products_categories where id="'.$rw[2].'"';
		$ress=mysql_query($ques) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$ques);
		$num_rowss=mysql_num_rows($ress);
		$rws=mysql_fetch_row($ress);
		if($c==$rw[2]) echo '<option value="',$rw[2],'" selected="true">',$rws[1],'</option>';
		}
	}	
}

if(isset($_SESSION['adman'])){
	if(!empty($_POST['edit'])){
	
		if($_POST['edit']==1){
			if(!empty($_POST['cid'])){
				$query='select id,bg_category,bg_description,bg_category,bg_description,bg_category,bg_description,bg_category,bg_description,bg_category,bg_description,parent,visible,link_to_offer,top_category,meta_title from products_categories where id="'.intval($_POST['cid']).'"';
				$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
				if(mysql_num_rows($result)){
					$row=mysql_fetch_array($result);
					$_POST['bg_cname']=htmlspecialchars($row[1]);
					$_POST['bg_descr']=htmlspecialchars($row[2]);
					$_POST['en_cname']=htmlspecialchars($row[3]);
					$_POST['en_descr']=htmlspecialchars($row[4]);
					$_POST['de_cname']=htmlspecialchars($row[5]);
					$_POST['de_descr']=htmlspecialchars($row[6]);
					$_POST['fr_cname']=htmlspecialchars($row[7]);
					$_POST['fr_descr']=htmlspecialchars($row[8]);
					$_POST['ru_cname']=htmlspecialchars($row[9]);
					$_POST['ru_descr']=htmlspecialchars($row[10]);
					$_POST['top_category']=$row[14];
					$_POST['meta_title']=htmlspecialchars($row['meta_title']);
					$_POST['mc']=$row[11];
					$_POST['visible']=$row[12];
					$_POST['link_to_offer']=$row[13];
					$img='../images/pc/'.create_url($row[1]).'.jpg';//echo 'aaa<br />';
					if(file_exists($img)){ $img=$img;//echo 'bbb<br />';
					}else $img='../images/no_product_image.jpg';
				}
			}
			else{
				$_POST['bg_cname']=='';
				$_POST['bg_descr']=='';
				$_POST['en_cname']=='';
				$_POST['en_descr']=='';
				$_POST['de_cname']=='';
				$_POST['de_descr']=='';
				$_POST['fr_cname']=='';
				$_POST['fr_descr']=='';
				$_POST['ru_cname']=='';
				$_POST['ru_descr']=='';
				$_POST['meta_title']='';
				$_POST['top_category']=0;
				$_POST['mc']=0;
				$_POST['visible']='';
				$img='../images/no_product_image.jpg';
			}
			$display='block';
		}
		elseif($_POST['edit']==2){
			if(isset($_POST['bg_descr'],$_POST['bg_cname'],$_POST['mc'],$_POST['visible'],$_POST['category_submit'])){
				$_POST['bg_descr']=addslashes(htmlspecialchars(trim($_POST['bg_descr'])));
				$_POST['bg_cname']=trim($_POST['bg_cname']);
				$_POST['top_category']=$_POST['top_category'];
				$_POST['mc']=addslashes(htmlspecialchars(trim($_POST['mc'])));
				$_POST['visible']=addslashes(htmlspecialchars(trim($_POST['visible'])));
				$_POST['link_to_offer']=$_POST['link_to_offer'];
				$_POST['meta_title']=$_POST['meta_title'];
				
				if(!empty($_POST['bg_cname'])){
					$query='select id from products_categories where bg_category like "'.mysql_real_escape_string($_POST['bg_cname']).'" and id!="'.mysql_real_escape_string($_POST['cid']).'" limit 1';
					$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
					if(mysql_num_rows($result)< 1){
					$img='../images/pc/'.create_url($_POST['bg_cname']).'.jpg';
					if(file_exists($img)) $img='../images/pc/'.create_url($_POST['bg_cname']).'.jpg';
					else $img='../images/no_product_image.jpg';
						if(isset($_FILES['pcimg']) && !empty($_FILES['pcimg']['name'])){
							$m=is_file_uploaded($_FILES['pcimg']);
							if($m[0]>0){
								$msg=$m[1];
								$display='block';
							}
							else{
								upload_file($_FILES['pcimg'],create_url($_POST['bg_cname']));
								$img_uploaded=1;
							}
						}

						if($_POST['cid']>0){
						$msg = check_master_categories($_POST['cid'],$_POST['mc']);
						}
						if(empty($msg)){
							if(empty($_POST['cid'])){
								$url=create_url(strtolower($_POST['bg_cname']));
								$_POST['bg_cname'] = preg_replace ( '/[^A-Za-z0-9\p{Cyrillic}\p{Ll}\w]/u', ' ', $_POST['bg_cname']);
								$query='insert into products_categories set url="'.mysql_real_escape_string($url).'",top_category="'.mysql_real_escape_string($_POST['top_category']).'", 
								bg_category="'.mysql_real_escape_string($_POST['bg_cname']).'",bg_description="'.mysql_real_escape_string($_POST['bg_descr']).'",meta_title="'.mysql_real_escape_string($_POST['meta_title']).'",
								parent="'.mysql_real_escape_string($_POST['mc']).'", visible="'.mysql_real_escape_string($_POST['visible']).'", link_to_offer="'.$_POST['link_to_offer'].'"';
								mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
								$_POST['cid']=mysql_insert_id();
								
								$msg ='Категорията беше запазена !';
							}else{
								if(empty($img_uploaded)){
									$query='select bg_category from products_categories where id="'.$_POST['cid'].'"';
									$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
									if(mysql_num_rows($result)){
										$row=mysql_fetch_row($result);
										$img_bg='../images/pc/'.create_url($row[0]).'.jpg';
										if(file_exists($img_bg)){
											$img='../images/pc/'.create_url($_POST['bg_cname']).'.jpg';
											rename($img_bg,$img);
										}
									}
								}
								
								if(!$message){
								$url=create_url(strtolower($_POST['bg_cname']));
								
								$query='update products_categories set url="'.mysql_real_escape_string($url).'",
								bg_category="'.mysql_real_escape_string($_POST['bg_cname']).'",bg_description="'.mysql_real_escape_string($_POST['bg_descr']).'",meta_title="'.mysql_real_escape_string($_POST['meta_title']).'",
								parent="'.mysql_real_escape_string($_POST['mc']).'", top_category="'.mysql_real_escape_string($_POST['top_category']).'",visible="'.mysql_real_escape_string($_POST['visible']).'",link_to_offer="'.$_POST['link_to_offer'].'" where id="'.mysql_real_escape_string($_POST['cid']).'"';
								mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
								$msg ='Категорията беше обновена !';
								}else {$display='none';echo $message;}
							}
							$_POST['edit']=1;
							$display='block';
			
						}else{ $_POST['mc']="0";$display='block';
						$msg='Не можете да направите тази категория дъщерна на категория на която е главна!';
						}
					}
					else{
						$display='block';
						$msg='Вече съществува категория с това име!';
					}
				}
				else{
					$display='block';
					$msg='Трябва да въведете полето "Име на категория"!!';
				}
			}
			else{
				$display='block';
				$msg='Трябва да въведете всички полета !';
			}
		}
	}
	elseif(isset($_POST['btn'],$_POST['category_id']) && $_POST['btn']=='del'){
	//var_dump($_POST);
	foreach($_POST['category_id'] as $categ){
	//echo $categ;
		$query='select id from products_categories where parent="'.$categ.'" limit 1';
		$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		if(mysql_num_rows($result)){
			$msg='За да може да изтриете тази категория, първо трябва да премахнете всичките и под категории!';
		}
		else{
			$query='select id from adverts_to_product_categories where category_id="'.$categ.'" limit 1';
			$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
			if(mysql_num_rows($result)){
				$msg='За да може да изтриете тази категория, първо трябва да премахнете всички листинги свързани с нея!';
			}
			else{		
				$query='select bg_category,url from products_categories where id="'.intval($categ).'"';
				$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
				$url = 'wrong cateogry';
				if(mysql_num_rows($result)){
					$row=mysql_fetch_row($result);
					$url = $row[1];
					$img='../images/pc/'.create_url($row[0]).'.jpg';
					if(file_exists($img)) @unlink($img);
				}
				
				$query='delete from products_categories where id="'.$categ.'"';
				mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
				$dir='../business-directory/'.$url;
				if(file_exists($dir)){
				$msg='Категорията премахната!';
				//triem direktoriqta - dosta e riskowo da ne omaze neshto !!!
				//deleteDirectory($dir);
				//echo $url.' -exists';echo '<br/>';
					}else $msg='Категорията не беше намерена сред файловете, но изтрита от базата !';
				}
			}
		}
	}
	//var_dump($_POST);
$old_bg_cname = create_url(strtolower($_POST['bg_cname']));
?>
<form action="index.php?page=categories" accept-charset="utf-8" name="form1" id="form1" enctype="multipart/form-data" method="POST">
<input type="hidden" name="page" value="categories" />
<input type="hidden" name="btn" id="btn" value="<?php echo $_POST['btn'];?>" />
<input type="hidden" name="cid" value="<?php if(!empty($_POST['cid'])) echo $_POST['cid'];?>" />
<input type="hidden" name="edit" id="edit" value="<?php echo $_POST['edit'];?>" />
<input type="hidden" name="old_bg_cname" value="<?php echo @$old_bg_cname;?>" />
<?php
	if($_POST['edit']==1){
?>
<div id="addf" style="display:<?php echo $display;?>;">
<table width="600" class="table table-bordered border-top mb-0" cellspacing="1"><tbody>
	<?php if(!empty($msg)) echo '<tr><td colspan="6" align="center" style="color:red;">',$msg,'</td></tr>';?>
	<tr>
		<th align="left" style="width:50%" colspan="2">Категория майка</th>
		<th align="left" style="width:50%" colspan="2">Активна</th>
		<th align="left" style="width:50%" colspan="2">Топ категория</th>
	</tr>
	<tr>
		<td align="left" colspan="2">
			<select name="mc" style="width:250px;"><?php getMasterCategories($_POST['mc'],$_POST['cid']);?></select>
		</td>
		<td align="left" colspan="2">
			<select name="visible" value="<?php echo @$_POST['visible'];?>">
				<option value="1">1</option>
				<option value="0" <?php if(empty($_POST['visible'])) echo 'selected="true"';?>>0</option>
			</select>
		</td>
		<td align="left" colspan="2">
			<select name="top_category" value="<?php echo @$_POST['top_category'];?>">
				<option value="1">1</option>
				<option value="0" <?php if(empty($_POST['top_category'])) echo 'selected="true"';?>>0</option>
			</select>
		</td>
	</tr>
	<tr>
		<th align="left" colspan="2"><img title="Български" src="../images/flags/bg.png" /> Име на категорията</th><th align="left" colspan="3"><img title="Български" src="../images/flags/bg.png" /> Мета описание</th></tr>
		<tr><td align="left" colspan="2"><input type="text" class="form-control" name="bg_cname" value="<?php echo @$_POST['bg_cname'];?>" /></td>
		<td align="left" colspan="3"><textarea name="bg_descr" value="<?php echo @$_POST['bg_descr'];?>" class="form-control" ><?php echo @$_POST['bg_descr'];?></textarea></td>
	</tr>
	<tr>
		<td align="center" colspan="2"><img src="<?php echo $img;?>" width="180"></td>
		<td align="left" colspan="3"><div class="imagediv"><input type="file" name="pcimg" /></div><br />Best image size: 100x100px</td>
	</tr>
	<tr>
		<td align="left" colspan="5"><b>Линк към специална оферта</b><br />
		<input class="form-control" type="text" name="link_to_offer" value="<?php echo @$_POST['link_to_offer'];?>" /></td>
	</tr>
	<tr>
		<td align="left" colspan="5"><b>Meta title на категорията</b><br />
		<input class="form-control" type="text" name="meta_title" value="<?php echo @$_POST['meta_title'];?>" /></td>
	</tr>
	<tr><td colspan="5" align="center" style="text-align:center;"><input class="cancel" type="reset" onclick="window.location='/adman/index.php?page=categories';" value="Откажи" />&nbsp;&nbsp;<input class="save" type="submit" name="category_submit" onclick="gE('edit').value=2;" value="Запази" /></td></tr>
</tbody></table>

</div>
<?php
}else{
?>

<?php if(!empty($msg)) echo '<p align="center" style="color:red">',$msg,'</p>';?>
<div class="ib w100 tar"><a class="btn btn-secondary" href="index.php?page=categories&edit=1">Добави</a></div>
<h1>Категории Бизнес регистър</h1>
<table id="tohide" class="table table-bordered border-top mb-0"><tbody>
	<tr>
		<th colspan="2" align="left">Име на категорията</th>
		<th align="left">Описание</th>
		<th align="left">Категория майка</th>
		<th width="30" align="left">Активна</th>
		<th width="30" align="left">Топ категория</th>
	</tr>
<?php
	if(isset($_POST['visible']) && !empty($_POST['cid'])){
		$query='update products_categories set visible="'.addslashes(htmlspecialchars($_POST['visible'])).'" where id="'.addslashes(htmlspecialchars($_POST['cid'])).'" or parent="'.addslashes(htmlspecialchars($_POST['cid'])).'"';
		mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		if($_POST['visible']==1){
			$query='select parent from products_categories where id="'.addslashes(htmlspecialchars($_POST['cid'])).'"';
			$result=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
			$row=mysql_fetch_array($result);
			$query='update products_categories set visible="1" where id="'.$row[0].'"';
			$result1=mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
		}
	}
	if(isset($_POST['top_category']) && !empty($_POST['cid'])){
		$query='update products_categories set top_category="'.addslashes(htmlspecialchars($_POST['top_category'])).'" where id="'.addslashes(htmlspecialchars($_POST['cid'])).'" or parent="'.addslashes(htmlspecialchars($_POST['cid'])).'"';
		mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
	}
	$cid=0;
	if(isset($_POST['cid'])) $cid=$_POST['cid'];
	echo listCategory(0,@$level,0,'');
?>
	<tr>
		<td colspan="3" style="text-align:right;">
		<?php
		if($_SESSION['adman']=="1"){
		?>
		<input type="button" class="cancel" name="del" onclick="delete_ask('Сигурен ли сте, че желаете да изтриете тази категория и нейните под категории?');" value="Премахни" />
		<?php
		}
		?></td>
		<td colspan="3" align="left"><a href="index.php?page=categories&edit=1"><button class="save" type="button" />Добави</button></a></td>
	</tr>
</tbody></table>
<?php
}
?>

</form>
<?php
}
else{
	header("Location:index.php");
	exit(0);
}
?>