<?php
if(isset($_SESSION['adman'])){

$lang=array(
'artcle_categories'=>array(
	'0'=>'Няма',
	'1'=>'Служебни',
	'2'=>'Новини',
	'3'=>'Архив'
	),
);
//var_dump($_POST);
if(isset($_POST['record_action']) && $_POST['record_action']=="delete"){
	echo $msg=delete_articles(addslashes($_POST['articles']));
}

if(isset($_GET['visible']) && !empty($_GET['aid'])){
	$query='update articles set active="'.addslashes(htmlspecialchars($_GET['visible'])).'" where id="'.addslashes(htmlspecialchars($_GET['aid'])).'"';
	mysql_query($query) or die('Mysql Error:'.mysql_error().'<br /> Query:'.$query);
}
	$changes=array();
	$_POST['pager_base_link']=get_base_link();

	
		if(isset($_GET['article']) || isset($_POST['btn'])){
		//require_once './suppliers/lang/English.php';
		require_once './suppliers/functions2.php';
		$message='';
		if(isset($_GET['article'])) $article=$_GET['article'];
		elseif(isset($_POST['article'])) $article=$_POST['article'];
		else $article=0;
		//echo $_POST['btn'];
		switch(@$_POST['btn']){
			case 'save':{
				$msg=save_article($article);
				$message=$msg[1];
				if($msg[0]==0) $article=$msg[2];
				require_once './suppliers/template/add_articles_tpl.php';
				break;
			}
			default: require_once './suppliers/template/add_articles_tpl.php'; break;
		}
		
	}else{
	
	
	if(isset($_GET['subarticles'])){
		$category=$_GET['subarticles'];
		$q="SELECT bg_article_title FROM articles WHERE id='".addslashes($_GET['subarticles'])."'";
		$r=mysql_query($q) or hjdie();
		$rw=mysql_fetch_assoc($r);
		$_POST['ttl']="\"".$rw['bg_article_title']."\"";
		$_POST['case']="with_parent";
	}
	else{
		$category=0;
		$_POST['ttl']="";
		$_POST['case']="without_parent";
	}
	$_POST['article_category']=$category;
	$actions = $_POST['record_action'];
	if(isset($_POST['record_action']) && ( (($_POST['record_action']=="desactivate") && array_key_exists("activate",$actions)) or ( ($_POST['record_action']!="desactivate")) )){
		if(($_POST['record_action']=="desactivate") or ($_POST['record_action']=="activate") or ($_POST['record_action']=="delete")){
			$function_name=$_POST['record_action']."_action";
			if(function_exists($function_name)) $function_name("articles",$_POST['article']);
		}
		elseif(array_key_exists($_POST['record_action'],$actions)){
			$url="index.php?article=".$_POST['article'];
			if(isset($_GET['subarticles'])) $url.="&subarticles=".$_GET['subarticles'];
			$url.="&pn=".$_GET['pn'];
			if(isset($_GET['asc'])) $url.="&asc=".$_GET['asc'];
			if(isset($_GET['desc'])) $url.="&desc=".$_GET['desc'];
			header("Location:".$url);
			exit(0);
		}
	}
	$changes=array();
	$query='SELECT id, bg_article_title, bg_meta_description, added_date, top_article, active, category_id FROM articles where show_in_site = "1" ';
	if(!empty($category)) $query.=" and category_id='".addslashes($category)."' ";

	$_POST['pages_count']=get_pages_count($query);
	if(isset($_GET['asc'])){
		$changes[$_GET['asc']]['sort']['link']="index.php?page=articles&pn=".$_POST['current_page']."&desc=".$_GET['asc'];
		$changes[$_GET['asc']]['sort']['icon']="asc.png";
		$sort_field=$_GET['asc'];
		$method="ASC";
	}
	
	if(isset($_GET['desc'])){
		$changes[$_GET['desc']]['sort']['link']="index.php?page=articles&pn=".$_POST['current_page']."&asc=".$_GET['desc'];
		$changes[$_GET['desc']]['sort']['icon']="desc.png";
		$sort_field=$_GET['desc'];
		$method="DESC";
	}

	//include($prev_path."main/pager_form.php");
	
	if(isset($_GET['asc']) or isset($_GET['desc']))	$query.=" ORDER BY by id asc";
	else{
	if(!isset($_GET['subarticles'])) $query.= ' group by category_id order by id asc';
	}
	//$query.=add_query_limits($_POST['current_page']);
	
	if(!isset($_GET['article'])){
	$_SESSION['back']=$_SERVER['REQUEST_URI'];
	
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
		$num_rows=mysql_num_rows($result);
		echo '<table width="100%" class="table table-bordered border-top mb-0"><tbody>
		<tr>
		<th>#</th>
		<th>Заглавие</th>
		<th>Кратко описание</th>
		<th>Категория</th>
		<th>Дата на добавяне</th>
		<th>Топ статия</th>
		<th width="10%">Действия<span style="float:right;"><form method="post" action="index.php?page=articles&article=0&pn=1">
		<input type="hidden" value="0" name="articles">
		<input type="hidden" value="add" name="record_action">
		<input type="image" src="../images/interface/add.png" value="add" title="Add New" name="record_submit">
		</form></span></th>
		</tr>';
		if($num_rows > 0){
		for($i = 0; $i < $num_rows; $i++) {
		$row=mysql_fetch_row($result);
		if($row[5]==0){$v1='<img src="../images/interface/activate.png" />'; $v2=1;}
		else{
		$v1='<img src="../images/interface/desactivate.png" />'; $v2=0;
		}
		//SELECT id, bg_article_title, bg_short_description, added_date, top_article, active
		
		//echo $row[4];
		echo '<tr>
		<td>',$i+1,'</td>
		<td>',$row[1],'</td>
		<td>',$row[2],'</td>
		<td><a href="index.php?page=articles&subarticles='.$row[6].'">',$lang['artcle_categories'][$row[6]],'</a></td>
		<td>',$row[3],'</td>
		<td>',$lang['artcle_categories'][$row[4]],'</td>
		<td align="center">
		<span class="laction"><a href="index.php?page=articles&aid='.$row[0].'&visible='.$v2.'">',$v1,'</a></span>
		
		<span class="laction">
		<form method="post" action="" >
		<input type="hidden" value="',$row[0],'" name="articles" />
		<input type="hidden" value="update" name="record_action" />
		<input type="image" src="../images/interface/update.png" value="update" title="update" name="record_submit" class="custom_submit" /></form></span>
		<span class="laction">
		<form method="post" action="" name="delform',$row[0],'" id="delform',$row[0],'">
		<input type="hidden" value="',$row[0],'" name="articles" />';
		if($_SESSION['adman']=="1"){
		echo '<input type="hidden" value="delete" name="record_action" />
		<img onclick="return delete_ask2(\'',$row[0],'\');" src="../images/interface/delete.png" title="delete" />';
		}
		echo '</form></span></td>		
		</tr>';
		
			}		
		}
		
		echo '</tbody></table>';
	//echo table_creator($query,"tbl_articles",$actions,$changes,$language,1);
	
	echo $html_pager_form;
	
	echo set_navigation($language);
		}
	}

}else{
	header("Location:index.php");
	exit(0);
}
?>