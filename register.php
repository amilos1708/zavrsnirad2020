<?php

require_once "config.php";
 
// Inicijalizacija (praznih) varijabli.
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Je li zahtjev već stigao preko POST metode? Ako da, ide provjera usernamea, passworda itd. Ako ne, printa se obrazac za registraciju (vidi dolje)
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Username unešen? Ima li razmaka s ljeve ili desne strane?
    // U našem slučaju koristimo email adresu kao username!
    // Varijabla ima naziv username zbog jendostavnosti (uobičajena praksa) iako korisnik u formi vidi da piše "email"
    if(empty(trim($_POST["username"]))){
        $username_err = "Molim Vas, unesite e-mail addresu.";
    } else{
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
    
    // Još jedna provjera prije kontaktiranja baze - ima li grešaka?
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Povezivanja varijabli i parametara
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Postavljanje paramatara + Hešanje passworda
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Ako je sve OK, idi na login page
            if(mysqli_stmt_execute($stmt)){
                header("location: login.php");
            } else{
                echo "Molim Vas, pokušajte ponovo.";
            }

            // Kraj
            mysqli_stmt_close($stmt);
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
        include "header.php"
    ?>
    <div class="LoginWrapper">
        <h2>Registracija</h2>
        <p>Ispunite ovaj obrazac za stvaranje računa.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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
                <input type="submit" class="btn btn-primary" value="Registrirajte se">
                <input type="reset" class="btn btn-default" value="Briši polja">
            </div>
            <p>Već imate račun? <a href="login.php">Prijavite se ovdje</a>.</p>
        </form>
    </div>    
</body>
</html>