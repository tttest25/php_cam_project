<?php

function dirToArray($dir) {
   $result = array();
   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         } 
         else 
         { 
            $result[] = $value; 
         } 
      } 
   } 
   return $result; 
} 

function printImg($v,$pfx = ''){
  $result='';
  if (is_array($v)) {
    foreach($v as $key => $value) {
//     echo "Itter $key $value -";
//     $pfx= is_array($value) ?  $pfx.$key.DIRECTORY_SEPARATOR : $pfx ;
//     echo "PFX $pfx\n";
    if (preg_match("/20\d{6}\b/i", $key)) {
       $result.=is_int($key) ? "<h3>$key</h3>" :"";
    }
     $result.=printImg($value, is_array($value) ?  $pfx.$key.DIRECTORY_SEPARATOR : $pfx );
    }
  } else {
    $result.='<img src="'.$pfx.$v.'" width="200" height="150" alt="lorem">'."\n";
  }
 return $result;
}


echo "<h3> List of all images </h3>\n";

//Get a list of file paths using the glob function.
//$fileList = glob('/var/www/dokuwiki/www/cam304/camera/*');
$fileList = dirToArray('/var/www/dokuwiki/www/cam304/camera/');
//Loop through the array that glob returned.
/*foreach($fileList as $filename){
   //Simply print them out onto the screen.
   echo $filename, '<br>';
}*/
echo printImg($fileList,'/www/cam304/camera/');

/*
echo '<pre>';
print_r($fileList);
echo '</pre>';
*/