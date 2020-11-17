<?php
// Potrebno zbog provjere logina
session_start();
 
// Je li korisnik ulogiran? Ako ne, idi na login stranicu.
// Je li ulogirni korisnik admin? Ako ne, prikaži poruku.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Samo admin može dodavati zaposlenike
if($_SESSION["user_type"]==1){
    header("location: welcome.php?status=nematePravo");
    exit;
}

// Ovo includeamo put zbog baze.
require_once "config.php";

// Da bismo znali koju stranicu u headeru oznaciti
const PAGE = 'zaposlenici';


// Inicijalizacija (praznih) varijabli.
$username = $password = $confirm_password = $ime = $prezime = "";
$date_birth = "";
$date_birth_err = "";
$username_err = $password_err = $confirm_password_err = $ime_err = $prezime_err = "";
 
// Je li zahtjev već stigao preko POST metode? Ako da, ide provjera usernamea, passworda itd. Ako ne, printa se obrazac za dodavanje (vidi dolje)
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    
    // Je li nobi zaposleni stariji of 18 godina?
    // datum rođenja - provjera
    
    
    if(empty(trim($_POST["date_birth"]))){
        $date_birth_err = "Molim Vas, unesite datum rođenja.";     
    } elseif (time() < strtotime('+18 years', strtotime($_POST["date_birth"]))) {
        $date_birth_err = "Novi zaposlenik mora biti stariji od 18 godina!";
    } else {
        $date_birth = trim($_POST["date_birth"]);
    }
    
 
    // Username unešen? Ima li razmaka s ljeve ili desne strane?
    // U našem slučaju koristimo email adresu kao username!
    // Varijabla ima naziv username zbog jendostavnosti (uobičajena praksa) iako korisnik u formi vidi da piše "email"
    if(empty(trim($_POST["username"]))){
        $username_err = "Molim Vas, unesite e-mail addresu.";
    } elseif (!filter_var($_POST["username"], FILTER_VALIDATE_EMAIL)) {
        $username_err = "Niste unijeli ispravni format emaila.";
    } else {
        // Ako username (email) polje nije prazo
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            
            // Povezivanje varijabli i parametara
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Pripremanje parametara
            $param_username = trim($_POST["username"]);
            
            // Je li stmt u redu?
            if(mysqli_stmt_execute($stmt)){
                // Spremanje
                mysqli_stmt_store_result($stmt);
                // Postoji li već korisnik s istim emailom?
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "E-mail adresa se već koristi.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Molim Vas, pokušajte ponovo.";
            }

            // Kraj
            mysqli_stmt_close($stmt);
        }
    }
    
    // Je li password OK? Ima li razmaka s ljeve ili desne strane?
    if(empty(trim($_POST["password"]))){
        $password_err = "Molim Vas, unesite lozinku.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Lozinka mora imati najmanje 6 znakova.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Podudaraju li se passwords?
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Lozinka se ne podudara.";
        }
    }
    
    
    // Ime - provjera
    if(empty(trim($_POST["ime"]))){
        $ime_err = "Molim Vas, unesite ime.";     
    } elseif(strlen(trim($_POST["ime"])) < 2){
        $ime_err = "Ime mora imati najmanje 2 znaka.";
    } else{
        $ime = trim($_POST["ime"]);
    }
    
    // Prezime - provjera
    if(empty(trim($_POST["prezime"]))){
        $prezime_err = "Molim Vas, unesite prezime.";     
    } elseif(strlen(trim($_POST["ime"])) < 2){
        $prezime_err = "Prezime mora imati najmanje 2 znaka.";
    } else{
        $prezime = trim($_POST["prezime"]);
    }
    
    
    // Još jedna provjera prije kontaktiranja baze - ima li grešaka?
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($ime_err) && empty($prezime_err) && empty($date_brth_err)){
        
        // Current timestamp in phpmyadmin format
        $date_reg = date('Y-m-d H:i:s', time());
        
        // Moramo hešati password jer je login tako dizajniran
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        // JOš jednom provjera konekcije (iako tehnički nepotrebno jer je config.pho includean)
        if ($link->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //SQL zahtjev
        $sql = "INSERT INTO users (username, password, date_birth, date_reg, ime, prezime, user_type) VALUES ('".$_POST['username']."', '".$hashed_password."', '".$_POST['date_birth']."', '".$date_reg."', '".$_POST['ime']."', '".$_POST['prezime']."', '1')";

        //Kreiraj poruku ako je zaposlenik spješno dodan. Ili error poruku ako ne.
        if ($link->query($sql) === TRUE) {
            header("Location: zaposlenici.php?status=zaposlenikDodan");
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
        <h2>Dodavanje zaposlenika</h2>
        
        <p>Ispunite ovaj obrazac za dodavanje zaposlenika.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
            <div class="form-group <?php echo (!empty($ime_err)) ? 'has-error' : ''; ?>">
                <label>Ime</label>
                <input type="text" name="ime" class="form-control" value="<?php echo $ime; ?>">
                <span class="help-block"><?php echo $ime_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($prezime_err)) ? 'has-error' : ''; ?>">
                <label>Prezime</label>
                <input type="text" name="prezime" class="form-control" value="<?php echo $prezime; ?>">
                <span class="help-block"><?php echo $prezime_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($date_birth_err)) ? 'has-error' : ''; ?>">
                <label>Datum rođenja</label>
                <input type="text" name="date_birth" onfocus = "(this.type = 'date')" name="date_brth" class="form-control" value="<?php echo $date_birth; ?>">
                <span class="help-block"><?php echo $date_birth_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>E-mail</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Lozinka</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Potvrdite lozinku</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Dodaj zaposlenika">
                <input type="reset" class="btn btn-default" value="Briši polja">
            </div>
        </form>
    </div>    
</body>
</html>