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
const PAGE = 'vozila';

?>


<!DOCTYPE html>
<html dir="ltr">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Vozila</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <link href="css.css" rel="stylesheet" type="text/css">
    </head>

    <body>        
        <?php
            // Prikaz izbornika
            include "header.php"
        ?>

        <h1>Popis vozila</h1>
        
       <?php
            if ($_GET['status'] == 'voziloIzmijenjeno') {
                $msg = "Vozilo je uspješno izmijenjeno!";
                print '<div class="Uspjeh"><p style="font-size:20;">'.$msg.'</p></div>';
            } elseif ($_GET['status'] == 'voziloObrisano') {
                $msg = "Vozilo je uspješno obrisano!";
                print '<div class="Uspjeh"><p style="font-size:20;">'.$msg.'</p></div>';
            } else if ($_GET['status'] == 'voziloDodano') {
                $msg = "Vozilo je uspješno dodano!";
                print '<div class="Uspjeh"><p style="font-size:20;">'.$msg.'</p></div>';
            }
        ?>
        <table class="TableRowHover">
            <thead>
                <tr>
                    <th>Proizvođač</th>
                    <th>Model</th>
                    <th>Registracija</th>
                    <th>Datum isteka registracije</th>
                    <th>Vrsta motora</th>
                    <th>Vrsta goriva</th>
                    <th>Stanje goriva</th>
                    <th>Kilometraža</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
            <thead>
            <tbody>

    
            <?php
                // Novi sql query - $link je definiran u conf.php
                $result = $link->query("SELECT * FROM vozila order by id desc");

                // Ako ima zaposlenika, printaj jednog po jednog, red po red
                if ($result->num_rows > 0) {
                    
            
                    while ($row = $result->fetch_assoc()) {                    
                  
                        
                        print '
                            <tr>
                                <td>' . $row["proizvodjac"] . '</td>
                                <td>' . $row["model"] . '</td>
                                <td>' . $row["registracija"] . '</td>
                                <td ';
                        //Označimo vozila čije su registracije istekle
                        if (strtotime($row["datum"]) < time()) {print 'style="background:rgba(255, 255, 0, .4);"';}
                        
                        print '>'. $row["datum"] . '</td>
                                <td>' . $row["motor"] . '</td>
                                <td>' . $row["vrsta_goriva"] . '</td>
                                <td>' . $row["stanje_goriva"] . '</td>
                                <td>' . $row["kilometraza"] . '</td>
                                ';
                        if ($row["status"]=="dostupno") {
                            print '<td style="background:rgba(51, 170, 51, .4);">Dostupno';
                        } elseif ($row["status"]=="posudjeno") {
                            print '<td style="background:rgba(255, 255, 0, .4);">Posuđeno';
                        } elseif ($row["status"]=="popravak") {
                            print '<td style="background:rgba(255, 255, 0, .4);">Na popravku';
                        }
                        
                        print ' </td>
                                <td><a href="update_vozilo.php?id=' . $row["id"] . '">Izmijeni</a></td>
                                <td><a href="delete_vozilo.php?id=' . $row["id"] . '">Briši</a></td>
                            </tr>
                            ';                        
                        }
                }
        
            print ' <tr class="final">
                        <td colspan="9"></td>
                        <td colspan="2"><a href="add_vozilo.php' . $row["id"] . '">Dodaj novo vozilo</a></td>
                    </tr>';
        
            ?>
                
        
            </tbody>    
        </table>
        
             
        
    </body>

</html>