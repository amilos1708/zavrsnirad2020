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

//lista svih DOSTUPNIH vozila
$result_vozila = $link->query("SELECT * FROM vozila WHERE status='dostupno'");


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
        $sql = "INSERT INTO aktivnosti (id_zaposlenik, id_vozilo, period_start, period_stop, stanje_goriva_start, kilometraza, stanje_goriva_stop) VALUES ('".$_POST['id_zaposlenik']."', '".$_POST['id_vozilo']."', '".$_POST['period_start']."', '".$_POST['period_stop']."', '".$_POST['kilometraza']."', '".$_POST['stanje_goriva_start']."', '".$_POST['stanje_goriva_stop']."')";
        
        //$link->query($sql);
        
        $result2 = $link->query("UPDATE vozila SET kilometraza='". $_POST['kilometraza'] ."' WHERE id=". $_POST['id_vozilo']);

        if (strtotime($_POST["period_stop"]) < time()) {
            $result3 = $link->query("UPDATE vozila SET status='dostupno' WHERE id=". $_POST['id_vozilo']);
        } elseif (strtotime($_POST["period_stop"]) > time() and strtotime($_POST["period_start"]) > time()) {
            $result3 = $link->query("UPDATE vozila SET status='dostupno' WHERE id=". $_POST['id_vozilo']);
        } else {
            $result3 = $link->query("UPDATE vozila SET status='posudjeno' WHERE id=". $_POST['id_vozilo']);
        }
    
        if ($link->query($sql) === TRUE and $result2 === TRUE and $result3 === TRUE) {
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

        <h1>Aktivnosti</h1>
        
        
        <?php
            if ($_GET['status'] == 'nedovoljnoPodataka') {
                $msg = "Nažalost, nije unijeli sve podatke. Pokušajte ponovo.";
                print '<div class="Uspjeh"><p >'.$msg.'</p></div>';
            } elseif ($_GET['status'] == 'aktivnostObrisana') {
                $msg = "Aktivnost je uspješno obrisana!";
                print '<div class="Uspjeh"><p>'.$msg.'</p></div>';
            } elseif ($_GET['status'] == 'aktivnostDodana') {
                $msg = "Aktivnost je uspješno dodana!";
                print '<div class="Uspjeh"><p>'.$msg.'</p></div>';
            } elseif (($_GET['status'] == 'neispravniDatumi')) {
                $msg = "Datum prestanka aktivnosti ne može biti prije datuma početka aktivnosti.";
                print '<div class="Uspjeh"><p>'.$msg.'</p></div>';
            }
        ?>      


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class="Activity">
                <thead>
                    <tr>
                        <th>Zaposlenik</th>
                        <th>Vozilo</th>
                        <th>Period</th>
                        <th>Potrošnja</th>
                    </tr>
                <thead>
                <tbody>
                    <tr class="novaKlasa">
                        <td colspan="1">
                            <a href="aktivnosti_zaposlenik.php"><div style="height:100%;width:100%">Prikaži statistiku po zaposleniku</div></a>
                        </td>
                        <td colspan="1">
                            <a href="aktivnosti_vozilo.php"><div style="height:100%;width:100%">Prikaži statistiku po vozilu</div></a>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="padding-top: 60px;vertical-align: top;">
       
                            <div class="form-group">
                                <select class="form-control" type="text" name="id_zaposlenik">                         
                                    <?php 
                                    
                                    //printa listu svih usera u drop-down menu
                                    while ($row_user = $result_users->fetch_assoc()) {
                                        print '<option value="'.$row_user["id"].'">'.$row_user["ime"].' '.$row_user["prezime"].' ('.$row_user["id"].' - '.$row_user["username"].')</option>';
                                    }
                                    
                                    ?>
                                </select>                                            
                            </div>
                           
                        </td>
                        <td style="padding-top: 60px;vertical-align: top;">
                        
                            <div class="form-group">
                                <select class="form-control" type="text" name="id_vozilo">                         
                                    <?php 
                                    
                                    //printa listu svih DOSTUPNIH vozila u drop-down menu
                                    while ($row_vozilo = $result_vozila->fetch_assoc()) {
                                        print '<option value="'.$row_vozilo["id"].'">'.$row_vozilo["proizvodjac"].' '.$row_vozilo["model"].' ('.$row_vozilo["id"].' - '.$row_vozilo["registracija"].')</option>';
                                    }
                                    
                                    ?>
                                </select>                                            
                            </div>
                        
                        </td>
                        <td style="padding-top: 40px;vertical-align: top;">  
                            
                            <div>
                                <label for="period_start">Početak vožnje:</label><br>
                                <input id="party" type="datetime-local" name="period_start">
                                <span class="validity"></span>
                            </div>
                            <br><br>
                            <div>
                                <label for="period_stop">Kraj vožnje:</label><br>
                                <input id="party" type="datetime-local" name="period_stop">
                                <span class="validity"></span>
                            </div>
                            
                        </td>
                        <td style="padding-top: 40px;vertical-align: top;">
                            
                            <div class="form-group <?php echo (!empty($stanje_goriva_start_err)) ? 'has-error' : ''; ?>">
                                <label>Stanje goriva prije vožnje</label><br>
                                <input type="text" name="stanje_goriva_start" class="form-control" value="<?php echo $stanje_goriva_start; ?>">
                                <span class="help-block"><?php echo $stanje_goriva_start_err; ?></span>
                            </div>

                            <div class="form-group <?php echo (!empty($stanje_goriva_stop_err)) ? 'has-error' : ''; ?>">
                                <br><br><label>Stanje goriva nakon vožnje</label><br>
                                <input type="text" name="stanje_goriva_stop" class="form-control" value="<?php echo $stanje_goriva_stop; ?>">
                                <span class="help-block"><?php echo $stanje_goriva_stop_err; ?></span>
                            </div>
                            
                            <div class="form-group <?php echo (!empty($stanje_goriva_stop_err)) ? 'has-error' : ''; ?>">
                                <br><br><label>Kilometraža nakon vožnje</label><br>
                                <input type="text" name="kilometraza" class="form-control" value="<?php echo $kilometraza; ?>">
                                <span class="help-block"><?php echo $kilometraza_err; ?></span>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="1">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" name="action" value="Dodaj aktivnost">
                                <input type="reset" class="btn btn-default" value="Briši polja">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5"><h2>Povijest aktivnosti</h2></td>
                    </tr>
                    
                    <tr>
                        <td colspan="5">
                            

                        

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
                    $result_akt = $link->query("SELECT * FROM aktivnosti");
                    while ($row_akt = $result_akt->fetch_assoc()) {
                        
                        
                        
                        //za imena zaposlenika i nazivi vozila
                        $boo = $row_akt['id_zaposlenik'];
                        $foo = $row_akt['id_vozilo'];
                        
                        $result_akt2 = $link->query("SELECT * FROM users WHERE id = '$boo'");
                        while ($row_akt2 = $result_akt2->fetch_assoc()) {
                            
                            $result_akt3 = $link->query("SELECT * FROM vozila WHERE id = '$foo'");
                            while ($row_akt3 = $result_akt3->fetch_assoc()) {
                                
                                print '<tr>';
                                
                                print '<td>'.$row_akt2['ime'].' '.$row_akt2['prezime'].'</td>';
                                print '<td>'.$row_akt3['proizvodjac'].' '.$row_akt3['model'].'</td>';
                                print '<td>'.$row_akt3['kilometraza'].'</td>';
                                
                                if (strtotime($row_akt["period_stop"]) < time()) {
                                    print '<td style="background:rgba(51, 170, 51, .4);">U prošlosti</td>';
                                } elseif (strtotime($row_akt["period_stop"]) > time() and strtotime($row_akt["period_start"]) > time()) {
                                    print '<td style="background:rgba(51, 170, 51, .4);">U budućnosti</td>';
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
                                
                                
                                print '</tr>';
                                
                                
                            }
                                                    
                        }
         
                    }
                ?>
                                        
                                    
                                
                            </table>
                            
                        </td>
                    </tr>
                </tbody>    
            </table>
            
            

            
    
        </form>
        
             
        
    </body>

</html>