<?php
/* Add / remove a product from "Любими". POST pid. Answer: {"ok":1,"on":true,"count":3} */
session_start();
header('Content-Type: application/json; charset=utf-8');
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' || $_SERVER['REQUEST_METHOD'] != 'POST'){ echo '{"ok":0}'; exit; }
require '../config.php';
if($_SERVER['HTTP_HOST'] != full_domain_name){ echo '{"ok":0}'; exit; }
require '../project_functions.php';
require_once '../modules/v3/favs.php';

$pid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;
$res = $pid > 0 ? v3_fav_toggle($pid) : false;
if($res === false){ echo json_encode(array('ok' => 0, 'msg' => 'Този продукт вече не се предлага.')); exit; }
echo json_encode(array('ok' => 1, 'on' => $res[0], 'count' => $res[1]));
