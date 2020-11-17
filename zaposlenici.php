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
const PAGE = 'zaposlenici';

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
            include "header.php"
        ?>

        <h1>Popis zaposlenika</h1>
       <?php
            if ($_GET['status'] == 'zaposlenikIzmijenjen') {
                $msg = "Zaposlenik je uspješno izmijenjen!";
                print '<div class="Uspjeh"><p>'.$msg.'</p></div>';
            } else if ($_GET['status'] == 'zaposlenikObrisan') {
                $msg = "Zaposlenik je uspješno obrisan!";
                print '<div class="Uspjeh"><p>'.$msg.'</p></div>';
            } else if ($_GET['status'] == 'zaposlenikDodan') {
                $msg = "Zaposlenik je uspješno dodan!";
                print '<div class="Uspjeh"><p>'.$msg.'</p></div>';
            }
        ?>
        <table class="TableRowHover">
            <thead>
                <tr>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>E-mail</th>
                    <th>Datum rođenja</th>
                    <th>Datum registracije</th>
                    <th>Tip korsnika</th>
                    <th></th>
                    <th></th>
                </tr>
            <thead>
            <tbody>

    
            <?php
                // Novi sql query - $link je definiran u conf.php
                $result = $link->query("SELECT * FROM users order by id desc");

                // Ako ima zaposlenika, printaj jednog po jednog, red po red
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {                

                        print '
                            <tr>
                                <td>' . $row["ime"] . '</td>
                                <td>' . $row["prezime"] . '</td>
                                <td>' . $row["username"] . '</td>
                                <td>' . $row["date_birth"] . '</td>
                                <td>' . $row["date_reg"] . '</td>';
                        
                        // Prikaži tip korisnika - admin ii zaposlenik
                        if($row["user_type"]==0)
                           {
                            print '<td>admin</td>';
                        } elseif ($row["user_type"]==1)
                           {
                            print '<td>zaposlenik</td>';
                        }
                        
                        print ' 
                                <td><a href="update_zaposlenik.php?id=' . $row["id"] . '">Izmijeni</a></td>
                                <td><a href="delete_zaposlenik.php?id=' . $row["id"] . '">Briši</a></td>
                            </tr>
                            ';                        
                        }
                }
        
            print ' <tr class="final">
                        <td colspan="6"></td>
                        <td colspan="2"><a href="add_zaposlenik.php' . $row["id"] . '">Dodaj novog zaposlenika</a></td>
                    </tr>';
        
            ?>
                
        
            </tbody>    
        </table>
        
             
        
    </body>

</html>
