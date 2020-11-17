<?php
// Potrebno zbog provjere logina
session_start();
 
// Prebriši sve varjable vezane za session
$_SESSION = array();
 
// Uništi session
session_destroy();
 
// Idi na login stranicu
header("location: login.php");
exit;
?>