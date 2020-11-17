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
const PAGE = 'aktivnosti';

//lista svih zaposlenika
$result_users = $link->query("SELECT * FROM users");

//lista svih zaposlenika
$result_vozila = $link->query("SELECT * FROM vozila");


if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // Jesu li sva polja popunjena
    if (isset($_POST['id_zaposlenik']) and
        !empty($_POST['id_zaposlenik'])
       ){
        
        
        //crtanje htmla u slučaju POST zahtjeva -  urednije nego gurati u kod!
        
        $temp = $_POST['id_zaposlenik']; // Lakše zbog navodnika i mysql verzije
        
        // sve aktivnosti zaposlenika
        $result_aktivnosti = $link->query("SELECT * FROM aktivnosti WHERE id_zaposlenik = '$temp'");
        
        $informacije = '<tr>';
        
        while ($row_aktivnosti = $result_aktivnosti->fetch_assoc()) {
            
            //test
            //print 'EVO: "'.$row_aktivnosti["id"].'" ID Z: "'.$row_aktivnosti["id_zaposlenik"].'", ID V: "'.$row_aktivnosti["id_vozilo"].'"<br><br>';
            
            // ime vozila za svaku aktivnost
            
            $temp2 = $row_aktivnosti["id_vozilo"];
            $result_aktivnosti2 = $link->query("SELECT * FROM vozila WHERE id = '$temp2'");
        
            
            
            while ($row_aktivnosti2 = $result_aktivnosti2->fetch_assoc()) {
                
                //test
                //print 'EVO: "'.$row_aktivnosti2["id"].'" proizvod "'.$row_aktivnosti2["proizvodjac"].'", potrosnja:: "'.$row_aktivnosti["stanje_goriva_stop"].'"<br><br>';
                
                $redak = '<td>'.$row_aktivnosti['period_start'].' - '.$row_aktivnosti['period_stop'].'</td>';
                
                $redak .= '<td>'.$row_aktivnosti2["proizvodjac"].' - '.$row_aktivnosti2["motor"].'</td>';
                
                $redak .= '<td>'.$row_aktivnosti["stanje_goriva_start"].' - '.$row_aktivnosti["stanje_goriva_stop"].'</td>';
                
                // konkatenacija string varijabli
                
            }
            $informacije .= $redak.'</tr>'; 
        }
        
        $informacije .= '';
        
        
        } elseif (!isset($_POST['id_zaposlenik']) or
                empty($_POST['id_zaposlenik'])
               ){

                header("location: aktivnosti.php?status=nedovoljnoPodataka");
                exit;

            }
    

    
//GET METODA - nepotrebna za ovu stranicu, bilo bi previše koda u jednom fileu
    
} 


?>


<!DOCTYPE html>
<html dir="ltr">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aktvnosti</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <link href="css.css" rel="stylesheet" type="text/css">
    </head>

    <body>        
        <?php
            // Prikaz izbornika
            include "header.php"
        ?>

        <h1>Aktivnosti</h1>
        

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class="Activity">
                <thead>
                    <tr>
                        <th>Zaposlenik</th>
                        <th colspan="3"></th>
                    </tr>
                <thead>
                <tbody>
                    <tr class="novaKlasa">
                        <td>
                            <a href="aktivnosti_zaposlenik.php"><div style="height:100%;width:100%">Prikaži statistiku po zaposleniku</div></a>
                        </td>
                        <td style="width:300px;">
                            <a href="aktivnosti_vozilo.php"><div style="height:100%;width:100%">Prikaži statistiku po vozilu</div></a>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="padding-top: 20px;vertical-align: top;">
       
                            <div class="form-group">
                                <select class="form-control" type="text" name="id_zaposlenik">                         
                                    <?php 
                                    
                                    print '<option>Odaberite zaposlenika</option>';
                                    
                                    //printa listu svih usera u drop-down menu
                                    while ($row_user = $result_users->fetch_assoc()) {
                                        print '<option ';
                                        print 'value="'.$row_user["id"].'"';
                                        
                                        if ($_POST['id_zaposlenik'] == $row_user["id"]){
                                            print ' selected';
                                        }
                                            
                                        print ' >'.$row_user["ime"].' '.$row_user["prezime"].' ('.$row_user["username"].')';
                                        print '</option>';
                                    }
                                    
                                    ?>
                                </select>                                            
                            </div>
                            
                            <div class="form-group" style="padding-top: 20px;vertical-align: top;">
                                <input type="submit" class="btn btn-primary" value="Prikaži statistiku">
                                
                                
                            </div>                            
                           
                        </td>
                        <td style="padding-top: 20px;vertical-align: top;" colspan="3">                
                            
                                
                                <table class="Activity" style="width:800px;">
                                    <thead>
                                        <tr>
                                            <th>Period</th>
                                            <th>Vozilo</th>
                                            <th>Potrošnja</th>
                                        </tr>
                                    <thead>
                                    <tbody>
                                        <tr>
                                            
                                       <?php
                                            
                                            if($_SERVER["REQUEST_METHOD"] != "POST"){
                                                print '
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>';
                                            } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {                                                
                                                print $informacije;
                                            } elseif (($_SERVER["REQUEST_METHOD"] == "POST") and !isset($informacije)) {
                                                print '
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>';
                                            }

                                            
                                            ?>
                                            
                                            
                                        </tr>
                                    </tbody>
                                </table>
                                
                                

                                
                       
                        </td>
                    </tr>
                </tbody>    
            </table>
            

            
    
        </form>
        
             
        
    </body>

</html>