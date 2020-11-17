<?php

require_once "config.php";
include "header.php";

function frand($min, $max, $decimals = 0) {
  $scale = pow(10, $decimals);
  return mt_rand($min * $scale, $max * $scale) * $scale;
}

echo "frand(0, 1000, 2) = " . frand(0, 1000, 2) . "<br>";


$sql = "SELECT * FROM aktivnosti";
$result = $link->query($sql);

//$sql5 = "SELECT * FROM vozila";
//$result5 = $link->query($sql5);

//$sql8 = "UPDATE vozila SET status=posudjeno";
//$link->query($sql8);

$fname =array();
$age =array();



//$link->query("UPDATE aktivnosti SET kilometraza = '".frand(0,1000,1)."'");



while ($row = $result->fetch_assoc()) {
    
    
        // popunjava redak kilometraže u vozilima random brojevima od 0 do 1000 zaokruženo na 1 decimalu
    
        $ghg=$row['id'];
    
        $link->query("UPDATE aktivnosti SET kilometraza = '".frand(0+$ghg,1+$ghg,1)."' WHERE id = ".$row['id']);
    
        
    
    
        print 'period = '.strtotime($row["period_stop"]).'<br>';
        print_r (date_parse($row["period_stop"]));
        print '<br>vijreme ='.time().'<br>';
        
        if (date_parse($row["period_stop"])['error_count']>0) {
            

            print "GREŠKA u formatu<br><br>";
            
        } else {
            
            if (strtotime($row["period_stop"]) > time()) {
                
                $link->query("UPDATE vozila SET status='posudjeno' WHERE id=".$row['id_vozilo']);
                
                print "promjena<br>";
                
            }
            
            
            print "OK<br><br>";
        }
        

}



$sql2 = "SELECT * FROM vozila";
$result2 = $link->query($sql2);

while ($row2 = $result2->fetch_assoc()) {
    
    $sql3 = "SELECT * FROM aktivnosti WHERE id_vozila=".$row2['id']." ORDER BY id DESC LIMIT 1";
    $result3 = $link->query($sql3);
    
    while ($row3 = $result3->fetch_assoc()) {
        
        $link->query("UPDATE vozila SET kilometraza = '".$row3['kilometraza']."'");
        print 'kukulele<br>';
                
    }
    
    
}





    $c=array_combine($fname,$age);
    print_r($c);
    //print_r($niz);
    print '<br>';
    //print $niz[0][0];
    






    

    



    
    
?>