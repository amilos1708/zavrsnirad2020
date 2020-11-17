<?php
// Potrebno zbog provjere logina
session_start();
 
// Je li korisnik već logiran? Ako da, ide na početnu stranicu.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Ovo includeamo put zbog baze.
require_once "config.php";

// Da bismo znali koju stranicu u headeru oznaciti
const PAGE = 'pocetna';
 
// Inicijalizacija (praznih) varijabli
$username = $password = $ime = $prezime = "";
$date_birth = $date_reg = "";
$user_type = "";
$username_err = $password_err = "";
 
// Je li zahtjev već stigao preko POST metode? Ako da, slijedi registracija. Ako ne, printa se obrazac za login (vidi dolje)
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Username unesen? Ima li razmaka s ljeve ili desne strane?
    // U našem slučaju koristimo email adresu kao username!
    if(empty(trim($_POST["username"]))){
        $username_err = "Molim Vas, unesite e-mail adresu.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Password unesen? Ima li razmaka s ljeve ili desne strane?
    if(empty(trim($_POST["password"]))){
        $password_err = "Molim Vas, unesite lozinku.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Još jedna provjera prije kontaktiranja baze - ima li grešaka?
    if(empty($username_err) && empty($password_err)){
        
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){
                
                mysqli_stmt_store_result($stmt);
                
                // Postoji li username (email)? Ako da, provjeri password.
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Ako password postoji, započni session.
                            session_start();
                            
                            // Podaci koje ćemo spremiti u session (pri provjeri usernamea i passworda smo koristili kompliciraniji način zbog sigurnosti)
                            $result = $link->query("SELECT * FROM users WHERE id = ".$id." LIMIT 1");
                            $row = mysqli_fetch_assoc($result);
                            
                            // Spremi podatke u session varijable.
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username; //email
                            $_SESSION["ime"] = $row['ime'];
                            $_SESSION["prezime"] = $row['prezime'];
                            $_SESSION["date_reg"] = $row['date_reg'];
                            $_SESSION["date_birth"] = $row['date_birth'];
                            $_SESSION["user_type"] = $row['user_type'];
                            
                            // U ovom koraku korisnijk je već uspješno logiran. Idi na početnu stranicu.
                            header("location: welcome.php");
                        } else{
                            // Ako password nije točan.
                            $password_err = "Unesena lozinka je netočna.";
                        }
                    }
                } else{
                    // Ako username (email) nije točan.
                    $username_err = "Ne postoji račun registriran unesenom e-mail adresom";
                }
            } else{
                echo "Molim Vas, pokušajte ponovo.";
            }
        
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
    <title>Prijava</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="css.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php
        // Prikaz izbornika
        include "header.php"
    ?>
    <div class="LoginWrapper">
        <h2>Prijava</h2>
        <p>Molim Vas, unesite podatke za prijavu.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>E-mail</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Lozinka</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Prijavite se">
            </div>
            <p>Nemate račun? <a href="register.php">Registrirajte se</a>.</p>
        </form>
    </div>    
</body>
</html>