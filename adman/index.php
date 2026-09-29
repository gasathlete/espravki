<?php
session_start();
//var_dump($_SESSION);echo '<br>';
require("../config.php");
require '../translation/bg_lang.php';

require("./suppliers/admin_functions.php");

if (!empty($_SESSION['country'])) {
	//echo $_SESSION['country'].'tuk';
	if ((!intval($_SESSION['country']) && $_SESSION['country'] != "Bulgaria") || (intval($_SESSION['country']) && $_SESSION['country'] != "35")) {
		header("Location: ../index.php");
		exit(0);
	}
} else {
	header("Location: ../index.php");
	exit(0);
}
if (!isset($_SESSION['adman'])) {

	error_reporting(E_ALL);
	header("Location: " . WebSite . "/adman/redirect.php");
	exit(0);
} else {
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<?php
		echo @$page_components['title'];
		echo @$page_components['keywords'];
		echo @$page_components['description'];
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta name="revisit_after" content="2 days">
		<link href="../assets/css/style.css" rel="stylesheet" />
		<link href="../assets/css/buttons.css" rel="stylesheet" />
		<link id="theme" href="../assets/color-skins/color1.css" rel="stylesheet" />
		<link href="../assets/plugins/fancyuploder/fancy_fileupload.css" rel="stylesheet" />
		<link href="../adman/suppliers/css/admin_style.css" rel="stylesheet" />


		<!--
<script src="./js/jquery1.min.js"></script>
<script src="./js/jalerts/jquery.js" type="text/javascript"></script>
<script src="./js/jquery-ui.js"></script>-->


		<style>
			.ui-autocomplete-loading {
				background: white url('../images/interface/icons/search.gif') right center no-repeat;
			}

			.ui-autocomplete {
				position: fixed;
				z-index: 9999 !important;
			}
		</style>
	</head>

	<body>
		<!-- <div id="global-loader">
			<img src="../assets/images/products/products/loader.png" class="loader-img floating" alt="Зареждам страницата" />
			<span><?php echo $lang['load']; ?></span>
		</div> -->
		<section>
			<div style="height:auto;" id="page">
				<div id="menu_top"><?php include("menu_top.php"); ?></div>
				<div class="container">
					<div class="box">
						<div id="central_column">
							<?php

							if (!empty($_SESSION['adman'])) {
								//var_dump($_POST);echo '<br>';
								if (isset($_GET['page']) && ($_GET['page'] != "") && !isset($_POST['record_action'])) {
									$file_name = "" . $_GET['page'] . ".php";
									if (file_exists($file_name)) {
										include($file_name);
									} else {
										header("Location: index.php");
										exit(0);
									}
								} elseif (!empty($_POST['record_action']) && $_POST['record_action'] == 'add') {
									if (isset($_GET['page']) && !isset($_GET['aid'])) {
										include('./suppliers/template/add_' . $_GET['page'] . '_tpl.php');
									} else {
										if (!empty($_GET['aid'])) include('./suppliers/template/edit_advert.php');
										if (!empty($_GET['pid'])) include('./suppliers/template/edit_product.php');
										elseif (!empty($_POST['articles'])) include('./suppliers/template/add_articles_tpl.php');
									}
								} elseif (!empty($_POST['record_action']) && $_POST['record_action'] == 'update') {

									if (isset($_GET['page']) && $_GET['page'] != "new_adverts") {
										if (!empty($_POST['advert'])) include('./edit_advert.php');
										else include('./suppliers/template/add_' . $_GET['page'] . '_tpl.php');
									} else {
										//if(!empty($_POST['advert'])) include('./suppliers/template/add_adverts_tpl.php');
										if (!empty($_POST['advert'])) include('./edit_advert.php');
										elseif (!empty($_POST['articles'])) include('./suppliers/template/add_articles_tpl.php');
									}
								} elseif (!empty($_POST['record_action']) && $_POST['record_action'] == 'delete') {
									if (!empty($_POST['advert'])) delete_advert($_POST['advert']);

									echo '<script>window.location.href = "../adman/index.php?page=new_adverts"</script>';
									//header("Location: ./index.php?page=adverts");
									//exit(0);
								} elseif (isset($_GET['page'])) {
									//echo 'add_'.$_GET['page'].'_tpl.php';
									//echo $_POST['record_action'];
									include('./' . $_GET['page'] . '.php');
								}
								/*else{
		if(!empty($_POST['advert'])) include('./suppliers/template/add_adverts_tpl.php');
		elseif(!empty($_POST['articles'])) include('./suppliers/template/add_articles_tpl.php');
		}*/ elseif (!empty($_GET['y'])) {
									require_once './mail-marketing-preview.php';
								} else {
									include('adverts.php');
								}
							}

							?>
						</div>
					</div>
				</div>
			</div>

		</section>
		<script src="../assets/js/<?php echo $_SESSION['lang'] . '_lang'; ?>.js"></script>
		<script src="../assets/js/fullfunctions.js"></script>
		<script src="../assets/js/jquery-ui.js"></script>
		<!--
<script src="../assets/plugins/fancyuploder/fancy_uploader_full.js"></script>-->
		<!--<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>-->
		<script src="https://cdn.tiny.cloud/1/1gk9wazgim3qrlmipqqxzu36f4m5tt469qzmcy5lkvz5wg48/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
		<?php
		if (isset($_GET['aid'])) {
		?>
			<script src="../../assets/plugins/fancyuploder/fancy_uploader_full.js"></script>
			<script src="../adman/js/contacts.js"></script>
			<!--<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>-->
			<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo google_api; ?>"></script>
			<script src="../adman/js/add_listings.js"></script>
			<script>
				var keywords = <?php echo json_encode($cities); ?>;
				$('#town').autocomplete({
					//source: [keywords],
					source: keywords,
					minLength: 1,
					select: function(event, ui) {
						event.preventDefault();
						//console.log(ui.item.label);
						$("#town").val(ui.item.label);
					}
				});

				//copirane na listing
				$('body').on('click', '#copy_listing', function() {
					$('#advertForm').prop('action', $('#copy_listing').attr('data-url'));
				})
			</script>
		<?php
		}
		if (isset($_GET['pid'])) {
		?>
			<script src="../assets/js/<?php echo $_SESSION['lang'] . '_lang'; ?>.js"></script>
			<!--
<script src="../assets/plugins/fancyuploder/fancy_uploader_full.js"></script>-->
			<script src="../assets/js/contacts.js"></script>
			<!--<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>-->
			<script src="../adman/js/add_products.js"></script>
		<?php
		}
		?>
		<script src="../adman/js/functions.js"></script>
		<script>
			$('document').ready(function() {
				if ($('#addf').length) {
					<?php
					if ($_GET['page'] == 'categories' && isset($_GET['edit']) && $_GET['edit'] == "1") {
					?>
						$("#tohide").hide();
					<?php
					}
					?>
					position_box_def_size('addf', 600, 450);
				}
			});
		</script>
	</body>

	</html>
<?php
}
?>