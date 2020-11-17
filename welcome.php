<?php
// Potrebno zbog provjere logina
session_start();
 
// Je li korisnik ulogiran? Ako ne, idi na login stranicu.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


// htmlspecialchars() - Ako koriničko ime (email) slučajno sadrži HTML kod, ova fukncija će ih ukloniti. Inače bi se izgled stranice mogao poremetiti.

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="css.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php
	// Da bismo znali koju stranicu u headeru oznaciti
	const PAGE = 'pocetna';
        // Prikaz izbornika
        include "header.php"; 
            
        // Za prikaz imena ispod koristimo $_SESSION["ime"]
        // htmlspecialchars - tehnički nije potrebno, ali dobro izgleda (:
    
    ?>
    <div class="page-header">
        <h1>Zdravo, <b>
            <?php
            echo htmlspecialchars($_SESSION["ime"]);
            echo ' ';
            echo htmlspecialchars($_SESSION["prezime"]);
            echo '</b>.<br>';
            
            if ($_GET["status"]=='nematePravo') {
                echo 'Nažalost, nemate ovlasti za tu akciju.';
            } else {
                echo 'Uspješno ste se prijavili!</h1>';
            }

            ?>
    </div>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Promijenite loziknu</a>
        <a href="logout.php" class="btn btn-danger">Odjavite se</a>
    </p>
</body>
</html>
