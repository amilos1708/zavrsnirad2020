<?php

// Podaci za spajanje na bazu
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'zavrsniradv2');
 
// Spajanje na bazu
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Da budmeo sigurni da će browser i baza podržavati naše znakove
$link->query('set character_set_client=utf8');
$link->query('set character_set_connection=utf8');
$link->query('set character_set_results=utf8');
$link->query('set character_set_server=utf8');
 
// Prikazi error ako spajanje nije uspjelo
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>