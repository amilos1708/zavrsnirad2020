<?php
// Potrebno zbog provjere logina
session_start();
 
// Je li korisnik ulogiran? Ako ne, idi na login stranicu.
// Je li ulogirni korisnik admin? Ako ne, prikaži poruku.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
    
}

// Samo admin može dodavati vozila
if($_SESSION["user_type"]==1){
    header("location: welcome.php?status=nematePravo");
    exit;
}

// Ovo includeamo put zbog baze.
require_once "config.php";

// Da bismo znali koju stranicu u headeru oznaciti
const PAGE = 'vozila';


// Inicijalizacija (praznih) varijabli.
$proizvodjac = $model = $motor =$registracija = $datum = $vrsta_goriva = $stanje_goriva = $kilometraza = "";
$status = "";
$status_err = "";
$proizvodjac_err = $model_err = $motor_err = $registracija_err = $datum_err = $vrsta_goriva_err = $stanje_goriva_err = $kilometraza_err = "";
 
// Je li zahtjev već stigao preko POST metode? Ako da, ide provjera usernamea, passworda itd. Ako ne, printa se obrazac za dodavanje (vidi dolje)
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // proizvodjac - provjera
    if(empty(trim($_POST["proizvodjac"]))){
        $proizvodjac_err = "Molim Vas, unesite proizvođača.";     
    } elseif(strlen(trim($_POST["proizvodjac"])) < 2){
        $proizvodjac_err = "Proizvođač mora imati najmanje 2 znaka.";
    } else{
        $proizvodjac = trim($_POST["proizvodjac"]);
    }
    
    // model - provjera
    if(empty(trim($_POST["model"]))){
        $model_err = "Molim Vas, unesite model.";     
    } elseif(strlen(trim($_POST["model"])) < 1){
        $model_err = "Model mora imati najmanje 1 znak.";
    } else{
        $model = trim($_POST["model"]);
    }
    
    // registracija - provjera
    if(empty(trim($_POST["registracija"]))){
        $registracija_err = "Molim Vas, unesite registraciju.";     
    } elseif(strlen(trim($_POST["registracija"])) < 2){
        $registracija_err = "Registracija mora imati najmanje 2 znaka.";
    } else{
        $registracija = trim($_POST["registracija"]);
    }
    
    // datum isteka registracije - provjera
    if(empty(trim($_POST["datum"]))){
        $datum_err = "Molim Vas, unesite datum isteka registracije.";     
    } elseif(strlen(trim($_POST["datum"])) < 2){
        $datum_err = "Datum isteka registracije mora imati najmanje 2 znaka.";
    } else{
        $datum = trim($_POST["datum"]);
    }
    
    // motor - provjera
    if(empty(trim($_POST["datum"]))){
        $motor_err = "Molim Vas, unesite vrstu motora.";     
    } elseif(strlen(trim($_POST["motor"])) < 2){
        $motor_err = "Vrsta motora mora imati najmanje 2 znaka.";
    } else{
        $motor = trim($_POST["motor"]);
    }

    // vrsta_goriva - provjera
    if(empty(trim($_POST["vrsta_goriva"]))){
        $vrsta_goriva_err = "Molim Vas, unesite vrstu goriva.";     
    } elseif(strlen(trim($_POST["vrsta_goriva"])) < 2){
        $vrsta_goriva_err = "Vrsta goriva mora imati najmanje 2 znaka.";
    } else{
        $vrsta_goriva = trim($_POST["vrsta_goriva"]);
    }
    
    // stanje_goriva - provjera
    if(empty(trim($_POST["stanje_goriva"]))){
        $stanje_goriva_err = "Molim Vas, unesite stanje goriva.";     
    } elseif(strlen(trim($_POST["stanje_goriva"])) < 2){
        $stanje_goriva_err = "Stanje goriva mora imati najmanje 2 znaka.";
    } else{
        $stanje_goriva = trim($_POST["stanje_goriva"]);
    }
    
    // kilometraza - provjera
    if(empty(trim($_POST["kilometraza"]))){
        $kilometraza_err = "Molim Vas, unesite kilometražu.";     
    } elseif(strlen(trim($_POST["kilometraza"])) < 2){
        $kilometraza_err = "Kilometraža mora imati najmanje 2 znaka.";
    } else{
        $kilometraza = trim($_POST["kilometraza"]);
    }
    
    // status - provjera
    if(empty(trim($_POST["status"]))){
        $status_err = "Molim Vas, unesite status vozila.";     
    } else{
        $status = trim($_POST["status"]);
    }
    
    // Još jedna provjera prije kontaktiranja baze - ima li grešaka?
    if(empty($proizvodjac_err) && empty($model_err) && empty($registracija_err) && empty($datum_err) && empty($motor_err) && empty($vrsta_goriva_err) && empty($stanje_goriva_err) && empty($kilometraza_err) && empty($status_err)){
        
    

        // JOš jednom provjera konekcije (iako tehnički nepotrebno jer je config.pho includean)
        if ($link->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //SQL zahtjev
        $sql = "INSERT INTO vozila (proizvodjac, model, registracija, datum, motor, vrsta_goriva, stanje_goriva, kilometraza, status) VALUES ('".$_POST['proizvodjac']."', '".$_POST['model']."', '".$_POST['registracija']."', '".$_POST['datum']."', '".$_POST['motor']."', '".$_POST['vrsta_goriva']."', '".$_POST['stanje_goriva']."', '".$_POST['kilometraza']."', '".$_POST['status']."')";

        //Kreiraj poruku ako je zaposlenik spješno dodan. Ili error poruku ako ne.
        if ($link->query($sql) === TRUE) {
            header("Location: vozila.php?status=voziloDodano");
        } else {
            $msg = "Error: " . $sql . "<br>" . $link->error;
        }     
      
    }
    
    mysqli_close($link);
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
        include "header.php";
    ?>
    <div class="LoginWrapper">
        <h2>Dodavanje vozila</h2>
        <?php
            if ($status == 'voziloDodano') {
                print '<div class="Uspjeh"><p>'.$msg.'</p></div>';
            }
        ?>
        <p>Ispunite ovaj obrazac za dodavanje vozila.</p>
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
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Dodaj vozilo">
                <input type="reset" class="btn btn-default" value="Briši polja">
            </div>
        </form>
    </div>    
</body>
</html>