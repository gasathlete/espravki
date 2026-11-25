<?php
/* "Любими" page: /favorites/ (replaces the old cart). */
session_start();
header('Content-Type: text/html; charset=utf-8');
require '../config.php';
require '../project_functions.php';
if(empty($_SESSION['lang'])) $_SESSION['lang'] = 'bg';
require '../translation/'.$_SESSION['lang'].'_lang.php';
$V3_PREVIEW = (isset($_GET['preview']) && $_GET['preview'] === 'v3');
include '../modules/v3/favorites.php';
