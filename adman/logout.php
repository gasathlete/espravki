<?php
session_start();
unset($_SESSION['adman']);
session_destroy();
ob_start();
header("Location: ../index.php");
exit(0);
ob_end_flush();
?>