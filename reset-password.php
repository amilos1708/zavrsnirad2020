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
 
// Inicijalizacija (praznih) varijabli
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Je li zahtjev za promjenu lozinke već poslan putem POST metode? Ako ne, printaj obrazac (vidi dolje).
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Je li novi password OK? Ima li razmaka s ljeve ili desne strane?
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Molim Vas, unesite novu lozinku.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Lozinka mora imati najmanje 6 znakova.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Podudaraju li se oba polja za novu lozinku?
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Molim Vas, potvrdite novu lozinku.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Lozinke se ne podudaraju.";
        }
    }
        
    // Još jedna provjera prije kontaktiranja baze - ima li grešaka?
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Ako je sve OK, radimo sql UPDATE
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Ako je ozinka uspješno promijenjena, uništi session i idi na login stranicu.
            if(mysqli_stmt_execute($stmt)){
                session_destroy();
                header("location: login.php");
                exit();
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
    <title>Promijeni lozinku</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="css.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <?php
        // Prikaz izbornika
        include "header.php"
    ?>
    <div class="LoginWrapper">
        <h2>Promijeni lozinku</h2>
        <p>Molim Vas, ispunite ovaj obrazac kako biste promijenili lozinku.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>Nova lozinka</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Potvrdi lozinku</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Promijeni lozinku">
                <a class="btn btn-link" href="welcome.php">Odustani</a>
            </div>
        </form>
    </div>    
</body>
</html>