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

 
// Je li zahtjev već stigao preko POST metode? Ako da, ide provjera usernamea, passworda itd. Ako ne, printa se obrazac za dodavanje (vidi dolje)
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Zaposlenik može editirati samo sebe
    if ($_SESSION["id"]!=$_POST['id'] and $_SESSION["user_type"]==1) {
        header("location: welcome.php?status=nematePravo");
        exit;
    } 

    
    // Jesu li sva polja popunjena
    if (isset($_POST['ime']) and
        !empty($_POST['ime']) and
        isset($_POST['prezime']) and
        !empty($_POST['prezime']) and
        isset($_POST['username']) and
        !empty($_POST['username'])){
        
            $result = $link->query("SELECT * FROM users WHERE id=" . $_POST['id']);
            $zaposlenik = $result->fetch_assoc();

            $pasword_zaposlenika = $zaposlenik['password'];
                       
            // podudaraju li se passwordi?
            if ($_POST['password']!=$_POST['confirm_password']) {
                    // Podudaraju li se passwords?
                if(empty(trim($_POST["confirm_password"]))){
                    $confirm_password_err = "Please confirm password.";     
                } else{
                    $confirm_password = trim($_POST["confirm_password"]);
                    if(empty($password_err) && ($password != $confirm_password)){
                        $confirm_password_err = "Lozinka se ne podudara.";
                    }
                }
            } else {
                if (!empty($_POST['confirm_password']) and $_POST['confirm_password']==$_POST['password']) {
                    
                    // ako se passwordi podudaraju
                    // sad ne provjeravamo duljinu passworda
                    
                    $result2 = $link->query("UPDATE users SET password='". $_POST['password'] ."' WHERE id=". $_POST['id']);
                    $status = 'zaposlenikIzmijenjen';
                    header("Location: zaposlenici.php?status=".$status);
                }
                
                

                $result2 = $link->query("UPDATE users SET username='". $_POST['username'] ."', date_birth='". $_POST['date_birth'] ."', ime='". $_POST['ime'] . "', prezime='". $_POST['prezime'] . "' WHERE id=". $_POST['id']);


                //Kreiraj poruku ako je zaposlenik spješno izmijenjen. Ili error poruku ako ne.
                if ($result2) {
                    $status = 'zaposlenikIzmijenjen';                    
                } else {
                    $msg = "Error: " . $sql . "<br>" . $link->error;
                }

                $link->close();

                header("Location: zaposlenici.php?status=".$status);

                die();

            }
        
        } // Kraj provjere "Jesu li sva polja popunjena"
    
    
      
    
    
    mysqli_close($link);
    
    
    // Je li zahtjev stigao preko GET metode (linka)?
    //Ako da, popunjavamo obrazac podacima zaposlenika (vidi dolje)
} else {
    
    // Zaposlenik može editirati samo sebe
    if ($_SESSION["id"]!=$_GET['id'] and $_SESSION["user_type"]==1) {
        header("location: welcome.php?status=nematePravo");
        exit;
    } 

    $result = $link->query("SELECT * FROM users WHERE id=" . $_GET['id']);
    $zaposlenik = $result->fetch_assoc();
    
    $id_zaposlenika = $zaposlenik['id'];
    $username = $zaposlenik['username'];
    $ime = $zaposlenik['ime'];
    $prezime = $zaposlenik['prezime'];
    $date_birth = $zaposlenik['date_birth'];
    
    $_SESSION['id_zaposlenika'] = $id_zaposlenika;
    
    echo $msg;
    
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
        include "header.php";
    ?>
    <div class="LoginWrapper">
        <h2>Izmjena zaposlenika</h2>
        <p>Ispunite ovaj obrazac za izmjenu zaposlenika.</p>
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
                <label>Nova Lozinka</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Potvrdite novu lozinku</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            
            <input type="hidden" name="id" value="<?php print $_GET['id']; ?>">
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Izmijeni zaposlenika">
                <input type="reset" class="btn btn-default" value="Briši polja">
            </div>
        </form>
    </div>    
</body>
</html>