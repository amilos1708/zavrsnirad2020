<header>

    <ul>
        <li>
            <a href="index.php"<?php if (PAGE == 'pocetna') print ' class="HeaderMenuHover"'; ?>>Početna</a>
        </li><li>
            <a href="zaposlenici.php"<?php if (PAGE == 'zaposlenici') print ' class="HeaderMenuHover"'; ?>>Zaposlenici</a>
        </li><li>
            <a href="vozila.php"<?php if (PAGE == 'vozila') print ' class="HeaderMenuHover"'; ?>>Vozila</a>
        </li><li>
            <a href="aktivnosti.php"<?php if (PAGE == 'aktivnosti') print ' class="HeaderMenuHover"'; ?>>Pregled aktivnosti</a>
        </li><li>
            <a href="logout.php">Odjava</a>
        </li><?php
            if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true){
                print '<li class ="noHover" stlye="pointer-events: none;"><a class ="noHover" stlye="pointer-events: none;">'.$_SESSION["ime"].', prijavljeni ste kao ';
                if ($_SESSION["user_type"]==0) {
                    print 'admin.</a></li>';      
                } elseif ($_SESSION["user_type"]==1) {
                    print 'zaposlenik.</a></li>';
                }
                
            }
        ?>
    </ul>

</header>