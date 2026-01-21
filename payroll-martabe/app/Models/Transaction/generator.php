<!DOCTYPE html>
<html>
<body>

<?php  
$alphabet =  
        array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$y = 0;
for ($x = 14; $x <= 319; $x+=5) {
            if ($x < 27) {
             $hasil = $alphabet[$x];
            }
            else if ($x > 26 && $x < 53) {
             $y = $x - 26;
             $hasil = 'A'.$alphabet[$y];
            }  else if ($x > 52 && $x < 79){
                $z = $x - 52;
                $hasil = 'B'.$alphabet[$z];
            } else if ($x > 78 && $x < 105) { 
                $AA = $x - 78;
                $hasil = 'C'.$alphabet[$AA];
            } else if ($x > 104 && $x < 131) {
                $AB = $x - 104;
                $hasil = 'D'.$alphabet[$AB];
            } else if ($x > 130 && $x < 157) {
                $AC = $x - 130;
                $hasil = 'E'.$alphabet[$AC];
            } else if ($x > 156 && $x < 168) {
                $AD = $x - 156;
                $hasil = 'F'.$alphabet[$AD];
            } else if ($x > 168) {
            	$ulang = $x-168;
                $y++;
                $hasil = '';
                if ($y > 0) {
                 $perulangan = $y - 23;
                 if ($perulangan <10)
            {
            	$in = 'in0'.$perulangan;
            } else {
            	$in = 'in'.$perulangan;
            }
                }
            }
            
           echo $in.$hasil.'</br>';
            
        
        }
        
?>  

</body>
</html>
