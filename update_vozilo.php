<?php
// Potrebno zbog provjere logina
session_start();
 
// Je li korisnik ulogiran? Ako ne, idi na login stranicu.
// Je li ulogirni korisnik admin? Ako ne, prikaži poruku.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
    
// Je li ulogirni korisnik admin? Ako ne, idi na welcome page.
// user_type: admin=0, zaposlenik = 1
} elseif ($_SESSION["user_type"]!=0) {
    header("location: welcome.php?status=nematePravo");
    exit;
} 

// Ovo includeamo put zbog baze.
require_once "config.php";

// Da bismo znali koju stranicu u headeru oznaciti
const PAGE = 'vozila';

 
// Je li zahtjev već stigao preko POST metode? Ako da, ide provjera usernamea, passworda itd. Ako ne, printa se obrazac za dodavanje (vidi dolje)
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    
    //$id_zaposlenika = $_SESSION['id_zaposlenika'];
    
    // Jesu li sva polja popunjena
    if (
        isset($_POST['proizvodjac']) and
        !empty($_POST['proizvodjac']) and
        isset($_POST['model']) and
        !empty($_POST['model']) and
        isset($_POST['registracija']) and
        !empty($_POST['registracija']) and
        isset($_POST['datum']) and
        !empty($_POST['datum'])and
        isset($_POST['motor']) and
        !empty($_POST['motor']) and
        isset($_POST['vrsta_goriva']) and
        !empty($_POST['vrsta_goriva']) and
        isset($_POST['stanje_goriva']) and
        !empty($_POST['stanje_goriva']) and
        isset($_POST['kilometraza']) and
        !empty($_POST['kilometraza']) and
        isset($_POST['status']) and
        !empty($_POST['status'])
       
       ){
        
               echo 'evoooo - sva polja osim passworda ispunjena!';

        
            $result2 = $link->query("UPDATE vozila SET proizvodjac='". $_POST['proizvodjac'] ."', model='". $_POST['model'] ."', registracija='". $_POST['registracija'] ."', datum='". $_POST['datum'] . "', motor='". $_POST['motor'] . "', vrsta_goriva='". $_POST['vrsta_goriva'] . "', stanje_goriva='". $_POST['stanje_goriva'] . "', kilometraza='". $_POST['kilometraza'] . "', status='". $_POST['status'] . "' WHERE id=". $_POST['id']);


            //Kreiraj poruku ako je vozilo spješno izmijenjeno. Ili error poruku ako ne.
            if ($result2) {
                $status = 'voziloIzmijenjeno';                    
            } else {
                $msg = "Error: " . $sql . "<br>" . $link->error;
            }

            $link->close();

            header("Location: vozila.php?status=".$status);

            die();

            
        
        }   
    
      
    
    
    mysqli_close($link);
    
    
    // Je li zahtjev stigao preko GET metode (linka)?
    //Ako da, popunjavamo obrazac podacima zaposlenika (vidi dolje)
} else {

    $result = $link->query("SELECT * FROM vozila WHERE id=" . $_GET['id']);
    $vozilo = $result->fetch_assoc();
    
    $id_vozila = $vozilo['id'];
    $proizvodjac = $vozilo['proizvodjac'];
    $model = $vozilo['model'];
    $registracija = $vozilo['registracija'];
    $datum = $vozilo['datum'];
    $motor = $vozilo['motor'];
    $vrsta_goriva = $vozilo['vrsta_goriva'];
    $stanje_goriva = $vozilo['stanje_goriva'];
    $kilometraza = $vozilo['kilometraza'];
    $status = $vozilo['status'];
    
    
    $_SESSION['id_vozila'] = $id_vozila;
    
    
    $link->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="css.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php
        // Prikaz izbornika
        include "header.php"
    ?>
    <div class="LoginWrapper">
        <h2>Izmjena vozila</h2>
        <p>Ispunite ovaj obrazac za izmjenu vozila.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
            <div class="form-group <?php echo (!empty($proizvodjac_err)) ? 'has-error' : ''; ?>">
                <label>Proizvođač</label>
                <input type="text" name="proizvodjac" class="form-control" value="<?php echo $proizvodjac; ?>">
                <span class="help-block"><?php echo $proizvodjac_err; ?></span>
            </div>
        
            
            <div class="form-group <?php echo (!empty($model_err)) ? 'has-error' : ''; ?>">
                <label>Model</label>
                <input type="text" name="model" class="form-control" value="<?php echo $model; ?>">
                <span class="help-block"><?php echo $model_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($registracija_err)) ? 'has-error' : ''; ?>">
                <label>Registracija</label>
                <input type="text" name="registracija" class="form-control" value="<?php echo $registracija; ?>">
                <span class="help-block"><?php echo $registracija_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($datum_err)) ? 'has-error' : ''; ?>">
                <label>Datum isteka registracije</label>
                <input type="text" name="datum" onfocus = "(this.type = 'date')" class="form-control" value="<?php echo $datum; ?>">
                <span class="help-block"><?php echo $datum_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($motor_err)) ? 'has-error' : ''; ?>">
                <label>Motor</label>
                <input type="text" name="motor" class="form-control" value="<?php echo $motor; ?>">
                <span class="help-block"><?php echo $motor_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($vrsta_goriva_err)) ? 'has-error' : ''; ?>">
                <label>Vrsta goriva</label>
                <input type="text" name="vrsta_goriva" class="form-control" value="<?php echo $vrsta_goriva; ?>">
                <span class="help-block"><?php echo $vrsta_goriva_err; ?></span>
            </div>
                
            <div class="form-group <?php echo (!empty($stanje_goriva_err)) ? 'has-error' : ''; ?>">
                <label>Stanje goriva</label>
                <input type="text" name="stanje_goriva" class="form-control" value="<?php echo $stanje_goriva; ?>">
                <span class="help-block"><?php echo $stanje_goriva_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($kilometraza_err)) ? 'has-error' : ''; ?>">
                <label>Kilometraza</label>
                <input type="text" name="kilometraza" class="form-control" value="<?php echo $kilometraza; ?>">
                <span class="help-block"><?php echo $kilometraza_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($status_err)) ? 'has-error' : ''; ?>">
                <label>Status vozila</label><br>
                <select class="form-control" type="text" name="status">
                  <option value="dostupno" <?php if ($status=="dostupno") echo 'selected'?>>Dostupno</option>
                  <option value="posudjeno" <?php if ($status=="posudjeno") echo 'selected'?>>Posuđeno</option>
                  <option value="popravak" <?php if ($status=="popravak") echo 'selected'?>>Na popravku</option>
                </select>                
                <span class="help-block"><?php echo $status_err; ?></span>
            </div>
            
            <input type="hidden" name="id" value="<?php print $_GET['id']; ?>">
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Izmijeni vozilo">
                <input type="reset" class="btn btn-default" value="Briši polja">
            </div>
        </form>
    </div>    
</body>
</html>