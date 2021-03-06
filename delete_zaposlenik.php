<?php
// Potrebno zbog provjere logina
session_start();
 
// Je li korisnik ulogiran? Ako ne, idi na login stranicu.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Samo admin može brisati zaposlenike
if($_SESSION["user_type"]==1){
    header("location: welcome.php?status=nematePravo");
    exit;
}

// Ovo includeamo put zbog baze.
require_once "config.php";

// Da bismo znali koju stranicu u headeru oznaciti
const PAGE = 'zaposlenici';

//briše iz baze
$link->query("DELETE FROM users WHERE id = " . $_GET['id']);

//vraća na stranicu zaposlenika
header("Location: zaposlenici.php?status=zaposlenikObrisan");

?>