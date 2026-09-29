<?php
session_start();
require './config.php';
session_destroy();
header('Location:'.WebSite);
exit(0);
?>