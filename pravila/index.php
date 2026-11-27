<?php
/* Legal pages: /pravila/ (list) and /pravila/<page>. Texts are in modules/v3/legal.php.
   DRAFTS (2026-11-27): to be checked by a lawyer. */
session_start();
header("Content-Type: text/html; charset=utf-8");
require '../config.php';
require '../project_functions.php';
if(empty($_SESSION['lang'])) $_SESSION['lang'] = 'bg';
require '../translation/'.$_SESSION['lang'].'_lang.php';

$legal_page = isset($_GET['p']) ? preg_replace('/[^a-z\-]/', '', (string)$_GET['p']) : '';
include '../modules/v3/legal.php';
