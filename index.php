<?php
// Potrebno zbog provjere logina
session_start();
 
// Je li korisnik ulogiran? Ako ne, idi na login stranicu.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;

}

// Ovo includeamo put zbog baze.
require_once "config.php";

// Da bismo znali koju stranicu u headeru oznaciti
const PAGE = 'pocetna';

//Neke sttistike

// Koliko ima usera?
$result_users = $link->query("SELECT * FROM users");
//echo $result_users->num_rows; //test

// Koliko ime zaposlenika?
$result_users_zaposlenici = $link->query("SELECT * FROM users WHERE user_type='1'");
//echo $result_users_zaposlenici->num_rows; //test

// Koliko ime admina?
$result_users_admini = $link->query("SELECT * FROM users WHERE user_type='0'");
//echo $result_users_admini->num_rows; //test

// Koliko ima vozila?
$result_vozila = $link->query("SELECT * FROM vozila");
//echo $result_vozila->num_rows;//test

// Koliko ima dosupnih vozila?
$result_vozila_dostupno = $link->query("SELECT * FROM vozila WHERE status = 'dostupno'");
//echo $result_vozila_dostupno->num_rows; //test

// Koliko ima posuđenih vozila?
$result_vozila_posudjeno = $link->query("SELECT * FROM vozila WHERE status = 'posudjeno'");
//echo $result_vozila_posudjeno->num_rows; //test

// Koliko ima vozila na popravku?
$result_vozila_popravak = $link->query("SELECT * FROM vozila WHERE status = 'popravak'");
//echo "There are " . $result_vozila_popravak->num_rows; //test

// Koliko ima ima isteklu registraciju?
$N=0;

if ($result_vozila->num_rows > 0) {
    while ($row = $result_vozila->fetch_assoc()) {
        if (strtotime($row["datum"]) < time()) {
            $N+=1;
        }
    }
}



// Novi sql query - $link je definiran u conf.php
$result = $link->query("SELECT * FROM users order by id desc");

?>


<!DOCTYPE html>
<html dir="ltr">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Aplikacija</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <link href="css.css" rel="stylesheet" type="text/css">
    </head>

    <body>        
            <?php
                // Prikaz izbornika
                include "header.php";
        ?>
        <h1 style="background-color:#F0F0F0; border:none;">
            <?php
            print 'Zdravo, '.$_SESSION["ime"].' '.$_SESSION["prezime"].', prijavljeni ste kao ';
                    if ($_SESSION["user_type"]==0) {
                        print 'admin.</h1>';      
                    } elseif ($_SESSION["user_type"]==1) {
                        print 'zaposlenik.';
                    }
            ?>
        </h1>
            <table style="font-weight: bold;" class="TableRowHover2">
                <thead>
                    <tr>
                        <th></th>
                        <th>Ukupno</th>
                        <th>Dostupno</th>
                        <th>Posuđeno</th>
                        <th>Na popravku</th>
                        <th>Istekla registracija</th>
                        <th></th>
                        <th>Ukupno</th>
                        <th>Zaposlenici</th>
                        <th>Admini</th>
                    </tr>
                <thead>
                <tbody>
                    <tr>
                        <th style="padding:20px;">Vozila</th>
                        <?php
                        print'
                        <td>'. $result_vozila->num_rows .'</td>
                        <td>'. $result_vozila_dostupno->num_rows .'</td>
                        <td>'. $result_vozila_posudjeno->num_rows .'</td>
                        <td>'. $result_vozila_popravak->num_rows .'</td>
                        <td>'. $N .'</td>
                        <th style="padding:20px;">Korisnici</th>                        
                        <td>'. $result_users->num_rows .'</td>
                        <td>'. $result_users_zaposlenici->num_rows .'</td>
                        <td>'. $result_users_admini->num_rows .'</td>';
                    ?>
                    </tr>
                </tbody>    
            </table>
        
        <br>
        <br>
 
            <p>
                <a href="reset-password.php" class="btn btn-warning">Promijenite loziknu</a>
                <a href="logout.php" class="btn btn-danger">Odjavite se</a>
            </p>

    </body>

</html>