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
        !empty($_POST['id_zaposlenik']) and
        isset($_POST['id_vozilo']) and
        !empty($_POST['id_vozilo']) and
        isset($_POST['period_start']) and
        !empty($_POST['period_start']) and
        isset($_POST['period_stop']) and
        !empty($_POST['period_stop']) and
        isset($_POST['stanje_goriva_start']) and
        !empty($_POST['stanje_goriva_start']) and
        isset($_POST['stanje_goriva_stop']) and
        !empty($_POST['stanje_goriva_stop']) and
        isset($_POST['kilometraza']) and
        !empty($_POST['kilometraza'])
       ){

            // redi preglednosti

           /* $id_zaposlenik = $_POST['id_zaposlenik'];
            $id_vozilo = $_POST['id_vozilo'];
            $period_start = $_POST['period_start'];
            $period_stop = $_POST['period_stop'];
            $stanje_goriva_start = $_POST['stanje_goriva_start'];
            $stanje_goriva_stop = $_POST['stanje_goriva_stop'];

            print $_POST['id_zaposlenik'];
            print '<br>';
            print $_POST['id_vozilo'];
            print '<br>';  
            print $_POST['period_start'];
            print '<br>';
            print $_POST['period_stop'];
            print '<br>';
            print $_POST['stanje_goriva_start'];   
            print '<br>';
            print $_POST['stanje_goriva_stop'];
            print '<br>';
        */





        
                //SQL zahtjev
        $sql = "INSERT INTO aktivnosti (id_zaposlenik, id_vozilo, period_start, period_stop, stanje_goriva_start, stanje_goriva_stop) VALUES ('".$_POST['id_zaposlenik']."', '".$_POST['id_vozilo']."', '".$_POST['period_start']."', '".$_POST['period_stop']."', '".$_POST['stanje_goriva_start']."', '".$_POST['stanje_goriva_stop']."')";
        
        $link->query($sql);
        
        $result2 = $link->query("UPDATE vozila SET kilometraza='". $_POST['kilometraza'] ."' WHERE id=". $_POST['id_vozilo']);
    
        if ($link->query($sql) === TRUE and $result2 === TRUE) {
            header("location: aktivnosti.php?status=aktivnostDodana");
            $link->close();
            exit;
        } elseif (strtotime($_POST['period_stop']) < strtotime($_POST['period_start'])) {
            
            // Ako je datum kraja prije datuma početka aktivnosti
            header("location: aktivnosti.php?status=neispravniDatumi");
            $link->close();
            exit;
    
        } else {
            $msg = "Error: " . $sql . "<br>" . $link->error;
            echo $msg;
        }  

        $link->close();


        // suprotno od gore - ako jedan unos fali

        } elseif (!isset($_POST['id_zaposlenik']) or
                empty($_POST['id_zaposlenik']) or
                !isset($_POST['id_vozilo']) or
                empty($_POST['id_vozilo']) or
                !isset($_POST['period_start']) or
                empty($_POST['period_start']) or
                !isset($_POST['period_stop']) or
                empty($_POST['period_stop']) or
                !isset($_POST['stanje_goriva_start']) or
                empty($_POST['stanje_goriva_start']) or
                !isset($_POST['stanje_goriva_stop']) or
                empty($_POST['stanje_goriva_stop']) or
                !isset($_POST['kilometraza']) or
                empty($_POST['kilometraza'])
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

        <h1>Pregled aktivnosti</h1>

                            

                        

                    <table class="Activity" style="width:100%; margin:0;padding:0;">
                                                    <tr>
                        <th>Zaposlenik</th>
                        <th>Vozilo</th>
                        <th>Pređeno kilometara (ukupno)</th>
                        <th>Status aktivnosti</th>
                        <th>Status vozila (trenutno)</th>
                    </tr>
                                
                                    
                                    
                <?php
                    //TableRowHover2
                    //lista svih aktivnosti
                
                    // računanje i spremanje pređenih km po aktivnosti
                    $aaa = $link->query("SELECT * FROM vozila");
                    while ($aaa_row = $aaa->fetch_assoc()) {
                        
                        print 'id vozila = '.$aaa_row['id'].'<br>';
                        
                        
                        $bbb = $link->query("SELECT * FROM aktivnosti WHERE id_vozilo = '".$aaa_row['id']."' ORDER BY id DESC");
                        
                        $razlika = 0;
                        
                        while ($bbb_row = $bbb->fetch_assoc()) {
                            $razlika = $bbb_row['kilometraza'] - $razlika;
                            
                            
                            //print $bbb_row['id'].'aaaaaa<br>';
                            
                            $link->query("UPDATE aktivnosti SET kilometraza = '".$razlika."' WHERE id = ".$bbb_row['id']);
                        }
                        
                        print 'razlika = '.$razlika.'<br><br>';
                        
                        
                        
                    }
                
                    
                    //$razlika = 0;
        
                    $result_akt = $link->query("SELECT * FROM aktivnosti");
                    while ($row_akt = $result_akt->fetch_assoc()) {

                    //$km[ $row_akt["id"] ] = $row_akt["kilometraza"];
                        

                                
                        
                    //za imena zaposlenika i nazivi vozila
                    $boo = $row_akt['id_zaposlenik'];
                    $foo = $row_akt['id_vozilo'];
                        

                    print '<tr>';
  
                        
                        $result_akt2 = $link->query("SELECT * FROM users WHERE id = '".$boo."'");
                        while ($row_akt2 = $result_akt2->fetch_assoc()) {

                            
                            $result_akt3 = $link->query("SELECT * FROM vozila WHERE id = '".$foo."'");
                            while ($row_akt3 = $result_akt3->fetch_assoc()) {
                                
                                //$km[ $row_akt["vozilo_id"] ] = $row_akt['kilometraza'];
                                
                                
                                
                                print '<td>'.$row_akt2['ime'].' '.$row_akt2['prezime'].'</td>';                                
                                print '<td>'.$row_akt3['proizvodjac'].' '.$row_akt3['model'].'</td>';
                                

                                

                    
                                print '<td>';
                                
                                print $row_akt['kilometraza'];

                                
                                
                                print '<br>';
                                print '</td>';
                                
                                
                               /* $result_akt555 = $link->query("SELECT * FROM aktivnosti WHERE id_vozilo = '$foo'");
                                while ($row_akt555 = $result_akt555->fetch_assoc()) {


                                    
                                }

                                
                                */
                                
                                
                                
                                /*if (!empty($row_akt['kilometraza'] or isset($row_akt['kilometraza']) {
                                    print '<td>'.$row_akt['kilometraza'].'</td>';
                                } else {
                                    print 'NOOO';
                                }
                                */
                            
                                
                                if (strtotime($row_akt["period_stop"]) < time()) {
                                    print '<td style="background:rgba(51, 170, 51, .4);">U prošlosti</td>';
                                } else {
                                    print '<td style="background:rgba(255, 255, 0, .4);">U tijeku</td>';                                    
                                }
                                
                                if ($row_akt3["status"]=="dostupno") {
                                    print '<td style="background:rgba(51, 170, 51, .4);">Dostupno</td>';
                                } elseif ($row_akt3["status"]=="posudjeno") {
                                    print '<td style="background:rgba(255, 255, 0, .4);">Posuđeno</td>';
                                } elseif ($row_akt3["status"]=="popravak") {
                                    print '<td style="background:rgba(255, 255, 0, .4);">Na popravku</td>';
                                }
                                
                                
                                
                                
                                
                            }
                            
                        
                            
                            
                        
                                                    
                        }
                        
                        
                        print '</tr>';
                        
                    }
         
                    
                    
                ?>
                                        
                                    
                                
                            </table>
                            
        
             
        
    </body>

</html>