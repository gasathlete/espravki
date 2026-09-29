<div id="navigation">
<div class="categories"><span id="showhide"><img alt="<?php echo $lang['show_categories'];?>" src="<?php echo WebSite;?>/images/interface/c4.png" /><?php echo $lang['show_categories'];?></span>

<span class="addlisting">
<?php
if(empty($_SESSION['advert_id'])){
echo '<a href="',WebSite,'/news/select-plan/">',$lang['add'],'</a>';
}else{
echo '<ul id="cmenu"><li><a class="my" href="#">',$lang['my_profile'],'</a>
<ul><li><a href="',WebSite,'/addbusiness/edit/">',$lang['edit'],'</a></li>
<li><a href="',WebSite,'/userlogins/logout.php">',$lang['logout'],'</a></li></ul></li></ul>
';
}
?>
</span><span class="s">
<a href="<?php echo WebSite;?>"><?php echo $lang['search'];?></a></span></div></div>