<?php

define("PATH_CAM",     '/var/www/dokuwiki/www/cam304/camera/');


function HumanSize($Bytes)
{
  $Type=array("", "kilo", "mega", "giga", "tera", "peta", "exa", "zetta", "yotta");
  $Index=0;
  while($Bytes>=1024)
  {
    $Bytes/=1024;
    $Index++;
  }
  return("".$Bytes." ".$Type[$Index]."bytes");
}

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

// funct directory to aray 
function camDirToArray($dir)
{
   $dirArr=array_diff(scandir($dir.'/images',1), array('.', '..','crontab.log'));
   
   /*$dirArr=array_filter($dirArr, function($v) {
      return is_dir($v);
   });*/
   array_walk($dirArr, function (&$value, $key) use (&$dir) {
      // $value=str_replace($dir,"/var/www/dokuwiki",".")."/images/$value";
      $value=str_replace("/var/www/dokuwiki","",$dir)."/images/$value";
   });
   return $dirArr;
}

// parse filename to hour 
function parseFilenameToHour($filename)
{
   preg_match('/A(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)(\d\d).jpg/', $filename, $matches);
   return $matches;
}

// file name pic to show
function fileThumbHtml($file_pic = null)
{
   $fDt=parseFilenameToHour($file_pic);
   $val= "<a href='$file_pic' target='_blank'><img src='/www/cam/imgresize.php?src=$file_pic' width='128px' height='72px'/>"
   // . "Year $fDt[1] Month $fDt[2] Day $fDt[3] Hour $fDt[4] Min $fDt[5] Sec $fDt[6]"
   ."</a>";
   return $val;
}

// func to show html directory
function camShowDir($dir)
{
   $val="";
   $arr=camDirToArray($dir);
   $curHour="";
   foreach ($arr as &$value) {
      // . "Year $fDt[1] Month $fDt[2] Day $fDt[3] Hour $fDt[4] Min $fDt[5] Sec $fDt[6]"
      $fDt=parseFilenameToHour($value); //  (!isset($_GET["dt"]))  ? $value : $_GET["dt"] || is_null(parseFilenameToHour($_GET["dt"]))
      if ($curHour!==$fDt[3].$fDt[4]) {
         $curHour=$fDt[3].$fDt[4];
         $val.="<br> Hour $fDt[4] <br>";
      }
      
      $val.= fileThumbHtml($value);
      //$val.= "$value<br> ";
   }
   unset($value); // разорвать ссылку на последний элемент
   return $val;
}


function dirListToArray($path = '.')
{
   # code...
   return  array_diff(scandir($path,1), array('.', '..','crontab.log'));
}

function isweekend($dt)
{
   $result=0;
   # isweekend or not
   $dateObj = \DateTime::createFromFormat("Ymd", $dt);
   if ($dateObj) {
      $result=$dateObj->format("N");
   }
   return $result>5 ? true : false;
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


// echo "<h3> List of all images </h3>\n";

//Get a list of file paths using the glob function.
//$fileList = glob('/var/www/dokuwiki/www/cam304/camera/*');
$fileList = dirToArray(PATH_CAM);
//Loop through the array that glob returned.
/*foreach($fileList as $filename){
   //Simply print them out onto the screen.
   echo $filename, '<br>';
}*/

// echo printImg($fileList,'/www/cam304/camera/');



// ------------- Code ------------------------------------

echo "<h2> Free ".HumanSize(disk_free_space(PATH_CAM))."</h2>";

// dirListToArray -> camshowdir -> (camdir2array) filethumb

foreach (dirListToArray(PATH_CAM) as &$value) {
   // Show only directories
     if ((strpos($value, 'log'))) { continue; }
   echo "<br> <span class='".(isweekend($value)?"weekend":"")."'> Date:<a  href='/www/cam/?page=list&dt=${value}'>${value}".(isweekend($value)?"*":"")."</a></span>";
   // Show pictures on today +yestoday or params dt
   if (
         ( 
           (isset($_GET["dt"]) ? $_GET["dt"] : "") === $value)
             OR
           (!isset($_GET["dt"]) AND ($value===date("Ymd") or $value===date("Ymd", time() - 60 * 60 * 24)))
   ) {
      //implode(" ", camShowDir(PATH_CAM.$value));
      echo camShowDir(PATH_CAM.$value);
   }
}
unset($value); // разорвать ссылку на последний элемент


/*
echo '<pre>';
print_r($fileList);
echo '</pre>';
*/