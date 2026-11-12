<?php
session_start();
if(!empty($_SESSION['adman'])){
header("Location: index.php");
exit(0);
}else 
header("Location: admin_login.php");
exit(0);
ob_end_flush();
?>